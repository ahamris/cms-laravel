<x-layouts.admin title="View Widget">
<div class="space-y-6">
    {{-- Header --}}
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">{{ $widget->title ?: 'Untitled Widget' }}</h1>
            <p class="text-gray-600">Widget details and preview</p>
        </div>
        <div class="flex items-center space-x-3">
            <a href="{{ route('admin.content.homepage-builder.edit', $widget) }}" 
               class="inline-flex items-center px-4 py-2 bg-primary text-white rounded-lg hover:bg-primary-dark transition-colors duration-200">
                <i class="fa-solid fa-edit mr-2"></i>
                Edit Widget
            </a>
            <a href="{{ route('admin.content.page-builder.manage', ['pageType' => $widget->section_identifier ?? 'homepage']) }}" 
               class="inline-flex items-center px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors duration-200">
                <i class="fa-solid fa-arrow-left mr-2"></i>
                Back to {{ ucfirst($widget->section_identifier ?? 'homepage') }} Builder
            </a>
        </div>
    </div>

    {{-- Widget Details --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        {{-- Main Content --}}
        <div class="lg:col-span-2 space-y-6">
            {{-- Content Preview Card --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-200">
                <div class="p-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Content Preview</h3>
                    
                    @if($widget->title)
                        <h2 class="text-2xl font-bold text-gray-900 mb-2">{{ $widget->title }}</h2>
                    @endif
                    
                    @if($widget->subtitle)
                        <p class="text-lg text-gray-600 mb-4">{{ $widget->subtitle }}</p>
                    @endif
                    
                    @if($widget->content)
                        <div class="prose max-w-none mb-4">
                            {!! $widget->content !!}
                        </div>
                    @endif
                    
                    @if($widget->image)
                        <div class="mb-4">
                            <img src="{{ asset('storage/' . $widget->image) }}" 
                                 alt="{{ $widget->title ?: 'Widget image' }}" 
                                 class="w-full h-48 object-cover rounded-lg border border-gray-200">
                        </div>
                    @endif
                    
                    @if($widget->hasButton())
                        <div class="mt-4">
                            <a href="{{ $widget->button_url }}" 
                               target="{{ $widget->button_target }}"
                               class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors duration-200">
                                {{ $widget->button_text }}
                                @if($widget->button_external)
                                    <i class="fa-solid fa-external-link-alt ml-2"></i>
                                @endif
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        {{-- Sidebar --}}
        <div class="space-y-6">
            {{-- Widget Info Card --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-200">
                <div class="p-6 space-y-4">
                    <h3 class="text-lg font-medium text-gray-900">Widget Information</h3>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Section</label>
                        <p class="text-sm text-gray-900">{{ $widget->section_display_name }}</p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Template</label>
                        <p class="text-sm text-gray-900">{{ $widget->template_display_name }}</p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $widget->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                            {{ $widget->is_active ? 'Active' : 'Inactive' }}
                        </span>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Sort Order</label>
                        <p class="text-sm text-gray-900">{{ $widget->sort_order }}</p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Created</label>
                        <p class="text-sm text-gray-900">{{ $widget->created_at->format('M d, Y \a\t g:i A') }}</p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Last Updated</label>
                        <p class="text-sm text-gray-900">{{ $widget->updated_at->format('M d, Y \a\t g:i A') }}</p>
                    </div>
                </div>
            </div>

            {{-- Styling Info Card --}}
            @if($widget->background_color || $widget->text_color)
            <div class="bg-white rounded-xl shadow-sm border border-gray-200">
                <div class="p-6 space-y-4">
                    <h3 class="text-lg font-medium text-gray-900">Styling</h3>
                    
                    @if($widget->background_color)
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Background Color</label>
                        <div class="flex items-center space-x-2">
                            <div class="w-6 h-6 rounded border border-gray-300" 
                                 style="background-color: {{ $widget->background_color }}"></div>
                            <span class="text-sm text-gray-900 font-mono">{{ $widget->background_color }}</span>
                        </div>
                    </div>
                    @endif

                    @if($widget->text_color)
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Text Color</label>
                        <div class="flex items-center space-x-2">
                            <div class="w-6 h-6 rounded border border-gray-300" 
                                 style="background-color: {{ $widget->text_color }}"></div>
                            <span class="text-sm text-gray-900 font-mono">{{ $widget->text_color }}</span>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
            @endif

            {{-- Actions Card --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-200">
                <div class="p-6 space-y-4">
                    <h3 class="text-lg font-medium text-gray-900">Actions</h3>
                    
                    <div class="space-y-2">
                        <a href="{{ route('admin.content.homepage-builder.edit', $widget) }}" 
                           class="w-full inline-flex items-center justify-center px-4 py-2 bg-primary text-white rounded-lg hover:bg-primary-dark transition-colors duration-200">
                            <i class="fa-solid fa-edit mr-2"></i>
                            Edit Widget
                        </a>

                        <form action="{{ route('admin.content.homepage-builder.duplicate', $widget) }}" method="POST" class="w-full">
                            @csrf
                            <button type="submit" 
                                    class="w-full inline-flex items-center justify-center px-4 py-2 bg-yellow-100 text-yellow-800 rounded-lg hover:bg-yellow-200 transition-colors duration-200">
                                <i class="fa-solid fa-copy mr-2"></i>
                                Duplicate Widget
                            </button>
                        </form>

                        <form action="{{ route('admin.content.homepage-builder.toggle-active', $widget) }}" method="POST" class="w-full">
                            @csrf
                            <button type="submit" 
                                    class="w-full inline-flex items-center justify-center px-4 py-2 {{ $widget->is_active ? 'bg-yellow-100 text-yellow-800 hover:bg-yellow-200' : 'bg-green-100 text-green-800 hover:bg-green-200' }} rounded-lg transition-colors duration-200">
                                <i class="fa-solid fa-{{ $widget->is_active ? 'pause' : 'play' }} mr-2"></i>
                                {{ $widget->is_active ? 'Deactivate' : 'Activate' }} Widget
                            </button>
                        </form>

                        <form action="{{ route('admin.content.homepage-builder.destroy', $widget) }}" 
                              method="POST" 
                              class="w-full"
                              onsubmit="return confirm('Are you sure you want to delete this widget? This action cannot be undone.')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" 
                                    class="w-full inline-flex items-center justify-center px-4 py-2 bg-red-100 text-red-800 rounded-lg hover:bg-red-200 transition-colors duration-200">
                                <i class="fa-solid fa-trash mr-2"></i>
                                Delete Widget
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</x-layouts.admin>
