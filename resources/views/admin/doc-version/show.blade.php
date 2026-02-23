<x-layouts.admin title="Documentation Version: {{ $docVersion->name }}">
    <div class="flex items-center justify-between mb-6">
        <div class="flex items-center gap-3">
            <div class="w-12 h-12 bg-primary rounded-md flex items-center justify-center">
                <i class="fa-solid fa-book text-white text-base"></i>
            </div>
            <div class="flex flex-col gap-1">
                <h2>{{ $docVersion->name }}</h2>
                <p>Version {{ $docVersion->version }} — {{ $docVersion->sections->count() }} sections, {{ $docVersion->sections->sum(fn($s) => $s->pages->count()) }} pages</p>
            </div>
        </div>
        <div class="flex items-center gap-2">
            <a href="{{ route('admin.doc-versions.edit', $docVersion) }}" class="px-5 py-2 rounded-md bg-primary text-white text-sm hover:bg-primary/80 flex items-center gap-2">
                <i class="fa fa-edit"></i> Edit
            </a>
            <a href="{{ route('admin.doc-versions.index') }}" class="px-5 py-2 rounded-md bg-white border border-gray-200 text-gray-700 text-sm hover:bg-gray-50 flex items-center gap-2">
                <i class="fa fa-arrow-left"></i> Back
            </a>
        </div>
    </div>

    <div class="space-y-4">
        <div class="bg-gray-50/50 rounded-md border border-gray-200 p-4 flex flex-wrap gap-4">
            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $docVersion->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                {{ $docVersion->is_active ? 'Active' : 'Inactive' }}
            </span>
            @if($docVersion->is_default)
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">Default</span>
            @endif
            <span class="text-gray-500">Sort order: {{ $docVersion->sort_order }}</span>
        </div>

        <div class="bg-gray-50/50 rounded-md border border-gray-200">
            <h3 class="px-4 py-3 border-b border-gray-200 font-semibold text-gray-900">Sections</h3>
            <ul class="divide-y divide-gray-200">
                @forelse($docVersion->sections as $section)
                    <li class="px-4 py-3 flex items-center justify-between hover:bg-gray-50/50">
                        <div>
                            <a href="{{ route('admin.doc-sections.show', $section) }}" class="font-medium text-gray-900 hover:text-primary">{{ $section->title }}</a>
                            <span class="text-gray-500 ml-2">({{ $section->pages->count() }} pages)</span>
                        </div>
                        <a href="{{ route('admin.doc-sections.edit', $section) }}" class="text-gray-500 hover:text-blue-600"><i class="fa-solid fa-edit"></i></a>
                    </li>
                @empty
                    <li class="px-4 py-6 text-center text-gray-500">No sections. <a href="{{ route('admin.doc-sections.create') }}" class="text-primary hover:underline">Create a section</a> for this version.</li>
                @endforelse
            </ul>
        </div>
    </div>
</x-layouts.admin>
