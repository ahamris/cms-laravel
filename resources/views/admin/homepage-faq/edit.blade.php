<x-layouts.admin-faq-hub title="Edit FAQ group" active="groups">
<div class="space-y-6">
    {{-- Header --}}
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Edit FAQ Group</h1>
            <p class="text-gray-600">Update FAQ group: {{ $faq->title ?? $faq->identifier }}</p>
        </div>
        <a href="{{ route('admin.faq-module.index') }}"
           class="bg-gray-600 text-white px-4 py-2 rounded-lg hover:bg-gray-700 transition-colors duration-200">
            <i class="fa-solid fa-arrow-left mr-2"></i>
            Back to List
        </a>
    </div>

    {{-- Form --}}
    <form action="{{ route('admin.faq-module.update', ['faq' => $faq]) }}" method="POST" class="space-y-6">
        @csrf
        @method('PUT')
        
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            {{-- Main Content --}}
            <div class="lg:col-span-2 space-y-6">
                {{-- Basic Information --}}
                <div class="bg-white rounded-xl shadow-sm border border-gray-200">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-900">Basic Information</h3>
                    </div>
                    <div class="p-6 space-y-4">
                        {{-- Identifier (hidden, default: contact for contact page FAQ) --}}
                        <input type="hidden" name="identifier" value="{{ old('identifier', $faq->identifier) }}">
                        @error('identifier')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror

                        {{-- Title --}}
                        <div>
                            <label for="title" class="block text-sm font-medium text-gray-700 mb-1">
                                Title
                            </label>
                            <input type="text" 
                                   id="title" 
                                   name="title" 
                                   value="{{ old('title', $faq->title) }}" 
                                   placeholder="e.g., Veelgestelde Vragen"
                                   maxlength="255"
                                   class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-primary focus:border-primary @error('title') border-red-500 @enderror">
                            @error('title')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                            <p class="text-xs text-gray-500 mt-1">Display title for this FAQ group</p>
                        </div>

                        {{-- Subtitle --}}
                        <div>
                            <label for="subtitle" class="block text-sm font-medium text-gray-700 mb-1">
                                Subtitle
                            </label>
                            <input type="text" 
                                   id="subtitle" 
                                   name="subtitle" 
                                   value="{{ old('subtitle', $faq->subtitle) }}" 
                                   placeholder="e.g., Alles wat je moet weten"
                                   maxlength="255"
                                   class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-primary focus:border-primary @error('subtitle') border-red-500 @enderror">
                            @error('subtitle')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                            <p class="text-xs text-gray-500 mt-1">Optional subtitle for this FAQ group</p>
                        </div>
                    </div>
                </div>

                {{-- FAQ Items --}}
                <div class="bg-white rounded-xl shadow-sm border border-gray-200">
                    <div class="px-6 py-4 border-b border-gray-200 flex items-center justify-between">
                        <h3 class="text-lg font-semibold text-gray-900">FAQ Items</h3>
                        <button type="button" onclick="addFaqItem()" class="bg-primary text-white px-3 py-1 rounded-lg hover:bg-primary/80 transition-colors duration-200 text-sm">
                            <i class="fa-solid fa-plus mr-1"></i>
                            Add FAQ Item
                        </button>
                    </div>
                    <div class="p-6">
                        <div id="faqItemsContainer" class="space-y-4">
                            {{-- FAQ items will be added here dynamically --}}
                        </div>
                        @error('items')
                            <p class="text-red-500 text-xs mt-2">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            {{-- Sidebar --}}
            <div class="space-y-6">
                {{-- Settings --}}
                <div class="bg-white rounded-xl shadow-sm border border-gray-200">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-900">Settings</h3>
                    </div>
                    <div class="p-6">
                        <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                            <div class="flex items-center mb-2">
                                <i class="fas fa-info-circle text-blue-600 mr-2"></i>
                                <h4 class="text-sm font-semibold text-blue-900">FAQ Group Info</h4>
                            </div>
                            <p class="text-sm text-blue-800 mb-2">Create a group of related FAQs that can be selected in the page builder using the identifier.</p>
                            <p class="text-sm text-blue-800">Add at least one FAQ item to save this group.</p>
                        </div>
                    </div>
                </div>

                {{-- Actions --}}
                <div class="bg-white rounded-xl shadow-sm border border-gray-200">
                    <div class="p-6">
                        <div class="space-y-3">
                            <button type="submit"
                                    class="w-full bg-primary text-white px-4 py-2 rounded-lg hover:bg-primary/80 transition-colors duration-200">
                                <i class="fa-solid fa-save mr-2"></i>
                                Update FAQ Group
                            </button>
                            <a href="{{ route('admin.faq-module.index') }}"
                               class="w-full bg-gray-500 text-white px-4 py-2 rounded-lg hover:bg-gray-600 transition-colors duration-200 text-center block">
                                <i class="fa-solid fa-times mr-2"></i>
                                Cancel
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

{{-- Delete Confirmation Modal --}}
<div id="deleteFaqItemModal" class="fixed inset-0 backdrop-blur-sm overflow-y-auto h-full w-full hidden z-50">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3 text-center">
            <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-red-100">
                <i class="fa-solid fa-exclamation-triangle text-red-600"></i>
            </div>
            <h3 class="text-lg font-medium text-gray-900 mt-4">Delete FAQ Item</h3>
            <div class="mt-2 px-7 py-3">
                <p class="text-sm text-gray-500">
                    Are you sure you want to delete this FAQ item?
                </p>
                <p class="text-sm text-red-600 mt-2">This action cannot be undone.</p>
            </div>
            <div class="flex justify-center space-x-4 mt-4">
                <button onclick="closeDeleteFaqItemModal()" 
                        class="px-4 py-2 bg-gray-300 text-gray-800 text-base font-medium rounded-md shadow-sm hover:bg-gray-400">
                    Cancel
                </button>
                <button onclick="confirmDeleteFaqItem()" 
                        class="px-4 py-2 bg-red-600 text-white text-base font-medium rounded-md shadow-sm hover:bg-red-700">
                    Delete
                </button>
            </div>
        </div>
    </div>
</div>


    <script>
let faqItemIndex = 0;
let faqItemToDelete = null;
const existingItems = @json(old('items', $faq->items ?? []));

function closeDeleteFaqItemModal() {
    faqItemToDelete = null;
    document.getElementById('deleteFaqItemModal').classList.add('hidden');
}

function confirmDeleteFaqItem() {
    if (faqItemToDelete) {
        faqItemToDelete.remove();
        if (window.toastManager) { window.toastManager.show('success', 'FAQ item deleted successfully'); }
        updateItemNumbers();
        closeDeleteFaqItemModal();
    }
}

// Close modal when clicking outside
document.getElementById('deleteFaqItemModal')?.addEventListener('click', function(e) {
    if (e.target === this) {
        closeDeleteFaqItemModal();
    }
});

// Load existing FAQ items or add one empty item
document.addEventListener('DOMContentLoaded', function() {
    if (existingItems && existingItems.length > 0) {
        existingItems.forEach((item, index) => {
            addFaqItem(item);
        });
    } else {
        addFaqItem();
    }
});

function addFaqItem(data = null) {
    const container = document.getElementById('faqItemsContainer');
    const currentIndex = faqItemIndex;
    const itemHtml = `
        <div class="border border-gray-200 rounded-lg p-4 bg-gray-50" id="faq-item-${currentIndex}">
            <div class="flex justify-between items-center mb-4">
                <h4 class="text-md font-semibold text-gray-900">FAQ Item #${currentIndex + 1}</h4>
                <button type="button" onclick="removeFaqItem(${currentIndex})" class="text-red-600 hover:text-red-800 transition-colors">
                    <i class="fas fa-trash"></i>
                </button>
            </div>
            
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Question <span class="text-red-500">*</span>
                    </label>
                    <input type="text" 
                           name="items[${currentIndex}][question]" 
                           value="${data ? (data.question || '') : ''}"
                           class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-primary focus:border-primary" 
                           placeholder="Enter the question"
                           maxlength="255"
                           required>
                    <p class="mt-1 text-xs text-gray-500">Maximum 255 characters</p>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Answer <span class="text-red-500">*</span>
                    </label>
                    <textarea name="items[${currentIndex}][answer]" 
                              rows="4" 
                              class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-primary focus:border-primary" 
                              placeholder="Enter the answer"
                              required>${data ? (data.answer || '') : ''}</textarea>
                    <p class="mt-1 text-xs text-gray-500">Provide a detailed answer to this question</p>
                </div>
            </div>
        </div>
    `;
    
    container.insertAdjacentHTML('beforeend', itemHtml);
    faqItemIndex++;
    updateItemNumbers();
}

function removeFaqItem(index) {
    const item = document.getElementById(`faq-item-${index}`);
    if (!item) return;
    
    faqItemToDelete = item;
    document.getElementById('deleteFaqItemModal').classList.remove('hidden');
}

function updateItemNumbers() {
    const items = document.querySelectorAll('[id^="faq-item-"]');
    items.forEach((item, index) => {
        const header = item.querySelector('h4');
        if (header) {
            header.textContent = `FAQ Item #${index + 1}`;
        }
        const inputs = item.querySelectorAll('input[name^="items["], textarea[name^="items["]');
        inputs.forEach(function(inp) {
            const match = inp.name.match(/^items\[(\d+)\]/);
            if (match && parseInt(match[1], 10) !== index) {
                inp.name = inp.name.replace(/^items\[\d+\]/, 'items[' + index + ']');
            }
        });
    });
}
</script>
</x-layouts.admin-faq-hub>
