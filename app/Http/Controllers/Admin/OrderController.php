<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Traits\LogActivity;
use Illuminate\Http\Request;
use Inertia\Inertia;

class OrderController extends Controller
{
    use LogActivity;

    public function index()
    {
        $orders = Order::with(['user', 'items'])->latest()->get();

        return Inertia::render('Admin/Orders', [
            'orders' => $orders->map(function($order) {
                return [
                    'id' => $order->id,
                    'user' => [
                        'name' => $order->user->name ?? 'Guest',
                        'email' => $order->user->email ?? 'N/A'
                    ],
                    'total' => number_format($order->total, 2),
                    'status' => $order->status,
                    'payment_method' => $order->payment_method ?? 'N/A',
                    'items_count' => $order->items->count(),
                    'created_at' => $order->created_at->format('M d, Y h:i A'),
                    'items' => $order->items->map(function($item) {
                        return [
                            'product_name' => $item->name,
                            'quantity' => $item->quantity,
                            'price' => number_format($item->price, 2),
                        ];
                    }),
                ];
            }),
        ]);
    }

    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:pending,processing,completed,cancelled'
        ]);

        $order = Order::findOrFail($id);
        $oldStatus = $order->status;
        $order->status = $request->status;
        $order->save();

        $this->logActivity('update_order_status', 'Order', $order->id, "Changed order #{$order->id} status from {$oldStatus} to {$request->status}");

        return back()->with('success', 'Order status updated successfully.');
    }
}
