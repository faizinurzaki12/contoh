@extends('layouts.siswa')

@section('title', 'Detail Karya')

@section('content')
    @php
        // Konfigurasi label dinamis per jurusan (sama seperti form upload)
        $defaultLabels = [
            'tech_label' => 'Teknologi / Alat yang Digunakan',
            'live_label' => 'Link Live Demo / Portfolio',
            'file_label' => 'Dokumentasi / Foto Karya',
        ];

        $overrides = [
            'PPLG' => [
                'tech_label' => 'Bahasa Pemrograman / Framework',
                'live_label' => 'Link Live Demo / Preview',
                'file_label' => 'Screenshot Tampilan Aplikasi',
            ],
            'TKJ' => [
                'tech_label' => 'Perangkat / Software Jaringan',
                'live_label' => 'Link Dokumentasi / Video Simulation',
                'file_label' => 'Foto / Skema Topologi Jaringan',
            ],
            'DKV' => [
                'tech_label' => 'Software Desain yang Digunakan',
                'live_label' => 'Link Portfolio (Behance/Dribbble/Drive)',
                'file_label' => 'File Hasil Desain',
            ],
            'TOI' => [
                'tech_label' => 'Jenis Alat / Mesin Otomasi',
                'live_label' => 'Link Video Demo Alat',
                'file_label' => 'Foto Alat / Mesin',
            ],
            'TSM' => [
                'tech_label' => 'Komponen / Alat / Teknologi',
                'live_label' => 'Link Video Demo / Dokumentasi',
                'file_label' => 'Foto Hasil Karya / Kendaraan',
            ],
        ];

        // Normalisasi key jurusan (TJKT disamakan dengan TKJ)
        $jurusanKey = strtoupper(trim($project->jurusan ?? ''));
        if ($jurusanKey === 'TJKT') {
            $jurusanKey = 'TKJ';
        }

        $labels = array_merge($defaultLabels, $overrides[$jurusanKey] ?? []);
        $isPplg = $jurusanKey === 'PPLG';

        // Warna baris "Catatan / Alasan Admin" mengikuti status project
        $note = match ($project->status) {
            'approved' => [
                'row'   => 'bg-[#ECFDF5]',
                'label' => 'bg-[#A7F3D0] text-emerald-950',
                'text'  => 'text-emerald-900',
                'badge' => 'bg-[#BBF7D0] text-emerald-950',
            ],
            'rejected' => [
                'row'   => 'bg-[#FFF1F2]',
                'label' => 'bg-[#FECDD3] text-rose-950',
                'text'  => 'text-rose-900',
                'badge' => 'bg-[#FDA4AF] text-rose-950',
            ],
            default => [
                'row'   => 'bg-[#FFFBEB]',
                'label' => 'bg-[#FDE68A] text-amber-950',
                'text'  => 'text-amber-900',
                'badge' => 'bg-[#FEF08A] text-amber-950',
            ],
        };
    @endphp

    {{-- Isi Halaman --}}
    <div class="flex-1 p-6 md:p-10 overflow-y-auto">
        <div class="max-w-3xl mx-auto">

            {{-- Tombol Kembali --}}
            <div class="mb-6">
                <a href="{{ url('/siswa/karya') }}"
                    class="inline-flex items-center gap-2 px-4 py-2 bg-white text-slate-800 text-sm font-extrabold rounded-xl neo-border neo-shadow-sm neo-btn">
                    ← Kembali ke Daftar Karya
                </a>
            </div>

            {{-- Card Container Utama --}}
            <div class="bg-white neo-border neo-shadow-lg rounded-2xl overflow-hidden relative min-h-[300px]">

                {{-- Loading Screen --}}
                <div id="loading-content"
                    class="absolute inset-0 bg-white/95 z-40 flex flex-col items-center justify-center transition-opacity duration-300 ease-out">
                    <div class="flex items-center gap-3 bg-[#E0E7FF] px-6 py-3 rounded-xl neo-border neo-shadow-sm">
                        <div class="w-5 h-5 border-3 border-slate-900 border-t-transparent rounded-full animate-spin"></div>
                        <p class="text-slate-900 font-black text-sm tracking-wide">Memuat detail karya...</p>
                    </div>
                </div>

                {{-- Header Card --}}
                <div class="px-6 py-5 border-b-[3px] border-slate-900 bg-[#FEF3C7]">
                    <h2 class="text-xl font-black text-slate-900">Detail Informasi Karya</h2>
                    <p class="text-xs font-extrabold text-slate-700 mt-1">Berikut rincian data project beserta umpan balik dari admin.</p>
                </div>

                {{-- Table Detail --}}
                <div class="p-6">
                    <div class="overflow-hidden neo-border rounded-xl">
                        <table class="w-full text-sm text-left">
                            <tbody class="divide-y-[3px] divide-slate-900 font-bold">

                                {{-- Judul Karya --}}
                                <tr class="bg-white">
                                    <td class="px-6 py-4 font-black text-slate-900 w-1/3 bg-[#F1F5F9] border-r-[3px] border-slate-900">Judul Karya</td>
                                    <td class="px-6 py-4 text-slate-900 font-extrabold">{{ $project->title }}</td>
                                </tr>

                                {{-- Jurusan --}}
                                <tr class="bg-white">
                                    <td class="px-6 py-4 font-black text-slate-900 w-1/3 bg-[#F1F5F9] border-r-[3px] border-slate-900">Jurusan</td>
                                    <td class="px-6 py-4">
                                        <span class="px-3 py-1 bg-[#818CF8] text-white rounded-full text-xs font-black neo-border">
                                            {{ $project->jurusan === 'TKJ' ? 'TJKT' : ($project->jurusan ?? '-') }}
                                        </span>
                                    </td>
                                </tr>

                                {{-- Teknologi / Alat (Dinamis per Jurusan) --}}
                                <tr class="bg-white">
                                    <td class="px-6 py-4 font-black text-slate-900 w-1/3 bg-[#F1F5F9] border-r-[3px] border-slate-900">
                                        {{ $labels['tech_label'] }}
                                    </td>
                                    <td class="px-6 py-4 text-slate-800 font-extrabold">
                                        {{ $project->technology_stack ?? '-' }}
                                    </td>
                                </tr>

                                {{-- Deskripsi --}}
                                <tr class="bg-white">
                                    <td class="px-6 py-4 font-black text-slate-900 w-1/3 bg-[#F1F5F9] border-r-[3px] border-slate-900">Deskripsi</td>
                                    <td class="px-6 py-4 leading-relaxed text-slate-800">{{ $project->description }}</td>
                                </tr>

                                {{-- URL GitHub (Khusus PPLG) --}}
                                @if ($isPplg)
                                    <tr class="bg-white">
                                        <td class="px-6 py-4 font-black text-slate-900 w-1/3 bg-[#F1F5F9] border-r-[3px] border-slate-900">URL GitHub</td>
                                        <td class="px-6 py-4">
                                            @if ($project->github_link)
                                                <a href="{{ $project->github_link }}" target="_blank" rel="noopener"
                                                    class="text-indigo-600 hover:text-indigo-800 font-extrabold underline inline-flex items-center gap-1 break-all">
                                                    {{ $project->github_link }} ↗
                                                </a>
                                            @else
                                                <span class="text-slate-500 font-bold">Belum diisi</span>
                                            @endif
                                        </td>
                                    </tr>
                                @endif

                                {{-- URL Project / Live Demo --}}
                                <tr class="bg-white">
                                    <td class="px-6 py-4 font-black text-slate-900 w-1/3 bg-[#F1F5F9] border-r-[3px] border-slate-900">
                                        {{ $labels['live_label'] }}
                                    </td>
                                    <td class="px-6 py-4">
                                        @if ($project->live_link)
                                            <a href="{{ $project->live_link }}" target="_blank" rel="noopener"
                                                class="text-indigo-600 hover:text-indigo-800 font-extrabold underline inline-flex items-center gap-1 break-all">
                                                {{ $project->live_link }} ↗
                                            </a>
                                        @else
                                            <span class="text-slate-500 font-bold">Tidak ada link</span>
                                        @endif
                                    </td>
                                </tr>

                                {{-- Link Embed / Iframe (Jika ada) --}}
                                @if (!empty($project->iframe_link))
                                    <tr class="bg-white">
                                        <td class="px-6 py-4 font-black text-slate-900 w-1/3 bg-[#F1F5F9] border-r-[3px] border-slate-900">Link Embed / Iframe</td>
                                        <td class="px-6 py-4">
                                            <a href="{{ $project->iframe_link }}" target="_blank" rel="noopener"
                                                class="text-indigo-600 hover:text-indigo-800 font-extrabold underline inline-flex items-center gap-1 break-all">
                                                {{ $project->iframe_link }} ↗
                                            </a>
                                        </td>
                                    </tr>
                                @endif

                                {{-- Dokumentasi Project (Foto) --}}
                                <tr class="bg-white">
                                    <td class="px-6 py-4 font-black text-slate-900 w-1/3 bg-[#F1F5F9] border-r-[3px] border-slate-900">
                                        {{ $labels['file_label'] }}
                                    </td>
                                    <td class="px-6 py-4">
                                        @if ($project->file_path)
                                            <div class="relative inline-block">
                                                <img src="{{ asset('storage/' . $project->file_path) }}" alt="Dokumentasi"
                                                    class="w-36 h-24 rounded-xl object-cover neo-border neo-shadow-sm cursor-pointer hover:opacity-90 transition-all neo-btn"
                                                    onclick="openModal(this.src)">
                                            </div>
                                        @else
                                            <span class="text-slate-500 font-bold">-</span>
                                        @endif
                                    </td>
                                </tr>

                                {{-- Catatan / Alasan Admin --}}
                                <tr class="{{ $note['row'] }}">
                                    <td class="px-6 py-4 font-black w-1/3 border-r-[3px] border-slate-900 {{ $note['label'] }}">Catatan / Alasan Admin</td>
                                    <td class="px-6 py-4">
                                        @if ($project->status == 'approved')
                                            <p class="{{ $note['text'] }} font-bold leading-relaxed">
                                                "{{ $project->approval_note ?? 'Project telah disetujui.' }}"
                                            </p>
                                        @elseif ($project->status == 'rejected')
                                            <p class="{{ $note['text'] }} font-bold leading-relaxed">
                                                "{{ $project->rejection_reason }}"
                                            </p>
                                        @else
                                            <p class="{{ $note['text'] }} font-bold leading-relaxed">
                                                Project masih menunggu review admin.
                                            </p>
                                        @endif

                                        @if ($project->reviewer)
                                            <span class="inline-block mt-3 text-xs px-3 py-1 rounded-full font-black neo-border {{ $note['badge'] }}">
                                                Dikirim oleh: {{ $project->reviewer->name }}
                                            </span>
                                        @endif
                                    </td>
                                </tr>

                            </tbody>
                        </table>
                    </div>
                </div>

            </div>
        </div>
    </div>

    {{-- ================= MODAL PREVIEW GAMBAR ================= --}}
    @if ($project->file_path)
        <div id="imageModal"
            class="fixed inset-0 z-50 hidden items-center justify-center bg-slate-900/60 backdrop-blur-xs p-4"
            onclick="closeModal()">
            <div class="relative max-w-3xl max-h-[90vh] bg-white rounded-2xl p-3 neo-border neo-shadow-lg"
                onclick="event.stopPropagation()">
                <button type="button" onclick="closeModal()" aria-label="Tutup preview"
                    class="absolute -top-4 -right-4 bg-[#FDA4AF] hover:bg-[#F43F5E] text-slate-900 rounded-full w-9 h-9 flex items-center justify-center font-black neo-border neo-shadow-sm cursor-pointer">
                    ✕
                </button>
                <img id="modalImage" src="" alt="Preview"
                    class="max-w-full max-h-[80vh] rounded-xl object-contain neo-border">
            </div>
        </div>
    @endif
@endsection

@push('scripts')
    <script>
        function openModal(imageSrc) {
            const modal = document.getElementById('imageModal');
            if (!modal) return;
            document.getElementById('modalImage').src = imageSrc;
            modal.classList.remove('hidden');
            modal.classList.add('flex');
            document.body.style.overflow = 'hidden';
        }

        function closeModal() {
            const modal = document.getElementById('imageModal');
            if (!modal) return;
            modal.classList.add('hidden');
            modal.classList.remove('flex');
            document.body.style.overflow = 'auto';
        }

        document.addEventListener('keydown', function (e) {
            if (e.key === 'Escape') closeModal();
        });

        window.addEventListener('load', function () {
            const loadingContent = document.getElementById('loading-content');
            setTimeout(() => {
                loadingContent.classList.add('opacity-0');
                setTimeout(() => loadingContent.classList.add('hidden'), 300);
            }, 800);
        });
    </script>
@endpush