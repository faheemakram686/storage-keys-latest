<?php

namespace App\Http\Requests\Auth;

use App\Models\Contact;
use App\Models\User;
use Illuminate\Auth\Events\Lockout;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class LoginRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'email' => ['required', 'string', 'email'],
            'password' => ['required', 'string'],
        ];
    }

    public function authenticate()
    {
        $this->ensureIsNotRateLimited();

        if (Auth::guard('web')->attempt($this->credentials(), $this->boolean('remember'))) {
            RateLimiter::clear($this->throttleKey());
            return;
        }

        if ($this->attemptCustomerLogin()) {
            RateLimiter::clear($this->throttleKey());
            return;
        }

        RateLimiter::hit($this->throttleKey());

        throw ValidationException::withMessages([
            'email' => 'Username or password incorrect',
        ]);
    }

    public function authenticateCustomer()
    {
        $this->ensureIsNotRateLimited();

        if (!$this->attemptCustomerLogin()) {
            RateLimiter::hit($this->throttleKey());

            throw ValidationException::withMessages([
                'email' => 'Username or password incorrect',
            ]);
        }

        RateLimiter::clear($this->throttleKey());
    }

    public function loggedInViaContactGuard(): bool
    {
        return Auth::guard('contact')->check();
    }

    protected function attemptCustomerLogin(): bool
    {
        $contact = $this->findContactForLogin();

        if (!$contact || !Hash::check($this->input('password'), $contact->getAuthPassword())) {
            return false;
        }

        Auth::guard('contact')->login($contact, $this->boolean('remember'));

        return true;
    }

    protected function findContactForLogin(): ?Contact
    {
        return Contact::query()
            ->whereRaw('LOWER(email) = ?', [Str::lower($this->input('email'))])
            ->where('is_deleted', 0)
            ->where('status', 1)
            ->first();
    }

    protected function credentials(): array
    {
        return $this->only('email', 'password');
    }

    public function ensureIsNotRateLimited()
    {
        if (!RateLimiter::tooManyAttempts($this->throttleKey(), 5)) {
            return;
        }

        event(new Lockout($this));

        $seconds = RateLimiter::availableIn($this->throttleKey());

        throw ValidationException::withMessages([
            'email' => trans('auth.throttle', [
                'seconds' => $seconds,
                'minutes' => ceil($seconds / 60),
            ]),
        ]);
    }

    public function throttleKey()
    {
        return Str::lower($this->input('email')).'|'.$this->ip();
    }
}
