<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Customer\CustomerHomeController;
use App\Http\Controllers\Auth\CustomerLoginController;
use App\Http\Controllers\Backend\InvoiceController;
use App\Http\Controllers\Backend\ContractController;
use App\Http\Controllers\Backend\ContactController;

require __DIR__.'/auth.php';




// Customer Routes
Route::middleware(['auth:contact'])->prefix('customer')->name('customer.')->group(function () {
    Route::get('/dashboard', [CustomerHomeController::class, 'index'])
        ->middleware('contact.permission:view_portal')
        ->name('dashboard');
    Route::any('update-profile',[CustomerHomeController::class,'updateProfile'])
        ->middleware('contact.permission:update_account|view_portal')
        ->name('update-profile');
    Route::middleware('hashid')->group(function () {
        Route::any('pdf-invoice/{id}',[InvoiceController::class,'pdfInvoice'])
            ->middleware('contact.permission:view_invoices')
            ->name('pdf-invoice');
        Route::any('print-invoice/{id}',[InvoiceController::class,'printInvoice'])
            ->middleware('contact.permission:view_invoices')
            ->name('print-invoice');
        Route::any('invoice-to-customer/{id}',[InvoiceController::class,'viewAsCustomerInvoice'])
            ->middleware('contact.permission:view_invoices');
        Route::any('contract-pdf/{id}', [ContractController::class,'contractPdf'])
            ->middleware('contact.permission:view_contracts')
            ->name('contract-pdf');
        Route::get('/contract-to-customer/{id}', [ContractController::class, 'contractToCustomer'])
            ->middleware('contact.permission:view_contracts')
            ->name('contract-customer');
        Route::any('pay-now/{id}',[InvoiceController::class,'payNowByCustomer'])
            ->middleware('contact.permission:pay_invoices');
    });
    Route::any('/redirect-response',[InvoiceController::class,'saveResponse'])->name('invoice.redirect-response');

});














