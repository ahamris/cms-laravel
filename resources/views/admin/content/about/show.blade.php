<x-layouts.admin title="About Section Details">
<div class="space-y-6">
    {{-- Header --}}
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">{{ $about->title }}</h1>
            <p class="text-gray-600">About section details and preview</p>
        </div>
        <div class="flex items-center space-x-3">
            <a href="{{ route('admin.content.about.edit', $about) }}"
               class="bg-primary text-white px-4 py-2 rounded-lg hover:bg-primary/80 transition-colors duration-200">
                <i class="fa-solid fa-edit mr-2"></i>
                Edit
            </a>
            <a href="{{ route('admin.content.about.index') }}"
               class="bg-gray-600 text-white px-4 py-2 rounded-lg hover:bg-gray-700 transition-colors duration-200">
                <i class="fa-solid fa-arrow-left mr-2"></i>
                Back to List
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        {{-- Main Content --}}
        <div class="lg:col-span-2 space-y-6">
            {{-- Content Details --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-200">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                        <i class="fa-solid fa-info-circle mr-2 text-green-500"></i>
                        Content Details
                    </h3>
                </div>
                <div class="p-6 space-y-6">
                    {{-- Basic Information --}}
                    <div>
                        <h4 class="font-semibold text-gray-900 mb-3">Basic Information</h4>
                        <div class="space-y-3 text-sm">
                            <div>
                                <strong class="text-gray-700">Anchor:</strong>
                                <p class="text-gray-900 mt-1">#{{ $about->anchor }}</p>
                            </div>
                            <div>
                                <strong class="text-gray-700">Navigation Title:</strong>
                                <p class="text-gray-900 mt-1">{{ $about->nav_title }}</p>
                            </div>
                            <div>
                                <strong class="text-gray-700">Title:</strong>
                                <p class="text-gray-900 mt-1">{{ $about->title }}</p>
                            </div>
                            @if($about->subtitle)
                                <div>
                                    <strong class="text-gray-700">Subtitle:</strong>
                                    <p class="text-gray-900 mt-1">{{ $about->subtitle }}</p>
                                </div>
                            @endif
                            @if($about->slug)
                                <div>
                                    <strong class="text-gray-700">Slug:</strong>
                                    <p class="text-gray-900 mt-1">{{ $about->slug }}</p>
                                </div>
                            @endif
                        </div>
                    </div>

                    {{-- Content Body --}}
                    @if($about->short_body || $about->long_body)
                        <div>
                            <h4 class="font-semibold text-gray-900 mb-3">Content Body</h4>
                            <div class="space-y-3 text-sm">
                                @if($about->short_body)
                                    <div>
                                        <strong class="text-gray-700">Short Description:</strong>
                                        <div class="text-gray-900 mt-1 p-3 bg-gray-50 rounded-lg">
                                            {!! $about->short_body !!}
                                        </div>
                                    </div>
                                @endif
                                @if($about->long_body)
                                    <div>
                                        <strong class="text-gray-700">Long Description:</strong>
                                        <div class="text-gray-900 mt-1 p-3 bg-gray-50 rounded-lg">
                                            {!! $about->long_body !!}
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endif

                    {{-- List Items --}}
                    @if($about->list_items && count($about->list_items) > 0)
                        <div>
                            <h4 class="font-semibold text-gray-900 mb-3">List Items</h4>
                            <ul class="space-y-2">
                                @foreach($about->list_items as $item)
                                    <li class="flex items-start">
                                        <i class="fa-solid fa-check text-green-500 mt-1 mr-2 flex-shrink-0"></i>
                                        <span class="text-gray-900">{{ $item }}</span>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    {{-- Link Text --}}
                    @if($about->link_text)
                        <div>
                            <h4 class="font-semibold text-gray-900 mb-3">Call-to-Action</h4>
                            <div class="text-sm">
                                <strong class="text-gray-700">Link Text:</strong>
                                <p class="text-gray-900 mt-1">{{ $about->link_text }}</p>
                            </div>
                        </div>
                    @endif

                    {{-- Testimonial --}}
                    @if($about->testimonial_quote)
                        <div>
                            <h4 class="font-semibold text-gray-900 mb-3">Testimonial</h4>
                            <div class="bg-blue-50 border-l-4 border-blue-400 p-4 rounded-r-lg">
                                <blockquote class="text-gray-900 italic mb-2">
                                    "{{ $about->testimonial_quote }}"
                                </blockquote>
                                @if($about->testimonial_author || $about->testimonial_company)
                                    <div class="text-sm text-gray-600">
                                        @if($about->testimonial_author)
                                            <strong>{{ $about->testimonial_author }}</strong>
                                        @endif
                                        @if($about->testimonial_company)
                                            @if($about->testimonial_author), @endif
                                            {{ $about->testimonial_company }}
                                        @endif
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            {{-- SEO Information --}}
            @if($about->meta_title || $about->meta_description || $about->meta_keywords)
                <div class="bg-white rounded-xl shadow-sm border border-gray-200">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                            <i class="fa-solid fa-search mr-2 text-blue-500"></i>
                            SEO Information
                        </h3>
                    </div>
                    <div class="p-6 space-y-4">
                        @if($about->meta_title)
                            <div>
                                <strong class="text-gray-700 text-sm">Meta Title:</strong>
                                <p class="text-gray-900 mt-1">{{ $about->meta_title }}</p>
                            </div>
                        @endif
                        @if($about->meta_description)
                            <div>
                                <strong class="text-gray-700 text-sm">Meta Description:</strong>
                                <p class="text-gray-900 mt-1">{{ $about->meta_description }}</p>
                            </div>
                        @endif
                        @if($about->meta_keywords)
                            <div>
                                <strong class="text-gray-700 text-sm">Meta Keywords:</strong>
                                <p class="text-gray-900 mt-1">{{ $about->meta_keywords }}</p>
                            </div>
                        @endif
                    </div>
                </div>
            @endif
        </div>

        {{-- Sidebar --}}
        <div class="space-y-6">
            {{-- Image Preview --}}
            @if($about->image)
                <div class="bg-white rounded-xl shadow-sm border border-gray-200">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-900">Image</h3>
                    </div>
                    <div class="p-6">
                        <div class="aspect-video bg-gray-100 rounded-lg overflow-hidden">
                            <img src="{{ $about->getImageUrl() }}" 
                                 alt="{{ $about->title }}" 
                                 class="w-full h-full object-cover">
                        </div>
                        <p class="text-sm text-gray-600 mt-2">
                            <strong>Position:</strong> {{ ucfirst($about->image_position) }}
                        </p>
                    </div>
                </div>
            @endif

            {{-- Status & Settings --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-200">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900">Status & Settings</h3>
                </div>
                <div class="p-6 space-y-4">
                    <div class="flex items-center justify-between">
                        <span class="text-sm font-medium text-gray-700">Status:</span>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $about->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                            <i class="fa-solid {{ $about->is_active ? 'fa-check-circle' : 'fa-times-circle' }} mr-1"></i>
                            {{ $about->is_active ? 'Active' : 'Inactive' }}
                        </span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-sm font-medium text-gray-700">Sort Order:</span>
                        <span class="text-sm text-gray-900">{{ $about->sort_order ?? 0 }}</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-sm font-medium text-gray-700">Created:</span>
                        <span class="text-sm text-gray-900">{{ $about->created_at->format('M j, Y g:i A') }}</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-sm font-medium text-gray-700">Updated:</span>
                        <span class="text-sm text-gray-900">{{ $about->updated_at->format('M j, Y g:i A') }}</span>
                    </div>
                </div>
            </div>

            {{-- Actions --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-200">
                <div class="p-6">
                    <div class="space-y-3">
                        <a href="{{ route('admin.content.about.edit', $about) }}"
                           class="w-full bg-primary text-white px-4 py-2 rounded-lg hover:bg-primary/80 transition-colors duration-200 text-center block">
                            <i class="fa-solid fa-edit mr-2"></i>
                            Edit About Section
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</x-layouts.admin>
