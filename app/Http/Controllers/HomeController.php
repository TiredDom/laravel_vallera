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
                    'stock' => $product->stock,
                    'category_name' => $product->category,
                    'category' => $product->category,
                ];
            }),
        ]);
    }
}
