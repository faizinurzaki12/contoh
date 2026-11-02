<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\User;
use App\Support\MuseumCache;
use Illuminate\Support\Facades\Auth;

class AdminDashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // Cache dipisah per jurusan. Super Admin / admin tanpa jurusan = 'all'
        $scope = $user->isSuperAdmin() ? 'all' : (User::normalizeJurusan($user->jurusan) ?? 'none');

        $data = MuseumCache::remember('stat', "admin.dashboard.{$scope}", function () use ($user) {
            // Admin jurusan hanya menghitung karya & siswa jurusannya (Super Admin: semua)
            $projectQuery = Project::visibleToAdmin($user);
            $siswaQuery   = User::where('role', 2)->visibleToAdmin($user); // Role 2 = Siswa

            return [
                // Statistik
                'totalProject' => (clone $projectQuery)->count(),
                'pending'      => (clone $projectQuery)->pending()->count(),
                'approved'     => (clone $projectQuery)->approved()->count(),
                'totalSiswa'   => $siswaQuery->count(),

                // Overview 1: Karya Menunggu Moderasi (Max 5)
                'pendingProjects' => (clone $projectQuery)
                    ->with('user')
                    ->pending()
                    ->latest()
                    ->take(5)
                    ->get(),

                // Overview 2: Karya Terbaru yang Sudah Disetujui (Max 5)
                'approvedProjects' => (clone $projectQuery)
                    ->with('user')
                    ->approved()
                    ->latest()
                    ->take(5)
                    ->get(),
            ];
        }, MuseumCache::TTL_STAT);

        return view('admin.dashboard', $data + ['user' => $user]);
    }
}
