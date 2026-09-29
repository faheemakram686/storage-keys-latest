<?php

namespace App\Http\Controllers\Core\Auth\User;

use App\Http\Controllers\Controller;
use App\Models\Core\Auth\Profile;
use App\Services\Core\Auth\UserService;
use App\Http\Requests\Core\Auth\User\UserSettingRequest;
use App\Models\Core\Auth\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class UserUpdateController extends Controller
{
    public function __construct(UserService $service)
    {
        $this->service = $service;
    }

    public function index()
    {
        return Profile::query()
            ->where('user_id', auth()->id())
            ->first();
    }

    /**
     * App Personal tab reads: message.user.* and message.profile.*
     */
    public function editUserApi()
    {
        try {
            $user = User::query()->where('id', auth()->id())->first();
            if (!$user) {
                return response()->json([
                    'status' => false,
                    'message' => 'Unauthenticated.',
                ], 401);
            }

            $profile = Profile::query()->firstOrCreate(
                ['user_id' => $user->id],
                [
                    'gender' => null,
                    'date_of_birth' => null,
                    'address' => null,
                    'contact' => null,
                    'about_me' => null,
                ]
            );

            return response()->json([
                'status' => true,
                'message' => [
                    'user' => [
                        'id' => $user->id,
                        'first_name' => $user->first_name,
                        'last_name' => $user->last_name,
                        'email' => $user->email,
                    ],
                    'profile' => [
                        'user_id' => $profile->user_id,
                        'gender' => $profile->gender,
                        'contact' => $profile->contact,
                        'date_of_birth' => $profile->date_of_birth,
                        'about_me' => $profile->about_me,
                        'address' => $profile->address,
                    ],
                ],
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage(),
            ], 500);
        }
    }

    public function update(UserSettingRequest $request)
    {
        $this->service->validateIsNotDemoVersion();

        auth()->user()->update(
            $request->only('first_name', 'last_name', 'email')
        );

        Profile::query()->updateOrCreate([
            'user_id' => auth()->id(),
        ], array_merge(
            ['user_id' => auth()->id()],
            $request->only('gender', 'date_of_birth', 'address', 'contact', 'about_me')
        ));

        return updated_responses('profile');
    }

    /**
     * Mobile/API Personal tab save.
     * Persists user + profile, then echoes the same fields back (200).
     */
    public function updateApi(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'first_name' => 'required|string|min:2|max:100',
            'last_name' => 'nullable|string|max:100',
            'email' => 'required|email|unique:users,email,' . auth()->id() . ',id',
            'gender' => 'required|in:male,female',
            'date_of_birth' => 'nullable|date',
            'contact' => 'nullable|string|max:40',
            'about_me' => 'nullable|string|max:2000',
            'address' => 'nullable|string|max:500',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => $validator->errors(),
            ], 422);
        }

        try {
            $user = auth()->user();
            if (!$user) {
                return response()->json([
                    'status' => false,
                    'message' => 'Unauthenticated.',
                ], 401);
            }

            // National number only — strip UAE country code if the client still sends it.
            $contact = preg_replace('/\s+/', '', (string) $request->input('contact', ''));
            $contact = preg_replace('/^\+?971/', '', $contact);

            $user->update([
                'first_name' => $request->input('first_name'),
                'last_name' => $request->input('last_name'),
                'email' => $request->input('email'),
            ]);

            $profile = Profile::query()->updateOrCreate(
                ['user_id' => $user->id],
                [
                    'user_id' => $user->id,
                    'gender' => $request->input('gender'),
                    'date_of_birth' => $request->input('date_of_birth') ?: null,
                    'contact' => $contact !== '' ? $contact : null,
                    'about_me' => $request->input('about_me'),
                    'address' => $request->input('address'),
                ]
            );

            $payload = [
                'first_name' => $user->first_name,
                'last_name' => $user->last_name,
                'email' => $user->email,
                'gender' => $profile->gender,
                'date_of_birth' => $profile->date_of_birth,
                'contact' => $profile->contact,
                'about_me' => $profile->about_me,
            ];

            return response()->json(array_merge([
                'status' => true,
                'message' => 'Profile updated successfully',
                'data' => $payload,
            ], $payload), 200);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage(),
            ], 500);
        }
    }
}
