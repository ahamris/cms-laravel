<x-layouts.admin title="Documentation Pages">
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <div class="flex items-center gap-3">
            <div class="w-12 h-12 bg-primary rounded-md flex items-center justify-center">
                <i class="fa-solid fa-file-alt text-white text-base"></i>
            </div>
            <div class="flex flex-col gap-1">
                <h2>Documentation Pages</h2>
                <p>Manage doc pages (content) inside sections.</p>
            </div>
        </div>
        <a href="{{ route('admin.doc-sections.index') }}" class="px-5 py-2 rounded-md bg-white border border-gray-200 text-gray-700 text-sm hover:bg-gray-50 flex items-center space-x-2">
            <i class="fa fa-folder-open"></i>
            <span>Sections</span>
        </a>
        <a href="{{ route('admin.doc-pages.create') }}" class="px-5 py-2 rounded-md bg-primary text-white text-sm hover:bg-primary/80 flex items-center space-x-2">
            <i class="fa fa-plus"></i>
            <span>Add Page</span>
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
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Section</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Title</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Slug</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Order</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($pages as $page)
                        <tr class="hover:bg-gray-50">
                            <td class="px-4 py-3 whitespace-nowrap text-gray-600">{{ $page->section->title ?? '-' }}</td>
                            <td class="px-4 py-3 whitespace-nowrap font-medium text-gray-900">{{ $page->title }}</td>
                            <td class="px-4 py-3 whitespace-nowrap text-gray-500">{{ $page->slug }}</td>
                            <td class="px-4 py-3 whitespace-nowrap text-gray-500">{{ $page->sort_order }}</td>
                            <td class="px-4 py-3 whitespace-nowrap">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $page->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                    {{ $page->is_active ? 'Active' : 'Inactive' }}
                                </span>
                            </td>
                            <td class="px-4 py-3 whitespace-nowrap text-sm font-medium">
                                <div class="flex items-center gap-2">
                                    <a href="{{ route('admin.doc-pages.show', $page) }}" class="text-gray-600 hover:text-primary" title="View"><i class="fa-solid fa-eye"></i></a>
                                    <a href="{{ route('admin.doc-pages.edit', $page) }}" class="text-gray-600 hover:text-blue-600" title="Edit"><i class="fa-solid fa-edit"></i></a>
                                    <form action="{{ route('admin.doc-pages.toggle-active', $page) }}" method="POST" class="inline">@csrf<button type="submit" class="text-gray-600 {{ $page->is_active ? 'hover:text-yellow-600' : 'hover:text-green-600' }}"><i class="fa-solid fa-{{ $page->is_active ? 'pause' : 'play' }}"></i></button></form>
                                    <form action="{{ route('admin.doc-pages.destroy', $page) }}" method="POST" class="inline" onsubmit="return confirm('Delete this page?')">@csrf @method('DELETE')<button type="submit" class="text-gray-600 hover:text-red-600"><i class="fa-solid fa-trash"></i></button></form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="px-4 py-8 text-center text-gray-500">No pages yet. Create a section first, then add pages.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
</x-layouts.admin>
