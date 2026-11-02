<!doctype html>
<html lang="id">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="icon" type="image/png" href="{{ asset('favicon.png') }}">
    <title>{{ $article->title }} - Museum Karya SMKN 4 Tasikmalaya</title>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>

    <style type="text/tailwindcss">
        @custom-variant dark (&:where(.dark, .dark *));
    </style>
    <style>
        .my-bg {
            background: linear-gradient(135deg, #2563eb 0%, #1e40af 100%);
        }

        .gradient-cyan {
            background: linear-gradient(135deg, #06b6d4 0%, #2563eb 100%);
        }

        .badge-custom {
            display: inline-block;
            background-color: transparent;
            border: 1px solid rgba(29, 78, 216, 0.15);
            color: #1d4ed8;
            font-size: 0.75rem;
            font-weight: 600;
            padding: 0.25rem 0.75rem;
            border-radius: 9999px;
        }

        .dark .badge-custom {
            background-color: transparent;
            border-color: rgba(147, 197, 253, 0.25);
            color: #93c5fd;
        }

        .eyebrow {
            letter-spacing: .08em;
        }

        .line-clamp-2 {
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        .article-thumb img {
            transition: transform .5s ease;
        }

        .related-card:hover .article-thumb img {
            transform: scale(1.06);
        }

        .icon-moon {
            display: block;
        }

        .icon-sun {
            display: none;
        }

        .dark .icon-moon {
            display: none;
        }

        .dark .icon-sun {
            display: block;
        }

        .article-content {
            white-space: pre-wrap;
            word-break: break-word;
            overflow-wrap: break-word;
        }

        .article-content h2 {
            font-size: 1.375rem;
            font-weight: 700;
            margin-top: 2rem;
            margin-bottom: 0.75rem;
        }

        .article-content p {
            margin-bottom: 1.1rem;
            line-height: 1.85;
        }

        .article-content ul,
        .article-content ol {
            margin-bottom: 1.1rem;
            padding-left: 1.4rem;
        }

        .article-content ul {
            list-style-type: disc;
        }

        .article-content ol {
            list-style-type: decimal;
        }

        .article-content li {
            margin-bottom: 0.4rem;
            line-height: 1.8;
        }

        .article-content blockquote {
            border-left: 4px solid #2563eb;
            background-color: #eff6ff;
            padding: 1rem 1.25rem;
            border-radius: 0.5rem;
            font-style: italic;
            color: #1e3a8a;
            margin: 1.5rem 0;
        }

        .dark .article-content blockquote {
            background-color: rgba(37, 99, 235, 0.12);
            color: #bfdbfe;
        }

        .share-btn {
            width: 2.5rem;
            height: 2.5rem;
            border-radius: 9999px;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: transform .2s ease;
        }

        .share-btn:hover {
            transform: translateY(-2px);
        }

        .neop-navbar-container {
            border: 3px solid #000;
            box-shadow: 4px 4px 0px 0px #000;
            border-radius: 9999px;
        }

        .dark .neop-navbar-container {
            border: 3px solid #fff;
            box-shadow: 4px 4px 0px 0px #fff;
        }

        .neop-btn-box {
            border: 2px solid #000;
            box-shadow: 2px 2px 0px 0px #000;
            transition: all 0.1s ease;
        }

        .neop-btn-box:active {
            transform: translate(2px, 2px);
            box-shadow: 0px 0px 0px 0px #000;
        }

        .dark .neop-btn-box {
            border: 2px solid #fff;
            box-shadow: 2px 2px 0px 0px #fff;
        }

        .dark .neop-btn-box:active {
            box-shadow: 0px 0px 0px 0px #fff;
        }
    </style>
</head>

<body class="scroll-smooth bg-white dark:bg-gray-950 text-gray-900 dark:text-gray-100 transition-colors duration-300">

    <!-- ===== HEADER (NEO-BRUTALISM FLOATING PILL NAVBAR) ===== -->
    <header class="sticky top-4 z-50 px-4">
        <nav class="mx-auto max-w-7xl neop-navbar-container bg-white dark:bg-gray-900 px-4 py-2.5 flex items-center justify-between transition-colors duration-300">
            
            <div class="flex items-center gap-3">
                <!-- Perubahan 1: Mengganti emoji 🏛️ dengan logo SMK 4 -->
                <div class="w-9 h-9 bg-white dark:bg-blue-600 border-2 border-black dark:border-white shadow-[2px_2px_0px_0px_rgba(0,0,0,1)] dark:shadow-[2px_2px_0px_0px_rgba(255,255,255,1)] rounded-lg flex items-center justify-center overflow-hidden p-1">
                    <img src="{{ asset('images/smk4.png') }}" alt="Logo" class="w-6 h-6 sm:w-7 sm:h-7 object-cover rounded-md" />
                </div>
                <span class="text-base sm:text-lg font-black tracking-tight text-black dark:text-white uppercase">MUSEUM.KARYA</span>
            </div>

            <div class="hidden md:flex items-center gap-x-8">
                <a href="{{ url('/') }}" class="text-xs lg:text-sm font-bold text-gray-700 dark:text-gray-300 hover:text-black dark:hover:text-white transition">BERANDA</a>
                <a href="{{ url('/karya') }}" class="text-xs lg:text-sm font-bold text-gray-700 dark:text-gray-300 hover:text-black dark:hover:text-white transition">KARYA</a>
                <a href="{{ url('/artikel') }}" class="text-xs lg:text-sm font-black text-black dark:text-white border-b-2 border-black dark:border-white pb-0.5">ARTIKEL</a>
                <a href="{{ url('/tentang') }}" class="text-xs lg:text-sm font-bold text-gray-700 dark:text-gray-300 hover:text-black dark:hover:text-white transition">TENTANG</a>
            </div>

            <div class="flex items-center gap-3">
                <button id="themeToggle" onclick="toggleTheme()" aria-label="Ganti mode terang/gelap" class="neop-btn-box w-9 h-9 flex items-center justify-center rounded-full bg-yellow-300 dark:bg-gray-800 text-black dark:text-yellow-300 cursor-pointer">
                    <svg class="icon-sun w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z" />
                    </svg>
                    <svg class="icon-moon w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z" />
                    </svg>
                </button>
                @auth
                    <a href="{{ $dashboardUrl }}"
                        class="md:hidden mt-2 text-xs font-black px-5 py-3 bg-[#74B9FF] text-black border-2 border-black rounded-2xl shadow-[2px_2px_0px_#000] active:translate-y-[1px] transition block text-center">
                        DASHBOARD
                    </a>
                @else
                    <a href="{{ route('login') }}"
                        class="md:hidden mt-2 text-xs font-black px-5 py-3 bg-[#74B9FF] text-black border-2 border-black rounded-2xl shadow-[2px_2px_0px_#000] active:translate-y-[1px] transition block text-center">
                        LOGIN
                    </a>
                @endauth
            </div>

        </nav>
    </header>

    <!-- ===== BREADCRUMB ===== -->
    <div class="bg-gray-50 dark:bg-gray-900 border-b border-gray-100 dark:border-gray-800 mt-4">
        <div class="mx-auto max-w-7xl px-6 lg:px-8 py-3 text-sm text-gray-500 dark:text-gray-400">
            <a href="{{ url('/') }}" class="hover:text-blue-600">Beranda</a>
            <span class="mx-1">/</span>
            <a href="{{ url('/artikel') }}" class="hover:text-blue-600">Artikel</a>
            <span class="mx-1">/</span>
            <span class="text-gray-700 dark:text-gray-300">
                {{ $article->category->name }}
            </span>
        </div>
    </div>

    <!-- ===== KONTEN UTAMA ===== -->
    <section class="py-10 lg:py-14">
        <div class="mx-auto max-w-7xl px-6 lg:px-8 grid lg:grid-cols-3 gap-12">

            <!-- Kolom kiri: isi artikel -->
            <article class="lg:col-span-2">

                <span class="badge-custom mb-4">
                    {{ $article->category->name }}
                </span>

                <h1 class="text-2xl lg:text-4xl font-bold text-gray-900 dark:text-white leading-snug mt-3 mb-4">
                    {{ $article->title }}
                </h1>

                <div class="flex flex-wrap items-center gap-3 text-sm text-gray-500 dark:text-gray-400 mb-6 pb-6 border-b border-gray-200 dark:border-gray-800">
                    <div class="w-8 h-8 rounded-full gradient-cyan flex items-center justify-center text-white text-xs font-bold flex-shrink-0">
                        TR
                    </div>
                    <span class="font-medium text-gray-700 dark:text-gray-300">{{ $article->author->name }}</span>
                    <span>•</span>
                    <span>
                        {{ $article->created_at->format('d F Y') }}
                    </span>
                    <span>•</span>
                    <span>4 menit membaca</span>
                </div>

                <div class="w-full h-56 sm:h-80 lg:h-96 rounded-2xl overflow-hidden mb-8 article-thumb">
                    <img src="{{ asset('storage/' . $article->cover) }}" alt="{{ $article->title }}" class="w-full h-full object-cover">
                </div>

                <!-- Perubahan 2: Menambahkan kelas text-left agar teks deskripsi rata kiri -->
                <div class="article-content text-gray-700 dark:text-gray-300 text-left">
                    {!! nl2br(e($article->content)) !!}

                    <!-- Bagikan artikel -->
                    <div class="flex items-center gap-3 mt-6">
                        <span class="text-sm font-semibold text-gray-700 dark:text-gray-300">Bagikan:</span>
                        <a href="#" aria-label="Bagikan ke WhatsApp" class="share-btn bg-green-500 text-white">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M20.52 3.48A11.9 11.9 0 0012.02 0C5.4 0 .06 5.34.06 11.94c0 2.1.55 4.15 1.6 5.96L0 24l6.27-1.64a11.9 11.9 0 005.75 1.47h.01c6.62 0 11.96-5.34 11.96-11.94 0-3.19-1.24-6.19-3.47-8.41zM12.03 21.5a9.5 9.5 0 01-4.84-1.32l-.35-.21-3.6.94.96-3.51-.23-.36a9.53 9.53 0 01-1.46-5.1c0-5.27 4.29-9.55 9.56-9.55 2.55 0 4.95.99 6.75 2.8a9.5 9.5 0 012.79 6.76c0 5.27-4.29 9.55-9.58 9.55z" />
                            </svg>
                        </a>
                        <a href="#" aria-label="Bagikan ke Facebook" class="share-btn bg-blue-600 text-white">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M22 12a10 10 0 10-11.56 9.87v-6.98H7.9V12h2.54V9.8c0-2.5 1.5-3.9 3.79-3.9 1.1 0 2.24.2 2.24.2v2.46h-1.26c-1.24 0-1.63.77-1.63 1.56V12h2.78l-.44 2.89h-2.34v6.98A10 10 0 0022 12z" />
                            </svg>
                        </a>
                        <a href="#" aria-label="Bagikan ke Twitter" class="share-btn bg-sky-500 text-white">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M23 4.5c-.8.36-1.66.6-2.56.7a4.5 4.5 0 001.98-2.48 9 9 0 01-2.83 1.08 4.47 4.47 0 00-7.63 4.08A12.7 12.7 0 013 3.16a4.47 4.47 0 001.38 5.96 4.4 4.4 0 01-2.02-.56v.06a4.47 4.47 0 003.58 4.38 4.5 4.5 0 01-2 .08 4.47 4.47 0 004.17 3.1A9 9 0 011 18.57 12.7 12.7 0 007.86 20.6c8.23 0 12.74-6.82 12.74-12.74l-.01-.58A9.1 9.1 0 0023 4.5z" />
                            </svg>
                        </a>
                    </div>
                </div>

            </article>

            <!-- Kolom kanan: sidebar -->
            <aside class="space-y-10">
                <div>
                    <h3 class="font-bold text-gray-900 dark:text-white mb-4 pb-2 border-b-2 border-blue-600 w-fit">
                        Kategori
                    </h3>
                    <div class="flex flex-col gap-2">
                        @foreach ($categories as $category)
                            <a href="{{ url('/artikel?kategori=' . $category->slug) }}" class="flex justify-between items-center p-2 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-800 transition text-sm text-gray-700 dark:text-gray-300">
                                <span>{{ $category->name }}</span>
                                <span class="bg-gray-100 dark:bg-gray-800 px-2 py-0.5 rounded text-xs text-gray-500">{{ $category->articles_count }}</span>
                            </a>
                        @endforeach
                    </div>
                </div>

                <div>
                    <h3 class="font-bold text-gray-900 dark:text-white mb-4 pb-2 border-b-2 border-blue-600 w-fit">
                        Paling Banyak Dibaca
                    </h3>
                    <div class="flex flex-col gap-4">
                        @foreach ($popular as $item)
                            <a href="{{ route('artikel.show', $item->slug) }}" class="flex gap-3 group">
                                <img src="{{ asset('storage/' . $item->cover) }}" class="w-20 h-16 object-cover rounded-lg flex-shrink-0" alt="{{ $item->title }}">
                                <div class="flex flex-col justify-between">
                                    <span class="text-[10px] font-semibold text-blue-600 dark:text-blue-400">{{ $item->category->name }}</span>
                                    <h4 class="text-xs font-bold text-gray-900 dark:text-white group-hover:text-blue-600 line-clamp-2">{{ $item->title }}</h4>
                                    <small class="text-[10px] text-gray-400">{{ number_format($item->views) }} kali dibaca</small>
                                </div>
                            </a>
                        @endforeach
                    </div>
                </div>
            </aside>
        </div>
    </section>

    <!-- ===== ARTIKEL TERKAIT ===== -->
    <section class="py-14 bg-gray-50 dark:bg-gray-900">
        <div class="mx-auto max-w-7xl px-6 lg:px-8">
            <div class="flex items-center gap-3 mb-8">
                <span class="h-2 w-2 rounded-full bg-blue-600"></span>
                <h2 class="text-xl font-bold text-gray-900 dark:text-white">Artikel Terkait</h2>
            </div>

            <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach ($related as $item)
                    <a href="{{ url('/artikel/' . $item->slug) }}" class="related-card group bg-white dark:bg-gray-800 rounded-xl overflow-hidden shadow-sm hover:shadow-lg transition">
                        <div class="article-thumb h-40 overflow-hidden">
                            <img src="{{ asset('storage/' . $item->cover) }}" class="w-full h-full object-cover" alt="{{ $item->title }}">
                        </div>
                        <div class="p-5">
                            <span class="badge-custom mb-3">
                                {{ $item->category->name }}
                            </span>
                            <h3 class="font-bold text-gray-900 dark:text-white group-hover:text-blue-600 transition line-clamp-2">
                                {{ $item->title }}
                            </h3>
                            <p class="text-xs text-gray-400 mt-2">
                                {{ $item->created_at->format('d M Y') }}
                            </p>
                        </div>
                    </a>
                @endforeach
            </div>
        </div>
    </section>

    <!-- ===== FOOTER ===== -->
    <footer class="bg-gray-900 dark:bg-black text-white text-center py-12">
        <div class="mx-auto max-w-7xl px-6 lg:px-8">
            <div class="border-t border-gray-800 pt-8">
                <p class="text-gray-400 text-sm">&copy; 2026 Museum Karya SMKN 4 Tasikmalaya</p>
                <p class="text-gray-400 text-sm">Design &amp; Development By PPLG</p>
            </div>
        </div>
    </footer>

    <script>
        function applyTheme(theme) {
            document.documentElement.classList.toggle("dark", theme === "dark");
        }

        function toggleTheme() {
            const isDark = document.documentElement.classList.contains("dark");
            const next = isDark ? "light" : "dark";
            localStorage.setItem("theme", next);
            applyTheme(next);
        }
        (function initTheme() {
            const saved = localStorage.getItem("theme");
            const prefersDark = window.matchMedia("(prefers-color-scheme: dark)").matches;
            applyTheme(saved || (prefersDark ? "dark" : "light"));
        })();
    </script>
</body>

</html>