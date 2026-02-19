<x-layouts.admin title="Document Categories">
    <div class="space-y-6">
        {{-- Page Header --}}
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-zinc-900 dark:text-white mb-2">Document Categories</h1>
                <p class="text-zinc-600 dark:text-zinc-400">Manage video categories for the academy</p>
            </div>
            <div class="flex items-center gap-3">
                <a href="{{ route('admin.content.academy-category.create') }}">
                    <x-button variant="primary" icon="plus" icon-position="left">Add Category</x-button>
                </a>
            </div>
        </div>

        {{-- Table --}}
        <livewire:admin.table resource="academy-category" :columns="[
        'id',
        'name',
        'slug',
        ['key' => 'description', 'type' => 'text', 'limit' => 60],
        ['key' => 'sort_order', 'type' => 'number'],
        ['key' => 'is_active', 'type' => 'toggle'],
        ['key' => 'created_at', 'format' => 'date'],
    ]" route-prefix="admin.content.academy-category"
            search-placeholder="Search categories..." :paginate="15"
            custom-actions-view="admin.content.academy-category.partials.table-actions"
            :search-fields="['name', 'slug', 'description']" />
    </div>
</x-layouts.admin>