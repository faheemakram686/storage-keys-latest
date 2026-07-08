<?php

namespace App\Models\Core\Auth\Traits;

use App\Mail\Core\User\PasswordResetMail;
use App\Services\Auth\PasswordResetService;
use Illuminate\Support\Facades\Mail;

/**
 * Class SendUserPasswordReset.
 */
trait SendUserPasswordReset
{
    /**
     * Send the password reset notification.
     *
     * @param string $token
     */
    public function sendPasswordResetNotification($token)
    {
        $passwordResetService = app(PasswordResetService::class);
        $resetToken = $passwordResetService->createToken($this->email);

        Mail::to($this)->send(new PasswordResetMail($this, $resetToken));
    }
}
