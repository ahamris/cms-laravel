<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\AdminBaseController;
use App\Http\Requests\DocSectionRequest;
use App\Models\DocSection;
use App\Models\DocVersion;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class DocSectionController extends AdminBaseController
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        $sections = DocSection::with('version')
            ->orderBy('doc_version_id')
            ->orderBy('sort_order')
            ->get();

        return view('admin.content.doc-section.index', compact('sections'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $versions = DocVersion::active()->ordered()->get();

        return view('admin.content.doc-section.create', compact('versions'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(DocSectionRequest $request): RedirectResponse
    {
        try {
            $validated = $request->validated();
            $validated['is_active'] = $request->has('is_active');
            $validated['sort_order'] = $validated['sort_order'] ?? 0;

            if (empty($validated['slug'])) {
                unset($validated['slug']);
            }

            $section = DocSection::create($validated);

            $this->logCreate($section);

            return redirect()
                ->route('admin.content.doc-sections.index')
                ->with('success', 'Documentation section created successfully.');
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->withInput()
                ->withErrors(['error' => 'An error occurred while creating the section: '.$e->getMessage()]);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(DocSection $docSection): View
    {
        $docSection->load(['version', 'pages']);

        return view('admin.content.doc-section.show', compact('docSection'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(DocSection $docSection): View
    {
        $versions = DocVersion::active()->ordered()->get();

        return view('admin.content.doc-section.edit', compact('docSection', 'versions'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(DocSectionRequest $request, DocSection $docSection): RedirectResponse
    {
        try {
            $validated = $request->validated();
            $validated['is_active'] = $request->has('is_active');
            $validated['sort_order'] = $validated['sort_order'] ?? $docSection->sort_order;

            if (empty($validated['slug'])) {
                unset($validated['slug']);
            }

            $docSection->update($validated);

            $this->logUpdate($docSection);

            return redirect()
                ->route('admin.content.doc-sections.index')
                ->with('success', 'Documentation section updated successfully.');
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->withInput()
                ->withErrors(['error' => 'An error occurred while updating the section: '.$e->getMessage()]);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(DocSection $docSection): RedirectResponse
    {
        try {
            $this->logDelete($docSection);
            $docSection->delete();

            return redirect()
                ->route('admin.content.doc-sections.index')
                ->with('success', 'Documentation section deleted successfully.');
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->withErrors(['error' => 'An error occurred while deleting the section: '.$e->getMessage()]);
        }
    }

    /**
     * Toggle active status.
     */
    public function toggleActive(DocSection $docSection)
    {
        $docSection->update(['is_active' => !$docSection->is_active]);
        $this->logUpdate($docSection);

        return response()->json([
            'success' => true,
            'is_active' => $docSection->is_active,
            'message' => $docSection->is_active ? 'Section activated successfully!' : 'Section deactivated successfully!',
        ]);
    }
}
