<x-layouts.admin title="{{ ucfirst($pageType ?? 'homepage') }} Page Builder">
<div class="space-y-6">
    {{-- Header --}}
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">{{ ucfirst($pageType ?? 'homepage') }} Page Builder</h1>
            <p class="text-gray-600">Manage your {{ $pageType ?? 'homepage' }} sections and content</p>
        </div>
        <div class="flex space-x-3">
            <a href="{{ route('admin.content.page-builder.index') }}" 
               class="inline-flex items-center px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors duration-200">
                <i class="fa-solid fa-arrow-left mr-2"></i>
                Back to Page Builder
            </a>
            <a href="{{ route('admin.content.homepage-builder.create', ['page_type' => $pageType ?? 'homepage']) }}" 
               class="inline-flex items-center px-4 py-2 bg-primary text-white rounded-lg hover:bg-primary-dark transition-colors duration-200">
                <i class="fa-solid fa-plus mr-2"></i>
                Add Section
            </a>
        </div>
    </div>

    {{-- Success Message --}}
    @if(session('success'))
        <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg">
            {{ session('success') }}
        </div>
    @endif

    {{-- Page Sections --}}
    @php
        $currentPageType = $pageType ?? 'homepage';
        $pageWidgets = $widgets;
        $sectionOrder = [
            'services' => 'Solutions Section',
            'features' => 'Features Section', 
            'cta' => 'Call to Action',
            'changelog' => 'Updates Section',
            'blog' => 'News & Articles',
            'testimonial' => 'Help & Support',
            'about' => 'About Section'
        ];
        
        // Use homepage builder routes
        $routePrefix = 'admin.content.homepage-builder';
    @endphp

    @if($pageWidgets->count() > 0)
        <div class="bg-white rounded-xl shadow-sm border border-gray-200">
            <div class="px-4 py-3 border-b border-gray-200">
                <h3 class="text-base font-semibold text-gray-900 flex items-center">
                    <i class="fa-solid fa-{{ $currentPageType === 'homepage' ? 'home' : ($currentPageType === 'static_page' ? 'file' : 'file-alt') }} mr-2 text-blue-500"></i>
                    {{ ucfirst($currentPageType) }} Sections
                    <span class="ml-2 text-sm font-normal text-gray-500">({{ $pageWidgets->count() }} sections)</span>
                </h3>
            </div>

            <div class="p-3">
                <div id="widgets-list" class="space-y-2">
                    @foreach($pageWidgets->sortBy('sort_order') as $widget)
                        <div class="widget-item group relative bg-gray-50 rounded-lg border border-gray-200 hover:border-blue-300 transition-colors duration-200 cursor-move" 
                             data-id="{{ $widget->id }}" 
                             data-sort-order="{{ $widget->sort_order }}">
                            <div class="flex items-center justify-between px-3 py-2">
                                <div class="flex items-center space-x-3 flex-1 min-w-0">
                                    <div class="flex-shrink-0 cursor-grab active:cursor-grabbing text-gray-400 hover:text-gray-600">
                                        <i class="fa-solid fa-grip-vertical"></i>
                                    </div>
                                    <div class="flex-shrink-0">
                                        <div class="w-8 h-8 bg-blue-100 rounded-lg flex items-center justify-center">
                                            <i class="fa-solid fa-{{ $widget->getTemplateIcon() }} text-blue-600 text-sm"></i>
                                        </div>
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <div class="flex items-center space-x-2 flex-wrap">
                                            <span class="position-badge inline-flex items-center justify-center w-6 h-6 rounded-full bg-gray-200 text-gray-700 text-xs font-semibold flex-shrink-0" title="Position">
                                                {{ $loop->iteration }}
                                            </span>
                                            <h4 class="text-sm font-medium text-gray-900 truncate">
                                                {{ $widget->title ?: 'Untitled Section' }}
                                            </h4>
                                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-blue-100 text-blue-800 whitespace-nowrap">
                                                {{ $sectionOrder[$widget->template] ?? ucfirst($widget->template) }}
                                            </span>
                                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium {{ $widget->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }} whitespace-nowrap">
                                                {{ $widget->is_active ? 'Visible' : 'Hidden' }}
                                            </span>
                                        </div>
                                        @if($widget->subtitle)
                                            <p class="text-xs text-gray-500 mt-0.5 truncate">{{ Str::limit($widget->subtitle, 60) }}</p>
                                        @endif
                                    </div>
                                </div>
                                <div class="flex items-center space-x-2 opacity-0 group-hover:opacity-100 transition-opacity duration-200">
                                    <a href="{{ route($routePrefix . '.show', $widget) }}" 
                                       class="text-blue-600 hover:text-blue-900 p-2 rounded-lg hover:bg-blue-50"
                                       title="Preview">
                                        <i class="fa-solid fa-eye"></i>
                                    </a>
                                    <a href="{{ route($routePrefix . '.edit', $widget) }}" 
                                       class="text-indigo-600 hover:text-indigo-900 p-2 rounded-lg hover:bg-indigo-50"
                                       title="Edit">
                                        <i class="fa-solid fa-edit"></i>
                                    </a>
                                    <form action="{{ route($routePrefix . '.duplicate', $widget) }}" 
                                          method="POST" class="inline">
                                        @csrf
                                        <button type="submit" 
                                                class="text-yellow-600 hover:text-yellow-900 p-2 rounded-lg hover:bg-yellow-50"
                                                title="Duplicate Section">
                                            <i class="fa-solid fa-copy"></i>
                                        </button>
                                    </form>
                                    <form action="{{ route($routePrefix . '.toggle-active', $widget) }}" 
                                          method="POST" class="inline">
                                        @csrf
                                        <button type="submit" 
                                                class="text-{{ $widget->is_active ? 'yellow' : 'green' }}-600 hover:text-{{ $widget->is_active ? 'yellow' : 'green' }}-900 p-2 rounded-lg hover:bg-{{ $widget->is_active ? 'yellow' : 'green' }}-50"
                                                title="{{ $widget->is_active ? 'Hide Section' : 'Show Section' }}">
                                            <i class="fa-solid fa-{{ $widget->is_active ? 'eye-slash' : 'eye' }}"></i>
                                        </button>
                                    </form>
                                    <form action="{{ route($routePrefix . '.destroy', $widget) }}" 
                                          method="POST" class="inline"
                                          onsubmit="return confirm('Are you sure you want to delete this section?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-900 p-2 rounded-lg hover:bg-red-50">
                                            <i class="fa-solid fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    @else
        <div class="bg-white rounded-xl shadow-sm border border-gray-200">
            <div class="text-center py-12">
                <i class="fa-solid fa-{{ $currentPageType === 'homepage' ? 'home' : ($currentPageType === 'static_page' ? 'file' : 'file-alt') }} text-6xl text-gray-300 mb-4"></i>
                <h3 class="text-lg font-medium text-gray-900 mb-2">No {{ $currentPageType }} sections found</h3>
                <p class="text-gray-500 mb-6">Get started by creating your first {{ $currentPageType }} section.</p>
                    <a href="{{ route($routePrefix . '.create', ['page_type' => $pageType ?? 'homepage']) }}"
                       class="inline-flex items-center px-4 py-2 bg-primary text-white rounded-lg hover:bg-primary-dark transition-colors duration-200">
                        <i class="fa-solid fa-plus mr-2"></i>
                        Create First Section
                    </a>
            </div>
        </div>
    @endif
</div>


    <style>
<style>
    .sortable-ghost {
        opacity: 0.4;
        background: #e0e7ff;
    }
    .sortable-drag {
        opacity: 0.8;
    }
    .widget-item:hover {
        background-color: #f9fafb;
    }
</style>

    <script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>
    <script>
document.addEventListener('DOMContentLoaded', function() {
    const widgetsList = document.getElementById('widgets-list');
    
    if (widgetsList) {
        const sortable = new Sortable(widgetsList, {
            animation: 150,
            ghostClass: 'sortable-ghost',
            dragClass: 'sortable-drag',
            handle: '.fa-grip-vertical',
            preventOnFilter: false,
            onEnd: function(evt) {
                // Update position numbers immediately after reordering
                updatePositionNumbers();
                updateWidgetOrder();
            }
        });

        function updatePositionNumbers() {
            const widgets = Array.from(widgetsList.children);
            widgets.forEach((item, index) => {
                const positionBadge = item.querySelector('.position-badge');
                if (positionBadge) {
                    positionBadge.textContent = index + 1;
                }
            });
        }

        function updateWidgetOrder() {
            const widgets = Array.from(widgetsList.children);
            const orderData = widgets.map((item, index) => ({
                id: parseInt(item.dataset.id),
                sort_order: index
            }));

            fetch('{{ route("admin.content.homepage-builder.update-order") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ widgets: orderData })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Update sort_order data attributes and position numbers
                    widgets.forEach((item, index) => {
                        item.dataset.sortOrder = index;
                        // Update the position badge number
                        const positionBadge = item.querySelector('.position-badge');
                        if (positionBadge) {
                            positionBadge.textContent = index + 1;
                        }
                    });
                    
                    // Show success message
                    if (typeof toastr !== 'undefined') {
                        toastr.success('Section order updated successfully!');
                    }
                } else {
                    console.error('Failed to update order');
                    if (typeof toastr !== 'undefined') {
                        toastr.error('Failed to update section order');
                    }
                }
            })
            .catch(error => {
                console.error('Error:', error);
                if (typeof toastr !== 'undefined') {
                    toastr.error('An error occurred while updating order');
                }
            });
        }
    }
});
</script>
    </script>
</x-layouts.admin>
