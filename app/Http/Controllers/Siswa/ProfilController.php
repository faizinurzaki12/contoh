<?php

namespace App\Http\Controllers\Siswa;

use App\Support\MuseumCache;
use App\Http\Controllers\Controller;
use App\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules\Password;

class ProfilController extends Controller
{
    public function index()
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        $projects = $user->projects()
            ->withCount(['likes', 'comments'])
            ->with(['likes' => function($q) use ($user) {
                $q->where('user_id', $user->id);
            }])
            ->latest()
            ->get();

        // 20 komentar/balasan terbaru dari orang lain di semua karya siswa ini
        $comments = Comment::whereIn('project_id', $projects->pluck('id'))
            ->where('user_id', '!=', $user->id)
            ->with(['user', 'project:id,title'])
            ->latest()
            ->limit(20)
            ->get();

        return view('siswa.profil-siswa', compact('user', 'projects', 'comments'));
    }

    public function edit()
    {
        $user = Auth::user();
        return view('siswa.edit-profil', compact('user'));
    }

    public function update(Request $request)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        $validated = $request->validate([
            'name'         => 'required|string|max:255',
            'bio'          => 'nullable|string|max:500',
            'instagram'    => 'nullable|string|max:50', 
            'avatar'       => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'password'     => ['nullable', 'confirmed', Password::min(6)],
        ]);

        $user->name         = $validated['name'];
        $user->bio           = $request->bio;
        $user->instagram = $validated['instagram'] ?? null;

        if ($request->hasFile('avatar')) {
            // Hapus avatar lama jika ada (pastikan path lokal yang dihapus, bukan yang berawalan /storage/)
            if ($user->avatar) {
                // Ubah format '/storage/avatars/xxx.jpg' menjadi 'avatars/xxx.jpg' untuk dihapus disk storage
                $oldPath = str_replace('/storage/', '', $user->avatar);
                if (Storage::disk('public')->exists($oldPath)) {
                    Storage::disk('public')->delete($oldPath);
                }
            }

            // Simpan dan kompres avatar baru ke folder avatars/
            $filename = $this->compressAndSaveAvatar($request->file('avatar'));
            $user->avatar = '/storage/' . $filename;
        }

        if (!empty($validated['password'])) {
            $user->password = Hash::make($validated['password']);
        }

        $user->save();

        // Nama/foto/bio tampil di beranda, /karya & profil publik
        MuseumCache::flush('karya');

        return redirect()->route('siswa.profil')
            ->with('success', 'Profil berhasil diperbarui.');
    }

    /**
     * Private function untuk kompresi dan simpan foto avatar siswa
     */
    private function compressAndSaveAvatar($file)
    {
        // Generate nama file acak di dalam folder avatars/
        $filename = 'avatars/' . Str::random(20) . '.jpg';

        // Ambil data gambar asli
        $source = imagecreatefromstring(file_get_contents($file->getRealPath()));
        $width  = imagesx($source);
        $height = imagesy($source);

        // Resize max lebar 500px untuk avatar (cukup tajam untuk foto profil) dengan merawat aspect ratio
        $maxWidth = 500;
        if ($width > $maxWidth) {
            $newWidth  = $maxWidth;
            $newHeight = intval($height * ($maxWidth / $width));
        } else {
            $newWidth  = $width;
            $newHeight = $height;
        }

        // Buat kanvas baru
        $resized = imagecreatetruecolor($newWidth, $newHeight);

        // Latar belakang putih
        $whiteBackground = imagecolorallocate($resized, 255, 255, 255);
        imagefill($resized, 0, 0, $whiteBackground);

        // Proses resize gambar
        imagecopyresampled(
            $resized, $source,
            0, 0, 0, 0,
            $newWidth, $newHeight,
            $width, $height
        );

        // Algoritma Kompresi Target Max 50 KB untuk Avatar
        $maxFileSizeBytes = 50 * 1024;
        $quality = 85; 
        $compressedContent = '';

        do {
            ob_start();
            imagejpeg($resized, null, $quality);
            $compressedContent = ob_get_clean();

            // Turunkan kualitas jika masih di atas target ukuran
            $quality -= 5; 
        } while (strlen($compressedContent) > $maxFileSizeBytes && $quality >= 30);

        // Bersihkan memori server
        imagedestroy($source);
        imagedestroy($resized);

        // Simpan hasil akhir ke storage Laravel (disk public)
        Storage::disk('public')->put($filename, $compressedContent);

        return $filename;
    }
}