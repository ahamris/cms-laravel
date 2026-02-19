<x-layouts.admin title="Edit Footer Link">
<div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="bg-white shadow rounded-lg">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-medium text-gray-900">Edit Footer Link</h3>
        </div>
        <form action="{{ route('admin.settings.footer-links.update', $footerLink) }}" method="POST" class="p-6" x-data="footerLinkEditForm()">
            @csrf
            @method('PUT')
            <div class="space-y-6">
                <div>
                    <label for="title" class="block text-sm font-medium text-gray-700">Title</label>
                    <input type="text" name="title" id="title" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" value="{{ $footerLink->title }}" required>
                </div>
                <!-- Link Configuration -->
                <div class="space-y-4">
                    <h4 class="text-md font-medium text-gray-900">Link Configuration</h4>
                    
                    <!-- Link Type Selection -->
                    <div class="space-y-3">
                        <label class="block text-sm font-medium text-gray-700">Link Type</label>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                            <label class="flex items-center space-x-2 cursor-pointer">
                                <input type="radio" name="link_type" value="predefined" 
                                       x-model="linkType"
                                       class="w-4 h-4 text-indigo-600 border-gray-300 focus:ring-indigo-500">
                                <span class="text-sm text-gray-700">Predefined Route</span>
                            </label>
                            <label class="flex items-center space-x-2 cursor-pointer">
                                <input type="radio" name="link_type" value="system" 
                                       x-model="linkType"
                                       class="w-4 h-4 text-indigo-600 border-gray-300 focus:ring-indigo-500">
                                <span class="text-sm text-gray-700">System Content</span>
                            </label>
                            <label class="flex items-center space-x-2 cursor-pointer">
                                <input type="radio" name="link_type" value="custom" 
                                       x-model="linkType"
                                       class="w-4 h-4 text-indigo-600 border-gray-300 focus:ring-indigo-500">
                                <span class="text-sm text-gray-700">Custom URL</span>
                            </label>
                        </div>
                    </div>

                    <!-- Predefined Routes Dropdown -->
                    <div x-show="linkType === 'predefined'" x-transition class="space-y-2">
                        <label for="predefined_route" class="block text-sm font-medium text-gray-700">Select Route</label>
                        <select id="predefined_route" x-model="selectedRoute" @change="updateUrl()"
                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
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
                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
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
                               placeholder="/custom-page or https://external-site.com"
                               class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                        <p class="text-xs text-gray-500">
                            Enter a relative path (/custom-page) or full URL (https://...)
                        </p>
                    </div>

                    <!-- Hidden URL Field (actual form field) -->
                    <input type="hidden" name="url" id="url" x-model="finalUrl">
                    
                    <!-- URL Preview -->
                    <div x-show="finalUrl" class="bg-gray-50 border border-gray-200 rounded-lg p-3">
                        <p class="text-sm text-gray-600">
                            <strong>Final URL:</strong> 
                            <span x-text="getDisplayUrl()" class="font-mono text-indigo-600"></span>
                        </p>
                    </div>
                    
                    @error('url')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label for="column" class="block text-sm font-medium text-gray-700">Column</label>
                    <select name="column" id="column" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" required>
                        <option value="1" {{ $footerLink->column == 1 ? 'selected' : '' }}>Column 1</option>
                        <option value="2" {{ $footerLink->column == 2 ? 'selected' : '' }}>Column 2</option>
                        <option value="3" {{ $footerLink->column == 3 ? 'selected' : '' }}>Column 3</option>
                        <option value="4" {{ $footerLink->column == 4 ? 'selected' : '' }}>Column 4</option>
                    </select>
                </div>
                <x-ui.toggle 
                    name="is_active"
                    :checked="old('is_active', $footerLink->is_active)"
                    label="Active"
                />
            </div>
            <div class="mt-6 flex justify-end">
                <a href="{{ route('admin.settings.footer-links.index') }}" class="bg-white py-2 px-4 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 hover:bg-gray-50">Cancel</a>
                <button type="submit" class="ml-3 inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700">Update</button>
            </div>
        </form>
    </div>
</div>

    <script>
function footerLinkEditForm() {
    const currentUrl = '{{ $footerLink->url }}';
    const availableRoutes = @json($availableRoutes ?? []);
    const systemContent = @json($systemContent ?? []);
    
    // Determine initial link type and values
    let initialLinkType = 'custom';
    let initialSelectedRoute = '';
    let initialSelectedSystemContent = '';
    let initialCustomUrl = currentUrl;
    
    // Check if current URL matches any predefined route
    if (currentUrl) {
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
        linkType: initialLinkType,
        selectedRoute: initialSelectedRoute,
        selectedSystemContent: initialSelectedSystemContent,
        customUrl: initialCustomUrl,
        finalUrl: currentUrl,
        
        init() {
            // Set initial URL
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
    </script>
</x-layouts.admin>
