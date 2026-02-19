@php use App\Helpers\Variable; @endphp
<x-layouts.admin title="Roles">
    <div class="space-y-6">
        {{-- Header --}}
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Roles</h1>
                <p class="text-gray-600">Manage system roles and their permissions</p>
            </div>
            @if(Variable::hasPermission('role_create'))
            <a href="{{ route('admin.administrator.roles.create') }}"
                class="bg-primary text-white px-4 py-2 rounded-lg hover:bg-primary/90 transition-colors duration-200 flex items-center space-x-2">
                <i class="fa-solid fa-plus"></i>
                <span>Add Role</span>
            </a>
            @endif
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

        {{-- Roles Table --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-200">
            @if ($roles->count() > 0)
                {{-- Custom Controls Header --}}
                <div class="p-4 border-b border-gray-200 flex items-center justify-between">
                    <div class="relative">
                        <i class="fa-solid fa-search absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                        <input type="text" id="searchInput" placeholder="Search roles..."
                            class="pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary text-sm">
                    </div>
                    <select id="pageLength"
                        class="border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-primary focus:border-primary">
                        <option value="10">Show 10 roles</option>
                        <option value="25">Show 25 roles</option>
                        <option value="50">Show 50 roles</option>
                        <option value="100">Show 100 roles</option>
                    </select>
                </div>

                <div class="overflow-x-auto">
                    <table id="rolesTable" class="w-full">
                        <thead class="bg-gray-50 border-b border-gray-200">
                            <tr>
                                <th class="text-left py-3 px-4 font-semibold text-gray-700">Name</th>
                                <th class="text-left py-3 px-4 font-semibold text-gray-700">Users</th>
                                <th class="text-left py-3 px-4 font-semibold text-gray-700">Permissions</th>
                                <th class="text-right py-3 px-4 font-semibold text-gray-700">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @foreach ($roles as $role)
                                <tr class="hover:bg-gray-50 transition-colors duration-200">
                                    <td class="py-3 px-4">
                                        <div class="font-medium text-gray-900">{{ $role->name }}</div>
                                    </td>
                                    <td class="py-3 px-4">
                                        @if($role->users_count > 0)
                                        <a href="{{ route('admin.administrator.users.index', ['role' => $role->id]) }}" 
                                           class="text-blue-600 hover:text-blue-800 hover:underline">
                                            {{ $role->users_count ?? 0 }} {{ $role->users_count == 1 ? 'user' : 'users' }}
                                        </a>
                                        @else
                                            <span class="text-gray-500">No users</span>
                                        @endif
                                    </td>
                                    <td class="py-3 px-4">
                                        <div class="flex flex-wrap gap-1">
                                            @forelse($role->permissions->take(3) as $permission)
                                                <span class="px-2 py-0.5 text-xs font-medium bg-blue-100 text-blue-800 rounded-full">
                                                    {{ $permission->name }}
                                                </span>
                                            @empty
                                                <span class="text-sm text-gray-500">No permissions</span>
                                            @endforelse
                                            
                                            @if($role->permissions->count() > 3)
                                                <span class="px-2 py-0.5 text-xs font-medium bg-gray-100 text-gray-600 rounded-full">
                                                    +{{ $role->permissions->count() - 3 }} more
                                                </span>
                                            @endif
                                        </div>
                                    </td>
                                    <td class="py-3 px-4">
                                        <div class="flex items-center justify-end space-x-2">
                                            @if(Variable::hasPermission('role_edit'))
                                            <a href="{{ route('admin.administrator.roles.edit', $role) }}"
                                                class="text-gray-600 hover:text-primary transition-colors duration-200"
                                                title="Edit">
                                                <i class="fa-solid fa-pen"></i>
                                            </a>
                                            @endif
                                            
                                            @if($role->name === 'super-admin')
                                                <span class="text-gray-400 cursor-not-allowed"
                                                    title="The super-admin role cannot be deleted">
                                                    <i class="fa-solid fa-trash"></i>
                                                </span>
                                            @elseif(auth()->user()->roles->pluck('name')->contains($role->name))
                                                <span class="text-gray-400 cursor-not-allowed"
                                                    title="You cannot delete your own role">
                                                    <i class="fa-solid fa-trash"></i>
                                                </span>
                                            @elseif(Variable::hasPermission('role_delete'))
                                                <form action="{{ route('admin.administrator.roles.destroy', $role) }}" 
                                                    method="POST"
                                                    onsubmit="return confirm('Are you sure you want to delete this role?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" 
                                                        class="text-gray-600 hover:text-red-600 transition-colors duration-200"
                                                        title="Delete">
                                                        <i class="fa-solid fa-trash"></i>
                                                    </button>
                                                </form>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                {{-- Custom Pagination Footer --}}
                <div class="p-4 border-t border-gray-200 flex items-center justify-between">
                    <div id="tableInfo" class="text-sm text-gray-600">
                        Showing {{ $roles->firstItem() }} to {{ $roles->lastItem() }} of {{ $roles->total() }} roles
                    </div>
                    <div class="flex items-center space-x-2">
                        {{ $roles->links() }}
                    </div>
                </div>
            @else
                <div class="text-center py-12">
                    <i class="fa-solid fa-shield-alt text-4xl text-gray-300 mb-4"></i>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">No roles found</h3>
                    <p class="text-gray-600 mb-4">Get started by adding your first role.</p>
                    @if(Variable::hasPermission('role_create'))
                    <a href="{{ route('admin.administrator.roles.create') }}"
                        class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-lg shadow-sm text-white bg-primary hover:bg-primary/90 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary">
                        <i class="fa-solid fa-plus mr-2"></i>
                        Add Role
                    </a>
                    @endif
                </div>
            @endif
        </div>
    </div>

    {{-- Delete Confirmation Modal --}}
    <div id="deleteModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/50">
        <div class="bg-white rounded-xl p-6 w-full max-w-md mx-4">
            <div class="flex items-center space-x-3 mb-4">
                <div class="flex-shrink-0 w-10 h-10 bg-red-100 rounded-full flex items-center justify-center">
                    <i class="fa-solid fa-exclamation-triangle text-red-600"></i>
                </div>
                <div>
                    <h3 class="text-lg font-medium text-gray-900">Delete Role</h3>
                    <p class="text-gray-600">Are you sure you want to delete this role?</p>
                </div>
            </div>
            <div class="flex justify-end space-x-3 mt-6">
                <button type="button" onclick="closeDeleteModal()"
                    class="px-4 py-2 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-50">
                    Cancel
                </button>
                <form id="deleteForm" method="POST" class="inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit"
                        class="px-4 py-2 border border-transparent rounded-lg text-sm font-medium text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                        Delete
                    </button>
                </form>
            </div>
        </div>
    </div>

    <style>
    <style>
        /* Add any custom styles here */
    </style>

    <script>
        // Initialize DataTable
        document.addEventListener('DOMContentLoaded', function() {
            // Display session messages
            @if (session('success'))
                toastr.success('{{ session('success') }}');
            @endif

            @if (session('error'))
                toastr.error('{{ session('error') }}');
            @endif

            // Initialize DataTable if available
            if (typeof DataTable !== 'undefined' && document.getElementById('rolesTable')) {
                const table = new DataTable('#rolesTable', {
                    pageLength: 10,
                    responsive: true,
                    order: [[0, 'asc']],
                    dom: 'rt<"flex justify-between items-center px-4 py-3 border-t border-gray-200"<"text-sm text-gray-600"i><"pagination flex space-x-1"p>>',
                    language: {
                        info: 'Showing _START_ to _END_ of _TOTAL_ roles',
                        infoEmpty: 'No roles to show',
                        infoFiltered: '(filtered from _MAX_ total roles)',
                        lengthMenu: 'Show _MENU_ roles',
                        search: '',
                        searchPlaceholder: 'Search roles...',
                        paginate: {
                            previous: '<i class="fa-solid fa-chevron-left"></i>',
                            next: '<i class="fa-solid fa-chevron-right"></i>'
                        }
                    },
                    drawCallback: function() {
                        // Update custom info
                        const info = this.api().page.info();
                        const infoElement = document.getElementById('tableInfo');
                        if (infoElement) {
                            const start = info.start + 1;
                            const end = info.end > info.recordsDisplay ? info.recordsDisplay : info.end;
                            infoElement.textContent = `Showing ${start} to ${end} of ${info.recordsDisplay} roles`;
                        }
                    }
                });

                // Make table accessible globally for pagination
                window.rolesTable = table;

                // Update custom search input
                const searchInput = document.getElementById('searchInput');
                if (searchInput) {
                    searchInput.addEventListener('keyup', function() {
                        table.search(this.value).draw();
                    });
                }

                // Update page length
                const pageLength = document.getElementById('pageLength');
                if (pageLength) {
                    pageLength.addEventListener('change', function() {
                        table.page.len(this.value).draw();
                    });
                }
            }
            }
        }

        // Update page length
        function updatePageLength() {
            const pageLength = document.getElementById('pageLength').value;
            const url = new URL(window.location.href);
            url.searchParams.set('per_page', pageLength);
            window.location.href = url.toString();
        }

        // Delete modal functions
        function openDeleteModal(roleId, roleName) {
            document.getElementById('roleName').textContent = roleName;
            document.getElementById('deleteForm').action = `/admin/administrator/roles/${roleId}`;
            document.getElementById('deleteModal').classList.remove('hidden');
            document.body.classList.add('overflow-hidden');
        }

        function closeDeleteModal() {
            document.getElementById('deleteModal').classList.add('hidden');
            document.body.classList.remove('overflow-hidden');
        }

        // Set current page length on load
        document.addEventListener('DOMContentLoaded', function() {
            const urlParams = new URLSearchParams(window.location.search);
            const currentPerPage = urlParams.get('per_page') || '10';
            const pageLengthSelect = document.getElementById('pageLength');
            if (pageLengthSelect) {
                pageLengthSelect.value = currentPerPage;
            }

            // Add event listeners
            const searchInput = document.getElementById('searchInput');
            if (searchInput) {
                searchInput.addEventListener('keyup', searchTable);
            }

            const pageLengthSelectElement = document.getElementById('pageLength');
            if (pageLengthSelectElement) {
                pageLengthSelectElement.addEventListener('change', updatePageLength);
            }
        });
    </script>
    </script>
</x-layouts.admin>
