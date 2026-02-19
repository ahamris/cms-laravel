<x-layouts.admin title="Cookie Settings">
    {{-- Header with Title --}}
    <div class="flex items-center justify-between mb-6">
        <div class="flex flex-col gap-2">
            <h2>Cookie Settings</h2>
            <p>Manage your cookie consent banner and cookie settings</p>
        </div>
        <div class="w-12 h-12 bg-primary rounded-md flex items-center justify-center">
            <i class="fa-solid fa-cookie-bite text-white text-xl"></i>
        </div>
    </div>

    {{-- Main Content --}}
    <div class="grid grid-cols-12 gap-8">
        {{-- Left Column: Settings Form --}}
        <div class="col-span-8">
            <div class="flex flex-col gap-4 bg-gray-50/50 rounded-md border border-gray-200 p-8 mb-8">
                @if (session('status') === 'settings-updated')
                    <div class="mb-6 bg-green-50 border border-green-200 rounded-xl p-4">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <i class="fa-solid fa-check-circle text-green-500 text-lg"></i>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm font-medium text-green-800">
                                    Settings updated successfully!
                                </p>
                            </div>
                        </div>
                    </div>
                @endif

                {{-- Settings Form --}}
                <form method="POST" action="{{ route('admin.settings.cookie.update') }}" class="space-y-8">
                    @csrf
                    @method('PUT')

                    {{-- Banner Settings --}}
                    <div>
                        <h4 class="mb-6 flex items-center">
                            <i class="fa-solid fa-toggle-on text-primary mr-3"></i>
                            Banner Settings
                        </h4>
                        <div class="space-y-6">
                            <x-ui.toggle
                                name="cookie_banner_enabled"
                                :checked="old('cookie_banner_enabled', get_setting('cookie_banner_enabled', '1')) == '1'"
                                label="Enable Cookie Banner"
                                description="Show the cookie consent banner on the frontend"
                            />
                        </div>
                    </div>

                    {{-- Banner Intro Text --}}
                    <div>
                        <h4 class="mb-6 flex items-center">
                            <i class="fa-solid fa-comment-dots text-primary mr-3"></i>
                            Banner Intro Text
                        </h4>
                        <div class="space-y-6">
                            <div>
                                <label for="cookie_intro_title" class="block text-sm font-medium text-gray-700 mb-2">
                                    Intro Title
                                </label>
                                <input id="cookie_intro_title" type="text" name="cookie_intro_title"
                                       value="{{ old('cookie_intro_title', get_setting('cookie_intro_title', 'We use cookies')) }}"
                                       class="block bg-white w-full px-4 py-3 border border-gray-200 rounded-md text-gray-900 placeholder-gray-500 focus:outline-none"
                                       placeholder="e.g., We use cookies">
                                @error('cookie_intro_title')
                                <p class="mt-2 text-sm text-red-600 flex items-center">
                                    <i class="fa-solid fa-exclamation-circle mr-1"></i>
                                    {{ $message }}
                                </p>
                                @enderror
                            </div>

                            <div>
                                <label for="cookie_intro_summary" class="block text-sm font-medium text-gray-700 mb-2">
                                    Intro Summary
                                </label>
                                <textarea id="cookie_intro_summary" name="cookie_intro_summary" rows="4"
                                          class="block bg-white w-full px-4 py-3 border border-gray-200 rounded-md text-gray-900 placeholder-gray-500 focus:outline-none"
                                          placeholder="Enter summary text for the cookie banner">{{ old('cookie_intro_summary', get_setting('cookie_intro_summary', 'In addition to functional cookies we also place analytics and marketing cookies to understand usage, show relevant content and offer support. Only essential cookies are enabled by default.')) }}</textarea>
                                @error('cookie_intro_summary')
                                <p class="mt-2 text-sm text-red-600 flex items-center">
                                    <i class="fa-solid fa-exclamation-circle mr-1"></i>
                                    {{ $message }}
                                </p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    {{-- Preferences Text --}}
                    <div>
                        <h4 class="mb-6 flex items-center">
                            <i class="fa-solid fa-sliders-h text-primary mr-3"></i>
                            Preferences Modal Text
                        </h4>
                        <div class="space-y-6">
                            <div>
                                <label for="cookie_preferences_title" class="block text-sm font-medium text-gray-700 mb-2">
                                    Preferences Title
                                </label>
                                <input id="cookie_preferences_title" type="text" name="cookie_preferences_title"
                                       value="{{ old('cookie_preferences_title', get_setting('cookie_preferences_title', 'Manage cookie preferences')) }}"
                                       class="block bg-white w-full px-4 py-3 border border-gray-200 rounded-md text-gray-900 placeholder-gray-500 focus:outline-none"
                                       placeholder="e.g., Manage cookie preferences">
                                @error('cookie_preferences_title')
                                <p class="mt-2 text-sm text-red-600 flex items-center">
                                    <i class="fa-solid fa-exclamation-circle mr-1"></i>
                                    {{ $message }}
                                </p>
                                @enderror
                            </div>

                            <div>
                                <label for="cookie_preferences_summary" class="block text-sm font-medium text-gray-700 mb-2">
                                    Preferences Summary
                                </label>
                                <textarea id="cookie_preferences_summary" name="cookie_preferences_summary" rows="3"
                                          class="block bg-white w-full px-4 py-3 border border-gray-200 rounded-md text-gray-900 placeholder-gray-500 focus:outline-none"
                                          placeholder="Enter summary text for the preferences modal">{{ old('cookie_preferences_summary', get_setting('cookie_preferences_summary', 'Configure your cookie preferences below. Need more information? Read our policy.')) }}</textarea>
                                @error('cookie_preferences_summary')
                                <p class="mt-2 text-sm text-red-600 flex items-center">
                                    <i class="fa-solid fa-exclamation-circle mr-1"></i>
                                    {{ $message }}
                                </p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    {{-- Links Configuration --}}
                    <div>
                        <h4 class="mb-6 flex items-center">
                            <i class="fa-solid fa-link text-primary mr-3"></i>
                            Links Configuration
                        </h4>
                        <div class="space-y-6">
                            {{-- Cookie Settings Link --}}
                            <div>
                                <label for="cookie_settings_label" class="block text-sm font-medium text-gray-700 mb-2">
                                    Cookie Settings Link Label
                                </label>
                                <input id="cookie_settings_label" type="text" name="cookie_settings_label"
                                       value="{{ old('cookie_settings_label', get_setting('cookie_settings_label', 'Cookie policy')) }}"
                                       class="block bg-white w-full px-4 py-3 border border-gray-200 rounded-md text-gray-900 placeholder-gray-500 focus:outline-none mb-4"
                                       placeholder="e.g., Cookie policy">
                                @error('cookie_settings_label')
                                <p class="mt-2 text-sm text-red-600 flex items-center">
                                    <i class="fa-solid fa-exclamation-circle mr-1"></i>
                                    {{ $message }}
                                </p>
                                @enderror

                                <div class="space-y-3">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">Select Cookie Settings Page</label>
                                        <select name="cookie_settings_page_type" id="cookie_settings_page_type"
                                                class="block bg-white w-full px-4 py-3 border border-gray-200 rounded-md text-gray-900 focus:outline-none mb-3"
                                                onchange="togglePageSelect('cookie_settings')">
                                            <option value="custom" {{ old('cookie_settings_page_type', get_setting('cookie_settings_page_type', 'custom')) == 'custom' ? 'selected' : '' }}>Custom URL</option>
                                            <option value="legal" {{ old('cookie_settings_page_type', get_setting('cookie_settings_page_type')) == 'legal' ? 'selected' : '' }}>Legal Page</option>
                                            <option value="static" {{ old('cookie_settings_page_type', get_setting('cookie_settings_page_type')) == 'static' ? 'selected' : '' }}>Static Page</option>
                                        </select>
                                    </div>

                                    <div id="cookie_settings_legal_select" style="display: {{ old('cookie_settings_page_type', get_setting('cookie_settings_page_type', 'custom')) == 'legal' ? 'block' : 'none' }};">
                                        <label class="block text-sm font-medium text-gray-700 mb-2">Select Legal Page</label>
                                        <select name="cookie_settings_page_id" id="cookie_settings_page_id"
                                                class="block bg-white w-full px-4 py-3 border border-gray-200 rounded-md text-gray-900 focus:outline-none">
                                            <option value="">-- Select a legal page --</option>
                                            @foreach($legalPages as $page)
                                                <option value="{{ $page->id }}" 
                                                    {{ old('cookie_settings_page_id', get_setting('cookie_settings_page_id')) == $page->id ? 'selected' : '' }}>
                                                    {{ $page->title }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div id="cookie_settings_static_select" style="display: {{ old('cookie_settings_page_type', get_setting('cookie_settings_page_type', 'custom')) == 'static' ? 'block' : 'none' }};">
                                        <label class="block text-sm font-medium text-gray-700 mb-2">Select Static Page</label>
                                        <select name="cookie_settings_page_id" id="cookie_settings_static_page_id"
                                                class="block bg-white w-full px-4 py-3 border border-gray-200 rounded-md text-gray-900 focus:outline-none">
                                            <option value="">-- Select a static page --</option>
                                            @foreach($staticPages as $page)
                                                <option value="{{ $page->id }}" 
                                                    {{ old('cookie_settings_page_id', get_setting('cookie_settings_page_id')) == $page->id ? 'selected' : '' }}>
                                                    {{ $page->title }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div id="cookie_settings_custom_url" style="display: {{ old('cookie_settings_page_type', get_setting('cookie_settings_page_type', 'custom')) == 'custom' ? 'block' : 'none' }};">
                                        <label class="block text-sm font-medium text-gray-700 mb-2">Custom URL</label>
                                        <input type="text" name="cookie_settings_url"
                                               value="{{ old('cookie_settings_url', get_setting('cookie_settings_url', 'javascript:void(0)')) }}"
                                               class="block bg-white w-full px-4 py-3 border border-gray-200 rounded-md text-gray-900 placeholder-gray-500 focus:outline-none"
                                               placeholder="e.g., /cookie-settings or https://example.com/cookies">
                                    </div>
                                </div>
                            </div>

                            {{-- Cookie Policy Link --}}
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Select Cookie Policy Page</label>
                                <select name="cookie_policy_page_type" id="cookie_policy_page_type"
                                        class="block bg-white w-full px-4 py-3 border border-gray-200 rounded-md text-gray-900 focus:outline-none mb-3"
                                        onchange="togglePageSelect('cookie_policy')">
                                    <option value="custom" {{ old('cookie_policy_page_type', get_setting('cookie_policy_page_type', 'custom')) == 'custom' ? 'selected' : '' }}>Custom URL</option>
                                    <option value="legal" {{ old('cookie_policy_page_type', get_setting('cookie_policy_page_type')) == 'legal' ? 'selected' : '' }}>Legal Page</option>
                                    <option value="static" {{ old('cookie_policy_page_type', get_setting('cookie_policy_page_type')) == 'static' ? 'selected' : '' }}>Static Page</option>
                                </select>

                                <div id="cookie_policy_legal_select" style="display: {{ old('cookie_policy_page_type', get_setting('cookie_policy_page_type', 'custom')) == 'legal' ? 'block' : 'none' }};">
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Select Legal Page</label>
                                    <select name="cookie_policy_page_id" id="cookie_policy_page_id"
                                            class="block bg-white w-full px-4 py-3 border border-gray-200 rounded-md text-gray-900 focus:outline-none">
                                        <option value="">-- Select a legal page --</option>
                                        @foreach($legalPages as $page)
                                            <option value="{{ $page->id }}" 
                                                {{ old('cookie_policy_page_id', get_setting('cookie_policy_page_id')) == $page->id ? 'selected' : '' }}>
                                                {{ $page->title }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div id="cookie_policy_static_select" style="display: {{ old('cookie_policy_page_type', get_setting('cookie_policy_page_type', 'custom')) == 'static' ? 'block' : 'none' }};">
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Select Static Page</label>
                                    <select name="cookie_policy_page_id" id="cookie_policy_static_page_id"
                                            class="block bg-white w-full px-4 py-3 border border-gray-200 rounded-md text-gray-900 focus:outline-none">
                                        <option value="">-- Select a static page --</option>
                                        @foreach($staticPages as $page)
                                            <option value="{{ $page->id }}" 
                                                {{ old('cookie_policy_page_id', get_setting('cookie_policy_page_id')) == $page->id ? 'selected' : '' }}>
                                                {{ $page->title }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div id="cookie_policy_custom_url" style="display: {{ old('cookie_policy_page_type', get_setting('cookie_policy_page_type', 'custom')) == 'custom' ? 'block' : 'none' }};">
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Custom URL</label>
                                    <input type="text" name="cookie_policy_url"
                                           value="{{ old('cookie_policy_url', get_setting('cookie_policy_url', 'javascript:void(0)')) }}"
                                           class="block bg-white w-full px-4 py-3 border border-gray-200 rounded-md text-gray-900 placeholder-gray-500 focus:outline-none"
                                           placeholder="e.g., /cookie-policy or https://example.com/cookie-policy">
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Cookie Categories --}}
                    <div>
                        <h4 class="mb-6 flex items-center">
                            <i class="fa-solid fa-list text-primary mr-3"></i>
                            Cookie Categories
                        </h4>
                        <div class="space-y-6">
                            {{-- Functional Category --}}
                            <div class="border border-gray-200 rounded-md p-4 bg-white">
                                <h5 class="font-semibold text-gray-900 mb-4">Functional Cookies</h5>
                                <div class="space-y-4">
                                    <div>
                                        <label for="cookie_category_functional_label" class="block text-sm font-medium text-gray-700 mb-2">
                                            Label
                                        </label>
                                        <input id="cookie_category_functional_label" type="text" name="cookie_category_functional_label"
                                               value="{{ old('cookie_category_functional_label', get_setting('cookie_category_functional_label', 'Functional cookies')) }}"
                                               class="block bg-white w-full px-4 py-3 border border-gray-200 rounded-md text-gray-900 placeholder-gray-500 focus:outline-none">
                                    </div>
                                    <div>
                                        <label for="cookie_category_functional_description" class="block text-sm font-medium text-gray-700 mb-2">
                                            Description
                                        </label>
                                        <textarea id="cookie_category_functional_description" name="cookie_category_functional_description" rows="2"
                                                  class="block bg-white w-full px-4 py-3 border border-gray-200 rounded-md text-gray-900 placeholder-gray-500 focus:outline-none">{{ old('cookie_category_functional_description', get_setting('cookie_category_functional_description', 'Required for core functionality of the website.')) }}</textarea>
                                    </div>
                                </div>
                            </div>

                            {{-- Analytics Category --}}
                            <div class="border border-gray-200 rounded-md p-4 bg-white">
                                <h5 class="font-semibold text-gray-900 mb-4">Analytics Cookies</h5>
                                <div class="space-y-4">
                                    <div>
                                        <label for="cookie_category_analytics_label" class="block text-sm font-medium text-gray-700 mb-2">
                                            Label
                                        </label>
                                        <input id="cookie_category_analytics_label" type="text" name="cookie_category_analytics_label"
                                               value="{{ old('cookie_category_analytics_label', get_setting('cookie_category_analytics_label', 'Analytics cookies')) }}"
                                               class="block bg-white w-full px-4 py-3 border border-gray-200 rounded-md text-gray-900 placeholder-gray-500 focus:outline-none">
                                    </div>
                                    <div>
                                        <label for="cookie_category_analytics_description" class="block text-sm font-medium text-gray-700 mb-2">
                                            Description
                                        </label>
                                        <textarea id="cookie_category_analytics_description" name="cookie_category_analytics_description" rows="2"
                                                  class="block bg-white w-full px-4 py-3 border border-gray-200 rounded-md text-gray-900 placeholder-gray-500 focus:outline-none">{{ old('cookie_category_analytics_description', get_setting('cookie_category_analytics_description', 'Help us measure usage and improve the experience.')) }}</textarea>
                                    </div>
                                </div>
                            </div>

                            {{-- Marketing Category --}}
                            <div class="border border-gray-200 rounded-md p-4 bg-white">
                                <h5 class="font-semibold text-gray-900 mb-4">Marketing Cookies</h5>
                                <div class="space-y-4">
                                    <div>
                                        <label for="cookie_category_marketing_label" class="block text-sm font-medium text-gray-700 mb-2">
                                            Label
                                        </label>
                                        <input id="cookie_category_marketing_label" type="text" name="cookie_category_marketing_label"
                                               value="{{ old('cookie_category_marketing_label', get_setting('cookie_category_marketing_label', 'Marketing cookies')) }}"
                                               class="block bg-white w-full px-4 py-3 border border-gray-200 rounded-md text-gray-900 placeholder-gray-500 focus:outline-none">
                                    </div>
                                    <div>
                                        <label for="cookie_category_marketing_description" class="block text-sm font-medium text-gray-700 mb-2">
                                            Description
                                        </label>
                                        <textarea id="cookie_category_marketing_description" name="cookie_category_marketing_description" rows="2"
                                                  class="block bg-white w-full px-4 py-3 border border-gray-200 rounded-md text-gray-900 placeholder-gray-500 focus:outline-none">{{ old('cookie_category_marketing_description', get_setting('cookie_category_marketing_description', 'Enable personalised content and external integrations.')) }}</textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="flex justify-end">
                        <button type="submit"
                                class="bg-primary text-white px-8 py-3 rounded-md font-medium hover:bg-primary/90 focus:outline-none">
                            <i class="fa-solid fa-save mr-2"></i>
                            Save Settings
                        </button>
                    </div>
                </form>
            </div>
        </div>

        {{-- Right Column: Info --}}
        <div class="col-span-4">
            <div class="bg-gray-50/50 rounded-md border border-gray-200 p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                    <i class="fa-solid fa-info-circle text-primary mr-2"></i>
                    Cookie Settings Info
                </h3>
                <div class="space-y-3 text-sm text-gray-600">
                    <p>
                        Configure your cookie consent banner settings here. The banner will appear on the frontend based on your configuration.
                    </p>
                    <p>
                        You can link to legal pages or static pages for the cookie policy and settings links, or use custom URLs.
                    </p>
                    <p>
                        The cookie categories can be customized with your own labels and descriptions.
                    </p>
                </div>
            </div>
        </div>
    </div>

    <style>
    <style>
        input:checked + .block {
            background-color: #3b82f6;
        }

        input:checked + .block .dot {
            transform: translateX(100%);
        }

        .dot {
            transition: transform 0.2s ease-in-out;
        }
    </style>

    <script>
        function togglePageSelect(type) {
            const pageType = document.getElementById(type + '_page_type').value;
            
            // Hide all selects
            document.getElementById(type + '_legal_select').style.display = 'none';
            document.getElementById(type + '_static_select').style.display = 'none';
            document.getElementById(type + '_custom_url').style.display = 'none';
            
            // Show relevant select
            if (pageType === 'legal') {
                document.getElementById(type + '_legal_select').style.display = 'block';
            } else if (pageType === 'static') {
                document.getElementById(type + '_static_select').style.display = 'block';
            } else {
                document.getElementById(type + '_custom_url').style.display = 'block';
            }
        }

        // Initialize on page load
        document.addEventListener('DOMContentLoaded', function() {
            togglePageSelect('cookie_settings');
            togglePageSelect('cookie_policy');
        });
    </script>
    </script>
</x-layouts.admin>

