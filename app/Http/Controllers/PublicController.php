<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\User;
use App\Support\MuseumCache;

class PublicController extends Controller
{
    public function index()
    {
        $data = MuseumCache::remember('karya', 'home', function () {
            $projects = Project::with('user')
                ->approved()
                ->latest()
                ->get();

            return [
                'projects'   => $projects,
                'totalKarya' => $projects->count(), // = jumlah karya approved, tanpa query tambahan
                'totalSiswa' => User::where('role', 2)->count(),
                'totalAdmin' => User::where('role', 1)->count(),
            ];
        });

        return view('index', $data);
    }
}
