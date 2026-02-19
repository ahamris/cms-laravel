<x-layouts.admin title="About Sections">
<div class="space-y-6">
    {{-- Header --}}
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">About Section</h1>
            <p class="text-gray-600">Manage your company's about section</p>
        </div>
    </div>

    {{-- About Sections List --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        @if($abouts->count() > 0)
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Order
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Anchor / Nav Title
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Title
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Image Position
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Status
                            </th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Actions
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($abouts as $about)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800">
                                        <i class="fa-solid fa-list mr-1"></i>
                                        {{ $about->sort_order }}
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex flex-col gap-1">
                                        <div class="font-medium text-gray-900">#{{ $about->anchor }}</div>
                                        <div class="text-sm text-gray-500">{{ $about->nav_title }}</div>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="font-medium text-gray-900 max-w-xs">{{ $about->title }}</div>
                                    @if($about->subtitle)
                                        <div class="text-sm text-gray-500 mt-1 max-w-xs truncate">{{ Str::limit($about->subtitle, 60) }}</div>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $about->image_position === 'left' ? 'bg-blue-100 text-blue-800' : 'bg-green-100 text-green-800' }}">
                                        <i class="fa-solid {{ $about->image_position === 'left' ? 'fa-arrow-left' : 'fa-arrow-right' }} mr-1"></i>
                                        {{ ucfirst($about->image_position) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $about->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                        <i class="fa-solid {{ $about->is_active ? 'fa-check-circle' : 'fa-times-circle' }} mr-1"></i>
                                        {{ $about->is_active ? 'Active' : 'Inactive' }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <div class="flex items-center justify-end space-x-2">
                                        <a href="{{ route('admin.content.about.show', $about) }}"
                                           class="text-blue-600 hover:text-blue-900 transition-colors duration-200"
                                           title="View">
                                            <i class="fa-solid fa-eye"></i>
                                        </a>
                                        <a href="{{ route('admin.content.about.edit', $about) }}"
                                           class="text-indigo-600 hover:text-indigo-900 transition-colors duration-200"
                                           title="Edit">
                                            <i class="fa-solid fa-edit"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            {{-- Pagination --}}
            @if($abouts->hasPages())
                <div class="px-6 py-4 border-t border-gray-200">
                    {{ $abouts->links() }}
                </div>
            @endif
        @else
            <div class="text-center py-12">
                <div class="w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fa-solid fa-info-circle text-3xl text-gray-400"></i>
                </div>
                <h3 class="text-lg font-medium text-gray-900 mb-2">No About Sections</h3>
                <p class="text-gray-500 mb-6">Get started by creating your first about section.</p>
                <a href="{{ route('admin.content.about.create') }}"
                   class="bg-primary text-white px-4 py-2 rounded-lg hover:bg-primary/80 transition-colors duration-200">
                    <i class="fa-solid fa-plus mr-2"></i>
                    Create About Section
                </a>
            </div>
        @endif
    </div>
</div>

{{-- Delete Confirmation Modal --}}
<div id="deleteModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/50">
    <div class="bg-white rounded-xl p-6 w-full max-w-md mx-4">
        <div class="flex items-center space-x-3 mb-4">
            <div class="flex-shrink-0 w-10 h-10 bg-red-100 rounded-full flex items-center justify-center">
                <i class="fa-solid fa-exclamation-triangle text-red-600"></i>
            </div>
            <div>
                <h3 class="text-lg font-medium text-gray-900">Delete About Section</h3>
                <p class="text-gray-600">Are you sure you want to delete this about section?</p>
            </div>
        </div>
        <div class="bg-gray-50 rounded-lg p-3 mb-4">
            <p class="text-sm text-gray-700">
                <strong>About Section:</strong> <span id="aboutTitle"></span>
            </p>
        </div>
        <div class="flex space-x-3">
            <button onclick="closeDeleteModal()" 
                    class="flex-1 bg-gray-200 text-gray-800 px-4 py-2 rounded-lg hover:bg-gray-300 transition-colors duration-200">
                Cancel
            </button>
            <form id="deleteForm" method="POST" class="flex-1">
                @csrf
                @method('DELETE')
                <button type="submit" 
                        class="w-full bg-red-600 text-white px-4 py-2 rounded-lg hover:bg-red-700 transition-colors duration-200">
                    Delete
                </button>
            </form>
        </div>
    </div>
</div>

    <script>
function deleteAbout(aboutId, aboutTitle) {
    document.getElementById('aboutTitle').textContent = aboutTitle;
    document.getElementById('deleteForm').action = `/admin/content/about/${aboutId}`;
    document.getElementById('deleteModal').classList.remove('hidden');
    document.getElementById('deleteModal').classList.add('flex');
}

function closeDeleteModal() {
    document.getElementById('deleteModal').classList.add('hidden');
    document.getElementById('deleteModal').classList.remove('flex');
}

// Close modal when clicking outside
document.getElementById('deleteModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeDeleteModal();
    }
});
</script>
    </script>
</x-layouts.admin>
