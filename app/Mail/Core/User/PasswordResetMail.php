<?php

namespace App\Mail\Core\User;

use App\Mail\Tag\UserTag;
use App\Models\Core\Auth\User;
use App\Notifications\Core\Helper\NotificationTemplateHelper;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\URL;

class PasswordResetMail extends Mailable
{
    use SerializesModels;

    protected User $user;

    protected string $token;

    public function __construct(User $user, $token, $deliverySettings = [])
    {
        $this->user = $user;
        $this->token = $token;
    }

    public function build()
    {
        $tag = new UserTag($this->user);
        $template = $this->template();

        if ($template) {
            return $this->view('notification.mail.user.template', [
                'template' => $template->parse(
                    $tag->passwordReset($this->token)
                ),
            ])->subject($template->parseSubject(
                method_exists($tag, 'passwordResetSubject') ? $tag->passwordResetSubject() : ['{name}' => $this->user->full_name]
            ));
        }

        $resetUrl = URL::signedRoute('admin.password.reset', [
            'token' => $this->token,
            'email' => $this->user->email,
        ]);

        return $this->subject('Reset your password - ' . config('app.name'))
            ->view('emails.contact-password-reset', [
                'name' => $this->user->full_name ?: 'User',
                'resetUrl' => $resetUrl,
            ]);
    }

    public function template()
    {
        try {
            return NotificationTemplateHelper::new()
                ->on('password_reset')
                ->mail();
        } catch (\Throwable $exception) {
            report($exception);

            return null;
        }
    }
}
