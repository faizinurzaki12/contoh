<?php

namespace App\Http\Controllers;

use App\Models\ProjectLike;
use App\Models\User;
use App\Support\MuseumCache;
use Illuminate\Support\Facades\Auth;

class PublicProfileController extends Controller
{
    public function show($id)
    {
        // Jumlah like dibaca dari kolom projects.likes_count (sama seperti halaman detail),
        // jadi di sini cukup hitung komentar saja. Data profil sama untuk semua pengunjung -> di-cache.
        $user = MuseumCache::remember('karya', "profil.{$id}", function () use ($id) {
            return User::with(['projects' => fn ($query) => $query->withCount('comments')])
                ->findOrFail($id);
        });

        // ID project di halaman ini yang sudah di-like (user login: dari database, tamu: dari session)
        // Ini per-pengunjung, JANGAN di-cache.
        $likedIds = Auth::check()
            ? ProjectLike::where('user_id', Auth::id())
                ->whereIn('project_id', $user->projects->pluck('id'))
                ->pluck('project_id')
                ->all()
            : session('guest_likes', []);

        return view('profile.show', compact('user', 'likedIds'));
    }
}
