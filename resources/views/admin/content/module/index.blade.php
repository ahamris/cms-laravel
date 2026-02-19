<x-layouts.admin title="Modules">
    <div class="space-y-6">
        {{-- Page Header --}}
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-zinc-900 dark:text-white mb-2">Modules</h1>
                <p class="text-zinc-600 dark:text-zinc-400">Manage module sections with anchor navigation</p>
            </div>
            <div class="flex items-center gap-3">
                <x-button variant="primary" icon="plus" icon-position="left" href="{{ route('admin.content.module.create') }}">Create New Module</x-button>
            </div>
        </div>

        {{-- Modules Table --}}
        <livewire:admin.table
            resource="module"
            :columns="[
                ['key' => 'sort_order', 'label' => 'Order'],
                ['key' => 'anchor', 'label' => 'Anchor / Nav Title', 'type' => 'custom', 'view' => 'admin.content.module.partials.anchor-column'],
                ['key' => 'title', 'limit' => 50],
                ['key' => 'image_position', 'label' => 'Image Position', 'type' => 'custom', 'view' => 'admin.content.module.partials.image-position-column'],
                ['key' => 'is_active', 'type' => 'toggle'],
            ]"
            route-prefix="admin.content.module"
            search-placeholder="Search modules..."
            :paginate="15"
            custom-actions-view="admin.content.module.partials.table-actions"
            :search-fields="['title', 'nav_title', 'anchor', 'slug']"
        />
    </div>
</x-layouts.admin>
