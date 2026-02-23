<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\AdminBaseController;
use App\Http\Requests\CourseCategoryRequest;
use App\Models\CourseCategory;
use Illuminate\View\View;

class CourseCategoryController extends AdminBaseController
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        return view('admin.course-category.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        return view('admin.course-category.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CourseCategoryRequest $request)
    {
        $validated = $request->validated();
        unset($validated['image']);

        $payload = [
            'name' => $validated['name'],
            'slug' => $validated['slug'],
            'description' => $validated['description'] ?? null,
            'sort_order' => (int) ($validated['sort_order'] ?? 0),
            'is_active' => $request->input('is_active', '0') === '1',
            'image_path' => null,
        ];

        if ($request->hasFile('image')) {
            $payload['image_path'] = $request->file('image')->store('course-categories', 'public');
        }

        CourseCategory::create($payload);

        return redirect()->route('admin.course-category.index')
            ->with('success', 'Course category created successfully.');
    }

    /**
     * Get course category data as JSON (for drawer edit/view).
     */
    public function getJson(CourseCategory $courseCategory)
    {
        return response()->json([
            'id' => $courseCategory->id,
            'name' => $courseCategory->name,
            'slug' => $courseCategory->slug,
            'description' => $courseCategory->description,
            'image_path' => $courseCategory->image_path,
            'image_url' => $courseCategory->image_url,
            'sort_order' => $courseCategory->sort_order,
            'is_active' => $courseCategory->is_active,
            'created_at' => $courseCategory->created_at?->toIso8601String(),
            'updated_at' => $courseCategory->updated_at?->toIso8601String(),
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(CourseCategory $courseCategory): View
    {
        $courseCategory->loadCount('videos');
        return view('admin.course-category.show', compact('courseCategory'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(CourseCategory $courseCategory): View
    {
        return view('admin.course-category.edit', compact('courseCategory'));
    }

    /**
     * Update the resource in storage.
     */
    public function update(CourseCategoryRequest $request, CourseCategory $courseCategory)
    {
        $validated = $request->validated();
        unset($validated['image']);

        $payload = [
            'name' => $validated['name'],
            'slug' => $validated['slug'],
            'description' => $validated['description'] ?? null,
            'sort_order' => (int) ($validated['sort_order'] ?? 0),
            'is_active' => $request->input('is_active', '0') === '1',
        ];

        if ($request->hasFile('image')) {
            if ($this->isLocalImagePath($courseCategory->image_path)) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($courseCategory->image_path);
            }
            $payload['image_path'] = $request->file('image')->store('course-categories', 'public');
        } elseif ($request->boolean('remove_image')) {
            if ($this->isLocalImagePath($courseCategory->image_path)) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($courseCategory->image_path);
            }
            $payload['image_path'] = null;
        }

        $courseCategory->update($payload);

        return redirect()->route('admin.course-category.index')
            ->with('success', 'Course category updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(CourseCategory $courseCategory)
    {
        if ($this->isLocalImagePath($courseCategory->image_path)) {
            \Illuminate\Support\Facades\Storage::disk('public')->delete($courseCategory->image_path);
        }

        $courseCategory->delete();

        return redirect()->route('admin.course-category.index')
            ->with('success', 'Course category deleted successfully.');
    }

    /**
     * Toggle course category active status.
     */
    public function toggleActive(CourseCategory $courseCategory)
    {
        $courseCategory->update(['is_active' => !$courseCategory->is_active]);

        return response()->json([
            'success' => true,
            'is_active' => $courseCategory->is_active,
            'message' => $courseCategory->is_active ? 'Category activated.' : 'Category deactivated.',
        ]);
    }

    private function isLocalImagePath(?string $path): bool
    {
        if (empty($path)) {
            return false;
        }
        return ! str_starts_with($path, 'http://') && ! str_starts_with($path, 'https://');
    }
}
