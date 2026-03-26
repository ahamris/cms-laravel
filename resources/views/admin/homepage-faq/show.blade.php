<x-layouts.admin-faq-hub title="FAQ group" active="groups">
<div class="space-y-6">
    {{-- Header --}}
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">{{ $faq->title ?? 'FAQ Group' }}</h1>
            <p class="text-gray-600">FAQ Group Details</p>
        </div>
        <div class="flex items-center space-x-3">
            <a href="{{ route('admin.faq-module.edit', ['faq' => $faq]) }}"
               class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition-colors duration-200">
                <i class="fa-solid fa-edit mr-2"></i>
                Edit FAQ Group
            </a>
            <a href="{{ route('admin.faq-module.index') }}"
               class="bg-gray-600 text-white px-4 py-2 rounded-lg hover:bg-gray-700 transition-colors duration-200">
                <i class="fa-solid fa-arrow-left mr-2"></i>
                Back to List
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        {{-- Main Content --}}
        <div class="lg:col-span-2 space-y-6">
            {{-- Group Information --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-200">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900">Basic Information</h3>
                </div>
                <div class="p-6 space-y-4">
                    <!-- Identifier -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Identifier</label>
                        <div class="flex items-center space-x-2">
                            <span class="bg-blue-100 text-blue-800 px-3 py-1 rounded-full text-sm font-mono font-semibold">{{ $faq->identifier }}</span>
                            <button class="text-gray-500 hover:text-gray-700 p-1" onclick="copyToClipboard('{{ $faq->identifier }}')"
 title="Copy identifier">
                                <i class="fas fa-copy text-sm"></i>
                            </button>
                        </div>
                    </div>

                    <!-- Title -->
                    @if($faq->title)
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Title</label>
                        <div class="bg-gray-50 border border-gray-200 rounded-lg p-3">
                            <p class="text-gray-900 font-semibold">{{ $faq->title }}</p>
                        </div>
                    </div>
                    @endif

                    <!-- Subtitle -->
                    @if($faq->subtitle)
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Subtitle</label>
                        <div class="bg-gray-50 border border-gray-200 rounded-lg p-3">
                            <p class="text-gray-600">{{ $faq->subtitle }}</p>
                        </div>
                    </div>
                    @endif
                </div>
            </div>

            {{-- FAQ Items --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-200">
                <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
                    <h3 class="text-lg font-semibold text-gray-900">FAQ Items</h3>
                    <span class="bg-green-100 text-green-800 px-3 py-1 rounded-full text-sm font-bold">
                        {{ is_array($faq->items) ? count($faq->items) : 0 }} Items
                    </span>
                </div>
                <div class="p-6">
                    @if(is_array($faq->items) && count($faq->items) > 0)
                        <div class="space-y-4">
                            @foreach($faq->items as $index => $item)
                            <div class="border border-gray-300 rounded-lg p-4 bg-gray-50">
                                <div class="flex items-start justify-between mb-3">
                                    <h4 class="text-md font-semibold text-gray-900">FAQ Item #{{ $index + 1 }}</h4>
                                    <span class="bg-blue-100 text-blue-800 px-2 py-1 rounded text-xs font-medium">Item {{ $index + 1 }}</span>
                                </div>
                                
                                <!-- Question -->
                                <div class="mb-3">
                                    <label class="block text-xs font-medium text-gray-600 mb-1">Question</label>
                                    <div class="bg-white border border-gray-200 rounded-lg p-3">
                                        <p class="text-gray-900 font-medium">{{ $item['question'] ?? 'No question' }}</p>
                                    </div>
                                </div>
                                
                                <!-- Answer -->
                                <div>
                                    <label class="block text-xs font-medium text-gray-600 mb-1">Answer</label>
                                    <div class="bg-white border border-gray-200 rounded-lg p-3">
                                        <p class="text-gray-700 leading-relaxed">{{ $item['answer'] ?? 'No answer' }}</p>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-8">
                            <i class="fas fa-inbox text-4xl text-gray-300 mb-3"></i>
                            <p class="text-gray-500">No FAQ items found in this group.</p>
                        </div>
                    @endif
                </div>
            </div>

            {{-- Frontend Preview --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-200">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900">Frontend Preview</h3>
                </div>
                <div class="p-6">
                    <p class="text-gray-600 mb-4">This is how the FAQ group will appear on the frontend:</p>
                    
                    @if($faq->title)
                    <div class="mb-4">
                        <h2 class="text-2xl font-bold text-gray-900">{{ $faq->title }}</h2>
                        @if($faq->subtitle)
                        <p class="text-gray-600 mt-1">{{ $faq->subtitle }}</p>
                        @endif
                    </div>
                    @endif
                    
                    <!-- FAQ Items Preview -->
                    @if(is_array($faq->items) && count($faq->items) > 0)
                    <div class="space-y-2">
                        @foreach($faq->items as $index => $item)
                        <div class="bg-white border border-gray-200 rounded-lg overflow-hidden" x-data="{ open: false }">
                            <button @click="open = !open" class="w-full px-6 py-4 text-left flex items-center justify-between hover:bg-gray-50 transition-colors duration-200">
                                <span class="text-lg font-semibold text-gray-900">{{ $item['question'] ?? 'No question' }}</span>
                                <i class="fas fa-plus text-gray-500 transition-transform duration-200" :class="{ 'rotate-45': open }"></i>
                            </button>
                            <div x-show="open" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="px-6 py-4 border-t border-gray-200">
                                <div class="text-gray-600 leading-relaxed">
                                    {{ $item['answer'] ?? 'No answer' }}
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    @else
                    <p class="text-gray-500 text-center py-4">No FAQ items to preview.</p>
                    @endif
                </div>
            </div>
        </div>

        {{-- Sidebar --}}
        <div class="space-y-6">
            {{-- Statistics --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-200">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900">Statistics</h3>
                </div>
                <div class="p-6 space-y-3">
                    <div class="flex justify-between items-center">
                        <span class="text-sm font-medium text-gray-700">Total Items:</span>
                        <span class="bg-green-100 text-green-800 px-3 py-1 rounded-full text-sm font-bold">{{ is_array($faq->items) ? count($faq->items) : 0 }}</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-sm font-medium text-gray-700">Identifier:</span>
                        <span class="bg-blue-100 text-blue-800 px-2 py-1 rounded text-xs font-mono">{{ $faq->identifier }}</span>
                    </div>
                </div>
            </div>

            {{-- Metadata --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-200">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900">Metadata</h3>
                </div>
                <div class="p-6 space-y-3">
                    <div class="flex justify-between items-center">
                        <span class="text-sm font-medium text-gray-700">ID:</span>
                        <div class="flex items-center space-x-2">
                            <span class="bg-gray-100 text-gray-800 px-2 py-1 rounded text-sm font-medium">{{ $faq->id }}</span>
                            <button class="text-gray-500 hover:text-gray-700 p-1" onclick="copyToClipboard('{{ $faq->id }}')" title="Copy ID">
                                <i class="fas fa-copy text-sm"></i>
                            </button>
                        </div>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-sm font-medium text-gray-700">Created:</span>
                        <span class="text-sm text-gray-600">{{ $faq->created_at->format('M d, Y H:i') }}</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-sm font-medium text-gray-700">Updated:</span>
                        <span class="text-sm text-gray-600">{{ $faq->updated_at->format('M d, Y H:i') }}</span>
                    </div>
                </div>
            </div>

            {{-- Actions --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-200">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900">Actions</h3>
                </div>
                <div class="p-6 space-y-3">
                    <button type="button" onclick="confirmDelete()" class="w-full bg-red-600 text-white px-4 py-2 rounded-lg hover:bg-red-700 transition-colors duration-200">
                        <i class="fa-solid fa-trash mr-2"></i>
                        Delete FAQ Group
                    </button>
                </div>
            </div>
        </div>
        </div>
    </div>
</div>

{{-- Delete Confirmation Modal --}}
<div x-data="{ open: false }" x-show="open" id="deleteModal" class="fixed inset-0 z-50 overflow-y-auto" style="display: none;">
    <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 transition-opacity bg-gray-500 bg-opacity-75" @click="open = false"></div>
        <div class="inline-block px-4 pt-5 pb-4 overflow-hidden text-left align-bottom transition-all transform bg-white rounded-lg shadow-xl sm:my-8 sm:align-middle sm:max-w-lg sm:w-full sm:p-6">
            <div class="sm:flex sm:items-start">
                <div class="flex items-center justify-center flex-shrink-0 w-12 h-12 mx-auto bg-red-100 rounded-full sm:mx-0 sm:h-10 sm:w-10">
                    <i class="fas fa-exclamation-triangle text-red-600"></i>
                </div>
                <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                    <h3 class="text-lg font-medium leading-6 text-gray-900">Confirm Delete</h3>
                    <div class="mt-2">
                        <p class="text-sm text-gray-500 mb-3">Are you sure you want to delete this FAQ group?</p>
                        <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-3 mb-3">
                            <p class="text-sm font-medium text-yellow-800">Identifier: {{ $faq->identifier }}</p>
                            <p class="text-sm text-yellow-700 mt-1">This will delete all {{ is_array($faq->items) ? count($faq->items) : 0 }} FAQ items in this group.</p>
                        </div>
                        <p class="text-sm text-gray-500">This action cannot be undone.</p>
                    </div>
                </div>
            </div>
            <div class="mt-5 sm:mt-4 sm:flex sm:flex-row-reverse">
                <form action="{{ route('admin.faq-module.destroy', ['faq' => $faq]) }}" method="POST" class="inline">
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
@endsection


    <script>
function confirmDelete() {
    document.getElementById('deleteModal').style.display = 'block';
    document.getElementById('deleteModal').__x.$data.open = true;
}

function copyToClipboard(text) {
    navigator.clipboard.writeText(text).then(function() {
        // You can add a toast notification here
        // Intentionally no console logging (keeps production logs clean).
    });
}
</script>
    </script>
</x-layouts.admin-faq-hub>
