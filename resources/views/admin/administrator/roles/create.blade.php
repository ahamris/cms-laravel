<x-layouts.admin title="Create New Role">
    <div class="space-y-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Create New Role</h1>
                <p class="text-gray-600">Add a new role and assign permissions</p>
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
            <form action="{{ route('admin.administrator.roles.store') }}" method="POST">
                @csrf

                {{-- Role Name --}}
                <div class="mb-6">
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-1">
                        Role Name <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="name" id="name" value="{{ old('name') }}"
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
                                        {{ in_array($permission->name, old('permissions', [])) ? 'checked' : '' }}>
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
                        Create Role
                    </button>
                </div>
            </form>
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
