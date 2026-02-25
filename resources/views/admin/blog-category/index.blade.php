<x-layouts.admin title="Blog Categories">
    <div class="space-y-6">
        {{-- Page Header --}}
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-zinc-900 dark:text-white mb-2">Blog Categories</h1>
                <p class="text-zinc-600 dark:text-zinc-400">Manage all blog categories in your system</p>
            </div>
            <div class="flex items-center gap-3">
                <a href="{{ route('admin.blog-category.create') }}"
                    class="inline-flex items-center gap-2 rounded-md bg-[var(--color-accent)] px-4 py-2 text-sm font-semibold text-white shadow-xs hover:opacity-90 transition-opacity focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-[var(--color-accent)]">
                    <i class="fa-solid fa-plus"></i>
                    Add New Category
                </a>
            </div>
        </div>

        {{-- Blog Categories Table --}}
        <livewire:admin.table resource="blog-category" :columns="[
            'id',
            'name',
            'slug',
            ['key' => 'description', 'type' => 'text', 'limit' => 60],
            ['key' => 'color', 'type' => 'color'],
            ['key' => 'is_active', 'type' => 'toggle'],
            ['key' => 'created_at', 'format' => 'date'],
        ]" route-prefix="admin.blog-category"
            search-placeholder="Search blog categories..." :paginate="15"
            custom-actions-view="admin.blog-category.partials.table-actions"
            :search-fields="['name', 'slug', 'description']" />
    </div>
</x-layouts.admin>
