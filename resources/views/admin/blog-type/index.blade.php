<x-layouts.admin title="{{ __('Article types') }}">
    <div class="space-y-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="mb-1 text-xl font-semibold text-zinc-900 dark:text-white">{{ __('Article types') }}</h1>
                <p class="text-[12.5px] text-zinc-600 dark:text-zinc-400">{{ __('Manage types (for example: blog, video, podcast, audio).') }}</p>
            </div>
            <x-ui.button variant="primary" icon="plus" icon-position="left" href="{{ route('admin.blog-type.create') }}">
                {{ __('Add type') }}
            </x-ui.button>
        </div>

        <livewire:admin.table
            resource="blog-type"
            :columns="[
                'id',
                'name',
                ['key' => 'description', 'type' => 'text', 'limit' => 90],
                ['key' => 'created_at', 'format' => 'date'],
            ]"
            route-prefix="admin.blog-type"
            search-placeholder="{{ __('Search types…') }}"
            :paginate="15"
            :search-fields="['name', 'description']"
            entity-count-label="{{ __('types') }}"
            :empty-state-title="__('No types found')"
            :empty-cta-url="route('admin.blog-type.create')"
            :empty-cta-label="__('Add type')"
        />
    </div>
</x-layouts.admin>
