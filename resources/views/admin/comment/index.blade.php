<x-layouts.admin title="{{ __('Comments') }}">
    <div class="space-y-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="mb-1 text-xl font-semibold text-zinc-900 dark:text-white">{{ __('Comments') }}</h1>
                <p class="text-[12.5px] text-zinc-600 dark:text-zinc-400">{{ __('Moderate and manage comments.') }}</p>
            </div>
        </div>

        <livewire:admin.table
            resource="comment"
            :columns="[
                ['key' => 'author', 'label' => 'Author', 'type' => 'custom', 'view' => 'admin.comment.partials.author-column'],
                ['key' => 'body', 'label' => 'Comment', 'type' => 'custom', 'view' => 'admin.comment.partials.comment-body-column'],
                ['key' => 'entity', 'label' => 'In response to', 'type' => 'custom', 'view' => 'admin.comment.partials.entity-column'],
                ['key' => 'is_approved', 'label' => 'Status', 'type' => 'custom', 'view' => 'admin.comment.partials.status-column'],
                ['key' => 'created_at', 'format' => 'date'],
            ]"
            route-prefix="admin.comment"
            search-placeholder="{{ __('Search comments…') }}"
            :paginate="10"
            custom-actions-view="admin.comment.partials.table-actions"
            :search-fields="['body']"
            entity-count-label="{{ __('comments') }}"
            :empty-state-title="__('No comments found')"
        />
    </div>
</x-layouts.admin>
