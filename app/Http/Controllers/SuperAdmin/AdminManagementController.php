<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Support\MuseumCache;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AdminManagementController extends Controller
{
    public function index(Request $request)
    {
        $admins = User::whereIn('role', [0, 1]);

        if ($request->search) {
            $admins->where(function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('email', 'like', '%' . $request->search . '%')
                  ->orWhere('jurusan', 'like', '%' . $request->search . '%');
            });
        }

        $admins = $admins->latest()->paginate(10);

        return view('superadmin.manajemen-admin', compact('admins'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users,email',
            'password' => 'required|string|min:6',
            'role'     => 'required|in:0,1',
            'jurusan'  => 'nullable|required_if:role,1|string', // Wajib diisi jika Role 1 (Admin Jurusan)
        ]);

        User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
            'role'     => (int) $request->role,
            'jurusan'  => $request->role == 1 ? $request->jurusan : null,
            'status'   => 'approved',
        ]);

        MuseumCache::flush('karya', 'stat');

        return redirect()->back()->with('success', 'Akun Admin berhasil ditambahkan!');
    }

    public function update(Request $request, User $user)
    {
        if (!in_array($user->role, [0, 1])) {
            return back()->with('error', 'User tidak valid.');
        }

        $request->validate([
            'name'    => 'required|string|max:255',
            'email'   => 'required|email|unique:users,email,' . $user->id,
            'role'    => 'required|in:0,1',
            'jurusan' => 'nullable|required_if:role,1|string',
        ]);

        $user->name    = $request->name;
        $user->email   = $request->email;
        $user->role    = $request->role;
        $user->jurusan = $request->role == 1 ? $request->jurusan : null;

        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        $user->save();

        MuseumCache::flush('karya', 'stat');

        return back()->with('success', 'Data Admin berhasil diperbarui!');
    }

    public function destroy(User $user)
    {
        if (!in_array($user->role, [0, 1])) {
            return back()->with('error', 'User tidak valid.');
        }

        if ($user->id === auth()->id()) {
            return back()->with('error', 'Tidak bisa menghapus akun sendiri.');
        }

        $user->delete();

        MuseumCache::flush('karya', 'stat');

        return back()->with('success', 'Admin berhasil dihapus');
    }
}