<!doctype html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />

    {{-- CSRF token agar fetch AJAX berhasil --}}
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link rel="icon" type="image/png" href="{{ asset('favicon.png') }}">
    <title>@yield('title', 'Student Dashboard') - Karya PPLG</title>

    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>

    {{-- Font: Plus Jakarta Sans --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">

    {{-- Helper neo-brutalist. Ditaruh di @layer components supaya utility Tailwind
         (mis. border-y-0, border-l-0) tetap bisa menimpanya. --}}
    @verbatim
    <style type="text/tailwindcss">
        @layer components {
            .neo-border { border: 3px solid #1e293b; }
            .neo-shadow-sm { box-shadow: 3px 3px 0px 0px #1e293b; }
            .neo-shadow { box-shadow: 5px 5px 0px 0px #1e293b; }
            .neo-shadow-lg { box-shadow: 7px 7px 0px 0px #1e293b; }
            .neo-btn { transition: all 0.15s ease-in-out; }
            .neo-btn:hover { transform: translate(-2px, -2px); box-shadow: 6px 6px 0px 0px #1e293b; }
            .neo-btn:active { transform: translate(2px, 2px); box-shadow: 2px 2px 0px 0px #1e293b; }
            .neo-input { border: 3px solid #1e293b; box-shadow: 3px 3px 0px 0px #1e293b; transition: all 0.15s ease-in-out; }
            .neo-input:focus { outline: none; box-shadow: 5px 5px 0px 0px #1e293b; transform: translate(-1px, -1px); }
        }
    </style>
    @endverbatim
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
    </style>

    @stack('styles')
</head>
<body class="bg-[#FAF7F2] text-[#1e293b]">
    @php
        // Daftar menu — dipakai di sidebar (desktop) dan dropdown (HP & tablet)
        $menus = [
            [
                'label'  => 'Dashboard',
                'url'    => url('/siswa/dashboard'),
                'active' => request()->is('siswa/dashboard*'),
                'icon'   => '<path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z" />',
            ],
            [
                'label'  => 'Profil',
                'url'    => url('/siswa/profil'),
                'active' => request()->is('siswa/profil*'),
                'icon'   => '<path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd" />',
            ],
            [
                'label'  => 'My Karya Gue',
                'url'    => url('/siswa/karya'),
                'active' => request()->is('siswa/karya*'),
                'icon'   => '<path d="M5.5 13a3.5 3.5 0 01-.369-6.98 4 4 0 117.753-1.3A4.5 4.5 0 1113.5 13H11V9.413l1.293 1.293a1 1 0 001.414-1.414l-3-3a1 1 0 00-1.414 0l-3 3a1 1 0 001.414 1.414L9 9.414V13H5.5z" />',
            ],
            [
                'label'  => 'Kirim Project',
                'url'    => url('/siswa/upload'),
                'active' => request()->is('siswa/upload*'),
                'icon'   => '<path fill-rule="evenodd" d="M3 17a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm3.293-7.293a1 1 0 011.414 0L9 10.586V3a1 1 0 112 0v7.586l1.293-1.293a1 1 0 111.414 1.414l-3 3a1 1 0 01-1.414 0l-3-3a1 1 0 010-1.414z" clip-rule="evenodd" />',
            ],
        ];

        // Class menu sidebar (desktop): aktif vs tidak aktif
        $navBase   = 'w-full flex items-center gap-3 px-4 py-3 rounded-xl text-sm neo-border neo-btn';
        $navActive = 'bg-[#818CF8] text-white font-extrabold neo-shadow-sm';
        $navIdle   = 'bg-white hover:bg-[#F1F5F9] text-slate-800 font-bold';
    @endphp

    <div class="flex h-dvh overflow-hidden">

        {{-- ================= SIDEBAR (hanya desktop, lg+) ================= --}}
        <aside id="sidebar"
            class="hidden lg:flex flex-col justify-between shrink-0 w-64 z-20 p-5 overflow-y-auto
                   bg-[#FFFDF9] neo-border border-y-0 border-l-0">
            <div>
                {{-- User Info --}}
                <div class="p-4 bg-[#E0E7FF] neo-border neo-shadow-sm rounded-2xl mb-6 flex items-center gap-3">
                        @php
                        $sideAv = Auth::user()->avatar;
                        $sideAvUrl = $sideAv
                            ? (str_starts_with($sideAv, 'http') ? $sideAv : (str_starts_with($sideAv, '/storage') ? asset($sideAv) : asset('storage/' . $sideAv)))
                            : null;
                    @endphp
                    @if ($sideAvUrl)
                        <img src="{{ $sideAvUrl }}" alt="Foto Profil" referrerpolicy="no-referrer"
                            class="w-10 h-10 neo-border rounded-xl object-cover shrink-0">
                    @else
                        <div class="w-10 h-10 bg-[#C7D2FE] neo-border rounded-xl flex items-center justify-center text-slate-900 font-black text-lg shrink-0">
                            {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                        </div>
                    @endif
                    <div class="min-w-0">
                        <p class="font-extrabold text-sm text-slate-900 leading-tight truncate">{{ Auth::user()->name }}</p>
                    </div>
                </div>

                {{-- Navigasi Sidebar --}}
                <nav class="space-y-3">
                    @foreach ($menus as $m)
                        <a href="{{ $m['url'] }}" @if ($m['active']) aria-current="page" @endif
                            class="{{ $navBase }} {{ $m['active'] ? $navActive : $navIdle }}">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">{!! $m['icon'] !!}</svg>
                            <span>{{ $m['label'] }}</span>
                        </a>
                    @endforeach

                    {{-- Menu ke museum utama --}}
                    <div class="pt-4 mt-4 border-t-[3px] border-[#1e293b]">
                        <a href="{{ url('/karya') }}"
                            class="{{ $navBase }} bg-[#BAE6FD] hover:bg-[#7DD3FC] text-slate-900 font-bold">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M10 12a2 2 0 100-4 2 2 0 000 4z" />
                                <path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd" />
                            </svg>
                            <span>Lihat Karya Siswa lain</span>
                        </a>
                    </div>
                </nav>
            </div>

            {{-- Tombol Logout --}}
            <div class="pt-6">
                <button type="button" onclick="openLogoutModal()"
                    class="w-full px-4 py-3 bg-[#FDA4AF] hover:bg-[#F43F5E] text-slate-900 rounded-xl font-extrabold neo-border neo-shadow-sm neo-btn cursor-pointer">
                    Logout
                </button>
            </div>
        </aside>

        {{-- ================= KONTEN (scroll vertikal) ================= --}}
        <div class="flex-1 min-w-0 flex flex-col overflow-y-auto">

            {{-- Topbar: tombol menu (HP/tablet) + judul halaman + nama user (HP/tablet).
                 Menu HP/tablet berupa dropdown yang turun tepat di bawah topbar. --}}
            <header id="siteHeader" class="sticky top-0 z-40 shrink-0 bg-white neo-border border-x-0 border-t-0 px-4 sm:px-6 lg:px-8 py-3 lg:py-5">
                <div class="flex items-center justify-between gap-3">
                    <div class="flex items-center gap-3 min-w-0">
                        <button id="menuToggle" type="button" onclick="toggleMobileMenu()"
                            aria-label="Buka menu" aria-expanded="false" aria-controls="mobileMenu"
                            class="lg:hidden shrink-0 w-11 h-11 flex items-center justify-center bg-[#818CF8] text-white neo-border neo-shadow-sm rounded-xl neo-btn cursor-pointer">
                            <svg id="menuIconOpen" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M4 6h16M4 12h16M4 18h16" />
                            </svg>
                            <svg id="menuIconClose" class="w-6 h-6 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                        <h1 class="text-lg sm:text-2xl font-black text-slate-900 tracking-tight leading-tight">
                            @hasSection('page_title') @yield('page_title') @else @yield('title') @endif
                        </h1>
                    </div>

                    <div class="lg:hidden shrink-0 max-w-[45%] text-right">
                        <p class="font-extrabold text-xs sm:text-sm text-slate-900 leading-tight truncate">{{ Auth::user()->name }}</p>
                        <p class="text-[10px] sm:text-xs font-bold text-slate-500">Siswa</p>
                    </div>
                </div>

                {{-- Dropdown menu (HP & tablet) --}}
                <nav id="mobileMenu"
                    class="hidden lg:hidden absolute left-0 right-0 top-full max-h-[calc(100dvh-4.5rem)] overflow-y-auto
                           bg-[#FFFDF9] border-b-[3px] border-slate-900 shadow-[0_5px_0_0_#1e293b] p-3 space-y-2">
                    @foreach ($menus as $m)
                        <a href="{{ $m['url'] }}" @if ($m['active']) aria-current="page" @endif
                            class="block px-4 py-3 rounded-xl text-sm
                                   {{ $m['active']
                                        ? 'bg-[#818CF8] text-white font-extrabold neo-border neo-shadow-sm'
                                        : 'text-slate-800 font-bold border-[3px] border-transparent hover:bg-[#F1F5F9]' }}">
                            {{ $m['label'] }}
                        </a>
                    @endforeach

                    <a href="{{ url('/karya') }}"
                        class="block px-4 py-3 rounded-xl text-sm bg-[#BAE6FD] hover:bg-[#7DD3FC] text-slate-900 font-bold neo-border">
                        Lihat Karya Siswa lain
                    </a>

                    <div class="border-t-2 border-slate-900 !mt-3 pt-3">
                        <button type="button" onclick="closeMobileMenu(); openLogoutModal()"
                            class="w-full px-4 py-3 bg-[#FDA4AF] hover:bg-[#F43F5E] text-slate-900 text-sm font-extrabold rounded-xl neo-border neo-shadow-sm neo-btn cursor-pointer">
                            Logout
                        </button>
                    </div>
                </nav>
            </header>

            @yield('content')
        </div>
    </div>

    {{-- ================= MODAL LOGOUT ================= --}}
    <div id="logoutModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-slate-900/40 backdrop-blur-xs p-4">
        <div class="bg-white neo-border neo-shadow-lg rounded-2xl max-w-md w-full overflow-hidden">
            <div class="p-6 text-center">
                <div class="w-16 h-16 bg-[#FFE4E6] neo-border rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8 text-[#F43F5E]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                            d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                    </svg>
                </div>

                <h3 class="text-xl font-black text-slate-900 mb-3">Logout?</h3>

                <p class="text-slate-700 text-xs font-bold leading-relaxed mb-6">
                    udah kirim karya?
                </p>

                <div class="flex gap-3">
                    <button type="button" onclick="closeLogoutModal()"
                        class="flex-1 px-3 py-3 bg-[#F1F5F9] hover:bg-[#E2E8F0] text-slate-800 neo-border rounded-xl text-xs font-extrabold neo-btn cursor-pointer">
                        Tidak
                    </button>

                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="flex-1">
                        @csrf
                        <button type="submit"
                            class="w-full px-3 py-3 bg-[#FDA4AF] hover:bg-[#F43F5E] text-slate-900 neo-border rounded-xl text-xs font-extrabold neo-btn cursor-pointer">
                            Yaa
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        // ===== Dropdown menu (HP & tablet) =====
        const mobileMenu = document.getElementById('mobileMenu');
        const menuToggleBtn = document.getElementById('menuToggle');
        const menuIconOpen = document.getElementById('menuIconOpen');
        const menuIconClose = document.getElementById('menuIconClose');

        function setMobileMenu(open) {
            mobileMenu.classList.toggle('hidden', !open);
            menuIconOpen.classList.toggle('hidden', open);
            menuIconClose.classList.toggle('hidden', !open);
            menuToggleBtn.setAttribute('aria-expanded', String(open));
            menuToggleBtn.setAttribute('aria-label', open ? 'Tutup menu' : 'Buka menu');
        }

        function closeMobileMenu() { setMobileMenu(false); }
        function toggleMobileMenu() {
            setMobileMenu(menuToggleBtn.getAttribute('aria-expanded') !== 'true');
        }

        // Tutup dropdown saat klik di luar topbar
        document.addEventListener('click', function (e) {
            if (!e.target.closest('#siteHeader')) closeMobileMenu();
        });

        // Kalau layar melebar ke desktop, reset dropdown
        window.matchMedia('(min-width: 1024px)').addEventListener('change', function (e) {
            if (e.matches) closeMobileMenu();
        });

        // ===== Modal logout =====
        function openLogoutModal() {
            closeMobileMenu();
            const modal = document.getElementById('logoutModal');
            modal.classList.remove('hidden');
            modal.classList.add('flex');
            document.body.style.overflow = 'hidden';
        }

        function closeLogoutModal() {
            const modal = document.getElementById('logoutModal');
            modal.classList.add('hidden');
            modal.classList.remove('flex');
            document.body.style.overflow = 'auto';
        }

        document.getElementById('logoutModal').addEventListener('click', function (e) {
            if (e.target === this) closeLogoutModal();
        });

        document.addEventListener('keydown', function (e) {
            if (e.key === 'Escape') {
                closeLogoutModal();
                closeMobileMenu();
            }
        });
    </script>

    @stack('scripts')
</body>
</html> 