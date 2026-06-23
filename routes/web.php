<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\UserController;
use GuzzleHttp\Middleware;
use Illuminate\Support\Facades\Route;

Route::get('/',  [ProductController::class, 'publicIndex']);
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
    //Route::get('/user/dashboard', [UserController::class, 'viewDashboard'])->name('user.dashboard');
    Route::get('/admin/revenue', [AdminController::class, 'viewRevenue'])->name('admin.revenue');
});
Route::middleware(['auth', 'admin'])->group(function () {
    Route::get('/admin/products/add', [ProductController::class, 'create'])->name('admin.add_product');
    Route::post('/admin/products/store', [ProductController::class, 'store'])->name('admin.store_product');
    Route::get('/admin/products', [ProductController::class, 'index'])->name('admin.products.index');
    Route::get('/admin/products/{id}/edit', [ProductController::class, 'edit'])->name('admin.products.edit');
    Route::match(['post', 'put'], '/admin/products/{id}/update', [ProductController::class, 'update'])->name('admin.products.update');
    Route::delete('/admin/products/{id}', [ProductController::class, 'destroy'])->name('admin.products.destroy');

    Route::get('/admin/categories', [CategoryController::class, 'index'])->name('admin.categories.index');
    Route::get('/admin/categories/create', [CategoryController::class, 'create'])->name('admin.categories.create');
    Route::post('/admin/categories', [CategoryController::class, 'store'])->name('admin.categories.store');
    Route::get('/admin/categories/{id}/edit', [CategoryController::class, 'edit'])->name('admin.categories.edit');
    Route::put('/admin/categories/{id}', [CategoryController::class, 'update'])->name('admin.categories.update');
    Route::delete('/admin/categories/{id}', [CategoryController::class, 'destroy'])->name('admin.categories.destroy');

    Route::patch('/admin/orders/{id}/paid', [OrderController::class, 'markPaid'])->name('admin.orders.paid');
    Route::patch('/admin/orders/{id}/cancel', [OrderController::class, 'cancelByAdmin'])->name('admin.orders.cancel');
});

Route::get('/api/suggestions', [ProductController::class, 'suggestions'])->name('api.suggestions');
Route::get('/products', [ProductController::class, 'publicIndex'])->name('products.index');
Route::get('/search', [ProductController::class, 'publicIndex'])->name('search');

Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
Route::post('/cart/add/{id}', [CartController::class, 'add'])->name('cart.add');
Route::post('/cart/remove/{id}', [CartController::class, 'remove'])->name('cart.remove');
Route::post('/cart/update/{id}', [CartController::class, 'update'])->name('cart.update');

