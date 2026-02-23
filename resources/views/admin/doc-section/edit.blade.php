<x-layouts.admin title="Edit Documentation Section">
    <div class="flex items-center justify-between mb-6">
        <div class="flex items-center gap-3">
            <div class="w-12 h-12 bg-primary rounded-md flex items-center justify-center">
                <i class="fa-solid fa-edit text-white text-base"></i>
            </div>
            <div class="flex flex-col gap-1">
                <h2>Edit Section</h2>
                <p>{{ $docSection->title }}</p>
            </div>
        </div>
        <a href="{{ route('admin.doc-sections.index') }}" class="px-5 py-2 rounded-md bg-white border border-gray-200 text-gray-700 text-sm hover:bg-gray-50 flex items-center space-x-2">
            <i class="fa fa-arrow-left"></i>
            <span>Back to List</span>
        </a>
    </div>

    <div class="bg-gray-50/50 rounded-md border border-gray-200">
        <form action="{{ route('admin.doc-sections.update', $docSection) }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')
            <div class="p-6 max-w-2xl space-y-6">
                <div>
                    <label for="doc_version_id" class="block text-xs font-medium text-gray-700 mb-1">Version <span class="text-red-500">*</span></label>
                    <select id="doc_version_id" name="doc_version_id" required class="w-full px-3 py-2 text-sm bg-white border border-gray-200 rounded-md focus:outline-none @error('doc_version_id') border-red-500 @enderror">
                        @foreach($versions as $v)
                            <option value="{{ $v->id }}" {{ old('doc_version_id', $docSection->doc_version_id) == $v->id ? 'selected' : '' }}>{{ $v->name }} ({{ $v->version }})</option>
                        @endforeach
                    </select>
                    @error('doc_version_id')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label for="title" class="block text-xs font-medium text-gray-700 mb-1">Title <span class="text-red-500">*</span></label>
                    <input type="text" id="title" name="title" value="{{ old('title', $docSection->title) }}" class="w-full px-3 py-2 text-sm bg-white border border-gray-200 rounded-md focus:outline-none @error('title') border-red-500 @enderror" required>
                    @error('title')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                </div>
                <x-ui.input id="slug" name="slug" label="Slug" :value="old('slug', $docSection->slug)" placeholder="section-slug" slug-from="title" :error="$errors->has('slug')" :errorMessage="$errors->first('slug')" />
                <div>
                    <label for="description" class="block text-xs font-medium text-gray-700 mb-1">Description</label>
                    <textarea id="description" name="description" rows="3" class="w-full px-3 py-2 text-sm bg-white border border-gray-200 rounded-md focus:outline-none @error('description') border-red-500 @enderror">{{ old('description', $docSection->description) }}</textarea>
                    @error('description')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label for="sort_order" class="block text-xs font-medium text-gray-700 mb-1">Sort order</label>
                    <input type="number" id="sort_order" name="sort_order" value="{{ old('sort_order', $docSection->sort_order) }}" min="0" class="w-full px-3 py-2 text-sm bg-white border border-gray-200 rounded-md focus:outline-none">
                    @error('sort_order')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                </div>
                <x-ui.toggle name="is_active" :checked="old('is_active', $docSection->is_active)" label="Active" />
            </div>
            <div class="px-6 py-4 bg-gray-50/80 border-t border-gray-200 flex items-center justify-end gap-3">
                <a href="{{ route('admin.doc-sections.index') }}" class="px-5 py-2 text-sm text-gray-700 bg-white border border-gray-200 rounded-md hover:bg-gray-50">Cancel</a>
                <button type="submit" class="px-5 py-2 text-sm text-white bg-primary rounded-md hover:bg-primary/80">Update Section</button>
            </div>
        </form>
    </div>
</x-layouts.admin>
