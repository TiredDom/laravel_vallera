<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Order;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CheckoutController extends Controller
{
    public function store(Request $request)
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
                    'name' => $cartItem->name ?: ($product ? $product->name : 'Unnamed Product'),
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
