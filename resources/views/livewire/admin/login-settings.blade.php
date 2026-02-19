<div>
    {{-- Inline script to ensure function is available immediately --}}
    <script>
        // Make routes available globally
        window.loginSettingsRoutes = @json($availableRoutes ?? []);
        window.loginSettingsSystemContent = @json($systemContent ?? []);
        
        // Create the function immediately if it doesn't exist
        if (!window.loginUrlSelector) {
            window.loginUrlSelector = function(currentUrl = '', currentLinkType = 'custom', linkIndex = 0) {
                const availableRoutes = window.loginSettingsRoutes || {};
                const systemContent = window.loginSettingsSystemContent || {};
                
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
                    linkIndex: linkIndex,
                    init() {
                        this.updateUrl();
                        this.$watch('finalUrl', (value) => { this.syncToLivewire(); });
                        this.$watch('linkType', (value) => { this.syncToLivewire(); });
                    },
                    syncToLivewire() {
                        if (this.$wire) {
                            this.$wire.set(`footerLinks.${this.linkIndex}.link`, this.finalUrl);
                            this.$wire.set(`footerLinks.${this.linkIndex}.link_type`, this.linkType);
                        }
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
                        this.syncToLivewire();
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
        }
    </script>
    
    {{-- Header with Title --}}
    <div class="flex items-center justify-between mb-6">
        <div class="flex flex-col gap-2">
            <h2>Login Settings</h2>
            <p>Customize your login page appearance and functionality</p>
        </div>
        <div class="w-12 h-12 bg-primary rounded-md flex items-center justify-center">
            <i class="fa-solid fa-sign-in-alt text-white text-xl"></i>
        </div>
    </div>

    {{-- Main Content --}}
    <div class="grid grid-cols-12 gap-8">
        {{-- Left Column: Login Settings --}}
        <div class="col-span-8">
            <div class="flex flex-col gap-4 bg-gray-50/50 rounded-md border border-gray-200 p-8 mb-8">
                {{-- Login Form Settings Card --}}
                <div class="flex items-center space-x-4 mb-6">
                    <div class="w-12 h-12 bg-secondary rounded-md flex items-center justify-center">
                        <i class="fa-solid fa-user-lock text-white text-2xl"></i>
                    </div>
                    <div>
                        <h3>Login Page Configuration</h3>
                        <p>Customize the appearance and behavior of your login page</p>
                    </div>
                </div>

                @if (session('status') === 'login-settings-updated')
                    <div class="mb-6 bg-green-50 border border-green-200 rounded-xl p-4">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <i class="fa-solid fa-check-circle text-green-500 text-lg"></i>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm font-medium text-green-800">
                                    Login settings updated successfully!
                                </p>
                            </div>
                        </div>
                    </div>
                @endif

                <div class="space-y-8">
                    {{-- Login Form Mode --}}
                    <div>
                        <h4 class="mb-4 flex items-center">
                            <i class="fa-solid fa-paint-brush text-primary mr-3"></i>
                            Login Form Style
                        </h4>
                        <div>
                            <label for="loginFormMode" class="block text-sm font-medium text-gray-700 mb-2">
                                Choose the style for login and forgot password forms
                            </label>
                            <select id="loginFormMode"
                                    wire:model.live="loginFormMode"
                                    class="block bg-white w-full px-4 py-3 border border-gray-200 rounded-md text-gray-900 focus:outline-none focus:border-primary focus:ring-2 focus:ring-primary/20">
                                <option value="white">White (Solid white background with image on right)</option>
                                <option value="glass">Glass (Transparent with blur effect over fullscreen background)</option>
                            </select>
                            <p class="mt-2 text-xs text-gray-600">
                                Glass style shows a blurred transparent form over fullscreen background image. White style shows a solid white form panel on the left with image on the right.
                            </p>
                            @error('loginFormMode')
                            <p class="mt-1 text-xs text-red-600">
                                {{ $message }}
                            </p>
                            @enderror
                        </div>
                    </div>

                    {{-- Login Page Content --}}
                    <div>
                        <h4 class="mb-4 flex items-center">
                            <i class="fa-solid fa-heading text-primary mr-3"></i>
                            Page Content
                        </h4>
                        <div class="space-y-4">
                            <div>
                                <label for="loginPageTitle" class="block text-sm font-medium text-gray-700 mb-2">
                                    Login Page Title
                                </label>
                                <input type="text"
                                       id="loginPageTitle"
                                       wire:model="loginPageTitle"
                                       class="block bg-white w-full px-4 py-3 border border-gray-200 rounded-md text-gray-900 placeholder-gray-500 focus:outline-none"
                                       placeholder="Log in">
                                @error('loginPageTitle')
                                <p class="mt-1 text-xs text-red-600">
                                    {{ $message }}
                                </p>
                                @enderror
                            </div>

                            <div>
                                <label for="loginPageSubtitle" class="block text-sm font-medium text-gray-700 mb-2">
                                    Login Page Subtitle
                                </label>
                                <input type="text"
                                       id="loginPageSubtitle"
                                       wire:model="loginPageSubtitle"
                                       class="block bg-white w-full px-4 py-3 border border-gray-200 rounded-md text-gray-900 placeholder-gray-500 focus:outline-none"
                                       placeholder="Enter your credentials to access your account">
                                @error('loginPageSubtitle')
                                <p class="mt-1 text-xs text-red-600">
                                    {{ $message }}
                                </p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    {{-- Images --}}
                    <div>
                        <h4 class="mb-4 flex items-center">
                            <i class="fa-solid fa-image text-primary mr-3"></i>
                            Images
                        </h4>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            {{-- Login Page Logo --}}
                            <div>
                                <x-image-upload
                                    id="loginPageLogo"
                                    name="loginPageLogo"
                                    label="Login Page Logo"
                                    :current-image="$currentLoginPageLogo"
                                    current-image-alt="Login Page Logo"
                                    help-text="JPEG, PNG, JPG, GIF, SVG up to 2MB"
                                    accept="image/jpeg,image/png,image/jpg,image/gif,image/svg+xml"
                                    :max-size="2048"
                                    size="medium"
                                    wire:model="loginPageLogo"
                                />
                                @if($currentLoginPageLogo)
                                    <button type="button"
                                            wire:click="removeLoginPageLogo"
                                            class="mt-2 text-sm text-red-600 hover:text-red-800">
                                        <i class="fa-solid fa-trash mr-1"></i>Remove Logo
                                    </button>
                                @endif
                                @error('loginPageLogo')
                                <p class="mt-1 text-xs text-red-600">
                                    {{ $message }}
                                </p>
                                @enderror
                            </div>

                            {{-- Login Background Image --}}
                            <div>
                                <x-image-upload
                                    id="loginBackgroundImage"
                                    name="loginBackgroundImage"
                                    label="Login Background Image"
                                    :current-image="$currentLoginBackgroundImage"
                                    current-image-alt="Login Background Image"
                                    help-text="JPEG, PNG, JPG, GIF up to 5MB"
                                    accept="image/jpeg,image/png,image/jpg,image/gif"
                                    :max-size="5120"
                                    size="large"
                                    wire:model="loginBackgroundImage"
                                />
                                @if($currentLoginBackgroundImage)
                                    <button type="button"
                                            wire:click="removeLoginBackgroundImage"
                                            class="mt-2 text-sm text-red-600 hover:text-red-800">
                                        <i class="fa-solid fa-trash mr-1"></i>Remove Background
                                    </button>
                                @endif
                                @error('loginBackgroundImage')
                                <p class="mt-1 text-xs text-red-600">
                                    {{ $message }}
                                </p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    {{-- Form Options --}}
                    <div>
                        <h4 class="mb-4 flex items-center">
                            <i class="fa-solid fa-toggle-on text-primary mr-3"></i>
                            Form Options
                        </h4>
                        <div class="space-y-4">
                            <x-ui.toggle
                                name="loginEnableRememberMe"
                                id="loginEnableRememberMe"
                                label="Enable Remember Me"
                                description="Show or hide the remember me checkbox on the login form"
                                :checked="$loginEnableRememberMe"
                                wire:model="loginEnableRememberMe"
                            />

                            <x-ui.toggle
                                name="loginEnableForgotPassword"
                                id="loginEnableForgotPassword"
                                label="Enable Forgot Password Link"
                                description="Show or hide the forgot password link on the login form"
                                :checked="$loginEnableForgotPassword"
                                wire:model="loginEnableForgotPassword"
                            />
                        </div>
                    </div>

                    {{-- Footer Copyright --}}
                    <div>
                        <h4 class="mb-4 flex items-center">
                            <i class="fa-solid fa-copyright text-primary mr-3"></i>
                            Footer Copyright
                        </h4>
                        <div>
                            <label for="loginFooterCopyright" class="block text-sm font-medium text-gray-700 mb-2">
                                Copyright Text (use @{{year}} for current year)
                            </label>
                            <input type="text"
                                   id="loginFooterCopyright"
                                   wire:model="loginFooterCopyright"
                                   class="block bg-white w-full px-4 py-3 border border-gray-200 rounded-md text-gray-900 placeholder-gray-500 focus:outline-none"
                                   placeholder="© @{{year}} All rights reserved.">
                            @error('loginFooterCopyright')
                            <p class="mt-1 text-xs text-red-600">
                                {{ $message }}
                            </p>
                            @enderror
                        </div>
                    </div>

                    {{-- Footer Links --}}
                    <div>
                        <div class="flex items-center justify-between mb-4">
                            <h4 class="flex items-center">
                                <i class="fa-solid fa-link text-primary mr-3"></i>
                                Footer Links
                            </h4>
                            <button type="button"
                                    wire:click="addFooterLink"
                                    class="text-sm bg-primary text-white px-4 py-2 rounded-md hover:bg-primary/90">
                                <i class="fa-solid fa-plus mr-1"></i>Add Link
                            </button>
                        </div>

                        @if(count($footerLinks) > 0)
                            <div class="space-y-4">
                                @foreach($footerLinks as $index => $link)
                                    <div class="bg-white border border-gray-200 rounded-md p-4">
                                        <div class="flex items-start justify-between mb-3">
                                            <span class="text-sm font-medium text-gray-700">Link #{{ $index + 1 }}</span>
                                            <button type="button"
                                                    wire:click="removeFooterLink({{ $index }})"
                                                    class="text-red-600 hover:text-red-800 text-sm">
                                                <i class="fa-solid fa-trash"></i>
                                            </button>
                                        </div>
                                        <div class="space-y-4">
                                            <div>
                                                <label class="block text-xs font-medium text-gray-700 mb-1">Title</label>
                                                <input type="text"
                                                       wire:model="footerLinks.{{ $index }}.title"
                                                       class="block bg-white w-full px-3 py-2 border border-gray-200 rounded-md text-gray-900 text-sm focus:outline-none"
                                                       placeholder="Privacy Policy">
                                                @error("footerLinks.{$index}.title")
                                                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                                                @enderror
                                            </div>

                                            {{-- URL Selector --}}
                                            <div x-data="loginUrlSelector(@js($footerLinks[$index]['link'] ?? ''), @js($footerLinks[$index]['link_type'] ?? 'custom'), @js($index))" 
                                                 class="space-y-2">
                                                <label class="block text-xs font-medium text-gray-700 mb-1">Link Configuration</label>
                                                
                                                {{-- Link Type Selection --}}
                                                <div class="grid grid-cols-3 gap-2 mb-3">
                                                    <label class="flex items-center space-x-1 cursor-pointer text-xs">
                                                        <input type="radio" 
                                                               x-model="linkType"
                                                               value="predefined"
                                                               @change="updateUrl()"
                                                               class="w-3 h-3 text-primary border-gray-300 focus:ring-primary">
                                                        <span class="text-gray-700">Predefined</span>
                                                    </label>
                                                    <label class="flex items-center space-x-1 cursor-pointer text-xs">
                                                        <input type="radio" 
                                                               x-model="linkType"
                                                               value="system"
                                                               @change="updateUrl()"
                                                               class="w-3 h-3 text-primary border-gray-300 focus:ring-primary">
                                                        <span class="text-gray-700">System</span>
                                                    </label>
                                                    <label class="flex items-center space-x-1 cursor-pointer text-xs">
                                                        <input type="radio" 
                                                               x-model="linkType"
                                                               value="custom"
                                                               @change="updateUrl()"
                                                               class="w-3 h-3 text-primary border-gray-300 focus:ring-primary">
                                                        <span class="text-gray-700">Custom</span>
                                                    </label>
                                                </div>

                                                {{-- Predefined Routes Dropdown --}}
                                                <div x-show="linkType === 'predefined'" 
                                                     x-transition:enter="transition ease-out duration-200"
                                                     x-transition:enter-start="opacity-0"
                                                     x-transition:enter-end="opacity-100"
                                                     x-transition:leave="transition ease-in duration-150"
                                                     x-transition:leave-start="opacity-100"
                                                     x-transition:leave-end="opacity-0"
                                                     class="space-y-1">
                                                    <select x-model="selectedRoute" 
                                                            @change="updateUrl()"
                                                            class="block bg-white w-full px-3 py-2 border border-gray-200 rounded-md text-gray-900 text-sm focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary">
                                                        <option value="">Choose a page...</option>
                                                        @if(isset($availableRoutes) && is_array($availableRoutes) && count($availableRoutes) > 0)
                                                            @foreach($availableRoutes as $name => $url)
                                                                <option value="{{ $url }}">{{ $name }}</option>
                                                            @endforeach
                                                        @else
                                                            <option value="" disabled>No routes available</option>
                                                        @endif
                                                    </select>
                                                </div>

                                                {{-- System Content Dropdown --}}
                                                <div x-show="linkType === 'system'" 
                                                     x-transition:enter="transition ease-out duration-200"
                                                     x-transition:enter-start="opacity-0"
                                                     x-transition:enter-end="opacity-100"
                                                     x-transition:leave="transition ease-in duration-150"
                                                     x-transition:leave-start="opacity-100"
                                                     x-transition:leave-end="opacity-0"
                                                     class="space-y-1">
                                                    <select x-model="selectedSystemContent" 
                                                            @change="updateUrl()"
                                                            class="block bg-white w-full px-3 py-2 border border-gray-200 rounded-md text-gray-900 text-sm focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary">
                                                        <option value="">Choose content...</option>
                                                        @if(isset($systemContent) && is_array($systemContent) && count($systemContent) > 0)
                                                            @foreach($systemContent as $category => $items)
                                                                <optgroup label="{{ $category }}">
                                                                    @foreach($items as $item)
                                                                        <option value="{{ $item['url'] }}">{{ $item['title'] }}</option>
                                                                    @endforeach
                                                                </optgroup>
                                                            @endforeach
                                                        @else
                                                            <option value="" disabled>No system content available</option>
                                                        @endif
                                                    </select>
                                                </div>

                                                {{-- Custom URL Input --}}
                                                <div x-show="linkType === 'custom'" x-transition class="space-y-1">
                                                    <input type="text" 
                                                           x-model="customUrl" 
                                                           @input="updateUrl()"
                                                           placeholder="/custom-page or https://external-site.com"
                                                           class="block bg-white w-full px-3 py-2 border border-gray-200 rounded-md text-gray-900 text-sm focus:outline-none">
                                                </div>

                                                {{-- Hidden fields for Livewire (values are synced via $wire.set) --}}
                                                <input type="hidden" 
                                                       wire:model="footerLinks.{{ $index }}.link"
                                                       x-ref="linkInput">
                                                <input type="hidden" 
                                                       wire:model="footerLinks.{{ $index }}.link_type"
                                                       x-ref="linkTypeInput">

                                                {{-- URL Preview --}}
                                                <div x-show="finalUrl" class="bg-gray-50 border border-gray-200 rounded p-2">
                                                    <p class="text-xs text-gray-600">
                                                        <strong>URL:</strong> 
                                                        <span x-text="getDisplayUrl()" class="font-mono text-primary"></span>
                                                    </p>
                                                </div>
                                                @error("footerLinks.{$index}.link")
                                                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                                                @enderror
                                            </div>
                                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                                <div>
                                                    <label class="block text-xs font-medium text-gray-700 mb-1">Order</label>
                                                    <input type="number"
                                                           wire:model="footerLinks.{{ $index }}.order"
                                                           min="0"
                                                           class="block bg-white w-full px-3 py-2 border border-gray-200 rounded-md text-gray-900 text-sm focus:outline-none"
                                                           placeholder="1">
                                                    @error("footerLinks.{$index}.order")
                                                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                                                    @enderror
                                                </div>
                                                <div>
                                                    <label class="block text-xs font-medium text-gray-700 mb-1">Target</label>
                                                    <select wire:model="footerLinks.{{ $index }}.target"
                                                            class="block bg-white w-full px-3 py-2 border border-gray-200 rounded-md text-gray-900 text-sm focus:outline-none">
                                                        <option value="_self">Same Window</option>
                                                        <option value="_blank">New Window</option>
                                                    </select>
                                                    @error("footerLinks.{$index}.target")
                                                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="bg-gray-50 border border-gray-200 rounded-md p-6 text-center">
                                <p class="text-sm text-gray-500">No footer links added yet. Click "Add Link" to get started.</p>
                            </div>
                        @endif
                    </div>

                    {{-- Action Buttons --}}
                    <div class="flex justify-end space-x-3 pt-4">
                        <button type="button"
                                wire:click="save"
                                wire:loading.attr="disabled"
                                class="px-6 py-2 rounded-md font-medium focus:outline-none disabled:opacity-50 disabled:cursor-not-allowed transition-all duration-300 text-sm {{ $saved ? 'bg-green-600 text-white hover:bg-green-700' : 'bg-primary text-white hover:bg-primary/90' }}">
                            <span wire:loading.remove wire:target="save">
                                @if($saved)
                                    <i class="fa-solid fa-check mr-2"></i>
                                    Saved!
                                @else
                                    <i class="fa-solid fa-save mr-2"></i>
                                    Save Login Settings
                                @endif
                            </span>
                            <span wire:loading wire:target="save">
                                <i class="fa-solid fa-spinner fa-spin mr-2"></i>
                                Saving...
                            </span>
                        </button>
                    </div>
                </div>
            </div>
        </div>

        {{-- Right Column: Preview & Info --}}
        <div class="col-span-4">
            {{-- Preview Card --}}
            <div class="bg-gray-50/50 rounded-md border border-gray-200 p-6 mb-6 sticky top-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                    <i class="fa-solid fa-eye text-primary mr-2"></i>
                    Preview
                </h3>
                <div class="space-y-4">
                    <div class="p-4 bg-white rounded-md border border-gray-200">
                        <h4 class="text-sm font-semibold text-gray-900 mb-2">Login Form Style</h4>
                        <p class="text-xs text-gray-600 mb-2">
                            <strong>Current:</strong> {{ $loginFormMode === 'white' ? 'White Style' : 'Glass Style' }}
                        </p>
                        <div class="text-xs text-gray-500">
                            @if($loginFormMode === 'white')
                                White form on left, image on right
                            @else
                                Glass form over fullscreen background
                            @endif
                        </div>
                    </div>

                    <div class="p-4 bg-white rounded-md border border-gray-200">
                        <h4 class="text-sm font-semibold text-gray-900 mb-2">Page Content</h4>
                        <div class="space-y-2 text-xs">
                            <div>
                                <strong>Title:</strong> <span class="text-gray-600">{{ $loginPageTitle ?: 'Not set' }}</span>
                            </div>
                            <div>
                                <strong>Subtitle:</strong> <span class="text-gray-600">{{ $loginPageSubtitle ?: 'Not set' }}</span>
                            </div>
                        </div>
                    </div>

                    <div class="p-4 bg-white rounded-md border border-gray-200">
                        <h4 class="text-sm font-semibold text-gray-900 mb-2">Form Options</h4>
                        <div class="space-y-1 text-xs">
                            <div class="flex items-center">
                                <i class="fa-solid {{ $loginEnableRememberMe ? 'fa-check-circle text-green-500' : 'fa-times-circle text-red-500' }} mr-2"></i>
                                <span>Remember Me</span>
                            </div>
                            <div class="flex items-center">
                                <i class="fa-solid {{ $loginEnableForgotPassword ? 'fa-check-circle text-green-500' : 'fa-times-circle text-red-500' }} mr-2"></i>
                                <span>Forgot Password</span>
                            </div>
                        </div>
                    </div>

                    @if(count($footerLinks) > 0)
                        <div class="p-4 bg-white rounded-md border border-gray-200">
                            <h4 class="text-sm font-semibold text-gray-900 mb-2">Footer Links ({{ count($footerLinks) }})</h4>
                            <div class="space-y-1 text-xs">
                                @foreach($footerLinks as $link)
                                    <div class="text-gray-600">
                                        {{ $link['title'] ?: 'Untitled' }} (Order: {{ $link['order'] ?? 'N/A' }})
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>


@push('scripts')
<script>
    // Make routes available globally so they're accessible after Livewire updates
    window.loginSettingsRoutes = @json($availableRoutes ?? []);
    window.loginSettingsSystemContent = @json($systemContent ?? []);

    // Create a global function that can be called directly in x-data
    // This works immediately without waiting for Alpine initialization
    window.loginUrlSelector = function(currentUrl = '', currentLinkType = 'custom', linkIndex = 0) {
        // Get routes from global scope (updated on each Livewire render)
        const availableRoutes = window.loginSettingsRoutes || {};
        const systemContent = window.loginSettingsSystemContent || {};
        
        // Determine initial link type and values
        let initialLinkType = currentLinkType || 'custom';
        let initialSelectedRoute = '';
        let initialSelectedSystemContent = '';
        let initialCustomUrl = currentUrl || '';

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
            finalUrl: currentUrl || '',
            linkIndex: linkIndex,

            init() {
                this.updateUrl();
                // Watch for changes and sync with Livewire
                this.$watch('finalUrl', (value) => {
                    this.syncToLivewire();
                });
                this.$watch('linkType', (value) => {
                    this.syncToLivewire();
                });
            },

            syncToLivewire() {
                // Update Livewire directly using $wire.set()
                if (this.$wire) {
                    this.$wire.set(`footerLinks.${this.linkIndex}.link`, this.finalUrl);
                    this.$wire.set(`footerLinks.${this.linkIndex}.link_type`, this.linkType);
                }
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
                // Sync to Livewire after updating
                this.syncToLivewire();
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
    };

    // Create URL selector functions for each footer link (for backward compatibility)
    @foreach($footerLinks as $index => $link)
        window['urlSelector{{ $index }}'] = function(currentUrl = '', currentLinkType = 'custom', availableRoutes = {}, systemContent = {}, linkIndex = {{ $index }}) {
            // Use Alpine.data if available, otherwise fallback
            if (window.Alpine && Alpine.data('loginUrlSelector')) {
                return Alpine.data('loginUrlSelector')(currentUrl, currentLinkType, linkIndex);
            }
            // Fallback implementation
            return {
                linkType: currentLinkType || 'custom',
                selectedRoute: '',
                selectedSystemContent: '',
                customUrl: currentUrl || '',
                finalUrl: currentUrl || '',
                linkIndex: linkIndex,
                init() {},
                updateUrl() {},
                getDisplayUrl() { return this.finalUrl || ''; }
            };
        };
    @endforeach
</script>
@endpush

