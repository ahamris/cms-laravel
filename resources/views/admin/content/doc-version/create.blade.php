<x-layouts.admin title="Create Documentation Version">
<div class="space-y-6">
    {{-- Header --}}
    <div class="flex items-center justify-between">
        <div class="flex items-center gap-3">
            <div class="w-12 h-12 bg-primary rounded-md flex items-center justify-center">
                <i class="fa-solid fa-plus text-white text-base"></i>
            </div>
            <div class="flex flex-col gap-1">
                <h2>Create Documentation Version</h2>
                <p>Add a new documentation version</p>
            </div>
        </div>
        <a href="{{ route('admin.content.doc-versions.index') }}" 
           class="px-5 py-2 rounded-md bg-white border border-gray-200 text-gray-700 text-sm hover:bg-gray-50 transition-colors duration-200 flex items-center space-x-2">
            <i class="fa-solid fa-arrow-left"></i>
            <span>Back to List</span>
        </a>
    </div>

    <div class="bg-gray-50/50 rounded-md border border-gray-200">
        <form action="{{ route('admin.content.doc-versions.store') }}" method="POST">
            @csrf
            <div class="p-6 space-y-6">
                <div class="bg-gray-50/50 rounded-md border border-gray-200 p-6 space-y-6">
                    <h3 class="text-base font-semibold text-gray-900 flex items-center">
                        <i class="fa-solid fa-info-circle mr-2 text-blue-500"></i>
                        Version Information
                    </h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="version" class="block text-xs font-medium text-gray-700 mb-1">
                                Version <span class="text-red-500">*</span>
                            </label>
                            <input type="text" 
                                   id="version" 
                                   name="version" 
                                   value="{{ old('version') }}" 
                                   class="w-full px-3 py-2 text-sm bg-white border border-gray-200 rounded-md focus:outline-none @error('version') border-red-500 @enderror" 
                                   placeholder="e.g., 0.6, 0.7, 1.0"
                                   maxlength="50"
                                   required>
                            <p class="mt-1 text-xs text-gray-500">Version identifier (e.g., 0.6, 12.x)</p>
                            @error('version')
                                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label for="name" class="block text-xs font-medium text-gray-700 mb-1">
                                Display Name <span class="text-red-500">*</span>
                            </label>
                            <input type="text" 
                                   id="name" 
                                   name="name" 
                                   value="{{ old('name') }}" 
                                   class="w-full px-3 py-2 text-sm bg-white border border-gray-200 rounded-md focus:outline-none @error('name') border-red-500 @enderror" 
                                   placeholder="e.g., Version 0.6"
                                   maxlength="255"
                                   required>
                            @error('name')
                                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
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
                        <div class="flex items-end">
                            <div class="flex items-center h-10">
                                <input type="checkbox" 
                                       id="is_default" 
                                       name="is_default" 
                                       value="1" 
                                       class="h-4 w-4 text-primary border-gray-300 rounded focus:outline-none" 
                                       {{ old('is_default', false) ? 'checked' : '' }}>
                                <label for="is_default" class="ml-2 text-xs text-gray-700">
                                    Default Version
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="px-6 py-4 border-t border-gray-200 bg-gray-50/80 flex justify-between rounded-b-md">
                <a href="{{ route('admin.content.doc-versions.index') }}" class="px-5 py-2 rounded-md bg-white border border-gray-200 text-gray-700 text-sm hover:bg-gray-50 transition-colors duration-200">
                    Cancel
                </a>
                <button type="submit" class="px-5 py-2 rounded-md bg-primary text-white text-sm hover:bg-primary/80 transition-colors duration-200">
                    Create Version
                </button>
            </div>
        </form>
    </div>
</div>
</x-layouts.admin>

