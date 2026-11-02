<?php

namespace App\Http\Controllers;

use App\Support\MuseumCache;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use App\Models\User;
use App\Models\InvitationCode;

class AuthController extends Controller
{
    public function showLogin() {
        return view('auth.login');
    }
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($credentials)) {
            // 1. Wajib regenerate session untuk keamanan & hapus sisa session lama
            $request->session()->regenerate();

            $user = Auth::user();

            // 2. Bersihkan intended session agar tidak meredirect balik ke POST request yang salah
            session()->forget('url.intended');

            // 3. Pengecekan role yang aman (mensupport integer maupun string)
            $role = is_numeric($user->role) ? (int) $user->role : $user->role;

            if ($role === 0 || $role === '0' || $role === 'superadmin') {
                return redirect('/superadmin/dashboard');
            } 
            
            if ($role === 1 || $role === '1' || $role === 'admin') {
                return redirect('/admin/dashboard');
            } 
            
            if ($role === 2 || $role === '2' || $role === 'siswa') {
                return redirect('/siswa/dashboard');
            }

            // Jika role tidak cocok sama sekali
            Auth::logout();
            return back()->withErrors(['email' => 'Role tidak dikenali.']);
        }

        return back()->withErrors([
            'email' => 'Email atau password salah.',
        ]);
    }
    public function showRegister()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $request->validate([
            'name'      => 'required|string|max:255',
            'email'     => 'required|email|unique:users,email',
            'password'  => 'required|string|min:6',
            'kode_unik' => 'required|string',
        ]);

        $invitationCode = InvitationCode::where('code', $request->kode_unik)->first();

        if (!$invitationCode || !$invitationCode->isValid()) {
            throw ValidationException::withMessages([
                'kode_unik' => 'Kode undangan tidak valid, sudah penuh, atau sudah tidak aktif.',
            ]);
        }

        $user = DB::transaction(function () use ($request, $invitationCode) {
            $user = User::create([
                'name'               => $request->name,
                'email'              => $request->email,
                'password'           => Hash::make($request->password),
                'role'               => 2, // siswa
                'status'             => 'approved',
                'kelas'              => $invitationCode->kelas,
                'jurusan'            => $invitationCode->jurusan,
                'invitation_code_id' => $invitationCode->id,
            ]);

            $invitationCode->increment('used_count');

            return $user;
        });

        // Siswa baru -> total siswa di beranda & dashboard ikut berubah
        MuseumCache::flush('karya', 'stat');

        Auth::login($user);

        return redirect('/siswa/dashboard');
    }

    /**
     * Halaman input kode undangan (khusus setelah Login Google)
     */
    public function showKodeUndangan()
    {
        if (!session()->has('google_user')) {
            return redirect('/login')->with('error', 'Silakan login dengan Google terlebih dahulu.');
        }

        return view('auth.kode-undangan');
    }

    /**
     * Proses kode undangan dari Google
     */
    public function submitKodeUndangan(Request $request)
    {
        $request->validate([
            'kode_unik' => 'required|string',
        ]);

        if (!session()->has('google_user')) {
            return redirect('/login')->with('error', 'Sesi Google telah habis. Silakan login ulang.');
        }

        $invitationCode = InvitationCode::where('code', $request->kode_unik)->first();

        if (!$invitationCode || !$invitationCode->isValid()) {
            throw ValidationException::withMessages([
                'kode_unik' => 'Kode undangan tidak valid, sudah penuh, atau sudah tidak aktif.',
            ]);
        }

        $googleUser = session('google_user');

        $user = DB::transaction(function () use ($googleUser, $invitationCode) {
            $user = User::create([
                'google_id'          => $googleUser['google_id'],
                'name'               => $googleUser['name'],
                'email'              => $googleUser['email'],
                'avatar'             => $googleUser['avatar'],
                'password'           => null,
                'role'               => 2,
                'status'             => 'approved',
                'kelas'              => $invitationCode->kelas,
                'jurusan'            => $invitationCode->jurusan,
                'invitation_code_id' => $invitationCode->id,
            ]);

            $invitationCode->increment('used_count');

            return $user;
        });

        session()->forget('google_user');

        // Siswa baru -> total siswa di beranda & dashboard ikut berubah
        MuseumCache::flush('karya', 'stat');

        Auth::login($user);

        return redirect('/siswa/dashboard');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login');
    }
}