<x-layouts.admin title="Pages">
    <div class="space-y-6">
        {{-- Page Header --}}
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-zinc-900 dark:text-white mb-2">Pages</h1>
                <p class="text-zinc-600 dark:text-zinc-400">Manage website pages and content</p>
            </div>
            <div class="flex items-center gap-3">
                <x-button variant="primary" icon="plus" icon-position="left" href="{{ route('admin.content.page.create') }}">
                    Add Page
                </x-button>
            </div>
        </div>

        {{-- Pages Table --}}
        <livewire:admin.table
            resource="page"
            :columns="[
                ['key' => 'image', 'label' => 'Image', 'type' => 'custom', 'view' => 'admin.page.partials.image-column'],
                ['key' => 'title', 'label' => 'Title', 'type' => 'custom', 'view' => 'admin.page.partials.title-column'],
                ['key' => 'slug', 'label' => 'Slug', 'type' => 'custom', 'view' => 'admin.page.partials.slug-column'],
                ['key' => 'created_at', 'label' => 'Created', 'format' => 'date'],
                ['key' => 'is_active', 'label' => 'Status', 'type' => 'toggle'],
            ]"
            route-prefix="admin.content.page"
            search-placeholder="Search pages..."
            :paginate="15"
            custom-actions-view="admin.page.partials.table-actions"
            :search-fields="['title', 'slug']"
        />
    </div>

</x-layouts.admin>
