<x-layouts.admin title="Edit Documentation Page">
    <div class="flex items-center justify-between mb-6">
        <div class="flex items-center gap-3">
            <div class="w-12 h-12 bg-primary rounded-md flex items-center justify-center">
                <i class="fa-solid fa-edit text-white text-base"></i>
            </div>
            <div class="flex flex-col gap-1">
                <h2>Edit Page</h2>
                <p>{{ $docPage->title }}</p>
            </div>
        </div>
        <a href="{{ route('admin.doc-pages.index') }}" class="px-5 py-2 rounded-md bg-white border border-gray-200 text-gray-700 text-sm hover:bg-gray-50 flex items-center space-x-2">
            <i class="fa fa-arrow-left"></i>
            <span>Back to List</span>
        </a>
    </div>

    <div class="bg-gray-50/50 rounded-md border border-gray-200">
        <form action="{{ route('admin.doc-pages.update', $docPage) }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')
            <div class="p-6 grid grid-cols-1 lg:grid-cols-3 gap-6">
                <div class="lg:col-span-2 space-y-6">
                    <div class="bg-gray-50/50 rounded-md border border-gray-200 p-6">
                        <h3 class="text-base font-semibold text-gray-900 mb-4">Basic Information</h3>
                        <div class="space-y-4">
                            <div>
                                <label for="doc_section_id" class="block text-xs font-medium text-gray-700 mb-1">Section <span class="text-red-500">*</span></label>
                                <select id="doc_section_id" name="doc_section_id" required class="w-full px-3 py-2 text-sm bg-white border border-gray-200 rounded-md focus:outline-none @error('doc_section_id') border-red-500 @enderror">
                                    @foreach($sections as $versionName => $versionSections)
                                        <optgroup label="{{ $versionName }}">
                                            @foreach($versionSections as $sec)
                                                <option value="{{ $sec->id }}" {{ old('doc_section_id', $docPage->doc_section_id) == $sec->id ? 'selected' : '' }}>{{ $sec->title }}</option>
                                            @endforeach
                                        </optgroup>
                                    @endforeach
                                </select>
                                @error('doc_section_id')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                            </div>
                            <div>
                                <label for="title" class="block text-xs font-medium text-gray-700 mb-1">Title <span class="text-red-500">*</span></label>
                                <input type="text" id="title" name="title" value="{{ old('title', $docPage->title) }}" class="w-full px-3 py-2 text-sm bg-white border border-gray-200 rounded-md focus:outline-none @error('title') border-red-500 @enderror" required>
                                @error('title')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                            </div>
                            <x-ui.input id="slug" name="slug" label="Slug" :value="old('slug', $docPage->slug)" placeholder="page-slug" slug-from="title" :error="$errors->has('slug')" :errorMessage="$errors->first('slug')" />
                            <div>
                                <label for="content" class="block text-sm font-medium text-gray-700 mb-2">Content <span class="text-red-500">*</span></label>
                                <x-editor id="content" name="content" :value="old('content', $docPage->content)" placeholder="Page content..." />
                                @error('content')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                            </div>
                        </div>
                    </div>
                    <div class="bg-gray-50/50 rounded-md border border-gray-200 p-6">
                        <h3 class="text-base font-semibold text-gray-900 mb-4">SEO</h3>
                        <div class="space-y-4">
                            <div>
                                <label for="meta_title" class="block text-xs font-medium text-gray-700 mb-1">Meta title</label>
                                <input type="text" id="meta_title" name="meta_title" value="{{ old('meta_title', $docPage->meta_title) }}" class="w-full px-3 py-2 text-sm bg-white border border-gray-200 rounded-md focus:outline-none @error('meta_title') border-red-500 @enderror">
                                @error('meta_title')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                            </div>
                            <div>
                                <label for="meta_description" class="block text-xs font-medium text-gray-700 mb-1">Meta description</label>
                                <textarea id="meta_description" name="meta_description" rows="2" class="w-full px-3 py-2 text-sm bg-white border border-gray-200 rounded-md focus:outline-none @error('meta_description') border-red-500 @enderror">{{ old('meta_description', $docPage->meta_description) }}</textarea>
                                @error('meta_description')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                            </div>
                        </div>
                    </div>
                </div>
                <div class="space-y-6">
                    <div class="bg-gray-50/50 rounded-md border border-gray-200 p-6">
                        <h3 class="text-base font-semibold text-gray-900 mb-4">Settings</h3>
                        <div class="space-y-4">
                            <div>
                                <label for="sort_order" class="block text-xs font-medium text-gray-700 mb-1">Sort order</label>
                                <input type="number" id="sort_order" name="sort_order" value="{{ old('sort_order', $docPage->sort_order) }}" min="0" class="w-full px-3 py-2 text-sm bg-white border border-gray-200 rounded-md focus:outline-none">
                                @error('sort_order')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                            </div>
                            <x-ui.toggle name="is_active" :checked="old('is_active', $docPage->is_active)" label="Active" />
                        </div>
                    </div>
                </div>
            </div>
            <div class="px-6 py-4 bg-gray-50/80 border-t border-gray-200 flex items-center justify-end gap-3">
                <a href="{{ route('admin.doc-pages.index') }}" class="px-5 py-2 text-sm text-gray-700 bg-white border border-gray-200 rounded-md hover:bg-gray-50">Cancel</a>
                <button type="submit" class="px-5 py-2 text-sm text-white bg-primary rounded-md hover:bg-primary/80">Update Page</button>
            </div>
        </form>
    </div>
</x-layouts.admin>
