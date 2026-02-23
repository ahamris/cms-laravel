<x-layouts.admin title="Create Documentation Section">
<div class="space-y-6">
    {{-- Header --}}
    <div class="flex items-center justify-between">
        <div class="flex items-center gap-3">
            <div class="w-12 h-12 bg-primary rounded-md flex items-center justify-center">
                <i class="fa-solid fa-plus text-white text-base"></i>
            </div>
            <div class="flex flex-col gap-1">
                <h2>Create Documentation Section</h2>
                <p>Add a new documentation section</p>
            </div>
        </div>
        <a href="{{ route('admin.doc-sections.index') }}" 
           class="px-5 py-2 rounded-md bg-white border border-gray-200 text-gray-700 text-sm hover:bg-gray-50 transition-colors duration-200 flex items-center space-x-2">
            <i class="fa-solid fa-arrow-left"></i>
            <span>Back to List</span>
        </a>
    </div>

    <div class="bg-gray-50/50 rounded-md border border-gray-200">
        <form action="{{ route('admin.doc-sections.store') }}" method="POST">
            @csrf
            <div class="p-6 space-y-6">
                <div class="bg-gray-50/50 rounded-md border border-gray-200 p-6 space-y-6">
                    <h3 class="text-base font-semibold text-gray-900 flex items-center">
                        <i class="fa-solid fa-info-circle mr-2 text-blue-500"></i>
                        Section Information
                    </h3>

                    <div>
                        <label for="doc_version_id" class="block text-xs font-medium text-gray-700 mb-1">
                            Version <span class="text-red-500">*</span>
                        </label>
                        <select id="doc_version_id" 
                                name="doc_version_id" 
                                class="w-full px-3 py-2 text-sm bg-white border border-gray-200 rounded-md focus:outline-none @error('doc_version_id') border-red-500 @enderror" 
                                required>
                            <option value="">Select Version</option>
                            @foreach($versions as $version)
                                <option value="{{ $version->id }}" {{ old('doc_version_id', request('version')) == $version->id ? 'selected' : '' }}>
                                    {{ $version->name }} ({{ $version->version }})
                                </option>
                            @endforeach
                        </select>
                        @error('doc_version_id')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="title" class="block text-xs font-medium text-gray-700 mb-1">
                            Title <span class="text-red-500">*</span>
                        </label>
                        <input type="text" 
                               id="title" 
                               name="title" 
                               value="{{ old('title') }}" 
                               class="w-full px-3 py-2 text-sm bg-white border border-gray-200 rounded-md focus:outline-none @error('title') border-red-500 @enderror" 
                               placeholder="e.g., Getting Started"
                               maxlength="255"
                               required>
                        @error('title')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="description" class="block text-xs font-medium text-gray-700 mb-1">
                            Description
                        </label>
                        <textarea id="description" 
                                  name="description" 
                                  rows="3" 
                                  class="w-full px-3 py-2 text-sm bg-white border border-gray-200 rounded-md focus:outline-none @error('description') border-red-500 @enderror" 
                                  placeholder="Brief description of this section"
                                  maxlength="1000">{{ old('description') }}</textarea>
                        @error('description')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <label for="slug" class="block text-xs font-medium text-gray-700 mb-1">
                                Slug
                            </label>
                            <input type="text" 
                                   id="slug" 
                                   name="slug" 
                                   value="{{ old('slug') }}" 
                                   class="w-full px-3 py-2 text-sm bg-white border border-gray-200 rounded-md focus:outline-none @error('slug') border-red-500 @enderror" 
                                   placeholder="Auto-generated from title">
                            <p class="mt-1 text-xs text-gray-500">Leave empty to auto-generate from title</p>
                            @error('slug')
                                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label for="sort_order" class="block text-xs font-medium text-gray-700 mb-1">
                                Sort Order
                            </label>
                            <input type="number" 
                                   id="sort_order" 
                                   name="sort_order" 
                                   value="{{ old('sort_order', 0) }}" 
                                   class="w-full px-3 py-2 text-sm bg-white border border-gray-200 rounded-md focus:outline-none @error('sort_order') border-red-500 @enderror" 
                                   min="0"
                                   placeholder="0">
                            @error('sort_order')
                                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        <div class="flex items-end">
                            <div class="flex items-center h-10">
                                <input type="checkbox" 
                                       id="is_active" 
                                       name="is_active" 
                                       value="1" 
                                       class="h-4 w-4 text-primary border-gray-300 rounded focus:outline-none" 
                                       {{ old('is_active', true) ? 'checked' : '' }}>
                                <label for="is_active" class="ml-2 text-xs text-gray-700">
                                    Active
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="px-6 py-4 border-t border-gray-200 bg-gray-50/80 flex justify-between rounded-b-md">
                <a href="{{ route('admin.doc-sections.index') }}" class="px-5 py-2 rounded-md bg-white border border-gray-200 text-gray-700 text-sm hover:bg-gray-50 transition-colors duration-200">
                    Cancel
                </a>
                <button type="submit" class="px-5 py-2 rounded-md bg-primary text-white text-sm hover:bg-primary/80 transition-colors duration-200">
                    Create Section
                </button>
            </div>
        </form>
    </div>
</div>

<x-slot:scripts>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const titleInput = document.getElementById('title');
    const slugInput = document.getElementById('slug');

    titleInput.addEventListener('input', function() {
        if (!slugInput.value || slugInput.dataset.manual !== 'true') {
            const slug = this.value
                .toLowerCase()
                .replace(/[^a-z0-9\s-]/g, '')
                .replace(/\s+/g, '-')
                .replace(/-+/g, '-')
                .trim('-');
            slugInput.value = slug;
        }
    });

    slugInput.addEventListener('input', function() {
        this.dataset.manual = 'true';
    });
});
</script>
</x-slot:scripts>
</x-layouts.admin>

