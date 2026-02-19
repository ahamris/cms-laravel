<x-layouts.admin title="Edit Social Setting">
    {{-- Page Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-8">
        <div>
            <h1 class="text-3xl font-bold text-zinc-900 dark:text-white mb-2">Edit Social Setting</h1>
            <p class="text-zinc-600 dark:text-zinc-400">Update social media link information</p>
        </div>
        <div class="flex items-center gap-3">
            <x-button variant="outline-secondary" :href="route('admin.social-settings.index')" icon="arrow-left">
                Back to Social Settings
            </x-button>
        </div>
    </div>

    <form action="{{ route('admin.social-settings.update', $socialSetting) }}" method="POST" id="social-setting-form">
        @csrf
        @method('PUT')
        
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            {{-- Left Column - 2/3 --}}
            <div class="lg:col-span-2 space-y-8">
                {{-- Social Setting Details Section --}}
                <div class="rounded-lg border border-gray-200 dark:border-white/10 bg-white dark:bg-white/5 p-6">
                    <div class="mb-6">
                        <h2 class="text-base/7 font-semibold text-gray-900 dark:text-white">Social Setting Details</h2>
                        <p class="mt-1 text-sm/6 text-gray-600 dark:text-gray-400">Basic information about the social media platform.</p>
                    </div>

                    <div class="space-y-6">
                        {{-- Name & Icon --}}
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <x-ui.input
                                    id="name"
                                    name="name"
                                    :value="old('name', $socialSetting->name)"
                                    label="Social Platform Name"
                                    hint="Enter the name of the social media platform"
                                    placeholder="e.g., Facebook, Twitter, Instagram"
                                    required
                                />
                                @error('name')
                                    <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <x-icon-picker
                                    id="icon"
                                    name="icon"
                                    :value="old('icon', $socialSetting->icon)"
                                    label="Icon Class"
                                    help-text="Select a FontAwesome icon for the platform"
                                    :required="true"
                                />
                                @error('icon')
                                    <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        {{-- URL --}}
                        <div>
                            <x-ui.input
                                id="url"
                                name="url"
                                type="url"
                                :value="old('url', $socialSetting->url)"
                                label="Social Media URL"
                                hint="Full URL to your social media profile or page"
                                placeholder="https://www.facebook.com/yourpage"
                                icon="link"
                                required
                            />
                            @error('url')
                                <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Preview Section --}}
                        <div class="rounded-lg border border-gray-200 dark:border-white/10 bg-gray-50 dark:bg-white/5 p-4">
                            <h4 class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-3">Preview</h4>
                            <div class="flex items-center gap-3">
                                <div id="iconPreview" class="text-2xl text-gray-400 dark:text-gray-500">
                                    <i class="{{ $socialSetting->icon }}"></i>
                                </div>
                                <div>
                                    <div id="namePreview" class="text-sm font-medium text-gray-900 dark:text-white">{{ $socialSetting->name }}</div>
                                    <div id="urlPreview" class="text-xs text-gray-500 dark:text-gray-400">{{ $socialSetting->url }}</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Right Column - 1/3 --}}
            <div class="lg:col-span-1 space-y-8">
                {{-- Actions Section --}}
                <div class="rounded-lg border border-gray-200 dark:border-white/10 bg-white dark:bg-white/5 p-6">
                    <div class="mb-6">
                        <h2 class="text-base/7 font-semibold text-gray-900 dark:text-white">Actions</h2>
                        <p class="mt-1 text-sm/6 text-gray-600 dark:text-gray-400">Save or cancel this social setting.</p>
                    </div>

                    <div class="space-y-4">
                        <x-button variant="primary" type="submit" icon="save" class="w-full justify-center">
                            Update Social Setting
                        </x-button>
                        <x-button variant="outline-secondary" :href="route('admin.social-settings.index')" icon="arrow-left" class="w-full justify-center">
                            Cancel
                        </x-button>
                    </div>
                </div>
            </div>
        </div>
    </form>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const nameInput = document.getElementById('name');
            const iconInput = document.getElementById('icon');
            const urlInput = document.getElementById('url');

            const namePreview = document.getElementById('namePreview');
            const iconPreview = document.getElementById('iconPreview');
            const urlPreview = document.getElementById('urlPreview');

            // Check if elements exist
            if (!nameInput || !iconInput || !urlInput || !namePreview || !iconPreview || !urlPreview) {
                return;
            }

            function updatePreview() {
                // Update name
                const name = nameInput.value || 'Social Platform Name';
                namePreview.textContent = name;

                // Update icon
                const iconClass = iconInput.value || 'fas fa-question-circle';
                iconPreview.innerHTML = `<i class="${iconClass}"></i>`;

                // Update URL
                const url = urlInput.value || 'URL will appear here';
                urlPreview.textContent = url;
            }

            // Add event listeners for input changes
            nameInput.addEventListener('input', updatePreview);
            iconInput.addEventListener('input', updatePreview);
            urlInput.addEventListener('input', updatePreview);

            // Initial preview update
            updatePreview();
        });
    </script>
</x-layouts.admin>
