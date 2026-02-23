<x-layouts.admin title="Comments">
    <div class="space-y-6">
        {{-- Page Header --}}
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-zinc-900 dark:text-white mb-2">Comments</h1>
                <p class="text-zinc-600 dark:text-zinc-400">Manage all comments</p>
            </div>
        </div>

        {{-- Comments Table --}}
        <livewire:admin.table resource="comment" :columns="[
        ['key' => 'author', 'label' => 'Author', 'type' => 'custom', 'view' => 'admin.comment.partials.author-column'],
        ['key' => 'body', 'label' => 'Comment', 'type' => 'custom', 'view' => 'admin.comment.partials.comment-body-column'],
        ['key' => 'entity', 'label' => 'In Response To', 'type' => 'custom', 'view' => 'admin.comment.partials.entity-column'],
        ['key' => 'is_approved', 'label' => 'Status', 'type' => 'custom', 'view' => 'admin.comment.partials.status-column'],
        ['key' => 'created_at', 'format' => 'date'],
    ]"
            route-prefix="admin.comment" search-placeholder="Search comments..." :paginate="10"
            custom-actions-view="admin.comment.partials.table-actions" :search-fields="['body']" />
    </div>
</x-layouts.admin>