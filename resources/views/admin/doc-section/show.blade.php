<x-layouts.admin title="View Documentation Section">
<div class="space-y-6">
    {{-- Header --}}
    <div class="flex items-center justify-between">
        <div class="flex items-center gap-3">
            <div class="w-12 h-12 bg-primary rounded-md flex items-center justify-center">
                <i class="fa-solid fa-folder text-white text-base"></i>
            </div>
            <div class="flex flex-col gap-1">
                <h2>{{ $docSection->title }}</h2>
                <p>Documentation section details</p>
            </div>
        </div>
        <div class="flex items-center space-x-2">
            <a href="{{ route('admin.content.doc-sections.edit', $docSection) }}" 
               class="px-5 py-2 rounded-md bg-primary text-white text-sm hover:bg-primary/80 transition-colors duration-200 flex items-center space-x-2">
                <i class="fa-solid fa-edit"></i>
                <span>Edit</span>
            </a>
            <a href="{{ route('admin.content.doc-sections.index') }}" 
               class="px-5 py-2 rounded-md bg-white border border-gray-200 text-gray-700 text-sm hover:bg-gray-50 transition-colors duration-200 flex items-center space-x-2">
                <i class="fa-solid fa-arrow-left"></i>
                <span>Back to List</span>
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="md:col-span-2 space-y-6">
            {{-- Pages --}}
            <div class="bg-gray-50/50 rounded-md border border-gray-200">
                <div class="p-6 border-b border-gray-200">
                    <h3 class="text-base font-semibold text-gray-900">Pages</h3>
                </div>
                <div class="p-6">
                    @if($docSection->pages->count() > 0)
                        <div class="space-y-4">
                            @foreach($docSection->pages as $page)
                                <div class="bg-white rounded-md border border-gray-200 p-4">
                                    <div class="flex items-center justify-between">
                                        <div>
                                            <h4 class="text-sm font-medium text-gray-900">{{ $page->title }}</h4>
                                            <p class="text-xs text-gray-400 mt-1">Slug: {{ $page->slug }}</p>
                                        </div>
                                        <div class="flex items-center space-x-2">
                                            <a href="{{ route('admin.content.doc-pages.edit', $page) }}"
                                               class="text-xs text-gray-600 hover:text-blue-600">
                                                <i class="fa-solid fa-edit"></i>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-sm text-gray-500 text-center py-8">No pages found. Create a page to get started.</p>
                    @endif
                </div>
            </div>
        </div>

        <div class="space-y-6">
            {{-- Section Info --}}
            <div class="bg-gray-50/50 rounded-md border border-gray-200">
                <div class="p-6 border-b border-gray-200">
                    <h3 class="text-base font-semibold text-gray-900">Section Information</h3>
                </div>
                <div class="p-6 space-y-4">
                    <div>
                        <p class="text-xs text-gray-500">Title</p>
                        <p class="text-sm font-medium text-gray-900">{{ $docSection->title }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500">Version</p>
                        <p class="text-sm font-medium text-gray-900">{{ $docSection->version->name }} ({{ $docSection->version->version }})</p>
                    </div>
                    @if($docSection->description)
                        <div>
                            <p class="text-xs text-gray-500">Description</p>
                            <p class="text-sm text-gray-900">{{ $docSection->description }}</p>
                        </div>
                    @endif
                    <div>
                        <p class="text-xs text-gray-500">Slug</p>
                        <p class="text-sm font-medium text-gray-900">{{ $docSection->slug }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500">Status</p>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $docSection->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                            {{ $docSection->is_active ? 'Active' : 'Inactive' }}
                        </span>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500">Sort Order</p>
                        <p class="text-sm font-medium text-gray-900">{{ $docSection->sort_order }}</p>
                    </div>
                </div>
            </div>

            <a href="{{ route('admin.content.doc-pages.create') }}?section={{ $docSection->id }}"
               class="block w-full px-4 py-2 rounded-md bg-primary text-white text-sm hover:bg-primary/80 transition-colors duration-200 text-center">
                <i class="fa-solid fa-plus mr-2"></i>
                Add Page
            </a>
        </div>
    </div>
</div>
</x-layouts.admin>

