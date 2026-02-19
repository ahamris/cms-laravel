<x-layouts.admin title="Edit Translation">
<div>
    <div class="bg-gray-50/50 rounded-md border border-gray-200">
        <div class="px-6 py-4 border-b border-gray-200 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-primary rounded-md flex items-center justify-center">
                    <i class="fa-solid fa-pen text-white text-base"></i>
                </div>
                <div class="flex flex-col gap-1">
                    <h2>Edit Translation</h2>
                    <p>Update translation key/value and metadata</p>
                </div>
            </div>
            <a href="{{ route('admin.translations.index') }}" class="px-4 py-2 rounded-md bg-white border border-gray-200 text-gray-700 text-sm">
                <i class="fas fa-arrow-left mr-2"></i> Back to List
            </a>
        </div>

        <form action="{{ route('admin.translations.update', $translation) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-12 gap-4">
                    <div class="md:col-span-6">
                        <label for="translation_key" class="block text-sm font-medium text-gray-700 mb-2">
                            Translation Key <span class="text-red-500">*</span>
                        </label>
                        <input type="text" class="block bg-white w-full px-3 py-2 border border-gray-200 rounded-md text-sm focus:outline-none @error('translation_key') border-red-300 @enderror"
                               id="translation_key" name="translation_key"
                               value="{{ old('translation_key', $translation->translation_key) }}"
                               placeholder="e.g., welcome_message, nav.home" required>
                        @error('translation_key')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                        <p class="mt-1 text-xs text-gray-500">Use dot notation for nested keys (e.g., nav.home, forms.submit)</p>
                    </div>

                    <div class="md:col-span-3">
                        <label for="locale" class="block text-sm font-medium text-gray-700 mb-2">
                            Locale <span class="text-red-500">*</span>
                        </label>
                        <select class="block bg-white w-full px-3 py-2 border border-gray-200 rounded-md text-sm focus:outline-none @error('locale') border-red-300 @enderror" id="locale" name="locale" required>
                            <option value="">Select Locale</option>
                            <option value="en" {{ old('locale', $translation->locale) == 'en' ? 'selected' : '' }}>English (EN)</option>
                            <option value="nl" {{ old('locale', $translation->locale) == 'nl' ? 'selected' : '' }}>Dutch (NL)</option>
                            <option value="de" {{ old('locale', $translation->locale) == 'de' ? 'selected' : '' }}>German (DE)</option>
                            <option value="fr" {{ old('locale', $translation->locale) == 'fr' ? 'selected' : '' }}>French (FR)</option>
                            <option value="es" {{ old('locale', $translation->locale) == 'es' ? 'selected' : '' }}>Spanish (ES)</option>
                            <option value="it" {{ old('locale', $translation->locale) == 'it' ? 'selected' : '' }}>Italian (IT)</option>
                        </select>
                        @error('locale')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="md:col-span-3">
                        <label for="group_name" class="block text-sm font-medium text-gray-700 mb-2">Group</label>
                        <input type="text" class="block bg-white w-full px-3 py-2 border border-gray-200 rounded-md text-sm focus:outline-none @error('group_name') border-red-300 @enderror"
                               id="group_name" name="group_name"
                               value="{{ old('group_name', $translation->group_name) }}"
                               placeholder="e.g., frontend, admin, common">
                        @error('group_name')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                        <p class="mt-1 text-xs text-gray-500">Optional grouping for organization</p>
                    </div>
                </div>

                <div class="mt-6">
                    <label for="translation_value" class="block text-sm font-medium text-gray-700 mb-2">
                        Translation Value <span class="text-red-500">*</span>
                    </label>
                    <textarea class="block bg-white w-full px-3 py-2 border border-gray-200 rounded-md text-sm focus:outline-none @error('translation_value') border-red-300 @enderror"
                              id="translation_value" name="translation_value" rows="4" required
                              placeholder="Enter the translated text...">{{ old('translation_value', $translation->translation_value) }}</textarea>
                    @error('translation_value')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                    <p class="mt-1 text-xs text-gray-500">
                        You can use Laravel translation placeholders like :name, :count, etc.
                    </p>
                </div>

                <div class="mt-6">
                    <x-ui.toggle 
                        name="is_active"
                        :checked="old('is_active', $translation->is_active)"
                        label="Active"
                    />
                    <p class="mt-1 text-xs text-gray-500">Inactive translations won't be used in the application</p>
                </div>

                <!-- Preview Section -->
                <div class="mt-6">
                    <h4 class="text-sm font-semibold text-gray-900 mb-3">Preview</h4>
                    <div class="bg-gray-50 rounded-md p-4 border border-gray-200">
                        <div class="grid grid-cols-1 md:grid-cols-4 gap-3">
                            <div>
                                <div class="text-sm font-medium text-gray-700">Key:</div>
                                <div id="preview-key" class="text-xs text-gray-500 mt-1">{{ $translation->translation_key }}</div>
                            </div>
                            <div>
                                <div class="text-sm font-medium text-gray-700">Locale:</div>
                                <div id="preview-locale" class="text-xs text-gray-500 mt-1">{{ strtoupper($translation->locale) }}</div>
                            </div>
                            <div>
                                <div class="text-sm font-medium text-gray-700">Group:</div>
                                <div id="preview-group" class="text-xs text-gray-500 mt-1">{{ $translation->group_name ?: '-' }}</div>
                            </div>
                            <div>
                                <div class="text-sm font-medium text-gray-700">Value:</div>
                                <div id="preview-value" class="text-xs text-gray-500 mt-1 break-words">{{ $translation->translation_value }}</div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Translation Info -->
                <div class="mt-6">
                    <h4 class="text-sm font-semibold text-gray-900 mb-3">Translation Info</h4>
                    <div class="bg-gray-50 rounded-md p-4 border border-gray-200">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                            <div>
                                <div class="text-sm font-medium text-gray-700">Created:</div>
                                <div class="text-xs text-gray-500 mt-1">
                                    {{ $translation->created_at->format('M d, Y H:i') }}
                                    ({{ $translation->created_at->diffForHumans() }})
                                </div>
                            </div>
                            <div>
                                <div class="text-sm font-medium text-gray-700">Last Updated:</div>
                                <div class="text-xs text-gray-500 mt-1">
                                    {{ $translation->updated_at->format('M d, Y H:i') }}
                                    ({{ $translation->updated_at->diffForHumans() }})
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="px-6 py-4 border-t border-gray-200 flex justify-end space-x-3">
                <a href="{{ route('admin.translations.index') }}" class="px-6 py-2 rounded-md bg-white border border-gray-200 text-gray-700 text-sm">
                    <i class="fas fa-times mr-2"></i> Cancel
                </a>
                <button type="submit" class="px-6 py-2 rounded-md bg-primary text-white text-sm">
                    <i class="fas fa-save mr-2"></i> Update Translation
                </button>
            </div>
        </form>
    </div>
</div>

    <script>
document.addEventListener('DOMContentLoaded', function() {
    const keyInput = document.getElementById('translation_key');
    const localeSelect = document.getElementById('locale');
    const groupInput = document.getElementById('group_name');
    const valueTextarea = document.getElementById('translation_value');

    const previewKey = document.getElementById('preview-key');
    const previewLocale = document.getElementById('preview-locale');
    const previewGroup = document.getElementById('preview-group');
    const previewValue = document.getElementById('preview-value');

    function updatePreview() {
        previewKey.textContent = keyInput.value || '-';
        previewLocale.textContent = localeSelect.value ? localeSelect.value.toUpperCase() : '-';
        previewGroup.textContent = groupInput.value || '-';
        previewValue.textContent = valueTextarea.value || '-';
    }

    keyInput.addEventListener('input', updatePreview);
    localeSelect.addEventListener('change', updatePreview);
    groupInput.addEventListener('input', updatePreview);
    valueTextarea.addEventListener('input', updatePreview);
});
</script>
    </script>
</x-layouts.admin>
