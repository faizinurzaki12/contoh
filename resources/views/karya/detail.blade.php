@extends('layouts.app')

@section('title', $project->title . ' - Museum Karya SMKN 4 Tasikmalaya')
@section('body_class', 'bg-[#fffdf9] dark:bg-zinc-950 text-zinc-900 dark:text-zinc-100 transition-colors duration-300 antialiased flex flex-col min-h-screen')

@push('styles')
    <style>
        .bg-grid-pattern {
            background-image:
                linear-gradient(to right, rgba(0, 0, 0, 0.05) 1px, transparent 1px),
                linear-gradient(to bottom, rgba(0, 0, 0, 0.05) 1px, transparent 1px);
            background-size: 30px 30px;
        }
        .dark .bg-grid-pattern {
            background-image:
                linear-gradient(to right, rgba(255, 255, 255, 0.03) 1px, transparent 1px),
                linear-gradient(to bottom, rgba(255, 255, 255, 0.03) 1px, transparent 1px);
        }

        /* Tombol neo-brutalist: "menekan" saat diklik */
        .nb-btn { transition: transform .1s, box-shadow .1s; }
        .nb-btn:active { transform: translate(2px, 2px); box-shadow: 0 0 0 0 #000; }

        /* Modal komentar: bottom sheet di HP, modal tengah di desktop */
        #comment-modal { transition: opacity .25s ease; }
        #comment-sheet { transition: transform .35s cubic-bezier(.16, 1, .3, 1); transform: translateY(100%); }
        #comment-modal.open { opacity: 1; pointer-events: auto; }
        #comment-modal.open #comment-sheet { transform: none; }
        @media (min-width: 640px) {
            #comment-sheet { transform: translateY(20px) scale(.96); }
        }

        .scrollbar-thin::-webkit-scrollbar { width: 6px; }
        .scrollbar-thin::-webkit-scrollbar-thumb { background: #18181b; border-radius: 6px; }
    </style>
@endpush

@section('content')
@php
    $u = $project->user;
    $uAv = $u->avatar ?? null;
    $uAvUrl = $uAv
        ? (str_starts_with($uAv, 'http') ? $uAv : (str_starts_with($uAv, '/storage') ? asset($uAv) : asset('storage/' . $uAv)))
        : null;
    $showImage = $project->file_path && (empty($project->file_type) || str_starts_with($project->file_type, 'image/'));
@endphp

<section class="flex-grow bg-grid-pattern py-8">
    <div class="mx-auto max-w-3xl px-4 lg:px-8 space-y-6">

        <a href="{{ url('/karya') }}"
           class="nb-btn inline-block px-4 py-2 bg-yellow-300 text-zinc-900 font-black text-xs rounded-xl border-2 border-zinc-900 shadow-[3px_3px_0px_#18181b]">
            ← Kembali ke Museum Karya
        </a>

        <article class="bg-white dark:bg-zinc-900 rounded-3xl overflow-hidden border-[3px] border-zinc-900 dark:border-zinc-100 shadow-[8px_8px_0px_0px_#18181b] dark:shadow-[8px_8px_0px_0px_#f4f4f5]">

            {{-- Media --}}
            <div class="w-full h-64 sm:h-96 bg-zinc-100 dark:bg-zinc-800 border-b-[3px] border-zinc-900 dark:border-zinc-100 flex items-center justify-center overflow-hidden">
                @if($showImage)
                    <img src="{{ asset('storage/' . $project->file_path) }}" alt="{{ $project->title }}" class="w-full h-full object-contain">
                @elseif($project->live_link)
                    <iframe src="{{ $project->live_link }}" loading="lazy" class="w-full h-full border-0"></iframe>
                @else
                    <span class="text-xs font-bold text-zinc-400">Tidak ada preview</span>
                @endif
            </div>

            <div class="p-5 sm:p-7 space-y-5">

                <div class="space-y-3">
                    <span class="inline-block px-2.5 py-1 bg-yellow-300 text-zinc-900 text-[11px] font-black rounded-lg border-2 border-zinc-900 shadow-[2px_2px_0px_#18181b]">
                        {{ $project->jurusan ?? 'Umum' }}
                    </span>
                    <h1 class="text-2xl sm:text-3xl font-black tracking-tight">{{ $project->title }}</h1>

                    {{-- Klik nama => profil siswa --}}
                    <a href="{{ $u ? url('/u/' . $u->id) : '#' }}" class="group flex items-center gap-3 w-fit">
                        <div class="relative overflow-hidden w-10 h-10 bg-yellow-300 text-zinc-900 border-2 border-zinc-900 rounded-xl flex items-center justify-center font-black text-sm shadow-[2px_2px_0px_#18181b]">
                            @if($uAvUrl)
                                <img src="{{ $uAvUrl }}" alt="{{ $u->name }}" class="absolute inset-0 w-full h-full object-cover">
                            @else
                                {{ strtoupper(substr($u->name ?? '-', 0, 1)) }}
                            @endif
                        </div>
                        <div>
                            <p class="text-[10px] font-bold text-zinc-500">Dibuat oleh</p>
                            <p class="font-black text-sm text-sky-600 dark:text-sky-400 group-hover:underline">{{ $u->name ?? 'Anonim' }}</p>
                        </div>
                    </a>
                </div>

                {{-- Info singkat --}}
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                    <div class="bg-zinc-100 dark:bg-zinc-800 p-3 rounded-xl border-2 border-zinc-900 dark:border-zinc-100">
                        <p class="text-[10px] text-zinc-500 font-black">Tahun</p>
                        <p class="font-black text-xs">{{ $project->created_at?->format('Y') ?? '-' }}</p>
                    </div>
                    <div class="bg-zinc-100 dark:bg-zinc-800 p-3 rounded-xl border-2 border-zinc-900 dark:border-zinc-100">
                        <p class="text-[10px] text-zinc-500 font-black">Teknologi</p>
                        <p class="font-black text-xs break-words">{{ $project->technology_stack ?: '-' }}</p>
                    </div>
                    <div class="bg-zinc-100 dark:bg-zinc-800 p-3 rounded-xl border-2 border-zinc-900 dark:border-zinc-100">
                        <p class="text-[10px] text-zinc-500 font-black">Guru pengampu</p>
                        <p class="font-black text-xs break-words">{{ $project->guru_pengampu ?: '-' }}</p>
                    </div>
                </div>

                <div>
                    <h2 class="font-black text-xs text-zinc-500 dark:text-zinc-400 mb-1">Tentang project</h2>
                    <p class="text-sm font-bold leading-relaxed whitespace-pre-line">{{ $project->description }}</p>
                </div>

                @if($project->github_link || $project->live_link)
                    <div class="flex flex-wrap gap-2">
                        @if($project->github_link)
                            <a href="{{ $project->github_link }}" target="_blank" rel="noopener"
                               class="nb-btn flex-1 min-w-[120px] py-2.5 bg-zinc-900 dark:bg-zinc-100 text-white dark:text-zinc-900 font-black text-xs rounded-xl border-2 border-zinc-900 dark:border-zinc-100 shadow-[3px_3px_0px_#000] text-center">GitHub</a>
                        @endif
                        @if($project->live_link)
                            <a href="{{ $project->live_link }}" target="_blank" rel="noopener"
                               class="nb-btn flex-1 min-w-[120px] py-2.5 bg-yellow-300 text-zinc-900 font-black text-xs rounded-xl border-2 border-zinc-900 shadow-[3px_3px_0px_#000] text-center">Live Demo</a>
                        @endif
                    </div>
                @endif

                {{-- Bar interaksi: like, komentar, bagikan --}}
                <div class="flex flex-wrap items-center gap-2 pt-5 border-t-2 border-dashed border-zinc-300 dark:border-zinc-700">
                    <button type="button" id="like-btn" onclick="toggleLike()"
                            class="nb-btn flex items-center gap-2 px-4 py-2 bg-white dark:bg-zinc-800 font-black text-xs rounded-xl border-2 border-zinc-900 dark:border-zinc-100 shadow-[3px_3px_0px_#18181b] dark:shadow-[3px_3px_0px_#f4f4f5] cursor-pointer">
                        <svg id="like-icon" class="w-5 h-5 {{ $liked ? 'text-red-500' : '' }}" fill="{{ $liked ? 'currentColor' : 'none' }}" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                        </svg>
                        <span id="like-count">{{ $project->likes_count ?? 0 }}</span>
                    </button>

                    <button type="button" onclick="openCommentModal()"
                            class="nb-btn flex items-center gap-2 px-4 py-2 bg-sky-300 text-zinc-900 font-black text-xs rounded-xl border-2 border-zinc-900 shadow-[3px_3px_0px_#18181b] cursor-pointer">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                        </svg>
                        <span><span id="comment-count">{{ $commentsCount }}</span> Komentar</span>
                    </button>

                    <button type="button" onclick="shareProject(this)"
                            class="nb-btn ml-auto px-4 py-2 bg-white dark:bg-zinc-800 font-black text-xs rounded-xl border-2 border-zinc-900 dark:border-zinc-100 shadow-[3px_3px_0px_#18181b] dark:shadow-[3px_3px_0px_#f4f4f5] cursor-pointer">
                        <span>Bagikan</span>
                    </button>
                </div>
            </div>
        </article>
    </div>
</section>

{{-- ===== MODAL KOMENTAR (langsung ada kolom ketik, tanpa pindah halaman) ===== --}}
<div id="comment-modal" class="fixed inset-0 z-50 bg-zinc-950/70 backdrop-blur-xs flex items-end sm:items-center justify-center opacity-0 pointer-events-none">
    <div id="comment-sheet"
         class="w-full sm:max-w-lg h-[85vh] sm:h-[700px] sm:max-h-[90vh] flex flex-col overflow-hidden bg-white dark:bg-zinc-900 text-zinc-900 dark:text-zinc-100 border-[3px] border-zinc-900 dark:border-zinc-100 rounded-t-3xl sm:rounded-3xl shadow-[8px_8px_0px_0px_#18181b] dark:shadow-[8px_8px_0px_0px_#f4f4f5]">

        {{-- Header --}}
        <div class="px-5 py-3 border-b-[3px] border-zinc-900 dark:border-zinc-100 bg-yellow-300 text-zinc-900 flex items-center justify-between shrink-0">
            <h3 class="font-black text-base">Komentar</h3>
            <button type="button" onclick="closeCommentModal()" aria-label="Tutup"
                    class="nb-btn w-8 h-8 bg-white border-2 border-zinc-900 rounded-lg font-black shadow-[2px_2px_0px_#000] cursor-pointer">✕</button>
        </div>

        {{-- Daftar komentar --}}
        <div id="comments-container" class="flex-1 overflow-y-auto scrollbar-thin p-4 sm:p-5 space-y-5">
            @forelse($comments as $comment)
                @include('karya._comment', ['c' => $comment, 'reply' => false])
            @empty
                <div id="no-comments" class="text-center py-16 text-xs font-bold text-zinc-500">
                    Belum ada komentar. Jadilah yang pertama memberi apresiasi!
                </div>
            @endforelse
        </div>

        {{-- Kolom ketik --}}
        <div class="shrink-0 border-t-[3px] border-zinc-900 dark:border-zinc-100 bg-white dark:bg-zinc-900">
            @auth
                <div id="reply-bar" class="hidden items-center justify-between px-4 py-2 text-[11px] font-black bg-sky-100 dark:bg-sky-950 border-b-2 border-zinc-900 dark:border-zinc-100">
                    <span>Membalas <span id="reply-name"></span></span>
                    <button type="button" onclick="cancelReply()" class="text-red-600 hover:underline cursor-pointer">Batal</button>
                </div>

                <form id="comment-form" onsubmit="postComment(event)" class="p-3 flex items-center gap-2">
                    <input type="text" id="comment-input" required maxlength="500" autocomplete="off" placeholder="Tulis komentar atau apresiasi..."
                           class="flex-1 min-w-0 px-4 py-2.5 text-xs font-bold rounded-xl border-2 border-zinc-900 dark:border-zinc-100 bg-zinc-50 dark:bg-zinc-800 focus:outline-none focus:bg-yellow-50 dark:focus:bg-zinc-700">
                    <button type="submit" id="submit-btn"
                            class="nb-btn px-4 py-2.5 bg-yellow-300 text-zinc-900 text-xs font-black rounded-xl border-2 border-zinc-900 shadow-[2px_2px_0px_#000] shrink-0 cursor-pointer disabled:opacity-60">Kirim</button>
                </form>
            @else
                <div class="p-4 text-center text-xs font-bold text-zinc-600 dark:text-zinc-400">
                    <a href="{{ route('login.required', ['next' => request()->getPathInfo() . '#komentar']) }}" class="font-black text-sky-600 dark:text-sky-400 underline">Login</a> untuk ikut berkomentar & membalas.
                </div>
            @endauth
        </div>
    </div>
</div>
@endsection

@push('scripts')
<div id="karya-config" hidden
     data-project-id="{{ $project->id }}"
     data-csrf="{{ csrf_token() }}"
     data-comment-url="{{ url('/project/' . $project->id . '/comment') }}"
     data-like-url="{{ url('/project/' . $project->id . '/like') }}"
     data-login-url="{{ route('login.required') }}"></div>

<script>
    const cfg         = document.getElementById('karya-config').dataset;
    const PROJECT_ID  = cfg.projectId;
    const CSRF        = cfg.csrf;
    const COMMENT_URL = cfg.commentUrl;
    const LIKE_URL    = cfg.likeUrl;

    // Kirim tamu ke login untuk komentar/balas, lalu kembali ke halaman ini
    function goLogin(hash = '') {
        location.href = cfg.loginUrl + '?next=' + encodeURIComponent(location.pathname + hash);
    }

    const modal      = document.getElementById('comment-modal');
    const container  = document.getElementById('comments-container');
    const input      = document.getElementById('comment-input');   // null jika belum login
    const replyBar   = document.getElementById('reply-bar');
    let parentId = null;

    // ===== Modal =====
    function openCommentModal() {
        modal.classList.add('open');
        document.body.classList.add('overflow-hidden');
        setTimeout(() => input && input.focus(), 350);
    }
    function closeCommentModal() {
        modal.classList.remove('open');
        document.body.classList.remove('overflow-hidden');
    }
    modal.addEventListener('click', e => { if (e.target === modal) closeCommentModal(); });
    document.addEventListener('keydown', e => { if (e.key === 'Escape') closeCommentModal(); });

    // Buka otomatis kalau URL berakhiran #komentar (mis. dari halaman profil / setelah login)
    if (location.hash === '#komentar') openCommentModal();

    // ===== Balas & lihat balasan (event delegation) =====
    container.addEventListener('click', e => {
        const replyBtn = e.target.closest('[data-reply]');
        if (replyBtn) return setReplyTo(replyBtn.dataset.id, replyBtn.dataset.name);

        const toggleBtn = e.target.closest('[data-toggle]');
        if (toggleBtn) toggleReplies(toggleBtn.closest('.comment-item'));
    });

    function setReplyTo(id, name) {
        if (!input) return goLogin('#komentar');
        parentId = id;
        input.placeholder = 'Membalas @' + name + '...';
        document.getElementById('reply-name').textContent = '@' + name;
        replyBar.classList.remove('hidden');
        replyBar.classList.add('flex');
        input.focus();
    }
    function cancelReply() {
        parentId = null;
        if (!input) return;
        input.placeholder = 'Tulis komentar atau apresiasi...';
        replyBar.classList.add('hidden');
        replyBar.classList.remove('flex');
    }
    function toggleReplies(item, forceOpen) {
        const list  = item.querySelector('.replies-list');
        const label = item.querySelector('[data-toggle]');
        const open  = forceOpen ?? list.classList.contains('hidden');
        list.classList.toggle('hidden', !open);
        label.textContent = open ? 'Sembunyikan balasan' : `Lihat ${list.children.length} balasan`;
    }

    // ===== Kirim komentar =====
    function postComment(e) {
        e.preventDefault();
        const text = input.value.trim();
        if (text) send({ comment: text, type: 'text' });
    }

    async function send(payload) {
        const btn = document.getElementById('submit-btn');
        if (btn) { btn.disabled = true; btn.textContent = '...'; }
        try {
            const res = await fetch(COMMENT_URL, {
                method: 'POST',
                headers: { 'X-CSRF-TOKEN': CSRF, 'Content-Type': 'application/json', 'Accept': 'application/json' },
                body: JSON.stringify({ ...payload, parent_id: parentId })
            });
            if (res.status === 401 || res.status === 419) return goLogin('#komentar'); // belum login / session habis
            const data = await res.json();
            if (!res.ok) throw new Error(data.message || 'Terjadi kesalahan pada server.');

            addComment(data);
            input.value = '';
            cancelReply();
        } catch (err) {
            alert('Gagal mengirim komentar: ' + err.message);
        } finally {
            if (btn) { btn.disabled = false; btn.textContent = 'Kirim'; }
        }
    }

    function addComment(data) {
        document.getElementById('no-comments')?.remove();

        if (data.parent_id) {
            const item = container.querySelector(`.comment-item[data-id="${data.parent_id}"]`);
            if (item) {
                item.querySelector('.replies-wrap').classList.remove('hidden');
                item.querySelector('.replies-list').insertAdjacentHTML('beforeend', data.html);
                toggleReplies(item, true);
            }
        } else {
            container.insertAdjacentHTML('afterbegin', data.html);
            container.scrollTop = 0;
        }
        document.getElementById('comment-count').textContent = data.total_comments;
    }

    // ===== Like (tamu & user login boleh, klik lagi = unlike) =====
    const likeIcon = document.getElementById('like-icon');
    const likeBtn  = document.getElementById('like-btn');

    function paintLike(liked) {
        likeIcon.setAttribute('fill', liked ? 'currentColor' : 'none');
        likeIcon.classList.toggle('text-red-500', liked);
    }

    async function toggleLike() {
        likeBtn.disabled = true;
        try {
            const res = await fetch(LIKE_URL, {
                method: 'POST',
                headers: { 'X-CSRF-TOKEN': CSRF, 'Accept': 'application/json' }
            });
            if (!res.ok) throw new Error();
            const data = await res.json();
            document.getElementById('like-count').textContent = data.likes_count;
            paintLike(data.liked);
        } catch (_) {
            alert('Gagal memproses like.');
        } finally {
            likeBtn.disabled = false;
        }
    }

    // ===== Bagikan =====
    function shareProject(btn) {
        const label = btn.querySelector('span');
        navigator.clipboard.writeText(location.href.split('#')[0]).then(() => {
            label.textContent = 'Tersalin!';
            setTimeout(() => label.textContent = 'Bagikan', 1500);
        });
    }
</script>
@endpush