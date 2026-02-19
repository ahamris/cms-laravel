<x-layouts.admin title="Edit Feature Block">
<div class="space-y-6">
    {{-- Header --}}
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Edit Feature Block</h1>
            <p class="text-gray-600">Edit feature block with multiple items</p>
        </div>
        <a href="{{ route('admin.content.block-feature.index') }}"
           class="bg-gray-600 text-white px-4 py-2 rounded-lg hover:bg-gray-700 transition-colors duration-200">
            <i class="fa-solid fa-arrow-left mr-2"></i>
            Back to List
        </a>
    </div>

    {{-- Form --}}
    <form action="{{ route('admin.content.block-feature.update', $blockFeature) }}" method="POST" enctype="multipart/form-data" class="space-y-6" id="featureBlockForm">
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
                        {{-- Identifier --}}
                        <div>
                            <label for="identifier" class="block text-sm font-medium text-gray-700 mb-1">
                                Identifier <span class="text-red-500">*</span>
                            </label>
                            <input type="text"
                                   id="identifier"
                                   name="identifier"
                                   value="{{ old('identifier', $blockFeature->identifier) }}"
                                   placeholder="e.g., homepage_features, about_features"
                                   class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-primary focus:border-primary @error('identifier') border-red-500 @enderror">
                            @error('identifier')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                            <p class="text-xs text-gray-500 mt-1">Unique identifier for this feature block (letters, numbers, dashes, underscores only)</p>
                        </div>

                        {{-- Section Title --}}
                        <div>
                            <label for="section_title" class="block text-sm font-medium text-gray-700 mb-1">
                                Section Title
                            </label>
                            <input type="text"
                                   id="section_title"
                                   name="section_title"
                                   value="{{ old('section_title', $blockFeature->section_title) }}"
                                   placeholder="Enter section title (optional)"
                                   class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-primary focus:border-primary @error('section_title') border-red-500 @enderror">
                            @error('section_title')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Section Subtitle --}}
                        <div>
                            <label for="section_subtitle" class="block text-sm font-medium text-gray-700 mb-1">
                                Section Subtitle
                            </label>
                            <input type="text"
                                   id="section_subtitle"
                                   name="section_subtitle"
                                   value="{{ old('section_subtitle', $blockFeature->section_subtitle) }}"
                                   placeholder="Enter section subtitle (optional)"
                                   class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-primary focus:border-primary @error('section_subtitle') border-red-500 @enderror">
                            @error('section_subtitle')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                {{-- Feature Items --}}
                <div class="bg-white rounded-xl shadow-sm border border-gray-200">
                    <div class="px-6 py-4 border-b border-gray-200 flex items-center justify-between">
                        <h3 class="text-lg font-semibold text-gray-900">Feature Items</h3>
                        <button type="button" onclick="addItem()" class="bg-primary text-white px-3 py-1 rounded-lg hover:bg-primary/80 transition-colors duration-200 text-sm">
                            <i class="fa-solid fa-plus mr-1"></i>
                            Add Item
                        </button>
                    </div>
                    <div class="p-6">
                        <div id="itemsContainer" class="space-y-4">
                            {{-- Items will be added here dynamically --}}
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
                    <div class="p-6 space-y-4">
                        {{-- Sort Order --}}
                        <div>
                            <label for="sort_order" class="block text-sm font-medium text-gray-700 mb-1">
                                Sort Order
                            </label>
                            <input type="number"
                                   id="sort_order"
                                   name="sort_order"
                                   value="{{ old('sort_order', 0) }}"
                                   min="0"
                                   class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-primary focus:border-primary @error('sort_order') border-red-500 @enderror">
                            @error('sort_order')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Active Status --}}
                        <div>
                            <x-ui.toggle 
                                name="is_active"
                                :checked="old('is_active', $blockFeature->is_active)"
                                label="Active"
                            />
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
                                Update Feature Block
                            </button>
                            <a href="{{ route('admin.content.block-feature.index') }}"
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
<div id="deleteItemModal" class="fixed inset-0 backdrop-blur-sm overflow-y-auto h-full w-full hidden z-50">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3 text-center">
            <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-red-100">
                <i class="fa-solid fa-exclamation-triangle text-red-600"></i>
            </div>
            <h3 class="text-lg font-medium text-gray-900 mt-4">Delete Item</h3>
            <div class="mt-2 px-7 py-3">
                <p class="text-sm text-gray-500">
                    Are you sure you want to delete "<span id="deleteItemName"></span>"?
                </p>
                <p class="text-sm text-red-600 mt-2">This action cannot be undone.</p>
            </div>
            <div class="flex justify-center space-x-4 mt-4">
                <button onclick="closeDeleteItemModal()" 
                        class="px-4 py-2 bg-gray-300 text-gray-800 text-base font-medium rounded-md shadow-sm hover:bg-gray-400">
                    Cancel
                </button>
                <button onclick="confirmDeleteItem()" 
                        class="px-4 py-2 bg-red-600 text-white text-base font-medium rounded-md shadow-sm hover:bg-red-700">
                    Delete
                </button>
            </div>
        </div>
    </div>
</div>

<script>
let itemToDelete = null;

function closeDeleteItemModal() {
    itemToDelete = null;
    document.getElementById('deleteItemModal').classList.add('hidden');
}

function confirmDeleteItem() {
    if (itemToDelete) {
        itemToDelete.remove();
        toastr.success('Item deleted successfully');
        closeDeleteItemModal();
    }
}

// Close modal when clicking outside
document.getElementById('deleteItemModal')?.addEventListener('click', function(e) {
    if (e.target === this) {
        closeDeleteItemModal();
    }
});

let itemIndex = 0;
const existingItems = @json(old('items', $blockFeature->items ?? []));

function addItem(itemData = null) {
    const data = itemData || {};
    const currentIndex = itemIndex;
    const container = document.getElementById('itemsContainer');
    const itemHtml = `
        <div class="border border-gray-200 rounded-lg p-4 relative" id="item-${currentIndex}">
            <button type="button" onclick="removeItem(${currentIndex})" class="absolute top-2 right-2 text-red-600 hover:text-red-800">
                <i class="fa-solid fa-times"></i>
            </button>
            
            <div class="grid grid-cols-3 gap-4">
                {{-- Left Column: Image Uploader (1/3 width) --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Image
                    </label>
                    <div class="flex justify-center px-4 py-4 border-2 border-gray-300 border-dashed rounded-lg hover:border-primary transition-colors duration-200 cursor-pointer" onclick="document.getElementById('item_image_${currentIndex}').click()">
                        <div class="space-y-1 text-center">
                            <div id="imagePreview-${currentIndex}" class="${data.image ? '' : 'hidden'}">
                                <img class="mx-auto h-24 w-auto rounded-lg" src="${data.image ? '{{ asset('storage/') }}/' + data.image : ''}" alt="Preview">
                            </div>
                            <div id="imagePlaceholder-${currentIndex}" class="${data.image ? 'hidden' : ''}">
                                <i class="fa-solid fa-image text-2xl text-gray-400"></i>
                                <div class="text-xs text-gray-600 mt-2">
                                    <span class="font-medium text-primary hover:text-primary/80">Click to upload</span>
                                </div>
                                <p class="text-xs text-gray-400 mt-1">PNG, JPG, GIF</p>
                            </div>
                        </div>
                    </div>
                    <input id="item_image_${currentIndex}" 
                           name="item_images[${currentIndex}]" 
                           type="file" 
                           class="hidden" 
                           accept="image/*"
                           onchange="previewImage(${currentIndex}, this)">
                    ${data.image ? `<input type="hidden" name="items[${currentIndex}][image]" value="${data.image}">` : ''}
                </div>
                
                {{-- Right Column: Title and Content (2/3 width) --}}
                <div class="col-span-2 space-y-3">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Title <span class="text-red-500">*</span>
                        </label>
                        <input type="text"
                               name="items[${currentIndex}][title]"
                               value="${data.title || ''}"
                               placeholder="Enter item title"
                               class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-primary focus:border-primary"
                               required>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Content
                        </label>
                        <textarea name="items[${currentIndex}][content]"
                                  rows="4"
                                  placeholder="Enter item content"
                                  class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-primary focus:border-primary">${data.content || ''}</textarea>
                    </div>
                </div>
            </div>
            
            <div class="space-y-3 mt-3">
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Image Position
                    </label>
                    <div class="grid grid-cols-2 gap-3">
                        <label class="relative flex items-center p-3 border-2 border-gray-300 rounded-lg cursor-pointer hover:border-primary transition-colors duration-200 group">
                            <input type="radio" 
                                   name="items[${currentIndex}][image_position]" 
                                   value="left" 
                                   ${!data.image_position || data.image_position === 'left' ? 'checked' : ''}
                                   class="sr-only peer">
                            <div class="flex items-center gap-2 w-full">
                                <div class="w-12 h-12 bg-gray-200 rounded flex-shrink-0 flex items-center justify-center">
                                    <i class="fa-solid fa-image text-gray-400 text-sm"></i>
                                </div>
                                <div class="flex-1">
                                    <div class="h-2 bg-gray-200 rounded mb-1"></div>
                                    <div class="h-2 bg-gray-200 rounded w-3/4"></div>
                                </div>
                            </div>
                            <div class="absolute inset-0 border-2 border-primary rounded-lg opacity-0 peer-checked:opacity-100 transition-opacity duration-200"></div>
                            <div class="absolute -top-2 -right-2 w-5 h-5 bg-primary rounded-full opacity-0 peer-checked:opacity-100 transition-opacity duration-200 flex items-center justify-center">
                                <i class="fa-solid fa-check text-white text-xs"></i>
                            </div>
                        </label>
                        
                        <label class="relative flex items-center p-3 border-2 border-gray-300 rounded-lg cursor-pointer hover:border-primary transition-colors duration-200 group">
                            <input type="radio" 
                                   name="items[${currentIndex}][image_position]" 
                                   value="right"
                                   ${data.image_position === 'right' ? 'checked' : ''}
                                   class="sr-only peer">
                            <div class="flex items-center gap-2 w-full">
                                <div class="flex-1">
                                    <div class="h-2 bg-gray-200 rounded mb-1"></div>
                                    <div class="h-2 bg-gray-200 rounded w-3/4"></div>
                                </div>
                                <div class="w-12 h-12 bg-gray-200 rounded flex-shrink-0 flex items-center justify-center">
                                    <i class="fa-solid fa-image text-gray-400 text-sm"></i>
                                </div>
                            </div>
                            <div class="absolute inset-0 border-2 border-primary rounded-lg opacity-0 peer-checked:opacity-100 transition-opacity duration-200"></div>
                            <div class="absolute -top-2 -right-2 w-5 h-5 bg-primary rounded-full opacity-0 peer-checked:opacity-100 transition-opacity duration-200 flex items-center justify-center">
                                <i class="fa-solid fa-check text-white text-xs"></i>
                            </div>
                        </label>
                    </div>
                    <p class="text-xs text-gray-500 mt-2">Choose layout: thumbnail image with content</p>
                </div>
                
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Link Text
                        </label>
                        <input type="text"
                               name="items[${currentIndex}][link_text]"
                               value="${data.link_text || ''}"
                               placeholder="Learn More"
                               class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-primary focus:border-primary">
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Link URL</label>
                        <div x-data="urlSelector${currentIndex}()" class="space-y-2">
                            <div class="grid grid-cols-3 gap-2 mb-2">
                                <label class="flex items-center space-x-1 cursor-pointer">
                                    <input type="radio" name="items[${currentIndex}][link_type]" value="predefined" 
                                           x-model="linkType"
                                           class="w-3 h-3 text-primary border-gray-300 focus:ring-primary">
                                    <span class="text-xs text-gray-700">Route</span>
                                </label>
                                <label class="flex items-center space-x-1 cursor-pointer">
                                    <input type="radio" name="items[${currentIndex}][link_type]" value="system" 
                                           x-model="linkType"
                                           class="w-3 h-3 text-primary border-gray-300 focus:ring-primary">
                                    <span class="text-xs text-gray-700">Content</span>
                                </label>
                                <label class="flex items-center space-x-1 cursor-pointer">
                                    <input type="radio" name="items[${currentIndex}][link_type]" value="custom" 
                                           x-model="linkType"
                                           class="w-3 h-3 text-primary border-gray-300 focus:ring-primary">
                                    <span class="text-xs text-gray-700">Custom</span>
                                </label>
                            </div>

                            <div x-show="linkType === 'predefined'" x-transition class="space-y-1">
                                <select x-model="selectedRoute" @change="updateUrl()" 
                                        id="predefined_route_${currentIndex}"
                                        class="w-full border border-gray-300 rounded px-2 py-1 text-sm focus:ring-1 focus:ring-primary focus:border-primary">
                                    <option value="">Choose a page...</option>
                                </select>
                            </div>

                            <div x-show="linkType === 'system'" x-transition class="space-y-1">
                                <select x-model="selectedSystemContent" @change="updateUrl()"
                                        id="system_content_${currentIndex}"
                                        class="w-full border border-gray-300 rounded px-2 py-1 text-sm focus:ring-1 focus:ring-primary focus:border-primary">
                                    <option value="">Choose content...</option>
                                </select>
                            </div>

                            <div x-show="linkType === 'custom'" x-transition class="space-y-1">
                                <input type="text" x-model="customUrl" @input="updateUrl()"
                                       placeholder="/custom-page or https://external-site.com"
                                       class="w-full border border-gray-300 rounded px-2 py-1 text-sm focus:ring-1 focus:ring-primary focus:border-primary">
                            </div>

                            <input type="hidden" name="items[${currentIndex}][link_url]" x-model="finalUrl">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    `;
    
    // Prepare data for Alpine component BEFORE inserting HTML
    const availableRoutes = @json($availableRoutes ?? []);
    const systemContent = @json($systemContent ?? []);
    const currentUrl = data.link_url || '';
    
    // Determine initial state
    let initialLinkType = 'custom';
    let initialSelectedRoute = '';
    let initialSelectedSystemContent = '';
    let initialCustomUrl = currentUrl;
    
    if (currentUrl) {
        for (const [name, url] of Object.entries(availableRoutes)) {
            if (url === currentUrl) {
                initialLinkType = 'predefined';
                initialSelectedRoute = url;
                initialCustomUrl = '';
                break;
            }
        }
        
        if (initialLinkType === 'custom') {
            for (const [category, items] of Object.entries(systemContent)) {
                for (const item of items) {
                    if (item.url === currentUrl) {
                        initialLinkType = 'system';
                        initialSelectedSystemContent = item.url;
                        initialCustomUrl = '';
                        break;
                    }
                }
                if (initialLinkType === 'system') break;
            }
        }
    }
    
    // Capture currentIndex for closure
    const itemIndexForClosure = currentIndex;
    
    // Create URL selector function BEFORE inserting HTML
    window['urlSelector' + currentIndex] = function() {
        return {
            linkType: initialLinkType,
            selectedRoute: initialSelectedRoute,
            selectedSystemContent: initialSelectedSystemContent,
            customUrl: initialCustomUrl,
            finalUrl: currentUrl || '',
            
            init() {
                this.updateUrl();
                // Populate selects after Alpine initializes
                setTimeout(() => this.populateSelects(), 10);
            },
            
            populateSelects() {
                const predefinedSelect = document.getElementById('predefined_route_' + itemIndexForClosure);
                const systemSelect = document.getElementById('system_content_' + itemIndexForClosure);
                
                // Populate predefined routes
                if (predefinedSelect && predefinedSelect.children.length === 1) {
                    Object.entries(availableRoutes).forEach(([name, url]) => {
                        const option = document.createElement('option');
                        option.value = url;
                        option.textContent = name;
                        if (url === this.selectedRoute) option.selected = true;
                        predefinedSelect.appendChild(option);
                    });
                }
                
                // Populate system content
                if (systemSelect && systemSelect.children.length === 1) {
                    Object.entries(systemContent).forEach(([category, items]) => {
                        const optgroup = document.createElement('optgroup');
                        optgroup.label = category;
                        items.forEach(item => {
                            const option = document.createElement('option');
                            option.value = item.url;
                            option.textContent = item.title;
                            if (item.url === this.selectedSystemContent) option.selected = true;
                            optgroup.appendChild(option);
                        });
                        systemSelect.appendChild(optgroup);
                    });
                }
            },
            
            updateUrl() {
                if (this.linkType === 'predefined') {
                    this.finalUrl = this.selectedRoute;
                    this.customUrl = '';
                    this.selectedSystemContent = '';
                } else if (this.linkType === 'system') {
                    this.finalUrl = this.selectedSystemContent;
                    this.customUrl = '';
                    this.selectedRoute = '';
                } else {
                    this.finalUrl = this.customUrl;
                    this.selectedRoute = '';
                    this.selectedSystemContent = '';
                }
            }
        };
    };
    
    // Now insert HTML - Alpine will find the function
    container.insertAdjacentHTML('beforeend', itemHtml);
    
    itemIndex++;
}

function previewImage(index, input) {
    const preview = document.getElementById(`imagePreview-${index}`);
    const placeholder = document.getElementById(`imagePlaceholder-${index}`);
    
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        
        reader.onload = function(e) {
            preview.querySelector('img').src = e.target.result;
            preview.classList.remove('hidden');
            placeholder.classList.add('hidden');
        };
        
        reader.readAsDataURL(input.files[0]);
    }
}

function removeItem(index) {
    const item = document.getElementById(`item-${index}`);
    if (!item) return;
    
    // Get item title for confirmation message
    const titleInput = item.querySelector('input[name*="[title]"]');
    const itemTitle = titleInput ? titleInput.value || 'this item' : 'this item';
    
    // Store item reference and show modal
    itemToDelete = item;
    document.getElementById('deleteItemName').textContent = itemTitle;
    document.getElementById('deleteItemModal').classList.remove('hidden');
}

// Load existing items on page load
document.addEventListener('DOMContentLoaded', function() {
    if (existingItems && existingItems.length > 0) {
        existingItems.forEach(item => addItem(item));
    } else {
        addItem();
    }
});
</script>
</x-layouts.admin>
