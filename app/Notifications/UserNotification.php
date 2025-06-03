<?php

namespace App\Notifications;

use App\Repo\EmailTemplateClass;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class UserNotification extends Notification
{
    use Queueable;

    private $user;
    private $template;
    private $notification;
    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($user)
    {
        $this->user = $user;
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
        return ['database'];
    }


    public function toArray($notifiable)
    {
        $notification = $this->template->getTemplate('project','database');

        return [
        'message'=> $notification[0]->temp_body,
        'name'=>$this->user['first_name'].' '.$this->user['last_name'],
        'url'=>url('notifications'),
        'notifier_id' =>  $this->user['id'],

//            'user_id' => $this->user['id'],
//            'first_name' => $this->user['first_name'],
//            'last_name' => $this->user['last_name'],
//            'email' => $this->user['email'],
        ];
    }
}
