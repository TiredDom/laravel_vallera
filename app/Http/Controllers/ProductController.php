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

        // Select only necessary fields to reduce payload size
        // We DO NOT select image_path here to avoid sending huge Base64 strings
        // Wait, if we don't select image_path, the accessor won't work!
        // We MUST select image_path if we want to use it in the accessor,
        // BUT we don't want to send it to the frontend.
        // The accessor uses image_path to generate the URL.
        // So we select it, but we map the result so image_path is NOT in the final JSON.

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
                    'image_url' => $product->image_url, // Accessor returns the route URL
                    'is_featured' => $product->is_featured,
                ];
            }),
            'categories' => $categories,
            'currentCategory' => $request->category,
            'searchQuery' => $request->search,
        ]);
    }

    public function showImage($id)
    {
        $product = Product::select('image_path')->find($id);

        if (!$product || !$product->image_path) {
            abort(404);
        }

        $imagePath = $product->image_path;

        // Check if it's a Base64 string
        if (str_starts_with($imagePath, 'data:image')) {
            // Extract the mime type and the base64 data
            // Format: data:image/jpeg;base64,......
            $parts = explode(',', $imagePath);
            $header = $parts[0];
            $data = $parts[1];

            // Extract mime type
            preg_match('/data:(.*?);base64/', $header, $matches);
            $mimeType = $matches[1] ?? 'image/jpeg';

            $decodedData = base64_decode($data);

            return response($decodedData)
                ->header('Content-Type', $mimeType)
                ->header('Cache-Control', 'public, max-age=86400'); // Cache for 1 day
        }

        // Fallback for file paths (if any exist)
        if (file_exists(public_path('storage/' . $imagePath))) {
             return response()->file(public_path('storage/' . $imagePath));
        }

        abort(404);
    }
}
