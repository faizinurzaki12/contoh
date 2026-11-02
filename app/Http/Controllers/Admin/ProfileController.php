<?php

namespace App\Http\Controllers\Admin;

use App\Support\MuseumCache;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    public function edit()
    {
        $user = Auth::user();
        return view('admin.profile', compact('user'));
    }

    public function update(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'name'                  => 'required|string|max:255',
            'email'                 => 'required|email|unique:users,email,' . $user->id,
            'password'              => 'nullable|string|min:6|confirmed',
        ], [
            'name.required'         => 'Nama tidak boleh kosong.',
            'email.required'        => 'Email tidak boleh kosong.',
            'email.unique'          => 'Email sudah digunakan oleh akun lain.',
            'password.min'          => 'Password minimal 6 karakter.',
            'password.confirmed'    => 'Konfirmasi password tidak cocok.',
        ]);

        $user->name  = $request->name;
        $user->email = $request->email;

        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        $user->save();

        MuseumCache::flush('artikel', 'stat');

        return back()->with('success', 'Profil berhasil diperbarui!');
    }
}