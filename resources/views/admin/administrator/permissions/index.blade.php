<x-layouts.admin title="Permissions">
    <div class="space-y-6">
        {{-- Header --}}
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Permissions</h1>
                <p class="text-gray-600">Manage system permissions</p>
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

        {{-- Search and Controls --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4 mb-6">
            <div class="flex flex-wrap items-center justify-between gap-4">
                <div class="flex-1 min-w-[250px]">
                    <div class="relative">
                        <i class="fa-solid fa-search absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                        <input type="text" id="searchInput" placeholder="Search permissions..."
                            class="pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary text-sm w-full"
                            onkeyup="searchTable()">
                    </div>
                </div>
                <div class="flex items-center space-x-4">
                    <select id="pageLength" onchange="updatePageLength()"
                        class="border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-primary focus:border-primary">
                        <option value="10">Show 10 permissions</option>
                        <option value="25">Show 25 permissions</option>
                        <option value="50">Show 50 permissions</option>
                        <option value="100">Show 100 permissions</option>
                    </select>
                </div>
            </div>
        </div>

        {{-- Permissions Table --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            <div class="overflow-x-auto">
                <table id="permissionsTable" class="w-full">
                    <thead class="bg-gray-50 border-b border-gray-200">
                        <tr>
                            <th class="text-left py-3 px-4 font-semibold text-gray-700">Permission Name</th>
                            <th class="text-left py-3 px-4 font-semibold text-gray-700">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200" id="permissionsTableBody">
                        @if ($permissions->count() > 0)
                            @foreach ($permissions as $permission)
                                <tr class="hover:bg-gray-50 transition-colors duration-200">
                                    <td class="py-3 px-4">
                                        <div class="font-medium text-gray-900">{{ $permission->name }}</div>
                                    </td>
                                    {{-- <td class="py-3 px-4">
                                        <div class="flex items-center space-x-3">
                                            <a href="#" class="text-gray-600 hover:text-blue-600 transition-colors duration-200" title="View">
                                                <i class="fa-solid fa-eye"></i>
                                            </a>
                                            <a href="#" class="text-gray-600 hover:text-yellow-600 transition-colors duration-200" title="Edit">
                                                <i class="fa-solid fa-pen-to-square"></i>
                                            </a>
                                        </div>
                                    </td> --}}
                                </tr>
                            @endforeach
                        @else
                            <tr>
                                <td colspan="2" class="py-8 text-center">
                                    <div class="text-center py-4">
                                        <i class="fa-solid fa-shield-alt text-4xl text-gray-300 mb-4"></i>
                                        <h3 class="text-lg font-medium text-gray-900 mb-2">No permissions found</h3>
                                        <p class="text-gray-600 mb-4">No permissions match the current filters.</p>
                                    </div>
                                </td>
                            </tr>
                        @endif
                        
                        <!-- Loading Row (initially hidden) -->
                        <tr id="loadingRow" class="hidden">
                            <td colspan="2" class="py-8 text-center">
                                <div class="flex justify-center items-center space-x-2">
                                    <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-primary"></div>
                                    <span class="text-gray-600">Loading permissions...</span>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            @if ($permissions->hasPages())
                <div class="p-4 border-t border-gray-200" id="paginationContainer">
                    {{ $permissions->withQueryString()->links('pagination::bootstrap-4') }}
                </div>
            @endif
        </div>
    </div>

    <script>
        // Search functionality
        function searchTable() {
            const input = document.getElementById('searchInput');
            const filter = input.value.toLowerCase();
            const table = document.getElementById('permissionsTable');
            const rows = table.getElementsByTagName('tr');
            let hasVisibleRows = false;

            // Skip the header row and loading row
            for (let i = 1; i < rows.length; i++) {
                if (rows[i].id === 'loadingRow') continue;
                
                const cell = rows[i].getElementsByTagName('td')[0]; // First column (permission name)
                if (cell) {
                    const text = cell.textContent || cell.innerText;
                    if (text.toLowerCase().indexOf(filter) > -1) {
                        rows[i].style.display = '';
                        hasVisibleRows = true;
                    } else {
                        rows[i].style.display = 'none';
                    }
                }
            }

            // Show/hide no results message
            const noResults = document.querySelector('.no-results');
            if (noResults) {
                noResults.style.display = hasVisibleRows ? 'none' : '';
            }
        }

        // Update page length
        function updatePageLength() {
            const pageLength = document.getElementById('pageLength').value;
            const url = new URL(window.location.href);
            url.searchParams.set('per_page', pageLength);
            window.location.href = url.toString();
        }

        // Set current page length on load
        document.addEventListener('DOMContentLoaded', function() {
            const urlParams = new URLSearchParams(window.location.search);
            const currentPerPage = urlParams.get('per_page') || '10';
            const pageLengthSelect = document.getElementById('pageLength');
            if (pageLengthSelect) {
                pageLengthSelect.value = currentPerPage;
            }
        });
    </script>
    </script>
</x-layouts.admin>