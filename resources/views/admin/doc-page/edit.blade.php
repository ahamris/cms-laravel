<x-layouts.admin title="Edit Documentation Page">
<div class="space-y-6">
    {{-- Header --}}
    <div class="flex items-center justify-between">
        <div class="flex items-center gap-3">
            <div class="w-12 h-12 bg-primary rounded-md flex items-center justify-center">
                <i class="fa-solid fa-edit text-white text-base"></i>
            </div>
            <div class="flex flex-col gap-1">
                <h2>Edit Documentation Page</h2>
                <p>Update page information</p>
            </div>
        </div>
        <div class="flex items-center space-x-2">
            <a href="{{ route('admin.content.doc-pages.show', $docPage) }}" 
               class="px-5 py-2 rounded-md bg-white border border-gray-200 text-gray-700 text-sm hover:bg-gray-50 transition-colors duration-200 flex items-center space-x-2">
                <i class="fa-solid fa-eye"></i>
                <span>View</span>
            </a>
            <a href="{{ route('admin.content.doc-pages.index') }}" 
               class="px-5 py-2 rounded-md bg-white border border-gray-200 text-gray-700 text-sm hover:bg-gray-50 transition-colors duration-200 flex items-center space-x-2">
                <i class="fa-solid fa-arrow-left"></i>
                <span>Back to List</span>
            </a>
        </div>
    </div>

    <div class="bg-gray-50/50 rounded-md border border-gray-200">
        <form action="{{ route('admin.content.doc-pages.update', $docPage) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="p-6 space-y-6">
                <div class="bg-gray-50/50 rounded-md border border-gray-200 p-6 space-y-6">
                    <h3 class="text-base font-semibold text-gray-900 flex items-center">
                        <i class="fa-solid fa-info-circle mr-2 text-blue-500"></i>
                        Page Information
                    </h3>

                    <div>
                        <label for="doc_section_id" class="block text-xs font-medium text-gray-700 mb-1">
                            Section <span class="text-red-500">*</span>
                        </label>
                        <select id="doc_section_id" 
                                name="doc_section_id" 
                                class="w-full px-3 py-2 text-sm bg-white border border-gray-200 rounded-md focus:outline-none @error('doc_section_id') border-red-500 @enderror" 
                                required>
                            <option value="">Select Section</option>
                            @foreach($sections as $versionName => $versionSections)
                                <optgroup label="{{ $versionName }}">
                                    @foreach($versionSections as $section)
                                        <option value="{{ $section->id }}" {{ old('doc_section_id', $docPage->doc_section_id) == $section->id ? 'selected' : '' }}>
                                            {{ $section->title }}
                                        </option>
                                    @endforeach
                                </optgroup>
                            @endforeach
                        </select>
                        @error('doc_section_id')
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
                               value="{{ old('title', $docPage->title) }}" 
                               class="w-full px-3 py-2 text-sm bg-white border border-gray-200 rounded-md focus:outline-none @error('title') border-red-500 @enderror" 
                               placeholder="e.g., Installation"
                               maxlength="255"
                               required>
                        @error('title')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="content" class="block text-xs font-medium text-gray-700 mb-1">
                            Content <span class="text-red-500">*</span>
                        </label>
                        <x-editor 
                            id="content"
                            name="content"
                            :value="old('content', $docPage->content)"
                            placeholder="Write the documentation page content here..." />
                        @error('content')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="meta_title" class="block text-xs font-medium text-gray-700 mb-1">
                                Meta Title
                            </label>
                            <input type="text" 
                                   id="meta_title" 
                                   name="meta_title" 
                                   value="{{ old('meta_title', $docPage->meta_title) }}" 
                                   class="w-full px-3 py-2 text-sm bg-white border border-gray-200 rounded-md focus:outline-none @error('meta_title') border-red-500 @enderror" 
                                   placeholder="SEO meta title"
                                   maxlength="255">
                            @error('meta_title')
                                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        <x-ui.input id="slug" name="slug" label="Slug" :value="old('slug', $docPage->slug)"
                                placeholder="url-friendly-title"
                                slug-from="title"
                                hint="Leave empty to auto-generate from title."
                                :error="$errors->has('slug')" :errorMessage="$errors->first('slug')" />
                    </div>

                    <div>
                        <label for="meta_description" class="block text-xs font-medium text-gray-700 mb-1">
                            Meta Description
                        </label>
                        <textarea id="meta_description" 
                                  name="meta_description" 
                                  rows="3" 
                                  class="w-full px-3 py-2 text-sm bg-white border border-gray-200 rounded-md focus:outline-none @error('meta_description') border-red-500 @enderror" 
                                  placeholder="SEO meta description"
                                  maxlength="500">{{ old('meta_description', $docPage->meta_description) }}</textarea>
                        @error('meta_description')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="sort_order" class="block text-xs font-medium text-gray-700 mb-1">
                                Sort Order
                            </label>
                            <input type="number" 
                                   id="sort_order" 
                                   name="sort_order" 
                                   value="{{ old('sort_order', $docPage->sort_order) }}" 
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
                                       {{ old('is_active', $docPage->is_active) ? 'checked' : '' }}>
                                <label for="is_active" class="ml-2 text-xs text-gray-700">
                                    Active
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="px-6 py-4 border-t border-gray-200 bg-gray-50/80 flex justify-between rounded-b-md">
                <a href="{{ route('admin.content.doc-pages.index') }}" class="px-5 py-2 rounded-md bg-white border border-gray-200 text-gray-700 text-sm hover:bg-gray-50 transition-colors duration-200">
                    Cancel
                </a>
                <button type="submit" class="px-5 py-2 rounded-md bg-primary text-white text-sm hover:bg-primary/80 transition-colors duration-200">
                    Update Page
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
    const originalSlug = slugInput.value;

    titleInput.addEventListener('input', function() {
        if (!slugInput.value || (!originalSlug && slugInput.dataset.manual !== 'true')) {
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

