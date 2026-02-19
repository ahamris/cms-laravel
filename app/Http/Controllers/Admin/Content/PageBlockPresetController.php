<?php

namespace App\Http\Controllers\Admin\Content;

use App\Http\Controllers\Admin\AdminBaseController;
use App\Http\Requests\PageBlockPresetRequest;
use App\Models\PageBlockPreset;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class PageBlockPresetController extends AdminBaseController
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        $presets = PageBlockPreset::with('creator')
            ->orderBy('type')
            ->orderBy('name')
            ->get();

        return view('admin.content.page-block-preset.index', compact('presets'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        return view('admin.content.page-block-preset.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(PageBlockPresetRequest $request): RedirectResponse
    {
        $validated = $request->validated();

        $preset = PageBlockPreset::create([
            'name' => $validated['name'],
            'description' => $validated['description'] ?? null,
            'type' => $validated['type'],
            'blocks' => $validated['blocks'],
            'is_active' => $validated['is_active'] ?? true,
            'created_by' => auth()->id(),
        ]);

        // Log activity
        $this->logCreate($preset);

        return redirect()->route('admin.content.page-block-preset.index')
            ->with('success', 'Preset created successfully!');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(PageBlockPreset $pageBlockPreset): View
    {
        return view('admin.content.page-block-preset.edit', compact('pageBlockPreset'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(PageBlockPresetRequest $request, PageBlockPreset $pageBlockPreset): RedirectResponse
    {
        $validated = $request->validated();

        $pageBlockPreset->update([
            'name' => $validated['name'],
            'description' => $validated['description'] ?? null,
            'type' => $validated['type'],
            'blocks' => $validated['blocks'],
            'is_active' => $validated['is_active'] ?? $pageBlockPreset->is_active,
        ]);

        // Log activity
        $this->logUpdate($pageBlockPreset);

        return redirect()->route('admin.content.page-block-preset.index')
            ->with('success', 'Preset updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(PageBlockPreset $pageBlockPreset): RedirectResponse
    {
        // Log activity before deletion
        $this->logDelete($pageBlockPreset);

        $pageBlockPreset->delete();

        return redirect()->route('admin.content.page-block-preset.index')
            ->with('success', 'Preset deleted successfully!');
    }
}
