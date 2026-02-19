<x-layouts.admin title="TailwindPlus Components">
<div class="space-y-6">
    {{-- Header --}}
    <div class="flex items-center justify-between">
        <div class="flex items-center gap-3">
            <div class="w-12 h-12 bg-primary rounded-md flex items-center justify-center">
                <i class="fa-solid fa-code text-white text-base"></i>
            </div>
            <div class="flex flex-col gap-1">
                <h2>TailwindPlus Components</h2>
                <p>Manage Tailwind UI components</p>
            </div>
        </div>
        <div class="flex items-center gap-2">
            <form id="importFromFileForm" action="{{ route('admin.content.tailwind-plus.import-from-file') }}" method="POST" enctype="multipart/form-data" class="flex items-center gap-2">
                @csrf
                <input type="file" id="importFileInput" name="file" accept=".html,.blade.php,.php" aria-label="Select HTML or Blade file" class="hidden">
                <button type="button" id="importFromFileBtn"
                        class="px-5 py-2 rounded-md bg-gray-100 text-gray-700 border border-gray-200 text-sm hover:bg-gray-200 transition-colors duration-200 flex items-center space-x-2">
                    <i class="fa-solid fa-file-import"></i>
                    <span>Import from File</span>
                </button>
                <a href="{{ route('admin.content.tailwind-plus.create') }}"
                   class="px-5 py-2 rounded-md bg-primary text-white text-sm hover:bg-primary/80 transition-colors duration-200 flex items-center space-x-2">
                    <i class="fa-solid fa-plus"></i>
                    <span>Add Component</span>
                </a>

            {{-- Import modal: form fields (modal is inside this form) --}}
            <div id="importModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/50">
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 w-full max-w-lg mx-4 max-h-[90vh] overflow-y-auto" onclick="event.stopPropagation()">
                    <h3 class="text-base font-medium text-gray-900 mb-4">Import from File</h3>
                    <p class="text-xs text-gray-500 mb-4">Set component metadata for the selected file.</p>

                    <div class="space-y-4">
                        <div>
                            <label for="import_category" class="block text-sm font-medium text-gray-700 mb-1">Category <span class="text-red-500">*</span></label>
                            <select id="import_category" name="category" required
                                    class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary">
                                <option value="">Select a category...</option>
                                <option value="MARKETING">MARKETING</option>
                                <option value="APPLICATION UI">APPLICATION UI</option>
                                <option value="ECOMMERCE">ECOMMERCE</option>
                                <option value="IMPORTED">IMPORTED</option>
                                @foreach($categories as $cat)
                                    @if($cat && !in_array($cat, ['MARKETING', 'APPLICATION UI', 'ECOMMERCE', 'IMPORTED'], true))
                                        <option value="{{ $cat }}">{{ $cat }}</option>
                                    @endif
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label for="import_component_group" class="block text-sm font-medium text-gray-700 mb-1">Component Group</label>
                            <select id="import_component_group" name="component_group"
                                    class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary">
                                <option value="">Select a group...</option>
                                <option value="Imported">Imported</option>
                                @foreach($componentGroups as $group)
                                    @if($group && $group !== 'Imported')
                                        <option value="{{ $group }}">{{ $group }}</option>
                                    @endif
                                @endforeach
                            </select>
                            <p class="mt-1 text-xs text-gray-500">Select existing group or add a new one</p>
                        </div>
                        <div>
                            <label for="import_component_name" class="block text-sm font-medium text-gray-700 mb-1">Component Name <span class="text-red-500">*</span></label>
                            <input type="text" id="import_component_name" name="component_name" required
                                   placeholder="e.g., Primary Button"
                                   class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary">
                        </div>
                        <div>
                            <label for="import_version" class="block text-sm font-medium text-gray-700 mb-1">Version <span class="text-red-500">*</span></label>
                            <input type="number" id="import_version" name="version" value="1" min="1" required
                                   class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary">
                            <p class="mt-1 text-xs text-gray-500">Component version number</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                            <x-ui.toggle name="is_active" label="Active" :checked="true" />
                        </div>
                    </div>

                    <div class="flex items-center gap-3 mt-6">
                        <button type="button" onclick="closeImportModal()"
                                class="flex-1 px-4 py-2 text-sm text-gray-700 bg-white border border-gray-200 rounded-md hover:bg-gray-50 transition-colors duration-200">
                            Cancel
                        </button>
                        <button type="submit"
                                class="flex-1 px-4 py-2 text-sm bg-primary text-white rounded-md hover:bg-primary/80 transition-colors duration-200">
                            Import
                        </button>
                    </div>
                </div>
            </div>
            </form>
        </div>
    </div>

    {{-- Filters --}}
    <div class="bg-gray-50/50 rounded-md border border-gray-200 p-4">
        <form method="GET" action="{{ route('admin.content.tailwind-plus.index') }}" class="grid grid-cols-1 md:grid-cols-5 gap-4">
            <div>
                <label class="block text-xs font-medium text-gray-700 mb-1">Search</label>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search components..."
                       class="w-full px-3 py-2 text-sm bg-white border border-gray-200 rounded-md focus:outline-none focus:ring-2 focus:ring-primary">
            </div>
            <div>
                <label class="block text-xs font-medium text-gray-700 mb-1">Category</label>
                <select name="category" class="w-full px-3 py-2 text-sm bg-white border border-gray-200 rounded-md focus:outline-none focus:ring-2 focus:ring-primary">
                    <option value="">All Categories</option>
                    @foreach($categories as $category)
                        <option value="{{ $category }}" {{ request('category') === $category ? 'selected' : '' }}>{{ $category }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-xs font-medium text-gray-700 mb-1">Component Group</label>
                <select name="component_group" class="w-full px-3 py-2 text-sm bg-white border border-gray-200 rounded-md focus:outline-none focus:ring-2 focus:ring-primary">
                    <option value="">All Groups</option>
                    @foreach($componentGroups as $group)
                        <option value="{{ $group }}" {{ request('component_group') === $group ? 'selected' : '' }}>{{ $group ?? '-' }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-xs font-medium text-gray-700 mb-1">Status</label>
                <select name="is_active" class="w-full px-3 py-2 text-sm bg-white border border-gray-200 rounded-md focus:outline-none focus:ring-2 focus:ring-primary">
                    <option value="">All</option>
                    <option value="1" {{ request('is_active') === '1' ? 'selected' : '' }}>Active</option>
                    <option value="0" {{ request('is_active') === '0' ? 'selected' : '' }}>Inactive</option>
                </select>
            </div>
            <div class="flex items-end gap-2">
                <button type="submit" class="px-4 py-2 text-sm bg-primary text-white rounded-md hover:bg-primary/80 transition-colors duration-200">
                    <i class="fa-solid fa-filter mr-1"></i> Filter
                </button>
                <a href="{{ route('admin.content.tailwind-plus.index') }}" class="px-4 py-2 text-sm bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300 transition-colors duration-200">
                    <i class="fa-solid fa-times mr-1"></i> Clear
                </a>
            </div>
        </form>
    </div>

    {{-- Components Table --}}
    <div class="bg-gray-50/50 rounded-md border border-gray-200">
        @if($components->count() > 0)
            {{-- Custom Controls Header --}}
            <div class="p-4 border-b border-gray-200 bg-gray-50/80 flex items-center justify-between">
                <div class="text-xs text-gray-600">
                    Showing {{ $components->firstItem() }} to {{ $components->lastItem() }} of {{ $components->total() }} components
                </div>
            </div>

            <div class="overflow-x-auto">
                <table id="componentsTable" class="w-full">
                    <thead class="bg-gray-50/80 border-b border-gray-200">
                        <tr>
                            <th class="text-left py-3 px-4 text-xs font-medium text-gray-500 uppercase tracking-wider">Component</th>
                            <th class="text-left py-3 px-4 text-xs font-medium text-gray-500 uppercase tracking-wider">Category</th>
                            <th class="text-left py-3 px-4 text-xs font-medium text-gray-500 uppercase tracking-wider">Group</th>
                            <th class="text-left py-3 px-4 text-xs font-medium text-gray-500 uppercase tracking-wider">Version</th>
                            <th class="text-left py-3 px-4 text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            <th class="text-left py-3 px-4 text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($components as $component)
                            <tr class="hover:bg-gray-50 transition-colors duration-200">
                                <td class="py-3 px-4">
                                    <div class="text-xs font-medium text-gray-900">{{ $component->component_name }}</div>
                                </td>
                                <td class="py-3 px-4">
                                    <span class="text-xs text-gray-700">{{ $component->category }}</span>
                                </td>
                                <td class="py-3 px-4">
                                    <span class="text-xs text-gray-700">{{ $component->component_group ?? '-' }}</span>
                                </td>
                                <td class="py-3 px-4">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                        v{{ $component->version }}
                                    </span>
                                </td>
                                <td class="py-3 px-4">
                                    <button onclick="toggleActive({{ $component->id }}, {{ $component->is_active ? 'true' : 'false' }})"
                                            class="status-toggle relative inline-flex h-6 w-11 items-center rounded-full transition-colors focus:outline-none {{ $component->is_active ? 'bg-primary' : 'bg-gray-200' }}"
                                            data-component-id="{{ $component->id }}">
                                        <span class="sr-only">Toggle status</span>
                                        <span class="inline-block h-4 w-4 transform rounded-full bg-white transition-transform {{ $component->is_active ? 'translate-x-6' : 'translate-x-1' }}"></span>
                                    </button>
                                </td>
                                <td class="py-3 px-4">
                                    <div class="flex items-center space-x-2">
                                        <button type="button" onclick="previewComponent(event, {{ $component->id }})"
                                                class="text-xs text-gray-600 hover:text-primary transition-colors duration-200"
                                                title="Preview">
                                            <i class="fa-solid fa-eye"></i>
                                        </button>
                                        <a href="{{ route('admin.content.tailwind-plus.show', $component) }}"
                                           class="text-xs text-gray-600 hover:text-blue-600 transition-colors duration-200"
                                           title="View">
                                            <i class="fa-solid fa-info-circle"></i>
                                        </a>
                                        <a href="{{ route('admin.content.tailwind-plus.edit', $component) }}"
                                           class="text-xs text-gray-600 hover:text-blue-600 transition-colors duration-200"
                                           title="Edit">
                                            <i class="fa-solid fa-edit"></i>
                                        </a>
                                        <button type="button" onclick="deleteComponent({{ $component->id }})"
                                                data-component-id="{{ $component->id }}"
                                                data-component-name="{{ e($component->component_name) }}"
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

            {{-- Pagination --}}
            <div class="p-4 border-t border-gray-200 bg-gray-50/80">
                {{ $components->links() }}
            </div>
        @else
            <div class="text-center py-12">
                <i class="fa-solid fa-code text-gray-300 text-3xl mb-2"></i>
                <h3 class="text-base font-medium text-gray-900 mb-1">No components found</h3>
                <p class="text-xs text-gray-500 mb-4">Get started by adding your first component.</p>
                <a href="{{ route('admin.content.tailwind-plus.create') }}"
                   class="px-5 py-2 rounded-md bg-primary text-white text-sm hover:bg-primary/80 transition-colors duration-200 inline-flex items-center space-x-2">
                    <i class="fa-solid fa-plus"></i>
                    <span>Add Component</span>
                </a>
            </div>
        @endif
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
const routes = {
    toggleActive: @json(route('admin.content.tailwind-plus.toggle-active', ['tailwindPlus' => '__ID__'])),
    destroy: @json(route('admin.content.tailwind-plus.destroy', ['tailwindPlus' => '__ID__']))
};

document.addEventListener('DOMContentLoaded', function() {
    @if(session('success'))
        toastr.success('{{ session('success') }}');
    @endif

    @if(session('error'))
        toastr.error('{{ session('error') }}');
    @endif

    document.getElementById('importFromFileBtn').addEventListener('click', function() {
        document.getElementById('importFileInput').click();
    });

    document.getElementById('importFileInput').addEventListener('change', function() {
        if (this.files && this.files.length > 0) {
            const file = this.files[0];
            const nameWithoutExt = file.name.replace(/\.[^.]+$/, '');
            const componentName = nameWithoutExt.length > 0
                ? nameWithoutExt.replace(/[-_]/g, ' ').replace(/\b\w/g, c => c.toUpperCase())
                : 'Imported Component';
            document.getElementById('import_component_name').value = componentName;
            document.getElementById('importModal').classList.remove('hidden');
            document.getElementById('importModal').classList.add('flex');
        }
    });

    document.getElementById('importModal').addEventListener('click', function(e) {
        if (e.target === this) {
            closeImportModal();
        }
    });

    @if($errors->any())
        @foreach ($errors->all() as $error)
            toastr.error('{{ $error }}');
        @endforeach
    @endif
});

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

// Close modal when clicking outside
document.getElementById('previewModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closePreviewModal();
    }
});

function toggleActive(componentId, currentStatus) {
    fetch(routes.toggleActive.replace('__ID__', componentId), {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            const toggle = document.querySelector(`[data-component-id="${componentId}"]`);
            const span = toggle.querySelector('span:last-child');

            if (data.is_active) {
                toggle.classList.remove('bg-gray-200');
                toggle.classList.add('bg-primary');
                span.classList.remove('translate-x-1');
                span.classList.add('translate-x-6');
                toastr.success('Component activated successfully!');
            } else {
                toggle.classList.remove('bg-primary');
                toggle.classList.add('bg-gray-200');
                span.classList.remove('translate-x-6');
                span.classList.add('translate-x-1');
                toastr.warning('Component deactivated successfully!');
            }
        }
    })
    .catch(error => {
        console.error('Error:', error);
        toastr.error('An error occurred while updating the status.');
    });
}

function deleteComponent(componentId) {
    const btn = document.querySelector('[data-component-id="' + componentId + '"]');
    const componentName = btn ? (btn.getAttribute('data-component-name') || '') : '';
    document.getElementById('componentName').textContent = componentName;
    document.getElementById('deleteForm').action = routes.destroy.replace('__ID__', componentId);
    document.getElementById('deleteModal').classList.remove('hidden');
    document.getElementById('deleteModal').classList.add('flex');
}

function closeDeleteModal() {
    document.getElementById('deleteModal').classList.add('hidden');
    document.getElementById('deleteModal').classList.remove('flex');
}

function closeImportModal() {
    document.getElementById('importModal').classList.add('hidden');
    document.getElementById('importModal').classList.remove('flex');
    document.getElementById('importFileInput').value = '';
}

// Close modal when clicking outside
document.getElementById('deleteModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeDeleteModal();
    }
});
</script>
</x-layouts.admin>

