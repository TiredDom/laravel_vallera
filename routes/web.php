<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\CartController;
use App\Http\Controllers\AdminController;

Route::get('/', function () {
    return Inertia::render('Landing');
})->name('home');

Route::get('/products', function () {
    return Inertia::render('Products', [
        'products' => [
            ['id' => 1, 'name' => 'Luxe Comfort Sofa', 'price' => 74999, 'category' => 'Sofas', 'isNew' => true],
            ['id' => 2, 'name' => 'Minimalist Oak Desk', 'price' => 39999, 'category' => 'Tables', 'isNew' => false],
            ['id' => 3, 'name' => 'Velvet Dream Armchair', 'price' => 29999, 'category' => 'Chairs', 'isNew' => false],
            ['id' => 4, 'name' => 'Industrial Metal Bookshelf', 'price' => 44999, 'category' => 'Storage', 'isNew' => true],
            ['id' => 5, 'name' => 'Aether Minimalist Lamp', 'price' => 7999, 'category' => 'Lighting', 'isNew' => false],
            ['id' => 6, 'name' => 'Granite-Top Coffee Table', 'price' => 34999, 'category' => 'Tables', 'isNew' => false],
            ['id' => 7, 'name' => 'Emerald Velvet Accent Chair', 'price' => 27999, 'category' => 'Chairs', 'isNew' => false],
            ['id' => 8, 'name' => 'Modular Sectional Sofa', 'price' => 129999, 'category' => 'Sofas', 'isNew' => false],
        ]
    ]);
})->name('products.index');

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
});

Route::middleware(['auth', \App\Http\Middleware\AdminMiddleware::class])->prefix('admin')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
    Route::post('/users/{id}/promote', [AdminController::class, 'promoteUser'])->name('admin.users.promote');
    Route::post('/users/{id}/demote', [AdminController::class, 'demoteUser'])->name('admin.users.demote');
});

