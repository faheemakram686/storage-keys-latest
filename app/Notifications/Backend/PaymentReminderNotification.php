<?php

namespace App\Notifications\Backend;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class PaymentReminderNotification extends Notification
{
    use Queueable;

    private $payment;

    public function __construct($payment)
    {
        $this->payment = $payment;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        $mail = (new MailMessage)
            ->subject($this->payment['subject'] ?? 'Invoice reminder')
            ->view('backend.emails.invoice-reminder', [
                'body' => $this->payment['body'] ?? '',
            ]);

        if (!empty($this->payment['from_email'])) {
            $mail->from($this->payment['from_email'], $this->payment['from_name'] ?? null);
        }

        if (!empty($this->payment['cc'])) {
            $mail->cc($this->payment['cc']);
        }

        if (!empty($this->payment['bcc'])) {
            $mail->bcc($this->payment['bcc']);
        }

        return $mail;
    }

    public function toArray($notifiable)
    {
        return [];
    }
}
