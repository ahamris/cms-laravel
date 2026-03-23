<?php

namespace App\Http\Controllers\Admin\Element;

use App\Enums\ElementType;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;

class CardGridElementController extends BaseTypedElementController
{
    protected function type(): ElementType
    {
        return ElementType::CardGrid;
    }

    protected function heading(): string
    {
        return 'Card grid elements';
    }

    protected function routeBase(): string
    {
        return 'admin.element-card-grid';
    }

    protected function optionsFormView(): string
    {
        return 'admin.elements.forms.card-grid-options';
    }

    protected function showOptionsView(): string
    {
        return 'admin.elements.show.card-grid-options';
    }

    protected function typeHelp(): string
    {
        return 'Section label, title and description plus repeatable cards (each with label, title, description, button text and link).';
    }

    protected function validateOptions(Request $request): array
    {
        $validated = $request->validate([
            'options.label' => 'nullable|string|max:255',
            'options.title' => 'nullable|string|max:500',
            'options.description' => 'nullable|string|max:20000',
            'options.cards' => 'nullable|array',
            'options.cards.*.label' => 'nullable|string|max:255',
            'options.cards.*.title' => 'nullable|string|max:500',
            'options.cards.*.description' => 'nullable|string|max:10000',
            'options.cards.*.button_text' => 'nullable|string|max:255',
            'options.cards.*.button_link' => 'nullable|string|max:500',
        ]);

        $options = $validated['options'];
        $cards = collect(Arr::get($options, 'cards', []))
            ->filter(function ($row) {
                if (! is_array($row)) {
                    return false;
                }

                return filled($row['label'] ?? null)
                    || filled($row['title'] ?? null)
                    || filled($row['description'] ?? null)
                    || filled($row['button_text'] ?? null)
                    || filled($row['button_link'] ?? null);
            })
            ->values()
            ->map(function (array $row) {
                return [
                    'label' => $row['label'] ?? '',
                    'title' => $row['title'] ?? '',
                    'description' => $row['description'] ?? '',
                    'button_text' => $row['button_text'] ?? '',
                    'button_link' => $row['button_link'] ?? '',
                ];
            })
            ->all();

        $options['cards'] = $cards;

        return $options;
    }
}
