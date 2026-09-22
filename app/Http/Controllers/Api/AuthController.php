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
                ->with(['roles', 'profile'])
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

            // Drop previous app tokens for this device-name to avoid token pile-up.
            $user->tokens()->where('name', 'API TOKEN')->delete();

            $token = $user->createToken('API TOKEN')->plainTextToken;

            // Flutter User.fromJson expects status as String?, not a status object.
            $user->loadMissing('status');
            $statusLabel = optional($user->status)->translated_name
                ?? optional($user->status)->name
                ?? 'Active';
            $user->unsetRelation('status');
            $user->unsetRelation('employmentStatus');

            $userPayload = $user->toArray();
            $userPayload['status'] = $statusLabel;

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
