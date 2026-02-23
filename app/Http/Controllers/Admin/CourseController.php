<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\AdminBaseController;
use App\Http\Requests\CourseRequest;
use App\Models\Course;
use App\Models\CourseCategory;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CourseController extends AdminBaseController
{
    /**
     * Display a listing of chapters (courses).
     */
    public function index(): View
    {
        return view('admin.course.index');
    }

    /**
     * Return courses for a category as JSON (for video form dropdown).
     */
    public function getByCategory(Request $request): JsonResponse
    {
        $categoryId = $request->input('course_category_id');
        if (!$categoryId) {
            return response()->json(['chapters' => []]);
        }
        $chapters = Course::where('course_category_id', $categoryId)
            ->ordered()
            ->get(['id', 'name']);
        return response()->json(['chapters' => $chapters]);
    }

    /**
     * Show the form for creating a new chapter.
     */
    public function create(): View
    {
        $categories = CourseCategory::active()->ordered()->get();
        return view('admin.course.create', compact('categories'));
    }

    /**
     * Store a newly created chapter.
     */
    public function store(CourseRequest $request)
    {
        $validated = $request->validated();
        $validated['sort_order'] = (int) ($validated['sort_order'] ?? 0);

        Course::create($validated);

        return redirect()->route('admin.course.index')
            ->with('success', 'Chapter created successfully.');
    }

    /**
     * Show the form for editing the specified chapter.
     */
    public function edit(Course $course): View
    {
        $categories = CourseCategory::active()->ordered()->get();
        return view('admin.course.edit', compact('course', 'categories'));
    }

    /**
     * Update the specified chapter.
     */
    public function update(CourseRequest $request, Course $course)
    {
        $validated = $request->validated();
        $validated['sort_order'] = (int) ($validated['sort_order'] ?? 0);

        $course->update($validated);

        return redirect()->route('admin.course.index')
            ->with('success', 'Chapter updated successfully.');
    }

    /**
     * Remove the specified chapter.
     */
    public function destroy(Course $course)
    {
        $course->delete();

        return redirect()->route('admin.course.index')
            ->with('success', 'Chapter deleted successfully.');
    }
}
