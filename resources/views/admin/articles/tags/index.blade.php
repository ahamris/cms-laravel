<x-layouts.admin title="{{ __('Tags') }}">
    <div class="space-y-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="mb-1 text-xl font-semibold text-zinc-900 dark:text-white">{{ __('Tags') }}</h1>
                <p class="text-[12.5px] text-zinc-600 dark:text-zinc-400">{{ __('Manage tags used across articles and pages.') }}</p>
            </div>
        </div>

        <div class="rounded-lg border border-zinc-200 bg-white p-4 dark:border-zinc-700 dark:bg-zinc-800/80">
            <form action="{{ route('admin.tag.store') }}" method="POST" class="flex flex-col gap-4 sm:flex-row sm:items-end">
                @csrf
                <div class="min-w-0 flex-1">
                    <x-ui.input name="name" label="{{ __('Tag name') }}" required placeholder="{{ __('Enter tag name…') }}" />
                </div>
                <div class="w-full sm:w-40">
                    <x-ui.input name="type" label="{{ __('Type') }}" placeholder="topic, tech…" />
                </div>
                <x-ui.button variant="primary" type="submit" icon="plus" icon-position="left" class="shrink-0">
                    {{ __('Add tag') }}
                </x-ui.button>
            </form>
        </div>

        <livewire:admin.table
            resource="tag"
            :columns="[
                'id',
                'name',
                'slug',
                ['key' => 'type', 'label' => 'Type'],
                ['key' => 'created_at', 'format' => 'date'],
            ]"
            route-prefix="admin.tag"
            search-placeholder="{{ __('Search tags…') }}"
            :paginate="25"
            :search-fields="['name', 'slug']"
            entity-count-label="{{ __('tags') }}"
            :empty-state-title="__('No tags found')"
        />
    </div>
</x-layouts.admin>
