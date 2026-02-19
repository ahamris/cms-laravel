<x-layouts.admin title="{{ ucfirst($pageType ?? 'homepage') }} Page Builder">
<div class="space-y-6">
    {{-- Header --}}
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">{{ ucfirst($pageType ?? 'homepage') }} Page Builder</h1>
            <p class="text-gray-600">Manage your {{ $pageType ?? 'homepage' }} sections and content</p>
        </div>
        <div class="flex space-x-3">
            <a href="{{ route('admin.content.homepage-builder.create') }}" 
               class="inline-flex items-center px-4 py-2 bg-primary text-white rounded-lg hover:bg-primary-dark transition-colors duration-200">
                <i class="fa-solid fa-plus mr-2"></i>
                Add Section
            </a>
            @if(($pageType ?? 'homepage') === 'homepage')
            <a href="{{ route('home') }}" target="_blank"
               class="inline-flex items-center px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition-colors duration-200">
                <i class="fa-solid fa-external-link-alt mr-2"></i>
                Preview Homepage
            </a>
            @endif
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
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                    <i class="fa-solid fa-{{ $currentPageType === 'homepage' ? 'home' : ($currentPageType === 'static_page' ? 'file' : 'file-alt') }} mr-2 text-blue-500"></i>
                    {{ ucfirst($currentPageType) }} Sections
                    <span class="ml-2 text-sm font-normal text-gray-500">({{ $pageWidgets->count() }} sections)</span>
                </h3>
            </div>

            <div class="p-6">
                <div class="space-y-4">
                    @foreach($pageWidgets->sortBy('sort_order') as $widget)
                        <div class="group relative bg-gray-50 rounded-lg border border-gray-200 hover:border-blue-300 transition-colors duration-200">
                            <div class="flex items-center justify-between p-4">
                                <div class="flex items-center space-x-4">
                                    <div class="flex-shrink-0">
                                        <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center">
                                            <i class="fa-solid fa-{{ $widget->getTemplateIcon() }} text-blue-600"></i>
                                        </div>
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <div class="flex items-center space-x-3">
                                            <h4 class="text-lg font-medium text-gray-900">
                                                {{ $widget->title ?: 'Untitled Section' }}
                                            </h4>
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                                {{ $sectionOrder[$widget->template] ?? ucfirst($widget->template) }}
                                            </span>
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $widget->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                                {{ $widget->is_active ? 'Visible' : 'Hidden' }}
                                            </span>
                                        </div>
                                        @if($widget->subtitle)
                                            <p class="text-sm text-gray-600 mt-1">{{ $widget->subtitle }}</p>
                                        @endif
                                        <div class="flex items-center space-x-4 mt-2">
                                            <span class="text-xs text-gray-400">
                                                <i class="fa-solid fa-sort mr-1"></i>
                                                Position {{ $widget->sort_order }}
                                            </span>
                                            <span class="text-xs text-gray-400">
                                                <i class="fa-solid fa-calendar mr-1"></i>
                                                {{ $widget->created_at->format('M d, Y') }}
                                            </span>
                                        </div>
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
                    <a href="{{ route($routePrefix . '.create') }}"
                       class="inline-flex items-center px-4 py-2 bg-primary text-white rounded-lg hover:bg-primary-dark transition-colors duration-200">
                        <i class="fa-solid fa-plus mr-2"></i>
                        Create First Section
                    </a>
            </div>
        </div>
    @endif
</div>

<script>
// Simple drag and drop functionality for reordering
document.addEventListener('DOMContentLoaded', function() {
    // This would be implemented with a proper drag and drop library
    // For now, we'll just show the visual indicators
    console.log('Widget reordering functionality can be implemented here');
});
</script>
</x-layouts.admin>
