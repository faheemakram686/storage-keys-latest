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
    Route::get('/dashboard', [CustomerHomeController::class, 'index'])->name('dashboard');
    Route::any('update-profile',[CustomerHomeController::class,'updateProfile'])->name('update-profile');
    Route::any('pdf-invoice/{id}',[InvoiceController::class,'pdfInvoice'])->name('pdf-invoice');
    Route::any('print-invoice/{id}',[InvoiceController::class,'printInvoice'])->name('print-invoice');
    Route::any('invoice-to-customer/{id}',[InvoiceController::class,'viewAsCustomerInvoice']);
    Route::any('contract-pdf/{id}', [ContractController::class,'contractPdf'])->name('contract-pdf');
    Route::get('/contract-to-customer/{id}', [ContractController::class, 'contractToCustomer'])->name('contract-customer');


});














