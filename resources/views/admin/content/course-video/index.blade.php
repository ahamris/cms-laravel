<x-layouts.admin title="Document Videos">
    <div class="space-y-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-zinc-900 dark:text-white mb-2">Document Videos</h1>
                <p class="text-zinc-600 dark:text-zinc-400">Manage training videos by category</p>
            </div>
            <a href="{{ route('admin.content.course-video.create') }}">
                <x-button variant="primary" icon="plus" icon-position="left">Add Video</x-button>
            </a>
        </div>

        <livewire:admin.table
            resource="course-video"
            :columns="[
                'id',
                ['key' => 'category.name', 'label' => 'Category'],
                'title',
                'slug',
                ['key' => 'duration_seconds', 'label' => 'Duration', 'type' => 'custom', 'view' => 'admin.content.course-video.partials.duration-cell'],
                ['key' => 'is_active', 'type' => 'toggle'],
                ['key' => 'created_at', 'format' => 'date'],
            ]"
            route-prefix="admin.content.course-video"
            search-placeholder="Search videos..."
            :paginate="15"
            custom-actions-view="admin.content.course-video.partials.table-actions"
            :search-fields="['title', 'slug', 'description']"
        />
    </div>
</x-layouts.admin>
