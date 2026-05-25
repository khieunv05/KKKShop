<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ProductController;

// Public endpoints
Route::get('products', [ProductController::class, 'index']);
Route::get('products/{product}', [ProductController::class, 'show']);

// Auth
use App\Http\Controllers\Api\AuthController as ApiAuthController;

Route::post('register', [ApiAuthController::class, 'register']);
Route::post('login', [ApiAuthController::class, 'login']);

use App\Http\Controllers\Api\OrderController;

// Orders
// Make create public for testing/dev; index/show remain protected
Route::post('orders', [OrderController::class, 'store']);
Route::middleware(['auth:sanctum'])->group(function () {
    Route::get('orders', [OrderController::class, 'index']);
    Route::get('orders/{order}', [OrderController::class, 'show']);
});

use App\Http\Controllers\Api\PaymentController;

// Simulated payment webhook (public for simulation)
Route::post('payments/simulate', [PaymentController::class, 'simulate']);

// Protected endpoints (require token auth, e.g. Sanctum)
Route::middleware(['auth:sanctum'])->group(function () {
    Route::post('products', [ProductController::class, 'store']);
    Route::put('products/{product}', [ProductController::class, 'update']);
    Route::delete('products/{product}', [ProductController::class, 'destroy']);
});
