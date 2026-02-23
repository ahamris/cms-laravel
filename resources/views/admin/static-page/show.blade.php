<x-layouts.admin title="Static Page Details">

    <div class="flex items-center justify-between mb-6">
        <div class="flex items-center gap-3">
            <div class="w-12 h-12 bg-primary rounded-md flex items-center justify-center">
                <i class="fa-solid fa-info-circle text-white text-base"></i>
            </div>
            <div class="flex flex-col gap-1">
                <h2>{{ $staticPage->title }}</h2>
                <p>View static page details</p>
            </div>
        </div>
        <div class="flex items-center space-x-3">
            <a href="{{ route('admin.content.static-page.edit', $staticPage) }}" 
               class="px-5 py-2 rounded-md bg-yellow-600 text-white text-sm hover:bg-yellow-700 transition-colors duration-200 flex items-center space-x-2">
                <i class="fa fa-edit"></i>
                <span>Edit</span>
            </a>
            <a href="{{ route('admin.content.static-page.index') }}" 
               class="px-5 py-2 rounded-md bg-white border border-gray-200 text-gray-700 text-sm hover:bg-gray-50 transition-colors duration-200 flex items-center space-x-2">
                <i class="fa fa-arrow-left"></i>
                <span>Back to List</span>
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Content -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Basic Information -->
            <div class="bg-gray-50/50 rounded-md border border-gray-200 p-6">
                <h3 class="text-base font-semibold text-gray-900 mb-4 flex items-center">
                    <i class="fa-solid fa-info-circle mr-2 text-blue-500"></i>
                    Basic Information
                </h3>
                
                <div class="space-y-4">
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-1">Title</label>
                        <p class="text-sm text-gray-900">{{ $staticPage->title }}</p>
                    </div>

                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-1">Slug</label>
                        <p class="text-sm text-gray-900 font-mono">{{ $staticPage->slug }}</p>
                    </div>

                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-1">Status</label>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $staticPage->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                            <i class="fa fa-{{ $staticPage->is_active ? 'check' : 'times' }} mr-1"></i>
                            {{ $staticPage->is_active ? 'Active' : 'Inactive' }}
                        </span>
                    </div>

                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-1">Content</label>
                        <div class="mt-1 bg-white rounded-md p-4 border border-gray-200 prose max-w-none">
                            {!! $staticPage->body !!}
                        </div>
                    </div>
                </div>
            </div>

            <!-- SEO Information -->
            <div class="bg-gray-50/50 rounded-md border border-gray-200 p-6">
                <h3 class="text-base font-semibold text-gray-900 mb-4 flex items-center">
                    <i class="fa-solid fa-search mr-2 text-green-500"></i>
                    SEO Information
                </h3>
                
                <div class="space-y-4">
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-1">Meta Title</label>
                        <p class="text-sm text-gray-900">{{ $staticPage->meta_title ?: 'Not set' }}</p>
                    </div>

                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-1">Meta Description</label>
                        <p class="text-sm text-gray-900">{{ $staticPage->meta_description ?: 'Not set' }}</p>
                    </div>

                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-1">Keywords</label>
                        @if($staticPage->keywords)
                            <div class="mt-1 flex flex-wrap gap-2">
                                @foreach(explode(',', $staticPage->keywords) as $keyword)
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                        {{ trim($keyword) }}
                                    </span>
                                @endforeach
                            </div>
                        @else
                            <p class="mt-1 text-xs text-gray-500">No keywords set</p>
                        @endif
                    </div>
                </div>
            </div>

        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Page Image -->
            @if($staticPage->image)
                <div class="bg-gray-50/50 rounded-md border border-gray-200 p-6">
                    <h3 class="text-base font-semibold text-gray-900 mb-4 flex items-center">
                        <i class="fa-solid fa-image mr-2 text-orange-500"></i>
                        Featured Image
                    </h3>
                    <img src="{{ Storage::disk('public')->url($staticPage->image) }}" 
                         alt="{{ $staticPage->title }}" 
                         class="w-full h-48 object-cover rounded-md">
                </div>
            @endif

            <!-- Page Statistics -->
            <div class="bg-gray-50/50 rounded-md border border-gray-200 p-6">
                <h3 class="text-base font-semibold text-gray-900 mb-4 flex items-center">
                    <i class="fa-solid fa-chart-bar mr-2 text-blue-500"></i>
                    Page Statistics
                </h3>
                
                <div class="space-y-3">
                    <div class="flex justify-between">
                        <span class="text-xs font-medium text-gray-700">Created</span>
                        <span class="text-xs text-gray-900">{{ $staticPage->created_at->format('M j, Y') }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-xs font-medium text-gray-700">Last Updated</span>
                        <span class="text-xs text-gray-900">{{ $staticPage->updated_at->format('M j, Y') }}</span>
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="bg-gray-50/50 rounded-md border border-gray-200 p-6">
                <h3 class="text-base font-semibold text-gray-900 mb-4 flex items-center">
                    <i class="fa-solid fa-bolt mr-2 text-yellow-500"></i>
                    Quick Actions
                </h3>
                
                <div class="space-y-2">
                    <form action="{{ route('admin.content.static-page.toggle-active', $staticPage) }}" method="POST" class="w-full">
                        @csrf
                        <button type="submit" 
                                class="w-full text-left px-4 py-2 text-sm text-gray-700 bg-white border border-gray-200 rounded-md hover:bg-gray-50 transition-colors duration-200 focus:outline-none">
                            <i class="fa fa-{{ $staticPage->is_active ? 'pause' : 'play' }} mr-2"></i>
                            {{ $staticPage->is_active ? 'Deactivate' : 'Activate' }} Page
                        </button>
                    </form>
                    
                    <a href="{{ route('admin.content.static-page.edit', $staticPage) }}" 
                       class="block w-full text-left px-4 py-2 text-sm text-gray-700 bg-white border border-gray-200 rounded-md hover:bg-gray-50 transition-colors duration-200">
                        <i class="fa fa-edit mr-2"></i>
                        Edit Page
                    </a>
                    
                    <form action="{{ route('admin.content.static-page.destroy', $staticPage) }}" 
                          method="POST" 
                          class="w-full"
                          onsubmit="return confirm('Are you sure you want to delete this static page?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" 
                                class="w-full text-left px-4 py-2 text-sm text-red-600 bg-white border border-red-200 rounded-md hover:bg-red-50 transition-colors duration-200 focus:outline-none">
                            <i class="fa fa-trash mr-2"></i>
                            Delete Page
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

<script>
</script>

</x-layouts.admin>
