<?php

namespace App\Console\Commands\Backend;

use App\Models\Invoice;
use App\Notifications\Backend\EstimateApprovalNotification;
use App\Notifications\Backend\PaymentReminderNotification;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Notification;

class PaymentReminder extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'payment:reminder';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send a Daily email to all users with a reminder of pending payments';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $qry = Invoice::with('customer.contact');
        $qry = $qry->where('payment_status','Unpaid');
        $qry = $qry->where('is_deleted',0);
        $data['customer'] = $qry->get();
        foreach ($data['customer'] as $user) {
            $email= [
                'greeting' => 'Hi '.$user->customer->contact->first_name.' '.$user->customer->contact->last_name.',',
                'body' => "Its reminder for you.<br> Your payment pending please pay your pending payments",
                'thanks' => 'Thank you this is from storage Key',
                'actionText' => 'Visit Storage Keys',
                'actionURL' => url('/'),
                'id' => $user->id,

            ];
            Notification::route('mail', $user->customer->contact->email)->notify(new PaymentReminderNotification($email));
        }
        return $this->info('Payment Reminder of the Day sent to All Users');
    }
};;
