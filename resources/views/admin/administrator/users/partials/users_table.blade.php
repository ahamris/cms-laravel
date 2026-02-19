@php use App\Helpers\Variable; @endphp
<div class="p-4 border-b border-gray-200 flex flex-wrap items-center justify-between gap-4">
    <div class="flex items-center space-x-4 flex-wrap gap-2">
        <div class="relative">
            <i class="fa-solid fa-search absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
            <input type="text" id="searchInput" placeholder="Search users..."
                class="pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary text-sm">
        </div>

        <div class="flex items-center space-x-2">
            <label class="text-sm text-gray-600 whitespace-nowrap">Filter by Role:</label>
            <select id="roleFilter"
                class="border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-primary focus:border-primary min-w-[180px]">
                <option value="">All Roles</option>
                @foreach ($roles as $role)
                    <option value="{{ $role->id }}" {{ request('role') == $role->id ? 'selected' : '' }}>
                        {{ $role->name }}</option>
                @endforeach
            </select>
        </div>

        <div class="flex items-center space-x-2">
            <label class="text-sm text-gray-600 whitespace-nowrap">Filter by Permission:</label>
            <select id="permissionFilter"
                class="border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-primary focus:border-primary min-w-[180px]">
                <option value="">All Permissions</option>
                @foreach ($permissions as $permission)
                    <option value="{{ $permission->id }}"
                        {{ request('permissions') == $permission->id ? 'selected' : '' }}>{{ $permission->name }}
                    </option>
                @endforeach
            </select>
        </div>
    </div>

    <div class="flex items-center space-x-2">
        <button id="resetFilters"
            class="text-sm text-gray-600 hover:text-primary transition-colors duration-200 flex items-center">
            <i class="fa-solid fa-rotate-left mr-1"></i> Reset Filters
        </button>
        <select id="pageLength"
            class="border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-primary focus:border-primary">
            <option value="10" {{ request('per_page', 10) == 10 ? 'selected' : '' }}>Show 10 users</option>
            <option value="25" {{ request('per_page') == 25 ? 'selected' : '' }}>Show 25 users</option>
            <option value="50" {{ request('per_page') == 50 ? 'selected' : '' }}>Show 50 users</option>
            <option value="100" {{ request('per_page') == 100 ? 'selected' : '' }}>Show 100 users</option>
        </select>
    </div>
</div>

<div class="overflow-x-auto">
    <table id="usersTable" class="w-full">
        <thead class="bg-gray-50 border-b border-gray-200">
            <tr>
                <th class="text-left py-3 px-4 font-semibold text-gray-700">Name</th>
                <th class="text-left py-3 px-4 font-semibold text-gray-700">Email</th>
                <th class="text-left py-3 px-4 font-semibold text-gray-700">Roles</th>
                <th class="text-left py-3 px-4 font-semibold text-gray-700">Permissions</th>
                <th class="text-left py-3 px-4 font-semibold text-gray-700">Actions</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-200" id="usersTableBody">
            @if ($users->count() > 0)
                @foreach ($users as $user)
                    <tr class="hover:bg-gray-50 transition-colors duration-200">
                        <td class="py-3 px-4">
                            <div class="flex items-center space-x-3">
                                <div class="flex-shrink-0 h-10 w-10">
                                    <img class="h-10 w-10 rounded-full object-cover" src="{{ $user->get_avatar }}"
                                        alt="{{ $user->name }}">
                                </div>
                                <div>
                                    <div class="font-medium text-gray-900">{{ $user->name }} {{ $user->last_name }}
                                    </div>
                                </div>
                            </div>
                        </td>
                        <td class="py-3 px-4">
                            <a href="mailto:{{ $user->email }}"
                                class="text-blue-600 hover:text-blue-800 hover:underline">
                                {{ $user->email }}
                            </a>
                        </td>
                        <td class="py-3 px-4">
                            <div class="flex flex-wrap gap-1">
                                @forelse($user->roles as $role)
                                    <span
                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                        {{ $role->name }}
                                    </span>
                                @empty
                                    <span class="text-sm text-gray-500">No roles</span>
                                @endforelse
                            </div>
                        </td>
                        <td class="py-3 px-4">
                            <div class="flex flex-wrap gap-1">
                                @php
                                    $directPermissions = $user->getDirectPermissions();
                                @endphp
                                @forelse($directPermissions->take(3) as $permission)
                                    <span
                                        class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-purple-100 text-purple-800">
                                        {{ $permission->name }}
                                    </span>
                                @empty
                                    <span class="text-sm text-gray-500">No extra permissions</span>
                                @endforelse

                                @if ($directPermissions->count() > 3)
                                    <span
                                        class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-gray-100 text-gray-600"
                                        title="{{ $directPermissions->slice(3)->pluck('name')->implode(', ') }}">
                                        +{{ $directPermissions->count() - 3 }} more
                                    </span>
                                @endif
                            </div>
                        </td>
                        <td class="py-3 px-4">
                            <div class="flex items-center space-x-3">
                                <a href="{{ route('admin.administrator.users.show', $user) }}"
                                    class="text-gray-600 hover:text-blue-600 transition-colors duration-200"
                                    title="View">
                                    <i class="fa-solid fa-eye"></i>
                                </a>
                                @if (Variable::hasPermission('user_edit'))
                                    <a href="{{ route('admin.administrator.users.edit', $user) }}"
                                        class="text-gray-600 hover:text-yellow-600 transition-colors duration-200"
                                        title="Edit">
                                        <i class="fa-solid fa-pen-to-square"></i>
                                    </a>
                                @endif
                                @if (Variable::hasPermission('user_delete') && $user->id !== 1)
                                    <button onclick="deleteUser({{ $user->id }}, '{{ addslashes($user->name) }}')"
                                        class="text-gray-600 hover:text-red-600 transition-colors duration-200"
                                        title="Delete">
                                        <i class="fa-solid fa-trash"></i>
                                    </button>
                                @elseif ($user->id === 1)
                                    <span class="text-gray-400" title="Super admin cannot be deleted">
                                        <i class="fa-solid fa-shield-halved"></i>
                                    </span>
                                @endif
                            </div>
                        </td>
                    </tr>
                @endforeach
            @else
                <tr>
                    <td colspan="5" class="py-8 text-center">
                        <div class="text-center py-4">
                            <i class="fa-solid fa-users-slash text-4xl text-gray-300 mb-4"></i>
                            <h3 class="text-lg font-medium text-gray-900 mb-2">No users found</h3>
                            <p class="text-gray-600 mb-4">No users match the current filters.</p>
                        </div>
                    </td>
                </tr>
            @endif

            <!-- Loading Row (initially hidden) -->
            <tr id="loadingRow" class="hidden">
                <td colspan="5" class="py-8 text-center">
                    <div class="flex justify-center items-center space-x-2">
                        <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-primary"></div>
                        <span class="text-gray-600">Loading users...</span>
                    </div>
                </td>
            </tr>
        </tbody>
    </table>
</div>

@if ($users->count() > 0)
    <!-- Pagination -->
    <div class="p-4 border-t border-gray-200 flex justify-between items-center" id="paginationContainer">
        <div>{{ $users->withQueryString()->links('pagination::bootstrap-4') }}</div>
    </div>
@else
@endif

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const searchInput = document.getElementById('searchInput');
            const roleFilter = document.getElementById('roleFilter');
            const permissionFilter = document.getElementById('permissionFilter');
            const resetFiltersBtn = document.getElementById('resetFilters');
            const resetFiltersTableBtn = document.getElementById('resetFiltersTable');
            const usersTableBody = document.getElementById('usersTableBody');
            const loadingRow = document.getElementById('loadingRow');
            const paginationContainer = document.getElementById('paginationContainer');
            let debounceTimer;

            // Function to show loading state
            function showLoading() {
                // Hide all rows except the loading row
                const rows = usersTableBody.querySelectorAll('tr:not(#loadingRow)');
                rows.forEach(row => row.classList.add('hidden'));
                loadingRow.classList.remove('hidden');
                if (paginationContainer) paginationContainer.classList.add('hidden');
            }

            // Function to update the table with new data
            function updateTable(html) {
                // Create a temporary div to parse the HTML
                const tempDiv = document.createElement('div');
                tempDiv.innerHTML = html;

                // Get the new table body and pagination
                const newTableBody = tempDiv.querySelector('#usersTableBody');
                const newPagination = tempDiv.querySelector('#paginationContainer');

                // Update the table body
                if (newTableBody) {
                    usersTableBody.innerHTML = newTableBody.innerHTML;
                }

                // Update the pagination
                if (paginationContainer && newPagination) {
                    paginationContainer.innerHTML = newPagination.innerHTML;
                    paginationContainer.classList.remove('hidden');
                } else if (paginationContainer) {
                    paginationContainer.classList.add('hidden');
                }

                // Hide loading state
                loadingRow.classList.add('hidden');
            }

            // Function to fetch filtered users
            function fetchUsers() {
                showLoading();

                // Get current URL and create search params
                const url = new URL(window.location.href);
                const params = new URLSearchParams(url.search);

                // Update search params with current filter values
                if (searchInput.value) params.set('search', searchInput.value);
                else params.delete('search');

                if (roleFilter.value) params.set('role', roleFilter.value);
                else params.delete('role');

                if (permissionFilter.value) params.set('permissions', permissionFilter.value);
                else params.delete('permissions');

                // Update URL without page reload
                history.pushState({}, '', `${url.pathname}?${params.toString()}`);

                // Make AJAX request
                fetch(`${url.pathname}?${params.toString()}&ajax=1`, {
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'Accept': 'application/json',
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.html) {
                            updateTable(data.html);
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        loadingRow.classList.add('hidden');
                        // Show error message
                        usersTableBody.innerHTML = `
                <tr>
                    <td colspan="5" class="py-8 text-center">
                        <div class="text-center py-4">
                            <i class="fa-solid fa-exclamation-triangle text-4xl text-red-500 mb-4"></i>
                            <h3 class="text-lg font-medium text-gray-900 mb-2">Error loading users</h3>
                            <p class="text-gray-600 mb-4">There was an error loading the users. Please try again.</p>
                            <button onclick="window.location.reload()" class="px-4 py-2 bg-primary text-white rounded-md hover:bg-primary-dark transition-colors">
                                Reload Page
                            </button>
                        </div>
                    </td>
                </tr>`;
                    });
            }

            // Debounce function to limit how often the fetchUsers function is called
            function debounceFetch() {
                clearTimeout(debounceTimer);
                debounceTimer = setTimeout(fetchUsers, 500);
            }

            // Event listeners
            searchInput.addEventListener('input', debounceFetch);
            roleFilter.addEventListener('change', fetchUsers);
            permissionFilter.addEventListener('change', fetchUsers);

            // Reset filters
            function resetFilters() {
                searchInput.value = '';
                roleFilter.value = '';
                permissionFilter.value = '';
                fetchUsers();
            }

            if (resetFiltersBtn) {
                resetFiltersBtn.addEventListener('click', resetFilters);
            }

            if (resetFiltersTableBtn) {
                resetFiltersTableBtn.addEventListener('click', resetFilters);
            }

            // Handle browser back/forward buttons
            window.addEventListener('popstate', function() {
                const url = new URL(window.location.href);
                const params = new URLSearchParams(url.search);

                // Update form fields
                searchInput.value = params.get('search') || '';
                roleFilter.value = params.get('role') || '';
                permissionFilter.value = params.get('permissions') || '';

                // Fetch users with current URL
                fetchUsers();
            });
        });
    </script>
@endpush
