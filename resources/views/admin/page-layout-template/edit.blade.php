<x-layouts.admin :title="__('Edit layout template')">
    <div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-start sm:justify-between">
        <div>
            <h1 class="text-2xl font-bold text-zinc-900 dark:text-white">{{ __('Edit layout template') }}</h1>
            <p class="mt-1 text-sm text-zinc-600 dark:text-zinc-400">{{ $pageLayoutTemplate->name }}</p>
        </div>
        <x-button variant="secondary" icon="arrow-left" icon-position="left" href="{{ route('admin.page-layout-template.index') }}">
            {{ __('Back') }}
        </x-button>
    </div>

    <form action="{{ route('admin.page-layout-template.update', $pageLayoutTemplate) }}" method="POST" class="max-w-6xl space-y-6">
        @csrf
        @method('PUT')
        @include('admin.page-layout-template.partials.template-settings', ['pageLayoutTemplate' => $pageLayoutTemplate])

        @php
            $rowDefaults = $pageLayoutTemplate->rows->map(fn ($r) => [
                'id' => $r->id,
                'row_kind' => $r->row_kind instanceof \BackedEnum ? $r->row_kind->value : ($r->row_kind ?? \App\Enums\PageLayoutRowKind::Element->value),
                'label' => $r->label,
                'section_category' => $r->section_category,
                'sort_order' => $r->sort_order,
            ])->values()->all();
            $initialRows = old('rows', $rowDefaults);
            if (! is_array($initialRows) || $initialRows === []) {
                $initialRows = [[
                    'id' => null,
                    'row_kind' => \App\Enums\PageLayoutRowKind::Element->value,
                    'label' => config('page_row_section_categories.categories.hero.default_row_label', 'Hero'),
                    'section_category' => 'hero',
                    'sort_order' => 0,
                ]];
            }
        @endphp
        @include('admin.page-layout-template.partials.row-fields', ['initialRows' => $initialRows])

        <div class="flex justify-end gap-3">
            <x-button variant="secondary" href="{{ route('admin.page-layout-template.index') }}">{{ __('Cancel') }}</x-button>
            <x-button type="submit" variant="primary" icon="save" icon-position="left">{{ __('Save changes') }}</x-button>
        </div>
    </form>
</x-layouts.admin>
