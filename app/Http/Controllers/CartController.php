<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Order;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
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
            'payment_data' => 'required|array',
            'delivery' => 'required|array',
            'delivery.name' => 'required|string|min:2|max:255',
            'delivery.phone' => ['required', 'string', 'regex:/^(09|\+639)\d{9}$/'],
            'delivery.address' => 'required|string|min:10|max:500',
            'delivery.city' => 'required|string|min:2|max:100',
            'delivery.province' => 'required|string|min:2|max:100',
            'delivery.postalCode' => ['required', 'string', 'regex:/^\d{4}$/'],
            'delivery.notes' => 'nullable|string|max:500',
        ]);
        $delivery = [
            'name' => trim(strip_tags($data['delivery']['name'])),
            'phone' => preg_replace('/\s+/', '', $data['delivery']['phone']),
            'address' => trim(strip_tags($data['delivery']['address'])),
            'city' => trim(strip_tags($data['delivery']['city'])),
            'province' => trim(strip_tags($data['delivery']['province'])),
            'postal_code' => trim(strip_tags($data['delivery']['postalCode'])),
            'notes' => isset($data['delivery']['notes']) ? trim(strip_tags($data['delivery']['notes'])) : null,
        ];
        $paymentData = array_map(function($v) {
            return is_string($v) ? trim(strip_tags($v)) : $v;
        }, $data['payment_data']);
        if ($data['payment_method'] === 'card') {
            if (empty($paymentData['holder']) || !preg_match('/^[A-Za-z ]+$/', $paymentData['holder'])) {
                return back()->withErrors(['error' => 'Card holder name must only contain letters and spaces.']);
            }
            if (empty($paymentData['number']) || !preg_match('/^\d{16}$/', $paymentData['number'])) {
                return back()->withErrors(['error' => 'Card number must be 16 digits.']);
            }
            if (empty($paymentData['expiry']) || !preg_match('/^(0[1-9]|1[0-2])\/(\d{2})$/', $paymentData['expiry'])) {
                return back()->withErrors(['error' => 'Expiry must be in MM/YY format.']);
            }
            if (empty($paymentData['cvc']) || !preg_match('/^\d{3,4}$/', $paymentData['cvc'])) {
                return back()->withErrors(['error' => 'CVC must be 3 or 4 digits.']);
            }
        }
        if ($data['payment_method'] === 'gcash') {
            if (isset($paymentData['reference']) && !preg_match('/^[A-Za-z0-9]{8,20}$/', $paymentData['reference'])) {
                return back()->withErrors(['error' => 'Invalid GCash reference number.']);
            }
        }
        if ($data['payment_method'] === 'bank') {
            if (empty($paymentData['account_name']) || !preg_match('/^[A-Za-z ]+$/', $paymentData['account_name'])) {
                return back()->withErrors(['error' => 'Bank account name must only contain letters and spaces.']);
            }
            if (empty($paymentData['account_number']) || !preg_match('/^\d{10,20}$/', $paymentData['account_number'])) {
                return back()->withErrors(['error' => 'Bank account number must be 10-20 digits.']);
            }
            if (empty($paymentData['reference']) || !preg_match('/^[A-Za-z0-9]{8,20}$/', $paymentData['reference'])) {
                return back()->withErrors(['error' => 'Invalid bank reference number.']);
            }
        }
        \DB::beginTransaction();
        try {
            $productIds = $cartItems->pluck('product_id')->toArray();
            $products = \App\Models\Product::whereIn('id', $productIds)
                ->where('is_active', true)
                ->lockForUpdate()
                ->get()
                ->keyBy('id');
            $invalidItems = [];
            foreach ($cartItems as $item) {
                $product = $products->get($item->product_id);
                if (!$product) {
                    $invalidItems[] = $item->name . ' (no longer available)';
                    continue;
                }
                if ($item->quantity > $product->stock) {
                    $invalidItems[] = $item->name . ' (insufficient stock)';
                }
            }
            if (!empty($invalidItems)) {
                \DB::rollBack();
                return back()->withErrors([
                    'error' => 'Some items are no longer available: ' . implode(', ', $invalidItems)
                ]);
            }
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
                'delivery_name' => $delivery['name'],
                'delivery_phone' => $delivery['phone'],
                'delivery_address' => $delivery['address'],
                'delivery_city' => $delivery['city'],
                'delivery_province' => $delivery['province'],
                'delivery_postal_code' => $delivery['postal_code'],
                'delivery_notes' => $delivery['notes'],
            ]);
            foreach ($cartItems as $cartItem) {
                $product = $products->get($cartItem->product_id);
                $order->items()->create([
                    'product_id' => $cartItem->product_id,
                    'name' => $cartItem->name,
                    'price' => $cartItem->price,
                    'quantity' => $cartItem->quantity,
                    'category' => $cartItem->category,
                ]);
                $product->stock -= $cartItem->quantity;
                if ($product->stock < 0) {
                    \DB::rollBack();
                    return back()->withErrors(['error' => 'Stock error for ' . $product->name]);
                }
                $product->save();
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
            cache()->forget("user." . Auth::id() . ".orders_count");
            \DB::commit();
            return redirect()->route('orders.index')->with('success', 'Order placed successfully! Your order #' . $order->id . ' has been confirmed.');
        } catch (\Exception $e) {
            \DB::rollBack();
            \Log::error('Checkout failed: ' . $e->getMessage());
            return back()->withErrors(['error' => 'An error occurred during checkout. Please try again.']);
        }
    }
}

