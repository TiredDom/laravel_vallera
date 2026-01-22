<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Traits\LogActivity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;

class ProductController extends Controller
{
    use LogActivity;

    public function index()
    {
        $products = Product::latest()->get();

        $featuredCount = Product::where('is_featured', true)->count();
        $maxFeatured = 8;

        return Inertia::render('Admin/Products', [
            'products' => $products->map(function($product) {
                return [
                    'id' => $product->id,
                    'name' => $product->name,
                    'description' => $product->description,
                    'price' => $product->price,
                    'stock' => $product->stock,
                    'category' => $product->category,
                    'image_path' => $product->image_path,
                    'image_url' => $product->image_url,
                    'is_featured' => $product->is_featured,
                    'is_active' => $product->is_active,
                    'created_at' => $product->created_at->format('M d, Y'),
                ];
            }),
            'featuredCount' => $featuredCount,
            'maxFeatured' => $maxFeatured,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255', 'regex:/^[a-zA-Z0-9\s\-&\'.()]+$/'],
            'description' => ['nullable', 'string', 'max:5000'],
            'price' => ['required', 'numeric', 'min:0', 'max:9999999.99'],
            'stock' => ['required', 'integer', 'min:0', 'max:999999'],
            'category' => ['required', 'string', 'max:100'],
            'image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
            'is_featured' => ['boolean'],
            'is_active' => ['boolean'],
        ], [
            'image.max' => 'The image must not be larger than 5MB.',
            'image.image' => 'The file must be an image.',
            'image.mimes' => 'The image must be a file of type: jpg, jpeg, png, webp.',
        ]);

        $dataToSave = $validated;

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            // Convert to Base64
            $path = $image->getRealPath();
            $type = $image->getMimeType();
            $data = file_get_contents($path);
            $base64 = 'data:' . $type . ';base64,' . base64_encode($data);
            $dataToSave['image_path'] = $base64;
        }

        if ($request->is_featured) {
            $featuredCount = Product::where('is_featured', true)->count();
            if ($featuredCount >= 8) {
                return back()->withErrors(['is_featured' => 'Maximum 8 products can be featured. Please unfeature another product first.']);
            }
        }

        $product = Product::create($dataToSave);

        $this->logActivity('create_product', 'Product', $product->id, "Created product: {$product->name}");

        return back()->with('success', 'Product created successfully.');
    }

    public function update(Request $request, $id)
    {
        $product = Product::findOrFail($id);

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255', 'regex:/^[A-Za-z\s\-\'\.\(\)&]+$/'], // Only letters, spaces, and allowed symbols
            'description' => ['nullable', 'string', 'max:5000'],
            'price' => ['required', 'numeric', 'min:0', 'max:9999999.99'],
            'stock' => ['required', 'integer', 'min:0', 'max:999999'],
            'category' => ['required', 'string', 'max:100', 'regex:/^[A-Za-z\s\-]+$/'], // Only letters, spaces, and dashes
            'image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
            'is_featured' => ['boolean'],
            'is_active' => ['boolean'],
        ], [
            'name.regex' => 'Product name must only contain letters, spaces, and allowed symbols.',
            'category.regex' => 'Category must only contain letters, spaces, and dashes.',
            'image.max' => 'The image must not be larger than 5MB.',
            'image.image' => 'The file must be an image.',
            'image.mimes' => 'The image must be a file of type: jpg, jpeg, png, webp.',
        ]);

        $dataToSave = $validated;

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            // Convert to Base64
            $path = $image->getRealPath();
            $type = $image->getMimeType();
            $data = file_get_contents($path);
            $base64 = 'data:' . $type . ';base64,' . base64_encode($data);
            $dataToSave['image_path'] = $base64;
        }

        if ($request->is_featured && !$product->is_featured) {
            $featuredCount = Product::where('is_featured', true)->where('id', '!=', $id)->count();
            if ($featuredCount >= 8) {
                return back()->withErrors(['is_featured' => 'Maximum 8 products can be featured. Please unfeature another product first.']);
            }
        }

        $product->update($dataToSave);

        $this->logActivity('update_product', 'Product', $product->id, "Updated product: {$product->name}");

        return back()->with('success', 'Product updated successfully.');
    }

    public function destroy($id)
    {
        $product = Product::findOrFail($id);

        // No need to delete file from storage as it's in DB now
        // But if we had mixed storage, we might want to check if it's a path or base64
        // For simplicity, we just delete the record

        $productName = $product->name;
        $product->delete();

        $this->logActivity('delete_product', 'Product', $id, "Deleted product: {$productName}");

        return back()->with('success', 'Product deleted successfully.');
    }
}
