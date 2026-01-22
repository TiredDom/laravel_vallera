<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Inertia\Response;

class UserOrderController extends Controller
{
    public function index(Request $request): Response
    {
        $orders = Order::where('user_id', Auth::id())
            ->with('items')
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function($order) {
                return [
                    'id' => $order->id,
                    'total' => number_format($order->total, 2),
                    'status' => $order->status,
                    'payment_method' => $order->payment_method ?? 'cash',
                    'created_at' => $order->created_at,
                    'items' => $order->items->map(function($item) {
                        return [
                            'name' => $item->name,
                            'price' => $item->price,
                            'quantity' => $item->quantity,
                            'category' => $item->category,
                        ];
                    })
                ];
            });

        return Inertia::render('Profile/Orders', [
            'orders' => $orders,
        ]);
    }

    public function cancel(Request $request, Order $order): RedirectResponse
    {
        if ($order->user_id !== Auth::id()) {
            return back()->withErrors(['error' => 'Unauthorized action.']);
        }

        if (!in_array($order->status, ['pending', 'processing'])) {
            return back()->withErrors(['error' => 'This order cannot be cancelled.']);
        }

        \DB::beginTransaction();
        try {
            // Restore product stock
            foreach ($order->items as $item) {
                $product = \App\Models\Product::find($item->product_id);
                if ($product) {
                    $product->stock += $item->quantity;
                    $product->save();
                }
            }
            $order->update(['status' => 'cancelled']);

            \App\Models\ActivityLog::create([
                'user_id' => Auth::id(),
                'action' => 'cancel_order',
                'model_type' => 'Order',
                'model_id' => $order->id,
                'description' => "Cancelled order #{$order->id}",
                'ip_address' => $request->ip(),
            ]);
            \DB::commit();
            return back()->with('success', 'Order cancelled successfully.');
        } catch (\Exception $e) {
            \DB::rollBack();
            return back()->withErrors(['error' => 'Failed to cancel order. Please try again.']);
        }
    }
}
