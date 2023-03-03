<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\SoalKeDuaController;

Route::post('/login', [AuthController::class, 'login']);
Route::post('/customer/login', [AuthController::class, 'custLogin']);
Route::post('/register', [AuthController::class, 'custRegister']);

// Route::group(['prefix''as' => 'customer.', 'middleware' => 'auth:api_customer'], function() {
Route::group(['middleware' => 'auth:api_customer'], function() {
    Route::get('/profile', [AuthController::class, 'getCustomer']);

    Route::get('/product', [ProductController::class, 'index']);
    Route::get('/product/{id}', [ProductController::class, 'show']);
    Route::resource('/cart', CartController::class);
    Route::post('/order-cart', [OrderController::class, 'orderCart']);
    Route::get('/order-export', [OrderController::class, 'export']);
    Route::resource('/order', OrderController::class);
});

Route::get('/soal-dua', [SoalKeDuaController::class, 'index']);

