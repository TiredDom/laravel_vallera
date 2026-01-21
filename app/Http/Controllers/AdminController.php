<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Order;
use App\Models\ActivityLog;
use App\Models\UserEditRequest;
use App\Models\Announcement;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;

class AdminController extends Controller
{
    public function dashboard()
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

    public function promoteUser(Request $request, $id)
    {
        $currentUser = Auth::user();

        if (!$currentUser->isSuperAdmin()) {
            return back()->withErrors(['error' => 'Only the super admin can promote users.']);
        }

        $user = User::findOrFail($id);

        if (!str_ends_with($user->email, '@vallera.com')) {
            return back()->withErrors(['error' => 'Only vallera.com users can be promoted to admin.']);
        }

        $user->is_admin = true;
        $user->save();

        return back()->with('success', 'User promoted to admin successfully.');
    }

    public function demoteUser(Request $request, $id)
    {
        $currentUser = Auth::user();

        if (!$currentUser->isSuperAdmin()) {
            return back()->withErrors(['error' => 'Only the super admin can demote users.']);
        }

        $user = User::findOrFail($id);

        if ($user->isSuperAdmin()) {
            return back()->withErrors(['error' => 'Cannot demote the super admin.']);
        }

        $user->is_admin = false;
        $user->save();

        $this->logActivity('demote_user', 'User', $user->id, "Demoted {$user->name} from admin");

        return back()->with('success', 'User demoted successfully.');
    }

    public function editUser($id)
    {
        $currentUser = Auth::user();
        $user = User::findOrFail($id);

        if (!$currentUser->isSuperAdmin()) {
            abort(403, 'Only super admin can edit users directly. Please submit an edit request.');
        }

        if ($user->isSuperAdmin() && $currentUser->id !== $user->id) {
            abort(403, 'Cannot edit super admin account.');
        }

        return Inertia::render('Admin/EditUser', [
            'editingUser' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'is_admin' => $user->is_admin,
                'is_super_admin' => $user->isSuperAdmin(),
                'orders_count' => $user->orders()->count(),
                'created_at' => $user->created_at->format('M d, Y'),
            ],
            'isSuperAdmin' => $currentUser->isSuperAdmin(),
        ]);
    }

    public function updateUser(Request $request, $id)
    {
        $currentUser = Auth::user();
        $user = User::findOrFail($id);

        if (!$currentUser->isSuperAdmin()) {
            abort(403, 'Only super admin can edit users directly.');
        }

        if ($user->isSuperAdmin() && $currentUser->id !== $user->id) {
            abort(403, 'Cannot edit super admin account.');
        }

        $rules = [
            'name' => ['required', 'string', 'max:255', 'regex:/^[a-zA-Z\s\-\'.]+$/'],
            'email' => ['required', 'string', 'email', 'max:255', 'lowercase', 'unique:users,email,' . $user->id],
        ];

        if ($request->filled('password')) {
            $rules['password'] = ['required', 'string', 'min:8', 'max:255', 'confirmed'];
        }

        $validated = $request->validate($rules);

        $validated['name'] = trim($validated['name']);
        $validated['email'] = strtolower(trim($validated['email']));

        $changes = [];
        if ($user->name !== $validated['name']) {
            $changes[] = "name from '{$user->name}' to '{$validated['name']}'";
        }
        if ($user->email !== $validated['email']) {
            $changes[] = "email from '{$user->email}' to '{$validated['email']}'";
        }

        $user->name = $validated['name'];
        $user->email = $validated['email'];

        if ($request->filled('password')) {
            $user->password = bcrypt($validated['password']);
            $changes[] = "password";
        }

        $user->save();

        if (!empty($changes)) {
            $this->logActivity('update_user', 'User', $user->id, "Updated user {$user->name}: " . implode(', ', $changes));
        }

        return redirect()->route('admin.dashboard')->with('success', 'User updated successfully.');
    }

    public function deleteUser($id)
    {
        $currentUser = Auth::user();

        if (!$currentUser->isSuperAdmin()) {
            abort(403, 'Only super admin can delete users.');
        }

        $user = User::findOrFail($id);

        if ($user->isSuperAdmin()) {
            return back()->withErrors(['error' => 'Cannot delete the super admin.']);
        }

        $userName = $user->name;
        $user->delete();

        $this->logActivity('delete_user', 'User', $id, "Deleted user: {$userName}");

        return back()->with('success', 'User deleted successfully.');
    }

    public function requestUserEdit(Request $request, $id)
    {
        $currentUser = Auth::user();
        $targetUser = User::findOrFail($id);

        if ($currentUser->isSuperAdmin()) {
            return back()->withErrors(['error' => 'Super admin can edit users directly.']);
        }

        if (!$currentUser->is_admin) {
            abort(403, 'Only admins can request user edits.');
        }

        if ($targetUser->is_admin || $targetUser->isSuperAdmin()) {
            return back()->withErrors(['error' => 'Cannot request edits for admin accounts.']);
        }

        $validated = $request->validate([
            'field_name' => ['required', 'string', 'in:name,email,password'],
            'new_value' => ['required', 'string', 'max:255'],
            'reason' => ['required', 'string', 'max:500'],
        ]);

        if ($validated['field_name'] === 'email' && !filter_var($validated['new_value'], FILTER_VALIDATE_EMAIL)) {
            return back()->withErrors(['new_value' => 'Invalid email format.']);
        }

        if ($validated['field_name'] === 'name' && !preg_match('/^[a-zA-Z\s\-\'.]+$/', $validated['new_value'])) {
            return back()->withErrors(['new_value' => 'Name can only contain letters, spaces, hyphens, apostrophes, and periods.']);
        }

        if ($validated['field_name'] === 'password' && strlen($validated['new_value']) < 8) {
            return back()->withErrors(['new_value' => 'Password must be at least 8 characters.']);
        }

        $validated['new_value'] = trim($validated['new_value']);
        $validated['reason'] = trim($validated['reason']);

        $oldValue = $validated['field_name'] === 'password' ? '(hidden)' : $targetUser->{$validated['field_name']};

        UserEditRequest::create([
            'requested_by' => $currentUser->id,
            'target_user_id' => $targetUser->id,
            'field_name' => $validated['field_name'],
            'old_value' => $oldValue,
            'new_value' => $validated['new_value'],
            'reason' => $validated['reason'],
            'status' => 'pending',
        ]);

        $this->logActivity('request_user_edit', 'UserEditRequest', $targetUser->id, "Requested to edit {$targetUser->name}'s {$validated['field_name']}");

        return back()->with('success', 'Edit request submitted successfully. Waiting for super admin approval.');
    }

    public function editRequests()
    {
        $currentUser = Auth::user();

        if (!$currentUser->isSuperAdmin()) {
            abort(403, 'Only super admin can view edit requests.');
        }

        $requests = UserEditRequest::with(['requester', 'targetUser', 'reviewer'])
            ->latest()
            ->paginate(20);

        $currentUser->edit_requests_last_seen_at = now();
        $currentUser->save();

        return Inertia::render('Admin/EditRequests', [
            'requests' => $requests,
        ]);
    }

    public function reviewEditRequest(Request $request, $id)
    {
        $currentUser = Auth::user();

        if (!$currentUser->isSuperAdmin()) {
            abort(403, 'Only super admin can review edit requests.');
        }

        $editRequest = UserEditRequest::with(['targetUser'])->findOrFail($id);

        if ($editRequest->status !== 'pending') {
            return back()->withErrors(['error' => 'This request has already been reviewed.']);
        }

        $validated = $request->validate([
            'status' => 'required|in:approved,rejected',
            'review_note' => 'nullable|string|max:500',
        ]);

        $editRequest->status = $validated['status'];
        $editRequest->reviewed_by = $currentUser->id;
        $editRequest->reviewed_at = now();
        $editRequest->review_note = $validated['review_note'] ?? null;
        $editRequest->save();

        if ($validated['status'] === 'approved') {
            $targetUser = $editRequest->targetUser;
            $fieldName = $editRequest->field_name;

            if ($fieldName === 'password') {
                $targetUser->password = bcrypt($editRequest->new_value);
            } else {
                $targetUser->{$fieldName} = $editRequest->new_value;
            }

            $targetUser->save();

            $this->logActivity('approve_user_edit', 'User', $targetUser->id, "Approved edit request for {$targetUser->name}'s {$fieldName}");
        } else {
            $this->logActivity('reject_user_edit', 'UserEditRequest', $editRequest->id, "Rejected edit request for user ID {$editRequest->target_user_id}");
        }

        return back()->with('success', 'Edit request ' . $validated['status'] . ' successfully.');
    }

    public function orders()
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
                            'product_name' => $item->product_name,
                            'quantity' => $item->quantity,
                            'price' => number_format($item->price, 2),
                        ];
                    }),
                ];
            }),
        ]);
    }

    public function updateOrderStatus(Request $request, $id)
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

    public function products()
    {
        $products = Product::latest()->get();

        $featuredCount = Product::where('is_featured', true)->count();

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
        ]);
    }

    public function storeProduct(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255', 'regex:/^[a-zA-Z0-9\s\-&\'.()]+$/'],
            'description' => ['nullable', 'string', 'max:5000'],
            'price' => ['required', 'numeric', 'min:0', 'max:9999999.99'],
            'stock' => ['required', 'integer', 'min:0', 'max:999999'],
            'category' => ['required', 'string', 'max:100'],
            'image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'is_featured' => ['boolean'],
            'is_active' => ['boolean'],
        ]);

        $validated['name'] = trim($validated['name']);
        $validated['description'] = isset($validated['description']) ? trim($validated['description']) : null;
        $validated['category'] = trim($validated['category']);

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = 'product-' . time() . '-' . uniqid() . '.' . $image->getClientOriginalExtension();
            $imagePath = $image->storeAs('products', $imageName, 'public');
            $validated['image_path'] = $imagePath;
        }

        if ($request->is_featured) {
            $featuredCount = Product::where('is_featured', true)->count();
            if ($featuredCount >= 3) {
                return back()->withErrors(['is_featured' => 'Maximum 3 products can be featured. Please unfeature another product first.']);
            }
        }

        $product = Product::create($validated);

        $this->logActivity('create_product', 'Product', $product->id, "Created product: {$product->name}");

        return back()->with('success', 'Product created successfully.');
    }

    public function updateProduct(Request $request, $id)
    {
        $product = Product::findOrFail($id);

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255', 'regex:/^[a-zA-Z0-9\s\-&\'.()]+$/'],
            'description' => ['nullable', 'string', 'max:5000'],
            'price' => ['required', 'numeric', 'min:0', 'max:9999999.99'],
            'stock' => ['required', 'integer', 'min:0', 'max:999999'],
            'category' => ['required', 'string', 'max:100'],
            'image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'is_featured' => ['boolean'],
            'is_active' => ['boolean'],
        ]);

        $validated['name'] = trim($validated['name']);
        $validated['description'] = isset($validated['description']) ? trim($validated['description']) : null;
        $validated['category'] = trim($validated['category']);

        if ($request->hasFile('image')) {
            if ($product->image_path) {
                Storage::disk('public')->delete($product->image_path);
            }

            $image = $request->file('image');
            $imageName = 'product-' . time() . '-' . uniqid() . '.' . $image->getClientOriginalExtension();
            $imagePath = $image->storeAs('products', $imageName, 'public');
            $validated['image_path'] = $imagePath;
        }

        if ($request->is_featured && !$product->is_featured) {
            $featuredCount = Product::where('is_featured', true)->where('id', '!=', $id)->count();
            if ($featuredCount >= 3) {
                return back()->withErrors(['is_featured' => 'Maximum 3 products can be featured. Please unfeature another product first.']);
            }
        }

        $product->update($validated);

        $this->logActivity('update_product', 'Product', $product->id, "Updated product: {$product->name}");

        return back()->with('success', 'Product updated successfully.');
    }

    public function deleteProduct($id)
    {
        $product = Product::findOrFail($id);

        if ($product->image_path) {
            Storage::disk('public')->delete($product->image_path);
        }

        $productName = $product->name;
        $product->delete();

        $this->logActivity('delete_product', 'Product', $id, "Deleted product: {$productName}");

        return back()->with('success', 'Product deleted successfully.');
    }

    public function logs(Request $request)
    {
        $user = Auth::user();

        if (!$user->isSuperAdmin()) {
            abort(403, 'Unauthorized');
        }

        try {
            $query = ActivityLog::with('user');

            if ($request->filled('user_id')) {
                $query->where('user_id', $request->user_id);
            }

            if ($request->filled('action')) {
                $query->where('action', $request->action);
            }

            if ($request->filled('date_from')) {
                $query->whereDate('created_at', '>=', $request->date_from);
            }

            if ($request->filled('date_to')) {
                $query->whereDate('created_at', '<=', $request->date_to);
            }

            if ($request->filled('search')) {
                $query->where('description', 'like', '%' . $request->search . '%');
            }

            $logs = $query->latest()
                ->paginate($request->input('per_page', 50))
                ->withQueryString();

            $users = User::select('id', 'name')->get();

            $actionTypes = ActivityLog::select('action')
                ->distinct()
                ->pluck('action')
                ->filter();

            return Inertia::render('Admin/AuditLogs', [
                'logs' => $logs,
                'users' => $users,
                'actionTypes' => $actionTypes,
                'filters' => $request->only(['user_id', 'action', 'date_from', 'date_to', 'search', 'per_page'])
            ]);
        } catch (\Exception $e) {
            \Log::error('Audit logs error: ' . $e->getMessage());

            return Inertia::render('Admin/AuditLogs', [
                'logs' => [
                    'data' => [],
                    'current_page' => 1,
                    'last_page' => 1,
                    'per_page' => 50,
                    'total' => 0,
                    'from' => 0,
                    'to' => 0,
                    'links' => []
                ],
                'users' => [],
                'actionTypes' => [],
                'filters' => []
            ]);
        }
    }

    public function announcements()
    {
        $currentUser = Auth::user();
        $query = Announcement::with(['creator', 'approver']);

        if ($currentUser->isSuperAdmin()) {
            $announcements = $query->latest()->paginate(20);
        } else {
            $announcements = $query->where(function($q) use ($currentUser) {
                $q->where('status', 'published')
                  ->orWhere('created_by', $currentUser->id);
            })->latest()->paginate(20);
        }

        $currentUser->announcements_last_seen_at = now();
        $currentUser->save();

        return Inertia::render('Admin/Announcements', [
            'announcements' => $announcements,
            'isSuperAdmin' => $currentUser->isSuperAdmin(),
        ]);
    }

    public function createAnnouncement(Request $request)
    {
        $currentUser = Auth::user();

        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255', 'regex:/^[a-zA-Z0-9\s\-&\'.,:!?()]+$/'],
            'message' => ['required', 'string', 'max:2000'],
            'type' => ['required', 'in:info,warning,success,danger'],
            'target_audience' => ['required', 'in:all,admins,users'],
            'expires_at' => ['nullable', 'date', 'after:now'],
        ]);

        $validated['title'] = trim($validated['title']);
        $validated['message'] = trim($validated['message']);

        $status = $currentUser->isSuperAdmin() ? 'published' : 'pending';

        $announcement = Announcement::create([
            'created_by' => $currentUser->id,
            'title' => $validated['title'],
            'message' => $validated['message'],
            'type' => $validated['type'],
            'target_audience' => $validated['target_audience'],
            'status' => $status,
            'approved_by' => $currentUser->isSuperAdmin() ? $currentUser->id : null,
            'approved_at' => $currentUser->isSuperAdmin() ? now() : null,
            'expires_at' => $validated['expires_at'] ?? null,
        ]);

        $this->logActivity('create_announcement', 'Announcement', $announcement->id, "Created announcement: {$validated['title']}");

        $message = $currentUser->isSuperAdmin()
            ? 'Announcement published successfully.'
            : 'Announcement submitted for approval.';

        return back()->with('success', $message);
    }

    public function reviewAnnouncement(Request $request, $id)
    {
        $currentUser = Auth::user();

        if (!$currentUser->isSuperAdmin()) {
            abort(403, 'Only super admin can review announcements.');
        }

        $announcement = Announcement::findOrFail($id);

        if ($announcement->status !== 'pending') {
            return back()->withErrors(['error' => 'This announcement has already been reviewed.']);
        }

        $validated = $request->validate([
            'status' => 'required|in:approved,rejected',
        ]);

        if ($validated['status'] === 'approved') {
            $announcement->status = 'published';
            $announcement->approved_by = $currentUser->id;
            $announcement->approved_at = now();
        } else {
            $announcement->status = 'rejected';
        }

        $announcement->save();

        $this->logActivity('review_announcement', 'Announcement', $announcement->id, ucfirst($validated['status']) . " announcement: {$announcement->title}");

        return back()->with('success', 'Announcement ' . $validated['status'] . ' successfully.');
    }

    public function deleteAnnouncement($id)
    {
        $currentUser = Auth::user();
        $announcement = Announcement::findOrFail($id);

        if (!$currentUser->isSuperAdmin() && $announcement->created_by !== $currentUser->id) {
            abort(403, 'You can only delete your own announcements.');
        }

        $title = $announcement->title;
        $announcement->delete();

        $this->logActivity('delete_announcement', 'Announcement', $id, "Deleted announcement: {$title}");

        return back()->with('success', 'Announcement deleted successfully.');
    }

    private function logActivity($action, $modelType, $modelId, $description)
    {
        ActivityLog::create([
            'user_id' => Auth::id(),
            'action' => $action,
            'model_type' => $modelType,
            'model_id' => $modelId,
            'description' => $description,
            'ip_address' => request()->ip(),
        ]);
    }
}
