<x-layouts.admin title="Edit Permission">
    <div class="space-y-6">
        <!-- Page Header -->
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-zinc-900 dark:text-white mb-2">Edit Permission</h1>
                <p class="text-zinc-600 dark:text-zinc-400">Update permission information and role assignments</p>
            </div>
            <x-button variant="secondary" icon="arrow-left" icon-position="left" href="{{ route('admin.permissions.index') }}">Back to Permissions</x-button>
        </div>

        <!-- Form Card -->
        <div class="bg-white dark:bg-zinc-800 rounded-lg border border-zinc-200 dark:border-zinc-700 shadow-sm">
            <form action="{{ route('admin.permissions.update', $permission) }}" method="POST" class="p-6 space-y-6">
                @csrf
                @method('PUT')

                <!-- Name Field -->
                <div>
                    <x-input 
                        label="Permission Name" 
                        name="name" 
                        type="text" 
                        placeholder="e.g., user_create, post_edit"
                        icon="key"
                        value="{{ old('name', $permission->name) }}"
                        required
                    />
                    @error('name')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Roles Section -->
                <div>
                    <label class="block text-sm font-medium text-zinc-900 dark:text-white mb-3">Assign to Roles</label>
                    <div class="space-y-2">
                        @forelse ($roles as $role)
                            <label class="flex items-center gap-2 cursor-pointer">
                                <input 
                                    type="checkbox" 
                                    name="roles[]" 
                                    value="{{ $role->name }}"
                                    {{ in_array($role->name, old('roles', $permission->roles->pluck('name')->toArray())) ? 'checked' : '' }}
                                    class="rounded border-zinc-300 text-[var(--color-accent)] focus:ring-[var(--color-accent)] dark:border-zinc-600 dark:bg-zinc-700"
                                >
                                <span class="text-sm text-zinc-700 dark:text-zinc-300">{{ $role->name }}</span>
                            </label>
                        @empty
                            <p class="text-sm text-zinc-500 dark:text-zinc-400">No roles available.</p>
                        @endforelse
                    </div>
                    @error('roles')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Form Actions -->
                <div class="flex items-center justify-end gap-3 pt-4 border-t border-zinc-200 dark:border-zinc-700">
                    <x-button variant="secondary" type="button" href="{{ route('admin.permissions.index') }}">Cancel</x-button>
                    <x-button variant="primary" type="submit" icon="save" icon-position="left">Update Permission</x-button>
                </div>
            </form>
        </div>
    </div>
</x-layouts.admin>
