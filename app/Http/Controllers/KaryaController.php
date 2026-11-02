<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Support\MuseumCache;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class KaryaController extends Controller
{
    public function index()
    {
        $karyas = MuseumCache::remember('karya', 'karya.index', function () {
            return Project::with('user')
                ->approved()
                ->latest()
                ->get();
        });

        return view('karya', compact('karyas'));
    }

    public function show(Project $project)
    {
        $project->load('user');

        // Komentar + jumlahnya di-cache per karya (5 menit).
        // Otomatis "dihapus" saat ada komentar baru (lihat comment() di bawah).
        $cached = MuseumCache::remember("komentar.{$project->id}", 'tree', function () use ($project) {
            return [
                'comments' => $project->comments()
                    ->whereNull('parent_id')
                    ->with(['user', 'replies.user'])
                    ->latest()
                    ->get(),
                'count'    => $project->comments()->count(),
            ];
        }, 300);

        $comments      = $cached['comments'];
        $commentsCount = $cached['count'];

        // Sudah di-like? Ini per-pengunjung, JANGAN di-cache.
        // User login dicek dari database, tamu dari session
        $liked = Auth::check()
            ? $project->likes()->where('user_id', Auth::id())->exists()
            : in_array($project->id, session('guest_likes', []));

        return view('karya.detail', compact('project', 'comments', 'commentsCount', 'liked'));
    }

    // Like ditangani InteractionController@toggleLike (tamu & user login)

    public function comment(Request $request, Project $project)
    {
        $data = $request->validate([
            'comment'    => 'required|string|max:500',
            'type'       => 'required|in:text,gif',
            // GIF hanya boleh dari Giphy, supaya tidak bisa disisipi URL sembarangan
            'attachment' => 'nullable|required_if:type,gif|url|starts_with:https://media.giphy.com/',
            'parent_id'  => 'nullable|integer',
        ]);

        // Balasan selalu ditempelkan ke komentar induk (gaya Instagram, hanya 1 level)
        $parentId = null;
        if (!empty($data['parent_id'])) {
            $parent   = $project->comments()->findOrFail($data['parent_id']);
            $parentId = $parent->parent_id ?? $parent->id;
        }

        $comment = $project->comments()->create([
            'user_id'    => $request->user()->id,
            'parent_id'  => $parentId,
            'body'       => $data['comment'],
            'type'       => $data['type'],
            'attachment' => $data['type'] === 'gif' ? $data['attachment'] : null,
        ]);

        // Hapus cache komentar karya ini + list/profil (jumlah komentar tampil di profil)
        MuseumCache::flush("komentar.{$project->id}", 'karya');

        $comment->load('user');

        return response()->json([
            'success'        => true,
            'parent_id'      => $comment->parent_id,
            'html'           => view('karya._comment', [
                                    'c'     => $comment,
                                    'reply' => (bool) $comment->parent_id,
                                ])->render(),
            'total_comments' => $project->comments()->count(),
        ]);
    }
}
