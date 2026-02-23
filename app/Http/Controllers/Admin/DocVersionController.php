<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\AdminBaseController;
use App\Http\Requests\DocVersionRequest;
use App\Models\DocVersion;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class DocVersionController extends AdminBaseController
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        $versions = DocVersion::ordered()->get();

        return view('admin.content.doc-version.index', compact('versions'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        return view('admin.content.doc-version.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(DocVersionRequest $request): RedirectResponse
    {
        try {
            $validated = $request->validated();
            $validated['is_active'] = $request->has('is_active');
            $validated['is_default'] = $request->has('is_default');
            $validated['sort_order'] = $validated['sort_order'] ?? 0;

            $version = DocVersion::create($validated);

            $this->logCreate($version);

            return redirect()
                ->route('admin.content.doc-versions.index')
                ->with('success', 'Documentation version created successfully.');
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->withInput()
                ->withErrors(['error' => 'An error occurred while creating the version: '.$e->getMessage()]);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(DocVersion $docVersion): View
    {
        $docVersion->load('sections.pages');

        return view('admin.content.doc-version.show', compact('docVersion'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(DocVersion $docVersion): View
    {
        return view('admin.content.doc-version.edit', compact('docVersion'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(DocVersionRequest $request, DocVersion $docVersion): RedirectResponse
    {
        try {
            $validated = $request->validated();
            $validated['is_active'] = $request->has('is_active');
            $validated['is_default'] = $request->has('is_default');
            $validated['sort_order'] = $validated['sort_order'] ?? $docVersion->sort_order;

            $docVersion->update($validated);

            $this->logUpdate($docVersion);

            return redirect()
                ->route('admin.content.doc-versions.index')
                ->with('success', 'Documentation version updated successfully.');
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->withInput()
                ->withErrors(['error' => 'An error occurred while updating the version: '.$e->getMessage()]);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(DocVersion $docVersion): RedirectResponse
    {
        try {
            $this->logDelete($docVersion);
            $docVersion->delete();

            return redirect()
                ->route('admin.content.doc-versions.index')
                ->with('success', 'Documentation version deleted successfully.');
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->withErrors(['error' => 'An error occurred while deleting the version: '.$e->getMessage()]);
        }
    }

    /**
     * Toggle active status.
     */
    public function toggleActive(DocVersion $docVersion)
    {
        $docVersion->update(['is_active' => !$docVersion->is_active]);
        $this->logUpdate($docVersion);

        return response()->json([
            'success' => true,
            'is_active' => $docVersion->is_active,
            'message' => $docVersion->is_active ? 'Version activated successfully!' : 'Version deactivated successfully!',
        ]);
    }

    /**
     * Set as default version.
     */
    public function setDefault(DocVersion $docVersion): RedirectResponse
    {
        try {
            $docVersion->update(['is_default' => true, 'is_active' => true]);
            $this->logUpdate($docVersion);

            return redirect()
                ->route('admin.content.doc-versions.index')
                ->with('success', 'Version set as default successfully.');
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->withErrors(['error' => 'An error occurred: '.$e->getMessage()]);
        }
    }
}
