<x-layouts.admin title="Solutions">
    <div class="space-y-6">
        {{-- Page Header --}}
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-zinc-900 dark:text-white mb-2">Solutions</h1>
                <p class="text-zinc-600 dark:text-zinc-400">Manage solution sections with anchor navigation</p>
            </div>
            <div class="flex items-center gap-3">
                <x-button variant="primary" icon="plus" icon-position="left" href="{{ route('admin.solution.create') }}">Create New Solution</x-button>
            </div>
        </div>

        {{-- Solutions Table --}}
        <livewire:admin.table
            resource="solution"
            :columns="[
                ['key' => 'sort_order', 'label' => 'Order'],
                ['key' => 'anchor', 'label' => 'Anchor / Nav Title', 'type' => 'custom', 'view' => 'admin.solution.partials.anchor-column'],
                ['key' => 'title', 'limit' => 50],
                ['key' => 'image', 'type' => 'custom', 'view' => 'admin.solution.partials.image-column'],
                ['key' => 'image_position', 'label' => 'Image Position', 'type' => 'custom', 'view' => 'admin.solution.partials.image-position-column'],
                ['key' => 'is_active', 'type' => 'toggle'],
            ]"
            route-prefix="admin.solution"
            search-placeholder="Search solutions..."
            :paginate="15"
            custom-actions-view="admin.solution.partials.table-actions"
            :search-fields="['title', 'nav_title', 'anchor', 'slug']"
        />
    </div>
</x-layouts.admin>
