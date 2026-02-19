<x-layouts.admin title="Create Hero Section">
<div class="space-y-6">
    {{-- Header --}}
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Create Hero Section</h1>
            <p class="text-gray-600">Create a new hero section for your homepage</p>
        </div>
        <a href="{{ route('admin.content.hero-section.index') }}"
           class="bg-gray-600 text-white px-4 py-2 rounded-lg hover:bg-gray-700 transition-colors duration-200">
            <i class="fa-solid fa-arrow-left mr-2"></i>
            Back to List
        </a>
    </div>

    {{-- Form --}}
    <form action="{{ route('admin.content.hero-section.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
        @csrf

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            {{-- Main Content --}}
            <div class="lg:col-span-2 space-y-6">
                {{-- Basic Information --}}
                <div class="bg-white rounded-xl shadow-sm border border-gray-200">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-900">Basic Information</h3>
                    </div>
                    <div class="p-6 space-y-4">


                        <div>
                            <label for="title" class="block text-sm font-medium text-gray-700 mb-1">
                                Title <span class="text-red-500">*</span>
                            </label>
                            <input type="text"
                                   id="title"
                                   name="title"
                                   value="{{ old('title') }}"
                                   class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-primary focus:border-primary @error('title') border-red-500 @enderror"
                                   required>
                            @error('title')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="subtitle" class="block text-sm font-medium text-gray-700 mb-1">
                                Subtitle
                            </label>
                            <textarea id="subtitle"
                                      name="subtitle"
                                      rows="3"
                                      class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-primary focus:border-primary @error('subtitle') border-red-500 @enderror">{{ old('subtitle') }}</textarea>
                            @error('subtitle')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="slogan" class="block text-sm font-medium text-gray-700 mb-1">
                                Slogan
                            </label>
                            <input type="text"
                                   id="slogan"
                                   name="slogan"
                                   value="{{ old('slogan') }}"
                                   placeholder="e.g., More than 15,000 satisfied customers trust OpenPublicatie"
                                   class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-primary focus:border-primary @error('slogan') border-red-500 @enderror">
                            @error('slogan')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">List Items</label>
                            
                            {{-- Show this when there are no items --}}
                            <div id="no-items-state" class="text-center py-8 border-2 border-dashed border-gray-300 rounded-lg">
                                <i class="fa-solid fa-list text-gray-400 text-3xl mb-3"></i>
                                <p class="text-gray-500 mb-4">No list items added yet</p>
                                <button type="button" onclick="showListItemsForm()" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition-colors duration-200 text-sm">
                                    <i class="fa-solid fa-plus mr-2"></i>
                                    Add First List Item
                                </button>
                            </div>
                            
                            {{-- Show this when there are items --}}
                            <div id="list-items-form" class="hidden">
                                <div id="list-items-container" class="space-y-2" style="list-style: none;">
                                </div>
                                <div class="mt-3 flex gap-2">
                                    <button type="button" onclick="addListItem()" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition-colors duration-200 text-sm">
                                        <i class="fa-solid fa-plus mr-2"></i>
                                        Add List Item
                                    </button>
                                    <button type="button" onclick="clearAllListItems()" class="bg-red-600 text-white px-4 py-2 rounded-lg hover:bg-red-700 transition-colors duration-200 text-sm">
                                        <i class="fa-solid fa-trash mr-2"></i>
                                        Clear All
                                    </button>
                                </div>
                            </div>
                            
                            @error('list_items')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                {{-- Top Header --}}
                <div class="bg-white rounded-xl shadow-sm border border-gray-200">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-900">Top Header</h3>
                    </div>
                    <div class="p-6 space-y-4">
                        {{-- Top Header Fields --}}
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <x-icon-picker
                                    id="top_header_icon"
                                    name="top_header_icon"
                                    :value="old('top_header_icon')"
                                    label="Top Header Icon"
                                    help-text="FontAwesome icon class (e.g., fa-solid fa-star)"
                                    :required="false"
                                />
                                @error('top_header_icon')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label for="top_header_text" class="block text-sm font-medium text-gray-700 mb-1">
                                    Top Header Text
                                </label>
                                <input type="text"
                                       id="top_header_text"
                                       name="top_header_text"
                                       value="{{ old('top_header_text') }}"
                                       placeholder="Featured Section"
                                       class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-primary focus:border-primary @error('top_header_text') border-red-500 @enderror">
                                @error('top_header_text')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        {{-- Top Header URL Selector --}}
                        <div x-data="topHeaderUrlSelector()" class="space-y-2">
                            <label class="block text-xs font-medium text-gray-600">URL Type</label>
                            <div class="grid grid-cols-3 gap-2">
                                <label class="flex items-center space-x-1 cursor-pointer">
                                    <input type="radio" name="top_header_link_type" value="predefined" 
                                           x-model="linkType"
                                           class="w-3 h-3 text-primary border-gray-300 focus:ring-primary">
                                    <span class="text-xs text-gray-700">Route</span>
                                </label>
                                <label class="flex items-center space-x-1 cursor-pointer">
                                    <input type="radio" name="top_header_link_type" value="system" 
                                           x-model="linkType"
                                           class="w-3 h-3 text-primary border-gray-300 focus:ring-primary">
                                    <span class="text-xs text-gray-700">Content</span>
                                </label>
                                <label class="flex items-center space-x-1 cursor-pointer">
                                    <input type="radio" name="top_header_link_type" value="custom" 
                                           x-model="linkType"
                                           class="w-3 h-3 text-primary border-gray-300 focus:ring-primary">
                                    <span class="text-xs text-gray-700">Custom</span>
                                </label>
                            </div>

                            <!-- Predefined Routes -->
                            <div x-show="linkType === 'predefined'" x-transition class="space-y-1">
                                <select x-model="selectedRoute" @change="updateUrl()"
                                        class="w-full border border-gray-300 rounded px-2 py-1 text-sm focus:ring-1 focus:ring-primary focus:border-primary">
                                    <option value="">Choose a page...</option>
                                    <option value="/">Home</option>
                                    <option value="/about">About</option>
                                    <option value="/contact">Contact</option>
                                    <option value="/pricing">Pricing</option>
                                    <option value="/artikelen">Blog</option>
                                </select>
                            </div>

                            <!-- System Content -->
                            <div x-show="linkType === 'system'" x-transition class="space-y-1">
                                <select x-model="selectedSystemContent" @change="updateUrl()"
                                        class="w-full border border-gray-300 rounded px-2 py-1 text-sm focus:ring-1 focus:ring-primary focus:border-primary">
                                    <option value="">Choose content...</option>
                                    @if(isset($systemContent))
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

                            <!-- Custom URL -->
                            <div x-show="linkType === 'custom'" x-transition class="space-y-1">
                                <input type="text" x-model="customUrl" @input="updateUrl()"
                                       placeholder="/custom-page or https://external-site.com"
                                       class="w-full border border-gray-300 rounded px-2 py-1 text-sm focus:ring-1 focus:ring-primary focus:border-primary">
                            </div>

                            <!-- Hidden URL Field -->
                            <input type="hidden" name="top_header_url" x-model="finalUrl">
                            
                            @error('top_header_url')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                {{-- Call-to-Action Buttons --}}
                <div class="bg-white rounded-xl shadow-sm border border-gray-200">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-900">Call-to-Action Buttons</h3>
                    </div>
                    <div class="p-6">
                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                        {{-- Primary Button --}}
                            <div class="border border-gray-200 rounded-lg p-3">
                                <h4 class="text-sm font-semibold text-gray-800 mb-3 flex items-center">
                                    <span class="w-6 h-6 bg-primary text-white rounded-full flex items-center justify-center text-xs font-bold mr-2">1</span>
                                    Primary Button
                                </h4>
                                <div class="space-y-3">
                            <div>
                                        <label for="primary_button_text" class="block text-xs font-medium text-gray-600 mb-1">Text</label>
                                <input type="text"
                                       id="primary_button_text"
                                       name="primary_button_text"
                                       value="{{ old('primary_button_text') }}"
                                       placeholder="Button text"
                                               class="w-full border border-gray-300 rounded px-2 py-1 text-sm focus:ring-1 focus:ring-primary focus:border-primary">
                                    </div>
                                    
                                    <!-- URL Selector -->
                                    <div x-data="primaryUrlSelector()" class="space-y-2">
                                        <label class="block text-xs font-medium text-gray-600">URL Type</label>
                                        <div class="grid grid-cols-3 gap-2">
                                            <label class="flex items-center space-x-1 cursor-pointer">
                                                <input type="radio" name="primary_link_type" value="predefined" 
                                                       x-model="linkType"
                                                       class="w-3 h-3 text-primary border-gray-300 focus:ring-primary">
                                                <span class="text-xs text-gray-700">Route</span>
                                            </label>
                                            <label class="flex items-center space-x-1 cursor-pointer">
                                                <input type="radio" name="primary_link_type" value="system" 
                                                       x-model="linkType"
                                                       class="w-3 h-3 text-primary border-gray-300 focus:ring-primary">
                                                <span class="text-xs text-gray-700">Content</span>
                                            </label>
                                            <label class="flex items-center space-x-1 cursor-pointer">
                                                <input type="radio" name="primary_link_type" value="custom" 
                                                       x-model="linkType"
                                                       class="w-3 h-3 text-primary border-gray-300 focus:ring-primary">
                                                <span class="text-xs text-gray-700">Custom</span>
                                            </label>
                                        </div>

                                        <!-- Predefined Routes -->
                                        <div x-show="linkType === 'predefined'" x-transition class="space-y-1">
                                            <select x-model="selectedRoute" @change="updateUrl()"
                                                    class="w-full border border-gray-300 rounded px-2 py-1 text-sm focus:ring-1 focus:ring-primary focus:border-primary">
                                                <option value="">Choose a page...</option>
                                                @if(isset($availableRoutes))
                                                    @foreach($availableRoutes as $name => $url)
                                                        <option value="{{ $url }}">{{ $name }}</option>
                                                    @endforeach
                                                @endif
                                            </select>
                            </div>

                                        <!-- System Content -->
                                        <div x-show="linkType === 'system'" x-transition class="space-y-1">
                                            <select x-model="selectedSystemContent" @change="updateUrl()"
                                                    class="w-full border border-gray-300 rounded px-2 py-1 text-sm focus:ring-1 focus:ring-primary focus:border-primary">
                                                <option value="">Choose content...</option>
                                                @if(isset($systemContent))
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

                                        <!-- Custom URL -->
                                        <div x-show="linkType === 'custom'" x-transition class="space-y-1">
                                            <input type="text" x-model="customUrl" @input="updateUrl()"
                                                   placeholder="/custom-page or https://external-site.com"
                                                   class="w-full border border-gray-300 rounded px-2 py-1 text-sm focus:ring-1 focus:ring-primary focus:border-primary">
                        </div>

                                        <!-- Hidden URL Field -->
                                        <input type="hidden" name="primary_button_url" x-model="finalUrl">
                                    </div>
                                </div>
                            </div>

                            {{-- Secondary Button --}}
                            <div class="border border-gray-200 rounded-lg p-3">
                                <h4 class="text-sm font-semibold text-gray-800 mb-3 flex items-center">
                                    <span class="w-6 h-6 bg-secondary text-white rounded-full flex items-center justify-center text-xs font-bold mr-2">2</span>
                                    Secondary Button
                                </h4>
                                <div class="space-y-3">
                            <div>
                                        <label for="secondary_button_text" class="block text-xs font-medium text-gray-600 mb-1">Text</label>
                                <input type="text"
                                       id="secondary_button_text"
                                       name="secondary_button_text"
                                       value="{{ old('secondary_button_text') }}"
                                       placeholder="Button text"
                                               class="w-full border border-gray-300 rounded px-2 py-1 text-sm focus:ring-1 focus:ring-primary focus:border-primary">
                                    </div>
                                    
                                    <!-- URL Selector -->
                                    <div x-data="secondaryUrlSelector()" class="space-y-2">
                                        <label class="block text-xs font-medium text-gray-600">URL Type</label>
                                        <div class="grid grid-cols-3 gap-2">
                                            <label class="flex items-center space-x-1 cursor-pointer">
                                                <input type="radio" name="secondary_link_type" value="predefined" 
                                                       x-model="linkType"
                                                       class="w-3 h-3 text-primary border-gray-300 focus:ring-primary">
                                                <span class="text-xs text-gray-700">Route</span>
                                            </label>
                                            <label class="flex items-center space-x-1 cursor-pointer">
                                                <input type="radio" name="secondary_link_type" value="system" 
                                                       x-model="linkType"
                                                       class="w-3 h-3 text-primary border-gray-300 focus:ring-primary">
                                                <span class="text-xs text-gray-700">Content</span>
                                            </label>
                                            <label class="flex items-center space-x-1 cursor-pointer">
                                                <input type="radio" name="secondary_link_type" value="custom" 
                                                       x-model="linkType"
                                                       class="w-3 h-3 text-primary border-gray-300 focus:ring-primary">
                                                <span class="text-xs text-gray-700">Custom</span>
                                            </label>
                                        </div>

                                        <!-- Predefined Routes -->
                                        <div x-show="linkType === 'predefined'" x-transition class="space-y-1">
                                            <select x-model="selectedRoute" @change="updateUrl()"
                                                    class="w-full border border-gray-300 rounded px-2 py-1 text-sm focus:ring-1 focus:ring-primary focus:border-primary">
                                                <option value="">Choose a page...</option>
                                                @if(isset($availableRoutes))
                                                    @foreach($availableRoutes as $name => $url)
                                                        <option value="{{ $url }}">{{ $name }}</option>
                                                    @endforeach
                                                @endif
                                            </select>
                                        </div>

                                        <!-- System Content -->
                                        <div x-show="linkType === 'system'" x-transition class="space-y-1">
                                            <select x-model="selectedSystemContent" @change="updateUrl()"
                                                    class="w-full border border-gray-300 rounded px-2 py-1 text-sm focus:ring-1 focus:ring-primary focus:border-primary">
                                                <option value="">Choose content...</option>
                                                @if(isset($systemContent))
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

                                        <!-- Custom URL -->
                                        <div x-show="linkType === 'custom'" x-transition class="space-y-1">
                                            <input type="text" x-model="customUrl" @input="updateUrl()"
                                                   placeholder="/custom-page or https://external-site.com"
                                                   class="w-full border border-gray-300 rounded px-2 py-1 text-sm focus:ring-1 focus:ring-primary focus:border-primary">
                                        </div>

                                        <!-- Hidden URL Field -->
                                        <input type="hidden" name="secondary_button_url" x-model="finalUrl">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Content Cards --}}
                <div class="bg-white rounded-xl shadow-sm border border-gray-200">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-900">Content Cards</h3>
                    </div>
                    <div class="p-6">
                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                            {{-- Card 1 --}}
                            <div class="border border-gray-200 rounded-lg p-3">
                                <h4 class="text-sm font-semibold text-gray-800 mb-3 flex items-center">
                                    <span class="w-6 h-6 bg-primary text-white rounded-full flex items-center justify-center text-xs font-bold mr-2">1</span>
                                    Card 1
                                </h4>
                                <div class="grid grid-cols-2 gap-3">
                                    <div>
                                        <x-icon-picker
                                            id="card1_icon"
                                            name="card1_icon"
                                            :value="old('card1_icon')"
                                            label="Icon"
                                            help-text=""
                                            :required="false"
                                        />
                                    </div>
                                    <div>
                                        <label for="card1_bgcolor" class="block text-xs font-medium text-gray-600 mb-1">Color</label>
                                        <select id="card1_bgcolor"
                                                name="card1_bgcolor"
                                                class="w-full border border-gray-300 rounded px-2 py-1 text-sm focus:ring-1 focus:ring-primary focus:border-primary">
                                            <option value="bg-primary" {{ old('card1_bgcolor') == 'bg-primary' ? 'selected' : '' }}>Primary</option>
                                            <option value="bg-secondary" {{ old('card1_bgcolor') == 'bg-secondary' ? 'selected' : '' }}>Secondary</option>
                                        </select>
                                    </div>
                                    <div>
                                        <label for="card1_title" class="block text-xs font-medium text-gray-600 mb-1">Title</label>
                                        <input type="text"
                                               id="card1_title"
                                               name="card1_title"
                                               value="{{ old('card1_title') }}"
                                               placeholder="Card title"
                                               class="w-full border border-gray-300 rounded px-2 py-1 text-sm focus:ring-1 focus:ring-primary focus:border-primary">
                                    </div>
                                    <div>
                                        <label for="card1_description" class="block text-xs font-medium text-gray-600 mb-1">Description</label>
                                        <input type="text"
                                               id="card1_description"
                                               name="card1_description"
                                               value="{{ old('card1_description') }}"
                                               placeholder="Short description"
                                               class="w-full border border-gray-300 rounded px-2 py-1 text-sm focus:ring-1 focus:ring-primary focus:border-primary">
                                    </div>
                                </div>
                            </div>

                            {{-- Card 2 --}}
                            <div class="border border-gray-200 rounded-lg p-3">
                                <h4 class="text-sm font-semibold text-gray-800 mb-3 flex items-center">
                                    <span class="w-6 h-6 bg-secondary text-white rounded-full flex items-center justify-center text-xs font-bold mr-2">2</span>
                                    Card 2
                                </h4>
                                <div class="grid grid-cols-2 gap-3">
                                    <div>
                                        <x-icon-picker
                                            id="card2_icon"
                                            name="card2_icon"
                                            :value="old('card2_icon')"
                                            label="Icon"
                                            help-text=""
                                            :required="false"
                                        />
                                    </div>
                                    <div>
                                        <label for="card2_bgcolor" class="block text-xs font-medium text-gray-600 mb-1">Color</label>
                                        <select id="card2_bgcolor"
                                                name="card2_bgcolor"
                                                class="w-full border border-gray-300 rounded px-2 py-1 text-sm focus:ring-1 focus:ring-primary focus:border-primary">
                                            <option value="bg-primary" {{ old('card2_bgcolor') == 'bg-primary' ? 'selected' : '' }}>Primary</option>
                                            <option value="bg-secondary" {{ old('card2_bgcolor') == 'bg-secondary' ? 'selected' : '' }}>Secondary</option>
                                        </select>
                                    </div>
                                    <div>
                                        <label for="card2_title" class="block text-xs font-medium text-gray-600 mb-1">Title</label>
                                        <input type="text"
                                               id="card2_title"
                                               name="card2_title"
                                               value="{{ old('card2_title') }}"
                                               placeholder="Card title"
                                               class="w-full border border-gray-300 rounded px-2 py-1 text-sm focus:ring-1 focus:ring-primary focus:border-primary">
                        </div>
                        <div>
                                        <label for="card2_description" class="block text-xs font-medium text-gray-600 mb-1">Description</label>
                            <input type="text"
                                               id="card2_description"
                                               name="card2_description"
                                               value="{{ old('card2_description') }}"
                                               placeholder="Short description"
                                               class="w-full border border-gray-300 rounded px-2 py-1 text-sm focus:ring-1 focus:ring-primary focus:border-primary">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Sidebar --}}
            <div class="space-y-6">
                {{-- Image Upload --}}
                <div class="bg-white rounded-xl shadow-sm border border-gray-200">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-900">Background Image</h3>
                    </div>
                    <div class="p-6">
                        <x-image-upload
                            id="image"
                            name="image"
                            label="Hero Image"
                            :required="false"
                            help-text="PNG, JPG, GIF, WEBP, SVG up to 2MB"
                            :max-size="2048"
                        />
                    </div>
                </div>

                {{-- Settings --}}
                <div class="bg-white rounded-xl shadow-sm border border-gray-200">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-900">Settings</h3>
                    </div>
                    <div class="p-6 space-y-4">
                        <div>
                            <label for="sort_order" class="block text-sm font-medium text-gray-700 mb-1">
                                Sort Order
                            </label>
                            <input type="number"
                                   id="sort_order"
                                   name="sort_order"
                                   value="{{ old('sort_order', 0) }}"
                                   min="0"
                                   class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-primary focus:border-primary">
                            <p class="text-xs text-gray-500 mt-1">Lower numbers appear first</p>
                        </div>
                        
                        <div>
                            <x-ui.toggle 
                                name="is_active"
                                :checked="old('is_active', false)"
                                label="Set as Active Hero Section"
                                description="Activate this hero section. You can have multiple active hero sections at the same time."
                            />
                        </div>
                    </div>
                </div>

                {{-- Actions --}}
                <div class="bg-white rounded-xl shadow-sm border border-gray-200">
                    <div class="p-6">
                        <div class="space-y-3">
                            <button type="submit"
                                    class="w-full bg-primary text-white px-4 py-2 rounded-lg hover:bg-primary/80 transition-colors duration-200">
                                <i class="fa-solid fa-save mr-2"></i>
                                Create Hero Section
                            </button>
                            <a href="{{ route('admin.content.hero-section.index') }}"
                               class="w-full bg-gray-500 text-white px-4 py-2 rounded-lg hover:bg-gray-600 transition-colors duration-200 text-center block">
                                <i class="fa-solid fa-times mr-2"></i>
                                Cancel
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

<script>
let listItemIndex = 1;

function showListItemsForm() {
    document.getElementById('no-items-state').classList.add('hidden');
    document.getElementById('list-items-form').classList.remove('hidden');
    
    // Add the first empty item
    addListItem();
}

function addListItem() {
    const container = document.getElementById('list-items-container');
    const listItemHtml = `
        <div class="flex gap-2" style="list-style: none;">
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
    reindexListItems();
    
    // Check if all items are deleted and handle accordingly
    const container = document.getElementById('list-items-container');
    const remainingItems = container.querySelectorAll('div.flex');
    
    if (remainingItems.length === 0) {
        // Hide the form and show the no-items state
        document.getElementById('list-items-form').classList.add('hidden');
        document.getElementById('no-items-state').classList.remove('hidden');
        
        // Add hidden input to indicate list should be cleared
        const hiddenInput = document.createElement('input');
        hiddenInput.type = 'hidden';
        hiddenInput.name = 'list_items_clear';
        hiddenInput.value = '1';
        hiddenInput.id = 'list_items_clear_flag';
        container.appendChild(hiddenInput);
        
        console.log('All items deleted, showing no-items state');
    }
}

function reindexListItems() {
    const container = document.getElementById('list-items-container');
    const items = container.querySelectorAll('div.flex');
    items.forEach((item, index) => {
        const input = item.querySelector('input[name^="list_items"]');
        if (input) {
            input.name = `list_items[${index}]`;
        }
    });
    
    // Update the listItemIndex to be the next available index
    listItemIndex = items.length;
}

function clearAllListItems() {
    const container = document.getElementById('list-items-container');
    container.innerHTML = '';
    
    // Add hidden input to indicate list should be cleared
    const hiddenInput = document.createElement('input');
    hiddenInput.type = 'hidden';
    hiddenInput.name = 'list_items_clear';
    hiddenInput.value = '1';
    hiddenInput.id = 'list_items_clear_flag';
    container.appendChild(hiddenInput);
    
    // Hide the form and show the no-items state
    document.getElementById('list-items-form').classList.add('hidden');
    document.getElementById('no-items-state').classList.remove('hidden');
    
    console.log('All list items cleared, showing no-items state');
}

// Clean up empty list items before form submission
function cleanupEmptyListItems() {
    const container = document.getElementById('list-items-container');
    const items = container.querySelectorAll('div.flex');
    
    // Remove empty items
    items.forEach((item) => {
        const input = item.querySelector('input[name^="list_items"]');
        if (input && input.value.trim() === '') {
            item.remove();
        }
    });
    
    // Reindex remaining items
    reindexListItems();
    
    // If no items remain, add a hidden input to clear the list_items
    const remainingItems = container.querySelectorAll('div.flex');
    if (remainingItems.length === 0) {
        // Remove any existing hidden input
        const existingHidden = container.querySelector('input[name="list_items_clear"]');
        if (existingHidden) {
            existingHidden.remove();
        }
        
        // Add hidden input to indicate list should be cleared
        const hiddenInput = document.createElement('input');
        hiddenInput.type = 'hidden';
        hiddenInput.name = 'list_items_clear';
        hiddenInput.value = '1';
        container.appendChild(hiddenInput);
    } else {
        // Remove the clear flag if there are items
        const existingHidden = container.querySelector('input[name="list_items_clear"]');
        if (existingHidden) {
            existingHidden.remove();
        }
    }
}

function topHeaderUrlSelector() {
    const currentUrl = '{{ old('top_header_url', '') }}';
    const systemContent = @json($systemContent ?? []);
    
    let initialLinkType = 'custom';
    let initialSelectedRoute = '';
    let initialSelectedSystemContent = '';
    let initialCustomUrl = currentUrl;
    
    // Check if current URL matches any predefined route
    if (currentUrl) {
        const predefinedRoutes = ['/', '/about', '/contact', '/pricing', '/artikelen'];
        if (predefinedRoutes.includes(currentUrl)) {
            initialLinkType = 'predefined';
            initialSelectedRoute = currentUrl;
            initialCustomUrl = '';
        } else {
            // Check if current URL matches any system content
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
        finalUrl: currentUrl,
        
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
}

// URL selector for primary button
function primaryUrlSelector() {
    const currentUrl = '{{ old('primary_button_url', '') }}';
    const availableRoutes = @json($availableRoutes ?? []);
    const systemContent = @json($systemContent ?? []);
    
    let initialLinkType = 'custom';
    let initialSelectedRoute = '';
    let initialSelectedSystemContent = '';
    let initialCustomUrl = currentUrl;
    
    // Check if current URL matches any predefined route
    if (currentUrl) {
        for (const [name, url] of Object.entries(availableRoutes)) {
            if (url === currentUrl) {
                initialLinkType = 'predefined';
                initialSelectedRoute = currentUrl;
                initialCustomUrl = '';
                break;
            }
        }
        
        // Check if current URL matches any system content
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
        finalUrl: currentUrl,
        
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
}

// URL selector for secondary button
function secondaryUrlSelector() {
    const currentUrl = '{{ old('secondary_button_url', '') }}';
    const availableRoutes = @json($availableRoutes ?? []);
    const systemContent = @json($systemContent ?? []);
    
    let initialLinkType = 'custom';
    let initialSelectedRoute = '';
    let initialSelectedSystemContent = '';
    let initialCustomUrl = currentUrl;
    
    // Check if current URL matches any predefined route
    if (currentUrl) {
        for (const [name, url] of Object.entries(availableRoutes)) {
            if (url === currentUrl) {
                initialLinkType = 'predefined';
                initialSelectedRoute = currentUrl;
                initialCustomUrl = '';
                break;
            }
        }
        
        // Check if current URL matches any system content
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
        finalUrl: currentUrl,
        
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
}

// Add event listener to clean up empty items before form submission
document.addEventListener('DOMContentLoaded', function() {
    const form = document.querySelector('form');
    if (form) {
        form.addEventListener('submit', function() {
            cleanupEmptyListItems();
        });
    }
});
</script>
</x-layouts.admin>
