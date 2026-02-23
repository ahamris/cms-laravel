<x-layouts.admin title="Blog Category Details">
    <div class="space-y-6">
    {{-- Header --}}
    <div class="flex items-center justify-between">
        <div class="flex items-center gap-3">
            <div class="w-12 h-12 bg-primary rounded-md flex items-center justify-center">
                <i class="fa-solid fa-info-circle text-white text-base"></i>
            </div>
            <div class="flex flex-col gap-1">
                <h2>Blog Category Details</h2>
                <p>View blog category information</p>
            </div>
        </div>
        <div class="flex items-center space-x-3">
            <a href="{{ route('admin.blog-category.edit', $blogCategory) }}" 
               class="px-5 py-2 rounded-md bg-yellow-600 text-white text-sm hover:bg-yellow-700 transition-colors duration-200 flex items-center space-x-2">
                <i class="fa-solid fa-edit"></i>
                <span>Edit</span>
            </a>
            <a href="{{ route('admin.blog-category.index') }}" 
               class="px-5 py-2 rounded-md bg-white border border-gray-200 text-gray-700 text-sm hover:bg-gray-50 transition-colors duration-200 flex items-center space-x-2">
                <i class="fa-solid fa-arrow-left"></i>
                <span>Back to Blog Categories</span>
            </a>
        </div>
    </div>

    {{-- Blog Category Details --}}
    <div class="bg-gray-50/50 rounded-md border border-gray-200">
        <div class="p-6 space-y-6">
            {{-- Basic Information --}}
            <div>
                <h3 class="text-base font-semibold text-gray-900 mb-4 flex items-center">
                    <i class="fa-solid fa-info-circle mr-2 text-blue-500"></i>
                    Basic Information
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-1">Name</label>
                        <p class="text-sm text-gray-900 font-medium">{{ $blogCategory->name }}</p>
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-1">Slug</label>
                        <code class="bg-white text-gray-800 px-2 py-1 rounded text-xs border border-gray-200">{{ $blogCategory->slug }}</code>
                    </div>
                </div>
            </div>

            {{-- Description --}}
            <div>
                <label class="block text-xs font-medium text-gray-700 mb-1">Description</label>
                <div class="bg-white rounded-md p-4 border border-gray-200">
                    <p class="text-xs text-gray-900 whitespace-pre-wrap">{{ $blogCategory->description }}</p>
                </div>
            </div>

            {{-- Color and Status --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-xs font-medium text-gray-700 mb-1">Color</label>
                    <div class="flex items-center space-x-3">
                        <div class="w-8 h-8 rounded-full border border-gray-300" style="background-color: {{ $blogCategory->color }}"></div>
                        <span class="text-xs text-gray-700 font-mono">{{ $blogCategory->color }}</span>
                    </div>
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-700 mb-1">Status</label>
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $blogCategory->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                        <i class="fa-solid {{ $blogCategory->is_active ? 'fa-check-circle' : 'fa-times-circle' }} mr-1"></i>
                        {{ $blogCategory->is_active ? 'Active' : 'Inactive' }}
                    </span>
                </div>
            </div>

            {{-- Timestamps --}}
            <div>
                <h3 class="text-base font-semibold text-gray-900 mb-4 flex items-center">
                    <i class="fa-solid fa-clock mr-2 text-gray-500"></i>
                    Timestamps
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-1">Created At</label>
                        <p class="text-xs text-gray-900">{{ $blogCategory->created_at->format('M d, Y H:i:s') }}</p>
                        <p class="text-xs text-gray-500">{{ $blogCategory->created_at->diffForHumans() }}</p>
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-1">Updated At</label>
                        <p class="text-xs text-gray-900">{{ $blogCategory->updated_at->format('M d, Y H:i:s') }}</p>
                        <p class="text-xs text-gray-500">{{ $blogCategory->updated_at->diffForHumans() }}</p>
                    </div>
                </div>
            </div>
        </div>

        {{-- Actions --}}
        <div class="px-6 py-4 bg-gray-50/80 border-t border-gray-200 rounded-b-md flex items-center justify-end space-x-3">
            <a href="{{ route('admin.blog-category.index') }}" 
               class="px-5 py-2 text-sm text-gray-700 bg-white border border-gray-200 rounded-md hover:bg-gray-50 transition-colors duration-200">
                Back to List
            </a>
            <a href="{{ route('admin.blog-category.edit', $blogCategory) }}" 
               class="px-5 py-2 text-sm text-white bg-primary rounded-md hover:bg-primary/80 transition-colors duration-200">
                Edit Blog Category
            </a>
        </div>
    </div>
</div>
</x-layouts.admin>
