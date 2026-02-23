<x-layouts.admin title="Static Pages">
<div class="space-y-6">
    {{-- Header --}}
    <div class="flex items-center justify-between">
        <div class="flex items-center gap-3">
            <div class="w-12 h-12 bg-primary rounded-md flex items-center justify-center">
                <i class="fa-solid fa-file-alt text-white text-base"></i>
            </div>
            <div class="flex flex-col gap-1">
                <h2>Static Pages</h2>
                <p>Manage your static pages content</p>
            </div>
        </div>
        <a href="{{ route('admin.static-page.create') }}" 
           class="px-5 py-2 rounded-md bg-primary text-white text-sm hover:bg-primary/80 transition-colors duration-200 flex items-center space-x-2">
            <i class="fa fa-plus"></i>
            <span>Add New Static Page</span>
        </a>
    </div>

    {{-- Success Message --}}
    @if(session('success'))
        <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-md text-sm">
            {{ session('success') }}
        </div>
    @endif

    {{-- Static Pages Table --}}
    <div class="bg-gray-50/50 rounded-md border border-gray-200">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50/80 border-b border-gray-200">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Title</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Slug</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Created</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($staticPages as $staticPage)
                        <tr class="hover:bg-gray-50">
                            <td class="px-4 py-3 whitespace-nowrap">
                                <div class="text-xs font-medium text-gray-900">{{ $staticPage->title }}</div>
                            </td>
                            <td class="px-4 py-3 whitespace-nowrap">
                                <div class="text-xs text-gray-500">{{ $staticPage->slug }}</div>
                            </td>
                            <td class="px-4 py-3 whitespace-nowrap">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $staticPage->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                    {{ $staticPage->is_active ? 'Active' : 'Inactive' }}
                                </span>
                            </td>
                            <td class="px-4 py-3 whitespace-nowrap text-xs text-gray-500">
                                {{ $staticPage->created_at->format('M d, Y') }}
                            </td>
                            <td class="px-4 py-3 whitespace-nowrap text-sm font-medium">
                                <div class="flex items-center space-x-2">
                                    <a href="{{ route('admin.static-page.show', $staticPage) }}"
                                       class="text-xs text-gray-600 hover:text-primary transition-colors duration-200">
                                        <i class="fa-solid fa-eye"></i>
                                    </a>
                                    <a href="{{ route('admin.static-page.edit', $staticPage) }}"
                                       class="text-xs text-gray-600 hover:text-blue-600 transition-colors duration-200">
                                        <i class="fa-solid fa-edit"></i>
                                    </a>
                                    <form action="{{ route('admin.static-page.toggle-active', $staticPage) }}"
                                          method="POST" class="inline">
                                        @csrf
                                        <button type="submit"
                                                class="text-xs text-gray-600 {{ $staticPage->is_active ? 'hover:text-yellow-600' : 'hover:text-green-600' }} transition-colors duration-200 focus:outline-none">
                                            <i class="fa-solid fa-{{ $staticPage->is_active ? 'pause' : 'play' }}"></i>
                                        </button>
                                    </form>
                                    <form action="{{ route('admin.static-page.destroy', $staticPage) }}"
                                          method="POST" class="inline"
                                          onsubmit="return confirm('Are you sure you want to delete this static page?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-xs text-gray-600 hover:text-red-600 transition-colors duration-200 focus:outline-none">
                                            <i class="fa-solid fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center text-gray-500">
                                <div class="flex flex-col items-center">
                                    <i class="fa-solid fa-file-lines text-3xl text-gray-300 mb-2"></i>
                                    <p class="text-base font-medium mb-1">No static pages found</p>
                                    <p class="text-xs text-gray-500">Get started by creating your first static page.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        @if($staticPages->hasPages())
            <div class="px-6 py-4 border-t border-gray-200 bg-gray-50/80">
                {{ $staticPages->links() }}
            </div>
        @endif
    </div>
</div>
</x-layouts.admin>
