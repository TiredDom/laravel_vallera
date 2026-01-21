<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\CartController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\ProductController;
use App\Models\Product;

Route::get('/', function () {
    $featuredProducts = Product::where('is_active', true)
        ->where('is_featured', true)
        ->limit(3)
        ->get();

    return Inertia::render('Landing', [
        'featuredProducts' => $featuredProducts->map(function($product) {
            return [
                'id' => $product->id,
                'name' => $product->name,
                'description' => $product->description,
                'price' => $product->price,
                'image_url' => $product->image_url,
            ];
        }),
    ]);
})->name('home');

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
Route::post('/cart/checkout', [CartController::class, 'checkout'])->middleware('auth')->name('cart.checkout');

require __DIR__.'/auth.php';

Route::middleware(['auth', 'verified'])->get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->name('dashboard');

Route::middleware(['auth'])->group(function () {
    Route::get('/profile', [\App\Http\Controllers\ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [\App\Http\Controllers\ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [\App\Http\Controllers\ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::get('/orders', [\App\Http\Controllers\ProfileController::class, 'orders'])->name('orders.index');
    Route::post('/orders/{order}/cancel', [\App\Http\Controllers\ProfileController::class, 'cancelOrder'])->name('orders.cancel');
});

Route::middleware(['auth', \App\Http\Middleware\AdminMiddleware::class])->prefix('admin')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');

    Route::post('/users/{id}/promote', [AdminController::class, 'promoteUser'])->name('admin.users.promote');
    Route::post('/users/{id}/demote', [AdminController::class, 'demoteUser'])->name('admin.users.demote');
    Route::get('/users/{id}/edit', [AdminController::class, 'editUser'])->name('admin.users.edit');
    Route::patch('/users/{id}', [AdminController::class, 'updateUser'])->name('admin.users.update');
    Route::delete('/users/{id}', [AdminController::class, 'deleteUser'])->name('admin.users.delete');
    Route::post('/users/{id}/request-edit', [AdminController::class, 'requestUserEdit'])->name('admin.users.requestEdit');

    Route::get('/edit-requests', [AdminController::class, 'editRequests'])->name('admin.editRequests');
    Route::post('/edit-requests/{id}/review', [AdminController::class, 'reviewEditRequest'])->name('admin.editRequests.review');

    Route::get('/announcements', [AdminController::class, 'announcements'])->name('admin.announcements');
    Route::post('/announcements', [AdminController::class, 'createAnnouncement'])->name('admin.announcements.create');
    Route::post('/announcements/{id}/review', [AdminController::class, 'reviewAnnouncement'])->name('admin.announcements.review');
    Route::delete('/announcements/{id}', [AdminController::class, 'deleteAnnouncement'])->name('admin.announcements.delete');

    Route::get('/orders', [AdminController::class, 'orders'])->name('admin.orders');
    Route::patch('/orders/{id}/status', [AdminController::class, 'updateOrderStatus'])->name('admin.orders.updateStatus');

    Route::get('/products', [AdminController::class, 'products'])->name('admin.products');
    Route::post('/products', [AdminController::class, 'storeProduct'])->name('admin.products.store');
    Route::post('/products/{id}', [AdminController::class, 'updateProduct'])->name('admin.products.update');
    Route::delete('/products/{id}', [AdminController::class, 'deleteProduct'])->name('admin.products.delete');

    Route::get('/logs', [AdminController::class, 'logs'])->name('admin.logs');
});

