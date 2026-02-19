<x-layouts.admin title="View Case Study">
<div class="space-y-6">
    {{-- Header --}}
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">{{ $caseStudy->title }}</h1>
            <p class="text-gray-600">Case Study Details</p>
        </div>
        <div class="flex items-center space-x-3">
            <a href="{{ route('admin.marketing.case-study.edit', $caseStudy) }}"
               class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition-colors duration-200">
                <i class="fa-solid fa-edit mr-2"></i>
                Edit Case Study
            </a>
            <a href="{{ route('admin.marketing.case-study.index') }}"
               class="bg-gray-600 text-white px-4 py-2 rounded-lg hover:bg-gray-700 transition-colors duration-200">
                <i class="fa-solid fa-arrow-left mr-2"></i>
                Back to List
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        {{-- Main Content --}}
        <div class="lg:col-span-2 space-y-6">
            {{-- Case Study Header --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-200">
                <div class="p-8">
                    <div class="flex items-start">
                        @if($caseStudy->featured_image)
                            <img src="{{ asset('storage/' . $caseStudy->featured_image) }}" 
                                 alt="{{ $caseStudy->title }}" 
                                 class="w-24 h-16 object-cover rounded-lg mr-6 flex-shrink-0">
                        @else
                            <div class="h-16 w-24 rounded-lg bg-green-50 flex items-center justify-center mr-6 flex-shrink-0">
                                <i class="fa-solid fa-chart-line text-green-600 text-2xl"></i>
                            </div>
                        @endif
                        
                        <div class="flex-1">
                            <div class="flex items-center space-x-3 mb-2">
                                <h2 class="text-2xl font-bold text-gray-900">{{ $caseStudy->title }}</h2>
                                @if($caseStudy->is_featured)
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-yellow-100 text-yellow-800">
                                        <i class="fa-solid fa-star mr-1"></i>Featured
                                    </span>
                                @endif
                            </div>
                            
                            <div class="flex items-center space-x-4 text-sm text-gray-500 mb-4">
                                <div class="flex items-center">
                                    <i class="fa-solid fa-building mr-1"></i>
                                    {{ $caseStudy->client_name }}
                                </div>
                                @if($caseStudy->client_industry)
                                    <div class="flex items-center">
                                        <i class="fa-solid fa-industry mr-1"></i>
                                        {{ $caseStudy->client_industry }}
                                    </div>
                                @endif
                                @if($caseStudy->client_size)
                                    <div class="flex items-center">
                                        <i class="fa-solid fa-users mr-1"></i>
                                        {{ $caseStudy->client_size }}
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Challenge, Solution, Results --}}
            <div class="grid grid-cols-1 gap-6">
                {{-- Challenge --}}
                <div class="bg-white rounded-xl shadow-sm border border-gray-200">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <div class="flex items-center">
                            <div class="h-8 w-8 rounded-lg bg-red-50 flex items-center justify-center mr-3">
                                <i class="fa-solid fa-exclamation-triangle text-red-600"></i>
                            </div>
                            <h3 class="text-lg font-semibold text-gray-900">Challenge</h3>
                        </div>
                    </div>
                    <div class="p-6">
                        <p class="text-gray-700 leading-relaxed">{{ $caseStudy->challenge }}</p>
                    </div>
                </div>

                {{-- Solution --}}
                <div class="bg-white rounded-xl shadow-sm border border-gray-200">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <div class="flex items-center">
                            <div class="h-8 w-8 rounded-lg bg-blue-50 flex items-center justify-center mr-3">
                                <i class="fa-solid fa-lightbulb text-blue-600"></i>
                            </div>
                            <h3 class="text-lg font-semibold text-gray-900">Solution</h3>
                        </div>
                    </div>
                    <div class="p-6">
                        <p class="text-gray-700 leading-relaxed">{{ $caseStudy->solution }}</p>
                    </div>
                </div>

                {{-- Results --}}
                <div class="bg-white rounded-xl shadow-sm border border-gray-200">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <div class="flex items-center">
                            <div class="h-8 w-8 rounded-lg bg-green-50 flex items-center justify-center mr-3">
                                <i class="fa-solid fa-trophy text-green-600"></i>
                            </div>
                            <h3 class="text-lg font-semibold text-gray-900">Results</h3>
                        </div>
                    </div>
                    <div class="p-6">
                        <p class="text-gray-700 leading-relaxed">{{ $caseStudy->results }}</p>
                    </div>
                </div>
            </div>

            {{-- Key Metrics --}}
            @if($caseStudy->metrics && count($caseStudy->metrics) > 0)
                <div class="bg-white rounded-xl shadow-sm border border-gray-200">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <div class="flex items-center">
                            <div class="h-8 w-8 rounded-lg bg-purple-50 flex items-center justify-center mr-3">
                                <i class="fa-solid fa-chart-bar text-purple-600"></i>
                            </div>
                            <h3 class="text-lg font-semibold text-gray-900">Key Metrics</h3>
                        </div>
                    </div>
                    <div class="p-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            @foreach($caseStudy->metrics as $metric)
                                <div class="flex items-center p-3 bg-gray-50 rounded-lg">
                                    <i class="fa-solid fa-arrow-up text-green-600 mr-3"></i>
                                    <span class="text-gray-900">{{ $metric }}</span>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endif

            {{-- Client Quote --}}
            @if($caseStudy->quote)
                <div class="bg-white rounded-xl shadow-sm border border-gray-200">
                    <div class="p-8">
                        <div class="text-center">
                            <i class="fa-solid fa-quote-left text-4xl text-primary/20 mb-4"></i>
                            <blockquote class="text-xl text-gray-900 italic leading-relaxed mb-6">
                                "{{ $caseStudy->quote }}"
                            </blockquote>
                            
                            @if($caseStudy->quote_author || $caseStudy->quote_position)
                                <div class="border-t border-gray-200 pt-6">
                                    @if($caseStudy->quote_author)
                                        <p class="text-lg font-semibold text-gray-900">{{ $caseStudy->quote_author }}</p>
                                    @endif
                                    @if($caseStudy->quote_position)
                                        <p class="text-gray-600">{{ $caseStudy->quote_position }}</p>
                                    @endif
                                    <p class="text-gray-600">{{ $caseStudy->client_name }}</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            @endif

            {{-- Related Product Features --}}
            @if($caseStudy->productFeatures && $caseStudy->productFeatures->count() > 0)
                <div class="bg-white rounded-xl shadow-sm border border-gray-200">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-900">Product Features Used</h3>
                    </div>
                    <div class="p-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            @foreach($caseStudy->productFeatures as $feature)
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
            @if($caseStudy->tags && count($caseStudy->tags) > 0)
                <div class="bg-white rounded-xl shadow-sm border border-gray-200">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-900">Tags</h3>
                    </div>
                    <div class="p-6">
                        <div class="flex flex-wrap gap-2">
                            @foreach($caseStudy->tags as $tag)
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
            {{-- Client Information --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-200">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900">Client Information</h3>
                </div>
                <div class="p-6 space-y-4">
                    <div class="text-center">
                        @if($caseStudy->client_logo)
                            <img src="{{ asset('storage/' . $caseStudy->client_logo) }}" 
                                 alt="{{ $caseStudy->client_name }}" 
                                 class="w-20 h-16 object-contain mx-auto mb-3 bg-gray-50 rounded">
                        @else
                            <div class="w-20 h-16 bg-gray-200 flex items-center justify-center mx-auto mb-3 rounded">
                                <i class="fa-solid fa-building text-gray-400 text-xl"></i>
                            </div>
                        @endif
                        <h4 class="font-semibold text-gray-900">{{ $caseStudy->client_name }}</h4>
                    </div>

                    @if($caseStudy->client_industry)
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Industry</label>
                            <p class="text-gray-900">{{ $caseStudy->client_industry }}</p>
                        </div>
                    @endif

                    @if($caseStudy->client_size)
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Company Size</label>
                            <p class="text-gray-900">{{ $caseStudy->client_size }}</p>
                        </div>
                    @endif

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Slug</label>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                            {{ $caseStudy->slug }}
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
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $caseStudy->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                            <i class="fa-solid fa-{{ $caseStudy->is_active ? 'check' : 'times' }} mr-1"></i>
                            {{ $caseStudy->is_active ? 'Active' : 'Inactive' }}
                        </span>
                    </div>

                    @if($caseStudy->is_featured)
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Featured</label>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                <i class="fa-solid fa-star mr-1"></i>
                                Featured Case Study
                            </span>
                        </div>
                    @endif

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Sort Order</label>
                        <p class="text-gray-900">{{ $caseStudy->sort_order }}</p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Created</label>
                        <p class="text-gray-900 text-sm">{{ $caseStudy->created_at->format('M j, Y \a\t g:i A') }}</p>
                    </div>

                    @if($caseStudy->updated_at != $caseStudy->created_at)
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Last Updated</label>
                            <p class="text-gray-900 text-sm">{{ $caseStudy->updated_at->format('M j, Y \a\t g:i A') }}</p>
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
                        <p class="text-gray-900">{{ $caseStudy->productFeatures->count() }} features</p>
                    </div>
                    
                    @if($caseStudy->metrics)
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Key Metrics</label>
                            <p class="text-gray-900">{{ count($caseStudy->metrics) }} metrics</p>
                        </div>
                    @endif

                    @if($caseStudy->tags)
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Tags</label>
                            <p class="text-gray-900">{{ count($caseStudy->tags) }} tags</p>
                        </div>
                    @endif

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Content Length</label>
                        <p class="text-gray-900">{{ str_word_count($caseStudy->challenge . ' ' . $caseStudy->solution . ' ' . $caseStudy->results) }} words</p>
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
                        Copy Case Study
                    </button>
                    
                    <button onclick="copyUrl()"
                            class="w-full bg-blue-600 text-white py-2 px-4 rounded-lg hover:bg-blue-700 transition-colors duration-200">
                        <i class="fa-solid fa-link mr-2"></i>
                        Copy Case Study URL
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function copyContent() {
    const content = `{{ $caseStudy->title }}\n\nClient: {{ $caseStudy->client_name }}\n\nChallenge:\n{{ $caseStudy->challenge }}\n\nSolution:\n{{ $caseStudy->solution }}\n\nResults:\n{{ $caseStudy->results }}`;
    navigator.clipboard.writeText(content).then(function() {
        showCopySuccess('Case study copied to clipboard!');
    });
}

function copyUrl() {
    const url = `{{ url('/case-studies/' . $caseStudy->slug) }}`;
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
