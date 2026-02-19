<x-layouts.admin title="Customers">
    <div class="space-y-6">
        {{-- Header --}}
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Customers</h1>
                <p class="text-gray-600">Manage system customers</p>
            </div>
            <button type="button" id="bulkDeleteBtn"
                class="hidden items-center px-4 py-2 border border-transparent text-sm font-medium rounded-lg shadow-sm text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                <i class="fa-solid fa-trash mr-2"></i>
                Delete Selected
            </button>
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

        {{-- Bulk Delete Form (Ana form) --}}
        <form id="bulkDeleteForm" action="{{ route('admin.administrator.customers.bulk_delete') }}" method="POST">
            @csrf
            @method('DELETE')
        </form>

        {{-- Customers Table --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-200">
            @if ($customers->count() > 0)
                {{-- Custom Controls Header --}}
                <div class="p-4 border-b border-gray-200 flex items-center justify-between">
                    <div class="flex items-center space-x-4">
                        <div class="flex items-center">
                            <input type="checkbox" id="selectAll"
                                class="h-4 w-4 text-primary border-gray-300 rounded focus:ring-primary">
                            <label for="selectAll" class="ml-2 text-sm text-gray-700">Select All</label>
                        </div>
                        <div class="relative">
                            <i
                                class="fa-solid fa-search absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                            <input type="text" id="searchInput" placeholder="Search customers..."
                                class="pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary text-sm">
                        </div>
                    </div>
                    <select id="pageLength"
                        class="border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-primary focus:border-primary">
                        <option value="10">Show 10 customers</option>
                        <option value="25">Show 25 customers</option>
                        <option value="50">Show 50 customers</option>
                        <option value="100">Show 100 customers</option>
                    </select>
                </div>

                <div class="overflow-x-auto">
                    <table id="customersTable" class="w-full">
                        <thead class="bg-gray-50 border-b border-gray-200">
                            <tr>
                                <th class="w-12 py-3 px-4">
                                    <!-- Checkbox column -->
                                </th>
                                <th class="text-left py-3 px-4 font-semibold text-gray-700">First Name</th>
                                <th class="text-left py-3 px-4 font-semibold text-gray-700">Last Name</th>
                                <th class="text-left py-3 px-4 font-semibold text-gray-700">Email</th>
                                <th class="text-left py-3 px-4 font-semibold text-gray-700">Secondary Email</th>
                                <th class="text-left py-3 px-4 font-semibold text-gray-700">Email Verified</th>
                                <th class="text-left py-3 px-4 font-semibold text-gray-700">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @foreach ($customers as $customer)
                                <tr class="hover:bg-gray-50 transition-colors duration-200">
                                    <td class="py-3 px-4">
                                        <input type="checkbox" name="ids[]" value="{{ $customer->id }}"
                                            form="bulkDeleteForm"
                                            class="customer-checkbox h-4 w-4 text-primary border-gray-300 rounded focus:ring-primary"
                                            @if ($customer->id === auth()->id()) disabled @endif>
                                    </td>
                                    <td class="py-3 px-4">
                                        <div class="flex items-center space-x-3">
                                            <div class="flex-shrink-0 h-10 w-10">
                                                <img class="h-10 w-10 rounded-full"
                                                    src="{{ $customer->avatar ?? 'https://ui-avatars.com/api/?name=' . urlencode($customer->name) . '&color=7F9CF5&background=EBF4FF' }}"
                                                    alt="{{ $customer->name }}">
                                            </div>
                                            <div class="font-medium text-gray-900">{{ $customer->name }}</div>
                                        </div>
                                    </td>
                                    <td class="py-3 px-4 text-gray-900">{{ $customer->last_name ?? '-' }}</td>
                                    <td class="py-3 px-4">
                                        <a href="mailto:{{ $customer->email }}"
                                            class="text-blue-600 hover:text-blue-800 hover:underline">
                                            {{ $customer->email }}
                                        </a>
                                    </td>
                                    <td class="py-3 px-4 text-gray-900">
                                        {{ empty($customer->secondary_email) ? 'N/A' : $customer->secondary_email }}
                                    </td>
                                    <td class="py-3 px-4">
                                        @if ($customer->email_verified_at)
                                            <span
                                                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                <i class="fa-solid fa-check mr-1"></i>
                                                Verified
                                            </span>
                                        @else
                                            <span
                                                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                                <i class="fa-solid fa-times mr-1"></i>
                                                Not Verified
                                            </span>
                                        @endif
                                    </td>
                                    <td class="py-3 px-4">
                                        <div class="flex items-center space-x-2">
                                            <a href="{{ route('admin.administrator.customers.edit', $customer) }}"
                                                class="text-gray-600 hover:text-primary transition-colors duration-200"
                                                title="Edit">
                                                <i class="fa-solid fa-pen"></i>
                                            </a>

                                            @if (!$customer->email_verified_at)
                                                {{-- Active Account Form --}}
                                                <form
                                                    action="{{ route('admin.administrator.customers.active_account', $customer) }}"
                                                    method="POST" class="inline">
                                                    @csrf
                                                    <button type="submit"
                                                        class="text-gray-600 hover:text-green-600 transition-colors duration-200"
                                                        title="Active Account"
                                                        onclick="return confirm('Are you sure you want to activate this account?');">
                                                        <i class="fa-solid fa-user-check"></i>
                                                    </button>
                                                </form>
                                            @endif

                                            <form action="{{ route('admin.administrator.customers.destroy', $customer) }}"
                                                method="POST" class="inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                    class="text-gray-600 hover:text-red-600 transition-colors duration-200"
                                                    title="Delete"
                                                    onclick="return confirm('Are you sure you want to delete this customer?');">
                                                    @if ($customer->id !== auth()->id())
                                                        <i class="fa-solid fa-trash"></i>
                                                    @endif
                                                </button>
                                            </form>
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
                        Showing {{ $customers->firstItem() }} to {{ $customers->lastItem() }} of
                        {{ $customers->total() }} customers
                    </div>
                    <div class="flex items-center space-x-2">
                        {{ $customers->links() }}
                    </div>
                </div>
            @else
                <div class="text-center py-12">
                    <i class="fa-solid fa-users-slash text-4xl text-gray-300 mb-4"></i>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">No customers found</h3>
                    <p class="text-gray-600 mb-4">Get started by adding your first customer.</p>
                    <a href="{{ route('admin.administrator.customers.create') }}"
                        class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-lg shadow-sm text-white bg-primary hover:bg-primary/90 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary">
                        <i class="fa-solid fa-plus mr-2"></i>
                        Add Customer
                    </a>
                </div>
            @endif
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Display session messages
            @if (session('success'))
                toastr.success('{{ session('success') }}');
            @endif

            @if (session('error'))
                toastr.error('{{ session('error') }}');
            @endif

            @if ($errors->any())
                @foreach ($errors->all() as $error)
                    toastr.error('{{ $error }}');
                @endforeach
            @endif

            // Bulk delete functionality
            const selectAll = document.getElementById('selectAll');
            const customerCheckboxes = document.querySelectorAll('.customer-checkbox');
            const bulkDeleteBtn = document.getElementById('bulkDeleteBtn');
            const bulkDeleteForm = document.getElementById('bulkDeleteForm');

            // Select All functionality
            if (selectAll) {
                selectAll.addEventListener('change', function() {
                    customerCheckboxes.forEach(checkbox => {
                        checkbox.checked = this.checked;
                    });
                    toggleBulkDeleteButton();
                });
            }

            // Individual checkbox functionality
            customerCheckboxes.forEach(checkbox => {
                checkbox.addEventListener('change', function() {
                    updateSelectAllCheckbox();
                    toggleBulkDeleteButton();
                });
            });

            // Update Select All checkbox state
            function updateSelectAllCheckbox() {
                if (!selectAll) return;

                const allChecked = Array.from(customerCheckboxes).every(checkbox => checkbox.checked);
                const someChecked = Array.from(customerCheckboxes).some(checkbox => checkbox.checked);

                selectAll.checked = allChecked;
                selectAll.indeterminate = someChecked && !allChecked;
            }

            // Toggle bulk delete button visibility
            function toggleBulkDeleteButton() {
                if (!bulkDeleteBtn) return;

                const anyChecked = Array.from(customerCheckboxes).some(checkbox => checkbox.checked);

                if (anyChecked) {
                    bulkDeleteBtn.classList.remove('hidden');
                } else {
                    bulkDeleteBtn.classList.add('hidden');
                }
            }

            // Bulk delete confirmation
            if (bulkDeleteBtn) {
                bulkDeleteBtn.addEventListener('click', function() {
                    const selectedCount = Array.from(customerCheckboxes).filter(checkbox => checkbox
                        .checked).length;

                    if (selectedCount === 0) {
                        toastr.warning('Please select at least one customer to delete.');
                        return;
                    }

                    if (confirm(
                            `Are you sure you want to delete ${selectedCount} selected customer(s)? This action cannot be undone.`
                        )) {
                        bulkDeleteForm.submit();
                    }
                });
            }

            // Simple search functionality
            const searchInput = document.getElementById('searchInput');
            const customersTable = document.getElementById('customersTable');
            const rows = customersTable ? customersTable.querySelectorAll('tbody tr') : [];

            if (searchInput && rows.length > 0) {
                searchInput.addEventListener('input', function() {
                    const searchTerm = this.value.toLowerCase();

                    rows.forEach(row => {
                        const text = row.textContent.toLowerCase();
                        if (text.includes(searchTerm)) {
                            row.style.display = '';
                        } else {
                            row.style.display = 'none';
                        }
                    });
                });
            }

            // Page length functionality
            const pageLengthSelect = document.getElementById('pageLength');
            if (pageLengthSelect) {
                pageLengthSelect.addEventListener('change', function() {
                    const url = new URL(window.location.href);
                    url.searchParams.set('per_page', this.value);
                    window.location.href = url.toString();
                });

                // Mevcut page length'i seçili yap
                const urlParams = new URLSearchParams(window.location.search);
                const currentPerPage = urlParams.get('per_page') || '10';
                pageLengthSelect.value = currentPerPage;
            }
        });
    </script>
    </script>
</x-layouts.admin>
