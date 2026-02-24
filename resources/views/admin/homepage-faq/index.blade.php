<x-layouts.admin title="FAQ Groups">
<div>
    <!-- Page Header -->
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Contact Page FAQ</h1>
            <p class="text-gray-600 mt-1">Manage the frequently asked questions on the contact page (identifier: contact)</p>
        </div>
    </div>

    <!-- FAQs Table -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200">
        <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
            <h3 class="text-lg font-semibold text-gray-900">Contact FAQ</h3>
            <span class="bg-blue-100 text-blue-800 px-3 py-1 rounded-full text-sm font-medium">{{ $faqs->total() }} Total</span>
        </div>
        <div class="p-6">
            @if($faqs->count() > 0)
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-16">#</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Identifier</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Title & Subtitle</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-24 text-center">Items</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-40">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($faqs as $faq)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $faq->id }}</td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center">
                                        <span class="bg-blue-100 text-blue-800 px-3 py-1 rounded-full text-xs font-mono font-semibold">{{ $faq->identifier }}</span>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    @if($faq->title)
                                        <div class="font-semibold text-gray-900">{{ $faq->title }}</div>
                                    @else
                                        <div class="text-gray-400 italic">No title</div>
                                    @endif
                                    @if($faq->subtitle)
                                        <div class="text-sm text-gray-500 mt-1">{{ $faq->subtitle }}</div>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-center">
                                    <span class="inline-flex items-center justify-center bg-green-100 text-green-800 px-3 py-1 rounded-full text-sm font-bold">
                                        {{ is_array($faq->items) ? count($faq->items) : 0 }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex space-x-2">
                                        <a href="{{ route('admin.faq-module.show', ['faq' => $faq]) }}" 
                                           class="bg-blue-100 hover:bg-blue-200 text-blue-700 p-2 rounded-lg transition-colors" title="View">
                                            <i class="fas fa-eye text-sm"></i>
                                        </a>
                                        <a href="{{ route('admin.faq-module.edit', ['faq' => $faq]) }}" 
                                           class="bg-yellow-100 hover:bg-yellow-200 text-yellow-700 p-2 rounded-lg transition-colors" title="Edit">
                                            <i class="fas fa-edit text-sm"></i>
                                        </a>
                                        <button type="button" class="bg-red-100 hover:bg-red-200 text-red-700 p-2 rounded-lg transition-colors" 
                                                data-delete-url="{{ route('admin.faq-module.destroy', ['faq' => $faq]) }}"
                                                onclick="confirmDelete(this)" title="Delete">
                                            <i class="fas fa-trash text-sm"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="flex justify-between items-center mt-6 pt-4 border-t border-gray-200">
                    <div class="text-sm text-gray-700">
                        Showing {{ $faqs->firstItem() }} to {{ $faqs->lastItem() }} of {{ $faqs->total() }} results
                    </div>
                    <div>
                        {{ $faqs->links() }}
                    </div>
                </div>
            @else
                <div class="text-center py-12">
                    <i class="fas fa-question-circle text-6xl text-gray-300 mb-4"></i>
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">No Contact FAQ Found</h3>
                    <p class="text-gray-600 mb-6">Run the FaqSeeder to create the contact page FAQ: <code class="bg-gray-100 px-2 py-1 rounded">php artisan db:seed --class=FaqSeeder</code></p>
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div x-data="{ open: false }" x-show="open" id="deleteModal" class="fixed inset-0 z-50 overflow-y-auto" style="display: none;">
    <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
        <div class="inline-block px-4 pt-5 pb-4 overflow-hidden text-left align-bottom transition-all transform bg-white rounded-lg shadow-xl sm:my-8 sm:align-middle sm:max-w-lg sm:w-full sm:p-6">
            <div class="sm:flex sm:items-start">
                <div class="flex items-center justify-center flex-shrink-0 w-12 h-12 mx-auto bg-red-100 rounded-full sm:mx-0 sm:h-10 sm:w-10">
                    <i class="fas fa-exclamation-triangle text-red-600"></i>
                </div>
                <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                    <h3 class="text-lg font-medium leading-6 text-gray-900">Confirm Delete</h3>
                    <div class="mt-2">
                        <p class="text-sm text-gray-500">Are you sure you want to delete this FAQ group? This will delete all FAQ items within this group. This action cannot be undone.</p>
                    </div>
                </div>
            </div>
            <div class="mt-5 sm:mt-4 sm:flex sm:flex-row-reverse">
                <form id="deleteForm" method="POST" class="inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="w-full px-4 py-2 text-base font-medium text-white bg-red-600 border border-transparent rounded-md shadow-sm hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:ml-3 sm:w-auto sm:text-sm">
                        Delete
                    </button>
                </form>
                <button type="button" @click="open = false" class="w-full px-4 py-2 mt-3 text-base font-medium text-gray-700 bg-white border border-gray-300 rounded-md shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:mt-0 sm:w-auto sm:text-sm">
                    Cancel
                </button>
            </div>
        </div>
    </div>
</div>

<script>
function confirmDelete(btn) {
    const url = btn && btn.dataset && btn.dataset.deleteUrl;
    if (!url) return;
    const deleteForm = document.getElementById('deleteForm');
    deleteForm.action = url;
    const modal = document.getElementById('deleteModal');
    if (modal && modal.__x) {
        modal.__x.$data.open = true;
    }
    modal.style.display = 'block';
}
</script>
</x-layouts.admin>
