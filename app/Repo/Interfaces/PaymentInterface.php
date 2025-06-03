<?php
namespace App\Repo\Interfaces;

interface PaymentInterface{
    public function savePayment($request);

    public function getPayment();
    public function deletePayment($id);
    public function editPayment($id);

    public function updatePayment($request);

    public function invoiceWisePayments($id);
    public function getCustomerAllPayments($id);
}
