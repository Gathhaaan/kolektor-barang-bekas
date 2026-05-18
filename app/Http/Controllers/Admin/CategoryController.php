<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::withCount('donations')->latest()->get();
        return view('admin.categories.index', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'        => 'required|string|max:100|unique:categories,name',
            'icon'        => 'nullable|string|max:10',
            'description' => 'nullable|string|max:255',
        ]);

        Category::create([
            'name'        => $request->name,
            'icon'        => $request->icon ?? '📦',
            'slug'        => Str::slug($request->name),
            'description' => $request->description,
        ]);

        return back()->with('success', 'Kategori berhasil ditambahkan.');
    }

    public function update(Request $request, Category $category)
    {
        $request->validate([
            'name'        => 'required|string|max:100|unique:categories,name,' . $category->id,
            'icon'        => 'nullable|string|max:10',
            'description' => 'nullable|string|max:255',
        ]);

        $category->update([
            'name'        => $request->name,
            'icon'        => $request->icon ?? $category->icon,
            'slug'        => Str::slug($request->name),
            'description' => $request->description,
        ]);

        return back()->with('success', 'Kategori berhasil diperbarui.');
    }

    public function destroy(Category $category)
    {
        if ($category->donations()->count() > 0) {
            return back()->with('error', 'Kategori tidak dapat dihapus karena masih memiliki donasi.');
        }
        $category->delete();
        return back()->with('success', 'Kategori berhasil dihapus.');
    }
}
