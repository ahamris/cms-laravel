<x-layouts.admin title="Section: {{ $docSection->title }}">
    <div class="flex items-center justify-between mb-6">
        <div class="flex items-center gap-3">
            <div class="w-12 h-12 bg-primary rounded-md flex items-center justify-center">
                <i class="fa-solid fa-folder-open text-white text-base"></i>
            </div>
            <div class="flex flex-col gap-1">
                <h2>{{ $docSection->title }}</h2>
                <p>{{ $docSection->version->name ?? 'N/A' }} — {{ $docSection->pages->count() }} pages</p>
            </div>
        </div>
        <div class="flex items-center gap-2">
            <a href="{{ route('admin.doc-pages.create') }}?doc_section_id={{ $docSection->id }}" class="px-5 py-2 rounded-md bg-primary text-white text-sm hover:bg-primary/80 flex items-center gap-2">
                <i class="fa fa-plus"></i> Add Page
            </a>
            <a href="{{ route('admin.doc-sections.edit', $docSection) }}" class="px-5 py-2 rounded-md bg-white border border-gray-200 text-gray-700 text-sm hover:bg-gray-50 flex items-center gap-2">
                <i class="fa fa-edit"></i> Edit
            </a>
            <a href="{{ route('admin.doc-sections.index') }}" class="px-5 py-2 rounded-md bg-white border border-gray-200 text-gray-700 text-sm hover:bg-gray-50 flex items-center gap-2">
                <i class="fa fa-arrow-left"></i> Back
            </a>
        </div>
    </div>

    @if($docSection->description)
        <div class="bg-gray-50/50 rounded-md border border-gray-200 p-4 mb-4">
            <p class="text-gray-700">{{ $docSection->description }}</p>
        </div>
    @endif

    <div class="bg-gray-50/50 rounded-md border border-gray-200">
        <h3 class="px-4 py-3 border-b border-gray-200 font-semibold text-gray-900">Pages</h3>
        <ul class="divide-y divide-gray-200">
            @forelse($docSection->pages as $page)
                <li class="px-4 py-3 flex items-center justify-between hover:bg-gray-50/50">
                    <div>
                        <a href="{{ route('admin.doc-pages.show', $page) }}" class="font-medium text-gray-900 hover:text-primary">{{ $page->title }}</a>
                        <span class="text-gray-500 ml-2">/ {{ $page->slug }}</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $page->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">{{ $page->is_active ? 'Active' : 'Inactive' }}</span>
                        <a href="{{ route('admin.doc-pages.edit', $page) }}" class="text-gray-500 hover:text-blue-600"><i class="fa-solid fa-edit"></i></a>
                    </div>
                </li>
            @empty
                <li class="px-4 py-6 text-center text-gray-500">No pages. <a href="{{ route('admin.doc-pages.create') }}?doc_section_id={{ $docSection->id }}" class="text-primary hover:underline">Add a page</a>.</li>
            @endforelse
        </ul>
    </div>
</x-layouts.admin>
