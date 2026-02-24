<x-layouts.admin title="Edit Footer Link">
    {{-- Page Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-8">
        <div>
            <h1 class="text-3xl font-bold text-zinc-900 dark:text-white mb-2">Edit Footer Link</h1>
            <p class="text-zinc-600 dark:text-zinc-400">Update title, column, and destination for this footer link.</p>
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ route('admin.settings.footer-links.index') }}" class="inline-flex items-center gap-2 rounded-md bg-white dark:bg-white/10 px-4 py-2 text-sm font-semibold text-gray-900 dark:text-white shadow-xs ring-1 ring-gray-300 ring-inset dark:ring-white/10 hover:bg-gray-50 dark:hover:bg-white/20">
                <i class="fa-solid fa-arrow-left"></i>
                Back to Footer Links
            </a>
        </div>
    </div>

    <form action="{{ route('admin.settings.footer-links.update', $footerLink) }}" method="POST" x-data="footerLinkEditForm()">
        @csrf
        @method('PUT')

        @if($errors->any())
            <div class="mb-6">
                <x-ui.alert variant="error" icon="circle-exclamation" :title="__('admin.form_fix_errors_title')">
                    <ul class="list-disc list-inside space-y-1">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </x-ui.alert>
            </div>
        @endif

        <div class="max-w-2xl space-y-8">
            {{-- Basic info --}}
            <div class="rounded-lg border border-gray-200 dark:border-white/10 bg-white dark:bg-white/5 p-6">
                <div class="mb-6">
                    <h2 class="text-base/7 font-semibold text-gray-900 dark:text-white">Link details</h2>
                    <p class="mt-1 text-sm/6 text-gray-600 dark:text-gray-400">Title and column for this footer link.</p>
                </div>
                <div class="space-y-6">
                    <div>
                        <x-ui.input
                            id="title"
                            name="title"
                            :value="old('title', $footerLink->title)"
                            label="Title"
                            placeholder="e.g. Contact, Privacy"
                            required
                            :error="$errors->has('title')"
                            :errorMessage="$errors->first('title')"
                        />
                    </div>
                    <div>
                        <label for="column" class="block text-sm/6 font-medium text-gray-900 dark:text-white mb-2">Column</label>
                        <select name="column" id="column" required
                                class="w-full rounded-lg border border-gray-300 dark:border-white/10 bg-white dark:bg-white/5 px-3 py-2 text-sm text-gray-900 dark:text-white focus:ring-2 focus:ring-[var(--color-accent)] focus:border-transparent">
                            <option value="1" {{ old('column', $footerLink->column) == 1 ? 'selected' : '' }}>Column 1</option>
                            <option value="2" {{ old('column', $footerLink->column) == 2 ? 'selected' : '' }}>Column 2</option>
                            <option value="3" {{ old('column', $footerLink->column) == 3 ? 'selected' : '' }}>Column 3</option>
                            <option value="4" {{ old('column', $footerLink->column) == 4 ? 'selected' : '' }}>Column 4</option>
                        </select>
                        @error('column')<p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>@enderror
                    </div>
                    <div class="flex items-center justify-between">
                        <div>
                            <label class="block text-sm/6 font-medium text-gray-900 dark:text-white">Active</label>
                            <p class="text-sm/6 text-gray-600 dark:text-gray-400">Show this link in the footer</p>
                        </div>
                        <input type="hidden" name="is_active" value="0">
                        <x-ui.toggle name="is_active" :checked="old('is_active', $footerLink->is_active)" />
                    </div>
                </div>
            </div>

            {{-- Link configuration --}}
            <div class="rounded-lg border border-gray-200 dark:border-white/10 bg-white dark:bg-white/5 p-6">
                <div class="mb-6">
                    <h2 class="text-base/7 font-semibold text-gray-900 dark:text-white">Link configuration</h2>
                    <p class="mt-1 text-sm/6 text-gray-600 dark:text-gray-400">Choose where the link points: predefined page, system content, or custom URL.</p>
                </div>

                <div class="space-y-6">
                    {{-- Link type --}}
                    <div>
                        <label class="block text-sm/6 font-medium text-gray-900 dark:text-white mb-3">Link type</label>
                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                            <label class="relative flex cursor-pointer rounded-lg border p-4 focus:outline-none transition-colors"
                                   :class="linkType === 'predefined' ? 'border-[var(--color-accent)] bg-[var(--color-accent)]/5 ring-2 ring-[var(--color-accent)]' : 'border-gray-200 dark:border-white/10 hover:border-gray-300 dark:hover:border-white/20'">
                                <input type="radio" name="link_type" value="predefined" x-model="linkType" class="sr-only">
                                <span class="flex flex-1 text-sm font-medium text-gray-900 dark:text-white">Predefined route</span>
                            </label>
                            <label class="relative flex cursor-pointer rounded-lg border p-4 focus:outline-none transition-colors"
                                   :class="linkType === 'system' ? 'border-[var(--color-accent)] bg-[var(--color-accent)]/5 ring-2 ring-[var(--color-accent)]' : 'border-gray-200 dark:border-white/10 hover:border-gray-300 dark:hover:border-white/20'">
                                <input type="radio" name="link_type" value="system" x-model="linkType" class="sr-only">
                                <span class="flex flex-1 text-sm font-medium text-gray-900 dark:text-white">System content</span>
                            </label>
                            <label class="relative flex cursor-pointer rounded-lg border p-4 focus:outline-none transition-colors"
                                   :class="linkType === 'custom' ? 'border-[var(--color-accent)] bg-[var(--color-accent)]/5 ring-2 ring-[var(--color-accent)]' : 'border-gray-200 dark:border-white/10 hover:border-gray-300 dark:hover:border-white/20'">
                                <input type="radio" name="link_type" value="custom" x-model="linkType" class="sr-only">
                                <span class="flex flex-1 text-sm font-medium text-gray-900 dark:text-white">Custom URL</span>
                            </label>
                        </div>
                    </div>

                    {{-- Predefined route --}}
                    <div x-show="linkType === 'predefined'" x-transition class="space-y-2">
                        <label for="predefined_route" class="block text-sm/6 font-medium text-gray-900 dark:text-white">Select route</label>
                        <select id="predefined_route" x-model="selectedRoute" @change="updateUrl()"
                                class="w-full rounded-lg border border-gray-300 dark:border-white/10 bg-white dark:bg-white/5 px-3 py-2 text-sm text-gray-900 dark:text-white focus:ring-2 focus:ring-[var(--color-accent)]">
                            <option value="">Choose a page…</option>
                            @if(isset($availableRoutes))
                                @foreach($availableRoutes as $name => $url)
                                    <option value="{{ $url }}">{{ $name }}</option>
                                @endforeach
                            @endif
                        </select>
                        <p class="text-xs text-gray-500 dark:text-gray-400">Select from available site pages</p>
                    </div>

                    {{-- System content --}}
                    <div x-show="linkType === 'system'" x-transition class="space-y-2">
                        <label for="system_content" class="block text-sm/6 font-medium text-gray-900 dark:text-white">Select content</label>
                        <select id="system_content" x-model="selectedSystemContent" @change="updateUrl()"
                                class="w-full rounded-lg border border-gray-300 dark:border-white/10 bg-white dark:bg-white/5 px-3 py-2 text-sm text-gray-900 dark:text-white focus:ring-2 focus:ring-[var(--color-accent)]">
                            <option value="">Choose content…</option>
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
                        <p class="text-xs text-gray-500 dark:text-gray-400">Pages, blog, solutions, or services</p>
                    </div>

                    {{-- Custom URL --}}
                    <div x-show="linkType === 'custom'" x-transition class="space-y-2">
                        <label for="custom_url" class="block text-sm/6 font-medium text-gray-900 dark:text-white">Custom URL</label>
                        <input type="text" id="custom_url" x-model="customUrl" @input="updateUrl()"
                               placeholder="/custom-page or https://example.com"
                               class="w-full rounded-lg border border-gray-300 dark:border-white/10 bg-white dark:bg-white/5 px-3 py-2 text-sm text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400 focus:ring-2 focus:ring-[var(--color-accent)]">
                        <p class="text-xs text-gray-500 dark:text-gray-400">Relative path or full URL</p>
                    </div>

                    <input type="hidden" name="url" id="url" x-model="finalUrl">

                    {{-- URL preview --}}
                    <div x-show="finalUrl" x-transition class="rounded-lg border border-gray-200 dark:border-white/10 bg-gray-50 dark:bg-white/5 p-3">
                        <p class="text-sm text-gray-600 dark:text-gray-400">
                            <span class="font-medium text-gray-900 dark:text-white">URL:</span>
                            <span x-text="getDisplayUrl()" class="font-mono text-[var(--color-accent)] break-all"></span>
                        </p>
                    </div>

                    @error('url')
                        <p class="text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>

        {{-- Form actions --}}
        <div class="mt-8 flex flex-col sm:flex-row items-center justify-end gap-4 border-t border-gray-200 dark:border-white/10 pt-6">
            <a href="{{ route('admin.settings.footer-links.index') }}" class="text-sm/6 font-semibold text-gray-900 dark:text-white hover:text-gray-600 dark:hover:text-gray-300">Cancel</a>
            <button type="submit" class="inline-flex items-center gap-2 rounded-md bg-[var(--color-accent)] px-4 py-2 text-sm font-semibold text-white shadow-xs hover:opacity-90 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-[var(--color-accent)]">
                <i class="fa-solid fa-check"></i>
                Update footer link
            </button>
        </div>
    </form>

    <script>
    function footerLinkEditForm() {
        const currentUrl = '{{ $footerLink->url }}';
        const availableRoutes = @json($availableRoutes ?? []);
        const systemContent = @json($systemContent ?? []);

        let initialLinkType = 'custom';
        let initialSelectedRoute = '';
        let initialSelectedSystemContent = '';
        let initialCustomUrl = currentUrl;

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
            finalUrl: currentUrl,

            init() { this.updateUrl(); },

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
                if (this.finalUrl.startsWith('http://') || this.finalUrl.startsWith('https://')) return this.finalUrl;
                if (this.finalUrl.startsWith('/')) return window.location.origin + this.finalUrl;
                return this.finalUrl;
            }
        };
    }
    </script>
</x-layouts.admin>
