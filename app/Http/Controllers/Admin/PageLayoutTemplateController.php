<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\PageLayoutTemplateRequest;
use App\Models\PageLayoutTemplate;
use App\Models\PageLayoutTemplateRow;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class PageLayoutTemplateController extends AdminBaseController
{
    public function index(): View
    {
        $templates = PageLayoutTemplate::query()
            ->withCount('rows')
            ->orderBy('name')
            ->get();

        return view('admin.page-layout-template.index', compact('templates'));
    }

    public function create(): View
    {
        return view('admin.page-layout-template.create');
    }

    public function store(PageLayoutTemplateRequest $request): RedirectResponse
    {
        $validated = $request->validated();

        DB::transaction(function () use ($validated) {
            $shell = $validated['shell_section'];
            $template = PageLayoutTemplate::create([
                'name' => $validated['name'],
                'description' => $validated['description'] ?? null,
                'use_header_section' => $shell === 'header',
                'use_hero_section' => $shell === 'hero',
            ]);
            foreach ($validated['rows'] as $i => $row) {
                $template->rows()->create([
                    'row_kind' => $row['row_kind'],
                    'label' => $row['label'],
                    'section_category' => $row['section_category'],
                    'sort_order' => $row['sort_order'] ?? $i,
                ]);
            }
        });

        return redirect()
            ->route('admin.page-layout-template.index')
            ->with('success', __('Layout template created.'));
    }

    public function edit(PageLayoutTemplate $pageLayoutTemplate): View
    {
        $pageLayoutTemplate->load('rows');

        return view('admin.page-layout-template.edit', compact('pageLayoutTemplate'));
    }

    public function update(PageLayoutTemplateRequest $request, PageLayoutTemplate $pageLayoutTemplate): RedirectResponse
    {
        $validated = $request->validated();

        DB::transaction(function () use ($validated, $pageLayoutTemplate) {
            $pageLayoutTemplate->update([
                'name' => $validated['name'],
                'description' => $validated['description'] ?? null,
                'use_header_section' => $validated['use_header_section'] ?? false,
                'use_hero_section' => $validated['use_hero_section'] ?? false,
            ]);

            $keptIds = [];
            foreach ($validated['rows'] as $i => $row) {
                $sort = $row['sort_order'] ?? $i;
                if (! empty($row['id'])) {
                    $existing = PageLayoutTemplateRow::query()
                        ->where('page_layout_template_id', $pageLayoutTemplate->id)
                        ->whereKey($row['id'])
                        ->first();
                    if ($existing) {
                        $existing->update([
                            'row_kind' => $row['row_kind'],
                            'label' => $row['label'],
                            'section_category' => $row['section_category'],
                            'sort_order' => $sort,
                        ]);
                        $keptIds[] = (int) $existing->id;

                        continue;
                    }
                }
                $created = $pageLayoutTemplate->rows()->create([
                    'row_kind' => $row['row_kind'],
                    'label' => $row['label'],
                    'section_category' => $row['section_category'],
                    'sort_order' => $sort,
                ]);
                $keptIds[] = $created->id;
            }

            $pageLayoutTemplate->rows()->whereNotIn('id', $keptIds)->delete();
        });

        return redirect()
            ->route('admin.page-layout-template.index')
            ->with('success', __('Layout template updated.'));
    }

    public function destroy(PageLayoutTemplate $pageLayoutTemplate): RedirectResponse
    {
        $pageLayoutTemplate->delete();

        return redirect()
            ->route('admin.page-layout-template.index')
            ->with('success', __('Layout template deleted.'));
    }
}
