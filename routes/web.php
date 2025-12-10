<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

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

Route::get('/cart', function () {
    return Inertia::render('Cart');
})->name('cart.index');