<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class CartController extends Controller
{
    public function index(Request $request)
    {
        if (Auth::check()) {
            $cart = Cart::firstOrCreate(['user_id' => Auth::id()]);
            $cartItems = $cart->items()->get();
            $productIds = $cartItems->pluck('product_id')->toArray();
            $products = \App\Models\Product::whereIn('id', $productIds)
                ->where('is_active', true)
                ->get()
                ->keyBy('id');
            $items = $cartItems->filter(function ($item) use ($products) {
                return $products->has($item->product_id);
            })->map(function ($item) use ($products) {
                $product = $products->get($item->product_id);
                return [
                    'id' => $item->id,
                    'product_id' => $item->product_id,
                    'name' => $item->name,
                    'price' => $item->price,
                    'quantity' => $item->quantity,
                    'category' => $item->category,
                    'image_url' => $product->image_url,
                    'stock' => $product->stock,
                ];
            })->values();
            $categories = $items->pluck('category')->unique()->filter()->values();

            // Fetch as Models first
            $suggestedProducts = collect();
            if ($categories->isNotEmpty()) {
                $suggestedProducts = \App\Models\Product::whereIn('category', $categories)
                    ->where('is_active', true)
                    ->whereNotIn('id', $items->pluck('product_id'))
                    ->inRandomOrder()
                    ->limit(4)
                    ->get();
            }

            if ($suggestedProducts->count() < 4) {
                $featuredProducts = \App\Models\Product::where('is_featured', true)
                    ->where('is_active', true)
                    ->whereNotIn('id', $items->pluck('product_id'))
                    ->whereNotIn('id', $suggestedProducts->pluck('id'))
                    ->inRandomOrder()
                    ->limit(4 - $suggestedProducts->count())
                    ->get();

                $suggestedProducts = $suggestedProducts->merge($featuredProducts);
            }

            // Map to array format at the end
            $suggestedProducts = $suggestedProducts->map(function ($product) {
                return [
                    'id' => $product->id,
                    'name' => $product->name,
                    'price' => $product->price,
                    'category' => $product->category,
                    'image_url' => $product->image_url,
                    'description' => $product->description,
                    'stock' => $product->stock,
                ];
            });

        } else {
            $items = collect([]);
            $suggestedProducts = \App\Models\Product::where('is_featured', true)
                ->where('is_active', true)
                ->inRandomOrder()
                ->limit(4)
                ->get()
                ->map(function ($product) {
                    return [
                        'id' => $product->id,
                        'name' => $product->name,
                        'price' => $product->price,
                        'category' => $product->category,
                        'image_url' => $product->image_url,
                        'description' => $product->description,
                        'stock' => $product->stock,
                    ];
                });
        }
        return Inertia::render('Cart', [
            'cartItems' => $items,
            'suggestedProducts' => $suggestedProducts
        ]);
    }

    public function store(Request $request)
    {
        \Log::info('Add to cart request', $request->all());
        if (!Auth::check()) {
            \Log::warning('Add to cart failed: not authenticated');
            return back()->withErrors(['error' => 'Authentication required']);
        }
        try {
            $data = $request->validate([
                'product_id' => ['required', 'integer', 'exists:products,id'],
                'name' => ['required', 'string', 'max:255'],
                'price' => ['required', 'numeric', 'min:0', 'max:9999999.99'],
                'quantity' => ['required', 'integer', 'min:1', 'max:999'],
                'category' => ['nullable', 'string', 'max:100'],
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            \Log::error('Add to cart validation failed', $e->errors());
            return back()->withErrors(['error' => 'Validation failed: ' . json_encode($e->errors())]);
        }
        $product = \App\Models\Product::find($data['product_id']);
        if (!$product || !$product->is_active) {
            return back()->withErrors(['error' => 'Product not available']);
        }
        if ($product->stock <= 0) {
            return back()->withErrors(['error' => 'Product is out of stock']);
        }
        $data['name'] = trim($data['name']);
        if (isset($data['category'])) {
            $data['category'] = trim($data['category']);
        }
        $cart = Cart::firstOrCreate(['user_id' => Auth::id()]);
        $item = $cart->items()->where('product_id', $data['product_id'])->first();
        $newQuantity = $item ? $item->quantity + $data['quantity'] : $data['quantity'];
        if ($newQuantity > $product->stock) {
            $available = $product->stock - ($item ? $item->quantity : 0);
            if ($available <= 0) {
                return back()->withErrors(['error' => 'No more stock available for this product']);
            }
            return back()->withErrors(['error' => "Only {$available} more available in stock"]);
        }
        if ($item) {
            $item->quantity += $data['quantity'];
            $item->save();
        } else {
            $cart->items()->create($data);
        }
        return back()->with('success', 'Added to cart!');
    }

    public function destroy(Request $request, $product_id)
    {
        if (!Auth::check()) {
            return back()->withErrors(['error' => 'Authentication required']);
        }
        $cart = Cart::firstOrCreate(['user_id' => Auth::id()]);
        $cart->items()->where('product_id', $product_id)->delete();
        return back()->with('success', 'Item removed from cart');
    }

    public function update(Request $request, $product_id)
    {
        if (!Auth::check()) {
            return back()->withErrors(['error' => 'Authentication required']);
        }
        $data = $request->validate([
            'quantity' => 'required|integer|min:1',
        ]);
        $product = \App\Models\Product::find($product_id);
        if (!$product || !$product->is_active) {
            return back()->withErrors(['error' => 'Product not available']);
        }
        if ($data['quantity'] > $product->stock) {
            return back()->withErrors(['error' => "Only {$product->stock} available in stock"]);
        }
        $cart = Cart::firstOrCreate(['user_id' => Auth::id()]);
        $item = $cart->items()->where('product_id', $product_id)->first();
        if ($item) {
            $item->quantity = $data['quantity'];
            $item->save();
        }
        return back()->with('success', 'Cart updated');
    }
}
