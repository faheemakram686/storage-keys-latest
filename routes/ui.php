<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Frontend\HomeController;
use App\Http\Controllers\Frontend\BookingController;
use App\Http\Controllers\Frontend\LeadController;
use App\Http\Controllers\Backend\CityController;
use App\Http\Controllers\Backend\LocationController;
use App\Http\Controllers\Backend\WarehouseController;
use App\Http\Controllers\Backend\EstimateController;
use App\Http\Controllers\TestingController;
use App\Http\Controllers\Backend\ContractController;
use App\Http\Controllers\Backend\ContactController;
use App\Http\Controllers\Backend\InvoiceController;
use App\Http\Controllers\Frontend\CartController;
use App\Http\Controllers\Frontend\CheckoutController;
use App\Http\Controllers\Frontend\OrderController;


require __DIR__.'/auth.php';


Route::middleware(['set.guard'])->group(function () {
    // Your protected routes here...

// Frontend Routes
    Route::get('/', [HomeController::class, 'index'])->name('index');
    Route::get('/notify', [HomeController::class, 'notify'])->name('notify');
    Route::get('/storage-options', [HomeController::class, 'storageOption'])->name('storageOption');
    Route::get('/shop', [HomeController::class, 'shop'])->name('shop');
    Route::get('/product-details', [HomeController::class, 'productDetails'])->name('productDetails');
    Route::any('/product-detail', [HomeController::class, 'productDetail'])->name('productDetails');
    Route::get('/booking', [HomeController::class, 'booking'])->name('booking');
    Route::get('/reservation/{id}', [HomeController::class, 'bookingReservation'])->name('booking-res');
    Route::get('/blogs', [HomeController::class, 'blogs'])->name('blogs');
    Route::get('/about-us', [HomeController::class, 'aboutUs'])->name('aboutUs');
    Route::get('/contact-us', [HomeController::class, 'contactUs'])->name('contactUs');
    Route::get('/blog-details', [HomeController::class, 'blogDetails'])->name('blogDetails');
    Route::get('/estimatetocustomer/{id}', [EstimateController::class, 'estimateToCustomer'])->name('estimate-customer');
    Route::get('/contract-to-customer/{id}', [ContractController::class, 'contractToCustomer'])->name('contract-customer');
    Route::any('estimate-upload-document/{id}', [EstimateController::class, 'showUploadDocuments'])->name('show-upload-document');
    Route::any('upload-estimate-documents', [EstimateController::class, 'uploadDocuments'])->name('upload-document');
//sign contract and download contract
    Route::post('sign-contract', [ContractController::class, 'signContract'])->name('contract.sign');
    Route::any('contract-pdf/{id}', [ContractController::class, 'contractPdf'])->name('contract-pdf');

//booking filter routes
    Route::any('/get-cities', [CityController::class, 'getCountryBaseCity'])->name('country-cities');
    Route::any('/get-locations', [LocationController::class, 'getCityBaseLocation'])->name('cities-location');
    Route::any('/get-warehouse', [WarehouseController::class, 'getLocBaseWarehouse'])->name('location-warehouse');
    Route::any('/get-warehouse', [WarehouseController::class, 'getLocBaseWarehouse'])->name('location-warehouse');
    Route::any('/get-storageunit', [BookingController::class, 'getSunitWarehouseWise'])->name('warehouse-storageunit');
    Route::any('/country-wise', [BookingController::class, 'countrywise'])->name('country-wise');
    Route::any('/send', [TestingController::class, 'send'])->name('test-email');

    Route::get('cart', [CartController::class, 'cartList'])->name('cart.list');
    Route::post('cart', [CartController::class, 'addToCart'])->name('cart.store');
    Route::get('update-cart', [CartController::class, 'updateCart'])->name('cart.update');
    Route::post('remove', [CartController::class, 'removeCart'])->name('cart.remove');
    Route::post('clear', [CartController::class, 'clearAllCart'])->name('cart.clear');
    Route::post('apply-coupon', [CartController::class, 'applyCoupon'])->name('apply.coupon');
});
    //checkout
    Route::get('checkout', [CheckoutController::class, 'checkout'])->name('checkout')->middleware(['customer','set.guard']);
    Route::post('save-order', [OrderController::class, 'save'])->name('order.save');

//lead save
    Route::any('/save-lead', [LeadController::class, 'saveLead'])->name('save-lead');


Route::get("/test", function(){
      return view("test");;
});

    Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
        return $request->user();
    });


    Route::get('contact-setpassword/{id}', [ContactController::class, 'setPassword'])
        ->name('contact.password.reset');
    Route::any('contact-savepassword', [ContactController::class, 'savePassword'])
        ->name('password.save');












