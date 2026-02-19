@php use App\Helpers\Variable; @endphp
<x-layouts.admin title="Users">
    <div class="space-y-6">
        {{-- Header --}}
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Users</h1>
                <p class="text-gray-600">Manage system users and their permissions</p>
            </div>
            <div class="flex items-center space-x-3">
                {{-- Toggle Admin/Non-Admin Users --}}
                <div class="flex bg-gray-100 rounded-lg p-1">
                    <a href="{{ route('admin.administrator.users.index') }}" 
                       class="px-4 py-2 rounded-md text-sm font-medium transition-colors duration-200 {{ !$showNonAdmins ? 'bg-white text-primary shadow-sm' : 'text-gray-600 hover:text-gray-900' }}">
                        Admin Users
                    </a>
                    <a href="{{ route('admin.administrator.users.index', ['show_non_admins' => true]) }}" 
                       class="px-4 py-2 rounded-md text-sm font-medium transition-colors duration-200 {{ $showNonAdmins ? 'bg-white text-primary shadow-sm' : 'text-gray-600 hover:text-gray-900' }}">
                        Non-Admin Users
                    </a>
                </div>
                
                @if(Variable::hasPermission('user_create'))
                <a href="{{ route('admin.administrator.users.create') }}"
                    class="bg-primary text-white px-4 py-2 rounded-lg hover:bg-primary/80 transition-colors duration-200 flex items-center space-x-2">
                    <i class="fa-solid fa-plus"></i>
                    <span>Add User</span>
                </a>
                @endif
            </div>
        </div>

        {{-- Success/Error Messages --}}
        @if (session('success'))
            <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg">
                <i class="fa-solid fa-check-circle mr-2"></i>
                {{ session('success') }}
            </div>
        @endif

        @if (session('error'))
            <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg">
                <i class="fa-solid fa-exclamation-circle mr-2"></i>
                {{ session('error') }}
            </div>
        @endif

        {{-- Users Table --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-200" id="usersTableContainer">
            @include('admin.administrator.users.partials.users_table', ['users' => $users, 'roles' => $roles, 'permissions' => $permissions, 'showNonAdmins' => $showNonAdmins])
        </div>

        {{-- Bulk Admin Assignment for Non-Admin Users --}}
        @if($showNonAdmins)
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-gray-900">Bulk Admin Assignment</h3>
                <span class="text-sm text-gray-500">Select users to assign admin role</span>
            </div>
            
            {{-- Search for Non-Admin Users --}}
            <div class="mb-4">
                <label for="userSearch" class="block text-sm font-medium text-gray-700 mb-2">Search Users by Email</label>
                <div class="relative">
                    <input type="text" 
                           id="userSearch" 
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary"
                           placeholder="Type email to search...">
                    <div class="absolute inset-y-0 right-0 flex items-center pr-3">
                        <i class="fa-solid fa-search text-gray-400"></i>
                    </div>
                </div>
                <div id="searchResults" class="mt-2 hidden max-h-60 overflow-y-auto border border-gray-200 rounded-lg bg-white shadow-lg"></div>
            </div>

            {{-- Selected Users --}}
            <div id="selectedUsers" class="mb-4 hidden">
                <label class="block text-sm font-medium text-gray-700 mb-2">Selected Users</label>
                <div id="selectedUsersList" class="space-y-2"></div>
            </div>

            {{-- Assign Admin Role Button --}}
            <div class="flex justify-end">
                <button id="assignAdminBtn" 
                        class="bg-primary text-white px-6 py-2 rounded-lg hover:bg-primary/80 transition-colors duration-200 disabled:opacity-50 disabled:cursor-not-allowed"
                        disabled>
                    <i class="fa-solid fa-user-shield mr-2"></i>
                    Assign Admin Role
                </button>
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
                    <h3 class="text-lg font-medium text-gray-900">Delete User</h3>
                    <p class="text-gray-600">Are you sure you want to delete this user?</p>
                </div>
            </div>
            <div class="bg-gray-50 rounded-lg p-3 mb-4">
                <p class="text-sm text-gray-700">
                    <strong>User:</strong> <span id="userName"></span>
                </p>
                <p class="text-sm text-gray-700 mt-1">
                    This action cannot be undone.
                </p>
            </div>
            <div class="flex space-x-3">
                <button onclick="closeModal()"
                    class="flex-1 bg-gray-200 text-gray-800 px-4 py-2 rounded-lg hover:bg-gray-300 transition-colors duration-200">
                    Cancel
                </button>
                <form id="deleteForm" method="POST" class="flex-1">
                    @csrf
                    @method('DELETE')
                    <button type="submit"
                        class="w-full bg-red-600 text-white px-4 py-2 rounded-lg hover:bg-red-700 transition-colors duration-200">
                        Delete
                    </button>
                </form>
            </div>
        </div>
    </div>

    <script>
        // Display session messages
        @if (session('success'))
            toastr.success('{{ session('success') }}');
        @endif

        @if (session('error'))
            toastr.error('{{ session('error') }}');
        @endif

        // Function to update the URL with current filters
        function updateUrl() {
            const params = new URLSearchParams(window.location.search);
            const role = document.getElementById('roleFilter').value;
            const permission = document.getElementById('permissionFilter').value;
            const perPage = document.getElementById('pageLength').value;
            
            if (role) params.set('role', role);
            else params.delete('role');
            
            if (permission) params.set('permissions', permission);
            else params.delete('permissions');
            
            params.set('per_page', perPage);
            
            const newUrl = `${window.location.pathname}?${params.toString()}`;
            window.history.pushState({ path: newUrl }, '', newUrl);
        }

        // Function to load users with current filters
        function loadUsers() {
            const container = document.getElementById('usersTableContainer');
            const role = document.getElementById('roleFilter').value;
            const permission = document.getElementById('permissionFilter').value;
            const perPage = document.getElementById('pageLength').value;
            
            // Show loading state
            container.innerHTML = `
                <div class="flex justify-center items-center p-12">
                    <div class="animate-spin rounded-full h-12 w-12 border-t-2 border-b-2 border-primary"></div>
                    <span class="ml-3 text-gray-600">Loading users...</span>
                </div>`;
            
            // Build query string
            const params = new URLSearchParams();
            if (role) params.append('role', role);
            if (permission) params.append('permissions', permission);
            if (perPage) params.append('per_page', perPage);
            
            // Add AJAX parameter
            params.append('ajax', '1');
            
            // Make AJAX request with headers
            fetch(`{{ route('admin.administrator.users.index') }}?${params.toString()}`, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                }
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json();
            })
            .then(data => {
                if (data && data.html) {
                    container.innerHTML = data.html;
                    // Re-attach event listeners
                    attachEventListeners();
                    
                    // Update pagination if available
                    if (data.pagination) {
                        const paginationContainer = container.querySelector('.pagination');
                        if (paginationContainer) {
                            paginationContainer.outerHTML = data.pagination;
                        }
                    }
                } else {
                    throw new Error('Invalid response format');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                container.innerHTML = `
                    <div class="p-6 text-center text-red-600">
                        <i class="fa-solid fa-exclamation-circle text-2xl mb-2"></i>
                        <p>An error occurred while loading users. Please refresh the page and try again.</p>
                        <button onclick="window.location.reload()" class="mt-2 text-primary hover:underline">
                            <i class="fa-solid fa-rotate-right mr-1"></i> Refresh Page
                        </button>
                    </div>`;
            });
        }
        
        // Function to attach event listeners to filter elements
        function attachEventListeners() {
            // Role filter change
            const roleFilter = document.getElementById('roleFilter');
            if (roleFilter) {
                roleFilter.addEventListener('change', function() {
                    updateUrl();
                    loadUsers();
                });
            }
            
            // Permission filter change
            const permissionFilter = document.getElementById('permissionFilter');
            if (permissionFilter) {
                permissionFilter.addEventListener('change', function() {
                    updateUrl();
                    loadUsers();
                });
            }
            
            // Page length change
            const pageLength = document.getElementById('pageLength');
            if (pageLength) {
                pageLength.addEventListener('change', function() {
                    updateUrl();
                    loadUsers();
                });
            }
            
            // Reset filters button
            const resetFilters = document.getElementById('resetFilters');
            if (resetFilters) {
                resetFilters.addEventListener('click', function(e) {
                    e.preventDefault();
                    
                    // Reset all filter inputs
                    const roleFilter = document.getElementById('roleFilter');
                    const permissionFilter = document.getElementById('permissionFilter');
                    const pageLength = document.getElementById('pageLength');
                    
                    // Reset to default values
                    if (roleFilter) roleFilter.selectedIndex = 0;
                    if (permissionFilter) permissionFilter.selectedIndex = 0;
                    if (pageLength) pageLength.value = '10';
                    
                    // Clear search input if exists
                    const searchInput = document.getElementById('searchInput');
                    if (searchInput) searchInput.value = '';
                    
                    // Reset URL to base path and reload the page to ensure clean state
                    const baseUrl = window.location.pathname;
                    window.location.href = baseUrl;
                });
            }
        }
        
        // Initialize when DOM is loaded
        document.addEventListener('DOMContentLoaded', function() {
            // Attach event listeners
            attachEventListeners();
            
            // Initialize filters from URL
            const urlParams = new URLSearchParams(window.location.search);
            const role = urlParams.get('role');
            const permission = urlParams.get('permissions');
            const perPage = urlParams.get('per_page');
            
            if (role && document.getElementById('roleFilter')) {
                document.getElementById('roleFilter').value = role;
            }
            
            if (permission && document.getElementById('permissionFilter')) {
                document.getElementById('permissionFilter').value = permission;
            }
            
            if (perPage && document.getElementById('pageLength')) {
                document.getElementById('pageLength').value = perPage;
            }
        });
        
        // Handle back/forward navigation
        window.onpopstate = function() {
            loadUsers();
        };

        function deleteUser(userId, userName) {
            // Prevent deletion of super admin (ID: 1)
            if (userId === 1) {
                toastr.error('Cannot delete the super admin account (ID: 1)');
                return;
            }
            
            document.getElementById('userName').textContent = userName;
            document.getElementById('deleteForm').action = `/admin/administrator/users/${userId}`;
            document.getElementById('deleteModal').classList.remove('hidden');
            document.body.classList.add('overflow-hidden');
        }

        function closeModal() {
            document.getElementById('deleteModal').classList.add('hidden');
            document.body.classList.remove('overflow-hidden');
        }

        // Close modal when clicking outside
        window.onclick = function(event) {
            const modal = document.getElementById('deleteModal');
            if (event.target === modal) {
                closeModal();
            }
        };

        // Bulk Admin Assignment functionality
        let selectedUsers = [];
        let searchTimeout;

        // Search for non-admin users
        document.addEventListener('DOMContentLoaded', function() {
            const userSearch = document.getElementById('userSearch');
            if (userSearch) {
                userSearch.addEventListener('input', function() {
                    clearTimeout(searchTimeout);
                    const query = this.value.trim();
                    
                    if (query.length < 2) {
                        hideSearchResults();
                        return;
                    }
                    
                    searchTimeout = setTimeout(() => {
                        searchUsers(query);
                    }, 300);
                });
            }

            // Assign admin role button
            const assignAdminBtn = document.getElementById('assignAdminBtn');
            if (assignAdminBtn) {
                assignAdminBtn.addEventListener('click', function() {
                    assignAdminRole();
                });
            }
        });

        function searchUsers(query) {
            fetch(`{{ route('admin.administrator.users.search-non-admins') }}?search=${encodeURIComponent(query)}`, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                displaySearchResults(data.users);
            })
            .catch(error => {
                console.error('Search error:', error);
                hideSearchResults();
            });
        }

        function displaySearchResults(users) {
            const resultsContainer = document.getElementById('searchResults');
            if (!resultsContainer) return;

            if (users.length === 0) {
                resultsContainer.innerHTML = '<div class="p-3 text-gray-500 text-center">No users found</div>';
            } else {
                resultsContainer.innerHTML = users.map(user => `
                    <div class="p-3 hover:bg-gray-50 cursor-pointer border-b border-gray-100 last:border-b-0" 
                         onclick="selectUser(${user.id}, '${user.name}', '${user.email}')">
                        <div class="font-medium text-gray-900">${user.name}</div>
                        <div class="text-sm text-gray-500">${user.email}</div>
                    </div>
                `).join('');
            }
            
            resultsContainer.classList.remove('hidden');
        }

        function hideSearchResults() {
            const resultsContainer = document.getElementById('searchResults');
            if (resultsContainer) {
                resultsContainer.classList.add('hidden');
            }
        }

        function selectUser(userId, userName, userEmail) {
            // Check if user is already selected
            if (selectedUsers.find(user => user.id === userId)) {
                return;
            }

            selectedUsers.push({ id: userId, name: userName, email: userEmail });
            updateSelectedUsersList();
            hideSearchResults();
            
            // Clear search input
            const userSearch = document.getElementById('userSearch');
            if (userSearch) {
                userSearch.value = '';
            }
        }

        function removeSelectedUser(userId) {
            selectedUsers = selectedUsers.filter(user => user.id !== userId);
            updateSelectedUsersList();
        }

        function updateSelectedUsersList() {
            const selectedUsersContainer = document.getElementById('selectedUsers');
            const selectedUsersList = document.getElementById('selectedUsersList');
            const assignAdminBtn = document.getElementById('assignAdminBtn');

            if (!selectedUsersContainer || !selectedUsersList || !assignAdminBtn) return;

            if (selectedUsers.length === 0) {
                selectedUsersContainer.classList.add('hidden');
                assignAdminBtn.disabled = true;
            } else {
                selectedUsersContainer.classList.remove('hidden');
                assignAdminBtn.disabled = false;
                
                selectedUsersList.innerHTML = selectedUsers.map(user => `
                    <div class="flex items-center justify-between bg-gray-50 rounded-lg p-3">
                        <div>
                            <div class="font-medium text-gray-900">${user.name}</div>
                            <div class="text-sm text-gray-500">${user.email}</div>
                        </div>
                        <button onclick="removeSelectedUser(${user.id})" 
                                class="text-red-600 hover:text-red-800 transition-colors duration-200">
                            <i class="fa-solid fa-times"></i>
                        </button>
                    </div>
                `).join('');
            }
        }

        function assignAdminRole() {
            if (selectedUsers.length === 0) {
                toastr.error('No users selected');
                return;
            }

            const userIds = selectedUsers.map(user => user.id);
            
            // Create form data
            const formData = new FormData();
            formData.append('_token', '{{ csrf_token() }}');
            userIds.forEach(id => formData.append('user_ids[]', id));

            // Show loading state
            const assignAdminBtn = document.getElementById('assignAdminBtn');
            const originalText = assignAdminBtn.innerHTML;
            assignAdminBtn.innerHTML = '<i class="fa-solid fa-spinner fa-spin mr-2"></i>Assigning...';
            assignAdminBtn.disabled = true;

            fetch('{{ route("admin.administrator.users.assign-admin-role") }}', {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    toastr.success(data.message || 'Admin role assigned successfully');
                    // Clear selected users
                    selectedUsers = [];
                    updateSelectedUsersList();
                    // Reload the page to show updated users
                    window.location.reload();
                } else {
                    toastr.error(data.message || 'Failed to assign admin role');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                toastr.error('An error occurred while assigning admin role');
            })
            .finally(() => {
                // Restore button state
                assignAdminBtn.innerHTML = originalText;
                assignAdminBtn.disabled = false;
            });
        }

        // Hide search results when clicking outside
        document.addEventListener('click', function(event) {
            const searchContainer = document.getElementById('userSearch')?.parentElement;
            const resultsContainer = document.getElementById('searchResults');
            
            if (searchContainer && resultsContainer && 
                !searchContainer.contains(event.target) && 
                !resultsContainer.contains(event.target)) {
                hideSearchResults();
            }
        });
    </script>
</x-layouts.admin>
