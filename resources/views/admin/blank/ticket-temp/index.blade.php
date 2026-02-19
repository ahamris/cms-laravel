<x-layouts.admin title="Tickets">
    <div class="space-y-6">
        {{-- Header --}}
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Tickets</h1>
                <p class="text-gray-600">Manage support tickets</p>
            </div>
            <a href="#"
                class="bg-primary text-white px-4 py-2 rounded-lg hover:bg-primary/80 transition-colors duration-200 flex items-center space-x-2">
                <i class="fa-solid fa-plus"></i>
                <span>Create Ticket</span>
            </a>
        </div>

        {{-- Search and Controls --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4 mb-6">
            <div class="flex flex-wrap items-center justify-between gap-4">
                <div class="flex-1 min-w-[250px]">
                    <div class="relative">
                        <i class="fa-solid fa-search absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                        <input type="text" id="searchInput" placeholder="Search tickets..."
                            class="pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary text-sm w-full"
                            onkeyup="searchTable()">
                    </div>
                </div>
                <select id="pageLength" onchange="updatePageLength()"
                    class="border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-primary focus:border-primary">
                    <option value="10">Show 10</option>
                    <option value="25">Show 25</option>
                    <option value="50">Show 50</option>
                    <option value="100">Show 100</option>
                </select>
            </div>
        </div>

        {{-- Tickets Table --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            <div class="overflow-x-auto">
                <table id="ticketsTable" class="w-full">
                    <thead class="bg-gray-50 border-b border-gray-200">
                        <tr>
                            <th class="text-left py-3 px-4 font-semibold text-gray-700">Reference</th>
                            <th class="text-left py-3 px-4 font-semibold text-gray-700">Title</th>
                            <th class="text-left py-3 px-4 font-semibold text-gray-700">Status</th>
                            <th class="text-left py-3 px-4 font-semibold text-gray-700">Priority</th>
                            <th class="text-left py-3 px-4 font-semibold text-gray-700">Type</th>
                            <th class="text-left py-3 px-4 font-semibold text-gray-700">Assigned To</th>
                            <th class="text-left py-3 px-4 font-semibold text-gray-700">Due Date</th>
                            <th class="text-left py-3 px-4 font-semibold text-gray-700">Created At</th>
                            <th class="text-right py-3 px-4 font-semibold text-gray-700">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200" id="ticketsTableBody">

                        {{-- Fake Data --}}
                        <tr class="hover:bg-gray-50 transition-colors duration-200">
                            <td class="py-3 px-4">
                                <div class="text-sm font-medium text-gray-900">#TCK-001</div>
                            </td>
                            <td class="py-3 px-4">
                                <div class="text-sm text-gray-900">Login Issue</div>
                                <div class="text-xs text-gray-500 truncate max-w-xs">User cannot log in with correct credentials.</div>
                            </td>
                            <td class="py-3 px-4">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                    Open
                                </span>
                            </td>
                            <td class="py-3 px-4">
                                <div class="text-sm text-gray-900">High</div>
                            </td>
                            <td class="py-3 px-4">
                                <div class="text-sm text-gray-900">Bug</div>
                            </td>
                            <td class="py-3 px-4">
                                <div class="text-sm text-gray-900">John Doe</div>
                            </td>
                            <td class="py-3 px-4">
                                <div class="text-sm text-gray-900">2025-09-30 12:00</div>
                            </td>
                            <td class="py-3 px-4">
                                <div class="text-sm text-gray-900">2025-09-20</div>
                            </td>
                            <td class="py-3 px-4 text-right">
                                <div class="flex justify-end space-x-2">
                                    <a href="#" class="text-gray-600 hover:text-yellow-600 transition-colors duration-200" title="Edit">
                                        <i class="fa-solid fa-pen-to-square"></i>
                                    </a>
                                    <button type="button"
                                            class="text-gray-600 hover:text-red-600 transition-colors duration-200"
                                            title="Delete"
                                            onclick="return confirm('Are you sure you want to delete this ticket?')">
                                        <i class="fa-solid fa-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>

                        <tr class="hover:bg-gray-50 transition-colors duration-200">
                            <td class="py-3 px-4">
                                <div class="text-sm font-medium text-gray-900">#TCK-002</div>
                            </td>
                            <td class="py-3 px-4">
                                <div class="text-sm text-gray-900">Payment Failed</div>
                                <div class="text-xs text-gray-500 truncate max-w-xs">Customer's payment could not be processed.</div>
                            </td>
                            <td class="py-3 px-4">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                    In Progress
                                </span>
                            </td>
                            <td class="py-3 px-4">
                                <div class="text-sm text-gray-900">Medium</div>
                            </td>
                            <td class="py-3 px-4">
                                <div class="text-sm text-gray-900">Support</div>
                            </td>
                            <td class="py-3 px-4">
                                <div class="text-sm text-gray-900">Jane Smith</div>
                            </td>
                            <td class="py-3 px-4">
                                <div class="text-sm text-gray-900">2025-10-02 18:00</div>
                            </td>
                            <td class="py-3 px-4">
                                <div class="text-sm text-gray-900">2025-09-21</div>
                            </td>
                            <td class="py-3 px-4 text-right">
                                <div class="flex justify-end space-x-2">
                                    <a href="#" class="text-gray-600 hover:text-yellow-600 transition-colors duration-200" title="Edit">
                                        <i class="fa-solid fa-pen-to-square"></i>
                                    </a>
                                    <button type="button"
                                            class="text-gray-600 hover:text-red-600 transition-colors duration-200"
                                            title="Delete"
                                            onclick="return confirm('Are you sure you want to delete this ticket?')">
                                        <i class="fa-solid fa-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>

                        <tr class="hover:bg-gray-50 transition-colors duration-200">
                            <td class="py-3 px-4">
                                <div class="text-sm font-medium text-gray-900">#TCK-003</div>
                            </td>
                            <td class="py-3 px-4">
                                <div class="text-sm text-gray-900">Feature Request</div>
                                <div class="text-xs text-gray-500 truncate max-w-xs">User requested dark mode support.</div>
                            </td>
                            <td class="py-3 px-4">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">
                                    Closed
                                </span>
                            </td>
                            <td class="py-3 px-4">
                                <div class="text-sm text-gray-900">Low</div>
                            </td>
                            <td class="py-3 px-4">
                                <div class="text-sm text-gray-900">Enhancement</div>
                            </td>
                            <td class="py-3 px-4">
                                <div class="text-sm text-gray-900">Unassigned</div>
                            </td>
                            <td class="py-3 px-4">
                                <div class="text-sm text-gray-900">No due date</div>
                            </td>
                            <td class="py-3 px-4">
                                <div class="text-sm text-gray-900">2025-09-19</div>
                            </td>
                            <td class="py-3 px-4 text-right">
                                <div class="flex justify-end space-x-2">
                                    <a href="#" class="text-gray-600 hover:text-yellow-600 transition-colors duration-200" title="Edit">
                                        <i class="fa-solid fa-pen-to-square"></i>
                                    </a>
                                    <button type="button"
                                            class="text-gray-600 hover:text-red-600 transition-colors duration-200"
                                            title="Delete"
                                            onclick="return confirm('Are you sure you want to delete this ticket?')">
                                        <i class="fa-solid fa-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>

                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script>
    // Search functionality
    function searchTable() {
        const input = document.getElementById('searchInput');
        const filter = input.value.toLowerCase();
        const table = document.getElementById('ticketsTable');
        const rows = table.getElementsByTagName('tr');

        for (let i = 1; i < rows.length; i++) {
            const cells = rows[i].getElementsByTagName('td');
            let rowText = '';
            for (let j = 0; j < cells.length; j++) {
                rowText += cells[j].textContent || cells[j].innerText;
            }
            rows[i].style.display = rowText.toLowerCase().includes(filter) ? '' : 'none';
        }
    }

    // Update page length
    function updatePageLength() {
        alert('Sayfa uzunluğu ayarı fake veri için çalışmıyor. Backend bağlanınca aktif olacak.');
    }
</script>
    </script>
</x-layouts.admin>
