<x-layouts.admin title="Presenters">
    <div class="space-y-6">
        {{-- Header --}}
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-zinc-900 dark:text-white mb-2">Presenters</h1>
                <p class="text-zinc-600 dark:text-zinc-400">Manage academy session presenters and speakers</p>
            </div>
            <a href="{{ route('admin.presenter.create') }}">
                <x-button variant="primary" icon="plus" icon-position="left">Add New Presenter</x-button>
            </a>
        </div>

        {{-- Presenters Table --}}
        <livewire:admin.table
            resource="presenter"
            :columns="[
                ['key' => 'name', 'label' => 'Presenter', 'type' => 'custom', 'view' => 'admin.presenter.partials.presenter-column'],
                ['key' => 'title', 'label' => 'Title & Company', 'type' => 'custom', 'view' => 'admin.presenter.partials.title-company-column'],
                ['key' => 'email', 'label' => 'Contact', 'type' => 'custom', 'view' => 'admin.presenter.partials.contact-column'],
                ['key' => 'liveSessions.id', 'label' => 'Sessions', 'type' => 'custom', 'view' => 'admin.presenter.partials.sessions-count-column'],
                ['key' => 'is_active', 'label' => 'Status', 'type' => 'toggle'],
            ]"
            route-prefix="admin.content.presenter"
            search-placeholder="Search presenters..."
            :paginate="15"
            custom-actions-view="admin.presenter.partials.table-actions"
            :search-fields="['name', 'title', 'company', 'email']"
            sort-field="sort_order"
            sort-direction="asc"
        />
    </div>
</x-layouts.admin>
