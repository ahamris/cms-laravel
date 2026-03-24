<?php

namespace App\Http\Controllers\Admin;

use App\Enums\ElementType;
use App\Http\Requests\PageRequest;
use App\Models\ContentType;
use App\Models\Element;
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
        return view('admin.page.index');
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

        $faqElements = Element::byType(ElementType::Faq)->orderBy('title')->get();
        $ctaElements = Element::byType(ElementType::Cta)->orderBy('title')->get();

        return view('admin.page.create', compact(
            'marketingPersonas',
            'contentTypes',
            'templates',
            'currentTemplate',
            'faqElements',
            'ctaElements'
        ));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(PageRequest $request)
    {
        $validated = $request->validated();
        $faqElementId = $validated['faq_element_id'] ?? null;
        $ctaElementId = $validated['cta_element_id'] ?? null;
        unset($validated['faq_element_id'], $validated['cta_element_id']);

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

        $this->syncPageElements($page, $faqElementId, $ctaElementId);

        Cache::forget("page.{$page->id}");
        Cache::forget("page.slug.{$page->slug}");

        // Log activity
        $this->logCreate($page);

        return redirect()->route('admin.page.index')
            ->with('success', 'Page created successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Page $page): View
    {
        $page->load('elements');

        return view('admin.page.show', compact('page'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Page $page): View
    {
        $page->load(['marketingPersona', 'contentType', 'elements']);

        $marketingPersonas = MarketingPersona::active()
            ->ordered()
            ->get();

        $contentTypes = ContentType::active()
            ->ordered()
            ->get();

        $templates = config('page_templates.templates', []);
        $currentTemplate = old('template', $page->template ?? config('page_templates.default', 'default'));

        $faqElements = Element::byType(ElementType::Faq)->orderBy('title')->get();
        $ctaElements = Element::byType(ElementType::Cta)->orderBy('title')->get();

        return view('admin.page.edit', compact(
            'page',
            'marketingPersonas',
            'contentTypes',
            'templates',
            'currentTemplate',
            'faqElements',
            'ctaElements'
        ));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(PageRequest $request, Page $page)
    {
        $validated = $request->validated();
        $faqElementId = $validated['faq_element_id'] ?? null;
        $ctaElementId = $validated['cta_element_id'] ?? null;
        unset($validated['faq_element_id'], $validated['cta_element_id']);

        $validated = $this->purifyHtmlKeys($validated, ['short_body', 'long_body']);

        // New upload wins over "remove" so replacing an image in one submit works
        if ($request->hasFile('image')) {
            if ($page->image) {
                \Storage::disk('public')->delete($page->image);
            }

            $validated['image'] = $request->file('image')->store('pages', 'public');
        } elseif ($request->has('remove_image') && $request->input('remove_image') == '1') {
            if ($page->image) {
                \Storage::disk('public')->delete($page->image);
            }
            $validated['image'] = null;
        }

        // Handle secondary keywords array
        if (isset($validated['secondary_keywords'])) {
            $validated['secondary_keywords'] = array_filter(array_map('trim', $validated['secondary_keywords']));
            $validated['secondary_keywords'] = ! empty($validated['secondary_keywords']) ? $validated['secondary_keywords'] : null;
        }

        $page->update($validated);

        $this->syncPageElements($page, $faqElementId, $ctaElementId);

        Cache::forget("page.{$page->id}");
        Cache::forget("page.slug.{$page->slug}");

        // Log activity
        $this->logUpdate($page);

        return redirect()->route('admin.page.index')
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

        return redirect()->route('admin.page.index')
            ->with('success', 'Page deleted successfully!');
    }

    /**
     * Attach at most one FAQ and one CTA element; keep other element types on the pivot.
     */
    private function syncPageElements(Page $page, ?int $faqElementId, ?int $ctaElementId): void
    {
        $otherIds = $page->elements()
            ->whereNotIn('elements.type', [ElementType::Faq->value, ElementType::Cta->value])
            ->pluck('elements.id');

        $selected = array_values(array_filter([
            $faqElementId,
            $ctaElementId,
        ]));

        $page->elements()->sync($otherIds->merge($selected)->unique()->values()->all());
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
