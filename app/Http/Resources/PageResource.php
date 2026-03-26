<?php

namespace App\Http\Resources;

use App\Enums\PageLayoutRowKind;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PageResource extends JsonResource
{
    /**
     * Transform the resource into an array (single page with long_body).
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $page = $this->resource;
        $elements = $page->relationLoaded('elements') ? $page->elements : collect();

        $layoutRows = $this->resolvedLayoutRows($page, $request);

        return resource_urls_to_paths([
            'id' => $this->id,
            'title' => $this->title,
            'slug' => $this->slug,
            'short_body' => $this->short_body,
            'long_body' => $this->long_body,
            'meta_title' => $this->meta_title,
            'meta_body' => $this->meta_body,
            'meta_keywords' => $this->meta_keywords,
            'image' => get_image($this->image, asset('front/images/blog.png')),
            'icon' => $this->icon,
            'layout' => $this->template ?? config('page_templates.default', 'default'),
            'template' => resolve_menu_template(api_path('page', $this->slug), $this->slug),
            'url' => route('api.pages.show', ['slug' => $this->slug]),
            'elements' => ElementResource::collection($elements)->resolve($request),
            'layout_template' => $page->relationLoaded('pageLayoutTemplate') && $page->pageLayoutTemplate
                ? [
                    'id' => $page->pageLayoutTemplate->id,
                    'name' => $page->pageLayoutTemplate->name,
                    'description' => $page->pageLayoutTemplate->description,
                    'use_header_section' => (bool) $page->pageLayoutTemplate->use_header_section,
                    'use_hero_section' => (bool) $page->pageLayoutTemplate->use_hero_section,
                ]
                : null,
            'layout_rows' => $layoutRows,
            'created_at' => $this->created_at?->toIso8601String(),
            'updated_at' => $this->updated_at?->toIso8601String(),
        ]);
    }

    /**
     * @return list<array<string, mixed>>
     */
    private function resolvedLayoutRows($page, Request $request): array
    {
        if (! $page->page_layout_template_id) {
            return [];
        }

        $page->loadMissing([
            'layoutAssignments.templateRow',
            'layoutAssignments.element',
            'pageLayoutTemplate',
        ]);

        return $page->layoutAssignments
            ->filter(fn ($a) => $a->templateRow !== null)
            ->sortBy(fn ($a) => [$a->templateRow->sort_order, $a->templateRow->id])
            ->values()
            ->map(function ($a) use ($page, $request) {
                $tr = $a->templateRow;
                $kind = $tr->row_kind instanceof \BackedEnum ? $tr->row_kind : PageLayoutRowKind::tryFrom((string) $tr->row_kind) ?? PageLayoutRowKind::Element;

                $base = [
                    'row_id' => $a->page_layout_template_row_id,
                    'label' => $tr->label,
                    'row_kind' => $kind->value,
                ];

                if ($kind === PageLayoutRowKind::ShortBody) {
                    $base['content'] = $page->short_body;

                    return $base;
                }

                if ($kind === PageLayoutRowKind::LongBody) {
                    $base['content'] = $page->long_body;

                    return $base;
                }

                $base['section_category'] = $tr->section_category ?? 'content';
                $base['element'] = $a->element
                    ? (new ElementResource($a->element))->resolve($request)
                    : null;

                return $base;
            })
            ->all();
    }
}
