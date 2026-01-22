<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\UserOrderController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\EditRequestController;
use App\Http\Controllers\Admin\AnnouncementController;
use App\Http\Controllers\Admin\OrderController as AdminOrderController;
use App\Http\Controllers\Admin\ProductController as AdminProductController;
use App\Http\Controllers\Admin\AuditLogController;

// Use the new HomeController for the landing page
Route::get('/', [HomeController::class, 'index'])->name('home');

Route::get('/products', [ProductController::class, 'index'])->name('products.index');

Route::get('/about', function () {
    return Inertia::render('About');
})->name('about');

Route::get('/contact', function () {
    return Inertia::render('Contact');
})->name('contact');

Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
Route::post('/cart', [CartController::class, 'store'])->name('cart.store');
Route::patch('/cart/{product_id}', [CartController::class, 'update'])->name('cart.update');
Route::delete('/cart/{product_id}', [CartController::class, 'destroy'])->name('cart.destroy');
Route::post('/cart/checkout', [CheckoutController::class, 'store'])->middleware('auth')->name('cart.checkout');

require __DIR__.'/auth.php';

Route::middleware(['auth', 'verified'])->get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->name('dashboard');

Route::middleware(['auth'])->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::get('/orders', [UserOrderController::class, 'index'])->name('orders.index');
    Route::post('/orders/{order}/cancel', [UserOrderController::class, 'cancel'])->name('orders.cancel');
});

Route::middleware(['auth', \App\Http\Middleware\AdminMiddleware::class])->prefix('admin')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('admin.dashboard');

    Route::post('/users/{id}/promote', [UserController::class, 'promote'])->name('admin.users.promote');
    Route::post('/users/{id}/demote', [UserController::class, 'demote'])->name('admin.users.demote');
    Route::get('/users/{id}/edit', [UserController::class, 'edit'])->name('admin.users.edit');
    Route::patch('/users/{id}', [UserController::class, 'update'])->name('admin.users.update');
    Route::delete('/users/{id}', [UserController::class, 'destroy'])->name('admin.users.delete');
    Route::post('/users/{id}/request-edit', [EditRequestController::class, 'store'])->name('admin.users.requestEdit');

    Route::get('/edit-requests', [EditRequestController::class, 'index'])->name('admin.editRequests');
    Route::post('/edit-requests/{id}/review', [EditRequestController::class, 'review'])->name('admin.editRequests.review');

    Route::get('/announcements', [AnnouncementController::class, 'index'])->name('admin.announcements');
    Route::post('/announcements', [AnnouncementController::class, 'store'])->name('admin.announcements.create');
    Route::post('/announcements/{id}/review', [AnnouncementController::class, 'review'])->name('admin.announcements.review');
    Route::delete('/announcements/{id}', [AnnouncementController::class, 'destroy'])->name('admin.announcements.delete');

    Route::get('/orders', [AdminOrderController::class, 'index'])->name('admin.orders');
    Route::patch('/orders/{id}/status', [AdminOrderController::class, 'updateStatus'])->name('admin.orders.updateStatus');

    Route::get('/products', [AdminProductController::class, 'index'])->name('admin.products');
    Route::post('/products', [AdminProductController::class, 'store'])->name('admin.products.store');
    Route::post('/products/{id}', [AdminProductController::class, 'update'])->name('admin.products.update');
    Route::delete('/products/{id}', [AdminProductController::class, 'destroy'])->name('admin.products.delete');

    Route::get('/logs', [AuditLogController::class, 'index'])->name('admin.logs');
});
