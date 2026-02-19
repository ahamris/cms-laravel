<x-layouts.admin title="Job Applications">
<div class="space-y-6">
    {{-- Header --}}
    <div class="flex items-center justify-between">
        <div class="flex items-center gap-3">
            <div class="w-12 h-12 bg-primary rounded-md flex items-center justify-center">
                <i class="fa-solid fa-file-alt text-white text-base"></i>
            </div>
            <div class="flex flex-col gap-1">
                <h2>Job Applications</h2>
                <p>Manage job applications and candidates</p>
                @if($selectedVacancy)
                    <p class="text-xs text-gray-500">Filtered by: <strong>{{ $selectedVacancy->title }}</strong></p>
                @endif
            </div>
        </div>
        <div class="flex items-center space-x-2">
            @if($selectedVacancy)
                <a href="{{ route('admin.job-applications.index') }}" 
                   class="px-5 py-2 rounded-md bg-white border border-gray-200 text-gray-700 text-sm hover:bg-gray-50 transition-colors duration-200 flex items-center space-x-2">
                    <i class="fa-solid fa-filter-circle-xmark"></i>
                    <span>Clear Filter</span>
                </a>
            @endif
            <a href="{{ route('admin.job-applications.create') }}" 
               class="px-5 py-2 rounded-md bg-primary text-white text-sm hover:bg-primary/80 transition-colors duration-200 flex items-center space-x-2">
                <i class="fa-solid fa-plus"></i>
                <span>Add Application</span>
            </a>
        </div>
    </div>

    {{-- Stats Cards --}}
    <div class="grid grid-cols-1 md:grid-cols-6 gap-4">
        <div class="bg-white rounded-md border border-gray-200 p-4">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs text-gray-500 mb-1">Total</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $stats['total'] }}</p>
                </div>
                <div class="w-12 h-12 bg-blue-100 rounded-md flex items-center justify-center">
                    <i class="fa-solid fa-file-alt text-blue-600"></i>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-md border border-gray-200 p-4">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs text-gray-500 mb-1">Pending</p>
                    <p class="text-2xl font-bold text-yellow-600">{{ $stats['pending'] }}</p>
                </div>
                <div class="w-12 h-12 bg-yellow-100 rounded-md flex items-center justify-center">
                    <i class="fa-solid fa-clock text-yellow-600"></i>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-md border border-gray-200 p-4">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs text-gray-500 mb-1">Reviewed</p>
                    <p class="text-2xl font-bold text-blue-600">{{ $stats['reviewed'] }}</p>
                </div>
                <div class="w-12 h-12 bg-blue-100 rounded-md flex items-center justify-center">
                    <i class="fa-solid fa-eye text-blue-600"></i>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-md border border-gray-200 p-4">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs text-gray-500 mb-1">Shortlisted</p>
                    <p class="text-2xl font-bold text-purple-600">{{ $stats['shortlisted'] }}</p>
                </div>
                <div class="w-12 h-12 bg-purple-100 rounded-md flex items-center justify-center">
                    <i class="fa-solid fa-star text-purple-600"></i>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-md border border-gray-200 p-4">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs text-gray-500 mb-1">Rejected</p>
                    <p class="text-2xl font-bold text-red-600">{{ $stats['rejected'] }}</p>
                </div>
                <div class="w-12 h-12 bg-red-100 rounded-md flex items-center justify-center">
                    <i class="fa-solid fa-times-circle text-red-600"></i>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-md border border-gray-200 p-4">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs text-gray-500 mb-1">Hired</p>
                    <p class="text-2xl font-bold text-green-600">{{ $stats['hired'] }}</p>
                </div>
                <div class="w-12 h-12 bg-green-100 rounded-md flex items-center justify-center">
                    <i class="fa-solid fa-check-circle text-green-600"></i>
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

    {{-- Applications Table --}}
    <div class="bg-gray-50/50 rounded-md border border-gray-200">
        @if($applications->count() > 0)
            {{-- Custom Controls Header --}}
            <div class="p-4 border-b border-gray-200 bg-gray-50/80 flex items-center justify-between">
                <div class="relative">
                    <i class="fa-solid fa-search absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400 text-xs"></i>
                    <input type="text" 
                           id="searchInput" 
                           placeholder="Search applications..." 
                           class="pl-10 pr-4 py-2 h-9 text-sm bg-white border border-gray-200 rounded-md focus:outline-none">
                </div>
                <select id="pageLength" class="h-9 text-sm bg-white border border-gray-200 rounded-md px-3 focus:outline-none">
                    <option value="10">Show 10 applications</option>
                    <option value="25">Show 25 applications</option>
                    <option value="50">Show 50 applications</option>
                    <option value="100">Show 100 applications</option>
                </select>
            </div>
            
            <div class="overflow-x-auto">
                <table id="applicationsTable" class="w-full">
                    <thead class="bg-gray-50/80 border-b border-gray-200">
                        <tr>
                            <th class="text-left py-3 px-4 text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                            <th class="text-left py-3 px-4 text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                            <th class="text-left py-3 px-4 text-xs font-medium text-gray-500 uppercase tracking-wider">Vacancy</th>
                            <th class="text-left py-3 px-4 text-xs font-medium text-gray-500 uppercase tracking-wider">Phone</th>
                            <th class="text-left py-3 px-4 text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            <th class="text-left py-3 px-4 text-xs font-medium text-gray-500 uppercase tracking-wider">Processed</th>
                            <th class="text-left py-3 px-4 text-xs font-medium text-gray-500 uppercase tracking-wider">Applied</th>
                            <th class="text-left py-3 px-4 text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($applications as $application)
                            <tr class="hover:bg-gray-50 transition-colors duration-200">
                                <td class="py-3 px-4">
                                    <div class="text-xs font-medium text-gray-900">{{ $application->name }}</div>
                                </td>
                                <td class="py-3 px-4">
                                    <div class="text-xs text-gray-900">{{ $application->email }}</div>
                                </td>
                                <td class="py-3 px-4">
                                    @if($application->vacancy)
                                        <a href="{{ route('admin.vacancies.show', $application->vacancy) }}" 
                                           class="text-xs text-primary hover:text-primary/80 transition-colors duration-200">
                                            {{ $application->vacancy->title }}
                                        </a>
                                    @else
                                        <span class="text-xs text-gray-500">N/A</span>
                                    @endif
                                </td>
                                <td class="py-3 px-4">
                                    <span class="text-xs text-gray-900">{{ $application->phone ?? 'N/A' }}</span>
                                </td>
                                <td class="py-3 px-4">
                                    @php
                                        $statusColors = [
                                            'pending' => 'bg-yellow-100 text-yellow-800',
                                            'reviewed' => 'bg-blue-100 text-blue-800',
                                            'shortlisted' => 'bg-purple-100 text-purple-800',
                                            'rejected' => 'bg-red-100 text-red-800',
                                            'hired' => 'bg-green-100 text-green-800',
                                        ];
                                    @endphp
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $statusColors[$application->status] ?? 'bg-gray-100 text-gray-800' }}">
                                        {{ ucfirst($application->status) }}
                                    </span>
                                </td>
                                <td class="py-3 px-4">
                                    <button onclick="toggleProcessed({{ $application->id }}, {{ $application->is_processed ? 'true' : 'false' }})" 
                                            class="processed-toggle relative inline-flex h-6 w-11 items-center rounded-full transition-colors focus:outline-none {{ $application->is_processed ? 'bg-green-500' : 'bg-gray-200' }}"
                                            data-application-id="{{ $application->id }}">
                                        <span class="sr-only">Toggle processed</span>
                                        <span class="inline-block h-4 w-4 transform rounded-full bg-white transition-transform {{ $application->is_processed ? 'translate-x-6' : 'translate-x-1' }}"></span>
                                    </button>
                                </td>
                                <td class="py-3 px-4">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                        {{ $application->created_at->format('M d, Y') }}
                                    </span>
                                </td>
                                <td class="py-3 px-4">
                                    <div class="flex items-center space-x-2">
                                        <a href="{{ route('admin.job-applications.show', $application) }}" 
                                           class="text-xs text-gray-600 hover:text-primary transition-colors duration-200" 
                                           title="View">
                                            <i class="fa-solid fa-eye"></i>
                                        </a>
                                        <a href="{{ route('admin.job-applications.edit', $application) }}" 
                                           class="text-xs text-gray-600 hover:text-blue-600 transition-colors duration-200" 
                                           title="Edit">
                                            <i class="fa-solid fa-edit"></i>
                                        </a>
                                        <button onclick="deleteApplication({{ $application->id }}, '{{ addslashes($application->name) }}')" 
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
                <i class="fa-solid fa-file-alt text-gray-300 text-3xl mb-2"></i>
                <h3 class="text-base font-medium text-gray-900 mb-1">No applications yet</h3>
                <p class="text-xs text-gray-500 mb-4">Get started by creating your first job application.</p>
                <a href="{{ route('admin.job-applications.create') }}" 
                   class="px-5 py-2 rounded-md bg-primary text-white text-sm hover:bg-primary/80 transition-colors duration-200 inline-flex items-center space-x-2">
                    <i class="fa-solid fa-plus"></i>
                    <span>Add Application</span>
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
                <h3 class="text-base font-medium text-gray-900">Delete Application</h3>
                <p class="text-xs text-gray-600">Are you sure you want to delete this application?</p>
            </div>
        </div>
        <div class="bg-gray-50 rounded-md p-3 mb-4 border border-gray-200">
            <p class="text-xs text-gray-700">
                <strong>Applicant:</strong> <span id="applicationName"></span>
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
    if (typeof $ !== 'undefined' && $.fn.DataTable && document.getElementById('applicationsTable')) {
        const table = $('#applicationsTable').DataTable({
            pageLength: 10,
            responsive: true,
            order: [[6, 'desc']], // Order by Applied column
            dom: 't', // Only show table
            columnDefs: [
                {
                    targets: [5, 7], // Processed and Actions columns
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
                        infoElement.textContent = 'No applications found';
                    } else if (info.recordsDisplay === 0) {
                        infoElement.textContent = 'No matching applications found';
                    } else {
                        infoElement.textContent = `Showing ${info.start + 1} to ${info.end} of ${info.recordsDisplay} applications`;
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
        window.applicationsTable = table;
        
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

function toggleProcessed(applicationId, currentStatus) {
    fetch(`/admin/job-applications/${applicationId}/toggle-processed`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            const toggle = document.querySelector(`[data-application-id="${applicationId}"]`);
            const span = toggle.querySelector('span:last-child');
            
            if (data.is_processed) {
                toggle.classList.remove('bg-gray-200');
                toggle.classList.add('bg-green-500');
                span.classList.remove('translate-x-1');
                span.classList.add('translate-x-6');
                toastr.success('Application marked as processed!');
            } else {
                toggle.classList.remove('bg-green-500');
                toggle.classList.add('bg-gray-200');
                span.classList.remove('translate-x-6');
                span.classList.add('translate-x-1');
                toastr.info('Application marked as unprocessed!');
            }
        }
    })
    .catch(error => {
        console.error('Error:', error);
        toastr.error('An error occurred while updating the processed status.');
    });
}

function deleteApplication(applicationId, applicationName) {
    document.getElementById('applicationName').textContent = applicationName;
    document.getElementById('deleteForm').action = `/admin/job-applications/${applicationId}`;
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

