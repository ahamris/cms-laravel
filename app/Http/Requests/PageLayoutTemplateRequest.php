<?php

namespace App\Http\Requests;

use App\Enums\PageLayoutRowKind;
use App\Models\PageLayoutTemplate;
use App\Models\PageLayoutTemplateRow;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Validator;

class PageLayoutTemplateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    protected function prepareForValidation(): void
    {
        $shell = $this->input('shell_section');
        if (! is_string($shell) || ! in_array($shell, ['none', 'header', 'hero'], true)) {
            $shell = 'none';
        }
        $this->merge(['shell_section' => $shell]);

        $rows = $this->input('rows', []);
        foreach ($rows as $i => $row) {
            $kind = $row['row_kind'] ?? PageLayoutRowKind::Element->value;
            if ($kind !== PageLayoutRowKind::Element->value) {
                $rows[$i]['section_category'] = null;
            }
        }
        $this->merge(['rows' => $rows]);
    }

    public function rules(): array
    {
        $categoryKeys = array_keys(config('page_row_section_categories.categories', []));
        $rowKinds = array_column(PageLayoutRowKind::cases(), 'value');

        return [
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:2000',
            'use_header_section' => 'sometimes|boolean',
            'use_hero_section' => 'sometimes|boolean',
            'rows' => 'required|array|min:1',
            'rows.*.id' => 'nullable|integer',
            'rows.*.label' => 'required|string|max:255',
            'rows.*.row_kind' => ['required', 'string', Rule::in($rowKinds)],
            'rows.*.section_category' => ['nullable', 'string', Rule::in($categoryKeys)],
            'rows.*.sort_order' => 'nullable|integer|min:0|max:65535',
        ];
    }

    public function withValidator(Validator $validator): void
    {
        $validator->after(function (Validator $validator) {
            $template = $this->route('page_layout_template');
            $rows = $this->input('rows', []);

            foreach ($rows as $i => $row) {
                $id = $row['id'] ?? null;
                if ($id !== null && $id !== '') {
                    if (! $template instanceof PageLayoutTemplate) {
                        $validator->errors()->add("rows.{$i}.id", __('Row IDs cannot be used when creating a template.'));
                    } else {
                        $exists = PageLayoutTemplateRow::query()
                            ->whereKey($id)
                            ->where('page_layout_template_id', $template->id)
                            ->exists();
                        if (! $exists) {
                            $validator->errors()->add("rows.{$i}.id", __('Invalid row for this template.'));
                        }
                    }
                }

                $kind = $row['row_kind'] ?? PageLayoutRowKind::Element->value;
                if ($kind === PageLayoutRowKind::Element->value) {
                    $cat = $row['section_category'] ?? null;
                    if (! is_string($cat) || $cat === '') {
                        $validator->errors()->add("rows.{$i}.section_category", __('Pick a section type for this component row.'));

                        continue;
                    }
                    $types = config('page_row_section_categories.categories.'.$cat.'.element_types');
                    if (! is_array($types) || $types === []) {
                        $validator->errors()->add("rows.{$i}.section_category", __('This section type has no allowed elements configured.'));
                    }
                }
            }
        });
    }

    public function attributes(): array
    {
        return [
            'name' => __('Name'),
            'description' => __('Internal note'),
            'shell_section' => __('Page header section'),
            'rows' => __('Rows'),
            'rows.*.label' => __('Row label'),
            'rows.*.row_kind' => __('Row type'),
            'rows.*.section_category' => __('Section type'),
            'rows.*.sort_order' => __('Sort order'),
        ];
    }
}
