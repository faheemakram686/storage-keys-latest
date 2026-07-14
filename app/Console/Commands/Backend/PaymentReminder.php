<?php

namespace App\Console\Commands\Backend;

use App\Models\Invoice;
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
        $invoices = Invoice::with(array_merge([
            'customer.contact',
        ], Invoice::paymentStatusRelations()))
            ->where('is_deleted', 0)
            ->get();

        foreach ($invoices as $invoice) {
            $invoice->syncPaymentStatus();

            if ($invoice->isFullyPaid() || !$invoice->customer?->contact?->email) {
                continue;
            }

            $email= [
                'greeting' => 'Hi '.$invoice->customer->contact->first_name.' '.$invoice->customer->contact->last_name.',',
                'body' => "Its reminder for you.<br> Your payment pending please pay your pending payments",
                'thanks' => 'Thank you this is from storage Key',
                'actionText' => 'Visit Storage Keys',
                'actionURL' => url('/'),
                'id' => $invoice->id,

            ];
            Notification::route('mail', $invoice->customer->contact->email)->notify(new PaymentReminderNotification($email));
        }
        return $this->info('Payment Reminder of the Day sent to All Users');
    }
};;
