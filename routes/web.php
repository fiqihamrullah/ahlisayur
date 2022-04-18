<?php

use Illuminate\Support\Facades\Route;


use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ProductController;


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
});
 
/*

Route::get('/home', function() {
    return view('home');
})->name('home')->middleware('auth');
*/