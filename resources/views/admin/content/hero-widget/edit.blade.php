<x-layouts.admin title="Edit Hero Media Widget">
@php
    // Ensure widget exists
    if (!isset($heroWidget) || !$heroWidget) {
        abort(404, 'Hero Media Widget not found');
    }
@endphp
<div class="space-y-6">
    {{-- Header --}}
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Edit Hero Media Widget</h1>
            <p class="text-gray-600">Manage hero section with image/video background</p>
        </div>
        <a href="{{ route('admin.content.hero-widget.index') }}"
           class="bg-gray-600 text-white px-4 py-2 rounded-lg hover:bg-gray-700 transition-colors duration-200">
            <i class="fa-solid fa-arrow-left mr-2"></i>
            Back to List
        </a>
    </div>

    @if(session('success'))
        <div class="bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-lg">
            {{ session('success') }}
        </div>
    @endif

    @if($errors->any())
        <div class="bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-lg">
            <ul class="list-disc list-inside">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- Form --}}
    <form action="{{ route('admin.content.hero-widget.update', $heroWidget) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            {{-- Main Content --}}
            <div class="lg:col-span-2 space-y-6">
                {{-- Top Header Section --}}
                <div class="bg-white rounded-xl shadow-sm border border-gray-200">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-900">Top Header</h3>
                    </div>
                    <div class="p-6 space-y-4">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 items-start">
                            <div class="flex flex-col">
                                <x-icon-picker
                                    id="top_header_icon"
                                    name="top_header_icon"
                                    :value="old('top_header_icon', $heroWidget->top_header_icon)"
                                    label="Top Header Icon"
                                    help-text="FontAwesome icon class (e.g., fa-solid fa-star)"
                                    :required="false"
                                />
                            </div>
                            <div class="flex flex-col">
                                <label for="top_header_text" class="block text-sm font-medium text-gray-700 mb-1">
                                    Top Header Text
                                </label>
                                <input type="text"
                                       id="top_header_text"
                                       name="top_header_text"
                                       value="{{ old('top_header_text', $heroWidget->top_header_text) }}"
                                       placeholder="e.g., Featured Section"
                                       class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-primary focus:border-primary">
                            </div>
                        </div>
                        <div>
                            <label for="top_header_url" class="block text-sm font-medium text-gray-700 mb-1">
                                Header URL
                            </label>
                            <input type="text"
                                   id="top_header_url"
                                   name="top_header_url"
                                   value="{{ old('top_header_url', $heroWidget->top_header_url) }}"
                                   placeholder="/featured"
                                   class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-primary focus:border-primary">
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label for="top_header_text_color" class="block text-sm font-medium text-gray-700 mb-1">
                                    Text Color
                                </label>
                                <input type="color"
                                       id="top_header_text_color"
                                       name="top_header_text_color"
                                       value="{{ old('top_header_text_color', $heroWidget->top_header_text_color ?: '#ffffff') }}"
                                       class="w-full h-10 border border-gray-300 rounded-lg">
                            </div>
                            <div>
                                <label for="top_header_bg_color" class="block text-sm font-medium text-gray-700 mb-1">
                                    Background Color
                                </label>
                                <input type="color"
                                       id="top_header_bg_color"
                                       name="top_header_bg_color"
                                       value="{{ old('top_header_bg_color', $heroWidget->top_header_bg_color ?: '#ffffff1a') }}"
                                       class="w-full h-10 border border-gray-300 rounded-lg">
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Title & Subtitle Section --}}
                <div class="bg-white rounded-xl shadow-sm border border-gray-200">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-900">Title & Subtitle</h3>
                    </div>
                    <div class="p-6 space-y-4">
                        <div>
                            <label for="title" class="block text-sm font-medium text-gray-700 mb-1">
                                Title
                            </label>
                            <input type="text"
                                   id="title"
                                   name="title"
                                   value="{{ old('title', $heroWidget->title) }}"
                                   placeholder="Welcome to Our Amazing Platform"
                                   class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-primary focus:border-primary">
                        </div>
                        <div>
                            <label for="title_color" class="block text-sm font-medium text-gray-700 mb-1">
                                Title Color
                            </label>
                            <input type="color"
                                   id="title_color"
                                   name="title_color"
                                   value="{{ old('title_color', $heroWidget->title_color ?: '#ffffff') }}"
                                   class="w-full h-10 border border-gray-300 rounded-lg">
                        </div>
                        <div>
                            <label for="subtitle" class="block text-sm font-medium text-gray-700 mb-1">
                                Subtitle
                            </label>
                            <textarea id="subtitle"
                                      name="subtitle"
                                      rows="3"
                                      placeholder="Transform your business with our cutting-edge solutions..."
                                      class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-primary focus:border-primary">{{ old('subtitle', $heroWidget->subtitle) }}</textarea>
                        </div>
                        <div>
                            <label for="subtitle_color" class="block text-sm font-medium text-gray-700 mb-1">
                                Subtitle Color
                            </label>
                            <input type="color"
                                   id="subtitle_color"
                                   name="subtitle_color"
                                   value="{{ old('subtitle_color', $heroWidget->subtitle_color ?: '#d1d5db') }}"
                                   class="w-full h-10 border border-gray-300 rounded-lg">
                        </div>
                    </div>
                </div>

                {{-- Slogan Section --}}
                <div class="bg-white rounded-xl shadow-sm border border-gray-200">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-900">Slogan</h3>
                    </div>
                    <div class="p-6 space-y-4">
                        <div>
                            <label for="slogan" class="block text-sm font-medium text-gray-700 mb-1">
                                Slogan Text
                            </label>
                            <input type="text"
                                   id="slogan"
                                   name="slogan"
                                   value="{{ old('slogan', $heroWidget->slogan) }}"
                                   placeholder="More than 15,000 satisfied customers trust us"
                                   class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-primary focus:border-primary">
                        </div>
                        <div>
                            <label for="slogan_color" class="block text-sm font-medium text-gray-700 mb-1">
                                Slogan Color
                            </label>
                            <input type="color"
                                   id="slogan_color"
                                   name="slogan_color"
                                   value="{{ old('slogan_color', $heroWidget->slogan_color ?: '#3b82f6') }}"
                                   class="w-full h-10 border border-gray-300 rounded-lg">
                        </div>
                    </div>
                </div>

                {{-- List Items Section --}}
                <div class="bg-white rounded-xl shadow-sm border border-gray-200">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-900">List Items</h3>
                    </div>
                    <div class="p-6 space-y-4">
                        <div id="no-items-state" class="text-center py-8 border-2 border-dashed border-gray-300 rounded-lg {{ ($heroWidget->list_items && count($heroWidget->list_items) > 0) ? 'hidden' : '' }}">
                            <i class="fa-solid fa-list text-gray-400 text-3xl mb-3"></i>
                            <p class="text-gray-500 mb-4">No list items added yet</p>
                            <button type="button" onclick="showListItemsForm()" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition-colors duration-200 text-sm">
                                <i class="fa-solid fa-plus mr-2"></i>
                                Add First List Item
                            </button>
                        </div>

                        <div id="list-items-form" class="{{ ($heroWidget->list_items && count($heroWidget->list_items) > 0) ? '' : 'hidden' }}">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                                <div>
                                    <label for="list_item_icon" class="block text-sm font-medium text-gray-700 mb-1">
                                        List Item Icon
                                    </label>
                                    <x-icon-picker
                                        id="list_item_icon"
                                        name="list_item_icon"
                                        :value="old('list_item_icon', $heroWidget->list_item_icon)"
                                        label=""
                                        help-text=""
                                        :required="false"
                                    />
                                </div>
                                <div>
                                    <label for="list_item_color" class="block text-sm font-medium text-gray-700 mb-1">
                                        List Item Color
                                    </label>
                                    <input type="color"
                                           id="list_item_color"
                                           name="list_item_color"
                                           value="{{ old('list_item_color', $heroWidget->list_item_color ?: '#3b82f6') }}"
                                           class="w-full h-10 border border-gray-300 rounded-lg">
                                </div>
                            </div>
                            <div id="list-items-container" class="space-y-2">
                                @if($heroWidget->list_items && count($heroWidget->list_items) > 0)
                                    @foreach($heroWidget->list_items as $index => $item)
                                        <div class="flex gap-2">
                                            <input type="text"
                                                   name="list_items[{{ $index }}]"
                                                   value="{{ $item }}"
                                                   placeholder="Enter list item"
                                                   class="flex-1 border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-primary focus:border-primary">
                                            <button type="button" onclick="removeListItem(this)" class="text-red-600 hover:text-red-800 px-2">
                                                <i class="fa-solid fa-trash"></i>
                                            </button>
                                        </div>
                                    @endforeach
                                @endif
                            </div>
                            <button type="button" onclick="addListItem()" class="mt-4 bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition-colors duration-200 text-sm">
                                <i class="fa-solid fa-plus mr-2"></i>
                                Add List Item
                            </button>
                        </div>
                    </div>
                </div>

                {{-- Primary Button Section --}}
                <div class="bg-white rounded-xl shadow-sm border border-gray-200">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-900">Primary Button</h3>
                    </div>
                    <div class="p-6 space-y-4">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label for="primary_button_text" class="block text-sm font-medium text-gray-700 mb-1">
                                    Button Text
                                </label>
                                <input type="text"
                                       id="primary_button_text"
                                       name="primary_button_text"
                                       value="{{ old('primary_button_text', $heroWidget->primary_button_text) }}"
                                       placeholder="Get Started"
                                       class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-primary focus:border-primary">
                            </div>
                            <div>
                                <x-icon-picker
                                    id="primary_button_icon"
                                    name="primary_button_icon"
                                    :value="old('primary_button_icon', $heroWidget->primary_button_icon)"
                                    label="Button Icon"
                                    help-text=""
                                    :required="false"
                                />
                            </div>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Button URL</label>
                            <div x-data="heroWidgetUrlSelector(@js($heroWidget->primary_button_url ?? ''), @js($heroWidget->primary_button_url ? 'custom' : 'custom'), @js($availableRoutes ?? []), @js($systemContent ?? []), 'primary_button')" class="space-y-2">
                                <div class="grid grid-cols-3 gap-2 mb-3">
                                    <label class="flex items-center space-x-1 cursor-pointer text-xs">
                                        <input type="radio" x-model="linkType" value="predefined" @change="updateUrl()" class="w-3 h-3 text-primary border-gray-300 focus:ring-primary">
                                        <span class="text-gray-700">Predefined</span>
                                    </label>
                                    <label class="flex items-center space-x-1 cursor-pointer text-xs">
                                        <input type="radio" x-model="linkType" value="system" @change="updateUrl()" class="w-3 h-3 text-primary border-gray-300 focus:ring-primary">
                                        <span class="text-gray-700">System</span>
                                    </label>
                                    <label class="flex items-center space-x-1 cursor-pointer text-xs">
                                        <input type="radio" x-model="linkType" value="custom" @change="updateUrl()" class="w-3 h-3 text-primary border-gray-300 focus:ring-primary">
                                        <span class="text-gray-700">Custom</span>
                                    </label>
                                </div>
                                <div x-show="linkType === 'predefined'" x-transition class="space-y-1">
                                    <select x-model="selectedRoute" @change="updateUrl()" class="block bg-white w-full px-3 py-2 border border-gray-200 rounded-md text-gray-900 text-sm focus:outline-none">
                                        <option value="">Choose a page...</option>
                                        @if(isset($availableRoutes) && is_array($availableRoutes) && count($availableRoutes) > 0)
                                            @foreach($availableRoutes as $name => $url)
                                                <option value="{{ $url }}">{{ $name }}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>
                                <div x-show="linkType === 'system'" x-transition class="space-y-1">
                                    <select x-model="selectedSystemContent" @change="updateUrl()" class="block bg-white w-full px-3 py-2 border border-gray-200 rounded-md text-gray-900 text-sm focus:outline-none">
                                        <option value="">Choose content...</option>
                                        @if(isset($systemContent) && is_array($systemContent) && count($systemContent) > 0)
                                            @foreach($systemContent as $category => $items)
                                                <optgroup label="{{ $category }}">
                                                    @foreach($items as $item)
                                                        <option value="{{ $item['url'] }}">{{ $item['title'] }}</option>
                                                    @endforeach
                                                </optgroup>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>
                                <div x-show="linkType === 'custom'" x-transition class="space-y-1">
                                    <input type="text" x-model="customUrl" @input="updateUrl()" placeholder="/custom-page or https://external-site.com" class="block bg-white w-full px-3 py-2 border border-gray-200 rounded-md text-gray-900 text-sm focus:outline-none">
                                </div>
                                <input type="hidden" name="primary_button_url" x-model="finalUrl" x-ref="urlInput">
                                <div x-show="finalUrl" class="bg-gray-50 border border-gray-200 rounded p-2">
                                    <p class="text-xs text-gray-600">
                                        <strong>URL:</strong> <span x-text="getDisplayUrl()" class="font-mono text-primary"></span>
                                    </p>
                                </div>
                            </div>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label for="primary_button_text_color" class="block text-sm font-medium text-gray-700 mb-1">
                                    Text Color
                                </label>
                                <input type="color"
                                       id="primary_button_text_color"
                                       name="primary_button_text_color"
                                       value="{{ old('primary_button_text_color', $heroWidget->primary_button_text_color ?: '#ffffff') }}"
                                       class="w-full h-10 border border-gray-300 rounded-lg">
                            </div>
                            <div>
                                <label for="primary_button_bg_color" class="block text-sm font-medium text-gray-700 mb-1">
                                    Background Color
                                </label>
                                <input type="color"
                                       id="primary_button_bg_color"
                                       name="primary_button_bg_color"
                                       value="{{ old('primary_button_bg_color', $heroWidget->primary_button_bg_color ?: '#3b82f6') }}"
                                       class="w-full h-10 border border-gray-300 rounded-lg">
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Secondary Button Section --}}
                <div class="bg-white rounded-xl shadow-sm border border-gray-200">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-900">Secondary Button</h3>
                    </div>
                    <div class="p-6 space-y-4">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label for="secondary_button_text" class="block text-sm font-medium text-gray-700 mb-1">
                                    Button Text
                                </label>
                                <input type="text"
                                       id="secondary_button_text"
                                       name="secondary_button_text"
                                       value="{{ old('secondary_button_text', $heroWidget->secondary_button_text) }}"
                                       placeholder="Learn More"
                                       class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-primary focus:border-primary">
                            </div>
                            <div>
                                <x-icon-picker
                                    id="secondary_button_icon"
                                    name="secondary_button_icon"
                                    :value="old('secondary_button_icon', $heroWidget->secondary_button_icon)"
                                    label="Button Icon"
                                    help-text=""
                                    :required="false"
                                />
                            </div>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Button URL</label>
                            <div x-data="heroWidgetUrlSelector(@js($heroWidget->secondary_button_url ?? ''), @js($heroWidget->secondary_button_url ? 'custom' : 'custom'), @js($availableRoutes ?? []), @js($systemContent ?? []), 'secondary_button')" class="space-y-2">
                                <div class="grid grid-cols-3 gap-2 mb-3">
                                    <label class="flex items-center space-x-1 cursor-pointer text-xs">
                                        <input type="radio" x-model="linkType" value="predefined" @change="updateUrl()" class="w-3 h-3 text-primary border-gray-300 focus:ring-primary">
                                        <span class="text-gray-700">Predefined</span>
                                    </label>
                                    <label class="flex items-center space-x-1 cursor-pointer text-xs">
                                        <input type="radio" x-model="linkType" value="system" @change="updateUrl()" class="w-3 h-3 text-primary border-gray-300 focus:ring-primary">
                                        <span class="text-gray-700">System</span>
                                    </label>
                                    <label class="flex items-center space-x-1 cursor-pointer text-xs">
                                        <input type="radio" x-model="linkType" value="custom" @change="updateUrl()" class="w-3 h-3 text-primary border-gray-300 focus:ring-primary">
                                        <span class="text-gray-700">Custom</span>
                                    </label>
                                </div>
                                <div x-show="linkType === 'predefined'" x-transition class="space-y-1">
                                    <select x-model="selectedRoute" @change="updateUrl()" class="block bg-white w-full px-3 py-2 border border-gray-200 rounded-md text-gray-900 text-sm focus:outline-none">
                                        <option value="">Choose a page...</option>
                                        @if(isset($availableRoutes) && is_array($availableRoutes) && count($availableRoutes) > 0)
                                            @foreach($availableRoutes as $name => $url)
                                                <option value="{{ $url }}">{{ $name }}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>
                                <div x-show="linkType === 'system'" x-transition class="space-y-1">
                                    <select x-model="selectedSystemContent" @change="updateUrl()" class="block bg-white w-full px-3 py-2 border border-gray-200 rounded-md text-gray-900 text-sm focus:outline-none">
                                        <option value="">Choose content...</option>
                                        @if(isset($systemContent) && is_array($systemContent) && count($systemContent) > 0)
                                            @foreach($systemContent as $category => $items)
                                                <optgroup label="{{ $category }}">
                                                    @foreach($items as $item)
                                                        <option value="{{ $item['url'] }}">{{ $item['title'] }}</option>
                                                    @endforeach
                                                </optgroup>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>
                                <div x-show="linkType === 'custom'" x-transition class="space-y-1">
                                    <input type="text" x-model="customUrl" @input="updateUrl()" placeholder="/custom-page or https://external-site.com" class="block bg-white w-full px-3 py-2 border border-gray-200 rounded-md text-gray-900 text-sm focus:outline-none">
                                </div>
                                <input type="hidden" name="secondary_button_url" x-model="finalUrl" x-ref="urlInput">
                                <div x-show="finalUrl" class="bg-gray-50 border border-gray-200 rounded p-2">
                                    <p class="text-xs text-gray-600">
                                        <strong>URL:</strong> <span x-text="getDisplayUrl()" class="font-mono text-primary"></span>
                                    </p>
                                </div>
                            </div>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div>
                                <label for="secondary_button_text_color" class="block text-sm font-medium text-gray-700 mb-1">
                                    Text Color
                                </label>
                                <input type="color"
                                       id="secondary_button_text_color"
                                       name="secondary_button_text_color"
                                       value="{{ old('secondary_button_text_color', $heroWidget->secondary_button_text_color ?: '#ffffff') }}"
                                       class="w-full h-10 border border-gray-300 rounded-lg">
                            </div>
                            <div>
                                <label for="secondary_button_bg_color" class="block text-sm font-medium text-gray-700 mb-1">
                                    Background Color
                                </label>
                                <input type="color"
                                       id="secondary_button_bg_color"
                                       name="secondary_button_bg_color"
                                       value="{{ old('secondary_button_bg_color', $heroWidget->secondary_button_bg_color ?: 'transparent') }}"
                                       class="w-full h-10 border border-gray-300 rounded-lg">
                            </div>
                            <div>
                                <label for="secondary_button_border_color" class="block text-sm font-medium text-gray-700 mb-1">
                                    Border Color
                                </label>
                                <input type="color"
                                       id="secondary_button_border_color"
                                       name="secondary_button_border_color"
                                       value="{{ old('secondary_button_border_color', $heroWidget->secondary_button_border_color ?: '#ffffff') }}"
                                       class="w-full h-10 border border-gray-300 rounded-lg">
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Background Media Section --}}
                <div class="bg-white rounded-xl shadow-sm border border-gray-200">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-900">Background Media</h3>
                    </div>
                    <div class="p-6 space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Background Type</label>
                            <div class="grid grid-cols-2 gap-4">
                                <label class="flex items-center space-x-2 cursor-pointer p-4 border-2 rounded-lg {{ old('background_type', $heroWidget->background_type) === 'image' ? 'border-primary bg-primary/5' : 'border-gray-200' }}">
                                    <input type="radio" name="background_type" value="image" {{ old('background_type', $heroWidget->background_type) === 'image' ? 'checked' : '' }} class="text-primary focus:ring-primary">
                                    <span class="text-sm font-medium">Image</span>
                                </label>
                                <label class="flex items-center space-x-2 cursor-pointer p-4 border-2 rounded-lg {{ old('background_type', $heroWidget->background_type) === 'video' ? 'border-primary bg-primary/5' : 'border-gray-200' }}">
                                    <input type="radio" name="background_type" value="video" {{ old('background_type', $heroWidget->background_type) === 'video' ? 'checked' : '' }} class="text-primary focus:ring-primary">
                                    <span class="text-sm font-medium">Video</span>
                                </label>
                            </div>
                        </div>

                        <div id="image-section" class="{{ old('background_type', $heroWidget->background_type) === 'image' ? '' : 'hidden' }}">
                            <x-image-upload
                                id="image"
                                name="image"
                                label="Background Image"
                                :current-image="$heroWidget->image ? get_image($heroWidget->image) : null"
                                current-image-alt="Hero background image"
                                help-text="JPEG, PNG, JPG, GIF, WebP up to 5MB"
                                accept="image/jpeg,image/png,image/jpg,image/gif,image/webp"
                                :max-size="5120"
                                size="large"
                            />
                        </div>

                        <div id="video-section" class="{{ old('background_type', $heroWidget->background_type) === 'video' ? '' : 'hidden' }}">
                            <label for="video_url" class="block text-sm font-medium text-gray-700 mb-1">
                                Video URL
                            </label>
                            <input type="url"
                                   id="video_url"
                                   name="video_url"
                                   value="{{ old('video_url', $heroWidget->video_url) }}"
                                   placeholder="https://www.example.com/video.mp4"
                                   class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-primary focus:border-primary">
                            <p class="text-xs text-gray-500 mt-1">Enter a direct link to a video file (MP4, WebM, etc.)</p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Sidebar --}}
            <div class="space-y-6">
                {{-- Component Settings --}}
                <div class="bg-white rounded-xl shadow-sm border border-gray-200">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-900">Component Settings</h3>
                    </div>
                    <div class="p-6 space-y-4">
                        <div>
                            <label for="component_type" class="block text-sm font-medium text-gray-700 mb-1">
                                Component Type
                            </label>
                            <input type="text"
                                   id="component_type"
                                   name="component_type"
                                   value="{{ old('component_type', $heroWidget->component_type) }}"
                                   placeholder="fullscreen"
                                   class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-primary focus:border-primary">
                        </div>
                        <div>
                            <label class="flex items-center space-x-2 cursor-pointer">
                                <input type="checkbox"
                                       name="full_height"
                                       value="1"
                                       {{ old('full_height', $heroWidget->full_height) ? 'checked' : '' }}
                                       class="w-4 h-4 text-primary border-gray-300 rounded focus:ring-primary">
                                <span class="text-sm font-medium text-gray-700">Full Height (100vh)</span>
                            </label>
                        </div>
                        <div id="height-input" class="{{ old('full_height', $heroWidget->full_height) ? 'hidden' : '' }}">
                            <label for="height" class="block text-sm font-medium text-gray-700 mb-1">
                                Height (px)
                            </label>
                            <input type="number"
                                   id="height"
                                   name="height"
                                   value="{{ old('height', $heroWidget->height) }}"
                                   placeholder="1000"
                                   min="0"
                                   class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-primary focus:border-primary">
                        </div>
                        <div>
                            <x-ui.toggle
                                name="is_active"
                                id="is_active"
                                label="Active"
                                description="Enable or disable this hero media widget"
                                :checked="old('is_active', $heroWidget->is_active)"
                            />
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Form Actions --}}
        <div class="flex items-center justify-end space-x-4 pt-6 border-t border-gray-200">
            <button type="submit"
                    class="px-6 py-2 bg-primary text-white rounded-lg hover:bg-primary-dark transition-colors duration-200">
                <i class="fa-solid fa-save mr-2"></i>
                Update Hero Widget
            </button>
        </div>
    </form>
</div>


    <script>
    // List Items Management
    let listItemIndex = {{ count($heroWidget->list_items ?? []) }};

    function showListItemsForm() {
        document.getElementById('no-items-state').classList.add('hidden');
        document.getElementById('list-items-form').classList.remove('hidden');
        if (document.getElementById('list-items-container').children.length === 0) {
            addListItem();
        }
    }

    function addListItem() {
        const container = document.getElementById('list-items-container');
        const listItemHtml = `
            <div class="flex gap-2">
                <input type="text"
                       name="list_items[${listItemIndex}]"
                       placeholder="Enter list item"
                       class="flex-1 border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-primary focus:border-primary">
                <button type="button" onclick="removeListItem(this)" class="text-red-600 hover:text-red-800 px-2">
                    <i class="fa-solid fa-trash"></i>
                </button>
            </div>
        `;
        container.insertAdjacentHTML('beforeend', listItemHtml);
        listItemIndex++;
    }

    function removeListItem(button) {
        button.parentElement.remove();
        const container = document.getElementById('list-items-container');
        if (container.children.length === 0) {
            document.getElementById('no-items-state').classList.remove('hidden');
            document.getElementById('list-items-form').classList.add('hidden');
        }
    }

    // Background Type Toggle
    document.querySelectorAll('input[name="background_type"]').forEach(radio => {
        radio.addEventListener('change', function() {
            if (this.value === 'image') {
                document.getElementById('image-section').classList.remove('hidden');
                document.getElementById('video-section').classList.add('hidden');
            } else {
                document.getElementById('image-section').classList.add('hidden');
                document.getElementById('video-section').classList.remove('hidden');
            }
        });
    });

    // Full Height Toggle
    document.querySelector('input[name="full_height"]').addEventListener('change', function() {
        const heightInput = document.getElementById('height-input');
        if (this.checked) {
            heightInput.classList.add('hidden');
        } else {
            heightInput.classList.remove('hidden');
        }
    });

    // URL Selector for Hero Widget
    window.heroWidgetUrlSelector = function(currentUrl = '', currentLinkType = 'custom', availableRoutes = {}, systemContent = {}, fieldName = '') {
        let initialLinkType = currentLinkType || 'custom';
        let initialSelectedRoute = '';
        let initialSelectedSystemContent = '';
        let initialCustomUrl = currentUrl || '';

        if (currentUrl) {
            for (const [name, url] of Object.entries(availableRoutes)) {
                if (url === currentUrl) {
                    initialLinkType = 'predefined';
                    initialSelectedRoute = url;
                    initialCustomUrl = '';
                    break;
                }
            }

            if (initialLinkType === 'custom') {
                for (const [category, items] of Object.entries(systemContent)) {
                    for (const item of items) {
                        if (item.url === currentUrl) {
                            initialLinkType = 'system';
                            initialSelectedSystemContent = item.url;
                            initialCustomUrl = '';
                            break;
                        }
                    }
                    if (initialLinkType === 'system') break;
                }
            }
        }

        return {
            linkType: initialLinkType,
            selectedRoute: initialSelectedRoute,
            selectedSystemContent: initialSelectedSystemContent,
            customUrl: initialCustomUrl,
            finalUrl: currentUrl || '',
            fieldName: fieldName,

            init() {
                this.updateUrl();
            },

            updateUrl() {
                if (this.linkType === 'predefined') {
                    this.finalUrl = this.selectedRoute;
                    this.customUrl = '';
                    this.selectedSystemContent = '';
                } else if (this.linkType === 'system') {
                    this.finalUrl = this.selectedSystemContent;
                    this.customUrl = '';
                    this.selectedRoute = '';
                } else {
                    this.finalUrl = this.customUrl;
                    this.selectedRoute = '';
                    this.selectedSystemContent = '';
                }
            },

            getDisplayUrl() {
                if (!this.finalUrl) return '';
                if (this.finalUrl.startsWith('http://') || this.finalUrl.startsWith('https://')) {
                    return this.finalUrl;
                }
                if (this.finalUrl.startsWith('/')) {
                    return window.location.origin + this.finalUrl;
                }
                return this.finalUrl;
            }
        };
    };
</script>
    </script>
</x-layouts.admin>

