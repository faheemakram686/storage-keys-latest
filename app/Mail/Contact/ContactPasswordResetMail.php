<?php

namespace App\Mail\Contact;

use App\Models\Contact;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ContactPasswordResetMail extends Mailable
{
    use Queueable, SerializesModels;

    protected Contact $contact;

    protected string $resetUrl;

    public function __construct(Contact $contact, string $resetUrl)
    {
        $this->contact = $contact;
        $this->resetUrl = $resetUrl;
    }

    public function build()
    {
        $name = trim($this->contact->first_name . ' ' . $this->contact->last_name);

        return $this->subject('Reset your password - ' . config('app.name'))
            ->view('emails.contact-password-reset', [
                'name' => $name ?: 'Customer',
                'resetUrl' => $this->resetUrl,
            ]);
    }
}
