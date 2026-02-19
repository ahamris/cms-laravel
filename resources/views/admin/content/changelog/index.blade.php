<x-layouts.admin title="Changelog Management">
    <div class="space-y-6">
        {{-- Header --}}
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-zinc-900 dark:text-white mb-2">Changelog Management</h1>
                <p class="text-zinc-600 dark:text-zinc-400">Manage changelog entries and updates</p>
            </div>
            <x-button variant="primary" icon="plus" icon-position="left" href="{{ route('admin.content.changelog.create') }}">
                Add New Entry
            </x-button>
        </div>

        {{-- Changelog Table --}}
        <livewire:admin.table
            resource="changelog"
            :columns="[
                ['key' => 'title', 'label' => 'Title', 'type' => 'custom', 'view' => 'admin.content.changelog.partials.title-column'],
                ['key' => 'status', 'label' => 'Status', 'type' => 'custom', 'view' => 'admin.content.changelog.partials.status-column'],
                ['key' => 'date', 'label' => 'Date', 'format' => 'date'],
                ['key' => 'is_active', 'label' => 'Active', 'type' => 'toggle'],
                ['key' => 'created_at', 'label' => 'Created', 'format' => 'date'],
            ]"
            route-prefix="admin.content.changelog"
            search-placeholder="Search changelog entries..."
            :paginate="15"
            custom-actions-view="admin.content.changelog.partials.table-actions"
            :search-fields="['title', 'description']"
        />
    </div>
</x-layouts.admin>
