<x-layouts.admin title="Live Sessions">
    <div class="space-y-6">
        {{-- Header --}}
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-zinc-900 dark:text-white mb-2">Live Sessions</h1>
                <p class="text-zinc-600 dark:text-zinc-400">Manage academy live sessions and webinars</p>
            </div>
            <a href="{{ route('admin.content.live-session.create') }}">
                <x-button variant="primary" icon="plus" icon-position="left">Create New Session</x-button>
            </a>
        </div>

        {{-- Live Sessions Table --}}
        <livewire:admin.table
            resource="live-session"
            :columns="[
                ['key' => 'title', 'label' => 'Title', 'type' => 'custom', 'view' => 'admin.content.live-session.partials.title-column'],
                ['key' => 'session_date', 'label' => 'Date & Time', 'type' => 'custom', 'view' => 'admin.content.live-session.partials.date-time-column'],
                ['key' => 'duration_minutes', 'label' => 'Duration', 'type' => 'custom', 'view' => 'admin.content.live-session.partials.duration-column'],
                ['key' => 'presenters.id', 'label' => 'Presenters', 'type' => 'custom', 'view' => 'admin.content.live-session.partials.presenters-column'],
                ['key' => 'id', 'label' => 'Registrations', 'type' => 'custom', 'view' => 'admin.content.live-session.partials.registrations-column'],
                ['key' => 'status', 'label' => 'Status', 'type' => 'custom', 'view' => 'admin.content.live-session.partials.status-column'],
                ['key' => 'is_active', 'label' => 'Active', 'type' => 'toggle'],
            ]"
            route-prefix="admin.content.live-session"
            search-placeholder="Search sessions..."
            :paginate="15"
            custom-actions-view="admin.content.live-session.partials.table-actions"
            :search-fields="['title', 'description']"
            sort-field="session_date"
            sort-direction="asc"
        />
    </div>
</x-layouts.admin>
