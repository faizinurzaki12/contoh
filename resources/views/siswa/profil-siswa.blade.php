@extends('layouts.siswa')

@section('title', 'Profil Saya')

@section('content')
    @php
        $avatarUrl = '';
        if (!empty($user->avatar)) {
            $avatarUrl = str_starts_with($user->avatar, 'http')
                ? $user->avatar
                : (str_starts_with($user->avatar, '/storage') ? asset($user->avatar) : asset('storage/' . $user->avatar));
        }
    @endphp

    {{-- Header Topbar --}}

    <div class="flex-1 p-8 overflow-y-auto">
        <section class="max-w-4xl mx-auto bg-white neo-border neo-shadow-lg rounded-2xl p-6 md:p-10">

            {{-- Bagian Atas: Foto & Nama --}}
            <div class="flex flex-col items-center text-center">
                <div class="p-1.5 rounded-full bg-[#C7D2FE] neo-border">
                    <div class="w-28 h-28 md:w-32 md:h-32 rounded-full overflow-hidden relative border-4 border-white bg-[#818CF8] flex items-center justify-center">
                        @if (!empty($user->avatar))
                            <img src="{{ $avatarUrl }}" alt="{{ $user->name }}" data-avatar="{{ $avatarUrl }}"
                                onclick="openModalFromEl(this)"
                                class="w-full h-full object-cover cursor-pointer hover:opacity-90 transition absolute inset-0">
                        @else
                            <span class="text-white text-4xl font-black">{{ strtoupper(substr($user->name, 0, 1)) }}</span>
                        @endif
                    </div>
                </div>

                <h2 class="mt-4 text-xl md:text-2xl font-black text-slate-900">{{ $user->name }}</h2>

                <div class="mt-2 flex flex-wrap items-center justify-center gap-x-3 gap-y-1 text-sm font-bold text-slate-500">
                    @if (!empty($user->kelas))
                        <span>{{ $user->kelas }}</span>
                        <span class="text-slate-300">•</span>
                    @endif
                    <span>{{ $user->jurusan ?? 'PPLG' }}</span>
                    <span class="text-slate-300">•</span>
                    <span>{{ $projects->count() }} Karya</span>
                </div>
                <p class="mt-3 max-w-xl text-sm font-bold text-slate-600">{{ $user->bio ?? 'Belum ada bio.' }}</p>

                {{-- TAMBAHAN: Link Instagram --}}
                @if(!empty($user->instagram))
                    @php
                        // Ambil URL/username dari database
                        $rawIg = trim($user->instagram);

                        // Jika berbentuk URL, ambil username di bagian paling akhir
                        if (str_starts_with($rawIg, 'http://') || str_starts_with($rawIg, 'https://')) {
                            $path = parse_url($rawIg, PHP_URL_PATH);
                            $username = trim($path, '/');
                            $igUrl = $rawIg;
                        } else {
                            // Jika pengguna hanya menginput @username atau username
                            $username = ltrim($rawIg, '@');
                            $igUrl = 'https://instagram.com/' . $username;
                        }
                    @endphp

                    <a href="{{ $igUrl }}" target="_blank" rel="noopener noreferrer" 
                        class="mt-3 inline-flex items-center gap-1.5 text-xs font-black text-pink-600 hover:text-pink-700 bg-pink-50 hover:bg-pink-100 px-3 py-1.5 rounded-full border-2 border-pink-500 transition">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/>
                        </svg>
                        <span>Instagram</span>
                    </a>
                @endif

                <a href="{{ route('siswa.profil.edit') }}"
                    class="mt-5 inline-flex items-center gap-2 px-5 py-2.5 bg-[#818CF8] text-white text-sm font-extrabold rounded-xl neo-border neo-shadow-sm neo-btn">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                    </svg>
                    Edit Profil
                </a>
            </div>

            {{-- Bagian Bawah: Karya Siswa --}}
            <div>
                <h3 class="font-black text-lg text-slate-900 mb-4">My Karya Gue</h3>

                @if ($projects->count() > 0)
                    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6">
                        @foreach ($projects as $project)
                            @php
                                $extension = $project->file_path ? strtolower(pathinfo($project->file_path, PATHINFO_EXTENSION)) : '';
                                $isImage = in_array($extension, ['jpg', 'jpeg', 'png', 'webp', 'gif']);
                                $isVideo = in_array($extension, ['mp4', 'webm', 'ogg', 'mov']);
                                $fileUrl = $project->file_path ? asset('storage/' . $project->file_path) : '';

                                [$statusLabel, $statusClass] = match ($project->status) {
                                    'approved' => ['Disetujui', 'bg-[#BBF7D0] text-emerald-800'],
                                    'pending'  => ['Menunggu', 'bg-[#FED7AA] text-amber-800'],
                                    default    => ['Ditolak', 'bg-[#FDA4AF] text-rose-900'],
                                };
                            @endphp

                            <article class="group bg-white neo-border neo-shadow rounded-2xl overflow-hidden flex flex-col justify-between"
                                data-title="{{ $project->title }}"
                                data-desc="{{ $project->description }}"
                                data-jurusan="{{ $project->jurusan ?? 'PPLG' }}"
                                data-status-label="{{ $statusLabel }}"
                                data-status-class="{{ $statusClass }}"
                                data-tech="{{ $project->technology_stack ?? '' }}"
                                data-live="{{ $project->live_link ?? '' }}"
                                data-file-path="{{ $fileUrl }}"
                                data-file-type="{{ $isImage ? 'image' : ($isVideo ? 'video' : '') }}"
                                data-tahun="{{ $project->created_at->format('Y') }}"
                                data-detail-url="{{ route('project.detail', $project->id) }}">

                                <div>
                                    {{-- Thumbnail (klik = buka detail; video dibiarkan agar kontrol tetap bisa dipakai) --}}
                                    <div class="h-36 flex items-center justify-center bg-[#E0E7FF] border-b-[3px] border-slate-900 overflow-hidden {{ $isVideo ? '' : 'cursor-pointer' }}"
                                        @unless ($isVideo) onclick="openProfilModal(this.closest('article'))" @endunless>
                                        @if ($isImage)
                                            <img src="{{ $fileUrl }}" alt="{{ $project->title }}"
                                                class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-200">
                                        @elseif ($isVideo)
                                            <video class="w-full h-full object-cover" controls>
                                                <source src="{{ $fileUrl }}" type="video/{{ $extension }}">
                                                Browser kamu tidak mendukung pemutar video.
                                            </video>
                                                                                    @elseif ($project->live_link)
                                            {{-- Preview website dari kolom live_link (iframe diperkecil supaya muat di kotak) --}}
                                            <div class="relative w-full h-full overflow-hidden pointer-events-none">
                                                <iframe src="{{ $project->live_link }}" title="Preview {{ $project->title }}"
                                                    loading="lazy" sandbox="allow-scripts allow-same-origin"
                                                    class="absolute top-0 left-0 border-0 origin-top-left"
                                                    style="width: 400%; height: 400%; transform: scale(0.25);"></iframe>
                                            </div>
                                        @else
                                            <div class="flex flex-col items-center justify-center text-slate-900 p-4 text-center">
                                                <svg class="w-10 h-10 mb-1" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                                </svg>
                                                <span class="text-xs font-extrabold">Preview Web / Aplikasi</span>
                                            </div>
                                        @endif
                                    </div>

                                    {{-- Info (klik = buka detail) --}}
                                    <div class="p-4 cursor-pointer" onclick="openProfilModal(this.closest('article'))">
                                        <div class="flex items-start justify-between gap-2">
                                            <h4 class="font-extrabold text-slate-900 text-sm leading-snug">{{ $project->title }}</h4>
                                            <span class="shrink-0 text-[11px] font-black px-2 py-0.5 rounded-full neo-border {{ $statusClass }}">{{ $statusLabel }}</span>
                                        </div>
                                        <p class="mt-1 text-xs font-bold text-slate-400">{{ $project->jurusan ?? 'PPLG' }}</p>
                                    </div>
                                </div>

                                {{-- Interaksi: Like, Komentar, Share --}}
                                <div class="px-4 py-3 border-t-[3px] border-slate-900 bg-[#FFFDF9] flex items-center justify-between text-sm">
                                    <div class="flex items-center gap-4">
                                        <button type="button" data-id="{{ $project->id }}" onclick="toggleLikeFromEl(this)"
                                            class="flex items-center gap-1.5 text-slate-700 hover:text-red-500 transition group cursor-pointer">
                                            <svg id="like-icon-{{ $project->id }}"
                                                class="w-5 h-5 transition transform group-active:scale-125 {{ $project->likes->isNotEmpty() ? 'text-red-500' : '' }}"
                                                fill="{{ $project->likes->isNotEmpty() ? 'currentColor' : 'none' }}" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                                            </svg>
                                            <span id="like-count-{{ $project->id }}" class="font-extrabold text-xs">{{ $project->likes_count ?? 0 }}</span>
                                        </button>

                                        <a href="{{ route('project.detail', $project->id) }}"
                                            class="flex items-center gap-1.5 text-slate-700 hover:text-indigo-600 transition">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                                            </svg>
                                            <span class="font-extrabold text-xs">{{ $project->comments_count ?? 0 }}</span>
                                        </a>
                                    </div>

                                    <button type="button" data-url="{{ route('project.detail', $project->id) }}" onclick="shareProject(this)"
                                        class="text-slate-700 hover:text-emerald-600 transition cursor-pointer" aria-label="Salin link karya">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.368 2.684 3 3 0 00-5.368-2.684z"></path>
                                        </svg>
                                    </button>
                                </div>
                            </article>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-16 bg-[#F8FAFC] rounded-2xl neo-border border-dashed">
                        <p class="text-slate-500 text-sm font-extrabold">Belum ada karya yang di-upload.</p>
                    </div>
                @endif
            </div>

            <div class="mt-8 mb-6 border-t-[3px] border-slate-900 border-dashed"></div>

            {{-- Bagian Komentar: komentar & balasan dari orang lain di karya siswa --}}
            <div>
                <h3 class="font-black text-lg text-slate-900 mb-4">Komentar di Karya Gue</h3>

                @if ($comments->count() > 0)
                    <div class="space-y-4">
                        @foreach ($comments as $c)
                            @php
                                $cName = $c->user->name ?? 'Anonim';
                                $cAv = $c->user->avatar ?? null;
                                $cAvUrl = $cAv
                                    ? (str_starts_with($cAv, 'http') ? $cAv : (str_starts_with($cAv, '/storage') ? asset($cAv) : asset('storage/' . $cAv)))
                                    : null;
                                $cLink = route('project.detail', $c->project_id) . '#komentar';
                            @endphp

                            <article class="bg-white neo-border neo-shadow-sm rounded-2xl p-4 flex items-start gap-3">
                                <div class="w-10 h-10 rounded-full overflow-hidden relative neo-border bg-[#818CF8] flex items-center justify-center shrink-0">
                                    @if ($cAvUrl)
                                        <img src="{{ $cAvUrl }}" alt="{{ $cName }}" class="absolute inset-0 w-full h-full object-cover">
                                    @else
                                        <span class="text-white text-sm font-black">{{ strtoupper(substr($cName, 0, 1)) }}</span>
                                    @endif
                                </div>

                                <div class="flex-1 min-w-0">
                                    <div class="flex flex-wrap items-center gap-x-2">
                                        <span class="font-black text-sm text-slate-900">{{ $cName }}</span>
                                        <span class="text-xs font-bold text-slate-400">{{ $c->created_at->diffForHumans() }}</span>
                                    </div>

                                    <p class="mt-0.5 text-xs font-bold text-slate-500">
                                        {{ $c->parent_id ? 'Membalas di' : 'Berkomentar di' }}
                                        <a href="{{ $cLink }}" class="font-extrabold text-indigo-600 hover:underline">{{ $c->project->title ?? 'Karya' }}</a>
                                    </p>

                                    @if ($c->type === 'gif')
                                        <img src="{{ $c->attachment }}" alt="GIF" loading="lazy"
                                            class="mt-2 w-full max-w-[200px] rounded-xl neo-border">
                                    @else
                                        <p class="mt-2 text-sm font-bold text-slate-700 break-words whitespace-pre-line">{{ $c->body }}</p>
                                    @endif

                                    <a href="{{ $cLink }}"
                                        class="mt-3 inline-block px-3 py-1.5 bg-[#FEF3C7] text-slate-900 text-xs font-extrabold rounded-xl neo-border neo-shadow-sm neo-btn">
                                        Lihat & balas
                                    </a>
                                </div>
                            </article>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-12 bg-[#F8FAFC] rounded-2xl neo-border border-dashed">
                        <p class="text-slate-500 text-sm font-extrabold">Belum ada komentar dari orang lain.</p>
                    </div>
                @endif
            </div>
        </section>
    </div>

    {{-- ================= MODAL LIGHTBOX FOTO PROFIL ================= --}}
    <div id="imageModal" class="hidden fixed inset-0 bg-slate-900/70 z-[60] items-center justify-center p-4"
        onclick="if (event.target === this) closeModal()">
        <button type="button" onclick="closeModal()" aria-label="Tutup"
            class="absolute top-5 right-5 w-10 h-10 flex items-center justify-center bg-white text-slate-900 neo-border neo-shadow-sm rounded-full neo-btn cursor-pointer">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12" />
            </svg>
        </button>
        <img id="modalImage" src="" alt="Foto Profil"
            class="max-w-full max-h-[85vh] rounded-2xl neo-border neo-shadow-lg object-contain">
    </div>

    {{-- ================= MODAL DETAIL KARYA ================= --}}
    <div id="profilDetailModal" class="hidden fixed inset-0 bg-slate-900/50 z-50 items-center justify-center p-4"
        onclick="if (event.target === this) closeProfilModal()">
        <div class="bg-white neo-border neo-shadow-lg rounded-2xl max-w-2xl w-full max-h-[90vh] overflow-y-auto">
            <div class="sticky top-0 px-6 py-4 border-b-[3px] border-slate-900 flex justify-between items-center bg-white rounded-t-2xl">
                <h3 id="profilModalTitle" class="text-xl font-black text-slate-900"></h3>
                <button type="button" onclick="closeProfilModal()" aria-label="Tutup"
                    class="w-8 h-8 flex items-center justify-center bg-white text-slate-900 neo-border rounded-full neo-btn cursor-pointer">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <div class="p-6 space-y-5">
                {{-- Preview --}}
                <div>
                    <img id="profilModalImage" alt="Preview karya" class="hidden w-full rounded-xl neo-border object-contain max-h-72" />
                    <video id="profilModalVideo" controls class="hidden w-full rounded-xl neo-border max-h-72"></video>
                    <iframe id="profilModalIframe" class="hidden w-full h-64 rounded-xl neo-border" allowfullscreen></iframe>
                    <div id="profilModalEmpty" class="hidden w-full h-64 items-center justify-center rounded-xl bg-[#F1F5F9] text-slate-500 font-bold neo-border border-dashed">
                        Tidak ada preview
                    </div>
                </div>

                {{-- Status & Jurusan --}}
                <div class="flex gap-2 flex-wrap">
                    <span id="profilModalStatus" class="px-3 py-1 rounded-full text-sm font-black neo-border"></span>
                    <span id="profilModalJurusan" class="bg-[#E0E7FF] text-indigo-800 px-3 py-1 rounded-full text-sm font-black neo-border"></span>
                </div>

                {{-- Deskripsi --}}
                <div>
                    <h4 class="font-black text-slate-900 mb-1">Deskripsi</h4>
                    <p id="profilModalDesc" class="text-slate-700 text-sm font-bold leading-relaxed"></p>
                </div>

                {{-- Info Tambahan --}}
                <div class="grid grid-cols-2 gap-4">
                    <div class="bg-[#F8FAFC] p-3 rounded-xl neo-border">
                        <p class="text-xs font-bold text-slate-500 mb-1">Tahun</p>
                        <p id="profilModalTahun" class="font-black text-slate-900"></p>
                    </div>
                    <div class="bg-[#F8FAFC] p-3 rounded-xl neo-border">
                        <p class="text-xs font-bold text-slate-500 mb-1">Teknologi</p>
                        <p id="profilModalTech" class="font-black text-slate-900 text-sm"></p>
                    </div>
                </div>

                {{-- Tombol aksi --}}
                <div class="pt-2 flex flex-wrap gap-3">
                    <a id="profilDetailBtn" href="#"
                        class="inline-block px-5 py-2.5 bg-[#FEF3C7] text-slate-900 rounded-xl font-extrabold neo-border neo-shadow-sm neo-btn text-sm">
                        Lihat Halaman Detail
                    </a>
                    <a id="profilLiveBtn" href="#" target="_blank" rel="noopener"
                        class="hidden px-5 py-2.5 bg-[#818CF8] text-white rounded-xl font-extrabold neo-border neo-shadow-sm neo-btn text-sm">
                        Buka Live Demo
                    </a>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        const $id = (id) => document.getElementById(id);
        const show = (el, display = 'block') => { el.classList.remove('hidden'); if (display) el.classList.add(display); };
        const hide = (el, display = 'block') => { el.classList.add('hidden'); if (display) el.classList.remove(display); };

        // ===== Lightbox foto profil =====
        function openModalFromEl(imgElement) {
            $id('modalImage').src = imgElement.getAttribute('data-avatar');
            show($id('imageModal'), 'flex');
            document.body.style.overflow = 'hidden';
        }

        function closeModal() {
            hide($id('imageModal'), 'flex');
            document.body.style.overflow = 'auto';
        }

        // ===== Modal detail karya =====
        function openProfilModal(card) {
            if (!card) return;
            const d = card.dataset;

            $id('profilModalTitle').textContent = d.title || 'Detail Karya';
            $id('profilModalDesc').textContent = d.desc || 'Tidak ada deskripsi.';
            $id('profilModalJurusan').textContent = d.jurusan || '-';
            $id('profilModalTahun').textContent = d.tahun || '-';
            $id('profilModalTech').textContent = d.tech || '-';

            const statusEl = $id('profilModalStatus');
            statusEl.textContent = d.statusLabel || '-';
            statusEl.className = 'px-3 py-1 rounded-full text-sm font-black neo-border ' + (d.statusClass || '');

            // Preview: gambar / video / iframe live / kosong
            const img = $id('profilModalImage');
            const video = $id('profilModalVideo');
            const iframe = $id('profilModalIframe');
            const empty = $id('profilModalEmpty');

            hide(img, null); hide(video, null); hide(iframe, null); hide(empty, 'flex');
            img.src = ''; video.removeAttribute('src'); iframe.src = '';

            if (d.filePath && d.fileType === 'image') {
                img.src = d.filePath;
                show(img, null);
            } else if (d.filePath && d.fileType === 'video') {
                video.src = d.filePath;
                show(video, null);
            } else if (d.live) {
                iframe.src = d.live;
                show(iframe, null);
            } else {
                show(empty, 'flex');
            }

            // Tombol
            $id('profilDetailBtn').href = d.detailUrl || '#';
            const liveBtn = $id('profilLiveBtn');
            if (d.live) {
                liveBtn.href = d.live;
                show(liveBtn, 'inline-block');
            } else {
                liveBtn.href = '#';
                hide(liveBtn, 'inline-block');
            }

            show($id('profilDetailModal'), 'flex');
            document.body.style.overflow = 'hidden';
        }

        function closeProfilModal() {
            hide($id('profilDetailModal'), 'flex');
            $id('profilModalIframe').src = '';
            const video = $id('profilModalVideo');
            video.pause();
            video.removeAttribute('src');
            document.body.style.overflow = 'auto';
        }

        document.addEventListener('keydown', function (e) {
            if (e.key === 'Escape') {
                closeModal();
                closeProfilModal();
            }
        });

        // ===== Like & Share =====
        function toggleLikeFromEl(button) {
            toggleLike(button.getAttribute('data-id'));
        }

        function toggleLike(projectId) {
            fetch(`/project/${projectId}/like`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                },
            })
            .then(response => {
                if (!response.ok) {
                    if (response.status === 401) {
                        alert('Silakan login terlebih dahulu untuk menyukai karya!');
                    }
                    throw new Error('Gagal memproses like');
                }
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    const countSpan = document.getElementById(`like-count-${projectId}`);
                    const likeIcon = document.getElementById(`like-icon-${projectId}`);

                    countSpan.textContent = data.likes_count;

                    if (data.liked) {
                        likeIcon.setAttribute('fill', 'currentColor');
                        likeIcon.classList.add('text-red-500');
                    } else {
                        likeIcon.setAttribute('fill', 'none');
                        likeIcon.classList.remove('text-red-500');
                    }
                }
            })
            .catch(error => console.error('Error:', error));
        }

        function shareProject(button) {
            const url = button.getAttribute('data-url');
            navigator.clipboard.writeText(url).then(() => {
                alert('Link karya berhasil disalin! Silakan bagikan ke temanmu.');
            }).catch(err => {
                console.error('Gagal menyalin:', err);
            });
        }
    </script>
@endpush