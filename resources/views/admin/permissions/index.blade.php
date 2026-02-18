<x-layouts.admin title="Permissions">
    <div class="space-y-6">
        <!-- Page Header -->
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-zinc-900 dark:text-white mb-2">Permissions</h1>
                <p class="text-zinc-600 dark:text-zinc-400">Manage system permissions</p>
            </div>
            <x-button variant="primary" icon="plus" icon-position="left" href="{{ route('admin.permissions.create') }}">Add New Permission</x-button>
        </div>

        @if (session('success'))
            <div class="rounded-md bg-green-50 dark:bg-green-900/20 p-4">
                <p class="text-sm text-green-800 dark:text-green-200">{{ session('success') }}</p>
            </div>
        @endif

        <!-- Permissions Table -->
        <livewire:admin.table
            resource="permissions"
            :columns="[
                'id',
                'name',
                ['key' => 'roles_count', 'label' => 'Roles', 'sortable' => false],
                ['key' => 'created_at', 'format' => 'date'],
            ]"
            :search-fields="['name']"
            :sortable-fields="['id', 'name', 'created_at']"
            route-prefix="admin.permissions"
            search-placeholder="Search permissions..."
            :paginate="15"
            :show-checkbox="false"
            :show-bulk-delete="false"
            :actions="['edit', 'delete']"
        />
    </div>
</x-layouts.admin>
