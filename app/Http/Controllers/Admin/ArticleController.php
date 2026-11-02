<?php

namespace App\Http\Controllers\Admin;

use App\Support\MuseumCache;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Article;
use App\Models\Category;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ArticleController extends Controller
{
    public function index()
    {
        $articles = Article::with(['author', 'category'])->get();
        $categories = Category::all();

        return view('admin.artikel', compact('articles', 'categories'));
    }

    public function create()
    {
        $categories = Category::orderBy('name')->get();

        return view('admin.upload-artikel', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|max:255',
            'category_id' => 'required|exists:categories,id',
            'excerpt' => 'required|max:200',
            'content' => 'required',
            'cover' => 'required|image|mimes:jpg,jpeg,png,webp|max:2048',
            'status' => 'required|in:draft,published',
            'is_featured' => 'nullable'
        ]);

        $cover = $request->file('cover')->store('articles', 'public');

        Article::create([
            'author_id' => Auth::id(),
            'category_id' => $request->category_id,
            'title' => $request->title,
            'slug' => Str::slug($request->title),
            'excerpt' => $request->excerpt,
            'content' => $request->content,
            'cover' => $cover,
            'status' => $request->status,
            'is_featured' => $request->has('is_featured'),
            'published_at' => $request->status == 'published' ? now() : null,
        ]);

        MuseumCache::flush('artikel');

        return redirect()->route('articles.index')->with('success', 'Artikel berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $article = Article::findOrFail($id);
        $categories = Category::orderBy('name')->get();

        return view('admin.edit-artikel', compact('article', 'categories'));
    }

    public function update(Request $request, $id)
    {
        $article = Article::findOrFail($id);

        $request->validate([
            'title' => 'required|max:255',
            'category_id' => 'required|exists:categories,id',
            'excerpt' => 'required|max:255',
            'content' => 'required',
            'cover' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'status' => 'required|in:draft,published',
        ]);

        $cover = $article->cover;

        if ($request->hasFile('cover')) {
            if ($article->cover && Storage::disk('public')->exists($article->cover)) {
                Storage::disk('public')->delete($article->cover);
            }
            $cover = $request->file('cover')->store('articles', 'public');
        }

        $article->update([
            'category_id' => $request->category_id,
            'title' => $request->title,
            'slug' => Str::slug($request->title),
            'excerpt' => $request->excerpt,
            'content' => $request->content,
            'cover' => $cover,
            'status' => $request->status,
            'is_featured' => $request->has('is_featured'),
            'published_at' => $request->status == 'published' ? now() : null,
        ]);

        MuseumCache::flush('artikel');

        return redirect()->route('articles.index')->with('success', 'Artikel berhasil diperbarui.');
    }

    public function destroy(string $id)
    {
        $article = Article::findOrFail($id);
        $article->delete();

        MuseumCache::flush('artikel');

        return redirect()->route('articles.index')->with('success', 'Artikel berhasil dihapus');
    }
}