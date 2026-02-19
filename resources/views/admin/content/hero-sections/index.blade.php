<x-layouts.admin title="Hero Sections">
<div class="space-y-6">
    {{-- Header --}}
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Hero Sections</h1>
            <p class="text-gray-600">Manage your homepage hero sections and call-to-action areas</p>
        </div>
        <div class="flex items-center space-x-3">
            <a href="{{ route('admin.content.hero-sections.create-default') }}" 
               onclick="return confirm('This will create a default hero section. Continue?')"
               class="bg-gray-600 text-white px-4 py-2 rounded-lg hover:bg-gray-700 transition-colors duration-200">
                <i class="fa-solid fa-magic mr-2"></i>
                Create Default
            </a>
            <a href="{{ route('admin.content.hero-section.create') }}" 
               class="bg-primary text-white px-4 py-2 rounded-lg hover:bg-primary/80 transition-colors duration-200">
                <i class="fa-solid fa-plus mr-2"></i>
                Add Hero Section
            </a>
        </div>
    </div>

    {{-- Success/Error Messages --}}
    @if(session('success'))
        <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg">
            <i class="fa-solid fa-check-circle mr-2"></i>
            {{ session('success') }}
        </div>
    @endif

    {{-- Hero Sections List --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-200">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-semibold text-gray-900">All Hero Sections</h3>
        </div>
        
        @if($heroSections->count() > 0)
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Title
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Status
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                List Items
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Buttons
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Updated
                            </th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Actions
                            </th>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($heroSections as $heroSection)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4">
                                    <div class="flex flex-col gap-1">
                                        @if($heroSection->top_header_icon || $heroSection->top_header_text)
                                            <div class="flex items-center text-xs text-primary mb-1">
                                                @if($heroSection->top_header_url)
                                                    <a href="{{ $heroSection->top_header_url }}" class="flex items-center hover:text-primary/80 transition-colors">
                                                        @if($heroSection->top_header_icon)
                                                            <i class="{{ $heroSection->top_header_icon }} mr-1"></i>
                                                        @endif
                                                        @if($heroSection->top_header_text)
                                                            <span>{{ $heroSection->top_header_text }}</span>
                                                        @endif
                                                    </a>
                                                @else
                                                    @if($heroSection->top_header_icon)
                                                        <i class="{{ $heroSection->top_header_icon }} mr-1"></i>
                                                    @endif
                                                    @if($heroSection->top_header_text)
                                                        <span>{{ $heroSection->top_header_text }}</span>
                                                    @endif
                                                @endif
                                            </div>
                                        @endif
                                        <div class="font-medium text-gray-900 max-w-xs">{{ $heroSection->title }}</div>
                                        @if($heroSection->subtitle)
                                            <div class="text-sm text-gray-500 mt-1 max-w-xs truncate">{{ Str::limit($heroSection->subtitle, 60) }}</div>
                                        @endif
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <button onclick="toggleActive({{ $heroSection->id }}, {{ $heroSection->is_active ? 'true' : 'false' }})" 
                                            class="status-toggle relative inline-flex h-6 w-11 items-center rounded-full transition-colors focus:outline-none focus:ring-2 focus:ring-primary focus:ring-offset-2 {{ $heroSection->is_active ? 'bg-primary' : 'bg-gray-200' }}"
                                            data-hero-id="{{ $heroSection->id }}">
                                        <span class="sr-only">Toggle status</span>
                                        <span class="inline-block h-4 w-4 transform rounded-full bg-white transition-transform {{ $heroSection->is_active ? 'translate-x-6' : 'translate-x-1' }}"></span>
                                    </button>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800">
                                        <i class="fa-solid fa-list mr-1"></i>
                                        {{ count($heroSection->list_items ?? []) }} items
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex flex-col gap-1">
                                        @if($heroSection->button1_text)
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                                <i class="fa-solid fa-mouse-pointer mr-1"></i>
                                                {{ $heroSection->button1_text }}
                                            </span>
                                        @endif
                                        @if($heroSection->button2_text)
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                <i class="fa-solid fa-mouse-pointer mr-1"></i>
                                                {{ $heroSection->button2_text }}
                                            </span>
                                        @endif
                                        @if(!$heroSection->button1_text && !$heroSection->button2_text)
                                            <span class="text-xs text-gray-400">No buttons</span>
                                        @endif
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $heroSection->updated_at->format('M j, Y') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <div class="flex items-center justify-end space-x-2">
                                        <a href="{{ route('admin.content.hero-section.show', $heroSection) }}" 
                                           class="text-blue-600 hover:text-blue-900 p-1">
                                            <i class="fa-solid fa-eye"></i>
                                        </a>
                                        <a href="{{ route('admin.content.hero-section.edit', $heroSection) }}" 
                                           class="text-indigo-600 hover:text-indigo-900 p-1">
                                            <i class="fa-solid fa-edit"></i>
                                        </a>
                                        <button onclick="deleteHeroSection({{ $heroSection->id }})" 
                                                class="text-red-600 hover:text-red-900 p-1">
                                            <i class="fa-solid fa-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            {{-- Pagination --}}
            @if($heroSections->hasPages())
                <div class="px-6 py-4 border-t border-gray-200">
                    {{ $heroSections->links() }}
                </div>
            @endif
        @else
            <div class="px-6 py-12 text-center">
                <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fa-solid fa-star text-gray-400 text-2xl"></i>
                </div>
                <h3 class="text-lg font-medium text-gray-900 mb-2">No Hero Sections</h3>
                <p class="text-gray-500 mb-6">Get started by creating your first hero section for the homepage.</p>
                <div class="flex items-center justify-center space-x-3">
                    <a href="{{ route('admin.content.hero-sections.create-default') }}" 
                       onclick="return confirm('This will create a default hero section. Continue?')"
                       class="bg-gray-600 text-white px-4 py-2 rounded-lg hover:bg-gray-700 transition-colors duration-200">
                        <i class="fa-solid fa-magic mr-2"></i>
                        Create Default
                    </a>
                    <a href="{{ route('admin.content.hero-section.create') }}" 
                       class="bg-primary text-white px-4 py-2 rounded-lg hover:bg-primary/80 transition-colors duration-200">
                        <i class="fa-solid fa-plus mr-2"></i>
                        Create Hero Section
                    </a>
                </div>
            </div>
        @endif
    </div>
</div>

{{-- Delete Confirmation Modal --}}
<div id="deleteModal" class="fixed inset-0 bg-black/50 hidden z-50 flex items-center justify-center">
    <div class="bg-white rounded-xl shadow-xl max-w-md w-full mx-4">
        <div class="p-6">
            <div class="flex items-center mb-4">
                <i class="fa-solid fa-exclamation-triangle text-red-500 text-2xl mr-3"></i>
                <h3 class="text-lg font-semibold text-gray-900">Delete Hero Section</h3>
            </div>
            <p class="text-gray-600 mb-6">
                Are you sure you want to delete this hero section? This action cannot be undone.
            </p>
            <div class="flex items-center justify-end space-x-4">
                <button type="button" 
                        onclick="closeDeleteModal()"
                        class="bg-gray-500 text-white px-4 py-2 rounded-lg hover:bg-gray-600 transition-colors duration-200">
                    Cancel
                </button>
                <form id="deleteForm" method="POST" class="inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" 
                            class="bg-red-600 text-white px-4 py-2 rounded-lg hover:bg-red-700 transition-colors duration-200">
                        Delete
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
function toggleActive(heroSectionId) {
    fetch(`/admin/content/hero-section/${heroSectionId}/toggle-active`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            toastr.success(data.message || 'Status updated successfully');
            setTimeout(() => location.reload(), 1000);
        } else {
            toastr.error(data.message || 'Error updating status');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        toastr.error('Error updating status. Please try again.');
    });
}

function deleteHeroSection(heroSectionId) {
    document.getElementById('deleteForm').action = `/admin/content/hero-section/${heroSectionId}`;
    document.getElementById('deleteModal').classList.remove('hidden');
}

function closeDeleteModal() {
    document.getElementById('deleteModal').classList.add('hidden');
}

// Close modal when clicking outside
document.getElementById('deleteModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeDeleteModal();
    }
});
</script>
</x-layouts.admin>
