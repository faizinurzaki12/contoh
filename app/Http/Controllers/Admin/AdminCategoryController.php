<?php

namespace App\Http\Controllers\Admin;

use App\Support\MuseumCache;
use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;

class AdminCategoryController extends Controller
{
    public function index()
    {
        $categories = Category::latest()->get();

        return view('admin.kategori', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:categories,name'
        ]);

        Category::create([
            'name' => $request->name,
        ]);

        MuseumCache::flush('artikel');

        return back()->with('success', 'Kategori berhasil ditambahkan.');
    }

    public function update(Request $request, $id)
    {
        $category = Category::findOrFail($id);

        $request->validate([
            'name' => 'required|unique:categories,name,' . $category->id,
        ]);

        $category->update([
            'name' => $request->name,
        ]);

        MuseumCache::flush('artikel');

        return back()->with('success', 'Kategori berhasil diubah.');
    }

    public function destroy($id)
    {
        $category = Category::findOrFail($id);
        $category->delete();

        MuseumCache::flush('artikel');

        return back()->with('success', 'Kategori berhasil dihapus.');
    }
}