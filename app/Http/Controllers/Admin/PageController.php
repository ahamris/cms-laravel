<?php

namespace App\Http\Controllers\Admin;

use App\Enums\ElementType;
use App\Enums\PageLayoutRowKind;
use App\Http\Requests\PageRequest;
use App\Models\ContentType;
use App\Models\Element;
use App\Models\MarketingPersona;
use App\Models\Page;
use App\Models\PageLayoutAssignment;
use App\Models\PageLayoutTemplate;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
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

        [$faqElements, $ctaElements] = $this->faqAndCtaElementLists();

        return view('admin.page.create', array_merge(
            compact(
                'marketingPersonas',
                'contentTypes',
                'templates',
                'currentTemplate',
                'faqElements',
                'ctaElements'
            ),
            $this->pageLayoutBuilderData(null)
        ));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(PageRequest $request): RedirectResponse
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

        $this->syncLayoutAssignments(
            $page,
            $validated['page_layout_template_id'] ?? null,
            $request->input('layout_row_element', [])
        );
        $this->syncPageElements($page, $faqElementId, $ctaElementId);

        Cache::forget("page.{$page->id}");
        Cache::forget("page.slug.{$page->slug}");

        // Log activity
        $this->logCreate($page);

        $flash = ['success' => 'Page created successfully!'];

        return $request->input('submit_action') === 'index'
            ? redirect()->route('admin.page.index')->with($flash)
            : redirect()->route('admin.page.edit', $page)->with($flash);
    }

    /**
     * Display the specified resource.
     */
    public function show(Page $page): View
    {
        $page->load([
            'elements',
            'marketingPersona',
            'contentType',
            'ogImage',
            'parent',
            'pageLayoutTemplate',
            'layoutAssignments.templateRow',
            'layoutAssignments.element',
        ]);

        return view('admin.page.show', compact('page'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Page $page): View
    {
        $page->load(['marketingPersona', 'contentType', 'elements', 'layoutAssignments']);

        $marketingPersonas = MarketingPersona::active()
            ->ordered()
            ->get();

        $contentTypes = ContentType::active()
            ->ordered()
            ->get();

        $templates = config('page_templates.templates', []);
        $currentTemplate = old('template', $page->template ?? config('page_templates.default', 'default'));

        [$faqElements, $ctaElements] = $this->faqAndCtaElementLists();

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
    public function update(PageRequest $request, Page $page): RedirectResponse
    {
        $validated = $request->validated();
        $faqElementId = $validated['faq_element_id'] ?? null;
        $ctaElementId = $validated['cta_element_id'] ?? null;
        unset($validated['faq_element_id'], $validated['cta_element_id']);

        $validated = $this->purifyHtmlKeys($validated, ['short_body', 'long_body']);

        // New upload wins over "remove" so replacing an image in one submit works
        if ($request->hasFile('image')) {
            if ($page->image) {
                Storage::disk('public')->delete($page->image);
            }

            $validated['image'] = $request->file('image')->store('pages', 'public');
        } elseif ($request->has('remove_image') && $request->input('remove_image') == '1') {
            if ($page->image) {
                Storage::disk('public')->delete($page->image);
            }
            $validated['image'] = null;
        }

        // Handle secondary keywords array
        if (isset($validated['secondary_keywords'])) {
            $validated['secondary_keywords'] = array_filter(array_map('trim', $validated['secondary_keywords']));
            $validated['secondary_keywords'] = ! empty($validated['secondary_keywords']) ? $validated['secondary_keywords'] : null;
        }

        $page->update($validated);

        $this->syncLayoutAssignments(
            $page,
            $validated['page_layout_template_id'] ?? null,
            $request->input('layout_row_element', [])
        );
        $this->syncPageElements($page, $faqElementId, $ctaElementId);

        Cache::forget("page.{$page->id}");
        Cache::forget("page.slug.{$page->slug}");

        // Log activity
        $this->logUpdate($page);

        $flash = ['success' => 'Page updated successfully!'];

        return $request->input('submit_action') === 'index'
            ? redirect()->route('admin.page.index')->with($flash)
            : redirect()->route('admin.page.edit', $page)->with($flash);
    }

    /**
     * Duplicate a page (new draft copy with unique slug; not homepage; copies featured image file when present).
     */
    public function duplicate(Page $page): RedirectResponse
    {
        $page->load(['elements', 'layoutAssignments']);

        $copy = $page->replicate([
            'id',
            'created_at',
            'updated_at',
        ]);

        $copy->title = __('Copy of :title', ['title' => $page->title]);
        $copy->slug = $this->uniqueDuplicateSlug($page->slug);
        $copy->is_homepage = false;
        $copy->is_active = false;
        $copy->save();

        foreach ($page->layoutAssignments as $assignment) {
            PageLayoutAssignment::query()->create([
                'page_id' => $copy->id,
                'page_layout_template_row_id' => $assignment->page_layout_template_row_id,
                'element_id' => $assignment->element_id,
            ]);
        }

        $copy->elements()->sync($page->elements->pluck('id')->all());

        if ($page->image && Storage::disk('public')->exists($page->image)) {
            $ext = pathinfo($page->image, PATHINFO_EXTENSION) ?: 'jpg';
            $newPath = 'pages/'.Str::uuid().($ext ? '.'.$ext : '');
            Storage::disk('public')->copy($page->image, $newPath);
            $copy->update(['image' => $newPath]);
        }

        Cache::forget("page.{$copy->id}");
        Cache::forget("page.slug.{$copy->slug}");

        $this->logCreate($copy);

        return redirect()
            ->route('admin.page.edit', $copy)
            ->with('success', __('Page duplicated. You are editing the copy.'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Page $page)
    {
        $this->logDelete($page);

        // Delete image if exists
        if ($page->image) {
            Storage::disk('public')->delete($page->image);
        }

        $page->delete();

        return redirect()->route('admin.page.index')
            ->with('success', 'Page deleted successfully!');
    }

    /**
     * Attach at most one FAQ and one CTA element; keep other element types on the pivot.
     */
    /**
     * Single query for FAQ + CTA dropdown options (admin page forms).
     *
     * @return array{0: Collection, 1: Collection}
     */
    /**
     * @return array<string, mixed>
     */
    private function pageLayoutBuilderData(?Page $page): array
    {
        $templates = PageLayoutTemplate::query()
            ->with('rows')
            ->orderBy('name')
            ->get();

        $pageLayoutTemplatesData = $templates->map(fn (PageLayoutTemplate $t) => [
            'id' => $t->id,
            'name' => $t->name,
            'description' => $t->description,
            'use_header_section' => (bool) $t->use_header_section,
            'use_hero_section' => (bool) $t->use_hero_section,
            'rows' => $t->rows->sortBy('sort_order')->values()->map(fn ($r) => [
                'id' => $r->id,
                'label' => $r->label,
                'row_kind' => $r->row_kind instanceof \BackedEnum ? $r->row_kind->value : ($r->row_kind ?? PageLayoutRowKind::Element->value),
                'section_category' => $r->section_category,
            ])->all(),
        ])->values()->all();

        $sectionElementTypes = collect(config('page_row_section_categories.categories', []))
            ->map(fn (array $meta) => $meta['element_types'] ?? [])
            ->all();

        $elementsForLayout = Element::query()
            ->orderBy('type')
            ->orderBy('title')
            ->get()
            ->map(fn (Element $e) => [
                'id' => $e->id,
                'type' => $e->type instanceof \BackedEnum ? $e->type->value : $e->type,
                'title' => $e->title !== null && $e->title !== '' ? $e->title : ('#'.$e->id),
            ])
            ->values()
            ->all();

        $layoutRowSelections = [];
        if ($page !== null) {
            foreach ($page->layoutAssignments as $a) {
                $layoutRowSelections[(string) $a->page_layout_template_row_id] = $a->element_id;
            }
        }

        $savedRows = old('layout_row_element');
        $rowMap = is_array($savedRows) ? $savedRows : $layoutRowSelections;

        return [
            'pageLayoutTemplates' => $templates,
            'pageLayoutTemplatesData' => $pageLayoutTemplatesData,
            'elementsForLayout' => $elementsForLayout,
            'sectionElementTypes' => $sectionElementTypes,
            'layoutRowSelections' => (object) $rowMap,
        ];
    }

    /**
     * @param  array<string|int, mixed>  $layoutRowElement
     */
    private function syncLayoutAssignments(Page $page, ?int $templateId, array $layoutRowElement): void
    {
        PageLayoutAssignment::query()->where('page_id', $page->id)->delete();

        if ($templateId === null) {
            return;
        }

        $template = PageLayoutTemplate::query()->with('rows')->find($templateId);
        if (! $template) {
            return;
        }

        foreach ($template->rows as $row) {
            $raw = $layoutRowElement[(string) $row->id] ?? $layoutRowElement[$row->id] ?? null;
            $elementId = ($raw === '' || $raw === null) ? null : (int) $raw;

            $kind = $row->row_kind instanceof \BackedEnum ? $row->row_kind : PageLayoutRowKind::tryFrom((string) $row->row_kind) ?? PageLayoutRowKind::Element;
            if ($kind !== PageLayoutRowKind::Element) {
                $elementId = null;
            }

            PageLayoutAssignment::query()->create([
                'page_id' => $page->id,
                'page_layout_template_row_id' => $row->id,
                'element_id' => $elementId,
            ]);
        }
    }

    private function faqAndCtaElementLists(): array
    {
        $rows = Element::query()
            ->whereIn('type', [ElementType::Faq->value, ElementType::Cta->value])
            ->orderBy('type')
            ->orderBy('title')
            ->get();

        return [
            $rows->filter(fn (Element $e) => $e->type === ElementType::Faq)->values(),
            $rows->filter(fn (Element $e) => $e->type === ElementType::Cta)->values(),
        ];
    }

    private function uniqueDuplicateSlug(string $baseSlug): string
    {
        $slug = $baseSlug.'-copy';
        $i = 2;
        while (Page::query()->where('slug', $slug)->exists()) {
            $slug = $baseSlug.'-copy-'.$i;
            $i++;
        }

        return $slug;
    }

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
