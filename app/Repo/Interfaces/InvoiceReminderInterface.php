<?php

namespace App\Repo\Interfaces;

interface InvoiceReminderInterface
{
    public function saveInvoiceReminder($request);

    public function getAllInvoiceReminders();

    public function deleteInvoiceReminder($id);

    public function editInvoiceReminder($id);

    public function updateInvoiceReminder($request);

    public function toggleInvoiceReminder($id, $enabled);
}
