<?php
namespace App\Repo\Interfaces;

interface InvoiceInterface{
    public function saveInvoice($request);
    public function getAllInvoices();
    public function deleteInvoice($id);
    public function editInvoice($id);
    public function getInvoice($id);
    public function updateInvoice($request);
    public function convertInvoice($request);
    public function getInvoiceItems($id);
    public function getCustomerInvoices($id);
    public function getCustomerInvoicesApi($id);
    public function generateOrderInvoice($id);



}
