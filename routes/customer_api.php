<?php

use App\Http\Controllers\Cutomer_api\AuthController;

use App\Http\Controllers\Core\Auth\User\UserUpdateController;

use Illuminate\Http\Request;

use App\Http\Controllers\Backend\ContractController;
use App\Http\Controllers\Backend\EstimateController;
use App\Http\Controllers\Backend\InvoiceController;
use App\Http\Controllers\Backend\MoveInRequestController;
use App\Http\Controllers\Backend\PaymentController;
use App\Http\Controllers\Customer\CustomerHomeController;
use App\Http\Controllers\Frontend\OrderController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
//auth api
Route::post('/customer/register', [AuthController::class, 'registerCustomer']);
Route::post('/customer/login', [AuthController::class, 'loginCustomer']);
Route::post('/customer/forgot-password', [AuthController::class, 'forgotPasswordCustomer']);


//Route::apiResource('users',[AuthController::class, 'users'])->middleware('auth:sanctum');

Route::middleware('auth:sanctum')->get('/users', function (Request $request) {
    return $request->user();
});



Route::middleware(['auth:sanctum'])->group(function () {

    //user related api
    Route::post('/profile',[AuthController::class, 'getCustomer']);
    Route::post('/customer',[AuthController::class, 'dashboardApi']);
    Route::post('/update-profile',[CustomerHomeController::class, 'updateProfileApi']);
    Route::post('/contract-list',[ContractController::class, 'getCustomerContractsApi']);
    Route::post('/invoice-list',[InvoiceController::class, 'getCustomerInvoicesApi']);
    Route::post('/pay-invoice',[InvoiceController::class, 'payInvoiceApi']);
    Route::post('/verify-payment',[InvoiceController::class, 'verifyPaymentApi']);
    Route::post('/order-list',[OrderController::class, 'getCustomerOrdersApi']);
    Route::post('/payment-list',[PaymentController::class, 'getCustomerPaymentsApi']);
    Route::post('/move-in-request',[MoveInRequestController::class, 'saveMoveInRequestApi']);
    Route::post('/move-out-request',[MoveInRequestController::class, 'saveMoveInRequestApi']);

    //estimate document upload (mobile app)
    Route::post('/estimate-list',[EstimateController::class, 'getCustomerEstimatesApi']);
    Route::post('/estimate-required-documents',[EstimateController::class, 'getEstimateRequiredDocumentsApi']);
    Route::post('/upload-estimate-documents',[EstimateController::class, 'uploadEstimateDocumentsApi']);
    Route::post('/reset-password',[CustomerHomeController::class, 'resetPassword'])->middleware('auth:sanctum');

});