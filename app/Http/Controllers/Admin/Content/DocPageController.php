<?php

namespace App\Http\Controllers\Admin\Content;

use App\Http\Controllers\Admin\AdminBaseController;
use App\Http\Requests\DocPageRequest;
use App\Models\DocPage;
use App\Models\DocSection;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class DocPageController extends AdminBaseController
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        $pages = DocPage::with(['section.version'])
            ->orderBy('doc_section_id')
            ->orderBy('sort_order')
            ->get();

        return view('admin.content.doc-page.index', compact('pages'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $sections = DocSection::with('version')
            ->whereHas('version', function ($query) {
                $query->where('is_active', true);
            })
            ->active()
            ->ordered()
            ->get()
            ->groupBy(function ($section) {
                return $section->version->name;
            });

        return view('admin.content.doc-page.create', compact('sections'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(DocPageRequest $request): RedirectResponse
    {
        try {
            $validated = $request->validated();
            $validated = $this->purifyHtmlKeys($validated, ['content']);
            $validated['is_active'] = $request->has('is_active');
            $validated['sort_order'] = $validated['sort_order'] ?? 0;

            if (empty($validated['slug'])) {
                unset($validated['slug']);
            }

            $page = DocPage::create($validated);

            $this->logCreate($page);

            return redirect()
                ->route('admin.content.doc-pages.index')
                ->with('success', 'Documentation page created successfully.');
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->withInput()
                ->withErrors(['error' => 'An error occurred while creating the page: '.$e->getMessage()]);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(DocPage $docPage): View
    {
        $docPage->load(['section.version']);

        return view('admin.content.doc-page.show', compact('docPage'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(DocPage $docPage): View
    {
        $sections = DocSection::with('version')
            ->whereHas('version', function ($query) {
                $query->where('is_active', true);
            })
            ->active()
            ->ordered()
            ->get()
            ->groupBy(function ($section) {
                return $section->version->name;
            });

        return view('admin.content.doc-page.edit', compact('docPage', 'sections'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(DocPageRequest $request, DocPage $docPage): RedirectResponse
    {
        try {
            $validated = $request->validated();
            $validated = $this->purifyHtmlKeys($validated, ['content']);
            $validated['is_active'] = $request->has('is_active');
            $validated['sort_order'] = $validated['sort_order'] ?? $docPage->sort_order;

            if (empty($validated['slug'])) {
                unset($validated['slug']);
            }

            $docPage->update($validated);

            $this->logUpdate($docPage);

            return redirect()
                ->route('admin.content.doc-pages.index')
                ->with('success', 'Documentation page updated successfully.');
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->withInput()
                ->withErrors(['error' => 'An error occurred while updating the page: '.$e->getMessage()]);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(DocPage $docPage): RedirectResponse
    {
        try {
            $this->logDelete($docPage);
            $docPage->delete();

            return redirect()
                ->route('admin.content.doc-pages.index')
                ->with('success', 'Documentation page deleted successfully.');
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->withErrors(['error' => 'An error occurred while deleting the page: '.$e->getMessage()]);
        }
    }

    /**
     * Toggle active status.
     */
    public function toggleActive(DocPage $docPage)
    {
        $docPage->update(['is_active' => !$docPage->is_active]);
        $this->logUpdate($docPage);

        return response()->json([
            'success' => true,
            'is_active' => $docPage->is_active,
            'message' => $docPage->is_active ? 'Page activated successfully!' : 'Page deactivated successfully!',
        ]);
    }
}
