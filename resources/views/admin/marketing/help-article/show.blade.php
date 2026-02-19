<x-layouts.admin title="View Help Article">
<div class="space-y-6">
    {{-- Header --}}
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">{{ $helpArticle->title }}</h1>
            <p class="text-gray-600">Help Article Details</p>
        </div>
        <div class="flex items-center space-x-3">
            <a href="{{ route('admin.marketing.help-article.edit', $helpArticle) }}"
               class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition-colors duration-200">
                <i class="fa-solid fa-edit mr-2"></i>
                Edit Article
            </a>
            <a href="{{ route('admin.marketing.help-article.index') }}"
               class="bg-gray-600 text-white px-4 py-2 rounded-lg hover:bg-gray-700 transition-colors duration-200">
                <i class="fa-solid fa-arrow-left mr-2"></i>
                Back to List
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        {{-- Main Content --}}
        <div class="lg:col-span-2 space-y-6">
            {{-- Article Header --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-200">
                <div class="p-8">
                    <div class="flex items-start">
                        <div class="h-16 w-16 rounded-xl bg-blue-50 flex items-center justify-center mr-6 flex-shrink-0">
                            <i class="fa-solid fa-question-circle text-blue-600 text-2xl"></i>
                        </div>
                        
                        <div class="flex-1">
                            <div class="flex items-center space-x-3 mb-2">
                                <h2 class="text-2xl font-bold text-gray-900">{{ $helpArticle->title }}</h2>
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium 
                                    @switch($helpArticle->difficulty_level)
                                        @case('beginner') bg-green-100 text-green-800 @break
                                        @case('intermediate') bg-yellow-100 text-yellow-800 @break
                                        @case('advanced') bg-red-100 text-red-800 @break
                                    @endswitch">
                                    @switch($helpArticle->difficulty_level)
                                        @case('beginner') <i class="fa-solid fa-seedling mr-1"></i>Beginner @break
                                        @case('intermediate') <i class="fa-solid fa-graduation-cap mr-1"></i>Intermediate @break
                                        @case('advanced') <i class="fa-solid fa-rocket mr-1"></i>Advanced @break
                                    @endswitch
                                </span>
                            </div>
                            
                            @if($helpArticle->excerpt)
                                <p class="text-gray-600 mb-3">{{ $helpArticle->excerpt }}</p>
                            @endif
                            
                            <div class="flex items-center space-x-4 text-sm text-gray-500">
                                @if($helpArticle->estimated_read_time)
                                    <div class="flex items-center">
                                        <i class="fa-solid fa-clock mr-1"></i>
                                        {{ $helpArticle->estimated_read_time }} min read
                                    </div>
                                @endif
                                <div class="flex items-center">
                                    <i class="fa-solid fa-calendar mr-1"></i>
                                    {{ $helpArticle->created_at->format('M j, Y') }}
                                </div>
                                @if($helpArticle->updated_at != $helpArticle->created_at)
                                    <div class="flex items-center">
                                        <i class="fa-solid fa-edit mr-1"></i>
                                        Updated {{ $helpArticle->updated_at->format('M j, Y') }}
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Article Content --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-200">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900">Article Content</h3>
                </div>
                <div class="p-6">
                    <div class="prose max-w-none">
                        {!! $helpArticle->content !!}
                    </div>
                </div>
            </div>

            {{-- Related Product Features --}}
            @if($helpArticle->productFeatures && $helpArticle->productFeatures->count() > 0)
                <div class="bg-white rounded-xl shadow-sm border border-gray-200">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-900">Related Product Features</h3>
                    </div>
                    <div class="p-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            @foreach($helpArticle->productFeatures as $feature)
                                <div class="flex items-center p-3 border border-gray-200 rounded-lg">
                                    @if($feature->icon)
                                        <div class="h-10 w-10 rounded-lg bg-blue-50 flex items-center justify-center mr-3">
                                            <i class="fa-solid {{ $feature->icon }} text-blue-600"></i>
                                        </div>
                                    @endif
                                    <div class="flex-1">
                                        <div class="flex items-center space-x-2">
                                            <h4 class="font-medium text-gray-900">{{ $feature->name }}</h4>
                                            @if($feature->is_premium)
                                                <span class="inline-flex items-center px-1.5 py-0.5 rounded text-xs font-medium bg-yellow-100 text-yellow-800">
                                                    <i class="fa-solid fa-crown mr-1"></i>Premium
                                                </span>
                                            @endif
                                        </div>
                                        @if($feature->description)
                                            <p class="text-sm text-gray-600">{{ Str::limit($feature->description, 60) }}</p>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endif

            {{-- Tags --}}
            @if($helpArticle->tags && count($helpArticle->tags) > 0)
                <div class="bg-white rounded-xl shadow-sm border border-gray-200">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-900">Tags</h3>
                    </div>
                    <div class="p-6">
                        <div class="flex flex-wrap gap-2">
                            @foreach($helpArticle->tags as $tag)
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-gray-100 text-gray-800">
                                    <i class="fa-solid fa-tag mr-1"></i>
                                    {{ $tag }}
                                </span>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endif
        </div>

        {{-- Sidebar --}}
        <div class="lg:col-span-1 space-y-6">
            {{-- Article Info --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-200">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900">Article Information</h3>
                </div>
                <div class="p-6 space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Difficulty Level</label>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                            @switch($helpArticle->difficulty_level)
                                @case('beginner') bg-green-100 text-green-800 @break
                                @case('intermediate') bg-yellow-100 text-yellow-800 @break
                                @case('advanced') bg-red-100 text-red-800 @break
                            @endswitch">
                            @switch($helpArticle->difficulty_level)
                                @case('beginner') <i class="fa-solid fa-seedling mr-1"></i>Beginner @break
                                @case('intermediate') <i class="fa-solid fa-graduation-cap mr-1"></i>Intermediate @break
                                @case('advanced') <i class="fa-solid fa-rocket mr-1"></i>Advanced @break
                            @endswitch
                        </span>
                    </div>

                    @if($helpArticle->estimated_read_time)
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Estimated Read Time</label>
                            <div class="flex items-center text-gray-900">
                                <i class="fa-solid fa-clock mr-2 text-gray-400"></i>
                                {{ $helpArticle->estimated_read_time }} minutes
                            </div>
                        </div>
                    @endif

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Slug</label>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                            {{ $helpArticle->slug }}
                        </span>
                    </div>
                </div>
            </div>

            {{-- Status & Settings --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-200">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900">Status & Settings</h3>
                </div>
                <div class="p-6 space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $helpArticle->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                            <i class="fa-solid fa-{{ $helpArticle->is_active ? 'check' : 'times' }} mr-1"></i>
                            {{ $helpArticle->is_active ? 'Active' : 'Inactive' }}
                        </span>
                    </div>

                    @if($helpArticle->is_featured)
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Featured</label>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                <i class="fa-solid fa-star mr-1"></i>
                                Featured Article
                            </span>
                        </div>
                    @endif

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Sort Order</label>
                        <p class="text-gray-900">{{ $helpArticle->sort_order }}</p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Created</label>
                        <p class="text-gray-900 text-sm">{{ $helpArticle->created_at->format('M j, Y \a\t g:i A') }}</p>
                    </div>

                    @if($helpArticle->updated_at != $helpArticle->created_at)
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Last Updated</label>
                            <p class="text-gray-900 text-sm">{{ $helpArticle->updated_at->format('M j, Y \a\t g:i A') }}</p>
                        </div>
                    @endif
                </div>
            </div>

            {{-- Statistics --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-200">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900">Statistics</h3>
                </div>
                <div class="p-6 space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Related Features</label>
                        <p class="text-gray-900">{{ $helpArticle->productFeatures->count() }} features</p>
                    </div>
                    
                    @if($helpArticle->tags)
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Tags</label>
                            <p class="text-gray-900">{{ count($helpArticle->tags) }} tags</p>
                        </div>
                    @endif

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Content Length</label>
                        <p class="text-gray-900">{{ str_word_count($helpArticle->content) }} words</p>
                    </div>
                </div>
            </div>

            {{-- Quick Actions --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-200">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900">Quick Actions</h3>
                </div>
                <div class="p-6 space-y-3">
                    <button onclick="copyContent()"
                            class="w-full bg-gray-600 text-white py-2 px-4 rounded-lg hover:bg-gray-700 transition-colors duration-200">
                        <i class="fa-solid fa-copy mr-2"></i>
                        Copy Content
                    </button>
                    
                    <button onclick="copyUrl()"
                            class="w-full bg-blue-600 text-white py-2 px-4 rounded-lg hover:bg-blue-700 transition-colors duration-200">
                        <i class="fa-solid fa-link mr-2"></i>
                        Copy Article URL
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function copyContent() {
    const content = `{{ $helpArticle->title }}\n\n{{ $helpArticle->content }}`;
    navigator.clipboard.writeText(content).then(function() {
        showCopySuccess('Content copied to clipboard!');
    });
}

function copyUrl() {
    const url = `{{ url('/help/' . $helpArticle->slug) }}`;
    navigator.clipboard.writeText(url).then(function() {
        showCopySuccess('URL copied to clipboard!');
    });
}

function showCopySuccess(message) {
    // Create a temporary success message
    const toast = document.createElement('div');
    toast.className = 'fixed top-4 right-4 bg-green-500 text-white px-4 py-2 rounded-lg shadow-lg z-50';
    toast.textContent = message;
    document.body.appendChild(toast);
    
    setTimeout(() => {
        toast.remove();
    }, 3000);
}
</script>
</x-layouts.admin>
