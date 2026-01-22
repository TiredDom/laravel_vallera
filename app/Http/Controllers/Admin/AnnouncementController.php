<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Announcement;
use App\Traits\LogActivity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class AnnouncementController extends Controller
{
    use LogActivity;

    public function index()
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

    public function store(Request $request)
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

    public function review(Request $request, $id)
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

    public function destroy($id)
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
}
