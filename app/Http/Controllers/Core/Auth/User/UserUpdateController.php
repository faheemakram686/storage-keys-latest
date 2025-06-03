<?php

namespace App\Http\Controllers\Core\Auth\User;

use App\Http\Controllers\Controller;
use App\Models\Core\Auth\Profile;
use App\Services\Core\Auth\UserService;
use App\Http\Requests\Core\Auth\User\UserSettingRequest as Request;
use App\Models\Core\Auth\User;
use Illuminate\Support\Facades\Response;
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
    public function editUserApi()
    {
        try {
            $data['profile'] = Profile::query()
                ->where('user_id', auth()->id())
                ->first();
            $data['user'] = User::query()
                ->where('id', auth()->id())
                ->first();

            return response()->json([
                'status' => true,
                'message' => $data
            ],200);

        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ], 500);
        }
    }

    public function update(Request $request)
    {

        $this->service->validateIsNotDemoVersion();

        auth()->user()->update(
            $request->only('first_name', 'last_name', 'email')
        );

        Profile::query()->updateOrCreate([
            'user_id' => auth()->id()
        ], array_merge(
            ['user_id' => auth()->id()],
            $request->only('gender', 'date_of_birth', 'address', 'contact', 'about_me')
        ));

        return updated_responses('profile');
    }
    public function updateApi(Request $request)
    {
        return $request->all();
        $validator = Validator::make($request->all(), [
            'first_name' => 'required',
            'last_name' => 'required',

        ]);

        if ($validator->fails()) {

            $responseArr['message'] = $validator->errors();;
            $responseArr['token'] = '';
            return response()->json($responseArr,500);
        }
//
//
//        try {
////        $this->service->validateIsNotDemoVersion();
//
        auth()->user()->update(
            $request->only('first_name', 'last_name', 'email')
        );

        Profile::query()->updateOrCreate([
            'user_id' => auth()->id()
        ], array_merge(
            ['user_id' => auth()->id()],
            $request->only('gender', 'date_of_birth', 'address', 'contact', 'about_me')
        ));

        return updated_responses('profile');
//        } catch (\Throwable $th) {
//            return response()->json([
//                'status' => false,
//                'message' => $th->getMessage()
//            ], 500);
//        }
    }
}
