<x-layouts.admin title="Documentation Sections">
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <div class="flex items-center gap-3">
            <div class="w-12 h-12 bg-primary rounded-md flex items-center justify-center">
                <i class="fa-solid fa-folder-open text-white text-base"></i>
            </div>
            <div class="flex flex-col gap-1">
                <h2>Documentation Sections</h2>
                <p>Sections belong to a version; each section contains pages.</p>
            </div>
        </div>
        <a href="{{ route('admin.doc-versions.index') }}" class="px-5 py-2 rounded-md bg-white border border-gray-200 text-gray-700 text-sm hover:bg-gray-50 flex items-center space-x-2">
            <i class="fa fa-book"></i>
            <span>Versions</span>
        </a>
        <a href="{{ route('admin.doc-sections.create') }}" class="px-5 py-2 rounded-md bg-primary text-white text-sm hover:bg-primary/80 flex items-center space-x-2">
            <i class="fa fa-plus"></i>
            <span>Add Section</span>
        </a>
    </div>

    @if(session('success'))
        <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-md text-sm">{{ session('success') }}</div>
    @endif
    @if($errors->has('error'))
        <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-md text-sm">{{ $errors->first('error') }}</div>
    @endif

    <div class="bg-gray-50/50 rounded-md border border-gray-200">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50/80 border-b border-gray-200">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Version</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Title</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Slug</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Order</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Pages</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($sections as $section)
                        <tr class="hover:bg-gray-50">
                            <td class="px-4 py-3 whitespace-nowrap text-gray-600">{{ $section->version->name ?? '-' }}</td>
                            <td class="px-4 py-3 whitespace-nowrap font-medium text-gray-900">{{ $section->title }}</td>
                            <td class="px-4 py-3 whitespace-nowrap text-gray-500">{{ $section->slug }}</td>
                            <td class="px-4 py-3 whitespace-nowrap text-gray-500">{{ $section->sort_order }}</td>
                            <td class="px-4 py-3 whitespace-nowrap text-gray-500">{{ $section->pages_count ?? 0 }}</td>
                            <td class="px-4 py-3 whitespace-nowrap">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $section->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                    {{ $section->is_active ? 'Active' : 'Inactive' }}
                                </span>
                            </td>
                            <td class="px-4 py-3 whitespace-nowrap text-sm font-medium">
                                <div class="flex items-center gap-2">
                                    <a href="{{ route('admin.doc-sections.show', $section) }}" class="text-gray-600 hover:text-primary" title="View"><i class="fa-solid fa-eye"></i></a>
                                    <a href="{{ route('admin.doc-sections.edit', $section) }}" class="text-gray-600 hover:text-blue-600" title="Edit"><i class="fa-solid fa-edit"></i></a>
                                    <form action="{{ route('admin.doc-sections.toggle-active', $section) }}" method="POST" class="inline">@csrf<button type="submit" class="text-gray-600 {{ $section->is_active ? 'hover:text-yellow-600' : 'hover:text-green-600' }}"><i class="fa-solid fa-{{ $section->is_active ? 'pause' : 'play' }}"></i></button></form>
                                    <form action="{{ route('admin.doc-sections.destroy', $section) }}" method="POST" class="inline" onsubmit="return confirm('Delete this section and all its pages?')">@csrf @method('DELETE')<button type="submit" class="text-gray-600 hover:text-red-600"><i class="fa-solid fa-trash"></i></button></form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="7" class="px-4 py-8 text-center text-gray-500">No sections yet. Create one from a version.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
</x-layouts.admin>
