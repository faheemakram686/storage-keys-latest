<?php

namespace App\Mail;

use App\Models\Inquiry;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class InquiryMail extends Mailable
{
    use Queueable, SerializesModels;

    public Inquiry $inquiry;

    public function __construct(Inquiry $inquiry)
    {
        $this->inquiry = $inquiry;
    }

    public function build()
    {
        $name = trim((string) $this->inquiry->name) ?: 'Website visitor';

        return $this->subject('New storage inquiry from ' . $name)
            ->replyTo($this->inquiry->email, $name)
            ->view('emails.inquiry');
    }
}
