<?php

namespace App\Http\Controllers\Admin\Element;

use App\Enums\ElementType;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;

class FaqElementController extends BaseTypedElementController
{
    protected function type(): ElementType
    {
        return ElementType::Faq;
    }

    protected function heading(): string
    {
        return 'FAQ Elements';
    }

    protected function routeBase(): string
    {
        return 'admin.element-faq';
    }

    protected function optionsFormView(): string
    {
        return 'admin.elements.forms.faq-options';
    }

    protected function showOptionsView(): string
    {
        return 'admin.elements.show.faq-options';
    }

    protected function typeHelp(): string
    {
        return 'Add question and answer pairs. Empty rows are ignored when saving.';
    }

    protected function validateOptions(Request $request): array
    {
        $validated = $request->validate([
            'options.layout' => 'required|in:accordion,tabs',
            'options.columns' => 'required|integer|in:1,2',
            'options.items' => 'nullable|array',
            'options.items.*.question' => 'nullable|string|max:2000',
            'options.items.*.answer' => 'nullable|string|max:50000',
        ]);

        $options = $validated['options'];
        $items = collect(Arr::get($options, 'items', []))
            ->filter(function ($row) {
                if (! is_array($row)) {
                    return false;
                }

                return filled($row['question'] ?? null) || filled($row['answer'] ?? null);
            })
            ->values()
            ->map(function (array $row) {
                return [
                    'question' => $row['question'] ?? '',
                    'answer' => $row['answer'] ?? '',
                ];
            })
            ->all();

        $options['items'] = $items;
        $options['columns'] = (int) $options['columns'];

        return $options;
    }
}
