<x-layouts.admin title="View Feature Block">
<div class="space-y-6">
    {{-- Header --}}
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">{{ $blockFeature->title }}</h1>
            <p class="text-gray-600">Feature Block Details</p>
        </div>
        <div class="flex items-center space-x-3">
            <a href="{{ route('admin.content.block-feature.edit', $blockFeature) }}"
               class="bg-primary text-white px-4 py-2 rounded-lg hover:bg-primary/80 transition-colors duration-200">
                <i class="fa-solid fa-edit mr-2"></i>
                Edit
            </a>
            <a href="{{ route('admin.content.block-feature.index') }}"
               class="bg-gray-600 text-white px-4 py-2 rounded-lg hover:bg-gray-700 transition-colors duration-200">
                <i class="fa-solid fa-arrow-left mr-2"></i>
                Back to List
            </a>
        </div>
    </div>

    {{-- Preview Section --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-200">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-semibold text-gray-900">Preview</h3>
        </div>
        <div class="p-6">
            <div class="rounded-lg border border-gray-200 p-8">
                <div class="max-w-4xl mx-auto">
                    {{-- Image --}}
                    @if($blockFeature->image)
                        <div class="mb-6 flex justify-center">
                            <img src="{{ $blockFeature->getImageUrl() }}" 
                                 alt="{{ $blockFeature->title }}"
                                 class="max-w-md w-full h-auto rounded-lg shadow-md">
                        </div>
                    @endif

                    {{-- Title --}}
                    <h2 class="text-3xl lg:text-4xl font-bold mb-4 text-center text-gray-900">{{ $blockFeature->title }}</h2>
                    
                    {{-- Subtitle --}}
                    @if($blockFeature->subtitle)
                        <p class="text-xl text-center text-gray-600 mb-6">{{ $blockFeature->subtitle }}</p>
                    @endif

                    {{-- Content --}}
                    @if($blockFeature->content)
                        <div class="text-lg text-center text-gray-700 mb-8">
                            {!! nl2br(e($blockFeature->content)) !!}
                        </div>
                    @endif

                    {{-- Button --}}
                    @if($blockFeature->button_text && $blockFeature->button_url)
                        <div class="flex justify-center">
                            <a href="{{ $blockFeature->button_url }}" 
                               class="inline-block bg-primary text-white px-6 py-3 rounded-lg font-semibold hover:bg-primary/80 transition-colors duration-200">
                                {{ $blockFeature->button_text }}
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        {{-- Basic Information --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-200">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900">Basic Information</h3>
            </div>
            <div class="p-6 space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Identifier</label>
                    <div class="flex items-center space-x-2">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                            {{ $blockFeature->identifier }}
                        </span>
                        <button onclick="copyToClipboard('{{ $blockFeature->identifier }}')" 
                                class="text-gray-500 hover:text-gray-700 transition-colors duration-200"
                                title="Copy identifier">
                            <i class="fa-solid fa-copy"></i>
                        </button>
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Title</label>
                    <p class="text-gray-900">{{ $blockFeature->title }}</p>
                </div>
                
                @if($blockFeature->subtitle)
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Subtitle</label>
                        <p class="text-gray-900">{{ $blockFeature->subtitle }}</p>
                    </div>
                @endif

                @if($blockFeature->content)
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Content</label>
                        <p class="text-gray-900">{!! nl2br(e($blockFeature->content)) !!}</p>
                    </div>
                @endif

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $blockFeature->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                        <i class="fa-solid {{ $blockFeature->is_active ? 'fa-check-circle' : 'fa-pause-circle' }} mr-1"></i>
                        {{ $blockFeature->is_active ? 'Active' : 'Inactive' }}
                    </span>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Sort Order</label>
                    <p class="text-gray-900">{{ $blockFeature->sort_order }}</p>
                </div>
            </div>
        </div>

        {{-- Additional Information --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-200">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900">Additional Information</h3>
            </div>
            <div class="p-6 space-y-4">
                @if($blockFeature->image)
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Image</label>
                        <img src="{{ $blockFeature->getImageUrl() }}" 
                             alt="{{ $blockFeature->title }}"
                             class="w-32 h-auto rounded-lg border border-gray-200">
                    </div>
                @endif

                @if($blockFeature->button_text)
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Button Text</label>
                        <p class="text-gray-900">{{ $blockFeature->button_text }}</p>
                    </div>
                @endif

                @if($blockFeature->button_url)
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Button URL</label>
                        <p class="text-gray-900">{{ $blockFeature->button_url }}</p>
                    </div>
                @endif

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Created</label>
                    <p class="text-gray-900">{{ $blockFeature->created_at->format('M j, Y \a\t g:i A') }}</p>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Last Updated</label>
                    <p class="text-gray-900">{{ $blockFeature->updated_at->format('M j, Y \a\t g:i A') }}</p>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function copyToClipboard(text) {
    navigator.clipboard.writeText(text).then(function() {
        // Show success message
        alert('Identifier copied to clipboard!');
    }, function(err) {
        console.error('Could not copy text: ', err);
    });
}
</script>
</x-layouts.admin>
