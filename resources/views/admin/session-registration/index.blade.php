<x-layouts.admin title="Session Registrations">
    <div class="space-y-6">
        {{-- Header --}}
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-zinc-900 dark:text-white mb-2">Session Registrations</h1>
                <p class="text-zinc-600 dark:text-zinc-400">Manage live session registrations and attendance</p>
            </div>
            <a href="{{ route('admin.session-registration.create') }}">
                <x-button variant="primary" icon="plus" icon-position="left">Add Registration</x-button>
            </a>
        </div>

        {{-- Registrations Table --}}
        <livewire:admin.table
            resource="session-registration"
            :columns="[
                ['key' => 'name', 'label' => 'Participant', 'type' => 'custom', 'view' => 'admin.session-registration.partials.participant-column'],
                ['key' => 'liveSession.title', 'label' => 'Session', 'type' => 'custom', 'view' => 'admin.session-registration.partials.session-column'],
                'organization',
                ['key' => 'registered_at', 'label' => 'Registration Date', 'type' => 'custom', 'view' => 'admin.session-registration.partials.registered-at-column'],
                ['key' => 'status', 'label' => 'Status', 'type' => 'custom', 'view' => 'admin.session-registration.partials.status-column'],
            ]"
            route-prefix="admin.content.session-registration"
            search-placeholder="Search by name, email, or organization..."
            :paginate="15"
            custom-actions-view="admin.session-registration.partials.table-actions"
            :search-fields="['name', 'email', 'organization']"
            sort-field="registered_at"
            sort-direction="desc"
        />
    </div>
</x-layouts.admin>
