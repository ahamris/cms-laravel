<x-layouts.admin title="Page Builder">
<div class="space-y-6">
    {{-- Header --}}
    <div class="flex items-center justify-between">
        <div>
            <h2>Page Builder</h2>
            <p>Select a page to manage its sections and content</p>
        </div>
        {{-- Quick Stats --}}
        <div class="flex items-center space-x-6 p-2 border border-gray-200 rounded-md bg-gray-50">
            <span><span class="font-semibold text-gray-900">{{ $totalPages }}</span> Pages</span>
            <span><span class="font-semibold text-gray-900">{{ $totalSections }}</span> Sections</span>
            <span><span class="font-semibold text-green-600">{{ $activeSections }}</span> Active</span>
            <span><span class="font-semibold text-gray-400">{{ $inactiveSections }}</span> Inactive</span>
        </div>
    </div>

    {{-- Success Message --}}
    @if(session('success'))
        <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg">
            {{ session('success') }}
        </div>
    @endif




    {{-- Available Pages Grid --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        @foreach($availablePages as $pageKey => $pageData)
            <a href="{{ route('admin.page-builder.manage', ['pageType' => $pageKey]) }}" 
               class="group block bg-white rounded-md shadow-md border border-gray-200 hover:shadow-none transition-all duration-200">
                <div class="p-6">
                    <div class="flex items-center space-x-4 mb-4">
                        <div class="flex-shrink-0">
                            <div class="w-12 h-12 bg-gradient-to-br from-secondary to-black rounded-md flex items-center justify-center">
                                <i class="fa-solid fa-{{ $pageData['icon'] }} text-white text-xl"></i>
                            </div>
                        </div>
                        <div class="flex-1 min-w-0">
                            <h3 class="text-lg font-semibold text-primary">
                                {{ $pageData['name'] }}
                            </h3>
                            <p class="text-sm text-gray-500">
                                {{ $pageData['sections_count'] }} {{ Str::plural('section', $pageData['sections_count']) }}
                            </p>
                        </div>
                    </div>
                    
                    <p class="text-sm text-gray-800 mb-4">
                        {{ $pageData['description'] }}
                    </p>
                    
                    <div class="flex items-center justify-between pt-4">
                        <span class="text-xs text-gray-500">
                            <i class="fa-solid fa-clock mr-2"></i>
                            Last updated: {{ $pageData['last_updated'] }}
                        </span>
                        <span class="inline-flex items-center text-primary text-sm font-medium">
                            Manage
                            <i class="fa-solid fa-arrow-right ml-2"></i>
                        </span>
                    </div>
                </div>
            </a>
        @endforeach
    </div>
</div>
</x-layouts.admin>
