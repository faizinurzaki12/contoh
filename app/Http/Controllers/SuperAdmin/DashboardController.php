<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\User;
use App\Models\InvitationCode;
use App\Support\MuseumCache;

class DashboardController extends Controller
{
    public function index()
    {
        $data = MuseumCache::remember('stat', 'superadmin.dashboard', function () {
            // ================= KARYA PER JURUSAN (buat donut chart) =================
            $karyaPerJurusan = Project::selectRaw('jurusan, count(*) as total')
                ->groupBy('jurusan')
                ->orderByDesc('total')
                ->get();

            return [
                // ================= RINGKASAN / TOTAL DATA =================
                'totalKarya'    => Project::count(),
                'totalSiswa'    => User::where('role', 2)->count(),
                'totalKodeUnik' => InvitationCode::count(),

                // ================= KARYA TERBARU DARI SISWA =================
                'karyaTerbaru'  => Project::with(['user', 'reviewer'])
                    ->latest()
                    ->take(5)
                    ->get(),

                // ================= DAFTAR ADMIN & AKTIVITASNYA =================
                'daftarAdmin'   => User::where('role', 1)
                    ->withCount('reviewedProjects')
                    ->latest()
                    ->take(10)
                    ->get(),

                'karyaPerJurusan'  => $karyaPerJurusan,

                // jurusan yang paling banyak ngirim karya
                'jurusanTerbanyak' => $karyaPerJurusan->first(),
            ];
        }, MuseumCache::TTL_STAT);

        return view('superadmin.dashboard', $data);
    }
}
