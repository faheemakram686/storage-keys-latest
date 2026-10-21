<?php

namespace App\Notifications\Backend;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class RecurringChargeFailedNotification extends Notification
{
    use Queueable;

    public function __construct(private array $payload)
    {
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        $invoiceNo = $this->payload['invoice_no'] ?? '';
        $amount = number_format((float) ($this->payload['amount'] ?? 0), 2);
        $url = $this->payload['invoice_url'] ?? url('/');
        $reason = $this->payload['reason'] ?? 'Automatic payment could not be completed';

        return (new MailMessage)
            ->subject($this->payload['subject'] ?? 'Invoice payment needs your attention')
            ->greeting('Hello,')
            ->line("We could not complete the automatic payment for invoice {$invoiceNo} (AED {$amount}).")
            ->line($reason)
            ->action('Pay invoice now', $url)
            ->line('If you already paid, you can ignore this email.');
    }

    public function toArray($notifiable)
    {
        return $this->payload;
    }
}
