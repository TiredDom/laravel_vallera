<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\UserEditRequest;
use App\Traits\LogActivity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class EditRequestController extends Controller
{
    use LogActivity;

    public function index()
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

    public function store(Request $request, $id)
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

    public function review(Request $request, $id)
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
}
