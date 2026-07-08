<?php

namespace App\Http\Controllers\Core\Auth\User;

use App\Exceptions\GeneralException;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Core\Auth\User\traits\PasswordResetTrait;
use App\Http\Requests\Core\Auth\User\PasswordResetRequest as Request;
use App\Http\Requests\Core\Auth\User\ResetPasswordRequest;
use App\Mail\Core\User\PasswordResetMail;
use App\Models\Core\Auth\User;
use App\Services\Auth\PasswordResetService;
use Illuminate\Http\Request as BaseRequest;
use Illuminate\Support\Facades\Mail;

class PasswordResetController extends Controller
{
    use PasswordResetTrait;

    protected PasswordResetService $passwordResetService;

    public function __construct(PasswordResetService $passwordResetService)
    {
        $this->passwordResetService = $passwordResetService;
    }

    public function index()
    {
        return view('frontend.user.password_reset');
    }

    public function store(Request $request)
    {
        /** @var User $user */
        ['user' => $user] = $this->tokenAndUser($request->get('email'));

        if (!$user) {
            return response()->json(['status' => false, 'message' => trans('default.no_user_found_on_that_email')], 404);
        }

        $token = $this->passwordResetService->createToken($request->get('email'));

        try {
            Mail::to($user)->send(new PasswordResetMail($user, $token));
        } catch (\Throwable $exception) {
            report($exception);

            return response()->json([
                'status' => false,
                'message' => 'Unable to send password reset email. Please try again later.',
            ], 500);
        }

        return response()->json(['status' => true, 'message' => trans('default.password_reset_mail_has_been_sent_successfully')]);
    }

    public function show(BaseRequest $request)
    {
        $request->validate([
            'token' => 'required|min:10',
            'email' => 'required|email',
        ]);

        ['user' => $user, 'token' => $token] = $this->tokenAndUser($request->get('email'), $request->get('token'));

        if (!$token || !$user || !$this->passwordResetService->tokenIsValid($token)) {
            throw new GeneralException(trans('default.invalid_token'));
        }

        return view('frontend.user.reset_password', ['user' => $user, 'token' => $request->get('token')]);
    }

    public function update(ResetPasswordRequest $request)
    {
        /** @var User $user */
        ['user' => $user, 'token' => $token] = $this->tokenAndUser($request->get('email'), $request->get('token'));

        if (!$token || !$user || !$this->passwordResetService->tokenIsValid($token)) {
            throw new GeneralException(trans('default.invalid_token'));
        }

        $user->update([
            'password' => $request->get('password'),
        ]);

        $this->passwordResetService->deleteToken($request->get('email'));

        auth()->login($user);

        return response()->json([
            'status' => true,
            'message' => trans('default.password_has_been_reset_successfully'),
            'redirect' => route(home_route()['route_name'], ['params' => home_route()['route_params']]),
        ]);
    }
}
