<x-layouts.admin title="View Content Type">
<div class="space-y-6">
    {{-- Header --}}
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">{{ $contentType->name }}</h1>
            <p class="text-gray-600">Content Type Details</p>
        </div>
        <div class="flex items-center space-x-3">
            <a href="{{ route('admin.marketing.content-type.edit', $contentType) }}"
               class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition-colors duration-200">
                <i class="fa-solid fa-edit mr-2"></i>
                Edit Content Type
            </a>
            <a href="{{ route('admin.marketing.content-type.index') }}"
               class="bg-gray-600 text-white px-4 py-2 rounded-lg hover:bg-gray-700 transition-colors duration-200">
                <i class="fa-solid fa-arrow-left mr-2"></i>
                Back to List
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        {{-- Main Content --}}
        <div class="lg:col-span-2 space-y-6">
            {{-- Basic Information --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-200">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900">Basic Information</h3>
                </div>
                <div class="p-6 space-y-4">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Name</label>
                            <p class="text-gray-900">{{ $contentType->name }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Slug</label>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                {{ $contentType->slug }}
                            </span>
                        </div>
                    </div>
                    
                    @if($contentType->description)
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                            <p class="text-gray-900">{{ $contentType->description }}</p>
                        </div>
                    @endif
                </div>
            </div>

            {{-- Visual Design --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-200">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900">Visual Design</h3>
                </div>
                <div class="p-6 space-y-4">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Icon</label>
                            @if($contentType->icon)
                                <div class="flex items-center">
                                    <i class="fa-solid {{ $contentType->icon }} text-lg mr-2" style="color: {{ $contentType->color ?? '#6366f1' }};"></i>
                                    <code class="text-sm bg-gray-100 px-2 py-1 rounded">{{ $contentType->icon }}</code>
                                </div>
                            @else
                                <p class="text-gray-500 italic">No icon set</p>
                            @endif
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Theme Color</label>
                            @if($contentType->color)
                                <div class="flex items-center">
                                    <div class="w-6 h-6 rounded border border-gray-300 mr-2" style="background-color: {{ $contentType->color }};"></div>
                                    <code class="text-sm bg-gray-100 px-2 py-1 rounded">{{ $contentType->color }}</code>
                                </div>
                            @else
                                <p class="text-gray-500 italic">No color set</p>
                            @endif
                        </div>
                    </div>

                    {{-- Preview --}}
                    <div class="border border-gray-200 rounded-lg p-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Preview</label>
                        <div class="flex items-center">
                            @if($contentType->icon)
                                <div class="h-12 w-12 rounded-lg flex items-center justify-center mr-3" style="background-color: {{ $contentType->color ?? '#6366f1' }}20;">
                                    <i class="fa-solid {{ $contentType->icon }} text-lg" style="color: {{ $contentType->color ?? '#6366f1' }};"></i>
                                </div>
                            @else
                                <div class="h-12 w-12 rounded-lg bg-gray-100 flex items-center justify-center mr-3">
                                    <i class="fa-solid fa-tag text-gray-400 text-lg"></i>
                                </div>
                            @endif
                            <div>
                                <div class="text-sm font-medium text-gray-900">{{ $contentType->name }}</div>
                                <div class="text-sm text-gray-500">How it appears in the admin</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Applicable Models --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-200">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900">Applicable Models</h3>
                </div>
                <div class="p-6">
                    @if($contentType->applicable_models && count($contentType->applicable_models) > 0)
                        <div class="flex flex-wrap gap-2">
                            @foreach($contentType->applicable_models as $model)
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800">
                                    @switch($model)
                                        @case('App\Models\Blog')
                                            <i class="fa-solid fa-newspaper mr-1"></i> Blog Articles
                                            @break
                                        @case('App\Models\Page')
                                            <i class="fa-solid fa-file mr-1"></i> Pages
                                            @break
                                        @case('App\Models\Service')
                                            <i class="fa-solid fa-briefcase mr-1"></i> Services
                                            @break
                                        @case('App\Models\CaseStudy')
                                            <i class="fa-solid fa-chart-line mr-1"></i> Case Studies
                                            @break
                                        @case('App\Models\HelpArticle')
                                            <i class="fa-solid fa-question-circle mr-1"></i> Help Articles
                                            @break
                                        @default
                                            <i class="fa-solid fa-file mr-1"></i> {{ class_basename($model) }}
                                    @endswitch
                                </span>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-8">
                            <i class="fa-solid fa-globe text-3xl text-gray-400 mb-2"></i>
                            <p class="text-gray-600">Available for all content models</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        {{-- Sidebar --}}
        <div class="lg:col-span-1 space-y-6">
            {{-- Visual Preview --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-200">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900">Visual Preview</h3>
                </div>
                <div class="p-6 text-center">
                    @if($contentType->icon)
                        <div class="h-20 w-20 rounded-xl flex items-center justify-center mx-auto mb-4" style="background-color: {{ $contentType->color ?? '#6366f1' }}20;">
                            <i class="fa-solid {{ $contentType->icon }} text-3xl" style="color: {{ $contentType->color ?? '#6366f1' }};"></i>
                        </div>
                    @else
                        <div class="h-20 w-20 rounded-xl bg-gray-100 flex items-center justify-center mx-auto mb-4">
                            <i class="fa-solid fa-tag text-gray-400 text-3xl"></i>
                        </div>
                    @endif
                    <h4 class="font-medium text-gray-900">{{ $contentType->name }}</h4>
                    @if($contentType->description)
                        <p class="text-sm text-gray-500 mt-1">{{ Str::limit($contentType->description, 60) }}</p>
                    @endif
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
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $contentType->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                            <i class="fa-solid fa-{{ $contentType->is_active ? 'check' : 'times' }} mr-1"></i>
                            {{ $contentType->is_active ? 'Active' : 'Inactive' }}
                        </span>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Sort Order</label>
                        <p class="text-gray-900">{{ $contentType->sort_order }}</p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Created</label>
                        <p class="text-gray-900 text-sm">{{ $contentType->created_at->format('M j, Y \a\t g:i A') }}</p>
                    </div>

                    @if($contentType->updated_at != $contentType->created_at)
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Last Updated</label>
                            <p class="text-gray-900 text-sm">{{ $contentType->updated_at->format('M j, Y \a\t g:i A') }}</p>
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
                        <label class="block text-sm font-medium text-gray-700 mb-1">Blog Articles</label>
                        <p class="text-gray-900">{{ $contentType->blogs()->count() }} articles</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Pages</label>
                        <p class="text-gray-900">{{ $contentType->pages()->count() }} pages</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Services</label>
                        <p class="text-gray-900">{{ $contentType->services()->count() }} services</p>
                    </div>
                    <div class="pt-2 border-t border-gray-200">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Total Usage</label>
                        <p class="text-lg font-semibold text-primary">
                            {{ $contentType->blogs()->count() + $contentType->pages()->count() + $contentType->services()->count() }}
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</x-layouts.admin>
