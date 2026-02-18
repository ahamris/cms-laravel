<x-layouts.admin title="Roles">
    <div class="space-y-6">
        <!-- Page Header -->
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-zinc-900 dark:text-white mb-2">Roles</h1>
                <p class="text-zinc-600 dark:text-zinc-400">Manage user roles and their permissions</p>
            </div>
            <x-button variant="primary" icon="plus" icon-position="left" href="{{ route('admin.roles.create') }}">Add New Role</x-button>
        </div>

        @if (session('success'))
            <div class="rounded-md bg-green-50 dark:bg-green-900/20 p-4">
                <p class="text-sm text-green-800 dark:text-green-200">{{ session('success') }}</p>
            </div>
        @endif

        @if (session('error'))
            <div class="rounded-md bg-red-50 dark:bg-red-900/20 p-4">
                <p class="text-sm text-red-800 dark:text-red-200">{{ session('error') }}</p>
            </div>
        @endif

        <!-- Roles Table -->
        <livewire:admin.table
            resource="roles"
            :columns="[
                'id',
                'name',
                ['key' => 'permissions_count', 'label' => 'Permissions', 'sortable' => false],
                ['key' => 'users_count', 'label' => 'Users', 'sortable' => false],
                ['key' => 'created_at', 'format' => 'date'],
            ]"
            :search-fields="['name']"
            :sortable-fields="['id', 'name', 'created_at']"
            route-prefix="admin.roles"
            search-placeholder="Search roles..."
            :paginate="15"
            :show-checkbox="false"
            :show-bulk-delete="false"
            :actions="['edit', 'delete']"
        />
    </div>
</x-layouts.admin>
