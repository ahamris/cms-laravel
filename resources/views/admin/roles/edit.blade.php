<x-layouts.admin title="Edit Role">
    <div class="space-y-6">
        <!-- Page Header -->
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-zinc-900 dark:text-white mb-2">Edit Role</h1>
                <p class="text-zinc-600 dark:text-zinc-400">Update role information and permissions</p>
            </div>
            <x-button variant="secondary" icon="arrow-left" icon-position="left" href="{{ route('admin.roles.index') }}">Back to Roles</x-button>
        </div>

        <!-- Form Card -->
        <div class="bg-white dark:bg-zinc-800 rounded-lg border border-zinc-200 dark:border-zinc-700 shadow-sm">
            <form action="{{ route('admin.roles.update', $role) }}" method="POST" class="p-6 space-y-6">
                @csrf
                @method('PUT')

                @if (session('error'))
                    <div class="rounded-md bg-red-50 dark:bg-red-900/20 p-4">
                        <p class="text-sm text-red-800 dark:text-red-200">{{ session('error') }}</p>
                    </div>
                @endif

                <!-- Name Field -->
                <div>
                    <x-input 
                        label="Role Name" 
                        name="name" 
                        type="text" 
                        placeholder="e.g., editor, manager"
                        icon="user-tag"
                        value="{{ old('name', $role->name) }}"
                        required
                        {{ $role->name === 'admin' ? 'readonly' : '' }}
                    />
                    @if ($role->name === 'admin')
                        <p class="mt-1 text-sm text-zinc-500 dark:text-zinc-400">Admin role name cannot be changed.</p>
                    @endif
                    @error('name')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Permissions Section -->
                <div>
                    <label class="block text-sm font-medium text-zinc-900 dark:text-white mb-3">Permissions</label>
                    @if ($role->name === 'admin')
                        <div class="rounded-md bg-yellow-50 dark:bg-yellow-900/20 p-4 mb-4">
                            <p class="text-sm text-yellow-800 dark:text-yellow-200">Admin role permissions cannot be modified.</p>
                        </div>
                    @endif
                    <div class="space-y-4">
                        @foreach ($permissions as $module => $modulePermissions)
                            <div class="border border-zinc-200 dark:border-zinc-700 rounded-lg p-4">
                                <h4 class="text-sm font-semibold text-zinc-900 dark:text-white mb-3 capitalize">{{ $module }}</h4>
                                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-3">
                                    @foreach ($modulePermissions as $permission)
                                        <label class="flex items-center gap-2 cursor-pointer {{ $role->name === 'admin' ? 'opacity-50 cursor-not-allowed' : '' }}">
                                            <input 
                                                type="checkbox" 
                                                name="permissions[]" 
                                                value="{{ $permission->name }}"
                                                {{ in_array($permission->name, old('permissions', $role->permissions->pluck('name')->toArray())) ? 'checked' : '' }}
                                                {{ $role->name === 'admin' ? 'disabled' : '' }}
                                                class="rounded border-zinc-300 text-[var(--color-accent)] focus:ring-[var(--color-accent)] dark:border-zinc-600 dark:bg-zinc-700"
                                            >
                                            <span class="text-sm text-zinc-700 dark:text-zinc-300">{{ $permission->name }}</span>
                                        </label>
                                    @endforeach
                                </div>
                            </div>
                        @endforeach
                    </div>
                    @error('permissions')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Form Actions -->
                <div class="flex items-center justify-end gap-3 pt-4 border-t border-zinc-200 dark:border-zinc-700">
                    <x-button variant="secondary" type="button" href="{{ route('admin.roles.index') }}">Cancel</x-button>
                    <x-button variant="primary" type="submit" icon="save" icon-position="left">Update Role</x-button>
                </div>
            </form>
        </div>
    </div>
</x-layouts.admin>
