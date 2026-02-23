<x-layouts.admin title="View Documentation Page">
<div class="space-y-6">
    {{-- Header --}}
    <div class="flex items-center justify-between">
        <div class="flex items-center gap-3">
            <div class="w-12 h-12 bg-primary rounded-md flex items-center justify-center">
                <i class="fa-solid fa-file-lines text-white text-base"></i>
            </div>
            <div class="flex flex-col gap-1">
                <h2>{{ $docPage->title }}</h2>
                <p>Documentation page details</p>
            </div>
        </div>
        <div class="flex items-center space-x-2">
            <a href="{{ route('admin.content.doc-pages.edit', $docPage) }}" 
               class="px-5 py-2 rounded-md bg-primary text-white text-sm hover:bg-primary/80 transition-colors duration-200 flex items-center space-x-2">
                <i class="fa-solid fa-edit"></i>
                <span>Edit</span>
            </a>
            <a href="{{ route('admin.content.doc-pages.index') }}" 
               class="px-5 py-2 rounded-md bg-white border border-gray-200 text-gray-700 text-sm hover:bg-gray-50 transition-colors duration-200 flex items-center space-x-2">
                <i class="fa-solid fa-arrow-left"></i>
                <span>Back to List</span>
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="md:col-span-2 space-y-6">
            {{-- Content Preview --}}
            <div class="bg-gray-50/50 rounded-md border border-gray-200">
                <div class="p-6 border-b border-gray-200">
                    <h3 class="text-base font-semibold text-gray-900">Content Preview</h3>
                </div>
                <div class="p-6 prose max-w-none">
                    {!! $docPage->content !!}
                </div>
            </div>
        </div>

        <div class="space-y-6">
            {{-- Page Info --}}
            <div class="bg-gray-50/50 rounded-md border border-gray-200">
                <div class="p-6 border-b border-gray-200">
                    <h3 class="text-base font-semibold text-gray-900">Page Information</h3>
                </div>
                <div class="p-6 space-y-4">
                    <div>
                        <p class="text-xs text-gray-500">Title</p>
                        <p class="text-sm font-medium text-gray-900">{{ $docPage->title }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500">Section</p>
                        <p class="text-sm font-medium text-gray-900">{{ $docPage->section->title }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500">Version</p>
                        <p class="text-sm font-medium text-gray-900">{{ $docPage->section->version->name }} ({{ $docPage->section->version->version }})</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500">Slug</p>
                        <p class="text-sm font-medium text-gray-900">{{ $docPage->slug }}</p>
                    </div>
                    @if($docPage->meta_title)
                        <div>
                            <p class="text-xs text-gray-500">Meta Title</p>
                            <p class="text-sm text-gray-900">{{ $docPage->meta_title }}</p>
                        </div>
                    @endif
                    @if($docPage->meta_description)
                        <div>
                            <p class="text-xs text-gray-500">Meta Description</p>
                            <p class="text-sm text-gray-900">{{ $docPage->meta_description }}</p>
                        </div>
                    @endif
                    <div>
                        <p class="text-xs text-gray-500">Status</p>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $docPage->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                            {{ $docPage->is_active ? 'Active' : 'Inactive' }}
                        </span>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500">Sort Order</p>
                        <p class="text-sm font-medium text-gray-900">{{ $docPage->sort_order }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</x-layouts.admin>

