<x-layouts.admin :title="__('New layout template')">
    <div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-start sm:justify-between">
        <div>
            <h1 class="text-2xl font-bold text-zinc-900 dark:text-white">{{ __('New layout template') }}</h1>
        </div>
        <x-button variant="secondary" icon="arrow-left" icon-position="left" href="{{ route('admin.page-layout-template.index') }}">
            {{ __('Back') }}
        </x-button>
    </div>

    <form action="{{ route('admin.page-layout-template.store') }}" method="POST" class="max-w-6xl space-y-6">
        @csrf
        @include('admin.page-layout-template.partials.template-settings', ['pageLayoutTemplate' => null])

        @php
            $__cats = config('page_row_section_categories.categories', []);
            $__defaultFirst = [
                'id' => null,
                'row_kind' => \App\Enums\PageLayoutRowKind::Element->value,
                'label' => $__cats['hero']['default_row_label'] ?? 'Hero',
                'section_category' => 'hero',
                'sort_order' => 0,
            ];
        @endphp
        @include('admin.page-layout-template.partials.row-fields', [
            'initialRows' => old('rows', [$__defaultFirst]),
        ])

        <div class="flex justify-end gap-3">
            <x-button variant="secondary" href="{{ route('admin.page-layout-template.index') }}">{{ __('Cancel') }}</x-button>
            <x-button type="submit" variant="primary" icon="save" icon-position="left">{{ __('Save') }}</x-button>
        </div>
    </form>
</x-layouts.admin>
