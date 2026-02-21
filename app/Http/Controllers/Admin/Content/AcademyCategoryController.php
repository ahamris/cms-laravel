<?php

namespace App\Http\Controllers\Admin\Content;

use App\Http\Controllers\Admin\AdminBaseController;
use App\Http\Requests\AcademyCategoryRequest;
use App\Models\AcademyCategory;
use Illuminate\View\View;

class AcademyCategoryController extends AdminBaseController
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        return view('admin.content.academy-category.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        return view('admin.content.academy-category.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(AcademyCategoryRequest $request)
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
            $payload['image_path'] = $request->file('image')->store('academy-categories', 'public');
        }

        AcademyCategory::create($payload);

        return redirect()->route('admin.content.academy-category.index')
            ->with('success', 'Academy category created successfully.');
    }

    /**
     * Get academy category data as JSON (for drawer edit/view).
     */
    public function getJson(AcademyCategory $academyCategory)
    {
        return response()->json([
            'id' => $academyCategory->id,
            'name' => $academyCategory->name,
            'slug' => $academyCategory->slug,
            'description' => $academyCategory->description,
            'image_path' => $academyCategory->image_path,
            'image_url' => $academyCategory->image_url,
            'sort_order' => $academyCategory->sort_order,
            'is_active' => $academyCategory->is_active,
            'created_at' => $academyCategory->created_at?->toIso8601String(),
            'updated_at' => $academyCategory->updated_at?->toIso8601String(),
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(AcademyCategory $academyCategory): View
    {
        $academyCategory->loadCount('videos');
        return view('admin.content.academy-category.show', compact('academyCategory'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(AcademyCategory $academyCategory): View
    {
        return view('admin.content.academy-category.edit', compact('academyCategory'));
    }

    /**
     * Update the resource in storage.
     */
    public function update(AcademyCategoryRequest $request, AcademyCategory $academyCategory)
    {
        $validated = $request->validated();
        unset($validated['image']); // never pass the file to the model

        $payload = [
            'name' => $validated['name'],
            'slug' => $validated['slug'],
            'description' => $validated['description'] ?? null,
            'sort_order' => (int) ($validated['sort_order'] ?? 0),
            'is_active' => $request->input('is_active', '0') === '1',
        ];

        if ($request->hasFile('image')) {
            if ($this->isLocalImagePath($academyCategory->image_path)) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($academyCategory->image_path);
            }
            $payload['image_path'] = $request->file('image')->store('academy-categories', 'public');
        } elseif ($request->boolean('remove_image')) {
            if ($this->isLocalImagePath($academyCategory->image_path)) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($academyCategory->image_path);
            }
            $payload['image_path'] = null;
        }

        $academyCategory->update($payload);

        return redirect()->route('admin.content.academy-category.index')
            ->with('success', 'Academy category updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(AcademyCategory $academyCategory)
    {
        if ($this->isLocalImagePath($academyCategory->image_path)) {
            \Illuminate\Support\Facades\Storage::disk('public')->delete($academyCategory->image_path);
        }

        $academyCategory->delete();

        return redirect()->route('admin.content.academy-category.index')
            ->with('success', 'Academy category deleted successfully.');
    }

    /**
     * Toggle academy category active status.
     */
    public function toggleActive(AcademyCategory $academyCategory)
    {
        $academyCategory->update(['is_active' => !$academyCategory->is_active]);

        return response()->json([
            'success' => true,
            'is_active' => $academyCategory->is_active,
            'message' => $academyCategory->is_active ? 'Category activated.' : 'Category deactivated.',
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
