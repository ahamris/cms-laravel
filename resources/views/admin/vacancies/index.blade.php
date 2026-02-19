<x-layouts.admin title="Vacancies">
<div class="space-y-6">
    {{-- Header --}}
    <div class="flex items-center justify-between">
        <div class="flex items-center gap-3">
            <div class="w-12 h-12 bg-primary rounded-md flex items-center justify-center">
                <i class="fa-solid fa-briefcase text-white text-base"></i>
            </div>
            <div class="flex flex-col gap-1">
                <h2>Vacancies</h2>
                <p>Manage job vacancies and positions</p>
            </div>
        </div>
        <a href="{{ route('admin.vacancies.create') }}" 
           class="px-5 py-2 rounded-md bg-primary text-white text-sm hover:bg-primary/80 transition-colors duration-200 flex items-center space-x-2">
            <i class="fa-solid fa-plus"></i>
            <span>Add Vacancy</span>
        </a>
    </div>

    {{-- Stats Cards --}}
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <div class="bg-white rounded-md border border-gray-200 p-4">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs text-gray-500 mb-1">Total Vacancies</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $stats['total'] }}</p>
                </div>
                <div class="w-12 h-12 bg-blue-100 rounded-md flex items-center justify-center">
                    <i class="fa-solid fa-briefcase text-blue-600"></i>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-md border border-gray-200 p-4">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs text-gray-500 mb-1">Active</p>
                    <p class="text-2xl font-bold text-green-600">{{ $stats['active'] }}</p>
                </div>
                <div class="w-12 h-12 bg-green-100 rounded-md flex items-center justify-center">
                    <i class="fa-solid fa-check-circle text-green-600"></i>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-md border border-gray-200 p-4">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs text-gray-500 mb-1">Inactive</p>
                    <p class="text-2xl font-bold text-red-600">{{ $stats['inactive'] }}</p>
                </div>
                <div class="w-12 h-12 bg-red-100 rounded-md flex items-center justify-center">
                    <i class="fa-solid fa-times-circle text-red-600"></i>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-md border border-gray-200 p-4">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs text-gray-500 mb-1">Total Applications</p>
                    <p class="text-2xl font-bold text-purple-600">{{ $stats['applications'] }}</p>
                </div>
                <div class="w-12 h-12 bg-purple-100 rounded-md flex items-center justify-center">
                    <i class="fa-solid fa-file-alt text-purple-600"></i>
                </div>
            </div>
        </div>
    </div>

    {{-- Success/Error Messages --}}
    @if(session('success'))
        <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-md text-sm">
            <i class="fa-solid fa-check-circle mr-2"></i>
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-md text-sm">
            <i class="fa-solid fa-exclamation-circle mr-2"></i>
            {{ session('error') }}
        </div>
    @endif

    {{-- Vacancies Table --}}
    <div class="bg-gray-50/50 rounded-md border border-gray-200">
        @if($vacancies->count() > 0)
            {{-- Custom Controls Header --}}
            <div class="p-4 border-b border-gray-200 bg-gray-50/80 flex items-center justify-between">
                <div class="relative">
                    <i class="fa-solid fa-search absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400 text-xs"></i>
                    <input type="text" 
                           id="searchInput" 
                           placeholder="Search vacancies..." 
                           class="pl-10 pr-4 py-2 h-9 text-sm bg-white border border-gray-200 rounded-md focus:outline-none">
                </div>
                <select id="pageLength" class="h-9 text-sm bg-white border border-gray-200 rounded-md px-3 focus:outline-none">
                    <option value="10">Show 10 vacancies</option>
                    <option value="25">Show 25 vacancies</option>
                    <option value="50">Show 50 vacancies</option>
                    <option value="100">Show 100 vacancies</option>
                </select>
            </div>
            
            <div class="overflow-x-auto">
                <table id="vacanciesTable" class="w-full">
                    <thead class="bg-gray-50/80 border-b border-gray-200">
                        <tr>
                            <th class="text-left py-3 px-4 text-xs font-medium text-gray-500 uppercase tracking-wider">Title</th>
                            <th class="text-left py-3 px-4 text-xs font-medium text-gray-500 uppercase tracking-wider">Location</th>
                            <th class="text-left py-3 px-4 text-xs font-medium text-gray-500 uppercase tracking-wider">Type</th>
                            <th class="text-left py-3 px-4 text-xs font-medium text-gray-500 uppercase tracking-wider">Department</th>
                            <th class="text-left py-3 px-4 text-xs font-medium text-gray-500 uppercase tracking-wider">Applications</th>
                            <th class="text-left py-3 px-4 text-xs font-medium text-gray-500 uppercase tracking-wider">Closing Date</th>
                            <th class="text-left py-3 px-4 text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            <th class="text-left py-3 px-4 text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($vacancies as $vacancy)
                            <tr class="hover:bg-gray-50 transition-colors duration-200">
                                <td class="py-3 px-4">
                                    <div class="text-xs font-medium text-gray-900 max-w-xs truncate">{{ $vacancy->title }}</div>
                                    <div class="text-xs text-gray-500 mt-1">
                                        <code class="text-xs">{{ $vacancy->slug }}</code>
                                    </div>
                                </td>
                                <td class="py-3 px-4">
                                    <span class="text-xs text-gray-900">{{ $vacancy->location }}</span>
                                </td>
                                <td class="py-3 px-4">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                        {{ ucfirst(str_replace('-', ' ', $vacancy->type)) }}
                                    </span>
                                </td>
                                <td class="py-3 px-4">
                                    <span class="text-xs text-gray-900">{{ $vacancy->department }}</span>
                                </td>
                                <td class="py-3 px-4">
                                    <a href="{{ route('admin.job-applications.index', ['vacancy_id' => $vacancy->id]) }}" 
                                       class="text-xs text-primary hover:text-primary/80 transition-colors duration-200">
                                        {{ $vacancy->applications_count }} application(s)
                                    </a>
                                </td>
                                <td class="py-3 px-4">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $vacancy->closing_date < now() ? 'bg-red-100 text-red-800' : 'bg-green-100 text-green-800' }}">
                                        {{ $vacancy->closing_date->format('M d, Y') }}
                                    </span>
                                </td>
                                <td class="py-3 px-4">
                                    <button onclick="toggleStatus({{ $vacancy->id }}, {{ $vacancy->is_active ? 'true' : 'false' }})" 
                                            class="status-toggle relative inline-flex h-6 w-11 items-center rounded-full transition-colors focus:outline-none {{ $vacancy->is_active ? 'bg-primary' : 'bg-gray-200' }}"
                                            data-vacancy-id="{{ $vacancy->id }}">
                                        <span class="sr-only">Toggle status</span>
                                        <span class="inline-block h-4 w-4 transform rounded-full bg-white transition-transform {{ $vacancy->is_active ? 'translate-x-6' : 'translate-x-1' }}"></span>
                                    </button>
                                </td>
                                <td class="py-3 px-4">
                                    <div class="flex items-center space-x-2">
                                        <a href="{{ route('career.detail', $vacancy->slug) }}" 
                                           target="_blank"
                                           class="text-xs text-gray-600 hover:text-green-600 transition-colors duration-200" 
                                           title="View on Site">
                                            <i class="fa-solid fa-external-link-alt"></i>
                                        </a>
                                        <a href="{{ route('admin.vacancies.show', $vacancy) }}" 
                                           class="text-xs text-gray-600 hover:text-primary transition-colors duration-200" 
                                           title="View">
                                            <i class="fa-solid fa-eye"></i>
                                        </a>
                                        <a href="{{ route('admin.vacancies.edit', $vacancy) }}" 
                                           class="text-xs text-gray-600 hover:text-blue-600 transition-colors duration-200" 
                                           title="Edit">
                                            <i class="fa-solid fa-edit"></i>
                                        </a>
                                        <button onclick="deleteVacancy({{ $vacancy->id }}, '{{ addslashes($vacancy->title) }}')" 
                                                class="text-xs text-gray-600 hover:text-red-600 transition-colors duration-200" 
                                                title="Delete">
                                            <i class="fa-solid fa-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            
            {{-- Custom Pagination Footer --}}
            <div class="p-4 border-t border-gray-200 bg-gray-50/80 flex items-center justify-between">
                <div id="tableInfo" class="text-xs text-gray-600"></div>
                <div id="tablePagination" class="flex items-center space-x-2"></div>
            </div>
        @else
            <div class="text-center py-12">
                <i class="fa-solid fa-briefcase text-gray-300 text-3xl mb-2"></i>
                <h3 class="text-base font-medium text-gray-900 mb-1">No vacancies yet</h3>
                <p class="text-xs text-gray-500 mb-4">Get started by creating your first vacancy.</p>
                <a href="{{ route('admin.vacancies.create') }}" 
                   class="px-5 py-2 rounded-md bg-primary text-white text-sm hover:bg-primary/80 transition-colors duration-200 inline-flex items-center space-x-2">
                    <i class="fa-solid fa-plus"></i>
                    <span>Add Vacancy</span>
                </a>
            </div>
        @endif
    </div>
</div>

{{-- Delete Confirmation Modal --}}
<div id="deleteModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/50">
    <div class="bg-white rounded-md p-6 w-full max-w-md mx-4">
        <div class="flex items-center space-x-3 mb-4">
            <div class="flex-shrink-0 w-10 h-10 bg-red-100 rounded-full flex items-center justify-center">
                <i class="fa-solid fa-exclamation-triangle text-red-600"></i>
            </div>
            <div>
                <h3 class="text-base font-medium text-gray-900">Delete Vacancy</h3>
                <p class="text-xs text-gray-600">Are you sure you want to delete this vacancy?</p>
            </div>
        </div>
        <div class="bg-gray-50 rounded-md p-3 mb-4 border border-gray-200">
            <p class="text-xs text-gray-700">
                <strong>Vacancy:</strong> <span id="vacancyTitle"></span>
            </p>
        </div>
        <div class="flex space-x-3">
            <button onclick="closeDeleteModal()" 
                    class="flex-1 px-4 py-2 text-sm text-gray-700 bg-white border border-gray-200 rounded-md hover:bg-gray-50 transition-colors duration-200">
                Cancel
            </button>
            <form id="deleteForm" method="POST" class="flex-1">
                @csrf
                @method('DELETE')
                <button type="submit" 
                        class="w-full px-4 py-2 text-sm bg-red-600 text-white rounded-md hover:bg-red-700 transition-colors duration-200">
                    Delete
                </button>
            </form>
        </div>
    </div>
</div>

<x-slot:scripts>
<script>
// Initialize Clean DataTable
document.addEventListener('DOMContentLoaded', function() {
    // Display session messages
    @if(session('success'))
        toastr.success('{{ session('success') }}');
    @endif

    @if(session('error'))
        toastr.error('{{ session('error') }}');
    @endif

    @if($errors->any())
        @foreach ($errors->all() as $error)
            toastr.error('{{ $error }}');
        @endforeach
    @endif
    if (typeof $ !== 'undefined' && $.fn.DataTable && document.getElementById('vacanciesTable')) {
        const table = $('#vacanciesTable').DataTable({
            pageLength: 10,
            responsive: true,
            order: [[5, 'desc']], // Order by Closing Date column
            dom: 't', // Only show table
            columnDefs: [
                {
                    targets: [6, 7], // Status and Actions columns
                    orderable: false
                }
            ],
            language: {
                zeroRecords: ""
            },
            drawCallback: function() {
                // Update custom info
                const api = this.api();
                const info = api.page.info();
                const infoElement = document.getElementById('tableInfo');
                if (infoElement) {
                    if (info.recordsTotal === 0) {
                        infoElement.textContent = 'No vacancies found';
                    } else if (info.recordsDisplay === 0) {
                        infoElement.textContent = 'No matching vacancies found';
                    } else {
                        infoElement.textContent = `Showing ${info.start + 1} to ${info.end} of ${info.recordsDisplay} vacancies`;
                        if (info.recordsDisplay !== info.recordsTotal) {
                            infoElement.textContent += ` (filtered from ${info.recordsTotal} total)`;
                        }
                    }
                }
                
                // Update custom pagination
                const paginationElement = document.getElementById('tablePagination');
                if (paginationElement && info.pages > 1) {
                    let paginationHtml = '';
                    
                    // Previous button
                    if (info.page > 0) {
                        paginationHtml += `<button data-action="previous" class="px-3 py-2 text-sm text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors duration-200 cursor-pointer">Previous</button>`;
                    } else {
                        paginationHtml += `<button disabled class="px-3 py-2 text-sm text-gray-400 bg-gray-100 border border-gray-200 rounded-lg cursor-not-allowed">Previous</button>`;
                    }
                    
                    // Page numbers (show max 5 pages)
                    const startPage = Math.max(0, info.page - 2);
                    const endPage = Math.min(info.pages - 1, info.page + 2);
                    
                    for (let i = startPage; i <= endPage; i++) {
                        if (i === info.page) {
                            paginationHtml += `<button class="px-3 py-2 text-sm text-white bg-primary border border-primary rounded-lg">${i + 1}</button>`;
                        } else {
                            paginationHtml += `<button data-action="page" data-page="${i}" class="px-3 py-2 text-sm text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors duration-200 cursor-pointer">${i + 1}</button>`;
                        }
                    }
                    
                    // Next button
                    if (info.page < info.pages - 1) {
                        paginationHtml += `<button data-action="next" class="px-3 py-2 text-sm text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors duration-200 cursor-pointer">Next</button>`;
                    } else {
                        paginationHtml += `<button disabled class="px-3 py-2 text-sm text-gray-400 bg-gray-100 border border-gray-200 rounded-lg cursor-not-allowed">Next</button>`;
                    }
                    
                    paginationElement.innerHTML = paginationHtml;
                    
                    // Add event listeners to pagination buttons after they are created
                    paginationElement.querySelectorAll('button[data-action]').forEach(button => {
                        button.addEventListener('click', function(e) {
                            e.preventDefault();
                            const action = this.getAttribute('data-action');
                            
                            if (action === 'previous') {
                                table.page('previous').draw('page');
                            } else if (action === 'next') {
                                table.page('next').draw('page');
                            } else if (action === 'page') {
                                const page = parseInt(this.getAttribute('data-page'));
                                table.page(page).draw('page');
                            }
                        });
                    });
                } else if (paginationElement) {
                    paginationElement.innerHTML = '';
                }
            }
        });
        
        // Make table accessible globally for pagination
        window.vacanciesTable = table;
        
        // Custom search
        document.getElementById('searchInput').addEventListener('input', function() {
            table.search(this.value).draw();
        });
        
        // Custom page length
        document.getElementById('pageLength').addEventListener('change', function() {
            table.page.len(parseInt(this.value)).draw();
        });
        
        // Initial draw to populate info and pagination
        table.draw();
    } else {
        console.warn('DataTables not available or table element not found');
        // Hide custom controls if DataTables isn't working
        const searchInput = document.getElementById('searchInput');
        const pageLength = document.getElementById('pageLength');
        if (searchInput) searchInput.style.display = 'none';
        if (pageLength) pageLength.style.display = 'none';
    }
});

function toggleStatus(vacancyId, currentStatus) {
    fetch(`/admin/vacancies/${vacancyId}/update-status`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({
            is_active: !currentStatus
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            const toggle = document.querySelector(`[data-vacancy-id="${vacancyId}"]`);
            const span = toggle.querySelector('span:last-child');
            
            if (data.is_active) {
                toggle.classList.remove('bg-gray-200');
                toggle.classList.add('bg-primary');
                span.classList.remove('translate-x-1');
                span.classList.add('translate-x-6');
                toastr.success('Vacancy activated successfully!');
            } else {
                toggle.classList.remove('bg-primary');
                toggle.classList.add('bg-gray-200');
                span.classList.remove('translate-x-6');
                span.classList.add('translate-x-1');
                toastr.warning('Vacancy deactivated successfully!');
            }
        }
    })
    .catch(error => {
        console.error('Error:', error);
        toastr.error('An error occurred while updating the status.');
    });
}

function deleteVacancy(vacancyId, vacancyTitle) {
    document.getElementById('vacancyTitle').textContent = vacancyTitle;
    document.getElementById('deleteForm').action = `/admin/vacancies/${vacancyId}`;
    document.getElementById('deleteModal').classList.remove('hidden');
    document.getElementById('deleteModal').classList.add('flex');
}

function closeDeleteModal() {
    document.getElementById('deleteModal').classList.add('hidden');
    document.getElementById('deleteModal').classList.remove('flex');
}

// Close modal when clicking outside
document.getElementById('deleteModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeDeleteModal();
    }
});
</script>
</x-slot:scripts>
</x-layouts.admin>

