<x-layouts.admin title="Manage Page Components">
<div class="space-y-6">
    {{-- Header --}}
    <div class="flex items-center justify-between">
        <div class="flex items-center gap-3">
            <div class="w-12 h-12 bg-primary rounded-md flex items-center justify-center">
                <i class="fa-solid fa-puzzle-piece text-white text-base"></i>
            </div>
            <div class="flex flex-col gap-1">
                <h2>Manage Components: {{ $page->title }}</h2>
                <p>Add and arrange UI components for this showcase page</p>
            </div>
        </div>
        <div class="flex items-center space-x-2">
            <a href="{{ route('admin.content.page.edit', $page) }}"
               class="px-5 py-2 rounded-md bg-blue-600 text-white text-sm hover:bg-blue-700 transition-colors duration-200 flex items-center space-x-2">
                <i class="fa-solid fa-edit"></i>
                <span>Edit Page</span>
            </a>
            <a href="{{ route('admin.content.page.index') }}"
               class="px-5 py-2 rounded-md bg-gray-500 text-white text-sm hover:bg-gray-600 transition-colors duration-200 flex items-center space-x-2">
                <i class="fa-solid fa-arrow-left"></i>
                <span>Back</span>
            </a>
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

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        {{-- Attached Components --}}
        <div class="lg:col-span-2 space-y-6">
            <div class="bg-gray-50/50 rounded-md border border-gray-200">
                <div class="p-4 border-b border-gray-200 bg-gray-50/80">
                    <h3 class="text-base font-semibold text-gray-900 flex items-center">
                        <i class="fa-solid fa-list mr-2 text-blue-500"></i>
                        Page Components ({{ $page->tailwindPlusComponents->count() }})
                    </h3>
                </div>

                @if($page->tailwindPlusComponents->count() > 0)
                    <div id="componentsList" class="p-4 space-y-3">
                        @foreach($page->tailwindPlusComponents as $component)
                            <div class="component-item bg-white border border-gray-200 rounded-md p-4 flex items-center justify-between hover:shadow-md transition-shadow duration-200"
                                 data-component-id="{{ $component->id }}"
                                 data-sort-order="{{ $component->pivot->sort_order }}">
                                <div class="flex items-center space-x-3 flex-1">
                                    <div class="cursor-move text-gray-400 hover:text-gray-600">
                                        <i class="fa-solid fa-grip-vertical"></i>
                                    </div>
                                    <div class="flex-1">
                                        <div class="text-sm font-medium text-gray-900">{{ $component->component_name }}</div>
                                        <div class="text-xs text-gray-500">
                                            {{ $component->category }} / {{ $component->component_group }}
                                        </div>
                                    </div>
                                    <div class="flex items-center space-x-2">
                                        <span class="text-xs text-gray-500">Order: {{ $component->pivot->sort_order }}</span>
                                        @if($component->pivot->is_active)
                                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-green-100 text-green-800">
                                                Active
                                            </span>
                                        @else
                                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-gray-100 text-gray-800">
                                                Inactive
                                            </span>
                                        @endif
                                    </div>
                                </div>
                                <div class="flex items-center space-x-2 ml-4">
                                    <button type="button" onclick="previewComponent({{ $component->id }})"
                                            class="text-xs text-gray-600 hover:text-primary transition-colors duration-200"
                                            title="Preview">
                                        <i class="fa-solid fa-eye"></i>
                                    </button>
                                    <button type="button" onclick="removeComponent({{ $component->id }}, '{{ addslashes($component->component_name) }}')"
                                            class="text-xs text-gray-600 hover:text-red-600 transition-colors duration-200"
                                            title="Remove">
                                        <i class="fa-solid fa-trash"></i>
                                    </button>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="p-8 text-center">
                        <i class="fa-solid fa-puzzle-piece text-gray-300 text-3xl mb-2"></i>
                        <h3 class="text-base font-medium text-gray-900 mb-1">No components added yet</h3>
                        <p class="text-xs text-gray-500">Add components from the list on the right to build your page.</p>
                    </div>
                @endif
            </div>
        </div>

        {{-- Available Components --}}
        <div class="space-y-6">
            <div class="bg-gray-50/50 rounded-md border border-gray-200">
                <div class="p-4 border-b border-gray-200 bg-gray-50/80">
                    <h3 class="text-base font-semibold text-gray-900 flex items-center">
                        <i class="fa-solid fa-plus-circle mr-2 text-green-500"></i>
                        Available Components
                    </h3>
                </div>
                <div class="p-4">
                    <div class="mb-4">
                        <input type="text" id="componentSearch" placeholder="Search components..."
                               class="w-full px-3 py-2 text-sm bg-white border border-gray-200 rounded-md focus:outline-none focus:ring-2 focus:ring-primary">
                    </div>
                    <div id="availableComponentsList" class="space-y-2 max-h-[600px] overflow-y-auto">
                        @foreach($availableComponents as $component)
                            <div class="component-option bg-white border border-gray-200 rounded-md p-3 hover:shadow-md transition-shadow duration-200 {{ in_array($component->id, $attachedComponentIds) ? 'opacity-50 cursor-not-allowed' : 'cursor-pointer' }}"
                                 data-component-id="{{ $component->id }}"
                                 data-component-name="{{ $component->component_name }}"
                                 data-component-category="{{ $component->category }}"
                                 data-component-group="{{ $component->component_group }}">
                                <div class="flex items-center justify-between">
                                    <div class="flex-1">
                                        <div class="text-xs font-medium text-gray-900">{{ $component->component_name }}</div>
                                        <div class="text-xs text-gray-500">
                                            {{ $component->category }} / {{ $component->component_group }}
                                        </div>
                                    </div>
                                    @if(in_array($component->id, $attachedComponentIds))
                                        <span class="text-xs text-gray-400 ml-2">
                                            <i class="fa-solid fa-check"></i>
                                        </span>
                                    @else
                                        <button type="button" onclick="addComponent({{ $component->id }})"
                                                class="text-xs text-primary hover:text-primary/80 transition-colors duration-200 ml-2">
                                            <i class="fa-solid fa-plus"></i>
                                        </button>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Preview Modal --}}
<div id="previewModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/50">
    <div class="bg-white w-full h-full flex flex-col">
        <div class="flex items-center justify-between p-4 border-b border-gray-200">
            <h3 class="text-base font-medium text-gray-900">Component Preview</h3>
            <button onclick="closePreviewModal()" class="text-gray-400 hover:text-gray-600">
                <i class="fa-solid fa-times text-lg"></i>
            </button>
        </div>
        <div class="flex-1 overflow-hidden">
            <iframe id="previewIframe" src="" class="w-full h-full border-0" frameborder="0" scrolling="no"></iframe>
        </div>
    </div>
</div>

{{-- Remove Confirmation Modal --}}
<div id="removeModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/50">
    <div class="bg-white rounded-md p-6 w-full max-w-md mx-4">
        <div class="flex items-center space-x-3 mb-4">
            <div class="flex-shrink-0 w-10 h-10 bg-red-100 rounded-full flex items-center justify-center">
                <i class="fa-solid fa-exclamation-triangle text-red-600"></i>
            </div>
            <div>
                <h3 class="text-base font-medium text-gray-900">Remove Component</h3>
                <p class="text-xs text-gray-600">Are you sure you want to remove this component from the page?</p>
            </div>
        </div>
        <div class="bg-gray-50 rounded-md p-3 mb-4 border border-gray-200">
            <p class="text-xs text-gray-700">
                <strong>Component:</strong> <span id="componentName"></span>
            </p>
        </div>
        <div class="flex space-x-3">
            <button onclick="closeRemoveModal()"
                    class="flex-1 px-4 py-2 text-sm text-gray-700 bg-white border border-gray-200 rounded-md hover:bg-gray-50 transition-colors duration-200">
                Cancel
            </button>
            <button onclick="confirmRemoveComponent()"
                    class="flex-1 px-4 py-2 text-sm bg-red-600 text-white rounded-md hover:bg-red-700 transition-colors duration-200">
                Remove
            </button>
        </div>
    </div>
</div>

    <script src="https://cdn.jsdelivr.net/npm/sortablejs@latest/Sortable.min.js"></script>
    <script>
let componentToRemove = null;

document.addEventListener('DOMContentLoaded', function() {
    @if(session('success'))
        toastr.success('{{ session('success') }}');
    @endif

    @if(session('error'))
        toastr.error('{{ session('error') }}');
    @endif

    // Initialize Sortable for components list
    const componentsList = document.getElementById('componentsList');
    if (componentsList) {
        new Sortable(componentsList, {
            handle: '.cursor-move',
            animation: 150,
            onEnd: function(evt) {
                updateComponentOrder();
            }
        });
    }

    // Component search functionality
    const searchInput = document.getElementById('componentSearch');
    if (searchInput) {
        searchInput.addEventListener('input', function() {
            const searchTerm = this.value.toLowerCase();
            const components = document.querySelectorAll('.component-option');
            
            components.forEach(component => {
                const name = component.getAttribute('data-component-name').toLowerCase();
                const category = component.getAttribute('data-component-category').toLowerCase();
                const group = component.getAttribute('data-component-group').toLowerCase();
                
                if (name.includes(searchTerm) || category.includes(searchTerm) || group.includes(searchTerm)) {
                    component.style.display = 'block';
                } else {
                    component.style.display = 'none';
                }
            });
        });
    }
});

function addComponent(componentId) {
    fetch(`{{ route('admin.content.page.components.add', $page) }}`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({
            tailwind_plus_id: componentId
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            toastr.success(data.message);
            setTimeout(() => {
                window.location.reload();
            }, 500);
        } else {
            toastr.error(data.error || 'Failed to add component');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        toastr.error('An error occurred while adding the component.');
    });
}

function removeComponent(componentId, componentName) {
    componentToRemove = componentId;
    document.getElementById('componentName').textContent = componentName;
    document.getElementById('removeModal').classList.remove('hidden');
    document.getElementById('removeModal').classList.add('flex');
}

function confirmRemoveComponent() {
    if (!componentToRemove) return;

    fetch(`{{ route('admin.content.page.components.remove', [$page, ':id']) }}`.replace(':id', componentToRemove), {
        method: 'DELETE',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            toastr.success(data.message);
            setTimeout(() => {
                window.location.reload();
            }, 500);
        } else {
            toastr.error(data.error || 'Failed to remove component');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        toastr.error('An error occurred while removing the component.');
    })
    .finally(() => {
        closeRemoveModal();
    });
}

function closeRemoveModal() {
    componentToRemove = null;
    document.getElementById('removeModal').classList.add('hidden');
    document.getElementById('removeModal').classList.remove('flex');
}

function updateComponentOrder() {
    const components = document.querySelectorAll('.component-item');
    const orderData = Array.from(components).map((item, index) => ({
        id: parseInt(item.getAttribute('data-component-id')),
        sort_order: index + 1
    }));

    fetch(`{{ route('admin.content.page.components.update-order', $page) }}`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({
            components: orderData
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Update sort order display
            components.forEach((item, index) => {
                const orderSpan = item.querySelector('.text-xs.text-gray-500');
                if (orderSpan && orderSpan.textContent.includes('Order:')) {
                    orderSpan.textContent = `Order: ${index + 1}`;
                }
            });
            toastr.success('Component order updated!');
        } else {
            toastr.error(data.error || 'Failed to update component order');
            window.location.reload();
        }
    })
    .catch(error => {
        console.error('Error:', error);
        toastr.error('An error occurred while updating the order.');
        window.location.reload();
    });
}

function previewComponent(componentId) {
    const iframe = document.getElementById('previewIframe');
    iframe.src = '{{ route('admin.content.tailwind-plus.preview', ':id') }}'.replace(':id', componentId);
    document.getElementById('previewModal').classList.remove('hidden');
    document.getElementById('previewModal').classList.add('flex');
}

function closePreviewModal() {
    const iframe = document.getElementById('previewIframe');
    iframe.src = '';
    document.getElementById('previewModal').classList.add('hidden');
    document.getElementById('previewModal').classList.remove('flex');
}

document.getElementById('previewModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closePreviewModal();
    }
});

document.getElementById('removeModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeRemoveModal();
    }
});
</script>
    </script>
</x-layouts.admin>

