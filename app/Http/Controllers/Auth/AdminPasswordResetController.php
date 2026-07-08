<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Core\Auth\User;
use App\Services\Auth\PasswordResetService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminPasswordResetController extends Controller
{
    protected PasswordResetService $passwordResetService;

    public function __construct(PasswordResetService $passwordResetService)
    {
        $this->passwordResetService = $passwordResetService;
    }

    public function show(Request $request)
    {
        $request->validate([
            'token' => 'required|min:10',
            'email' => 'required|email',
        ]);

        $user = $this->passwordResetService->findUserByEmail(
            $request->email,
            User::class
        );

        $token = $this->passwordResetService->findToken($request->email, $request->token);

        if (!$user || !$this->passwordResetService->tokenIsValid($token)) {
            return redirect()
                ->route('password.request')
                ->withErrors(['email' => __('passwords.token')]);
        }

        return view('auth.admin-reset-password', [
            'email' => $request->email,
            'token' => $request->token,
        ]);
    }

    public function update(Request $request)
    {
        $request->validate([
            'token' => ['required', 'min:10'],
            'email' => ['required', 'email'],
            'password' => ['required', 'min:8', 'confirmed'],
        ]);

        $user = $this->passwordResetService->findUserByEmail(
            $request->email,
            User::class
        );

        $token = $this->passwordResetService->findToken($request->email, $request->token);

        if (!$user || !$this->passwordResetService->tokenIsValid($token)) {
            return back()
                ->withInput($request->only('email'))
                ->withErrors(['email' => __('passwords.token')]);
        }

        $user->update([
            'password' => $request->password,
        ]);

        $this->passwordResetService->deleteToken($request->email);

        Auth::guard('web')->login($user);
        $request->session()->regenerate();

        return redirect('/admin')->with('status', __('passwords.reset'));
    }
}
