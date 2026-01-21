<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class CartController extends Controller
{
    public function index(Request $request)
    {
        if (Auth::check()) {
            $cart = Cart::firstOrCreate(['user_id' => Auth::id()]);
            $items = $cart->items()->get();

            $categories = $items->pluck('category')->unique()->filter()->values();

            $suggestedProducts = collect();

            if ($categories->isNotEmpty()) {
                $suggestedProducts = \App\Models\Product::whereIn('category', $categories)
                    ->where('is_active', true)
                    ->whereNotIn('id', $items->pluck('product_id'))
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

            if ($suggestedProducts->count() < 4) {
                $featuredProducts = \App\Models\Product::where('is_featured', true)
                    ->where('is_active', true)
                    ->whereNotIn('id', $items->pluck('product_id'))
                    ->inRandomOrder()
                    ->limit(4 - $suggestedProducts->count())
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

                $suggestedProducts = $suggestedProducts->merge($featuredProducts);
            }
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
        if (!Auth::check()) {
            return back()->withErrors(['error' => 'Authentication required']);
        }

        $data = $request->validate([
            'product_id' => ['required', 'integer', 'exists:products,id'],
            'name' => ['required', 'string', 'max:255'],
            'price' => ['required', 'numeric', 'min:0', 'max:9999999.99'],
            'quantity' => ['required', 'integer', 'min:1', 'max:999'],
            'category' => ['nullable', 'string', 'max:100'],
        ]);

        $data['name'] = trim($data['name']);
        if (isset($data['category'])) {
            $data['category'] = trim($data['category']);
        }

        $cart = Cart::firstOrCreate(['user_id' => Auth::id()]);
        $item = $cart->items()->where('product_id', $data['product_id'])->first();
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

        $cart = Cart::firstOrCreate(['user_id' => Auth::id()]);
        $item = $cart->items()->where('product_id', $product_id)->first();
        if ($item) {
            $item->quantity = $data['quantity'];
            $item->save();
        }

        return back()->with('success', 'Cart updated');
    }

    public function checkout(Request $request)
    {
        if (!Auth::check()) {
            return back()->withErrors(['error' => 'Authentication required']);
        }

        $cart = Cart::firstOrCreate(['user_id' => Auth::id()]);
        $cartItems = $cart->items()->get();

        if ($cartItems->isEmpty()) {
            return back()->withErrors(['error' => 'Your cart is empty']);
        }

        $data = $request->validate([
            'payment_method' => 'required|string|in:gcash,card,bank',
            'payment_data' => 'required|array'
        ]);

        $subtotal = $cartItems->sum(function ($item) {
            return $item->price * $item->quantity;
        });
        $shipping = 500;
        $total = $subtotal + $shipping;

        $order = Order::create([
            'user_id' => Auth::id(),
            'total' => $total,
            'status' => 'pending',
            'payment_method' => $data['payment_method'],
        ]);

        foreach ($cartItems as $cartItem) {
            $order->items()->create([
                'product_id' => $cartItem->product_id,
                'name' => $cartItem->name,
                'price' => $cartItem->price,
                'quantity' => $cartItem->quantity,
                'category' => $cartItem->category,
            ]);
        }

        ActivityLog::create([
            'user_id' => Auth::id(),
            'action' => 'create_order',
            'model_type' => 'Order',
            'model_id' => $order->id,
            'description' => "Created order #{$order->id} with {$cartItems->count()} items, total: ₱{$total}",
            'ip_address' => $request->ip(),
        ]);

        $cart->items()->delete();

        return back()->with('success', 'Order placed successfully! Payment confirmed via ' . ucfirst($data['payment_method']) . '.');
    }
}

