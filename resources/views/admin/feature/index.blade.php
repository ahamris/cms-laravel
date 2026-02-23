<x-layouts.admin title="Features">
    <div class="space-y-6">
        {{-- Page Header --}}
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-zinc-900 dark:text-white mb-2">Features</h1>
                <p class="text-zinc-600 dark:text-zinc-400">Manage features that can be associated with modules</p>
            </div>
            <div class="flex items-center gap-3">
                <x-button variant="primary" icon="plus" icon-position="left" href="{{ route('admin.feature.create') }}">Create New Feature</x-button>
            </div>
        </div>

        {{-- Features Table --}}
        <livewire:admin.table
            resource="feature"
            :columns="[
                ['key' => 'sort_order', 'label' => 'Order'],
                'title',
                ['key' => 'icon', 'type' => 'custom', 'view' => 'admin.feature.partials.icon-column'],
                ['key' => 'description', 'type' => 'custom', 'view' => 'admin.feature.partials.description-column'],
                ['key' => 'is_active', 'type' => 'toggle'],
            ]"
            route-prefix="admin.content.feature"
            search-placeholder="Search features..."
            :paginate="15"
            custom-actions-view="admin.feature.partials.table-actions"
            :search-fields="['title', 'description']"
        />
    </div>
</x-layouts.admin>
