<x-layouts.admin title="{{ __('Category groups') }}">
    <div class="space-y-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="mb-1 text-xl font-semibold text-zinc-900 dark:text-white">{{ __('Category groups') }}</h1>
                <p class="text-[12.5px] text-zinc-600 dark:text-zinc-400">{{ __('Manage groups used to organise articles (for example: use cases, news).') }}</p>
            </div>
            <div class="flex items-center gap-3">
                <x-ui.button variant="primary" icon="plus" icon-position="left" href="{{ route('admin.blog-category.create') }}">
                    {{ __('Add group') }}
                </x-ui.button>
            </div>
        </div>

        <livewire:admin.table
            resource="blog-category"
            :columns="[
                'id',
                'name',
                'slug',
                ['key' => 'description', 'type' => 'text', 'limit' => 60],
                ['key' => 'color', 'type' => 'color'],
                ['key' => 'is_active', 'type' => 'toggle'],
                ['key' => 'created_at', 'format' => 'date'],
            ]"
            route-prefix="admin.blog-category"
            search-placeholder="{{ __('Search groups…') }}"
            :paginate="15"
            custom-actions-view="admin.blog-category.partials.table-actions"
            :search-fields="['name', 'slug', 'description']"
            entity-count-label="{{ __('groups') }}"
            :empty-state-title="__('No groups found')"
            :empty-cta-url="route('admin.blog-category.create')"
            :empty-cta-label="__('Add group')"
        />
    </div>
</x-layouts.admin>
