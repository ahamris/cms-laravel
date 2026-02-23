<x-layouts.admin title="Chapters">
    <div class="space-y-6">
        {{-- Page Header --}}
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-zinc-900 dark:text-white mb-2">Chapters</h1>
                <p class="text-zinc-600 dark:text-zinc-400">Manage chapters per document category</p>
            </div>
            <div class="flex items-center gap-3">
                <a href="{{ route('admin.content.course.create') }}">
                    <x-button variant="primary" icon="plus" icon-position="left">Add Chapter</x-button>
                </a>
            </div>
        </div>

        {{-- Table --}}
        <livewire:admin.table resource="course" :columns="[
            'id',
            ['key' => 'category.name', 'label' => 'Category', 'sortable' => false],
            'name',
            ['key' => 'description', 'type' => 'text', 'limit' => 60],
            ['key' => 'sort_order', 'type' => 'number'],
            ['key' => 'created_at', 'format' => 'date'],
        ]" route-prefix="admin.content.course"
            search-placeholder="Search chapters..." :paginate="15"
            custom-actions-view="admin.course.partials.table-actions"
            :search-fields="['name', 'description', 'category.name']" />
    </div>
</x-layouts.admin>
