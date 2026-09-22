<?php

namespace App\Http\Controllers\Api;

use App\Models\Core\Auth\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function getUser(Request $request)
    {
       return User::find($request->id);
    }

    public function createUser(Request $request)
    {

        try {
            //Validated
            $validateUser = Validator::make($request->all(),
                [
                    'name' => 'required',
                    'email' => 'required|email|unique:users,email',
                    'password' => 'required',
                ]);

            if($validateUser->fails()){
                return response()->json([
                    'status' => false,
                    'success' => false,
                    'message' => 'validation error',
                    'errors' => $validateUser->errors()
                ], 401);
            }

            $user = User::create([
                'first_name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'status_id' =>1,
            ]);

            return response()->json([
                'user' => $user,
                'status' => true,
                'success' => true,
                'message' => 'User Created Successfully',
                'token' => $user->createToken("API TOKEN")->plainTextToken
            ], 200);

        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'success' => false,
                'message' => $th->getMessage()
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
