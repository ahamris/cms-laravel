<x-layouts.admin title="View Documentation Version">
<div class="space-y-6">
    {{-- Header --}}
    <div class="flex items-center justify-between">
        <div class="flex items-center gap-3">
            <div class="w-12 h-12 bg-primary rounded-md flex items-center justify-center">
                <i class="fa-solid fa-book text-white text-base"></i>
            </div>
            <div class="flex flex-col gap-1">
                <h2>{{ $docVersion->name }} ({{ $docVersion->version }})</h2>
                <p>Documentation version details</p>
            </div>
        </div>
        <div class="flex items-center space-x-2">
            <a href="{{ route('admin.doc-versions.edit', $docVersion) }}" 
               class="px-5 py-2 rounded-md bg-primary text-white text-sm hover:bg-primary/80 transition-colors duration-200 flex items-center space-x-2">
                <i class="fa-solid fa-edit"></i>
                <span>Edit</span>
            </a>
            <a href="{{ route('admin.doc-versions.index') }}" 
               class="px-5 py-2 rounded-md bg-white border border-gray-200 text-gray-700 text-sm hover:bg-gray-50 transition-colors duration-200 flex items-center space-x-2">
                <i class="fa-solid fa-arrow-left"></i>
                <span>Back to List</span>
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="md:col-span-2 space-y-6">
            {{-- Sections --}}
            <div class="bg-gray-50/50 rounded-md border border-gray-200">
                <div class="p-6 border-b border-gray-200">
                    <h3 class="text-base font-semibold text-gray-900">Sections</h3>
                </div>
                <div class="p-6">
                    @if($docVersion->sections->count() > 0)
                        <div class="space-y-4">
                            @foreach($docVersion->sections as $section)
                                <div class="bg-white rounded-md border border-gray-200 p-4">
                                    <div class="flex items-center justify-between">
                                        <div>
                                            <h4 class="text-sm font-medium text-gray-900">{{ $section->title }}</h4>
                                            @if($section->description)
                                                <p class="text-xs text-gray-500 mt-1">{{ Str::limit($section->description, 100) }}</p>
                                            @endif
                                            <p class="text-xs text-gray-400 mt-2">
                                                {{ $section->pages->count() }} page(s)
                                            </p>
                                        </div>
                                        <div class="flex items-center space-x-2">
                                            <a href="{{ route('admin.doc-sections.edit', $section) }}"
                                               class="text-xs text-gray-600 hover:text-blue-600">
                                                <i class="fa-solid fa-edit"></i>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-sm text-gray-500 text-center py-8">No sections found. Create a section to get started.</p>
                    @endif
                </div>
            </div>
        </div>

        <div class="space-y-6">
            {{-- Version Info --}}
            <div class="bg-gray-50/50 rounded-md border border-gray-200">
                <div class="p-6 border-b border-gray-200">
                    <h3 class="text-base font-semibold text-gray-900">Version Information</h3>
                </div>
                <div class="p-6 space-y-4">
                    <div>
                        <p class="text-xs text-gray-500">Version</p>
                        <p class="text-sm font-medium text-gray-900">{{ $docVersion->version }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500">Name</p>
                        <p class="text-sm font-medium text-gray-900">{{ $docVersion->name }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500">Status</p>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $docVersion->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                            {{ $docVersion->is_active ? 'Active' : 'Inactive' }}
                        </span>
                    </div>
                    @if($docVersion->is_default)
                        <div>
                            <p class="text-xs text-gray-500">Default</p>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                Default Version
                            </span>
                        </div>
                    @endif
                    <div>
                        <p class="text-xs text-gray-500">Sort Order</p>
                        <p class="text-sm font-medium text-gray-900">{{ $docVersion->sort_order }}</p>
                    </div>
                </div>
            </div>

            <a href="{{ route('admin.doc-sections.create') }}?version={{ $docVersion->id }}"
               class="block w-full px-4 py-2 rounded-md bg-primary text-white text-sm hover:bg-primary/80 transition-colors duration-200 text-center">
                <i class="fa-solid fa-plus mr-2"></i>
                Add Section
            </a>
        </div>
    </div>
</div>
</x-layouts.admin>

