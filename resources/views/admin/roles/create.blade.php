<x-layouts.admin title="Create Role">
    <div class="space-y-6">
        <!-- Page Header -->
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-zinc-900 dark:text-white mb-2">Create Role</h1>
                <p class="text-zinc-600 dark:text-zinc-400">Create a new role and assign permissions</p>
            </div>
            <x-button variant="secondary" icon="arrow-left" icon-position="left" href="{{ route('admin.roles.index') }}">Back to Roles</x-button>
        </div>

        <!-- Form Card -->
        <div class="bg-white dark:bg-zinc-800 rounded-lg border border-zinc-200 dark:border-zinc-700 shadow-sm">
            <form action="{{ route('admin.roles.store') }}" method="POST" class="p-6 space-y-6">
                @csrf

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
                        value="{{ old('name') }}"
                        required
                    />
                    @error('name')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Permissions Section -->
                <div>
                    <label class="block text-sm font-medium text-zinc-900 dark:text-white mb-3">Permissions</label>
                    <div class="space-y-4">
                        @foreach ($permissions as $module => $modulePermissions)
                            <div class="border border-zinc-200 dark:border-zinc-700 rounded-lg p-4">
                                <h4 class="text-sm font-semibold text-zinc-900 dark:text-white mb-3 capitalize">{{ $module }}</h4>
                                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-3">
                                    @foreach ($modulePermissions as $permission)
                                        <label class="flex items-center gap-2 cursor-pointer">
                                            <input 
                                                type="checkbox" 
                                                name="permissions[]" 
                                                value="{{ $permission->name }}"
                                                {{ in_array($permission->name, old('permissions', [])) ? 'checked' : '' }}
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
                    <x-button variant="primary" type="submit" icon="save" icon-position="left">Create Role</x-button>
                </div>
            </form>
        </div>
    </div>
</x-layouts.admin>
