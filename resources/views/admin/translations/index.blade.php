<x-layouts.admin title="Translation Manager">
<div>
    <div class="bg-gray-50/50 rounded-md border border-gray-200">
        <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-primary rounded-md flex items-center justify-center">
                    <i class="fa-solid fa-language text-white text-base"></i>
                </div>
                <div class="flex flex-col gap-1">
                    <h2>Translation Manager</h2>
                    <p>Manage and filter application translations</p>
                </div>
            </div>
            <div class="flex items-center space-x-2">
                <button type="button" class="px-4 py-2 rounded-md bg-yellow-500 hover:bg-yellow-600 text-white text-sm" onclick="loadToCache()">
                    <i class="fas fa-sync mr-2"></i> Load to Cache
                </button>
                <button type="button" class="px-4 py-2 rounded-md bg-gray-600 hover:bg-gray-700 text-white text-sm" onclick="clearCache()">
                    <i class="fas fa-trash mr-2"></i> Clear Cache
                </button>
                <form action="{{ route('admin.translations.run-import-command') }}" method="POST" onsubmit="return confirm('Are you sure you want to import all translations from language files? This may overwrite existing translations.');">
                    @csrf
                    <button type="submit" class="px-4 py-2 rounded-md bg-secondary hover:bg-secondary/90 text-white text-sm">
                        <i class="fas fa-file-import mr-2"></i> Import from Files
                    </button>
                </form>
            </div>
        </div>

        <div class="p-6">
            <!-- Filters -->
            <form method="GET" id="filterForm" class="mb-6">
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <div>
                        <label for="locale" class="block text-sm font-medium text-gray-700 mb-2">Locale</label>
                        <select name="locale" id="locale" class="block bg-white w-full px-3 py-2 border border-gray-200 rounded-md text-gray-900 focus:outline-none text-sm" onchange="submitFilters()">
                            <option value="">All Locales</option>
                            @foreach($locales as $locale)
                                <option value="{{ $locale }}" {{ request('locale') == $locale ? 'selected' : '' }}>
                                    {{ strtoupper($locale) }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label for="group" class="block text-sm font-medium text-gray-700 mb-2">Group</label>
                        <select name="group" id="group" class="block bg-white w-full px-3 py-2 border border-gray-200 rounded-md text-gray-900 focus:outline-none text-sm" onchange="submitFilters()">
                            <option value="">All Groups</option>
                            @foreach($groups as $group)
                                <option value="{{ $group }}" {{ request('group') == $group ? 'selected' : '' }}>
                                    {{ ucfirst($group) }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label for="status" class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                        <select name="status" id="status" class="block bg-white w-full px-3 py-2 border border-gray-200 rounded-md text-gray-900 focus:outline-none text-sm" onchange="submitFilters()">
                            <option value="">All Status</option>
                            <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                            <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                        </select>
                    </div>
                    <div>
                        <label for="search" class="block text-sm font-medium text-gray-700 mb-2">Search</label>
                        <div class="flex">
                            <input type="text" name="search" id="search" class="flex-1 min-w-0 block bg-white w-full px-3 py-2 border border-gray-200 rounded-l-md text-sm focus:outline-none"
                                   placeholder="Search key or value..." value="{{ request('search') }}">
                            <button type="submit" class="px-3 py-2 border border-gray-200 rounded-r-md bg-gray-50 text-gray-600 text-sm">
                                <i class="fas fa-search"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </form>

            <!-- Translations Table -->
            <div class="overflow-hidden border border-gray-200 rounded-md">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50/80">
                        <tr>
                            <th scope="col" class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Key</th>
                            <th scope="col" class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Locale</th>
                            <th scope="col" class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Value</th>
                            <th scope="col" class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Group</th>
                            <th scope="col" class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            <th scope="col" class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Updated</th>
                            <th scope="col" class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-100">
                        @forelse($translations as $translation)
                            <tr class="hover:bg-gray-50">
                                <td class="px-4 py-3 whitespace-nowrap">
                                    <code class="text-xs font-mono text-indigo-600 bg-indigo-50 px-2 py-1 rounded">{{ $translation->translation_key }}</code>
                                </td>
                                <td class="px-4 py-3 whitespace-nowrap">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                        {{ strtoupper($translation->locale) }}
                                    </span>
                                </td>
                                <td class="px-4 py-3">
                                    <div class="inline-edit-container" data-id="{{ $translation->id }}">
                                        <div class="inline-edit-display text-sm text-gray-900 max-w-xs cursor-pointer hover:bg-gray-50 p-1 rounded"
                                             title="Click to edit: {{ $translation->translation_value }}"
                                             onclick="startInlineEdit({{ $translation->id }})">
                                            <span class="truncate block">{{ $translation->translation_value }}</span>
                                            <i class="fas fa-edit text-xs text-gray-400 ml-1"></i>
                                        </div>
                                        <div class="inline-edit-form hidden">
                                            <div class="flex items-center space-x-2">
                                                <textarea class="inline-edit-input flex-1 px-2 py-1 text-sm border border-gray-200 rounded focus:outline-none"
                                                          rows="2">{{ $translation->translation_value }}</textarea>
                                                <div class="flex flex-col space-y-1">
                                                    <button type="button" class="inline-flex items-center px-2 py-1 text-xs font-medium text-white bg-green-600 hover:bg-green-700 rounded"
                                                            onclick="saveInlineEdit({{ $translation->id }})">
                                                        <i class="fas fa-check"></i>
                                                    </button>
                                                    <button type="button" class="inline-flex items-center px-2 py-1 text-xs font-medium text-white bg-gray-600 hover:bg-gray-700 rounded"
                                                            onclick="cancelInlineEdit({{ $translation->id }})">
                                                        <i class="fas fa-times"></i>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-4 py-3 whitespace-nowrap">
                                    @if($translation->group_name)
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                            {{ $translation->group_name }}
                                        </span>
                                    @else
                                        <span class="text-gray-400">-</span>
                                    @endif
                                </td>
                                <td class="px-4 py-3 whitespace-nowrap">
                                    @if($translation->is_active)
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                            Active
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                            Inactive
                                        </span>
                                    @endif
                                </td>
                                <td class="px-4 py-3 whitespace-nowrap text-xs text-gray-500">
                                    {{ $translation->updated_at->diffForHumans() }}
                                </td>
                                <td class="px-4 py-3 whitespace-nowrap text-right text-sm font-medium">
                                    <a href="{{ route('admin.translations.edit', $translation) }}" class="text-primary hover:text-primary/80 mr-3" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <button type="button" class="text-red-600 hover:text-red-800" title="Delete" onclick="deleteTranslation({{ $translation->id }})">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-6 py-12 text-center">
                                    <div class="text-gray-500">
                                        <i class="fas fa-language text-4xl mb-4 text-gray-300"></i>
                                        <p class="text-lg font-medium mb-2">No translations found.</p>
                                        <a href="{{ route('admin.translations.create') }}" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700">
                                            Add First Translation
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if($translations->hasPages())
                <div class="mt-4 flex justify-center">
                    {{ $translations->appends(request()->query())->links() }}
                </div>
            @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Bulk Import Modal -->
<div id="bulkImportModal" class="fixed inset-0 bg-gray-600/50 overflow-y-auto h-full w-full hidden z-50">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <form action="{{ route('admin.translations.bulk-import') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="mt-3">
                <div class="flex items-center justify-between mb-3">
                    <h3 class="text-base font-semibold text-gray-900">Bulk Import Translations</h3>
                    <button type="button" class="text-gray-400 hover:text-gray-600" onclick="closeBulkImportModal()">
                        <i class="fas fa-times"></i>
                    </button>
                </div>

                <div class="space-y-3">
                    <div>
                        <label for="file" class="block text-sm font-medium text-gray-700 mb-2">JSON File</label>
                        <input type="file" class="block bg-white w-full px-3 py-2 border border-gray-200 rounded-md text-sm focus:outline-none" id="file" name="file" accept=".json" required>
                        <p class="mt-1 text-xs text-gray-500">Upload a JSON file with key-value pairs.</p>
                    </div>

                    <div>
                        <label for="import_locale" class="block text-sm font-medium text-gray-700 mb-2">Locale</label>
                        <select name="locale" id="import_locale" class="block bg-white w-full px-3 py-2 border border-gray-200 rounded-md text-sm focus:outline-none" required>
                            <option value="">Select Locale</option>
                            <option value="en">English (EN)</option>
                            <option value="nl">Dutch (NL)</option>
                            <option value="de">German (DE)</option>
                            <option value="fr">French (FR)</option>
                        </select>
                    </div>

                    <div>
                        <label for="import_group" class="block text-sm font-medium text-gray-700 mb-2">Group (Optional)</label>
                        <input type="text" class="block bg-white w-full px-3 py-2 border border-gray-200 rounded-md text-sm focus:outline-none" id="import_group" name="group"
                               placeholder="e.g., frontend, admin, common">
                    </div>
                </div>

                <div class="flex justify-end space-x-3 mt-4">
                    <button type="button" class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-200 rounded-md hover:bg-gray-50 focus:outline-none" onclick="closeBulkImportModal()">
                        Cancel
                    </button>
                    <button type="submit" class="px-4 py-2 text-sm font-medium text-white bg-primary border border-transparent rounded-md hover:bg-primary/90 focus:outline-none">
                        Import
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Delete Form -->
<form id="deleteForm" method="POST" class="hidden">
    @csrf
    @method('DELETE')
</form>

    <script>
function openBulkImportModal() {
    document.getElementById('bulkImportModal').classList.remove('hidden');
}

function closeBulkImportModal() {
    document.getElementById('bulkImportModal').classList.add('hidden');
}

function loadToCache() {
    if (confirm('Load all translations to cache? This may take a moment.')) {
        fetch('{{ route("admin.translations.load-cache") }}', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Content-Type': 'application/json',
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Translations loaded to cache successfully!');
            } else {
                alert('Error loading translations to cache: ' + data.message);
            }
        })
        .catch(error => {
            alert('Error: ' + error.message);
        });
    }
}

function clearCache() {
    const locale = document.getElementById('locale').value;
    const message = locale ? `Clear cache for locale '${locale.toUpperCase()}'?` : 'Clear all translation caches?';

    if (confirm(message)) {
        fetch('{{ route("admin.translations.clear-cache") }}', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({ locale: locale })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Translation cache cleared successfully!');
            } else {
                alert('Error clearing cache: ' + data.message);
            }
        })
        .catch(error => {
            alert('Error: ' + error.message);
        });
    }
}

function submitFilters() {
    document.getElementById('filterForm').submit();
}

function startInlineEdit(id) {
    const container = document.querySelector(`[data-id="${id}"]`);
    const display = container.querySelector('.inline-edit-display');
    const form = container.querySelector('.inline-edit-form');
    const input = container.querySelector('.inline-edit-input');

    display.classList.add('hidden');
    form.classList.remove('hidden');
    input.focus();
    input.select();
}

function cancelInlineEdit(id) {
    const container = document.querySelector(`[data-id="${id}"]`);
    const display = container.querySelector('.inline-edit-display');
    const form = container.querySelector('.inline-edit-form');
    const input = container.querySelector('.inline-edit-input');

    // Reset the input value to original
    const originalValue = display.querySelector('span').textContent;
    input.value = originalValue;

    form.classList.add('hidden');
    display.classList.remove('hidden');
}

function saveInlineEdit(id) {
    const container = document.querySelector(`[data-id="${id}"]`);
    const display = container.querySelector('.inline-edit-display');
    const form = container.querySelector('.inline-edit-form');
    const input = container.querySelector('.inline-edit-input');
    const newValue = input.value.trim();

    if (!newValue) {
        alert('Translation value cannot be empty');
        return;
    }

    // Show loading state
    const saveBtn = form.querySelector('.bg-green-600');
    const originalSaveContent = saveBtn.innerHTML;
    saveBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';
    saveBtn.disabled = true;

    // Send AJAX request
    fetch(`{{ route('admin.translations.index') }}/${id}/inline-update`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Content-Type': 'application/json',
            'Accept': 'application/json'
        },
        body: JSON.stringify({
            translation_value: newValue
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Update the display text
            display.querySelector('span').textContent = newValue;
            display.setAttribute('title', `Click to edit: ${newValue}`);

            // Hide form, show display
            form.classList.add('hidden');
            display.classList.remove('hidden');

            // Show success message briefly
            const successMsg = document.createElement('div');
            successMsg.className = 'fixed top-4 right-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded z-50';
            successMsg.innerHTML = '<i class="fas fa-check mr-2"></i>Translation updated successfully';
            document.body.appendChild(successMsg);

            setTimeout(() => {
                successMsg.remove();
            }, 3000);
        } else {
            alert('Error updating translation: ' + (data.message || 'Unknown error'));
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Error updating translation: ' + error.message);
    })
    .finally(() => {
        // Reset button state
        saveBtn.innerHTML = originalSaveContent;
        saveBtn.disabled = false;
    });
}

function deleteTranslation(id) {
    if (confirm('Are you sure you want to delete this translation?')) {
        const form = document.getElementById('deleteForm');
        form.action = '{{ route("admin.translations.index") }}/' + id;
        form.submit();
    }
}
</script>
    </script>
</x-layouts.admin>
