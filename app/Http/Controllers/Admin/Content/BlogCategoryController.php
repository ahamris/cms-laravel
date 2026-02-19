<?php

namespace App\Http\Controllers\Admin\Content;

use App\Http\Controllers\Admin\AdminBaseController;
use App\Http\Requests\BlogCategoryRequest;
use App\Models\BlogCategory;
use Illuminate\View\View;

class BlogCategoryController extends AdminBaseController
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        return view('admin.content.blog-category.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        return view('admin.content.blog-category.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(BlogCategoryRequest $request)
    {
        $validated = $request->validated();

        // Set is_active (toggle sends '1' when checked, '0' when unchecked)
        $validated['is_active'] = $request->input('is_active', '0') === '1';

        $blogCategory = BlogCategory::create($validated);

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Blog category created successfully!',
                'data' => $blogCategory,
            ]);
        }

        return redirect()->route('admin.content.blog-category.index')
            ->with('success', 'Blog category created successfully!');
    }

    /**
     * Get blog category data as JSON.
     */
    public function getJson(BlogCategory $blogCategory)
    {
        return response()->json([
            'id' => $blogCategory->id,
            'name' => $blogCategory->name,
            'slug' => $blogCategory->slug,
            'description' => $blogCategory->description,
            'color' => $blogCategory->color,
            'is_active' => $blogCategory->is_active,
            'created_at' => $blogCategory->created_at?->toIso8601String(),
            'updated_at' => $blogCategory->updated_at?->toIso8601String(),
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(BlogCategory $blogCategory): View
    {
        return view('admin.content.blog-category.show', compact('blogCategory'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(BlogCategory $blogCategory): View
    {
        return view('admin.content.blog-category.edit', compact('blogCategory'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(BlogCategoryRequest $request, BlogCategory $blogCategory)
    {
        $validated = $request->validated();

        // Set is_active (toggle sends '1' when checked, '0' when unchecked)
        $validated['is_active'] = $request->input('is_active', '0') === '1';

        $blogCategory->update($validated);

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Blog category updated successfully!',
                'data' => $blogCategory,
            ]);
        }

        return redirect()->route('admin.content.blog-category.index')
            ->with('success', 'Blog category updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(BlogCategory $blogCategory)
    {
        $blogCategory->delete();

        return redirect()->route('admin.content.blog-category.index')
            ->with('success', 'Blog category deleted successfully!');
    }

    /**
     * Toggle blog category active status
     */
    public function toggleActive(BlogCategory $blogCategory)
    {
        $blogCategory->update(['is_active' => ! $blogCategory->is_active]);

        return response()->json([
            'success' => true,
            'is_active' => $blogCategory->is_active,
            'message' => $blogCategory->is_active ? 'Blog category activated successfully!' : 'Blog category deactivated successfully!',
        ]);
    }
}
