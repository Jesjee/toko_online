<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Inertia\Inertia;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::orderBy('sort_order')->orderBy('name')->paginate(10);
        return Inertia::render('Admin/Categories/Index', [
            'categories' => $categories,
        ]);
    }

    public function create()
    {
        return Inertia::render('Admin/Categories/Create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'        => 'required|string|max:255',
            'description' => 'nullable|string',
            'icon'        => 'nullable|string|max:10',
            'is_active'   => 'boolean',
            'sort_order'  => 'integer|min:0',
        ]);

        Category::create($request->all());

        return redirect()->route('admin.categories.index')
            ->with('success', 'Kategori berhasil dibuat!');
    }

    public function edit(Category $category)
    {
        return Inertia::render('Admin/Categories/Edit', [
            'category' => $category,
        ]);
    }

    public function update(Request $request, Category $category)
    {
        $request->validate([
            'name'        => 'required|string|max:255',
            'description' => 'nullable|string',
            'icon'        => 'nullable|string|max:10',
            'is_active'   => 'boolean',
            'sort_order'  => 'integer|min:0',
        ]);

        $category->update($request->all());

        return redirect()->route('admin.categories.index')
            ->with('success', 'Kategori berhasil diupdate!');
    }

    public function destroy(Category $category)
    {
        $category->delete();

        return redirect()->route('admin.categories.index')
            ->with('success', 'Kategori berhasil dihapus!');
    }
}