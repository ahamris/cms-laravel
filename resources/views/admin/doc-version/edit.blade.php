<x-layouts.admin title="Edit Documentation Version">
    <div class="flex items-center justify-between mb-6">
        <div class="flex items-center gap-3">
            <div class="w-12 h-12 bg-primary rounded-md flex items-center justify-center">
                <i class="fa-solid fa-edit text-white text-base"></i>
            </div>
            <div class="flex flex-col gap-1">
                <h2>Edit Version</h2>
                <p>{{ $docVersion->version }} — {{ $docVersion->name }}</p>
            </div>
        </div>
        <a href="{{ route('admin.doc-versions.index') }}" class="px-5 py-2 rounded-md bg-white border border-gray-200 text-gray-700 text-sm hover:bg-gray-50 transition-colors duration-200 flex items-center space-x-2">
            <i class="fa fa-arrow-left"></i>
            <span>Back to List</span>
        </a>
    </div>

    <div class="bg-gray-50/50 rounded-md border border-gray-200">
        <form action="{{ route('admin.doc-versions.update', $docVersion) }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')
            <div class="p-6 max-w-2xl space-y-6">
                <div>
                    <label for="version" class="block text-xs font-medium text-gray-700 mb-1">Version <span class="text-red-500">*</span></label>
                    <input type="text" id="version" name="version" value="{{ old('version', $docVersion->version) }}" placeholder="e.g. 1.0"
                           class="w-full px-3 py-2 text-sm bg-white border border-gray-200 rounded-md focus:outline-none @error('version') border-red-500 @enderror" required>
                    @error('version')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label for="name" class="block text-xs font-medium text-gray-700 mb-1">Name <span class="text-red-500">*</span></label>
                    <input type="text" id="name" name="name" value="{{ old('name', $docVersion->name) }}" placeholder="e.g. Version 1.0"
                           class="w-full px-3 py-2 text-sm bg-white border border-gray-200 rounded-md focus:outline-none @error('name') border-red-500 @enderror" required>
                    @error('name')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label for="sort_order" class="block text-xs font-medium text-gray-700 mb-1">Sort order</label>
                    <input type="number" id="sort_order" name="sort_order" value="{{ old('sort_order', $docVersion->sort_order) }}" min="0"
                           class="w-full px-3 py-2 text-sm bg-white border border-gray-200 rounded-md focus:outline-none @error('sort_order') border-red-500 @enderror">
                    @error('sort_order')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                </div>
                <div class="flex items-center gap-4">
                    <x-ui.toggle name="is_active" :checked="old('is_active', $docVersion->is_active)" label="Active" />
                    <x-ui.toggle name="is_default" :checked="old('is_default', $docVersion->is_default)" label="Default version" />
                </div>
            </div>
            <div class="px-6 py-4 bg-gray-50/80 border-t border-gray-200 flex items-center justify-end gap-3">
                <a href="{{ route('admin.doc-versions.index') }}" class="px-5 py-2 text-sm text-gray-700 bg-white border border-gray-200 rounded-md hover:bg-gray-50">Cancel</a>
                <button type="submit" class="px-5 py-2 text-sm text-white bg-primary rounded-md hover:bg-primary/80">Update Version</button>
            </div>
        </form>
    </div>
</x-layouts.admin>
