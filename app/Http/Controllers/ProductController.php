<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::where('is_active', true);

        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('description', 'like', '%' . $request->search . '%');
            });
        }

        $products = $query->latest()->get();

        $categories = Product::where('is_active', true)
            ->distinct()
            ->pluck('category')
            ->sort()
            ->values();

        return Inertia::render('Products', [
            'products' => $products->map(function($product) {
                return [
                    'id' => $product->id,
                    'name' => $product->name,
                    'description' => $product->description,
                    'price' => $product->price,
                    'stock' => $product->stock,
                    'category' => $product->category,
                    'image_url' => $product->image_url,
                    'is_featured' => $product->is_featured,
                ];
            }),
            'categories' => $categories,
            'currentCategory' => $request->category,
            'searchQuery' => $request->search,
        ]);
    }
}
