<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\UserController;
use GuzzleHttp\Middleware;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});
Route::middleware('guest')->group(function () {
    Route::post('/login', [AuthController::class, 'login'])->name('login');
    Route::post('/register', [AuthController::class, 'register'])->name('register');
    Route::get('/login', [AuthController::class, 'viewLogin'])->name('viewLogin');
    Route::get('/register', [AuthController::class, 'viewRegister'])->name('viewRegister');
});

Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::post('/profile/edit', [AuthController::class, 'editProfile'])->name('profile.update');
    Route::get('/profile/edit', function () {
        return view('auth.edit');
    })->name('profile.edit');
    Route::post('/profile/password', [AuthController::class, 'editPassword'])->name('profile.password.update');
    Route::get('/profile/password', function () {
        return view('auth.editPassword');
    })->name('profile.password.edit');
    Route::get('/orders', [UserController::class, 'viewOrders'])->name('orders.index');
});
Route::middleware(['auth', 'admin'])->group(function () {
    //Route::get('/admin/dashboard', [AdminController::class, 'viewDashboard'])->name('admin.dashboard');
    Route::get('/admin/products/add', [AdminController::class, 'addProduct'])->name('admin.add_product');
    Route::post('/admin/products/store', [AdminController::class, 'storeProduct'])->name('admin.store_product');
});
