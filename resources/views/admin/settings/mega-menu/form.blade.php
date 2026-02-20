<div class="bg-white rounded-lg shadow p-6 space-y-6" x-data="megaMenuForm()">
    <!-- Basic Settings -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <!-- Parent Menu (Only for child items) -->
        @if(isset($parent) && $parent)
            <div class="col-span-2 bg-blue-50 border border-blue-200 rounded-lg p-3">
                <p class="text-sm text-blue-900">
                    <i class="fas fa-info-circle mr-1"></i>
                    This will be a <strong>child item</strong> under <strong>{{ $parent->title }}</strong>
                </p>
            </div>
            <input type="hidden" name="parent_id" value="{{ $parent->id }}">
        @else
            <div>
                <label for="parent_id" class="block text-sm font-medium text-gray-700 mb-2">Parent Menu</label>
                <select name="parent_id" id="parent_id" 
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary">
                    <option value="">Root Level Menu Item</option>
                    @if(isset($rootItems))
                        @foreach($rootItems as $item)
                            @if($item->is_mega_menu)
                                <option value="{{ $item->id }}" {{ old('parent_id', $megaMenu->parent_id ?? '') == $item->id ? 'selected' : '' }}>
                                    {{ $item->title }} (Mega Menu)
                                </option>
                            @endif
                        @endforeach
                    @endif
                </select>
                @error('parent_id')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
                <p class="text-xs text-gray-500 mt-1">Leave empty for root level, or select a mega menu parent</p>
            </div>
        @endif

        <!-- Order -->
        <div>
            <label for="order" class="block text-sm font-medium text-gray-700 mb-2">Order <span class="text-red-500">*</span></label>
            <input type="number" name="order" id="order" value="{{ old('order', $megaMenu->order ?? 0) }}" min="0" required
                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary">
            @error('order')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror
            <p class="text-xs text-gray-500 mt-1">Lower numbers appear first</p>
        </div>
    </div>

    <!-- Mega Menu Checkbox (Only for root level items) -->
    @if(!isset($parent) || !$parent)
        <div class="border-t border-gray-200 pt-4 space-y-3" x-show="!document.getElementById('parent_id').value" x-transition>
            <h3 class="text-lg font-semibold text-gray-900 mb-3">Menu Type</h3>
            
            <label class="flex items-center space-x-2 cursor-pointer">
                <input type="checkbox" name="is_mega_menu" value="1" 
                       x-model="isMegaMenu"
                       {{ old('is_mega_menu', $megaMenu->is_mega_menu ?? false) ? 'checked' : '' }}
                       class="w-4 h-4 text-primary border-gray-300 rounded focus:ring-primary">
                <span class="text-sm font-medium text-gray-700">📋 Enable Mega Menu (with sub-items)</span>
            </label>
            <p class="text-xs text-gray-500 ml-6">Check this to allow adding child menu items under this root item</p>
            
            <div x-show="!isMegaMenu" class="text-xs text-gray-600 ml-6 mt-2">
                <i class="fas fa-arrow-right mr-1"></i> Simple menu item with custom link
            </div>
        </div>
    @endif

    <!-- Item Details -->
    <div class="border-t border-gray-200 pt-4 space-y-4">
        <h3 class="text-lg font-semibold text-gray-900">Item Details</h3>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <!-- Title -->
            <div>
                <label for="title" class="block text-sm font-medium text-gray-700 mb-2">Title <span class="text-red-500">*</span></label>
                <input type="text" name="title" id="title" value="{{ old('title', $megaMenu->title ?? '') }}" required
                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary">
                @error('title')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Subtitle -->
            <div>
                <label for="subtitle" class="block text-sm font-medium text-gray-700 mb-2">Subtitle</label>
                <input type="text" name="subtitle" id="subtitle" value="{{ old('subtitle', $megaMenu->subtitle ?? '') }}"
                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary">
            </div>
        </div>

        <!-- Description -->
        <div>
            <label for="description" class="block text-sm font-medium text-gray-700 mb-2">Description</label>
            <textarea name="description" id="description" rows="2"
                      class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary">{{ old('description', $megaMenu->description ?? '') }}</textarea>
        </div>

        <!-- Icon Settings -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <x-icon-picker
                    id="icon"
                    name="icon"
                    :value="old('icon', $megaMenu->icon ?? '')"
                    label="Icon Class (Font Awesome)"
                    help-text="Example: fas fa-rocket, fas fa-chart-line"
                    :required="false"
                />
            </div>

            <div>
                <label for="icon_bg_color" class="block text-sm font-medium text-gray-700 mb-2">Icon Background Color</label>
                <input type="color" name="icon_bg_color" id="icon_bg_color" value="{{ old('icon_bg_color', $megaMenu->icon_bg_color ?? '#3B82F6') }}"
                       class="w-full h-10 border border-gray-300 rounded-lg">
            </div>
        </div>
    </div>

    <!-- Link Configuration -->
    <div class="border-t border-gray-200 pt-4 space-y-4">
        <h3 class="text-lg font-semibold text-gray-900">Link Configuration</h3>
        
        <!-- Link Type Selection -->
        <div class="space-y-3">
            <label class="block text-sm font-medium text-gray-700">Link Type</label>
            <div class="grid grid-cols-1 md:grid-cols-4 gap-3">
                <label class="flex items-center space-x-2 cursor-pointer">
                    <input type="radio" name="link_type" value="page" 
                           x-model="linkType"
                           class="w-4 h-4 text-primary border-gray-300 focus:ring-primary">
                    <span class="text-sm text-gray-700">Page Reference</span>
                </label>
                <label class="flex items-center space-x-2 cursor-pointer">
                    <input type="radio" name="link_type" value="predefined" 
                           x-model="linkType"
                           class="w-4 h-4 text-primary border-gray-300 focus:ring-primary">
                    <span class="text-sm text-gray-700">Predefined Route</span>
                </label>
                <label class="flex items-center space-x-2 cursor-pointer">
                    <input type="radio" name="link_type" value="system" 
                           x-model="linkType"
                           class="w-4 h-4 text-primary border-gray-300 focus:ring-primary">
                    <span class="text-sm text-gray-700">System Content</span>
                </label>
                <label class="flex items-center space-x-2 cursor-pointer">
                    <input type="radio" name="link_type" value="custom" 
                           x-model="linkType"
                           class="w-4 h-4 text-primary border-gray-300 focus:ring-primary">
                    <span class="text-sm text-gray-700">Custom URL</span>
                </label>
            </div>
        </div>

        <!-- Page Reference Dropdown -->
        <div x-show="linkType === 'page'" x-transition class="space-y-2">
            <label for="page_id" class="block text-sm font-medium text-gray-700">Select Page</label>
            <select name="page_id" id="page_id" x-model="selectedPageId" @change="updateUrl()"
                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary">
                <option value="">Choose a page...</option>
                @php
                    $pages = \App\Models\Page::where('is_active', true)
                        ->select('id', 'title', 'slug')
                        ->orderBy('title')
                        ->get();
                @endphp
                @foreach($pages as $page)
                    <option value="{{ $page->id }}" 
                            {{ old('page_id', $megaMenu->page_id ?? '') == $page->id ? 'selected' : '' }}
                            data-url="{{ $page->link_url }}">
                        {{ $page->title }} ({{ $page->slug }})
                    </option>
                @endforeach
            </select>
            <p class="text-xs text-gray-500">Select a page to link to. The menu item will automatically use the page's URL.</p>
            @error('page_id')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>

        <!-- Predefined Routes Dropdown -->
        <div x-show="linkType === 'predefined'" x-transition class="space-y-2">
            <label for="predefined_route" class="block text-sm font-medium text-gray-700">Select Route</label>
            <select id="predefined_route" x-model="selectedRoute" @change="updateUrl()"
                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary">
                <option value="">Choose a page...</option>
                @if(isset($availableRoutes))
                    @foreach($availableRoutes as $name => $url)
                        <option value="{{ $url }}">{{ $name }}</option>
                    @endforeach
                @endif
            </select>
            <p class="text-xs text-gray-500">Select from available site pages</p>
        </div>


        <!-- System Content Dropdown -->
        <div x-show="linkType === 'system'" x-transition class="space-y-2">
            <label for="system_content" class="block text-sm font-medium text-gray-700">Select Content</label>
            <select id="system_content" x-model="selectedSystemContent" @change="updateUrl()"
                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary">
                <option value="">Choose content...</option>
                @if(isset($systemContent))
                    @foreach($systemContent as $category => $items)
                        <optgroup label="{{ $category }}">
                            @foreach($items as $item)
                                <option value="{{ $item['url'] }}" data-type="{{ $item['type'] }}">
                                    {{ $item['title'] }}
                                </option>
                            @endforeach
                        </optgroup>
                    @endforeach
                @endif
            </select>
            <p class="text-xs text-gray-500">Select from existing pages, blog posts, solutions, or services</p>
        </div>

        <!-- Custom URL Input -->
        <div x-show="linkType === 'custom'" x-transition class="space-y-2">
            <label for="custom_url" class="block text-sm font-medium text-gray-700">Custom URL</label>
            <input type="text" id="custom_url" x-model="customUrl" @input="updateUrl()"
                   placeholder="/custom-page or https://external-site.com or # for no link"
                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary">
            <p class="text-xs text-gray-500">
                Enter a relative path (/custom-page), full URL (https://...), or # for no link
            </p>
        </div>

        <!-- Hidden URL Field (actual form field) -->
        <input type="hidden" name="url" id="url" x-model="finalUrl">
        
        <!-- URL Preview -->
        <div x-show="finalUrl" class="bg-gray-50 border border-gray-200 rounded-lg p-3">
            <p class="text-sm text-gray-600">
                <strong>Final URL:</strong> 
                <span x-text="getDisplayUrl()" class="font-mono text-primary"></span>
            </p>
        </div>
        
        @error('url')
            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
        @enderror
    </div>



    <!-- Display Options -->
    <div class="border-t border-gray-200 pt-4 space-y-3">
        <h3 class="text-lg font-semibold text-gray-900">Display Options</h3>
        
        <x-ui.toggle 
            name="is_active"
            :checked="old('is_active', $megaMenu->is_active ?? true)"
            label="Active (visible in menu)"
        />

        <label class="flex items-center space-x-2 cursor-pointer">
            <input type="checkbox" name="open_in_new_tab" value="1" 
                   {{ old('open_in_new_tab', $megaMenu->open_in_new_tab ?? false) ? 'checked' : '' }}
                   class="w-4 h-4 text-primary border-gray-300 rounded focus:ring-primary">
            <span class="text-sm text-gray-700">Open in new tab</span>
        </label>

        <!-- Tags (multiple, comma-separated) -->
        <div>
            <label for="tags" class="block text-sm font-medium text-gray-700 mb-2">Tags</label>
            <input type="text" name="tags" id="tags"
                   value="{{ old('tags', implode(', ', optional($megaMenu)->tags ?? [])) }}"
                   placeholder="e.g. nav, cta, primary (comma-separated)"
                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary">
            <p class="text-xs text-gray-500 mt-1">Multiple tags allowed, comma-separated. Used for styling or grouping (e.g. nav, dropdown, cta, primary).</p>
            @error('tags')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>
    </div>
</div>

@push('scripts')
<script>
function megaMenuForm() {
    const currentUrl = '{{ old('url', $megaMenu->url ?? '') }}';
    const availableRoutes = @json($availableRoutes ?? []);
    const systemContent = @json($systemContent ?? []);
    
    // Get page ID if exists
    const currentPageId = {{ old('page_id', $megaMenu->page_id ?? 'null') ?? 'null' }};
    
    // Determine initial link type and values
    let initialLinkType = 'custom';
    let initialSelectedRoute = '';
    let initialSelectedSystemContent = '';
    let initialSelectedPageId = currentPageId || '';
    let initialCustomUrl = currentUrl;
    
    // If page_id is set, use page reference type
    if (currentPageId) {
        initialLinkType = 'page';
        // Get page URL from selected option if available
        const pageSelect = document.getElementById('page_id');
        if (pageSelect) {
            const selectedOption = pageSelect.options[pageSelect.selectedIndex];
            if (selectedOption && selectedOption.dataset.url) {
                initialCustomUrl = selectedOption.dataset.url;
            }
        }
    } else if (currentUrl) {
        // Check if current URL matches any predefined route
        for (const [name, url] of Object.entries(availableRoutes)) {
            if (url === currentUrl) {
                initialLinkType = 'predefined';
                initialSelectedRoute = url;
                initialCustomUrl = '';
                break;
            }
        }
        
        // If not found in predefined routes, check system content
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
        isMegaMenu: {{ old('is_mega_menu', $megaMenu->is_mega_menu ?? false) ? 'true' : 'false' }},
        linkType: initialLinkType,
        selectedPageId: initialSelectedPageId,
        selectedRoute: initialSelectedRoute,
        selectedSystemContent: initialSelectedSystemContent,
        customUrl: initialCustomUrl,
        finalUrl: currentUrl,
        
        init() {
            // Watch for parent_id changes to hide mega menu checkbox
            const parentSelect = document.getElementById('parent_id');
            if (parentSelect) {
                parentSelect.addEventListener('change', () => {
                    if (parentSelect.value) {
                        this.isMegaMenu = false;
                    }
                });
            }
            
            // Set initial URL
            this.updateUrl();
        },
        
        updateUrl() {
            if (this.linkType === 'page') {
                // Get page URL from selected option
                const pageSelect = document.getElementById('page_id');
                if (pageSelect && pageSelect.value) {
                    const selectedOption = pageSelect.options[pageSelect.selectedIndex];
                    if (selectedOption && selectedOption.dataset.url) {
                        this.finalUrl = selectedOption.dataset.url;
                    } else {
                        this.finalUrl = '';
                    }
                } else {
                    this.finalUrl = '';
                }
                // Clear other fields when using page reference
                this.customUrl = '';
                this.selectedRoute = '';
                this.selectedSystemContent = '';
            } else if (this.linkType === 'predefined') {
                this.finalUrl = this.selectedRoute;
                this.customUrl = ''; // Clear custom URL when using predefined
                this.selectedSystemContent = ''; // Clear system content when using predefined
                this.selectedPageId = ''; // Clear page ID when using predefined
            } else if (this.linkType === 'system') {
                this.finalUrl = this.selectedSystemContent;
                this.customUrl = ''; // Clear custom URL when using system content
                this.selectedRoute = ''; // Clear predefined route when using system content
                this.selectedPageId = ''; // Clear page ID when using system content
            } else {
                this.finalUrl = this.customUrl;
                this.selectedRoute = ''; // Clear selected route when using custom
                this.selectedSystemContent = ''; // Clear system content when using custom
                this.selectedPageId = ''; // Clear page ID when using custom
            }
        },
        
        getDisplayUrl() {
            if (!this.finalUrl) return '';
            
            // If it's already a full URL, return as-is
            if (this.finalUrl.startsWith('http://') || this.finalUrl.startsWith('https://')) {
                return this.finalUrl;
            }
            
            // If it's a relative path, show the full URL
            if (this.finalUrl.startsWith('/')) {
                return window.location.origin + this.finalUrl;
            }
            
            // If it's just a fragment or other, return as-is
            return this.finalUrl;
        }
    };
}
</script>
@endpush
