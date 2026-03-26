<x-layouts.admin title="{{ __('Article categories') }}">
    <div class="space-y-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="mb-1 text-xl font-semibold text-zinc-900 dark:text-white">{{ __('Article categories') }}</h1>
                <p class="text-[12.5px] text-zinc-600 dark:text-zinc-400">{{ __('Manage categories and hierarchy for articles.') }}</p>
            </div>
            <div class="flex items-center gap-3">
                <x-ui.button variant="primary" icon="plus" icon-position="left" href="{{ route('admin.article-category.create') }}">
                    {{ __('Add category') }}
                </x-ui.button>
            </div>
        </div>

        <livewire:admin.table
            resource="article-category"
            :columns="[
                'id',
                'name',
                'slug',
                ['key' => 'color', 'type' => 'color'],
                ['key' => 'icon', 'label' => 'Icon'],
                ['key' => 'is_active', 'type' => 'toggle'],
                ['key' => 'sort_order', 'label' => 'Order'],
                ['key' => 'created_at', 'format' => 'date'],
            ]"
            route-prefix="admin.article-category"
            search-placeholder="{{ __('Search categories…') }}"
            :paginate="15"
            :search-fields="['name', 'slug', 'description']"
            entity-count-label="{{ __('categories') }}"
            :empty-state-title="__('No categories found')"
            :empty-cta-url="route('admin.article-category.create')"
            :empty-cta-label="__('Add category')"
        />
    </div>
</x-layouts.admin>
