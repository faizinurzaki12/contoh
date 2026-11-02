<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\Category;
use App\Support\MuseumCache;

class ArticlePageController extends Controller
{
    public function index()
    {
        $articles = MuseumCache::remember('artikel', 'index', function () {
            return Article::with(['author', 'category'])
                ->where('status', 'published')
                ->latest('published_at')
                ->get();
        });

        return view('artikel', compact('articles'));
    }

    public function show($slug)
{
    // 1. Ambil data artikel & sidebar dari cache
    $data = MuseumCache::remember('artikel', 'show.' . md5($slug), function () use ($slug) {
        $article = Article::with(['author', 'category'])
            ->where('slug', $slug)
            ->where('status', 'published')
            ->firstOrFail();

        return [
            'article'    => $article,
            'categories' => Category::withCount('articles')->get(),
            'popular'    => Article::where('status', 'published')
                ->orderByDesc('views')
                ->take(5)
                ->get(),
            'related'    => Article::with('category')
                ->where('status', 'published')
                ->where('category_id', $article->category_id)
                ->where('id', '!=', $article->id)
                ->latest('published_at')
                ->take(3)
                ->get(),
        ];
    });

    // 2. Increment views (langsung ke DB, tidak di-cache)
    Article::whereKey($data['article']->id)->increment('views');

    // 3. Tentukan URL Dashboard berdasarkan role session yang aktif
    $dashboardUrl = url('/login'); // Fallback default
    
    if (auth()->check()) {
        $user = auth()->user();
        $role = is_numeric($user->role) ? (int) $user->role : $user->role;

        if ($role === 0 || $role === '0' || $role === 'superadmin') {
            $dashboardUrl = url('/superadmin/dashboard');
        } elseif ($role === 1 || $role === '1' || $role === 'admin') {
            $dashboardUrl = url('/admin/dashboard');
        } elseif ($role === 2 || $role === '2' || $role === 'siswa') {
            $dashboardUrl = url('/siswa/dashboard');
        }
    }

    // 4. Masukkan variabel dashboardUrl ke array $data sebelum dikirim ke view
    $data['dashboardUrl'] = $dashboardUrl;

    return view('artikel.show', $data);
}
}
