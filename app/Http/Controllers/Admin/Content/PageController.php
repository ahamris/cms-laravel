<?php

namespace App\Http\Controllers\Admin\Content;

use App\Http\Controllers\Admin\AdminBaseController;
use App\Http\Requests\PageRequest;
use App\Models\ContentType;
use App\Models\MarketingPersona;
use App\Models\Page;
use Illuminate\Support\Facades\Cache;
use Illuminate\View\View;

class PageController extends AdminBaseController
{
    /**
     * Display a listing of the resource.
     * Table is rendered by Livewire admin.table component.
     */
    public function index(): View
    {
        return view('admin.content.page.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $marketingPersonas = MarketingPersona::active()
            ->ordered()
            ->get();

        $contentTypes = ContentType::active()
            ->ordered()
            ->get();

        $templates = config('page_templates.templates', []);
        $currentTemplate = old('template', config('page_templates.default', 'default'));

        return view('admin.content.page.create', compact(
            'marketingPersonas',
            'contentTypes',
            'templates',
            'currentTemplate'
        ));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(PageRequest $request)
    {
        $validated = $request->validated();
        $validated = $this->purifyHtmlKeys($validated, ['short_body', 'long_body']);

        // Handle image upload
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('pages', 'public');
            $validated['image'] = $imagePath;
        }

        // Handle secondary keywords array
        if (isset($validated['secondary_keywords'])) {
            $validated['secondary_keywords'] = array_filter(array_map('trim', $validated['secondary_keywords']));
            $validated['secondary_keywords'] = ! empty($validated['secondary_keywords']) ? $validated['secondary_keywords'] : null;
        }

        $page = Page::create($validated);

        Cache::forget("page.{$page->id}");
        Cache::forget("page.slug.{$page->slug}");

        // Log activity
        $this->logCreate($page);

        return redirect()->route('admin.content.page.index')
            ->with('success', 'Page created successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Page $page): View
    {
        return view('admin.content.page.show', compact('page'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Page $page): View
    {
        $page->load(['marketingPersona', 'contentType']);

        $marketingPersonas = MarketingPersona::active()
            ->ordered()
            ->get();

        $contentTypes = ContentType::active()
            ->ordered()
            ->get();

        $templates = config('page_templates.templates', []);
        $currentTemplate = old('template', $page->template ?? config('page_templates.default', 'default'));

        return view('admin.content.page.edit', compact(
            'page',
            'marketingPersonas',
            'contentTypes',
            'templates',
            'currentTemplate'
        ));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(PageRequest $request, Page $page)
    {
        $validated = $request->validated();
        $validated = $this->purifyHtmlKeys($validated, ['short_body', 'long_body']);

        // Handle image deletion
        if ($request->has('remove_image') && $request->input('remove_image') == '1') {
            // Delete old image from storage if exists
            if ($page->image) {
                \Storage::disk('public')->delete($page->image);
            }
            // Set image to null in database
            $validated['image'] = null;
        }
        // Handle image upload
        elseif ($request->hasFile('image')) {
            // Delete old image
            if ($page->image) {
                \Storage::disk('public')->delete($page->image);
            }

            $imagePath = $request->file('image')->store('pages', 'public');
            $validated['image'] = $imagePath;
        }

        // Handle secondary keywords array
        if (isset($validated['secondary_keywords'])) {
            $validated['secondary_keywords'] = array_filter(array_map('trim', $validated['secondary_keywords']));
            $validated['secondary_keywords'] = ! empty($validated['secondary_keywords']) ? $validated['secondary_keywords'] : null;
        }

        $page->update($validated);

        Cache::forget("page.{$page->id}");
        Cache::forget("page.slug.{$page->slug}");

        // Log activity
        $this->logUpdate($page);

        return redirect()->route('admin.content.page.index')
            ->with('success', 'Page updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Page $page)
    {
        $this->logDelete($page);

        // Delete image if exists
        if ($page->image) {
            \Storage::disk('public')->delete($page->image);
        }

        $page->delete();

        return redirect()->route('admin.content.page.index')
            ->with('success', 'Page deleted successfully!');
    }

    /**
     * Toggle page active status
     */
    public function toggleActive(Page $page)
    {
        $page->update(['is_active' => ! $page->is_active]);

        return response()->json([
            'success' => true,
            'is_active' => $page->is_active,
            'message' => $page->is_active ? 'Page activated successfully!' : 'Page deactivated successfully!',
        ]);
    }
}
