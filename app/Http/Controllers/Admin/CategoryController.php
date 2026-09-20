<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCategoryRequest;
use App\Models\Category;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CategoryController extends Controller
{
    /**
     * Display a listing of categories.
     */
    public function index(): View
    {
        $categories = Category::withCount('products')->latest()->get();
        
        return view('admin.categories.index', compact('categories'));
    }

    /**
     * Store a newly created category.
     */
    public function store(StoreCategoryRequest $request): RedirectResponse
    {
        Category::create($request->validated());

        return back()->with('success', 'Kategori berhasil ditambahkan!');
    }

    /**
     * Update the specified category.
     */
    public function update(StoreCategoryRequest $request, Category $category): RedirectResponse
    {
        $category->update($request->validated());

        return back()->with('success', 'Kategori berhasil diperbarui!');
    }

    /**
     * Remove the specified category.
     */
    public function destroy(Category $category): RedirectResponse
    {
        if ($category->products()->count() > 0) {
            return back()->withErrors(['error' => 'Kategori tidak dapat dihapus karena masih memiliki produk.']);
        }

        $category->delete();

        return back()->with('success', 'Kategori berhasil dihapus!');
    }
}
