<x-layouts.admin title="Contacts">
    <div class="space-y-6">
        {{-- Page Header --}}
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-zinc-900 dark:text-white mb-2">Contacts</h1>
                <p class="text-zinc-600 dark:text-zinc-400">Manage all organization contacts</p>
            </div>
            <div class="flex items-center gap-3">
                <x-button variant="primary" icon="plus" icon-position="left" href="{{ route('admin.administrator.contacts.create') }}">Add New Contact</x-button>
            </div>
        </div>

        {{-- Contacts Table --}}
        <livewire:admin.table
            resource="contact"
            :columns="[
                'organization_name',
                'email',
                'phone',
                ['key' => 'type', 'label' => 'Type', 'type' => 'custom', 'view' => 'admin.administrator.contacts.partials.type-column'],
                ['key' => 'created_at', 'format' => 'date'],
                ['key' => 'is_active', 'type' => 'toggle'],
            ]"
            route-prefix="admin.administrator.contacts"
            search-placeholder="Search contacts..."
            :paginate="15"
            custom-actions-view="admin.administrator.contacts.partials.table-actions"
            :search-fields="['organization_name', 'email', 'phone', 'alias']"
        />
    </div>
</x-layouts.admin>
