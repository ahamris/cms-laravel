<x-layouts.admin title="View Call Action">
<div class="space-y-6">
    {{-- Header --}}
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">{{ $callAction->title }}</h1>
            <p class="text-gray-600">Call Action Details</p>
        </div>
        <div class="flex items-center space-x-3">
            <a href="{{ route('admin.content.call-action.edit', $callAction) }}"
               class="bg-primary text-white px-4 py-2 rounded-lg hover:bg-primary/80 transition-colors duration-200">
                <i class="fa-solid fa-edit mr-2"></i>
                Edit
            </a>
            <a href="{{ route('admin.content.call-action.index') }}"
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
            <div class="rounded-lg p-8 text-center" style="background-color: {{ $callAction->background_color }}; color: {{ $callAction->text_color }};">
                <div class="max-w-4xl mx-auto">
                    {{-- Title --}}
                    <h2 class="text-3xl lg:text-4xl font-bold mb-6">{{ $callAction->title }}</h2>
                    
                    {{-- Content --}}
                    @if($callAction->content)
                        <p class="text-lg mb-8 opacity-90">{{ $callAction->content }}</p>
                    @endif


                    {{-- Buttons --}}
                    @if($callAction->hasPrimaryButton() || $callAction->hasSecondaryButton())
                        <div class="flex flex-col sm:flex-row gap-4 justify-center mb-8">
                            @if($callAction->hasPrimaryButton())
                                <a href="{{ $callAction->primary_button_url }}" 
                                   target="{{ $callAction->primary_button_target }}"
                                   class="inline-block bg-white text-gray-900 px-6 py-3 rounded-lg font-semibold hover:bg-gray-100 transition-colors duration-200">
                                    {{ $callAction->primary_button_text }}
                                </a>
                            @endif
                            @if($callAction->hasSecondaryButton())
                                <a href="{{ $callAction->secondary_button_url }}" 
                                   target="{{ $callAction->secondary_button_target }}"
                                   class="inline-block border-2 border-white px-6 py-3 rounded-lg font-semibold hover:bg-white hover:text-gray-900 transition-colors duration-200">
                                    {{ $callAction->secondary_button_text }}
                                </a>
                            @endif
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
                    <label class="block text-sm font-medium text-gray-700 mb-1">Title</label>
                    <p class="text-gray-900">{{ $callAction->title }}</p>
                </div>
                
                @if($callAction->content)
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Content</label>
                        <p class="text-gray-900">{{ $callAction->content }}</p>
                    </div>
                @endif

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Section Identifier</label>
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                        {{ $callAction->section_identifier }}
                    </span>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $callAction->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                        <i class="fa-solid {{ $callAction->is_active ? 'fa-check-circle' : 'fa-pause-circle' }} mr-1"></i>
                        {{ $callAction->is_active ? 'Active' : 'Inactive' }}
                    </span>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Sort Order</label>
                    <p class="text-gray-900">{{ $callAction->sort_order }}</p>
                </div>
            </div>
        </div>

        {{-- Design Settings --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-200">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900">Design Settings</h3>
            </div>
            <div class="p-6 space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Background Color</label>
                    <div class="flex items-center space-x-2">
                        <div class="w-6 h-6 rounded border border-gray-300" style="background-color: {{ $callAction->background_color }}"></div>
                        <span class="text-gray-900 font-mono">{{ $callAction->background_color }}</span>
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Text Color</label>
                    <div class="flex items-center space-x-2">
                        <div class="w-6 h-6 rounded border border-gray-300" style="background-color: {{ $callAction->text_color }}"></div>
                        <span class="text-gray-900 font-mono">{{ $callAction->text_color }}</span>
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Created</label>
                    <p class="text-gray-900">{{ $callAction->created_at->format('M j, Y \a\t g:i A') }}</p>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Last Updated</label>
                    <p class="text-gray-900">{{ $callAction->updated_at->format('M j, Y \a\t g:i A') }}</p>
                </div>
            </div>
        </div>
    </div>

    {{-- Buttons Information --}}
    @if($callAction->hasPrimaryButton() || $callAction->hasSecondaryButton())
        <div class="bg-white rounded-xl shadow-sm border border-gray-200">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900">Button Configuration</h3>
            </div>
            <div class="p-6">
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    {{-- Primary Button --}}
                    @if($callAction->hasPrimaryButton())
                        <div>
                            <h4 class="font-medium text-gray-900 mb-3">Primary Button</h4>
                            <div class="space-y-2 text-sm">
                                <div><span class="font-medium">Text:</span> {{ $callAction->primary_button_text }}</div>
                                <div><span class="font-medium">URL:</span> {{ $callAction->primary_button_url }}</div>
                                <div><span class="font-medium">Target:</span> {{ $callAction->primary_button_external ? 'New Tab' : 'Same Tab' }}</div>
                            </div>
                        </div>
                    @endif

                    {{-- Secondary Button --}}
                    @if($callAction->hasSecondaryButton())
                        <div>
                            <h4 class="font-medium text-gray-900 mb-3">Secondary Button</h4>
                            <div class="space-y-2 text-sm">
                                <div><span class="font-medium">Text:</span> {{ $callAction->secondary_button_text }}</div>
                                <div><span class="font-medium">URL:</span> {{ $callAction->secondary_button_url }}</div>
                                <div><span class="font-medium">Target:</span> {{ $callAction->secondary_button_external ? 'New Tab' : 'Same Tab' }}</div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    @endif

</div>
</x-layouts.admin>
