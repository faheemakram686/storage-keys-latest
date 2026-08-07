<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Mail\Contact\ContactPasswordResetMail;
use App\Services\Auth\PasswordResetService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\URL;

class CustomerPasswordResetController extends Controller
{
    protected PasswordResetService $passwordResetService;

    public function __construct(PasswordResetService $passwordResetService)
    {
        $this->passwordResetService = $passwordResetService;
    }

    public function create()
    {
        return view('auth.customer-forgot-password');
    }

    public function store(Request $request)
    {
        $request->validate([
            'email' => ['required', 'email'],
        ]);

        $contact = $this->passwordResetService->findContactByEmail($request->email);

        if (!$contact) {
            return back()
                ->withInput($request->only('email'))
                ->withErrors(['email' => __('passwords.user')]);
        }

        $token = $this->passwordResetService->createToken($contact->email);

        $resetUrl = URL::signedRoute('customer.password.reset', [
            'token' => $token,
            'email' => $contact->email,
        ]);

        try {
            Mail::to($contact)->send(new ContactPasswordResetMail($contact, $resetUrl));
        } catch (\Throwable $exception) {
            report($exception);

            return back()
                ->withInput($request->only('email'))
                ->withErrors(['email' => 'Unable to send password reset email. Please try again later.']);
        }

        return back()->with('status', __('passwords.sent'));
    }

    public function show(Request $request)
    {
        $request->validate([
            'token' => 'required|min:10',
            'email' => 'required|email',
        ]);

        $contact = $this->passwordResetService->findContactByEmail($request->email);

        $token = $this->passwordResetService->findToken($contact?->email ?? $request->email, $request->token);

        if (!$contact || !$this->passwordResetService->tokenIsValid($token)) {
            return redirect()
                ->route('customer.password.request')
                ->withErrors(['email' => __('passwords.token')]);
        }

        return view('auth.customer-reset-password', [
            'email' => $contact->email,
            'token' => $request->token,
        ]);
    }

    public function update(Request $request)
    {
        $request->validate([
            'token' => ['required', 'min:10'],
            'email' => ['required', 'email'],
            'password' => ['required', 'confirmed', 'min:8'],
        ]);

        $contact = $this->passwordResetService->findContactByEmail($request->email);

        $token = $this->passwordResetService->findToken($contact?->email ?? $request->email, $request->token);

        if (!$contact || !$this->passwordResetService->tokenIsValid($token)) {
            return back()
                ->withInput($request->only('email'))
                ->withErrors(['email' => __('passwords.token')]);
        }

        $contact->update([
            'password' => Hash::make($request->password),
        ]);

        $this->passwordResetService->deleteToken($contact->email);

        return redirect()
            ->route('customer.login')
            ->with('status', __('passwords.reset'));
    }
}
