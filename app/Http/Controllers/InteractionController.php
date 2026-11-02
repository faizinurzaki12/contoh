<?php

namespace App\Http\Controllers;

use App\Support\MuseumCache;
use App\Models\Project;
use App\Models\ProjectLike;
use Illuminate\Support\Facades\Auth;

class InteractionController extends Controller
{
    // Like / Unlike project. Hanya bisa digunakan oleh user yang sudah login.
    public function toggleLike(Project $project)
    {
        // Jika belum login, kembalikan status 401 agar frontend bisa menangkap dan menampilkan modal login
        if (!Auth::check()) {
            return response()->json([
                'success' => false,
                'message' => 'Silakan login terlebih dahulu untuk memberikan apresiasi/like.'
            ], 401);
        }

        $userId = Auth::id();
        $liked = $this->toggleForUser($project, $userId);

        // Jumlah like tampil di beranda, /karya & profil -> hapus cache supaya langsung segar
        MuseumCache::flush('karya');

        return response()->json([
            'success'     => true,
            'liked'       => $liked,
            'likes_count' => $project->fresh()->likes_count,
        ]);
    }

    // User login: dicatat di tabel project_likes
    private function toggleForUser(Project $project, $userId): bool
    {
        $existingLike = ProjectLike::where('project_id', $project->id)
                                   ->where('user_id', $userId)
                                   ->first();

        if ($existingLike) {
            // Sudah dilike -> batalkan (Unlike)
            $existingLike->delete();
            $project->decrement('likes_count');
            return false;
        }

        ProjectLike::create([
            'project_id' => $project->id,
            'user_id'    => $userId,
        ]);
        $project->increment('likes_count');
        return true;
    }
}