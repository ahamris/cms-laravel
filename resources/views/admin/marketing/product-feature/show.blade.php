<x-layouts.admin title="View Product Feature">
<div class="space-y-6">
    {{-- Header --}}
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">{{ $productFeature->name }}</h1>
            <p class="text-gray-600">Product Feature Details</p>
        </div>
        <div class="flex items-center space-x-3">
            <a href="{{ route('admin.marketing.product-feature.edit', $productFeature) }}"
               class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition-colors duration-200">
                <i class="fa-solid fa-edit mr-2"></i>
                Edit Feature
            </a>
            <a href="{{ route('admin.marketing.product-feature.index') }}"
               class="bg-gray-600 text-white px-4 py-2 rounded-lg hover:bg-gray-700 transition-colors duration-200">
                <i class="fa-solid fa-arrow-left mr-2"></i>
                Back to List
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        {{-- Main Content --}}
        <div class="lg:col-span-2 space-y-6">
            {{-- Feature Overview --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-200">
                <div class="p-8">
                    <div class="flex items-start">
                        @if($productFeature->icon)
                            <div class="h-16 w-16 rounded-xl bg-blue-50 flex items-center justify-center mr-6 flex-shrink-0">
                                <i class="fa-solid {{ $productFeature->icon }} text-blue-600 text-2xl"></i>
                            </div>
                        @else
                            <div class="h-16 w-16 rounded-xl bg-gray-100 flex items-center justify-center mr-6 flex-shrink-0">
                                <i class="fa-solid fa-star text-gray-400 text-2xl"></i>
                            </div>
                        @endif
                        
                        <div class="flex-1">
                            <div class="flex items-center space-x-3 mb-2">
                                <h2 class="text-2xl font-bold text-gray-900">{{ $productFeature->name }}</h2>
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium {{ $productFeature->is_premium ? 'bg-yellow-100 text-yellow-800' : 'bg-green-100 text-green-800' }}">
                                    <i class="fa-solid fa-{{ $productFeature->is_premium ? 'crown' : 'check' }} mr-1"></i>
                                    {{ $productFeature->is_premium ? 'Premium' : 'Free' }}
                                </span>
                            </div>
                            
                            @if($productFeature->category)
                                <p class="text-gray-600 mb-3">
                                    <i class="fa-solid fa-folder mr-1"></i>
                                    {{ $productFeature->category }}
                                </p>
                            @endif
                            
                            @if($productFeature->description)
                                <p class="text-gray-700 leading-relaxed">{{ $productFeature->description }}</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            {{-- Basic Information --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-200">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900">Basic Information</h3>
                </div>
                <div class="p-6 space-y-4">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Feature Name</label>
                            <p class="text-gray-900">{{ $productFeature->name }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Slug</label>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                {{ $productFeature->slug }}
                            </span>
                        </div>
                    </div>
                    
                    @if($productFeature->description)
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                            <p class="text-gray-900">{{ $productFeature->description }}</p>
                        </div>
                    @endif

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        @if($productFeature->icon)
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Icon</label>
                                <div class="flex items-center">
                                    <i class="fa-solid {{ $productFeature->icon }} text-lg mr-2 text-blue-600"></i>
                                    <code class="text-sm bg-gray-100 px-2 py-1 rounded">{{ $productFeature->icon }}</code>
                                </div>
                            </div>
                        @endif

                        @if($productFeature->category)
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Category</label>
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                    {{ $productFeature->category }}
                                </span>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Benefits --}}
            @if($productFeature->benefits && count($productFeature->benefits) > 0)
                <div class="bg-white rounded-xl shadow-sm border border-gray-200">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-900">Benefits</h3>
                    </div>
                    <div class="p-6">
                        <ul class="space-y-2">
                            @foreach($productFeature->benefits as $benefit)
                                <li class="flex items-start">
                                    <i class="fa-solid fa-check-circle text-green-500 mt-1 mr-3 flex-shrink-0"></i>
                                    <span class="text-gray-900">{{ $benefit }}</span>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            @endif

            {{-- Related Content --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-200">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900">Related Content</h3>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Help Articles</label>
                            @if($productFeature->helpArticles()->count() > 0)
                                <div class="space-y-1">
                                    @foreach($productFeature->helpArticles()->limit(3)->get() as $article)
                                        <a href="{{ route('admin.marketing.help-article.show', $article) }}" class="block text-sm text-blue-600 hover:text-blue-800">
                                            <i class="fa-solid fa-question-circle mr-1"></i>
                                            {{ $article->title }}
                                        </a>
                                    @endforeach
                                    @if($productFeature->helpArticles()->count() > 3)
                                        <p class="text-xs text-gray-500">+{{ $productFeature->helpArticles()->count() - 3 }} more articles</p>
                                    @endif
                                </div>
                            @else
                                <p class="text-gray-500 text-sm">No help articles linked</p>
                            @endif
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Case Studies</label>
                            @if($productFeature->caseStudies()->count() > 0)
                                <div class="space-y-1">
                                    @foreach($productFeature->caseStudies()->limit(3)->get() as $caseStudy)
                                        <a href="{{ route('admin.marketing.case-study.show', $caseStudy) }}" class="block text-sm text-blue-600 hover:text-blue-800">
                                            <i class="fa-solid fa-chart-line mr-1"></i>
                                            {{ $caseStudy->title }}
                                        </a>
                                    @endforeach
                                    @if($productFeature->caseStudies()->count() > 3)
                                        <p class="text-xs text-gray-500">+{{ $productFeature->caseStudies()->count() - 3 }} more case studies</p>
                                    @endif
                                </div>
                            @else
                                <p class="text-gray-500 text-sm">No case studies linked</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Sidebar --}}
        <div class="lg:col-span-1 space-y-6">
            {{-- Feature Type --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-200">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900">Feature Type</h3>
                </div>
                <div class="p-6 text-center">
                    <div class="mb-4">
                        @if($productFeature->is_premium)
                            <div class="w-16 h-16 rounded-full bg-yellow-100 flex items-center justify-center mx-auto mb-3">
                                <i class="fa-solid fa-crown text-yellow-600 text-2xl"></i>
                            </div>
                            <h4 class="font-semibold text-gray-900">Premium Feature</h4>
                            <p class="text-sm text-gray-600">Available in paid plans</p>
                        @else
                            <div class="w-16 h-16 rounded-full bg-green-100 flex items-center justify-center mx-auto mb-3">
                                <i class="fa-solid fa-check text-green-600 text-2xl"></i>
                            </div>
                            <h4 class="font-semibold text-gray-900">Free Feature</h4>
                            <p class="text-sm text-gray-600">Available in all plans</p>
                        @endif
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
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $productFeature->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                            <i class="fa-solid fa-{{ $productFeature->is_active ? 'check' : 'times' }} mr-1"></i>
                            {{ $productFeature->is_active ? 'Active' : 'Inactive' }}
                        </span>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Sort Order</label>
                        <p class="text-gray-900">{{ $productFeature->sort_order }}</p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Created</label>
                        <p class="text-gray-900 text-sm">{{ $productFeature->created_at->format('M j, Y \a\t g:i A') }}</p>
                    </div>

                    @if($productFeature->updated_at != $productFeature->created_at)
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Last Updated</label>
                            <p class="text-gray-900 text-sm">{{ $productFeature->updated_at->format('M j, Y \a\t g:i A') }}</p>
                        </div>
                    @endif
                </div>
            </div>

            {{-- Usage Statistics --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-200">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900">Usage Statistics</h3>
                </div>
                <div class="p-6 space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Help Articles</label>
                        <p class="text-gray-900">{{ $productFeature->helpArticles()->count() }} articles</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Case Studies</label>
                        <p class="text-gray-900">{{ $productFeature->caseStudies()->count() }} case studies</p>
                    </div>
                    <div class="pt-2 border-t border-gray-200">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Total Usage</label>
                        <p class="text-lg font-semibold text-primary">
                            {{ $productFeature->helpArticles()->count() + $productFeature->caseStudies()->count() }}
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</x-layouts.admin>
