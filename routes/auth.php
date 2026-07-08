<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\ConfirmablePasswordController;
use App\Http\Controllers\Auth\EmailVerificationNotificationController;
use App\Http\Controllers\Auth\EmailVerificationPromptController;
use App\Http\Controllers\Auth\AdminPasswordResetController;
use App\Http\Controllers\Auth\CustomerPasswordResetController;
use App\Http\Controllers\Auth\PasswordResetLinkController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Auth\VerifyEmailController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Backend\ContactController;
use App\Http\Controllers\Auth\CustomerLoginController;
use App\Http\Controllers\Auth\CustomerRegisterController;

Route::middleware('guest:contact')->group(function () {
    Route::get('customer/register', [CustomerRegisterController::class, 'create'])
        ->name('customer.register');

    Route::post('customer/register', [CustomerRegisterController::class, 'register'])
        ->name('customer.register');

    Route::get('customer-login', [CustomerLoginController::class, 'customerLoginForm'])
        ->name('customer.login');

    Route::post('/customer/login', [CustomerLoginController::class, 'customerLogin'])
        ->name('customer.login.post');

    Route::get('customer/forgot-password', [CustomerPasswordResetController::class, 'create'])
        ->name('customer.password.request');

    Route::post('customer/forgot-password', [CustomerPasswordResetController::class, 'store'])
        ->name('customer.password.email');

    Route::get('customer/reset-password', [CustomerPasswordResetController::class, 'show'])
        ->name('customer.password.reset')
        ->middleware('signed');

    Route::post('customer/reset-password', [CustomerPasswordResetController::class, 'update'])
        ->name('customer.password.update');
});

Route::middleware('guest')->group(function () {
    Route::get('register', [RegisteredUserController::class, 'create'])
                ->name('register');

    Route::post('register', [RegisteredUserController::class, 'store'])->name('register.store');

    Route::get('login', [AuthenticatedSessionController::class, 'create'])
                ->name('login');

    Route::post('login', [AuthenticatedSessionController::class, 'store']);

    Route::get('forgot-password', [PasswordResetLinkController::class, 'create'])
                ->name('password.request');

    Route::post('forgot-password', [PasswordResetLinkController::class, 'store'])
                ->name('password.email');

    Route::get('reset-password', [AdminPasswordResetController::class, 'show'])
                ->name('admin.password.reset')
                ->middleware('signed');

    Route::post('reset-password', [AdminPasswordResetController::class, 'update'])
                ->name('admin.password.update');
});

Route::middleware('auth')->group(function () {

    Route::get('verify-email', [EmailVerificationPromptController::class, '__invoke'])
                ->name('verification.notice');

    Route::get('verify-email/{id}/{hash}', [VerifyEmailController::class, '__invoke'])
                ->middleware(['signed', 'throttle:6,1'])
                ->name('verification.verify');

    Route::post('email/verification-notification', [EmailVerificationNotificationController::class, 'store'])
                ->middleware('throttle:6,1')
                ->name('verification.send');

    Route::get('confirm-password', [ConfirmablePasswordController::class, 'show'])
                ->name('password.confirm');

    Route::post('confirm-password', [ConfirmablePasswordController::class, 'store']);

    Route::post('logout', [AuthenticatedSessionController::class, 'destroy'])
                ->name('logout');

});

Route::middleware('auth:contact')->group(function () {
    Route::post('logout', [CustomerLoginController::class, 'logout'])
        ->name('all.logout');
});
