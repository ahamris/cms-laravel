<x-layouts.admin title="View Page">
<div class="space-y-6">
    {{-- Header --}}
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Page Details</h1>
            <p class="text-gray-600">View page information and content</p>
        </div>
        <div class="flex items-center space-x-3">
            <a href="{{ route('admin.page.edit', $page) }}"
               class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition-colors duration-200 flex items-center space-x-2">
                <i class="fa-solid fa-edit"></i>
                <span>Edit</span>
            </a>
            <a href="{{ route('admin.page.index') }}"
               class="bg-gray-500 text-white px-4 py-2 rounded-lg hover:bg-gray-600 transition-colors duration-200 flex items-center space-x-2">
                <i class="fa-solid fa-arrow-left"></i>
                <span>Back to Pages</span>
            </a>
        </div>
    </div>

    {{-- Page Information --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-200">
        <div class="p-6">
            <div class="flex items-start space-x-6 mb-6">
                {{-- Page Image --}}
                @if($page->image)
                    <div class="flex-shrink-0">
                        <img src="{{ asset('storage/' . $page->image) }}" 
                             alt="{{ $page->title }}" 
                             class="w-24 h-24 object-cover rounded-lg">
                    </div>
                @endif
                
                <div class="flex-1">
                    <div class="flex items-center space-x-3 mb-4">
                        @if($page->icon)
                            <i class="{{ $page->icon }} text-primary text-2xl"></i>
                        @endif
                        <h2 class="text-2xl font-semibold text-gray-900">{{ $page->title }}</h2>
                        @if($page->is_active)
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                Active
                            </span>
                        @else
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                Inactive
                            </span>
                        @endif
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Slug</dt>
                            <dd class="mt-1">
                                <code class="bg-gray-100 text-gray-800 px-2 py-1 rounded text-sm">{{ $page->slug }}</code>
                            </dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Created</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $page->created_at->format('M d, Y H:i') }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Last Updated</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $page->updated_at->format('M d, Y H:i') }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">URL</dt>
                            <dd class="mt-1">
                                <a href="{{ url('/pagina/' . $page->slug) }}" 
                                   target="_blank"
                                   class="text-primary hover:text-primary/80 text-sm">
                                    /pagina/{{ $page->slug }}
                                    <i class="fa-solid fa-external-link-alt ml-1"></i>
                                </a>
                            </dd>
                        </div>
                    </div>
                </div>
            </div>
            
            {{-- Short Body --}}
            <div class="border-t border-gray-200 pt-6 mb-6">
                <h3 class="text-lg font-medium text-gray-900 mb-3">Short Description</h3>
                <div class="prose max-w-none">
                    <p class="text-gray-700">{{ $page->short_body }}</p>
                </div>
            </div>
            
            {{-- Long Body --}}
            <div class="border-t border-gray-200 pt-6 mb-6">
                <h3 class="text-lg font-medium text-gray-900 mb-3">Full Content</h3>
                <div class="prose max-w-none">
                    {!! $page->long_body !!}
                </div>
            </div>
            
            {{-- SEO Information --}}
            @if($page->meta_title || $page->meta_body)
                <div class="border-t border-gray-200 pt-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-3">SEO Information</h3>
                    
                    @if($page->meta_title)
                        <div class="mb-4">
                            <dt class="text-sm font-medium text-gray-500 mb-1">Meta Title</dt>
                            <dd class="text-sm text-gray-900">{{ $page->meta_title }}</dd>
                        </div>
                    @endif
                    
                    @if($page->meta_body)
                        <div>
                            <dt class="text-sm font-medium text-gray-500 mb-1">Meta Description</dt>
                            <dd class="text-sm text-gray-900">{{ $page->meta_body }}</dd>
                        </div>
                    @endif
                </div>
            @endif
        </div>
    </div>
</div>
</x-layouts.admin>
