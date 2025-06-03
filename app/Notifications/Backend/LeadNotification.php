<?php

namespace App\Notifications\Backend;

use App\Repo\EmailTemplateClass;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class LeadNotification extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    private $lead;
    private $template;
    public function __construct($lead)
    {
        $this->lead = $lead;
        $this->template = new EmailTemplateClass();
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail','database'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->greeting($this->lead['greeting'])
            ->line($this->lead['body'])
            ->action($this->lead['actionText'], $this->lead['actionURL'])
            ->line($this->lead['thanks']);
    }

    public function toDatabase($notifiable)
    {
        $notification = $this->template->getTemplate('lead','database');
        return [
            'message'=> $notification[0]->temp_body,
            'name'=> "Ali",
//            'name'=>$this->lead['first_name'].' '.$this->lead['last_name'],
            'url'=>url('admin/leads'),
            'notifier_id' =>  $this->lead['id'],
        ];
    }
    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
           //
        ];
    }
}
