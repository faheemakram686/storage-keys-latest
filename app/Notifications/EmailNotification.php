<?php

namespace App\Notifications;

use App\Repo\EmailTemplateClass;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class EmailNotification extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    private $template;
    public function __construct($project,$lead_owner)
    {
        $this->project = $project;
        $this->lead_owner = $lead_owner;
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
            ->greeting($this->project['greeting'])
            ->line($this->project['body'])
            ->action($this->project['actionText'], $this->project['actionURL'])
            ->line($this->project['thanks']);
    }

    public function toDatabase($notifiable)
    {
        $notification = $this->template->getTemplate('lead','database');
        return [
            'message'=> $notification[0]->temp_body,
            'name'=> $this->lead_owner['email'],
            'url'=>url('admin/leads'),
            'notifier_id' =>  $this->project['id']
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
