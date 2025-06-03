<?php

namespace App\Notifications\Backend;

use App\Repo\EmailTemplateClass;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ContractApprovalNotification extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     *
     * @return void
     */

    private $user;
    private $contract;
    private $email;
    private $template;
    public function __construct($email,$user,$contract)
    {
        $this->template = new EmailTemplateClass();
        $this->email = $email;
        $this->user = $user;
        $this->contract = $contract;
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
            ->greeting($this->email['greeting'])
            ->line($this->email['body'])
            ->action($this->email['actionText'], $this->email['actionURL'])
            ->line($this->email['thanks']);
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        $notification = $this->template->getTemplateByName('contract','database','contract_approval');
        return [
            'message'=> $notification[0]->temp_body,
            'name'=>$this->user['first_name'].' '.$this->user['last_name'],
            'url'=> url('admin/contract/detail').'/'.$this->contract->id,
            'notifier_id' => $this->user->id,
        ];
    }
}
