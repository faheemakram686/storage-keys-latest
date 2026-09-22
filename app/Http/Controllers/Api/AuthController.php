<?php

namespace App\Http\Controllers\Api;

use App\Models\Core\Auth\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
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
                'message' => 'User Created Successfully',
                'token' => $user->createToken("API TOKEN")->plainTextToken
            ], 200);

        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ], 500);
        }
    }
    public function loginUser(Request $request)
    {
        try {
            $validateUser = Validator::make($request->all(),
                [
                    'email' => 'required|email',
                    'password' => 'required'
                ]);

            if($validateUser->fails()){
                return response()->json([
                    'status' => false,
                    'message' => 'validation error',
                    'errors' => $validateUser->errors()
                ], 401);
            }

            $email = strtolower(trim((string) $request->email));

            // Match web login: look up active users with Hash::check.
            // Do NOT use Auth::attempt() here — the API middleware group has no
            // session store, so the session guard attempt often fails for app clients.
            $user = User::query()
                ->whereRaw('LOWER(email) = ?', [$email])
                ->whereHas('status', function ($builder) {
                    $builder->whereNotIn('name', ['status_inactive', 'status_invited']);
                })
                ->with(['roles', 'profile', 'status', 'employmentStatus'])
                ->first();

            if (!$user || !Hash::check((string) $request->password, (string) $user->password)) {
                return response()->json([
                    'status' => false,
                    'message' => 'Email & Password does not match with our record.',
                ], 401);
            }

            $employment = $user->employmentStatus;
            $employmentAlias = null;
            if (is_object($employment)) {
                $employmentAlias = $employment->alias
                    ?? (method_exists($employment, 'first') ? optional($employment->first())->alias : null);
            }
            if ($employmentAlias === 'terminated') {
                return response()->json([
                    'status' => false,
                    'message' => 'Your employment has been terminated. Please contact HR.',
                ], 403);
            }

            if (!$user->roles()->exists()) {
                return response()->json([
                    'status' => false,
                    'message' => 'No roles found for this user. Please contact admin.',
                ], 403);
            }

            // Drop previous app tokens for this device-name to avoid token pile-up.
            $user->tokens()->where('name', 'API TOKEN')->delete();

            return response()->json([
                'user' => $user,
                'status' => true,
                'message' => 'User Logged In Successfully',
                'token' => $user->createToken('API TOKEN')->plainTextToken
            ], 200);

        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ], 500);
        }
    }
}