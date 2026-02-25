<?php

namespace App\Http\Controllers\Admin;

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
        return view('admin.blog-category.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        return view('admin.blog-category.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(BlogCategoryRequest $request)
    {
        $validated = $request->validated();

        // Set is_active (toggle sends '1' when checked, '0' when unchecked)
        $validated['is_active'] = $request->input('is_active', '0') === '1';

        BlogCategory::create($validated);

        return redirect()->route('admin.blog-category.index')
            ->with('success', 'Blog category created successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show(BlogCategory $blogCategory): View
    {
        return view('admin.blog-category.show', compact('blogCategory'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(BlogCategory $blogCategory): View
    {
        return view('admin.blog-category.edit', compact('blogCategory'));
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

        return redirect()->route('admin.blog-category.index')
            ->with('success', 'Blog category updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(BlogCategory $blogCategory)
    {
        $blogCategory->delete();

        return redirect()->route('admin.blog-category.index')
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
