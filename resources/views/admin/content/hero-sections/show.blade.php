<x-layouts.admin title="Hero Section Details">
<div class="space-y-6">
    {{-- Header --}}
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">{{ $heroSection->title }}</h1>
            <p class="text-gray-600">Hero section details and preview</p>
        </div>
        <div class="flex items-center space-x-3">
            <a href="{{ route('admin.content.hero-section.edit', $heroSection) }}"
               class="bg-primary text-white px-4 py-2 rounded-lg hover:bg-primary/80 transition-colors duration-200">
                <i class="fa-solid fa-edit mr-2"></i>
                Edit
            </a>
            <a href="{{ route('admin.content.hero-section.index') }}"
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
                            @if($heroSection->top_header_icon || $heroSection->top_header_text)
                                <div>
                                    <strong class="text-gray-700">Top Header:</strong>
                                    <div class="flex items-center text-primary mt-1">
                                        @if($heroSection->top_header_url)
                                            <a href="{{ $heroSection->top_header_url }}" class="flex items-center hover:text-primary/80 transition-colors">
                                                @if($heroSection->top_header_icon)
                                                    <i class="{{ $heroSection->top_header_icon }} mr-2"></i>
                                                @endif
                                                @if($heroSection->top_header_text)
                                                    <span>{{ $heroSection->top_header_text }}</span>
                                                @endif
                                            </a>
                                        @else
                                            @if($heroSection->top_header_icon)
                                                <i class="{{ $heroSection->top_header_icon }} mr-2"></i>
                                            @endif
                                            @if($heroSection->top_header_text)
                                                <span>{{ $heroSection->top_header_text }}</span>
                                            @endif
                                        @endif
                                    </div>
                                    @if($heroSection->top_header_url)
                                        <p class="text-xs text-gray-500 mt-1">
                                            <i class="fa-solid fa-link mr-1"></i>
                                            {{ $heroSection->top_header_url }}
                                        </p>
                                    @endif
                                </div>
                            @endif
                            <div>
                                <strong class="text-gray-700">Title:</strong>
                                <p class="text-gray-900 mt-1">{{ $heroSection->title }}</p>
                            </div>
                            @if($heroSection->subtitle)
                                <div>
                                    <strong class="text-gray-700">Subtitle:</strong>
                                    <p class="text-gray-900 mt-1">{{ $heroSection->subtitle }}</p>
                                </div>
                            @endif
                        </div>
                    </div>

                    {{-- List Items --}}
                    @if($heroSection->list_items && count($heroSection->list_items) > 0)
                        <div>
                            <h4 class="font-semibold text-gray-900 mb-3">List Items</h4>
                            <ul class="space-y-2">
                                @foreach($heroSection->list_items as $item)
                                    <li class="flex items-start space-x-2">
                                        <i class="fa-solid fa-check-circle text-green-600 mt-1"></i>
                                        <span class="text-sm text-gray-700">{{ $item }}</span>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                {{-- Call-to-Action Buttons --}}
                <div>
                    <h4 class="font-semibold text-gray-900 mb-3">Call-to-Action Buttons</h4>
                    <div class="space-y-4">
                        {{-- Primary Button --}}
                        @if($heroSection->primary_button_text || $heroSection->primary_button_url)
                            <div class="border border-gray-200 rounded-lg p-4">
                                <h5 class="font-semibold text-gray-900 mb-2">Primary Button</h5>
                                @if($heroSection->primary_button_text)
                                    <p class="text-lg font-medium text-gray-800 mb-1">{{ $heroSection->primary_button_text }}</p>
                                @endif
                                @if($heroSection->primary_button_url)
                                    <p class="text-sm text-gray-600">URL: <span class="font-mono bg-gray-100 px-2 py-1 rounded">{{ $heroSection->primary_button_url }}</span></p>
                                @endif
                            </div>
                        @endif

                        {{-- Secondary Button --}}
                        @if($heroSection->secondary_button_text || $heroSection->secondary_button_url)
                            <div class="border border-gray-200 rounded-lg p-4">
                                <h5 class="font-semibold text-gray-900 mb-2">Secondary Button</h5>
                                @if($heroSection->secondary_button_text)
                                    <p class="text-lg font-medium text-gray-800 mb-1">{{ $heroSection->secondary_button_text }}</p>
                                @endif
                                @if($heroSection->secondary_button_url)
                                    <p class="text-sm text-gray-600">URL: <span class="font-mono bg-gray-100 px-2 py-1 rounded">{{ $heroSection->secondary_button_url }}</span></p>
                                @endif
                            </div>
                        @endif

                        @if(!$heroSection->primary_button_text && !$heroSection->primary_button_url && !$heroSection->secondary_button_text && !$heroSection->secondary_button_url)
                            <p class="text-sm text-gray-500">No call-to-action buttons configured</p>
                        @endif
                    </div>
                </div>

                {{-- Content Cards --}}
                <div>
                    <h4 class="font-semibold text-gray-900 mb-3">Content Cards</h4>
                    <div class="space-y-4">
                        {{-- Card 1 --}}
                        @if($heroSection->card1_title || $heroSection->card1_description)
                            <div class="border border-gray-200 rounded-lg p-4">
                                <div class="flex items-start space-x-3">
                                    @if($heroSection->card1_icon)
                                        <div class="flex-shrink-0">
                                            <div class="w-10 h-10 {{ $heroSection->card1_bgcolor ?? 'bg-primary' }} text-white rounded-lg flex items-center justify-center">
                                                <i class="{{ $heroSection->card1_icon }} text-sm"></i>
                                            </div>
                                        </div>
                                    @endif
                                    <div class="flex-1">
                                        <h5 class="font-semibold text-gray-900 mb-1">Card 1</h5>
                                        @if($heroSection->card1_title)
                                            <p class="text-lg font-medium text-gray-800">{{ $heroSection->card1_title }}</p>
                                        @endif
                                        @if($heroSection->card1_description)
                                            <p class="text-sm text-gray-600 mt-1">{{ $heroSection->card1_description }}</p>
                                        @endif
                                        <div class="mt-2 text-xs text-gray-500">
                                            <span class="inline-block bg-gray-100 px-2 py-1 rounded">Background: {{ $heroSection->card1_bgcolor ?? 'bg-primary' }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif

                        {{-- Card 2 --}}
                        @if($heroSection->card2_title || $heroSection->card2_description)
                            <div class="border border-gray-200 rounded-lg p-4">
                                <div class="flex items-start space-x-3">
                                    @if($heroSection->card2_icon)
                                        <div class="flex-shrink-0">
                                            <div class="w-10 h-10 {{ $heroSection->card2_bgcolor ?? 'bg-secondary' }} text-white rounded-lg flex items-center justify-center">
                                                <i class="{{ $heroSection->card2_icon }} text-sm"></i>
                                            </div>
                                        </div>
                                    @endif
                                    <div class="flex-1">
                                        <h5 class="font-semibold text-gray-900 mb-1">Card 2</h5>
                                        @if($heroSection->card2_title)
                                            <p class="text-lg font-medium text-gray-800">{{ $heroSection->card2_title }}</p>
                                        @endif
                                        @if($heroSection->card2_description)
                                            <p class="text-sm text-gray-600 mt-1">{{ $heroSection->card2_description }}</p>
                                        @endif
                                        <div class="mt-2 text-xs text-gray-500">
                                            <span class="inline-block bg-gray-100 px-2 py-1 rounded">Background: {{ $heroSection->card2_bgcolor ?? 'bg-secondary' }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif

                        @if(!$heroSection->card1_title && !$heroSection->card1_description && !$heroSection->card2_title && !$heroSection->card2_description)
                            <p class="text-sm text-gray-500">No content cards configured</p>
                        @endif
                    </div>
                </div>

                    {{-- Image --}}
                    <div>
                        <h4 class="font-semibold text-gray-900 mb-3">Background Image</h4>
                        <div class="rounded-lg overflow-hidden border border-gray-200">
                            <img src="{{ get_image($heroSection->image) }}" alt="{{ $heroSection->title }}" class="w-full h-auto">
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Sidebar --}}
        <div class="space-y-6">
            {{-- Quick Actions --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-200">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                        <i class="fa-solid fa-bolt mr-2 text-yellow-500"></i>
                        Quick Actions
                    </h3>
                </div>
                <div class="p-6">
                    <div class="space-y-3">
                        <a href="{{ route('admin.content.hero-section.edit', $heroSection) }}"
                           class="w-full flex items-center justify-center px-4 py-2 bg-blue-100 text-blue-700 rounded-lg hover:bg-blue-200 transition-colors duration-200">
                            <i class="fa-solid fa-edit mr-2"></i>
                            Edit Section
                        </a>

                        <button onclick="deleteHeroSection({{ $heroSection->id }})"
                                class="w-full flex items-center justify-center px-4 py-2 bg-red-100 text-red-700 rounded-lg hover:bg-red-200 transition-colors duration-200">
                            <i class="fa-solid fa-trash mr-2"></i>
                            Delete Section
                        </button>
                    </div>
                </div>
            </div>

            {{-- Status Information --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-200">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                        <i class="fa-solid fa-toggle-on mr-2 text-blue-500"></i>
                        Status
                    </h3>
                </div>
                <div class="p-6">
                    <div class="space-y-3">
                        <div>
                            <strong class="text-gray-700">Current Status:</strong>
                            <div class="mt-2">
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium {{ $heroSection->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                    <i class="fa-solid {{ $heroSection->is_active ? 'fa-check-circle' : 'fa-times-circle' }} mr-1"></i>
                                    {{ $heroSection->is_active ? 'Active' : 'Inactive' }}
                                </span>
                            </div>
                        </div>
                        @if($heroSection->is_active)
                            <p class="text-xs text-gray-500 bg-blue-50 border border-blue-200 rounded p-2">
                                <i class="fa-solid fa-info-circle mr-1"></i>
                                This is the currently active hero section displayed on the website.
                            </p>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Meta Information --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-200">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                        <i class="fa-solid fa-calendar mr-2 text-purple-500"></i>
                        Meta Information
                    </h3>
                </div>
                <div class="p-6">
                    <div class="space-y-3 text-sm">
                        <div>
                            <strong class="text-gray-700">Created:</strong>
                            <p class="text-gray-900 mt-1">{{ $heroSection->created_at->format('M j, Y \a\t g:i A') }}</p>
                        </div>
                        <div>
                            <strong class="text-gray-700">Last Updated:</strong>
                            <p class="text-gray-900 mt-1">{{ $heroSection->updated_at->format('M j, Y \a\t g:i A') }}</p>
                        </div>
                        <div>
                            <strong class="text-gray-700">ID:</strong>
                            <p class="text-gray-900 mt-1">#{{ $heroSection->id }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Delete Confirmation Modal --}}
<div id="deleteModal" class="fixed inset-0 bg-black/50 hidden z-50 flex items-center justify-center">
    <div class="bg-white rounded-xl shadow-xl max-w-md w-full mx-4">
        <div class="p-6">
            <div class="flex items-center mb-4">
                <i class="fa-solid fa-exclamation-triangle text-red-500 text-2xl mr-3"></i>
                <h3 class="text-lg font-semibold text-gray-900">Delete Hero Section</h3>
            </div>
            <p class="text-gray-600 mb-6">
                Are you sure you want to delete this hero section? This action cannot be undone.
            </p>
            <div class="flex items-center justify-end space-x-4">
                <button type="button"
                        onclick="closeDeleteModal()"
                        class="bg-gray-500 text-white px-4 py-2 rounded-lg hover:bg-gray-600 transition-colors duration-200">
                    Cancel
                </button>
                <form id="deleteForm" method="POST" class="inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit"
                            class="bg-red-600 text-white px-4 py-2 rounded-lg hover:bg-red-700 transition-colors duration-200">
                        Delete
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
function deleteHeroSection(heroSectionId) {
    document.getElementById('deleteForm').action = `/admin/content/hero-section/${heroSectionId}`;
    document.getElementById('deleteModal').classList.remove('hidden');
}

function closeDeleteModal() {
    document.getElementById('deleteModal').classList.add('hidden');
}

// Close modal when clicking outside
document.getElementById('deleteModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeDeleteModal();
    }
});
</script>
</x-layouts.admin>
