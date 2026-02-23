<x-layouts.admin title="View Carousel Widget">
    {{-- Header --}}
    <div class="mb-8">
        <div class="flex items-center justify-between mb-6">
            <div>
                <h2 class="text-3xl font-bold text-gray-900">{{ $carouselWidget->name }}</h2>
                <p class="text-gray-600 mt-2">Carousel widget details</p>
            </div>
            <div class="flex items-center space-x-3">
                <a href="{{ route('admin.content.carousel-widgets.edit', $carouselWidget) }}"
                   class="inline-flex items-center px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-xl transition-all duration-200">
                    <i class="fa fa-edit mr-2"></i>
                    Edit
                </a>
                <a href="{{ route('admin.content.carousel-widgets.index') }}"
                   class="inline-flex items-center px-6 py-3 bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold rounded-xl transition-all duration-200">
                    <i class="fa fa-arrow-left mr-2"></i>
                    Back to List
                </a>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        {{-- Main Content --}}
        <div class="lg:col-span-2 space-y-6">
            {{-- Basic Information --}}
            <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-6 flex items-center">
                    <i class="fa fa-info-circle text-blue-600 mr-2"></i>
                    Basic Information
                </h3>

                <dl class="space-y-4">
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Name</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $carouselWidget->name }}</dd>
                    </div>

                    <div>
                        <dt class="text-sm font-medium text-gray-500">Identifier</dt>
                        <dd class="mt-1">
                            <span class="bg-blue-100 text-blue-800 px-3 py-1 rounded-full text-sm font-mono">
                                {{ $carouselWidget->identifier }}
                            </span>
                        </dd>
                    </div>

                    @if($carouselWidget->title)
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Title</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $carouselWidget->title }}</dd>
                        </div>
                    @endif

                    @if($carouselWidget->description)
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Description</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $carouselWidget->description }}</dd>
                        </div>
                    @endif
                </dl>
            </div>

            {{-- Configuration --}}
            <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-6 flex items-center">
                    <i class="fa fa-cog text-blue-600 mr-2"></i>
                    Configuration
                </h3>

                <dl class="grid grid-cols-2 gap-4">
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Data Source</dt>
                        <dd class="mt-1 text-sm text-gray-900 capitalize">{{ $carouselWidget->data_source }}</dd>
                    </div>

                    @if($carouselWidget->blogCategory)
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Blog Category</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $carouselWidget->blogCategory->name }}</dd>
                        </div>
                    @endif

                    <div>
                        <dt class="text-sm font-medium text-gray-500">Items Per Row</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $carouselWidget->items_per_row }}</dd>
                    </div>

                    <div>
                        <dt class="text-sm font-medium text-gray-500">Total Items</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $carouselWidget->total_items }}</dd>
                    </div>
                </dl>
            </div>

            {{-- Behavior Settings --}}
            <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-6 flex items-center">
                    <i class="fa fa-sliders-h text-blue-600 mr-2"></i>
                    Behavior Settings
                </h3>

                <dl class="grid grid-cols-2 gap-4">
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Show Arrows</dt>
                        <dd class="mt-1">
                            @if($carouselWidget->show_arrows)
                                <span class="bg-green-100 text-green-800 px-3 py-1 rounded-full text-xs font-semibold">Yes</span>
                            @else
                                <span class="bg-gray-100 text-gray-800 px-3 py-1 rounded-full text-xs font-semibold">No</span>
                            @endif
                        </dd>
                    </div>

                    <div>
                        <dt class="text-sm font-medium text-gray-500">Show Dots</dt>
                        <dd class="mt-1">
                            @if($carouselWidget->show_dots)
                                <span class="bg-green-100 text-green-800 px-3 py-1 rounded-full text-xs font-semibold">Yes</span>
                            @else
                                <span class="bg-gray-100 text-gray-800 px-3 py-1 rounded-full text-xs font-semibold">No</span>
                            @endif
                        </dd>
                    </div>

                    <div>
                        <dt class="text-sm font-medium text-gray-500">Show Author</dt>
                        <dd class="mt-1">
                            @if($carouselWidget->show_author ?? true)
                                <span class="bg-green-100 text-green-800 px-3 py-1 rounded-full text-xs font-semibold">Yes</span>
                            @else
                                <span class="bg-gray-100 text-gray-800 px-3 py-1 rounded-full text-xs font-semibold">No</span>
                            @endif
                        </dd>
                    </div>

                    <div>
                        <dt class="text-sm font-medium text-gray-500">Autoplay</dt>
                        <dd class="mt-1">
                            @if($carouselWidget->autoplay)
                                <span class="bg-green-100 text-green-800 px-3 py-1 rounded-full text-xs font-semibold">Yes</span>
                            @else
                                <span class="bg-gray-100 text-gray-800 px-3 py-1 rounded-full text-xs font-semibold">No</span>
                            @endif
                        </dd>
                    </div>

                    @if($carouselWidget->autoplay)
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Autoplay Speed</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ number_format($carouselWidget->autoplay_speed) }}ms</dd>
                        </div>
                    @endif

                    <div>
                        <dt class="text-sm font-medium text-gray-500">Infinite Loop</dt>
                        <dd class="mt-1">
                            @if($carouselWidget->infinite_loop)
                                <span class="bg-green-100 text-green-800 px-3 py-1 rounded-full text-xs font-semibold">Yes</span>
                            @else
                                <span class="bg-gray-100 text-gray-800 px-3 py-1 rounded-full text-xs font-semibold">No</span>
                            @endif
                        </dd>
                    </div>

                    <div>
                        <dt class="text-sm font-medium text-gray-500">Show View All Button</dt>
                        <dd class="mt-1">
                            @if($carouselWidget->show_view_all_button ?? false)
                                <span class="bg-green-100 text-green-800 px-3 py-1 rounded-full text-xs font-semibold">Yes</span>
                            @else
                                <span class="bg-gray-100 text-gray-800 px-3 py-1 rounded-full text-xs font-semibold">No</span>
                            @endif
                        </dd>
                    </div>
                </dl>

                @if($carouselWidget->show_view_all_button ?? false)
                    <div class="mt-6 pt-6 border-t border-gray-200">
                        <h4 class="text-sm font-semibold text-gray-900 mb-4">View All Button Settings</h4>
                        <dl class="space-y-3">
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Title</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ $carouselWidget->view_all_title ?? 'View All Articles' }}</dd>
                            </div>
                            @if($carouselWidget->view_all_description)
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Description</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ $carouselWidget->view_all_description }}</dd>
                                </div>
                            @endif
                        </dl>
                    </div>
                @endif
            </div>
        </div>

        {{-- Sidebar --}}
        <div class="space-y-6">
            {{-- Status --}}
            <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Status</h3>
                
                @if($carouselWidget->is_active)
                    <span class="bg-green-100 text-green-800 px-4 py-2 rounded-full text-sm font-semibold">
                        <i class="fa fa-check-circle mr-2"></i>Active
                    </span>
                @else
                    <span class="bg-gray-100 text-gray-800 px-4 py-2 rounded-full text-sm font-semibold">
                        <i class="fa fa-times-circle mr-2"></i>Inactive
                    </span>
                @endif
            </div>

            {{-- Metadata --}}
            <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Metadata</h3>
                
                <dl class="space-y-3 text-sm">
                    <div>
                        <dt class="text-gray-500">Created</dt>
                        <dd class="text-gray-900">{{ $carouselWidget->created_at->format('M d, Y H:i') }}</dd>
                    </div>
                    <div>
                        <dt class="text-gray-500">Last Updated</dt>
                        <dd class="text-gray-900">{{ $carouselWidget->updated_at->format('M d, Y H:i') }}</dd>
                    </div>
                    <div>
                        <dt class="text-gray-500">Sort Order</dt>
                        <dd class="text-gray-900">{{ $carouselWidget->sort_order }}</dd>
                    </div>
                </dl>
            </div>
        </div>
    </div>
</x-layouts.admin>

