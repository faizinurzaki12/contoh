<?php

namespace App\Http\Controllers\Admin;

use App\Support\MuseumCache;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminSiswaController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->search;

        $query = User::where('role', 2)
            ->visibleToAdmin(Auth::user()) // admin jurusan hanya melihat siswa jurusannya
            ->with('invitationCode')
            ->when($search, function ($query) use ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', "%$search%")
                    ->orWhere('email', 'like', "%$search%")
                    ->orWhere('kelas', 'like', "%$search%");
                });
            });

        $totalSiswa = (clone $query)->count();

        $siswas = $query->latest()
            ->simplePaginate(10)
            ->withQueryString();

        return view('admin.siswa', compact('siswas', 'totalSiswa'));
    }

    public function update(Request $request, User $user)
    {
        abort_unless($user->isManageableBy(Auth::user()), 403, 'Siswa ini bukan dari jurusanmu.');

        $request->validate([
            'name' => 'required',
            'kelas' => 'required',
            'email' => 'required|email',
        ]);

        $user->update([
            'name' => $request->name,
            'kelas' => $request->kelas,
            'email' => $request->email,
        ]);

        MuseumCache::flush('karya');

        return back()->with('success','Data siswa berhasil diubah.');
    }

    public function destroy(User $user)
    {
        abort_unless($user->isManageableBy(Auth::user()), 403, 'Siswa ini bukan dari jurusanmu.');

        $user->delete();

        MuseumCache::flush('karya', 'stat');

        return back()->with('success','Data siswa berhasil dihapus.');
    }
}