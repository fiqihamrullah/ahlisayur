<?php

use Illuminate\Support\Facades\Route;


use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\OrderController;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/



Auth::routes();

Route::middleware(['auth'])->group(function () 
{
    Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
    Route::get('/', [App\Http\Controllers\HomeController::class, 'index']);

    Route::resource('category', CategoryController::class);
    Route::post('category/load', [CategoryController::class, 'load'])->name('category.load');

    Route::resource('product', ProductController::class);
    Route::post('product/load', [ProductController::class, 'load'])->name('product.load');

    Route::resource('customer', CustomerController::class);
    Route::post('customer/load', [CustomerController::class, 'load'])->name('customer.load');

    Route::resource('order', OrderController::class);
    Route::post('order/load', [OrderController::class, 'load'])->name('order.load');
});
 
/*

Route::get('/home', function() {
    return view('home');
})->name('home')->middleware('auth');
*/