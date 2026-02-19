<?php

namespace App\Http\Controllers\Admin\Marketing;

use App\Http\Controllers\Admin\AdminBaseController;
use App\Models\ContentType;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class ContentTypeController extends AdminBaseController
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        $contentTypes = ContentType::ordered()->get();
        
        return view('admin.marketing.content-type.index', compact('contentTypes'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        return view('admin.marketing.content-type.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:content_types,slug',
            'description' => 'nullable|string',
            'icon' => 'nullable|string|max:255',
            'color' => 'nullable|string|regex:/^#([A-Fa-f0-9]{6}|[A-Fa-f0-9]{3})$/',
            'applicable_models' => 'nullable|array',
            'applicable_models.*' => 'string',
            'is_active' => 'boolean',
            'sort_order' => 'integer|min:0',
        ]);

        ContentType::create($validated);

        return redirect()->route('admin.marketing.content-type.index')
            ->with('success', 'Content type created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(ContentType $contentType): View
    {
        return view('admin.marketing.content-type.show', compact('contentType'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ContentType $contentType): View
    {
        return view('admin.marketing.content-type.edit', compact('contentType'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ContentType $contentType): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:content_types,slug,' . $contentType->id,
            'description' => 'nullable|string',
            'icon' => 'nullable|string|max:255',
            'color' => 'nullable|string|regex:/^#([A-Fa-f0-9]{6}|[A-Fa-f0-9]{3})$/',
            'applicable_models' => 'nullable|array',
            'applicable_models.*' => 'string',
            'is_active' => 'boolean',
            'sort_order' => 'integer|min:0',
        ]);

        $contentType->update($validated);

        return redirect()->route('admin.marketing.content-type.index')
            ->with('success', 'Content type updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ContentType $contentType): RedirectResponse
    {
        $contentType->delete();

        return redirect()->route('admin.marketing.content-type.index')
            ->with('success', 'Content type deleted successfully.');
    }
}
