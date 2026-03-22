<x-layouts.admin title="Article Categories">
    <div class="space-y-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="font-bold text-zinc-900 dark:text-white mb-2">Article Categories</h1>
                <p class="text-zinc-600 dark:text-zinc-400">Manage article categories and hierarchy</p>
            </div>
            <div class="flex items-center gap-3">
                <a href="{{ route('admin.article-category.create') }}"
                    class="inline-flex items-center gap-2 rounded-md bg-[var(--color-accent)] px-4 py-2 font-semibold text-white shadow-xs hover:opacity-90 transition-opacity">
                    <i class="fa-solid fa-plus"></i>
                    Add Category
                </a>
            </div>
        </div>

        <livewire:admin.table resource="article-category" :columns="[
            'id',
            'name',
            'slug',
            ['key' => 'color', 'type' => 'color'],
            ['key' => 'icon', 'label' => 'Icon'],
            ['key' => 'is_active', 'type' => 'toggle'],
            ['key' => 'sort_order', 'label' => 'Order'],
            ['key' => 'created_at', 'format' => 'date'],
        ]" route-prefix="admin.article-category"
            search-placeholder="Search categories..." :paginate="15"
            :search-fields="['name', 'slug', 'description']" />
    </div>
</x-layouts.admin>
