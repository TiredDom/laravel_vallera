<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Order;
use App\Models\UserEditRequest;
use App\Models\Announcement;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $totalRevenue = Order::whereIn('status', ['completed', 'processing'])->sum('total');
        $todayRevenue = Order::whereIn('status', ['completed', 'processing'])
            ->whereDate('created_at', today())
            ->sum('total');
        $yesterdayRevenue = Order::whereIn('status', ['completed', 'processing'])
            ->whereDate('created_at', today()->subDay())
            ->sum('total');

        $revenueChange = $yesterdayRevenue > 0
            ? round((($todayRevenue - $yesterdayRevenue) / $yesterdayRevenue) * 100, 1)
            : 0;

        $totalOrders = Order::count();
        $todayOrders = Order::whereDate('created_at', today())->count();
        $yesterdayOrders = Order::whereDate('created_at', today()->subDay())->count();
        $ordersChange = $yesterdayOrders > 0
            ? round((($todayOrders - $yesterdayOrders) / $yesterdayOrders) * 100, 1)
            : 0;

        $totalUsers = User::count();
        $thisWeekUsers = User::where('created_at', '>=', now()->subWeek())->count();
        $lastWeekUsers = User::whereBetween('created_at', [now()->subWeeks(2), now()->subWeek()])->count();
        $usersChange = $lastWeekUsers > 0
            ? round((($thisWeekUsers - $lastWeekUsers) / $lastWeekUsers) * 100, 1)
            : 0;

        $pendingOrders = Order::where('status', 'pending')->count();

        $averageOrderValue = $totalOrders > 0
            ? round($totalRevenue / $totalOrders, 2)
            : 0;

        $ordersByStatus = [
            'pending' => Order::where('status', 'pending')->count(),
            'processing' => Order::where('status', 'processing')->count(),
            'completed' => Order::where('status', 'completed')->count(),
            'cancelled' => Order::where('status', 'cancelled')->count(),
        ];

        $last7DaysRevenue = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = today()->subDays($i);
            $last7DaysRevenue[] = [
                'date' => $date->format('M d'),
                'revenue' => (float) Order::whereIn('status', ['completed', 'processing'])
                    ->whereDate('created_at', $date)
                    ->sum('total')
            ];
        }

        $recentOrders = Order::with(['user', 'items'])
            ->latest()
            ->take(5)
            ->get()
            ->map(function($order) {
                return [
                    'id' => $order->id,
                    'total' => number_format($order->total, 2),
                    'items_count' => $order->items->count(),
                    'status' => $order->status,
                    'payment_method' => $order->payment_method ?? 'N/A',
                    'created_at' => $order->created_at->diffForHumans(),
                    'user' => [
                        'name' => $order->user->name ?? 'Guest',
                        'email' => $order->user->email ?? 'N/A'
                    ]
                ];
            });

        $recentUsers = User::latest()
            ->take(5)
            ->get()
            ->map(function($user) {
                return [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'is_admin' => $user->is_admin,
                    'created_at' => $user->created_at->diffForHumans(),
                ];
            });

        $users = User::withCount('orders')->orderBy('created_at', 'desc')->get();

        $pendingEditRequests = 0;
        $pendingAnnouncements = 0;
        $newAnnouncementsCount = 0;

        if ($user->isSuperAdmin()) {
            $lastSeenEditRequests = $user->edit_requests_last_seen_at ?? now()->subYears(10);
            $pendingEditRequests = UserEditRequest::where('status', 'pending')
                ->where('created_at', '>', $lastSeenEditRequests)
                ->count();

            $lastSeenAnnouncements = $user->announcements_last_seen_at ?? now()->subYears(10);
            $pendingAnnouncements = Announcement::where('status', 'pending')
                ->where('created_at', '>', $lastSeenAnnouncements)
                ->count();
        } else {
            $lastSeenAnnouncements = $user->announcements_last_seen_at ?? now()->subYears(10);
            $newAnnouncementsCount = Announcement::where('status', 'published')
                ->where('created_at', '>', $lastSeenAnnouncements)
                ->count();
        }

        $stats = [
            'totalRevenue' => number_format($totalRevenue, 2),
            'revenueChange' => $revenueChange,
            'totalOrders' => $totalOrders,
            'todayOrders' => $todayOrders,
            'ordersChange' => $ordersChange,
            'totalUsers' => $totalUsers,
            'newUsersThisWeek' => $thisWeekUsers,
            'usersChange' => $usersChange,
            'pendingOrders' => $pendingOrders,
            'averageOrderValue' => number_format($averageOrderValue, 2),
            'ordersByStatus' => $ordersByStatus,
            'last7DaysRevenue' => $last7DaysRevenue,
            'recentOrders' => $recentOrders,
            'recentUsers' => $recentUsers,
            'pendingEditRequests' => $pendingEditRequests,
            'pendingAnnouncements' => $pendingAnnouncements,
            'newAnnouncementsCount' => $newAnnouncementsCount,
        ];

        return Inertia::render('Admin/Dashboard', [
            'users' => $users,
            'stats' => $stats,
            'isSuperAdmin' => $user->isSuperAdmin(),
        ]);
    }
}
