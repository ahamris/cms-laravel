<x-layouts.admin title="General Settings">
    {{-- Page Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-8">
        <div>
            <h1 class="text-3xl font-bold text-zinc-900 dark:text-white mb-2">General Settings</h1>
            <p class="text-zinc-600 dark:text-zinc-400">Manage your website's general settings and configuration</p>
        </div>
    </div>

    @if (session('status') === 'settings-updated')
        <div class="mb-6 rounded-lg bg-green-50 dark:bg-green-500/10 border border-green-200 dark:border-green-500/20 p-4">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <i class="fa-solid fa-check-circle text-green-500 text-lg"></i>
                </div>
                <div class="ml-3">
                    <p class="text-sm font-medium text-green-800 dark:text-green-300">
                        Settings updated successfully!
                    </p>
                </div>
            </div>
        </div>
    @endif

    <form method="POST" action="{{ route('admin.settings.general.update') }}" enctype="multipart/form-data" id="general-settings-form">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            {{-- Left Column - 2/3 --}}
            <div class="lg:col-span-2 space-y-8">
                {{-- Site Information Section --}}
                <div class="rounded-lg border border-gray-200 dark:border-white/10 bg-white dark:bg-white/5 p-6">
                    <div class="mb-6">
                        <h2 class="text-base/7 font-semibold text-gray-900 dark:text-white">Site Information</h2>
                        <p class="mt-1 text-sm/6 text-gray-600 dark:text-gray-400">Basic information about your website</p>
                    </div>

                    <div class="space-y-6">
                        {{-- Site Name --}}
                        <div>
                            <x-ui.input
                                id="site_name"
                                name="site_name"
                                :value="old('site_name', get_setting('site_name', 'Open Publicaties'))"
                                label="Site Name"
                                placeholder="Enter your site name"
                                required
                            />
                            @error('site_name')
                                <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Copyright Footer --}}
                        <div>
                            <x-ui.input
                                id="copyright_footer"
                                name="copyright_footer"
                                :value="old('copyright_footer', get_setting('copyright_footer', 'Open Publicaties'))"
                                label="Footer Copyright Text"
                                placeholder="Enter your copyright text"
                                required
                            />
                            @error('copyright_footer')
                                <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Site Description --}}
                        <div>
                            <label for="site_description" class="block text-sm/6 font-medium text-gray-900 dark:text-white">Site Description</label>
                            <div class="mt-2">
                                <textarea id="site_description" 
                                          name="site_description" 
                                          rows="3" 
                                          placeholder="Enter your site description"
                                          class="block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline-2 focus:-outline-offset-2 focus:outline-[var(--color-accent)] sm:text-sm/6 dark:bg-white/5 dark:text-white dark:outline-white/10 dark:placeholder:text-gray-500 dark:focus:outline-[var(--color-accent)] @error('site_description') outline-red-500 dark:outline-red-500 @enderror">{{ old('site_description', get_setting('site_description', 'Alle openbare overheidsinformatie op één plek.')) }}</textarea>
                            </div>
                            @error('site_description')
                                <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Site Email & Phone --}}
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <x-ui.input
                                    id="site_email"
                                    name="site_email"
                                    type="email"
                                    :value="old('site_email', get_setting('site_email', 'info@example.com'))"
                                    label="Site Email"
                                    placeholder="Enter your site email"
                                    required
                                />
                                @error('site_email')
                                    <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <x-ui.input
                                    id="site_phone"
                                    name="site_phone"
                                    type="tel"
                                    :value="old('site_phone', get_setting('site_phone', '+1234567890'))"
                                    label="Site Phone"
                                    placeholder="Enter your site phone"
                                />
                                @error('site_phone')
                                    <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        {{-- Site Address --}}
                        <div>
                            <label for="site_address" class="block text-sm/6 font-medium text-gray-900 dark:text-white">Site Address</label>
                            <div class="mt-2">
                                <textarea id="site_address" 
                                          name="site_address" 
                                          rows="2" 
                                          placeholder="Enter your site address"
                                          class="block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline-2 focus:-outline-offset-2 focus:outline-[var(--color-accent)] sm:text-sm/6 dark:bg-white/5 dark:text-white dark:outline-white/10 dark:placeholder:text-gray-500 dark:focus:outline-[var(--color-accent)] @error('site_address') outline-red-500 dark:outline-red-500 @enderror">{{ old('site_address', get_setting('site_address', '123 Main St, City, Country')) }}</textarea>
                            </div>
                            @error('site_address')
                                <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                {{-- Brand Assets Section --}}
                <div class="rounded-lg border border-gray-200 dark:border-white/10 bg-white dark:bg-white/5 p-6">
                    <div class="mb-6">
                        <h2 class="text-base/7 font-semibold text-gray-900 dark:text-white">Brand Assets</h2>
                        <p class="mt-1 text-sm/6 text-gray-600 dark:text-gray-400">Upload logos and favicons for your website</p>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        {{-- Site Logo --}}
                        <div>
                            <x-image-upload
                                id="site_logo"
                                name="site_logo"
                                label="Site Logo"
                                :current-image="\App\Models\Setting::hasFile('site_logo') ? \App\Models\Setting::getLogoUrl() : null"
                                help-text="JPEG, PNG, JPG, GIF, SVG, WebP up to 20MB"
                                :max-size="20480"
                                current-image-alt="Site logo"
                            />
                        </div>

                        {{-- Favicon --}}
                        <div>
                            <x-image-upload
                                id="site_favicon"
                                name="site_favicon"
                                label="Site Favicon"
                                :current-image="\App\Models\Setting::hasFile('site_favicon') ? \App\Models\Setting::getFaviconUrl() : null"
                                help-text="ICO, PNG, GIF, JPG, JPEG, WebP up to 20MB"
                                :max-size="20480"
                                current-image-alt="Site favicon"
                            />
                        </div>

                        {{-- Admin Logo --}}
                        <div>
                            <x-image-upload
                                id="admin_logo"
                                name="admin_logo"
                                label="Admin Logo"
                                :current-image="\App\Models\Setting::hasFile('admin_logo') ? \App\Models\Setting::getFileUrl('admin_logo') : null"
                                help-text="Logo displayed in admin panel. JPEG, PNG, JPG, GIF, SVG, WebP up to 20MB"
                                :max-size="20480"
                                current-image-alt="Admin logo"
                            />
                        </div>

                        {{-- Footer Logo --}}
                        <div>
                            <x-image-upload
                                id="footer_logo"
                                name="footer_logo"
                                label="Footer Logo"
                                :current-image="\App\Models\Setting::hasFile('footer_logo') ? \App\Models\Setting::getFileUrl('footer_logo') : null"
                                help-text="Logo shown in site footer. JPEG, PNG, JPG, GIF, SVG, WebP up to 20MB"
                                :max-size="20480"
                                current-image-alt="Footer logo"
                            />
                        </div>
                    </div>
                </div>

                {{-- SEO Settings Section --}}
                <div class="rounded-lg border border-gray-200 dark:border-white/10 bg-white dark:bg-white/5 p-6">
                    <div class="mb-6">
                        <h2 class="text-base/7 font-semibold text-gray-900 dark:text-white">SEO Settings</h2>
                        <p class="mt-1 text-sm/6 text-gray-600 dark:text-gray-400">Default SEO metadata for your website</p>
                    </div>

                    <div class="space-y-6">
                        {{-- Meta Title --}}
                        <div>
                            <x-ui.input
                                id="meta_title"
                                name="meta_title"
                                :value="old('meta_title', get_setting('meta_title', 'OPUB - Open Publishing Platform'))"
                                label="Default Meta Title"
                                placeholder="Enter default meta title"
                                required
                            />
                            @error('meta_title')
                                <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Meta Description --}}
                        <div>
                            <label for="meta_description" class="block text-sm/6 font-medium text-gray-900 dark:text-white">Default Meta Description</label>
                            <div class="mt-2">
                                <textarea id="meta_description" 
                                          name="meta_description" 
                                          rows="3" 
                                          placeholder="Enter default meta description"
                                          class="block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline-2 focus:-outline-offset-2 focus:outline-[var(--color-accent)] sm:text-sm/6 dark:bg-white/5 dark:text-white dark:outline-white/10 dark:placeholder:text-gray-500 dark:focus:outline-[var(--color-accent)] @error('meta_description') outline-red-500 dark:outline-red-500 @enderror">{{ old('meta_description', get_setting('meta_description', 'OPUB is an open-source publishing platform for creating and managing content')) }}</textarea>
                            </div>
                            @error('meta_description')
                                <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Meta Keywords --}}
                        <div>
                            <label for="meta_keywords" class="block text-sm/6 font-medium text-gray-900 dark:text-white">Default Meta Keywords</label>
                            <div class="mt-2">
                                <textarea id="meta_keywords" 
                                          name="meta_keywords" 
                                          rows="2" 
                                          placeholder="Enter default meta keywords"
                                          class="block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline-2 focus:-outline-offset-2 focus:outline-[var(--color-accent)] sm:text-sm/6 dark:bg-white/5 dark:text-white dark:outline-white/10 dark:placeholder:text-gray-500 dark:focus:outline-[var(--color-accent)] @error('meta_keywords') outline-red-500 dark:outline-red-500 @enderror">{{ old('meta_keywords', get_setting('meta_keywords', 'opub, publishing, content management, open-source')) }}</textarea>
                            </div>
                            @error('meta_keywords')
                                <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Google Analytics --}}
                        <div>
                            <x-ui.input
                                id="google_analytics"
                                name="google_analytics"
                                :value="old('google_analytics', get_setting('google_analytics', ''))"
                                label="Google Analytics ID"
                                placeholder="Enter Google Analytics ID (e.g., G-XXXXXXXXXX)"
                            />
                            @error('google_analytics')
                                <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                {{-- Content Settings Section --}}
                <div class="rounded-lg border border-gray-200 dark:border-white/10 bg-white dark:bg-white/5 p-6">
                    <div class="mb-6">
                        <h2 class="text-base/7 font-semibold text-gray-900 dark:text-white">Content Settings</h2>
                        <p class="mt-1 text-sm/6 text-gray-600 dark:text-gray-400">Configure content-related settings</p>
                    </div>

                    <div class="space-y-6">
                        {{-- Posts Per Page & Default Category --}}
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <x-ui.input
                                    id="posts_per_page"
                                    name="posts_per_page"
                                    type="number"
                                    :value="old('posts_per_page', get_setting('posts_per_page', '10'))"
                                    label="Posts Per Page"
                                    placeholder="Enter number of posts per page"
                                    :min="1"
                                    :max="100"
                                    required
                                />
                                @error('posts_per_page')
                                    <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <x-ui.input
                                    id="default_category"
                                    name="default_category"
                                    type="number"
                                    :value="old('default_category', get_setting('default_category', '1'))"
                                    label="Default Category ID"
                                    placeholder="Enter default category ID"
                                    :min="1"
                                    required
                                />
                                @error('default_category')
                                    <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        {{-- Comment Settings --}}
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="flex items-center justify-between">
                                <div>
                                    <label class="block text-sm/6 font-medium text-gray-900 dark:text-white">Enable Comments</label>
                                    <p class="text-sm/6 text-gray-600 dark:text-gray-400">Allow comments on posts</p>
                                </div>
                                <div>
                                    <input type="hidden" name="enable_comments" value="0">
                                    <x-ui.toggle 
                                        name="enable_comments"
                                        :checked="old('enable_comments', get_setting('enable_comments', '1')) == '1'"
                                    />
                                </div>
                            </div>

                            <div class="flex items-center justify-between">
                                <div>
                                    <label class="block text-sm/6 font-medium text-gray-900 dark:text-white">Moderate Comments</label>
                                    <p class="text-sm/6 text-gray-600 dark:text-gray-400">Require approval for comments</p>
                                </div>
                                <div>
                                    <input type="hidden" name="moderate_comments" value="0">
                                    <x-ui.toggle 
                                        name="moderate_comments"
                                        :checked="old('moderate_comments', get_setting('moderate_comments', '1')) == '1'"
                                    />
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Doculet Sandbox Settings Section --}}
                <div class="rounded-lg border border-gray-200 dark:border-white/10 bg-white dark:bg-white/5 p-6">
                    <div class="mb-6">
                        <h2 class="text-base/7 font-semibold text-gray-900 dark:text-white">Doculet Sandbox Settings</h2>
                        <p class="mt-1 text-sm/6 text-gray-600 dark:text-gray-400">Configure Doculet API testing and development settings</p>
                    </div>

                    <div class="flex items-center justify-between">
                        <div>
                            <label class="block text-sm/6 font-medium text-gray-900 dark:text-white">Enable Doculet Sandbox</label>
                            <p class="text-sm/6 text-gray-600 dark:text-gray-400">Enable sandbox mode for Doculet API testing and development</p>
                        </div>
                        <div>
                            <input type="hidden" name="doculoket_sandbox" value="0">
                            <x-ui.toggle 
                                name="doculoket_sandbox"
                                :checked="old('doculoket_sandbox', get_setting('doculoket_sandbox', '0')) == '1'"
                            />
                        </div>
                    </div>
                </div>

                {{-- Map Settings Section --}}
                <div class="rounded-lg border border-gray-200 dark:border-white/10 bg-white dark:bg-white/5 p-6">
                    <div class="mb-6">
                        <h2 class="text-base/7 font-semibold text-gray-900 dark:text-white">Map Settings (OpenStreetMap)</h2>
                        <p class="mt-1 text-sm/6 text-gray-600 dark:text-gray-400">Configure map location and coordinates</p>
                    </div>

                    <div class="space-y-6">
                        {{-- Search Location --}}
                        <div>
                            <label for="location_search" class="block text-sm/6 font-medium text-gray-900 dark:text-white">Search Location</label>
                            <div class="mt-2 flex gap-2">
                                <input id="location_search" 
                                       type="text"
                                       class="flex-1 block rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline-2 focus:-outline-offset-2 focus:outline-[var(--color-accent)] sm:text-sm/6 dark:bg-white/5 dark:text-white dark:outline-white/10 dark:placeholder:text-gray-500 dark:focus:outline-[var(--color-accent)]"
                                       placeholder="Search for a location (e.g., Amsterdam, Netherlands)">
                                <button type="button" 
                                        onclick="searchLocation()"
                                        class="inline-flex items-center gap-2 rounded-md bg-[var(--color-accent)] px-4 py-2 text-sm font-semibold text-white shadow-xs hover:opacity-90">
                                    <i class="fa-solid fa-search"></i>
                                    Search
                                </button>
                            </div>
                            <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Search for your location to automatically fill coordinates</p>
                        </div>

                        {{-- Interactive Map --}}
                        <div>
                            <label class="block text-sm/6 font-medium text-gray-900 dark:text-white mb-2">Click on map to set location</label>
                            <div id="admin_map" class="w-full h-[400px] rounded-lg border border-gray-200 dark:border-white/10"></div>
                            <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Click anywhere on the map to set your location marker</p>
                        </div>

                        {{-- Coordinates --}}
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <div>
                                <x-ui.input
                                    id="map_latitude"
                                    name="map_latitude"
                                    :value="old('map_latitude', get_setting('map_latitude', '52.3676'))"
                                    label="Latitude"
                                    placeholder="e.g., 52.3676"
                                    hint="Latitude coordinate for map center"
                                    readonly
                                />
                                @error('map_latitude')
                                    <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <x-ui.input
                                    id="map_longitude"
                                    name="map_longitude"
                                    :value="old('map_longitude', get_setting('map_longitude', '4.9041'))"
                                    label="Longitude"
                                    placeholder="e.g., 4.9041"
                                    hint="Longitude coordinate for map center"
                                    readonly
                                />
                                @error('map_longitude')
                                    <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <x-ui.input
                                    id="map_zoom"
                                    name="map_zoom"
                                    type="number"
                                    :value="old('map_zoom', get_setting('map_zoom', '13'))"
                                    label="Zoom Level"
                                    placeholder="13"
                                    hint="Map zoom level (1-19, where 1 is world view and 19 is street level)"
                                    :min="1"
                                    :max="19"
                                />
                                @error('map_zoom')
                                    <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Right Column - 1/3 --}}
            <div class="lg:col-span-1 space-y-8">
                {{-- Quick Actions Section --}}
                <div class="rounded-lg border border-gray-200 dark:border-white/10 bg-white dark:bg-white/5 p-6">
                    <div class="mb-6">
                        <h2 class="text-base/7 font-semibold text-gray-900 dark:text-white">Quick Actions</h2>
                        <p class="mt-1 text-sm/6 text-gray-600 dark:text-gray-400">Quick access to related pages</p>
                    </div>

                    <div class="space-y-3">
                        <a href="{{ route('admin.settings.hero-backgrounds.index') }}"
                           class="flex items-center gap-3 p-3 rounded-lg border border-gray-200 dark:border-white/10 bg-gray-50 dark:bg-white/5 hover:bg-gray-100 dark:hover:bg-white/10 transition-colors">
                            <div class="flex-shrink-0 w-10 h-10 rounded-lg bg-blue-100 dark:bg-blue-500/10 flex items-center justify-center">
                                <i class="fa-solid fa-image text-blue-600 dark:text-blue-400"></i>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-medium text-gray-900 dark:text-white">Hero section backgrounds</p>
                                <p class="text-xs text-gray-500 dark:text-gray-400">Contact, blog, solutions, modules, academy</p>
                            </div>
                        </a>
                        <a href="{{ route('admin.administrator.users.index') }}"
                           class="flex items-center gap-3 p-3 rounded-lg border border-gray-200 dark:border-white/10 bg-gray-50 dark:bg-white/5 hover:bg-gray-100 dark:hover:bg-white/10 transition-colors">
                            <div class="flex-shrink-0 w-10 h-10 rounded-lg bg-purple-100 dark:bg-purple-500/10 flex items-center justify-center">
                                <i class="fa-solid fa-users text-purple-600 dark:text-purple-400"></i>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-medium text-gray-900 dark:text-white">User Management</p>
                                <p class="text-xs text-gray-500 dark:text-gray-400">Manage users and permissions</p>
                            </div>
                        </a>
                    </div>
                </div>

                {{-- System Information Section --}}
                <div class="rounded-lg border border-gray-200 dark:border-white/10 bg-white dark:bg-white/5 p-6">
                    <div class="mb-6">
                        <h2 class="text-base/7 font-semibold text-gray-900 dark:text-white">System Information</h2>
                        <p class="mt-1 text-sm/6 text-gray-600 dark:text-gray-400">Current system details</p>
                    </div>

                    <div class="space-y-4">
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-gray-600 dark:text-gray-400">Laravel Version:</span>
                            <span class="text-sm font-medium text-gray-900 dark:text-white">{{ app()->version() }}</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-gray-600 dark:text-gray-400">PHP Version:</span>
                            <span class="text-sm font-medium text-gray-900 dark:text-white">{{ phpversion() }}</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-gray-600 dark:text-gray-400">Environment:</span>
                            <span class="inline-flex items-center gap-x-1.5 rounded-full px-2 py-1 text-xs font-medium {{ config('app.env') === 'production' ? 'bg-green-100 text-green-800 dark:bg-green-500/10 dark:text-green-400' : 'bg-yellow-100 text-yellow-800 dark:bg-yellow-500/10 dark:text-yellow-400' }}">
                                {{ ucfirst(config('app.env')) }}
                            </span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-gray-600 dark:text-gray-400">Debug Mode:</span>
                            @if(config('app.debug'))
                                <span class="inline-flex items-center gap-x-1.5 rounded-full bg-red-100 dark:bg-red-500/10 px-2 py-1 text-xs font-medium text-red-800 dark:text-red-400">
                                    Enabled
                                </span>
                            @else
                                <span class="inline-flex items-center gap-x-1.5 rounded-full bg-green-100 dark:bg-green-500/10 px-2 py-1 text-xs font-medium text-green-800 dark:text-green-400">
                                    Disabled
                                </span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Form Actions --}}
        <div class="mt-8 flex flex-col sm:flex-row items-center justify-end gap-4 border-t border-gray-200 dark:border-white/10 pt-6">
            <button type="submit" class="inline-flex items-center gap-2 rounded-md bg-[var(--color-accent)] px-4 py-2 text-sm font-semibold text-white shadow-xs hover:opacity-90 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-[var(--color-accent)]">
                <i class="fa-solid fa-save"></i>
                Save Settings
            </button>
        </div>
    </form>

    {{-- Leaflet CSS --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.9.4/leaflet.min.css" integrity="sha512-h9FcoyWjHcOcmEVkxOfTLnmZFWIH0iZhZT1H2TbOq55xssQGEJHEaIm+PgoUaZbRvQTNTluNOEfb1ZRy6D3BOw==" crossorigin="anonymous" referrerpolicy="no-referrer" />

    {{-- Leaflet JS --}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.9.4/leaflet.min.js" integrity="sha512-puJW3E/qXDqYp9IfhAI54BJEaWIfloJ7JWs7OeD5i6ruC9JZL1gERT1wjtwXFlh7CjE7ZJ+/vcRZRkIYIb6p4g==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

    <script>
        // Leaflet Map for Location Selection
        let adminMap, adminMarker;

        document.addEventListener('DOMContentLoaded', function() {
            const lat = parseFloat(document.getElementById('map_latitude').value) || 52.3676;
            const lng = parseFloat(document.getElementById('map_longitude').value) || 4.9041;
            const zoom = parseInt(document.getElementById('map_zoom').value) || 13;

            // Initialize map
            adminMap = L.map('admin_map', {
                scrollWheelZoom: false
            }).setView([lat, lng], zoom);

            // Add OpenStreetMap tiles
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors',
                maxZoom: 19
            }).addTo(adminMap);

            // Add marker
            adminMarker = L.marker([lat, lng], {
                draggable: true
            }).addTo(adminMap);

            // Update coordinates when marker is dragged
            adminMarker.on('dragend', function(e) {
                const position = e.target.getLatLng();
                updateCoordinates(position.lat, position.lng);
            });

            // Update coordinates when map is clicked
            adminMap.on('click', function(e) {
                adminMarker.setLatLng(e.latlng);
                updateCoordinates(e.latlng.lat, e.latlng.lng);
            });

            // Allow Enter key to trigger search
            document.getElementById('location_search').addEventListener('keypress', function(e) {
                if (e.key === 'Enter') {
                    e.preventDefault();
                    searchLocation();
                }
            });
        });

        function updateCoordinates(lat, lng) {
            document.getElementById('map_latitude').value = lat.toFixed(6);
            document.getElementById('map_longitude').value = lng.toFixed(6);
        }

        async function searchLocation() {
            const query = document.getElementById('location_search').value.trim();
            if (!query) {
                alert('Please enter a location to search');
                return;
            }

            try {
                // Use Nominatim API for geocoding
                const response = await fetch(`https://nominatim.openstreetmap.org/search?format=json&q=${encodeURIComponent(query)}&limit=1`);
                const data = await response.json();

                if (data && data.length > 0) {
                    const result = data[0];
                    const lat = parseFloat(result.lat);
                    const lng = parseFloat(result.lon);

                    // Update map
                    adminMap.setView([lat, lng], 13);
                    adminMarker.setLatLng([lat, lng]);

                    // Update form fields
                    updateCoordinates(lat, lng);

                    // Show success message
                    alert(`Location found: ${result.display_name}`);
                } else {
                    alert('Location not found. Please try a different search term.');
                }
            } catch (error) {
                console.error('Search error:', error);
                alert('Error searching for location. Please try again.');
            }
        }
    </script>
</x-layouts.admin>