<?php

namespace App\Http\Controllers\Admin\Content;

use App\Http\Controllers\Admin\AdminBaseController;
use App\Http\Requests\AcademyChapterRequest;
use App\Models\AcademyCategory;
use App\Models\AcademyChapter;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AcademyChapterController extends AdminBaseController
{
    /**
     * Display a listing of chapters (HTML).
     */
    public function index(): View
    {
        return view('admin.content.academy-chapter.index');
    }

    /**
     * Return chapters for a category as JSON (for video form dropdown).
     */
    public function getByCategory(Request $request): JsonResponse
    {
        $categoryId = $request->input('academy_category_id');
        if (!$categoryId) {
            return response()->json(['chapters' => []]);
        }
        $chapters = AcademyChapter::where('academy_category_id', $categoryId)
            ->ordered()
            ->get(['id', 'name']);
        return response()->json(['chapters' => $chapters]);
    }

    /**
     * Show the form for creating a new chapter.
     */
    public function create(): View
    {
        $categories = AcademyCategory::active()->ordered()->get();
        return view('admin.content.academy-chapter.create', compact('categories'));
    }

    /**
     * Store a newly created chapter.
     */
    public function store(AcademyChapterRequest $request)
    {
        $validated = $request->validated();
        $validated['sort_order'] = (int) ($validated['sort_order'] ?? 0);

        AcademyChapter::create($validated);

        return redirect()->route('admin.content.academy-chapter.index')
            ->with('success', 'Chapter created successfully.');
    }

    /**
     * Show the form for editing the specified chapter.
     */
    public function edit(AcademyChapter $academyChapter): View
    {
        $categories = AcademyCategory::active()->ordered()->get();
        return view('admin.content.academy-chapter.edit', compact('academyChapter', 'categories'));
    }

    /**
     * Update the specified chapter.
     */
    public function update(AcademyChapterRequest $request, AcademyChapter $academyChapter)
    {
        $validated = $request->validated();
        $validated['sort_order'] = (int) ($validated['sort_order'] ?? 0);

        $academyChapter->update($validated);

        return redirect()->route('admin.content.academy-chapter.index')
            ->with('success', 'Chapter updated successfully.');
    }

    /**
     * Remove the specified chapter.
     */
    public function destroy(AcademyChapter $academyChapter)
    {
        $academyChapter->delete();

        return redirect()->route('admin.content.academy-chapter.index')
            ->with('success', 'Chapter deleted successfully.');
    }
}
