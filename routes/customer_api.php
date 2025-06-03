<?php

use App\Http\Controllers\Cutomer_api\AuthController;

use App\Http\Controllers\Core\Auth\User\UserUpdateController;

use Illuminate\Http\Request;

use App\Http\Controllers\Backend\ContractController;
use App\Http\Controllers\Backend\InvoiceController;
use App\Http\Controllers\Backend\MoveInRequestController;
use App\Http\Controllers\Backend\PaymentController;
use App\Http\Controllers\Customer\CustomerHomeController;

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
Route::post('/customer/register', [AuthController::class, 'createCustomer']);
Route::post('/customer/login', [AuthController::class, 'loginCustomer']);


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
    Route::post('/payment-list',[PaymentController::class, 'getCustomerPaymentsApi']);
    Route::post('/move-in-request',[MoveInRequestController::class, 'saveMoveInRequestApi']);
    Route::post('/move-out-request',[MoveInRequestController::class, 'saveMoveInRequestApi']);
    Route::post('/reset-password',[CustomerHomeController::class, 'resetPassword'])->middleware('auth:sanctum');

});