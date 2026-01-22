<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Traits\LogActivity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class UserController extends Controller
{
    use LogActivity;

    public function promote(Request $request, $id)
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

    public function demote(Request $request, $id)
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

    public function edit($id)
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

    public function update(Request $request, $id)
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

    public function destroy($id)
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
}
