<?php

namespace App\Http\Controllers\Admin;

use App\Support\MuseumCache;
use App\Http\Controllers\Controller;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class AdminProjectController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->search;
        $user = Auth::user();

        $projects = Project::with('user')
            // Admin jurusan hanya melihat karya jurusannya sendiri (Super Admin: semua)
            ->visibleToAdmin($user)
            // Filter Pencarian
            ->when($search, function ($query) use ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('title', 'like', "%{$search}%")
                      ->orWhereHas('user', function ($u) use ($search) {
                          $u->where('name', 'like', "%{$search}%");
                      })
                      ->orWhere('jurusan', 'like', "%{$search}%");
                });
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('admin.karya', compact('projects'));
    }

    public function updateStatus(Request $request, Project $project)
    {
        abort_unless($project->isManageableBy(Auth::user()), 403, 'Karya ini bukan dari jurusanmu.');

        $request->validate([
            'status' => 'required|in:approved,rejected',
            'catatan' => 'nullable|string'
        ]);

        if ($request->status == 'approved') {
            $project->update([
                'status' => 'approved',
                'approval_note' => $request->catatan,
                'reviewed_by' => Auth::id(),
                'reviewed_at' => now(),
            ]);
        } else {
            $project->update([
                'status' => 'rejected',
                'rejection_reason' => $request->catatan,
                'reviewed_by' => Auth::id(),
                'reviewed_at' => now(),
            ]);
        }

        MuseumCache::flush('karya', 'stat');

        return redirect('/admin/karya')
            ->with('success', 'Status berhasil diperbarui');
    }

    public function show(Project $project)
    {
        abort_unless($project->isManageableBy(Auth::user()), 403, 'Karya ini bukan dari jurusanmu.');

        // Muat relasi user (pemilik project) dan reviewer (admin yang mereview)
        $project->load(['user', 'reviewer']);

        return view('admin.detail-karya', compact('project'));
    }

    public function destroy(Project $project)
    {
        abort_unless($project->isManageableBy(Auth::user()), 403, 'Karya ini bukan dari jurusanmu.');

        if ($project->file_path) {
            Storage::disk('public')->delete($project->file_path);
        }

        $project->delete();

        MuseumCache::flush('karya', 'stat');

        return redirect('/admin/karya')
            ->with('success', 'Karya berhasil dihapus.');
    }
}