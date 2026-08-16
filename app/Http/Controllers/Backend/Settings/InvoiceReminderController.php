<?php

namespace App\Http\Controllers\Backend\Settings;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Repo\Interfaces\InvoiceReminderInterface;
use Illuminate\Http\Request;

class InvoiceReminderController extends Controller
{
    private $reminders;

    public function __construct(InvoiceReminderInterface $reminders)
    {
        $this->reminders = $reminders;
    }

    public function index()
    {
        $users = User::query()
            ->where('is_deleted', 0)
            ->orderBy('first_name')
            ->get(['id', 'first_name', 'last_name', 'email']);

        return view('backend.settings.invoice-reminders.index')->with(compact('users'));
    }

    public function saveInvoiceReminder(Request $request)
    {
        return $this->reminders->saveInvoiceReminder($request);
    }

    public function getInvoiceReminders()
    {
        return $this->reminders->getAllInvoiceReminders();
    }

    public function deleteInvoiceReminder(Request $request)
    {
        $this->reminders->deleteInvoiceReminder($request->id);

        return response()->json(['success' => 'Record deleted successfully'], 200);
    }

    public function editInvoiceReminder(Request $request)
    {
        $reminder = $this->reminders->editInvoiceReminder($request->id);

        return response()->json($reminder);
    }

    public function updateInvoiceReminder(Request $request)
    {
        $this->reminders->updateInvoiceReminder($request);

        return response()->json(['success' => 'Record updated successfully'], 200);
    }

    public function toggleInvoiceReminder(Request $request)
    {
        $this->reminders->toggleInvoiceReminder($request->id, $request->is_enabled);

        return response()->json(['success' => 'Reminder status updated'], 200);
    }
}
