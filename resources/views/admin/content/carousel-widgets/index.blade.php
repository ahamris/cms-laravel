<x-layouts.admin title="Carousel Widgets">
    {{-- Header --}}
    <div class="mb-8">
        <div class="flex items-center justify-between mb-6">
            <div>
                <h2 class="text-3xl font-bold text-gray-900">Carousel Widgets</h2>
                <p class="text-gray-600 mt-2">Create and manage carousel widgets for displaying content</p>
            </div>
            <a href="{{ route('admin.content.carousel-widgets.create') }}"
               class="inline-flex items-center px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-xl transition-all duration-200 shadow-lg hover:shadow-xl">
                <i class="fa fa-plus mr-2"></i>
                Create New Carousel
            </a>
        </div>
    </div>

    {{-- Carousel Widgets List --}}
    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
        @if($carouselWidgets->count() > 0)
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">#</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Carousel Details</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Identifier</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Configuration</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-4 text-right text-xs font-semibold text-gray-600 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($carouselWidgets as $widget)
                            <tr class="hover:bg-gray-50 transition-colors duration-150">
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $widget->id }}
                                </td>
                                <td class="px-6 py-4">
                                    <div>
                                        <div class="font-semibold text-gray-900">{{ $widget->name }}</div>
                                        @if($widget->title)
                                            <div class="text-sm text-gray-500 mt-1">{{ Str::limit($widget->title, 60) }}</div>
                                        @endif
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="bg-blue-100 text-blue-800 px-3 py-1 rounded-full text-xs font-mono font-semibold">
                                        {{ $widget->identifier }}
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-sm text-gray-600">
                                        <div>{{ $widget->items_per_row }} per row × {{ $widget->number_of_rows }} rows</div>
                                        <div class="text-xs text-gray-500 mt-1">Total: {{ $widget->total_items }} items</div>
                                        @if($widget->blogCategory)
                                            <div class="text-xs text-gray-500 mt-1">Category: {{ $widget->blogCategory->name }}</div>
                                        @endif
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($widget->is_active)
                                        <span class="bg-green-100 text-green-800 px-3 py-1 rounded-full text-xs font-semibold">
                                            Active
                                        </span>
                                    @else
                                        <span class="bg-gray-100 text-gray-800 px-3 py-1 rounded-full text-xs font-semibold">
                                            Inactive
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <div class="flex items-center justify-end space-x-2">
                                        <a href="{{ route('admin.content.carousel-widgets.show', $widget) }}"
                                           class="text-blue-600 hover:text-blue-900 transition-colors"
                                           title="View">
                                            <i class="fa fa-eye"></i>
                                        </a>
                                        <a href="{{ route('admin.content.carousel-widgets.edit', $widget) }}"
                                           class="text-purple-600 hover:text-purple-900 transition-colors"
                                           title="Edit">
                                            <i class="fa fa-edit"></i>
                                        </a>
                                        <button type="button"
                                                onclick="deleteCarousel({{ $widget->id }})"
                                                class="text-red-600 hover:text-red-900 transition-colors"
                                                title="Delete">
                                            <i class="fa fa-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            {{-- Pagination --}}
            @if($carouselWidgets->hasPages())
                <div class="px-6 py-4 border-t border-gray-200">
                    {{ $carouselWidgets->links() }}
                </div>
            @endif
        @else
            {{-- Empty State --}}
            <div class="text-center py-16 px-6">
                <div class="inline-flex items-center justify-center w-20 h-20 bg-blue-100 rounded-full mb-6">
                    <i class="fa fa-images text-4xl text-blue-600"></i>
                </div>
                <h3 class="text-xl font-semibold text-gray-900 mb-2">No Carousel Widgets Found</h3>
                <p class="text-gray-600 mb-6 max-w-md mx-auto">
                    Get started by creating your first carousel widget. Display blogs, images, or custom content in beautiful carousels!
                </p>
                <a href="{{ route('admin.content.carousel-widgets.create') }}"
                   class="inline-flex items-center px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-xl transition-all duration-200">
                    <i class="fa fa-plus mr-2"></i>
                    Create Your First Carousel
                </a>
            </div>
        @endif
    </div>

    {{-- Delete Modal --}}
    <div id="deleteModal" class="hidden fixed inset-0 bg-gray-600/50 overflow-y-auto h-full w-full z-50">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-2xl bg-white">
            <div class="mt-3">
                <div class="flex items-center justify-center w-12 h-12 mx-auto bg-red-100 rounded-full mb-4">
                    <i class="fa fa-exclamation-triangle text-2xl text-red-600"></i>
                </div>
                <h3 class="text-lg font-semibold text-gray-900 text-center mb-2">Delete Carousel Widget</h3>
                <p class="text-sm text-gray-600 text-center mb-6">
                    Are you sure you want to delete this carousel widget? This action cannot be undone.
                </p>
                <form id="deleteForm" method="POST" class="space-y-3">
                    @csrf
                    @method('DELETE')
                    <button type="submit"
                            class="w-full px-4 py-3 bg-red-600 hover:bg-red-700 text-white font-semibold rounded-xl transition-all duration-200">
                        Yes, Delete Carousel
                    </button>
                    <button type="button"
                            onclick="closeDeleteModal()"
                            class="w-full px-4 py-3 bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold rounded-xl transition-all duration-200">
                        Cancel
                    </button>
                </form>
            </div>
        </div>
    </div>

    <script>
    function deleteCarousel(widgetId) {
        const modal = document.getElementById('deleteModal');
        const form = document.getElementById('deleteForm');
        form.action = `/admin/content/carousel-widgets/${widgetId}`;
        modal.classList.remove('hidden');
    }

    function closeDeleteModal() {
        document.getElementById('deleteModal').classList.add('hidden');
    }

    // Close modal on outside click
    document.getElementById('deleteModal')?.addEventListener('click', function(e) {
        if (e.target === this) {
            closeDeleteModal();
        }
    });
</script>
    </script>
</x-layouts.admin>

