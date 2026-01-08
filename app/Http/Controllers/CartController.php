<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\CartItem;
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
        } else {
            $items = collect([]);
        }
        return Inertia::render('Cart', [
            'cartItems' => $items
        ]);
    }

    public function store(Request $request)
    {
        if (!Auth::check()) {
            return back()->withErrors(['error' => 'Authentication required']);
        }

        $data = $request->validate([
            'product_id' => 'required|integer',
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'quantity' => 'required|integer|min:1',
            'category' => 'nullable|string|max:100',
        ]);

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
        $cart->items()->delete();
        return back()->with('success', 'Order placed successfully!');
    }
}

