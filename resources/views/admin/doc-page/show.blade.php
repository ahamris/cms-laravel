<x-layouts.admin title="Page: {{ $docPage->title }}">
    <div class="flex items-center justify-between mb-6">
        <div class="flex items-center gap-3">
            <div class="w-12 h-12 bg-primary rounded-md flex items-center justify-center">
                <i class="fa-solid fa-file-alt text-white text-base"></i>
            </div>
            <div class="flex flex-col gap-1">
                <h2>{{ $docPage->title }}</h2>
                <p>{{ $docPage->section->version->name ?? 'N/A' }} / {{ $docPage->section->title ?? 'N/A' }} — {{ $docPage->slug }}</p>
            </div>
        </div>
        <div class="flex items-center gap-2">
            <a href="{{ route('admin.doc-pages.edit', $docPage) }}" class="px-5 py-2 rounded-md bg-primary text-white text-sm hover:bg-primary/80 flex items-center gap-2">
                <i class="fa fa-edit"></i> Edit
            </a>
            <a href="{{ route('admin.doc-sections.show', $docPage->section) }}" class="px-5 py-2 rounded-md bg-white border border-gray-200 text-gray-700 text-sm hover:bg-gray-50 flex items-center gap-2">
                <i class="fa fa-folder-open"></i> Section
            </a>
            <a href="{{ route('admin.doc-pages.index') }}" class="px-5 py-2 rounded-md bg-white border border-gray-200 text-gray-700 text-sm hover:bg-gray-50 flex items-center gap-2">
                <i class="fa fa-arrow-left"></i> Back
            </a>
        </div>
    </div>

    <div class="space-y-4">
        <div class="bg-gray-50/50 rounded-md border border-gray-200 p-4 flex flex-wrap gap-4">
            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $docPage->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                {{ $docPage->is_active ? 'Active' : 'Inactive' }}
            </span>
            <span class="text-gray-500">Sort order: {{ $docPage->sort_order }}</span>
            @if($docPage->meta_title)
                <span class="text-gray-500">Meta: {{ Str::limit($docPage->meta_title, 40) }}</span>
            @endif
        </div>

        <div class="bg-gray-50/50 rounded-md border border-gray-200 p-6">
            <h3 class="font-semibold text-gray-900 mb-2">Content</h3>
            <div class="prose max-w-none text-gray-700">
                {!! $docPage->content ?: '<p class="text-gray-500">No content.</p>' !!}
            </div>
        </div>
    </div>
</x-layouts.admin>
