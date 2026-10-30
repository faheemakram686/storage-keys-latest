<?php

namespace App\Http\Controllers\Api;

use App\Models\Core\Auth\Role;
use App\Models\Core\Auth\User;
use App\Models\EmployeeAccountDeletion;
use App\Repositories\Core\Status\StatusRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class AuthController extends Controller
{
    public function getUser(Request $request)
    {
       return User::find($request->id);
    }

    /**
     * Mobile API: an employee signs up from the app.
     * The account gets the employee role but stays inactive (pending
     * approval), so it cannot log in until an admin activates it and sets
     * the department, designation and employment status.
     */
    public function createUser(Request $request)
    {
        try {
            // Older app builds send a single "name" field.
            if (!$request->filled('first_name') && $request->filled('name')) {
                $request->merge(['first_name' => $request->input('name')]);
            }

            $validateUser = Validator::make($request->all(),
                [
                    'first_name' => ['required', 'string', 'max:100'],
                    'last_name' => ['nullable', 'string', 'max:100'],
                    'email' => ['required', 'email', 'max:160', Rule::unique('users', 'email')],
                    'phone' => ['nullable', 'string', 'max:30'],
                    'password' => ['required', 'string', 'min:8', 'confirmed'],
                ]);

            if($validateUser->fails()){
                return response()->json([
                    'status' => false,
                    'success' => false,
                    'message' => 'validation error',
                    'errors' => $validateUser->errors()
                ], 422);
            }

            $employeeRole = Role::query()->where('alias', 'employee')->first();

            if (!$employeeRole) {
                return response()->json([
                    'status' => false,
                    'success' => false,
                    'message' => 'Sign up is not available. Please contact admin.',
                ], 500);
            }

            $user = DB::transaction(function () use ($request, $employeeRole) {
                // phone and the legacy status column are not mass assignable;
                // access is controlled by status_id.
                $user = new User();
                $user->forceFill([
                    'first_name' => trim($request->first_name),
                    'last_name' => $request->filled('last_name') ? trim($request->last_name) : null,
                    'email' => strtolower(trim($request->email)),
                    'phone' => $request->filled('phone') ? trim($request->phone) : null,
                    'password' => Hash::make($request->password),
                    'status_id' => resolve(StatusRepository::class)->userInactive(),
                    'status' => 0,
                    'invitation_token' => '',
                    'is_in_employee' => 1,
                ])->save();

                $user->roles()->attach($employeeRole->id);

                return $user;
            });

            Log::info('api.auth.register.success', [
                'email' => $user->email,
                'user_id' => $user->id,
                'ua' => $request->userAgent(),
                'ip' => $request->ip(),
            ]);

            return response()->json([
                'user' => [
                    'id' => $user->id,
                    'first_name' => $user->first_name,
                    'last_name' => $user->last_name,
                    'email' => $user->email,
                ],
                'status' => true,
                'success' => true,
                'pending_approval' => true,
                'message' => 'Your account has been created. You can log in once an admin approves it.',
            ], 201);

        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'success' => false,
                'message' => $th->getMessage()
            ], 500);
        }
    }

    /**
     * Mobile API: reasons the app can show on the delete account screen.
     */
    public function deleteAccountReasons()
    {
        $reasons = collect(EmployeeAccountDeletion::REASONS)
            ->map(fn ($label, $key) => ['key' => $key, 'label' => $label])
            ->values();

        return response()->json([
            'data' => $reasons,
            'status' => true,
            'success' => true,
            'message' => 'Account deletion reasons',
        ], 200);
    }

    /**
     * Mobile API: the logged-in employee deletes their own account.
     * The user is soft deleted (attendance, leave and payroll records are
     * kept) and their email is released so it can sign up again; the
     * original email is kept in employee_account_deletions. All API tokens
     * are revoked.
     */
    public function deleteAccount(Request $request)
    {
        try {
            $user = $request->user();

            if (!$user instanceof User) {
                return response()->json([
                    'status' => false,
                    'success' => false,
                    'message' => 'Unauthorized',
                ], 401);
            }

            // The app admin must not lock everyone out by deleting themselves.
            if ($user->isAppAdmin()) {
                return response()->json([
                    'status' => false,
                    'success' => false,
                    'message' => 'Admin accounts cannot be deleted from the app.',
                ], 403);
            }

            $validator = Validator::make($request->all(), [
                'password' => ['required', 'string'],
                'reason'   => ['required', 'in:' . implode(',', array_keys(EmployeeAccountDeletion::REASONS))],
                'comments' => ['required_if:reason,other', 'nullable', 'string', 'max:1000'],
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'success' => false,
                    'message' => 'validation error',
                    'errors' => $validator->errors(),
                ], 422);
            }

            if (!Hash::check((string) $request->password, (string) $user->password)) {
                return response()->json([
                    'status' => false,
                    'success' => false,
                    'message' => 'validation error',
                    'errors' => ['password' => ['The password is incorrect.']],
                ], 422);
            }

            DB::transaction(function () use ($request, $user) {
                EmployeeAccountDeletion::create([
                    'user_id'    => $user->id,
                    'email'      => $user->email,
                    'reason'     => $request->reason,
                    'comments'   => $request->comments,
                    'ip_address' => $request->ip(),
                    'user_agent' => substr((string) $request->userAgent(), 0, 255),
                ]);

                $user->tokens()->delete();

                // users.email is unique at DB level, so free it for a future sign up.
                $user->email = substr('deleted_' . $user->id . '_' . time() . '_' . $user->email, 0, 160);
                $user->status_id = resolve(StatusRepository::class)->userInactive();
                $user->is_deleted = 1;
                $user->save();

                $user->delete();
            });

            return response()->json([
                'status' => true,
                'success' => true,
                'message' => 'Your account has been deleted successfully.',
            ], 200);

        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'success' => false,
                'message' => $th->getMessage(),
            ], 500);
        }
    }

    public function loginUser(Request $request)
    {
        try {
            // Mobile apps sometimes send "username" instead of "email".
            if (!$request->filled('email') && $request->filled('username')) {
                $request->merge(['email' => $request->input('username')]);
            }

            $validateUser = Validator::make($request->all(),
                [
                    'email' => 'required|email',
                    'password' => 'required'
                ]);

            if($validateUser->fails()){
                Log::warning('api.auth.login.validation_failed', [
                    'keys' => array_keys($request->except(['password'])),
                    'email' => $request->input('email'),
                    'ua' => $request->userAgent(),
                    'origin' => $request->headers->get('Origin'),
                    'ip' => $request->ip(),
                    'errors' => $validateUser->errors()->toArray(),
                ]);

                return response()->json([
                    'status' => false,
                    'success' => false,
                    'message' => 'validation error',
                    'errors' => $validateUser->errors()
                ], 401);
            }

            $email = strtolower(trim((string) $request->email));

            // Match web login: look up active users with Hash::check.
            // Do NOT use Auth::attempt() here — the API middleware group has no
            // session store, so the session guard attempt often fails for app clients.
            // Keep payload close to the old login shape (no nested status relation)
            // so mobile parsers that expect a flat user object do not break.
            $user = User::query()
                ->whereRaw('LOWER(email) = ?', [$email])
                ->whereHas('status', function ($builder) {
                    $builder->whereNotIn('name', ['status_inactive', 'status_invited']);
                })
                ->first();

            if (!$user || !Hash::check((string) $request->password, (string) $user->password)) {
                Log::warning('api.auth.login.invalid_credentials', [
                    'email' => $email,
                    'ua' => $request->userAgent(),
                    'origin' => $request->headers->get('Origin'),
                    'ip' => $request->ip(),
                    'user_found' => (bool) $user,
                ]);

                return response()->json([
                    'status' => false,
                    'success' => false,
                    'message' => 'Email & Password does not match with our record.',
                ], 401);
            }

            // Use relation query (not $user->employmentStatus) so we do not attach
            // nested objects that break the Flutter User model.
            $employmentAlias = optional(
                $user->employmentStatus()->first()
            )->alias;
            if ($employmentAlias === 'terminated') {
                Log::warning('api.auth.login.terminated', [
                    'email' => $email,
                    'user_id' => $user->id,
                ]);

                return response()->json([
                    'status' => false,
                    'success' => false,
                    'message' => 'Your employment has been terminated. Please contact HR.',
                ], 403);
            }

            if (!$user->roles()->exists()) {
                Log::warning('api.auth.login.no_roles', [
                    'email' => $email,
                    'user_id' => $user->id,
                ]);

                return response()->json([
                    'status' => false,
                    'success' => false,
                    'message' => 'No roles found for this user. Please contact admin.',
                ], 403);
            }

            // Keep the latest few tokens valid so the app does not get 401 if it
            // briefly still uses a previous Bearer after a re-login / race.
            $newAccessToken = $user->createToken('API TOKEN');
            $token = $newAccessToken->plainTextToken;
            $keepIds = $user->tokens()
                ->where('name', 'API TOKEN')
                ->latest('id')
                ->take(5)
                ->pluck('id');
            $user->tokens()
                ->where('name', 'API TOKEN')
                ->whereNotIn('id', $keepIds)
                ->delete();

            // Flutter User.fromJson expects flat String?/int? fields only.
            // Nested roles/profile/status objects cause Map→String? parse crashes.
            $user->loadMissing('status');
            $statusLabel = optional($user->status)->translated_name
                ?? optional($user->status)->name
                ?? 'Active';

            $userPayload = [
                'id' => $user->id,
                'first_name' => $user->first_name,
                'last_name' => $user->last_name,
                'firstName' => $user->first_name,
                'lastName' => $user->last_name,
                'email' => $user->email,
                'avatar' => $user->avatar,
                'phone' => $user->phone,
                'address' => $user->address,
                'user_type' => $user->user_type,
                'userType' => $user->user_type,
                'status' => $statusLabel,
                'is_deleted' => (int) ($user->is_deleted ?? 0),
                'last_login_at' => optional($user->last_login_at)->toJSON(),
                // Flutter expects String? createdBy — never send int.
                'created_by' => $user->created_by === null ? null : (string) $user->created_by,
                'createdBy' => $user->created_by === null ? null : (string) $user->created_by,
                'status_id' => (int) $user->status_id,
                'invitation_token' => $user->invitation_token === null || $user->invitation_token === ''
                    ? null
                    : (string) $user->invitation_token,
                'created_at' => optional($user->created_at)->toJSON(),
                'updated_at' => optional($user->updated_at)->toJSON(),
                'deleted_at' => optional($user->deleted_at)->toJSON(),
                'is_in_employee' => (int) ($user->is_in_employee ?? 0),
                'full_name' => $user->full_name,
                'fullName' => $user->full_name,
            ];

            Log::info('api.auth.login.success', [
                'email' => $email,
                'user_id' => $user->id,
                'ua' => $request->userAgent(),
                'origin' => $request->headers->get('Origin'),
                'ip' => $request->ip(),
            ]);

            return response()->json([
                'user' => $userPayload,
                'status' => true,
                'success' => true,
                'message' => 'User Logged In Successfully',
                'token' => $token,
            ], 200);

        } catch (\Throwable $th) {
            Log::error('api.auth.login.exception', [
                'message' => $th->getMessage(),
                'email' => $request->input('email'),
                'ua' => $request->userAgent(),
                'origin' => $request->headers->get('Origin'),
                'ip' => $request->ip(),
            ]);

            return response()->json([
                'status' => false,
                'success' => false,
                'message' => $th->getMessage()
            ], 500);
        }
    }
}
