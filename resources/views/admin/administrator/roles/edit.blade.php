<x-layouts.admin title="Edit Role: {{ $role->name }}">
    <div class="space-y-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Edit Role: {{ $role->name }}</h1>
                <p class="text-gray-600">Update role details and permissions</p>
            </div>
            <a href="{{ route('admin.administrator.roles.index') }}"
                class="px-4 py-2 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-50">
                Back to Roles
            </a>
        </div>

        {{-- Success/Error Messages --}}
        @if (session('success'))
            <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg">
                <i class="fa-solid fa-check-circle mr-2"></i>
                {{ session('success') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg">
                <i class="fa-solid fa-exclamation-circle mr-2"></i>
                <ul class="list-disc list-inside">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <form action="{{ route('admin.administrator.roles.update', $role) }}" method="POST">
                @csrf
                @method('PUT')

                {{-- Role Name --}}
                <div class="mb-6">
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-1">
                        Role Name <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="name" id="name" value="{{ old('name', $role->name) }}"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary"
                        placeholder="e.g., editor, manager" required>
                    <p class="mt-1 text-sm text-gray-500">
                        Use lowercase letters, numbers, and underscores only (e.g., content_editor)
                    </p>
                </div>

                {{-- Permissions --}}
                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Permissions
                    </label>

                    @if ($permissions->count() > 0)
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                            @foreach ($permissions as $permission)
                                <div class="flex items-center">
                                    <input id="permission-{{ $permission->id }}" name="permissions[]" type="checkbox"
                                        value="{{ $permission->name }}"
                                        class="h-4 w-4 text-primary focus:ring-primary border-gray-300 rounded"
                                        {{ in_array($permission->name, old('permissions', $role->permissions->pluck('name')->toArray())) ? 'checked' : '' }}>
                                    <label for="permission-{{ $permission->id }}" class="ml-2 text-sm text-gray-700">
                                        {{ $permission->name }}
                                    </label>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-4">
                            <i class="fa-solid fa-exclamation-triangle text-yellow-500 text-xl mb-2"></i>
                            <p class="text-gray-600">No permissions found. Please create permissions first.</p>
                        </div>
                    @endif
                </div>

                <div class="flex justify-end space-x-3 pt-4 border-t border-gray-200">
                    <a href="{{ route('admin.administrator.roles.index') }}"
                        class="px-4 py-2 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-50">
                        Cancel
                    </a>
                    <button type="submit"
                        class="px-4 py-2 border border-transparent rounded-lg text-sm font-medium text-white bg-primary hover:bg-primary/90 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary cursor-pointer">
                        Update Role
                    </button>
                </div>
            </form>         

            {{-- Delete Form --}}
            @if ($role->name !== 'super-admin' && !auth()->user()->roles->pluck('name')->contains($role->name))
                <div class="mt-8 pt-6 border-t border-gray-200">
                    <div class="bg-red-50 border-l-4 border-red-400 p-4">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <i class="fa-solid fa-exclamation-triangle text-red-400"></i>
                            </div>
                            <div class="ml-3">
                                <h3 class="text-sm font-medium text-red-800">Danger Zone</h3>
                                <div class="mt-2 text-sm text-red-700">
                                    <p>Once you delete a role, all of its data will be permanently removed. This action
                                        cannot be undone.</p>
                                </div>
                                <div class="mt-4">
                                    <form action="{{ route('admin.administrator.roles.destroy', $role) }}" method="POST"
                                        class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                            class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500"
                                            onclick="return confirm('Are you sure you want to delete this role? This action cannot be undone.')">
                                            <i class="fa-solid fa-trash mr-2"></i> Delete Role
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @elseif($role->name !== 'super-admin')
                <div class="mt-8 pt-6 border-t border-gray-200">
                    <div class="bg-gray-50 border-l-4 border-gray-400 p-4">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <i class="fa-solid fa-info-circle text-gray-400"></i>
                            </div>
                            <div class="ml-3">
                                <h3 class="text-sm font-medium text-gray-800">Role Management</h3>
                                <div class="mt-2 text-sm text-gray-700">
                                    <p>You cannot delete your own role. Please contact an administrator for assistance.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>

    <script>
        // Initialize any JS components if needed
        document.addEventListener('DOMContentLoaded', function() {
            // Add any initialization code here
        });
    </script>
    </script>
</x-layouts.admin>
