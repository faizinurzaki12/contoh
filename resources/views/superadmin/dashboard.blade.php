@extends('layouts.superadmin')

@section('title', 'Dashboard Super Admin')
@section('page_title', 'Dashboard Super Admin')

@section('content')

    {{-- ================= WELCOME BANNER ================= --}}
    <div class="bg-amber-200 border-[3px] border-black rounded-2xl shadow-[6px_6px_0px_0px_#000] p-6 mb-6">
        <h2 class="text-xl font-black text-black">Selamat datang, Super Admin!</h2>
        <p class="text-black mt-1 font-medium">Ini adalah dashboard khusus Super Admin.</p>
    </div>

    {{-- ================= RINGKASAN / STAT CARDS ================= --}}
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-6">

        <div class="bg-white border-[3px] border-black rounded-2xl shadow-[6px_6px_0px_0px_#000] p-5 flex items-center justify-between">
            <div>
                <p class="text-sm font-bold text-gray-700 uppercase">Total Karya</p>
                <p class="text-3xl font-black text-black">{{ $totalKarya }}</p>
            </div>
            <div class="w-12 h-12 border-[3px] border-black rounded-xl bg-violet-300 flex items-center justify-center text-black font-black">K</div>
        </div>

        <div class="bg-white border-[3px] border-black rounded-2xl shadow-[6px_6px_0px_0px_#000] p-5 flex items-center justify-between">
            <div>
                <p class="text-sm font-bold text-gray-700 uppercase">Total Siswa</p>
                <p class="text-3xl font-black text-black">{{ $totalSiswa }}</p>
            </div>
            <div class="w-12 h-12 border-[3px] border-black rounded-xl bg-amber-300 flex items-center justify-center text-black font-black">S</div>
        </div>

        <div class="bg-white border-[3px] border-black rounded-2xl shadow-[6px_6px_0px_0px_#000] p-5 flex items-center justify-between">
            <div>
                <p class="text-sm font-bold text-gray-700 uppercase">Kode Unik</p>
                <p class="text-3xl font-black text-black">{{ $totalKodeUnik }}</p>
            </div>
            <div class="w-12 h-12 border-[3px] border-black rounded-xl bg-red-300 flex items-center justify-center text-black font-black">U</div>
        </div>

    </div>

    {{-- ================= OVERVIEW KARYA PER JURUSAN (DONUT) ================= --}}
    <div class="bg-white border-[3px] border-black rounded-2xl shadow-[6px_6px_0px_0px_#000] p-6 mb-6">
        <h3 class="text-lg font-black text-black uppercase mb-4">Overview Karya per Jurusan</h3>

        @php
            $totalJ = $karyaPerJurusan->sum('total');
            $jurusanColorMap = [
                'PPLG' => '#198754',
                'TKJ'  => '#0d6efd',
                'TJKT' => '#0d6efd',
                'DKV'  => '#ffc107',
                'TOI'  => '#6c757d',
                'TSM'  => '#dc3545',
            ];
            $fallbackColors = ['#A78BFA','#4ECDC4','#F472B6','#FB923C'];
            $r = 70; $cx = 90; $cy = 90;
            $circumference = 2 * M_PI * $r;
            $offset = 0;
            $segments = [];
            foreach ($karyaPerJurusan as $i => $item) {
                $percent = $totalJ > 0 ? $item->total / $totalJ : 0;
                $len = $percent * $circumference;
                $key = strtoupper(trim($item->jurusan));
                $color = $jurusanColorMap[$key] ?? $fallbackColors[$i % count($fallbackColors)];
                $segments[] = [
                    'jurusan' => $item->jurusan,
                    'total'   => $item->total,
                    'percent' => round($percent * 100, 1),
                    'color'   => $color,
                    'len'     => $len,
                    'off'     => $offset,
                ];
                $offset += $len;
            }
        @endphp

        <div class="flex flex-col md:flex-row items-center gap-8">
            {{-- DONUT --}}
            <div class="relative shrink-0">
                <svg width="180" height="180" viewBox="0 0 180 180" class="-rotate-90">
                    <circle cx="{{ $cx }}" cy="{{ $cy }}" r="{{ $r }}" fill="none" stroke="#e5e7eb" stroke-width="26"/>
                    @foreach($segments as $seg)
                        <circle cx="{{ $cx }}" cy="{{ $cy }}" r="{{ $r }}" fill="none"
                            stroke="{{ $seg['color'] }}" stroke-width="26"
                            stroke-dasharray="{{ $seg['len'] }} {{ $circumference - $seg['len'] }}"
                            stroke-dashoffset="{{ -$seg['off'] }}" />
                    @endforeach
                    <circle cx="{{ $cx }}" cy="{{ $cy }}" r="{{ $r - 13 }}" fill="none" stroke="#000" stroke-width="3"/>
                    <circle cx="{{ $cx }}" cy="{{ $cy }}" r="{{ $r + 13 }}" fill="none" stroke="#000" stroke-width="3"/>
                </svg>
                <div class="absolute inset-0 flex flex-col items-center justify-center text-center px-4">
                    @if($jurusanTerbanyak)
                        <p class="text-[10px] font-bold uppercase text-gray-600">Terbanyak</p>
                        <p class="text-lg font-black text-black leading-tight">{{ $jurusanTerbanyak->jurusan }}</p>
                        <p class="text-xs font-bold text-gray-700">{{ $jurusanTerbanyak->total }} karya</p>
                    @else
                        <p class="text-sm font-bold text-gray-500">Belum ada data</p>
                    @endif
                </div>
            </div>

            {{-- LEGENDA --}}
            <div class="flex-1 w-full space-y-2">
                @forelse($segments as $seg)
                    <div class="flex items-center justify-between border-[3px] border-black rounded-xl px-3 py-2 {{ $loop->first ? 'bg-amber-100' : 'bg-white' }}">
                        <div class="flex items-center gap-2">
                            <span class="w-4 h-4 rounded-md border-2 border-black inline-block" style="background:{{ $seg['color'] }}"></span>
                            <span class="font-bold text-black text-sm">{{ $seg['jurusan'] }}</span>
                            @if($loop->first)
                                <span class="text-[10px] font-black bg-black text-white px-2 py-0.5 rounded-md">TOP</span>
                            @endif
                        </div>
                        <span class="font-black text-sm text-black">{{ $seg['total'] }} ({{ $seg['percent'] }}%)</span>
                    </div>
                @empty
                    <p class="text-gray-400 text-sm">Belum ada karya yang diupload.</p>
                @endforelse
            </div>
        </div>
    </div>

    {{-- ================= 2 TABEL BERDAMPINGAN (KIRI - KANAN) ================= --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

        {{-- ===== KIRI: KARYA TERBARU DARI SISWA ===== --}}
        <div class="bg-white border-[3px] border-black rounded-2xl shadow-[6px_6px_0px_0px_#000] p-6">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-black text-black uppercase">Karya Terbaru Siswa</h3>
                <a href="{{ url('/admin/karya') }}" class="text-sm font-bold text-black bg-violet-300 border-2 border-black rounded-lg px-2 py-1 hover:bg-violet-400">Lihat semua</a>
            </div>

            {{-- Tambahkan max-h-[350px] dan overflow-y-auto di sini --}}
            <div class="max-h-[350px] overflow-y-auto overflow-x-auto rounded-xl border-2 border-black">
                <table class="w-full text-sm text-left border-collapse">
                    <thead class="sticky top-0 z-10">
                        <tr class="bg-black text-white">
                            <th class="py-2 px-3 font-bold bg-black">Judul Karya</th>
                            <th class="py-2 px-3 font-bold bg-black">Siswa</th>
                            <th class="py-2 px-3 font-bold bg-black">Jurusan</th>
                            <th class="py-2 px-3 font-bold bg-black">Status</th>
                            <th class="py-2 px-3 font-bold bg-black">Direview Oleh</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($karyaTerbaru as $karya)
                            @php
                                $badgeClass = match($karya->status) {
                                    'approved' => 'bg-green-300 text-black',
                                    'rejected' => 'bg-red-300 text-black',
                                    'pending'  => 'bg-amber-300 text-black',
                                    default    => 'bg-gray-200 text-black',
                                };
                            @endphp
                            <tr class="border-t-2 border-black hover:bg-gray-50">
                                <td class="py-3 px-3 font-bold text-black">{{ $karya->title }}</td>
                                <td class="py-3 px-3 text-gray-700">{{ $karya->user->name ?? '-' }}</td>
                                <td class="py-3 px-3 text-gray-700">{{ $karya->jurusan }}</td>
                                <td class="py-3 px-3 whitespace-nowrap">
                                    <span class="inline-block px-2 py-1 text-xs font-bold border-2 border-black rounded-lg {{ $badgeClass }}">
                                        {{ $karya->getStatusLabel() }}
                                    </span>
                                </td>
                                <td class="py-3 px-3 text-gray-700">{{ $karya->reviewer->name ?? 'Belum direview' }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="py-6 text-center text-gray-400">Belum ada karya yang diupload.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- ===== KANAN: DAFTAR ADMIN & AKTIVITASNYA ===== --}}
        <div class="bg-white border-[3px] border-black rounded-2xl shadow-[6px_6px_0px_0px_#000] p-6">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-black text-black uppercase">Admin & Aktivitasnya</h3>
                <a href="{{ url('/superadmin/manajemen-admin') }}" class="text-sm font-bold text-black bg-violet-300 border-2 border-black rounded-lg px-2 py-1 hover:bg-violet-400">Kelola admin</a>
            </div>

            {{-- Tambahkan max-h-[350px] dan overflow-y-auto di sini juga --}}
            <div class="max-h-[350px] overflow-y-auto overflow-x-auto rounded-xl border-2 border-black">
                <table class="w-full text-sm text-left border-collapse">
                    <thead class="sticky top-0 z-10">
                        <tr class="bg-black text-white">
                            <th class="py-2 px-3 font-bold bg-black">Nama Admin</th>
                            <th class="py-2 px-3 font-bold bg-black">Email</th>
                            <th class="py-2 px-3 font-bold bg-black">Karya Direview</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($daftarAdmin as $admin)
                            <tr class="border-t-2 border-black hover:bg-gray-50">
                                <td class="py-3 px-3 font-bold text-black">{{ $admin->name }}</td>
                                <td class="py-3 px-3 text-gray-700">{{ $admin->email }}</td>
                                <td class="py-3 px-3">
                                    <span class="px-2 py-1 text-xs font-bold border-2 border-black rounded-lg bg-violet-200 text-black">
                                        {{ $admin->reviewed_projects_count }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="py-6 text-center text-gray-400">Belum ada data admin.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </div>

@endsection

@push('scripts')
    <script src="{{ asset('assets/js/superadmin/manajemen-admin.js') }}"></script>
@endpush