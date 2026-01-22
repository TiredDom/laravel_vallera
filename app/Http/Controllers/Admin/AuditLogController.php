<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class AuditLogController extends Controller
{
    public function index(Request $request)
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
}
