<x-layouts.admin title="Hero Media Widgets">
<div class="space-y-6">
    {{-- Header --}}
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Hero Media Widgets</h1>
            <p class="text-gray-600">Manage hero sections with image/video background</p>
        </div>
        <a href="{{ route('admin.content.hero-widget.create') }}"
           class="bg-primary text-white px-4 py-2 rounded-lg hover:bg-primary/80 transition-colors duration-200">
            <i class="fa-solid fa-plus mr-2"></i>
            Create New Widget
        </a>
    </div>

    {{-- Success/Error Messages --}}
    @if(session('success'))
        <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg">
            <i class="fa-solid fa-check-circle mr-2"></i>
            {{ session('success') }}
        </div>
    @endif

    {{-- Widgets List --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-200">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-semibold text-gray-900">All Hero Media Widgets</h3>
        </div>
        
        @if($heroWidgets->count() > 0)
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Title
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Background Type
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Status
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Updated
                            </th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Actions
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($heroWidgets as $widget)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4">
                                    <div class="flex flex-col gap-1">
                                        @if($widget->top_header_text)
                                            <div class="flex items-center text-xs text-primary mb-1">
                                                @if($widget->top_header_icon)
                                                    <i class="{{ $widget->top_header_icon }} mr-1"></i>
                                                @endif
                                                <span>{{ $widget->top_header_text }}</span>
                                            </div>
                                        @endif
                                        <div class="font-medium text-gray-900 max-w-xs">
                                            {{ $widget->title ?: 'Untitled Widget' }}
                                        </div>
                                        @if($widget->subtitle)
                                            <div class="text-sm text-gray-500 mt-1 max-w-xs truncate">
                                                {{ Str::limit($widget->subtitle, 60) }}
                                            </div>
                                        @endif
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                        {{ $widget->background_type === 'video' ? 'bg-purple-100 text-purple-800' : 'bg-blue-100 text-blue-800' }}">
                                        <i class="fa-solid {{ $widget->background_type === 'video' ? 'fa-video' : 'fa-image' }} mr-1"></i>
                                        {{ ucfirst($widget->background_type) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                        {{ $widget->is_active ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                                        <i class="fa-solid {{ $widget->is_active ? 'fa-check-circle' : 'fa-times-circle' }} mr-1"></i>
                                        {{ $widget->is_active ? 'Active' : 'Inactive' }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $widget->updated_at->format('M d, Y') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <div class="flex items-center justify-end space-x-2">
                                        <a href="{{ route('admin.content.hero-widget.edit', $widget) }}"
                                           class="text-primary hover:text-primary/80 transition-colors">
                                            <i class="fa-solid fa-edit"></i>
                                        </a>
                                        <form action="{{ route('admin.content.hero-widget.destroy', $widget) }}" 
                                              method="POST" 
                                              class="inline"
                                              onsubmit="return confirm('Are you sure you want to delete this widget?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-800 transition-colors">
                                                <i class="fa-solid fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="p-12 text-center">
                <i class="fa-solid fa-video text-gray-400 text-4xl mb-4"></i>
                <p class="text-gray-500 mb-4">No hero media widgets found</p>
                <a href="{{ route('admin.content.hero-widget.create') }}"
                   class="inline-flex items-center px-4 py-2 bg-primary text-white rounded-lg hover:bg-primary/80 transition-colors">
                    <i class="fa-solid fa-plus mr-2"></i>
                    Create Your First Widget
                </a>
            </div>
        @endif
    </div>
</div>
</x-layouts.admin>

