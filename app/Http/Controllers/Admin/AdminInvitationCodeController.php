<?php

namespace App\Http\Controllers\Admin;

use App\Support\MuseumCache;
use App\Http\Controllers\Controller;
use App\Models\InvitationCode;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class AdminInvitationCodeController extends Controller
{
    public function index()
    {
        $codes = InvitationCode::latest()->get();
        return view('admin.kode-undangan', compact('codes'));
    }

    public function create()
    {
        return redirect()->route('admin.kode-undangan.index');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'code'        => 'required|string|max:255|unique:invitation_codes,code',
            'kelas'       => 'required|string|max:255',
            'jurusan'     => 'required|string|max:255',
            'description' => 'nullable|string|max:255',
            'max_uses'    => 'required|integer|min:1',
            'is_active'   => 'nullable|boolean',
            'expires_at'  => 'nullable|date|after:today',
        ]);

        $validated['is_active']  = $request->boolean('is_active', true);
        $validated['used_count'] = 0;
        $validated['created_by'] = Auth::id();

        InvitationCode::create($validated);

        MuseumCache::flush('stat'); // total kode undangan di dashboard super admin

        return redirect()->route('admin.kode-undangan.index')
            ->with('success', 'Kode undangan berhasil ditambahkan.');
    }

    public function edit(InvitationCode $kodeUndangan)
    {
        return redirect()->route('admin.kode-undangan.index');
    }

    public function update(Request $request, InvitationCode $kodeUndangan)
    {
        $validated = $request->validate([
            'code' => [
                'required', 'string', 'max:255',
                Rule::unique('invitation_codes', 'code')->ignore($kodeUndangan->id)
            ],
            'kelas'       => 'required|string|max:255',
            'jurusan'     => 'required|string|max:255',
            'description' => 'nullable|string|max:255',
            'max_uses'    => 'required|integer|min:1',
            'is_active'   => 'nullable|boolean',
            'expires_at'  => 'nullable|date',
        ]);

        $validated['is_active'] = $request->boolean('is_active');

        $kodeUndangan->update($validated);

        return redirect()->route('admin.kode-undangan.index')
            ->with('success', 'Kode undangan berhasil diperbarui.');
    }

    public function destroy(InvitationCode $kodeUndangan)
    {
        $kodeUndangan->delete();

        MuseumCache::flush('stat');

        return redirect()->route('admin.kode-undangan.index')
            ->with('success', 'Kode undangan berhasil dihapus.');
    }
}