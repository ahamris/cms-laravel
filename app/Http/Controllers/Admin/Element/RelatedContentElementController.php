<?php

namespace App\Http\Controllers\Admin\Element;

use App\Enums\ElementType;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;

class RelatedContentElementController extends BaseTypedElementController
{
    protected function type(): ElementType
    {
        return ElementType::RelatedContent;
    }

    protected function heading(): string
    {
        return 'Related Content Elements';
    }

    protected function routeBase(): string
    {
        return 'admin.element-related-content';
    }

    protected function optionsFormView(): string
    {
        return 'admin.elements.forms.related-content-options';
    }

    protected function showOptionsView(): string
    {
        return 'admin.elements.show.related-content-options';
    }

    protected function typeHelp(): string
    {
        return 'Link to related pages or resources with title, URL, and optional excerpt.';
    }

    protected function validateOptions(Request $request): array
    {
        $validated = $request->validate([
            'options.layout' => 'required|in:grid,list,carousel',
            'options.columns' => 'required|integer|in:1,2,3,4',
            'options.items' => 'nullable|array',
            'options.items.*.title' => 'nullable|string|max:255',
            'options.items.*.url' => 'nullable|string|max:500',
            'options.items.*.excerpt' => 'nullable|string|max:2000',
        ]);

        $options = $validated['options'];
        $items = collect(Arr::get($options, 'items', []))
            ->filter(function ($row) {
                if (! is_array($row)) {
                    return false;
                }

                return filled($row['title'] ?? null) || filled($row['url'] ?? null) || filled($row['excerpt'] ?? null);
            })
            ->values()
            ->map(function (array $row) {
                return [
                    'title' => $row['title'] ?? '',
                    'url' => $row['url'] ?? '',
                    'excerpt' => $row['excerpt'] ?? '',
                ];
            })
            ->all();

        $options['items'] = $items;
        $options['columns'] = (int) $options['columns'];

        return $options;
    }
}
