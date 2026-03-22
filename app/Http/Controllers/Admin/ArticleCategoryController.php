<?php

namespace App\Http\Controllers\Admin;

use App\Models\ArticleCategory;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ArticleCategoryController extends AdminBaseController
{
    public function index(): View
    {
        return view('admin.articles.categories.index');
    }

    public function create(): View
    {
        $parents = ArticleCategory::active()->roots()->ordered()->get();

        return view('admin.articles.categories.create', compact('parents'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'        => 'required|string|max:100',
            'slug'        => 'nullable|string|max:100|unique:article_categories,slug',
            'description' => 'nullable|string',
            'color'       => 'nullable|string|max:7',
            'icon'        => 'nullable|string|max:50',
            'parent_id'   => 'nullable|exists:article_categories,id',
            'sort_order'  => 'nullable|integer',
            'is_active'   => 'nullable|boolean',
        ]);

        $validated['is_active'] = $request->boolean('is_active', true);

        ArticleCategory::create($validated);

        $this->logActivity('article_category', 'created', "Created article category: {$validated['name']}");

        return redirect()->route('admin.article-category.index')
            ->with('success', 'Category created successfully.');
    }

    public function show(ArticleCategory $articleCategory): View
    {
        $articleCategory->loadCount('articles');

        return view('admin.articles.categories.show', compact('articleCategory'));
    }

    public function edit(ArticleCategory $articleCategory): View
    {
        $parents = ArticleCategory::active()
            ->where('id', '!=', $articleCategory->id)
            ->roots()
            ->ordered()
            ->get();

        return view('admin.articles.categories.edit', compact('articleCategory', 'parents'));
    }

    public function update(Request $request, ArticleCategory $articleCategory)
    {
        $validated = $request->validate([
            'name'        => 'required|string|max:100',
            'slug'        => 'nullable|string|max:100|unique:article_categories,slug,' . $articleCategory->id,
            'description' => 'nullable|string',
            'color'       => 'nullable|string|max:7',
            'icon'        => 'nullable|string|max:50',
            'parent_id'   => 'nullable|exists:article_categories,id',
            'sort_order'  => 'nullable|integer',
            'is_active'   => 'nullable|boolean',
        ]);

        $validated['is_active'] = $request->boolean('is_active', true);

        $articleCategory->update($validated);

        $this->logActivity('article_category', 'updated', "Updated article category: {$articleCategory->name}");

        return redirect()->route('admin.article-category.index')
            ->with('success', 'Category updated successfully.');
    }

    public function destroy(ArticleCategory $articleCategory)
    {
        $name = $articleCategory->name;
        $articleCategory->delete();

        $this->logActivity('article_category', 'deleted', "Deleted article category: {$name}");

        return redirect()->route('admin.article-category.index')
            ->with('success', 'Category deleted successfully.');
    }

    public function toggleActive(ArticleCategory $articleCategory)
    {
        $articleCategory->update(['is_active' => !$articleCategory->is_active]);

        return back()->with('success', 'Category status updated.');
    }
}
