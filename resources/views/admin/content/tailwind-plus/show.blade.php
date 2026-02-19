<x-layouts.admin title="Component Details">
<div class="space-y-6">
    {{-- Header --}}
    <div class="flex items-center justify-between">
        <div class="flex items-center gap-3">
            <div class="w-12 h-12 bg-primary rounded-md flex items-center justify-center">
                <i class="fa-solid fa-code text-white text-base"></i>
            </div>
            <div class="flex flex-col gap-1">
                <h2>{{ $tailwindPlus->component_name }}</h2>
                <p>Component Details</p>
            </div>
        </div>
        <div class="flex items-center space-x-2">
            <button type="button" onclick="previewComponent(event, {{ $tailwindPlus->id }})"
                    class="px-5 py-2 rounded-md bg-primary text-white text-sm hover:bg-primary/80 transition-colors duration-200 flex items-center space-x-2">
                <i class="fa-solid fa-eye"></i>
                <span>Preview</span>
            </button>
            <a href="{{ route('admin.content.tailwind-plus.edit', $tailwindPlus) }}"
               class="px-5 py-2 rounded-md bg-blue-600 text-white text-sm hover:bg-blue-700 transition-colors duration-200 flex items-center space-x-2">
                <i class="fa-solid fa-edit"></i>
                <span>Edit</span>
            </a>
            <a href="{{ route('admin.content.tailwind-plus.index') }}"
               class="px-5 py-2 rounded-md bg-gray-500 text-white text-sm hover:bg-gray-600 transition-colors duration-200 flex items-center space-x-2">
                <i class="fa-solid fa-arrow-left"></i>
                <span>Back</span>
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        {{-- Main Content --}}
        <div class="lg:col-span-2 space-y-6">
            {{-- Basic Information --}}
            <div class="bg-gray-50/50 rounded-md border border-gray-200 p-6">
                <h3 class="text-base font-semibold text-gray-900 mb-4 flex items-center">
                    <i class="fa-solid fa-info-circle mr-2 text-blue-500"></i>
                    Basic Information
                </h3>
                
                <div class="space-y-4">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-medium text-gray-700 mb-1">Component Name</label>
                            <p class="text-sm text-gray-900 font-medium">{{ $tailwindPlus->component_name }}</p>
                        </div>

                        <div>
                            <label class="block text-xs font-medium text-gray-700 mb-1">Category</label>
                            <p class="text-sm text-gray-900">{{ $tailwindPlus->category }}</p>
                        </div>

                        <div>
                            <label class="block text-xs font-medium text-gray-700 mb-1">Component Group</label>
                            <p class="text-sm text-gray-900">{{ $tailwindPlus->component_group ?? '-' }}</p>
                        </div>

                        <div>
                            <label class="block text-xs font-medium text-gray-700 mb-1">Version</label>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                v{{ $tailwindPlus->version }}
                            </span>
                        </div>

                        <div>
                            <label class="block text-xs font-medium text-gray-700 mb-1">Status</label>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $tailwindPlus->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                <i class="fa fa-{{ $tailwindPlus->is_active ? 'check' : 'times' }} mr-1"></i>
                                {{ $tailwindPlus->is_active ? 'Active' : 'Inactive' }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Code Preview --}}
            <div class="bg-gray-50/50 rounded-md border border-gray-200 p-6">
                <h3 class="text-base font-semibold text-gray-900 mb-4 flex items-center">
                    <i class="fa-solid fa-code mr-2 text-green-500"></i>
                    Component Code
                </h3>
                
                <div class="bg-gray-900 rounded-md p-4 overflow-x-auto">
                    <pre class="text-xs text-gray-100 font-mono whitespace-pre-wrap"><code>{{ htmlspecialchars($tailwindPlus->code) }}</code></pre>
                </div>
            </div>

            @if($tailwindPlus->preview)
            {{-- Preview Code --}}
            <div class="bg-gray-50/50 rounded-md border border-gray-200 p-6">
                <h3 class="text-base font-semibold text-gray-900 mb-4 flex items-center">
                    <i class="fa-solid fa-eye mr-2 text-purple-500"></i>
                    Preview Code
                </h3>
                
                <div class="bg-gray-900 rounded-md p-4 overflow-x-auto">
                    <pre class="text-xs text-gray-100 font-mono whitespace-pre-wrap"><code>{{ htmlspecialchars($tailwindPlus->preview) }}</code></pre>
                </div>
            </div>
            @endif
        </div>

        {{-- Sidebar --}}
        <div class="space-y-6">
            {{-- Timestamps --}}
            <div class="bg-gray-50/50 rounded-md border border-gray-200 p-6">
                <h3 class="text-base font-semibold text-gray-900 mb-4 flex items-center">
                    <i class="fa-solid fa-clock mr-2 text-gray-500"></i>
                    Timestamps
                </h3>
                
                <div class="space-y-3">
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-1">Created At</label>
                        <p class="text-sm text-gray-900">{{ $tailwindPlus->created_at->format('M j, Y \a\t g:i A') }}</p>
                    </div>

                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-1">Updated At</label>
                        <p class="text-sm text-gray-900">{{ $tailwindPlus->updated_at->format('M j, Y \a\t g:i A') }}</p>
                    </div>
                </div>
            </div>

            {{-- Quick Actions --}}
            <div class="bg-gray-50/50 rounded-md border border-gray-200 p-6">
                <h3 class="text-base font-semibold text-gray-900 mb-4 flex items-center">
                    <i class="fa-solid fa-bolt mr-2 text-yellow-500"></i>
                    Quick Actions
                </h3>
                
                <div class="space-y-2">
                    <button onclick="toggleActive({{ $tailwindPlus->id }}, {{ $tailwindPlus->is_active ? 'true' : 'false' }})"
                            class="w-full px-4 py-2 text-sm text-gray-700 bg-white border border-gray-200 rounded-md hover:bg-gray-50 transition-colors duration-200 text-left">
                        <i class="fa-solid fa-toggle-{{ $tailwindPlus->is_active ? 'on' : 'off' }} mr-2"></i>
                        {{ $tailwindPlus->is_active ? 'Deactivate' : 'Activate' }}
                    </button>
                    
                    <button onclick="deleteComponent({{ $tailwindPlus->id }}, '{{ addslashes($tailwindPlus->component_name) }}')"
                            class="w-full px-4 py-2 text-sm text-red-700 bg-white border border-red-200 rounded-md hover:bg-red-50 transition-colors duration-200 text-left">
                        <i class="fa-solid fa-trash mr-2"></i>
                        Delete Component
                    </button>
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

{{-- Delete Confirmation Modal --}}
<div id="deleteModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/50">
    <div class="bg-white rounded-md p-6 w-full max-w-md mx-4">
        <div class="flex items-center space-x-3 mb-4">
            <div class="flex-shrink-0 w-10 h-10 bg-red-100 rounded-full flex items-center justify-center">
                <i class="fa-solid fa-exclamation-triangle text-red-600"></i>
            </div>
            <div>
                <h3 class="text-base font-medium text-gray-900">Delete Component</h3>
                <p class="text-xs text-gray-600">Are you sure you want to delete this component?</p>
            </div>
        </div>
        <div class="bg-gray-50 rounded-md p-3 mb-4 border border-gray-200">
            <p class="text-xs text-gray-700">
                <strong>Component:</strong> <span id="componentName"></span>
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

    <script>
function previewComponent(event, componentId) {
    if (event) {
        event.preventDefault();
        event.stopPropagation();
    }
    const iframe = document.getElementById('previewIframe');
    iframe.src = '{{ route('admin.content.tailwind-plus.preview', ':id') }}'.replace(':id', componentId);
    const modal = document.getElementById('previewModal');
    modal.classList.remove('hidden');
    modal.classList.add('flex');
    return false;
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

function toggleActive(componentId, currentStatus) {
    fetch(`/admin/content/tailwind-plus/${componentId}/toggle-active`, {
        method: 'POST',
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
            }, 1000);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        toastr.error('An error occurred while updating the status.');
    });
}

function deleteComponent(componentId, componentName) {
    document.getElementById('componentName').textContent = componentName;
    document.getElementById('deleteForm').action = `/admin/content/tailwind-plus/${componentId}`;
    document.getElementById('deleteModal').classList.remove('hidden');
    document.getElementById('deleteModal').classList.add('flex');
}

function closeDeleteModal() {
    document.getElementById('deleteModal').classList.add('hidden');
    document.getElementById('deleteModal').classList.remove('flex');
}

document.getElementById('deleteModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeDeleteModal();
    }
});
</script>
    </script>
</x-layouts.admin>

