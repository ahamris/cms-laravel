<x-layouts.admin title="User Details: {{ $user->name }}">
    <div class="space-y-6">
        {{-- Header --}}
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">User Details</h1>
                <p class="text-gray-600">View and manage user information</p>
            </div>
            <div class="flex space-x-2">
                <a href="{{ route('admin.administrator.users.index') }}" class="text-primary hover:text-primary/80 flex items-center">
                    <i class="fa-solid fa-arrow-left mr-1"></i> Back to Users
                </a>
                @if (auth()->user()->isAdmin())
                    <a href="{{ route('admin.administrator.users.edit', $user) }}"
                        class="bg-primary text-white px-4 py-2 rounded-lg hover:bg-primary/80 transition-colors duration-200 flex items-center space-x-2">
                        <i class="fa-solid fa-edit"></i>
                        <span>Edit User</span>
                    </a>
                @endif
            </div>
        </div>

        {{-- User Profile Card --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            <div class="px-6 py-5 border-b border-gray-200">
                <div class="flex items-center space-x-4">
                    <div class="flex-shrink-0">
                        <img class="h-20 w-20 rounded-full"
                            src="{{ $user->avatar ?? 'https://ui-avatars.com/api/?name=' . urlencode($user->name) . '&color=7F9CF5&background=EBF4FF' }}"
                            alt="{{ $user->name }}">
                    </div>
                    <div>
                        <h2 class="text-xl font-bold text-gray-900">{{ $user->name }}</h2>
                        <div class="mt-1 flex flex-wrap gap-2">
                            @foreach ($user->roles as $role)
                                <span class="px-2 py-0.5 text-xs font-medium bg-blue-100 text-blue-800 rounded-full">
                                    {{ $role->name }}
                                </span>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>

            <div class="px-6 py-5">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    {{-- Account Information --}}
                    <div class="space-y-4">
                        <h3 class="text-lg font-medium text-gray-900 border-b pb-2">Account Information</h3>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Email Address</dt>
                            <dd class="mt-1 text-sm text-gray-900 flex items-center">
                                {{ $user->email }}
                                @if ($user->email_verified_at)
                                    <span
                                        class="ml-2 inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-green-100 text-green-800">
                                        <i class="fa-solid fa-check-circle mr-1"></i> Verified
                                    </span>
                                @else
                                    <span
                                        class="ml-2 inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-yellow-100 text-yellow-800">
                                        <i class="fa-solid fa-exclamation-circle mr-1"></i> Unverified
                                    </span>
                                @endif
                            </dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Member Since</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $user->created_at->format('M d, Y') }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Last Updated</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $user->updated_at->diffForHumans() }}</dd>
                        </div>
                    </div>

                    {{-- Profile Information --}}
                    <div class="space-y-4">
                        <h3 class="text-lg font-medium text-gray-900 border-b pb-2">Personal Information</h3>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">First Name</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $user->name }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Last Name</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $user->last_name ?? 'N/A' }}</dd>
                        </div>
                    </div>

                    {{-- Activity Information --}}
                    <div class="space-y-4">
                        <h3 class="text-lg font-medium text-gray-900 border-b pb-2">Activity</h3>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Last Login</dt>
                            <dd class="mt-1 text-sm text-gray-900">
                                {{ $user->last_login_at ? $user->last_login_at->diffForHumans() : 'Never logged in' }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">IP Address</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $user->last_login_ip ?? 'N/A' }}</dd>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Danger Zone --}}
        @if (auth()->user()->isAdmin() && $user->id !== auth()->id())
            <div class="bg-white rounded-xl shadow-sm border border-red-200 overflow-hidden">
                <div class="px-6 py-5 border-b border-red-200 bg-red-50">
                    <h3 class="text-lg font-medium text-red-800">Danger Zone</h3>
                    <p class="mt-1 text-sm text-red-600">These actions are irreversible. Please be certain.</p>
                </div>
                <div class="px-6 py-5">
                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
                        <div class="max-w-xl">
                            <h4 class="text-base font-medium text-gray-900">Delete User Account</h4>
                            <p class="text-sm text-gray-600">Once you delete this user's account, there is no going back.
                                Please be certain.</p>
                        </div>
                        <button onclick="deleteUser('{{ $user->id }}', '{{ $user->name }}')"
                            class="mt-4 sm:mt-0 inline-flex items-center justify-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                            <i class="fa-solid fa-trash mr-2"></i> Delete Account
                        </button>
                    </div>
                </div>
            </div>
        @endif
    </div>

    {{-- Delete Confirmation Modal --}}
    <div id="deleteModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/50">
        <div class="bg-white rounded-xl p-6 w-full max-w-md mx-4">
            <div class="flex items-center space-x-3 mb-4">
                <div class="flex-shrink-0 w-10 h-10 bg-red-100 rounded-full flex items-center justify-center">
                    <i class="fa-solid fa-exclamation-triangle text-red-600"></i>
                </div>
                <div>
                    <h3 class="text-lg font-medium text-gray-900">Delete User Account</h3>
                    <p class="text-gray-600">Are you sure you want to delete this user's account?</p>
                </div>
            </div>
            <div class="bg-gray-50 rounded-lg p-3 mb-4">
                <p class="text-sm text-gray-700">
                    <strong>User:</strong> <span id="userName"></span>
                </p>
                <p class="text-sm text-gray-700 mt-1">
                    This action cannot be undone. All user data will be permanently removed.
                </p>
            </div>
            <div class="flex space-x-3">
                <button onclick="closeDeleteModal()"
                    class="flex-1 bg-gray-200 text-gray-800 px-4 py-2 rounded-lg hover:bg-gray-300 transition-colors duration-200">
                    Cancel
                </button>
                <form id="deleteForm" method="POST" class="flex-1">
                    @csrf
                    @method('DELETE')
                    <button type="submit"
                        class="w-full bg-red-600 text-white px-4 py-2 rounded-lg hover:bg-red-700 transition-colors duration-200">
                        Delete Account
                    </button>
                </form>
            </div>
        </div>
    </div>

    <script>
        // Delete user
        let deleteForm = document.getElementById('deleteForm');
        let deleteModal = document.getElementById('deleteModal');
        let userName = document.getElementById('userName');

        function deleteUser(userId, name) {
            userName.textContent = name;
            deleteForm.action = `/admin/administrator/users/${userId}`;
            deleteModal.classList.remove('hidden');
            deleteModal.classList.add('flex');
        }

        function closeDeleteModal() {
            deleteModal.classList.add('hidden');
            deleteModal.classList.remove('flex');
        }

        // Close modal when clicking outside
        window.onclick = function(event) {
            if (event.target === deleteModal) {
                closeDeleteModal();
            }
        }
    </script>
</x-layouts.admin>
