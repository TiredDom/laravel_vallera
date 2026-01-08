<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class AdminController extends Controller
{
    public function dashboard()
    {
        $user = Auth::user();
        $users = User::orderBy('created_at', 'desc')->get();

        $stats = [
            'totalUsers' => User::count(),
            'adminUsers' => User::where('is_admin', true)->count(),
            'regularUsers' => User::where('is_admin', false)->count(),
            'valleraUsers' => User::where('email', 'like', '%@vallera.com')->count(),
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

        return back()->with('success', 'User demoted successfully.');
    }
}

