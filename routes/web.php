<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\QuoteController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\LocationController;




Route::get('blog', function () {
    return view('Blogs.blog');
});
Route::get( 'review', function () {
    return view('SubPages.Review');
});
Route::get( 'international-shipping', function () {
    return view('SubPages.International_shipping');
});
Route::get( 'domestic-shipping', function () {
    return view('SubPages.Domestic_shipping');
});
Route::get('delivery', function () {
    return view('SubPages.delivery_service');
});
Route::get('couriers', function () {
    return view('SubPages.couriers');
});
Route::get('/', function () {
    return view('mainpage.home');
});
Route::get('contact', function () {
    return view('ContactUs.contact');
});



Route::get('/sample', function () {
    return view('query.locate');
});

Route::get('/about', function () {
    return view('AboutUs.about');
});
Route::get('/dev/2k24', function () {
    return view('sample');
});

// Route::get('/', [QuoteController::class, 'countryDb'])->name('retrieveShipments');
Route::get('/getToken', [QuoteController::class, 'getToken'])->name('hello');
Route::get('/getQuote', [QuoteController::class, 'getQuote'])->name('retrieveShipments');

Route::post('/shipment', [QuoteController::class, 'shipping'])->name('shipPage');


Route::post('/createShipment', [QuoteController::class, 'createdShipment'])->name('createdShipment');
Route::post('/createFullQuote', [QuoteController::class, 'getFullQuote'])->name('createFullQuote');



// Radar API


// Route::get('/search', [LocationController::class, 'search']);



// Payment API

Route::post('/payment', [PaymentController::class, 'processPayment'])->name('payment');
Route::get('/success', [PaymentController::class, 'success'])->name('success');
Route::get('/error', [PaymentController::class, 'error'])->name('error');


