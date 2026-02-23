<x-layouts.admin title="External Codes">
<div class="space-y-6">
    {{-- Header --}}
    <div class="flex items-center justify-between">
        <div class="flex items-center gap-3">
            <div class="w-12 h-12 bg-primary rounded-md flex items-center justify-center">
                <i class="fa-solid fa-code text-white text-base"></i>
            </div>
            <div class="flex flex-col gap-1">
                <h2>External Codes</h2>
                <p>Manage external code snippets for header and body injection</p>
            </div>
        </div>
        <a href="{{ route('admin.external-code.create') }}"
           class="px-5 py-2 rounded-md bg-primary text-white text-sm">
            <i class="fa-solid fa-plus mr-2"></i>
            Create New External Code
        </a>
    </div>

    {{-- External Codes List --}}
    <div class="bg-gray-50/50 rounded-md border border-gray-200 overflow-hidden">
        @if($externalCodes->count() > 0)
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50/80">
                        <tr>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Order
                            </th>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Name
                            </th>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Injection Points
                            </th>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Status
                            </th>
                            <th class="px-4 py-2 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Actions
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-100">
                        @foreach($externalCodes as $externalCode)
                            <tr class="hover:bg-gray-50">
                                <td class="px-4 py-3 whitespace-nowrap">
                                    <span class="text-xs font-medium text-gray-900">#{{ $externalCode->sort_order }}</span>
                                </td>
                                <td class="px-4 py-3">
                                    <div class="text-sm font-medium text-gray-900">{{ $externalCode->name }}</div>
                                    <div class="text-xs text-gray-500">{{ Str::limit(strip_tags($externalCode->content), 50) }}</div>
                                </td>
                                <td class="px-4 py-3 whitespace-nowrap">
                                    <div class="flex flex-wrap gap-1">
                                        @if($externalCode->before_header)
                                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                                <i class="fa-solid fa-code mr-1"></i>
                                                Header
                                            </span>
                                        @endif
                                        @if($externalCode->before_body)
                                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                <i class="fa-solid fa-code mr-1"></i>
                                                Body
                                            </span>
                                        @endif
                                    </div>
                                </td>
                                <td class="px-4 py-3 whitespace-nowrap">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $externalCode->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                        <i class="fa-solid {{ $externalCode->is_active ? 'fa-check-circle' : 'fa-times-circle' }} mr-1"></i>
                                        {{ $externalCode->is_active ? 'Active' : 'Inactive' }}
                                    </span>
                                </td>
                                <td class="px-4 py-3 whitespace-nowrap text-right text-sm font-medium">
                                    <div class="flex items-center justify-end space-x-2">
                                        <a href="{{ route('admin.external-code.show', $externalCode) }}"
                                           class="text-primary hover:text-primary/80"
                                           title="View">
                                            <i class="fa-solid fa-eye"></i>
                                        </a>
                                        <a href="{{ route('admin.external-code.edit', $externalCode) }}"
                                           class="text-primary hover:text-primary/80"
                                           title="Edit">
                                            <i class="fa-solid fa-edit"></i>
                                        </a>
                                        <button onclick="deleteExternalCode({{ $externalCode->id }})"
                                                class="text-red-600 hover:text-red-800"
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

            {{-- Pagination --}}
            <div class="px-6 py-4 border-t border-gray-200">
                {{ $externalCodes->links() }}
            </div>
        @else
            <div class="text-center py-12">
                <i class="fa-solid fa-code text-4xl text-gray-400 mb-4"></i>
                <p class="text-gray-600">No external codes found. Create your first external code!</p>
            </div>
        @endif
    </div>
</div>

{{-- Delete Confirmation Modal --}}
<div id="deleteModal" class="fixed inset-0 bg-black/50 hidden z-50 flex items-center justify-center">
    <div class="bg-white rounded-md border border-gray-200 shadow-xl max-w-md w-full mx-4">
        <div class="p-6">
            <div class="flex items-center mb-3">
                <i class="fa-solid fa-exclamation-triangle text-red-500 text-xl mr-3"></i>
                <h3 class="text-base font-semibold text-gray-900">Delete External Code</h3>
            </div>
            <p class="text-gray-600 text-sm mb-4">
                Are you sure you want to delete this external code? This action cannot be undone.
            </p>
            <div class="flex items-center justify-end space-x-3 border-t border-gray-200 pt-4">
                <button type="button"
                        onclick="closeDeleteModal()"
                        class="px-5 py-2 rounded-md bg-white border border-gray-200 text-gray-700 text-sm">
                    Cancel
                </button>
                <form id="deleteForm" method="POST" class="inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit"
                            class="px-5 py-2 rounded-md bg-red-600 text-white text-sm">
                        Delete
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
function deleteExternalCode(externalCodeId) {
    document.getElementById('deleteForm').action = `/admin/content/external-code/${externalCodeId}`;
    document.getElementById('deleteModal').classList.remove('hidden');
}

function closeDeleteModal() {
    document.getElementById('deleteModal').classList.add('hidden');
}

// Close modal when clicking outside
document.getElementById('deleteModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeDeleteModal();
    }
});

// Display session messages
document.addEventListener('DOMContentLoaded', function() {
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
});
</script>
</x-layouts.admin>
