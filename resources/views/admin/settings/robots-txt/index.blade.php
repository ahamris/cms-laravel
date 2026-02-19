<x-layouts.admin title="Robots.txt Editor">
    {{-- Page Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-8">
        <div class="flex items-center gap-3">
            <div class="w-12 h-12 bg-[var(--color-accent)] rounded-md flex items-center justify-center">
                <i class="fa-solid fa-robot text-white text-xl"></i>
            </div>
            <div class="flex flex-col gap-1">
                <h1 class="text-3xl font-bold text-zinc-900 dark:text-white">Robots.txt Editor</h1>
                <p class="text-zinc-600 dark:text-zinc-400">Manage your robots.txt file content dynamically</p>
            </div>
        </div>
        <div class="flex flex-col sm:flex-row items-start sm:items-center gap-3">
            <form action="{{ route('admin.settings.robots-txt.clear-cache') }}" method="POST" class="inline">
                @csrf
                <x-button variant="outline-secondary" type="submit" icon="sync">
                    Clear Cache
                </x-button>
            </form>
            <form action="{{ route('admin.settings.robots-txt.reset') }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to reset to default content?')">
                @csrf
                <x-button variant="warning" type="submit" icon="undo">
                    Reset to Default
                </x-button>
            </form>
            <x-button variant="outline-secondary" :href="url('/robots.txt')" target="_blank" icon="external-link-alt">
                View Live
            </x-button>
        </div>
    </div>

    @if (session('success'))
        <div class="mb-6 rounded-lg bg-green-50 dark:bg-green-500/10 border border-green-200 dark:border-green-500/20 p-4">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <i class="fa-solid fa-check-circle text-green-500 text-lg"></i>
                </div>
                <div class="ml-3">
                    <p class="text-sm font-medium text-green-800 dark:text-green-300">
                        {{ session('success') }}
                    </p>
                </div>
            </div>
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        {{-- Left Column - 2/3 --}}
        <div class="lg:col-span-2 space-y-6">
            {{-- Info Card --}}
            <x-ui.card>
                <div class="flex gap-4">
                    <div class="flex-shrink-0">
                        <i class="fa-solid fa-info-circle text-blue-500 text-xl"></i>
                    </div>
                    <div>
                        <h3 class="text-sm font-semibold text-gray-900 dark:text-white mb-2">About Robots.txt</h3>
                        <div class="text-sm text-gray-600 dark:text-gray-400 space-y-2">
                            <p>The robots.txt file tells search engine crawlers which URLs they can access on your site. This is served dynamically from the database and cached for 24 hours.</p>
                            <p>
                                <strong class="text-gray-900 dark:text-white">Live URL:</strong> 
                                <a href="{{ url('/robots.txt') }}" target="_blank" class="text-[var(--color-accent)] hover:underline">{{ url('/robots.txt') }}</a>
                            </p>
                        </div>
                    </div>
                </div>
            </x-ui.card>

            {{-- Editor Form --}}
            <form method="POST" action="{{ route('admin.settings.robots-txt.update') }}">
                @csrf
                @method('PUT')

                <x-ui.card>
                    <div class="mb-6">
                        <h2 class="text-base/7 font-semibold text-gray-900 dark:text-white">Robots.txt Content</h2>
                        <p class="mt-1 text-sm/6 text-gray-600 dark:text-gray-400">Edit your robots.txt file content</p>
                    </div>

                    <div class="space-y-6">
                        <div>
                            <x-ui.textarea
                                id="content"
                                name="content"
                                :value="old('content', $robotsTxt->content)"
                                label="Robots.txt Content"
                                placeholder="User-agent: *&#10;Disallow: /admin"
                                :rows="20"
                                required
                                :error="!!$errors->has('content')"
                                :errorMessage="$errors->first('content')"
                                class="font-mono"
                            />
                            <p class="mt-2 text-xs text-gray-500 dark:text-gray-400">
                                <i class="fa-solid fa-lightbulb mr-1"></i>
                                Tip: Use "User-agent: *" to apply rules to all crawlers. Use "Disallow: /admin" to block specific paths.
                            </p>
                        </div>

                        {{-- Example Patterns --}}
                        <div class="rounded-lg border border-gray-200 dark:border-white/10 bg-gray-50 dark:bg-white/5 p-4">
                            <h4 class="text-sm font-semibold text-gray-900 dark:text-white mb-3">Common Patterns:</h4>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                                <div>
                                    <p class="font-mono text-gray-900 dark:text-white text-xs mb-1">User-agent: *</p>
                                    <p class="text-gray-600 dark:text-gray-400 text-xs">Allow all crawlers</p>
                                </div>
                                <div>
                                    <p class="font-mono text-gray-900 dark:text-white text-xs mb-1">Disallow: /admin</p>
                                    <p class="text-gray-600 dark:text-gray-400 text-xs">Block admin area</p>
                                </div>
                                <div>
                                    <p class="font-mono text-gray-900 dark:text-white text-xs mb-1">Allow: /</p>
                                    <p class="text-gray-600 dark:text-gray-400 text-xs">Allow all pages</p>
                                </div>
                                <div>
                                    <p class="font-mono text-gray-900 dark:text-white text-xs mb-1">Sitemap: {{ url('/sitemap.xml') }}</p>
                                    <p class="text-gray-600 dark:text-gray-400 text-xs">Link to sitemap</p>
                                </div>
                            </div>
                        </div>

                        {{-- Actions --}}
                        <div class="flex justify-end pt-4 border-t border-gray-200 dark:border-white/10">
                            <x-button variant="primary" type="submit" icon="save">
                                Save Changes
                            </x-button>
                        </div>
                    </div>
                </x-ui.card>
            </form>
        </div>

        {{-- Right Column - 1/3 --}}
        <div class="space-y-6">
            {{-- Current Live Content Preview --}}
            <x-ui.card>
                <div class="mb-4">
                    <h3 class="text-base font-semibold text-gray-900 dark:text-white mb-1">Current Live Content</h3>
                    <p class="text-xs text-gray-600 dark:text-gray-400">Cached version</p>
                </div>
                <div class="rounded-lg border border-gray-200 dark:border-white/10 bg-gray-50 dark:bg-white/5 p-4 overflow-x-auto">
                    <pre class="text-xs font-mono text-gray-900 dark:text-gray-100 whitespace-pre-wrap">{{ \App\Models\RobotsTxt::getCachedContent() }}</pre>
                </div>
                <p class="mt-3 text-xs text-gray-500 dark:text-gray-400">
                    <i class="fa-solid fa-clock mr-1"></i>
                    This content is cached for 24 hours and automatically refreshes when you save changes.
                </p>
            </x-ui.card>
        </div>
    </div>
</x-layouts.admin>
