<?php

use App\Http\Controllers\Api\Auth\LoginController;
use App\Http\Controllers\Api\Auth\LogoutController;
use App\Http\Controllers\Api\Auth\RegisterController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\VoucherController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function () {
    Route::post('/register', RegisterController::class);
    Route::post('/login', LoginController::class);
    Route::post('/logout', LogoutController::class)->middleware('auth:sanctum');

    Route::middleware(['auth:sanctum'])->group(function () {
        Route::apiResource('products', ProductController::class);
        Route::apiResource('vouchers', VoucherController::class);
        Route::post('/vouchers/redeem', [VoucherController::class, 'redeem'])->name('vouchers.redeem');
    });
});
