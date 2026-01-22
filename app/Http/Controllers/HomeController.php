<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Inertia\Inertia;

class HomeController extends Controller
{
    public function index()
    {
        $featuredProducts = Product::where('is_active', true)
            ->where('is_featured', true)
            ->with('category') // Eager load the category
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
                    'quantity' => $product->stock, // Use the correct stock field
                    'category_name' => $product->category->name, // Get the category name
                ];
            }),
        ]);
    }
}
