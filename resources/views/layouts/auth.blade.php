<!doctype html>
<html lang="id">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="icon" type="image/png" href="{{ asset('favicon.png') }}">
    <title>@yield('title', 'Karya PPLG')</title>

    <style type="text/tailwindcss">
      @custom-variant dark (&:where(.dark, .dark *));
    </style>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <link rel="stylesheet" href="{{ asset('assets/css/login.css') }}" />

    <style>
      .custom-nav-container {
        border: 3px solid #111827;
        box-shadow: 4px 4px 0px #111827;
        border-radius: 9999px;
      }
      .dark .custom-nav-container {
        border-color: #374151;
        box-shadow: 4px 4px 0px #374151;
      }
      .custom-btn-capsule {
        border: 2px solid #111827;
        box-shadow: 2px 2px 0px #111827;
      }
      .dark .custom-btn-capsule {
        border-color: #374151;
        box-shadow: 2px 2px 0px #111827;
      }

      /* Panel dropdown menu mobile (muncul di bawah pill navbar) */
      .mobile-nav-panel {
        border: 3px solid #111827;
        box-shadow: 4px 4px 0px #111827;
        max-height: 0;
        opacity: 0;
        transform: translateY(-10px) scaleY(0.95);
        transform-origin: top;
        overflow: hidden;
        pointer-events: none;
        transition: max-height 0.4s cubic-bezier(0.4, 0, 0.2, 1),
                    opacity 0.3s ease-in-out,
                    transform 0.35s cubic-bezier(0.34, 1.56, 0.64, 1);
      }
      .dark .mobile-nav-panel {
        border-color: #374151;
        box-shadow: 4px 4px 0px #374151;
      }
      .mobile-nav-panel.panel-open {
        max-height: 400px;
        opacity: 1;
        transform: translateY(0) scaleY(1);
        pointer-events: auto;
      }
    </style>

    @stack('styles')
  </head>
  <body class="bg-gray-50 text-gray-800 dark:bg-gray-950 dark:text-gray-100 font-sans min-h-screen flex flex-col transition-colors duration-300">

    <!-- NAVBAR UTAMA -->
    <header class="w-full py-3 sm:py-4 px-2 sm:px-4 lg:px-8 sticky top-0 z-50 bg-gray-50/80 dark:bg-gray-950/80 backdrop-blur-md">
      <div class="max-w-7xl mx-auto relative">
        <nav class="bg-white dark:bg-gray-900 px-3 sm:px-6 py-2 sm:py-3 custom-nav-container flex items-center justify-between transition-colors duration-300 gap-2">

          <!-- Logo & Nama Brand -->
          <div class="flex items-center gap-2 sm:gap-3 min-w-0">
            <div class="w-8 h-8 sm:w-10 sm:h-10 rounded-full border-2 border-gray-900 dark:border-gray-700 overflow-hidden flex items-center justify-center bg-blue-600 text-white font-black shrink-0">
              <img src="{{ asset('images/smk4.png') }}" onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';" alt="SMK4 Logo" class="w-full h-full object-cover" />
              <span style="display:none;" class="text-xs sm:text-sm">K</span>
            </div>
            <span class="text-xs sm:text-lg font-black tracking-wider text-black dark:text-white truncate">MUSEUM KARYA</span>
          </div>

          <!-- Menu Navigasi Tengah (Desktop Only) -->
          <div class="hidden md:flex items-center gap-8">
            <a href="{{ url('/') }}" class="text-sm font-bold text-gray-700 dark:text-gray-300 hover:text-blue-600 transition">BERANDA</a>
            <a href="{{ url('/karya') }}" class="text-sm font-bold text-gray-700 dark:text-gray-300 hover:text-blue-600 transition">KARYA</a>
            <a href="{{ url('/artikel') }}" class="text-sm font-bold text-gray-700 dark:text-gray-300 hover:text-blue-600 transition">ARTIKEL</a>
            <a href="{{ url('/tentang') }}" class="text-sm font-bold text-gray-700 dark:text-gray-300 hover:text-blue-600 transition">TENTANG</a>
          </div>

          <!-- Bagian Kanan (Hamburger Mobile, Theme Toggle & Tombol Auth Dinamis) -->
          <div class="flex items-center gap-1.5 sm:gap-3 shrink-0">

            <!-- Tombol Hamburger: hanya tampil di bawah md -->
            <button id="mobileNavToggle" onclick="toggleMobileNav()" aria-label="Buka menu navigasi" class="md:hidden w-8 h-8 sm:w-10 sm:h-10 rounded-full bg-white dark:bg-gray-800 text-gray-900 dark:text-white custom-btn-capsule flex items-center justify-center hover:translate-x-[-1px] hover:translate-y-[-1px] transition cursor-pointer shrink-0">
              <svg id="hamburgerIcon" class="w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M4 6h16M4 12h16M4 18h16" />
              </svg>
              <svg id="closeIcon" class="w-4 h-4 sm:w-5 sm:h-5 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12" />
              </svg>
            </button>

            <button id="themeToggle" onclick="toggleTheme()" aria-label="Ganti mode terang/gelap" class="w-8 h-8 sm:w-10 sm:h-10 rounded-full bg-yellow-300 dark:bg-gray-800 text-gray-900 dark:text-yellow-300 custom-btn-capsule flex items-center justify-center hover:translate-x-[-1px] hover:translate-y-[-1px] transition cursor-pointer shrink-0">
              <svg class="icon-sun w-4 h-4 sm:w-5 sm:h-5 hidden dark:block" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z" />
              </svg>
              <svg class="icon-moon w-4 h-4 sm:w-5 sm:h-5 block dark:hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z" />
              </svg>
            </button>

            @if(Route::is('login'))
              <a href="{{ route('register') }}" class="px-3 sm:px-5 py-1.5 sm:py-2 rounded-full bg-blue-400 dark:bg-blue-600 text-gray-900 dark:text-white font-black text-xs sm:text-sm custom-btn-capsule hover:translate-x-[-1px] hover:translate-y-[-1px] transition whitespace-nowrap shrink-0">
                DAFTAR
              </a>
            @else
              <a href="{{ route('login') }}" class="px-3 sm:px-5 py-1.5 sm:py-2 rounded-full bg-blue-400 dark:bg-blue-600 text-gray-900 dark:text-white font-black text-xs sm:text-sm custom-btn-capsule hover:translate-x-[-1px] hover:translate-y-[-1px] transition whitespace-nowrap shrink-0">
                LOGIN
              </a>
            @endif
          </div>

        </nav>

        <!-- PANEL DROPDOWN MENU MOBILE (di bawah pill navbar, hanya < md) -->
        <div id="mobileNavPanel" class="mobile-nav-panel md:hidden absolute top-full left-0 right-0 mt-2 bg-white dark:bg-gray-900 rounded-2xl z-40">
          <nav class="flex flex-col p-2">
            <a href="{{ url('/') }}" class="px-4 py-3 rounded-xl text-sm font-bold text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800 hover:text-blue-600 transition">BERANDA</a>
            <a href="{{ url('/karya') }}" class="px-4 py-3 rounded-xl text-sm font-bold text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800 hover:text-blue-600 transition">KARYA</a>
            <a href="{{ url('/artikel') }}" class="px-4 py-3 rounded-xl text-sm font-bold text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800 hover:text-blue-600 transition">ARTIKEL</a>
            <a href="{{ url('/tentang') }}" class="px-4 py-3 rounded-xl text-sm font-bold text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800 hover:text-blue-600 transition">TENTANG</a>
          </nav>
        </div>
      </div>
    </header>

    <!-- KONTEN UTAMA -->
    <main class="flex-1 flex items-center justify-center">
      @yield('content')
    </main>

    <!-- SCRIPT INTERNAL DARK MODE & MOBILE NAV -->
    <script>
      function toggleTheme() {
        if (document.documentElement.classList.contains('dark')) {
          document.documentElement.classList.remove('dark');
          localStorage.setItem('theme', 'light');
        } else {
          document.documentElement.classList.add('dark');
          localStorage.setItem('theme', 'dark');
        }
      }

      if (localStorage.getItem('theme') === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
        document.documentElement.classList.add('dark');
      } else {
        document.documentElement.classList.remove('dark');
      }

      // Toggle dropdown menu navigasi mobile
      function toggleMobileNav() {
        const panel = document.getElementById('mobileNavPanel');
        const hamburgerIcon = document.getElementById('hamburgerIcon');
        const closeIcon = document.getElementById('closeIcon');

        panel.classList.toggle('panel-open');
        hamburgerIcon.classList.toggle('hidden');
        closeIcon.classList.toggle('hidden');
      }

      // Tutup panel otomatis kalau layar di-resize ke ukuran md ke atas
      window.addEventListener('resize', function () {
        if (window.innerWidth >= 768) {
          const panel = document.getElementById('mobileNavPanel');
          if (panel.classList.contains('panel-open')) {
            toggleMobileNav();
          }
        }
      });
    </script>

    @stack('scripts')
  </body>
</html>