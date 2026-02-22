@php
    $availableRoutes = $availableRoutes ?? [];
    $systemContent = $systemContent ?? [];
    $pages = $pages ?? [];
@endphp

@once
    @push('scripts')
    <script>
    (function() {
        if (window.URL_SELECTOR_ROUTES === undefined) {
            window.URL_SELECTOR_ROUTES = @json($availableRoutes);
            window.URL_SELECTOR_SYSTEM = @json($systemContent);
            window.URL_SELECTOR_PAGES = @json($pages);
        }
    })();
    </script>
@endpush
@endonce

@once
    @push('scripts')
    <script>
    document.addEventListener('alpine:init', function() {
        Alpine.data('urlSelector', function(config) {
            const routes = window.URL_SELECTOR_ROUTES || {};
            const system = window.URL_SELECTOR_SYSTEM || {};
            const pages = window.URL_SELECTOR_PAGES || [];
            const initial = (config.initialValue || '').trim();

            let linkType = 'custom';
            let selectedPageId = '';
            let selectedRoute = '';
            let selectedSystemContent = '';
            let customUrl = initial;

            if (initial) {
                const pageMatch = pages.find(p => p.url === initial);
                if (pageMatch) {
                    linkType = 'page';
                    selectedPageId = String(pageMatch.id);
                } else {
                    if (Object.values(routes).includes(initial)) {
                        linkType = 'predefined';
                        selectedRoute = initial;
                    } else {
                        let found = false;
                        for (const [, items] of Object.entries(system)) {
                            for (const item of items) {
                                if (item.url === initial) {
                                    linkType = 'system';
                                    selectedSystemContent = initial;
                                    found = true;
                                    break;
                                }
                            }
                            if (found) break;
                        }
                        if (!found) customUrl = initial;
                    }
                }
            }

            return {
                inputName: config.inputName,
                id: config.id,
                linkType,
                selectedPageId,
                selectedRoute,
                selectedSystemContent,
                customUrl,
                finalUrl: initial,
                routes,
                system,
                pages,

                init() {
                    this.updateUrl();
                },

                updateUrl() {
                    if (this.linkType === 'page') {
                        const page = this.pages.find(p => String(p.id) === this.selectedPageId);
                        this.finalUrl = page ? page.url : '';
                        this.customUrl = '';
                        this.selectedRoute = '';
                        this.selectedSystemContent = '';
                    } else if (this.linkType === 'predefined') {
                        this.finalUrl = this.selectedRoute;
                        this.customUrl = '';
                        this.selectedSystemContent = '';
                        this.selectedPageId = '';
                    } else if (this.linkType === 'system') {
                        this.finalUrl = this.selectedSystemContent;
                        this.customUrl = '';
                        this.selectedRoute = '';
                        this.selectedPageId = '';
                    } else {
                        this.finalUrl = this.customUrl;
                        this.selectedRoute = '';
                        this.selectedSystemContent = '';
                        this.selectedPageId = '';
                    }
                }
            };
        });
    });
    </script>
    @endpush
@endonce

<div class="space-y-3" x-data="urlSelector({ inputName: @js($name), initialValue: @js($value ?? ''), id: @js($id) })" x-init="init()">
    @if($label)
        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ $label }}</label>
    @endif
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-2">
        <label class="flex items-center gap-2 cursor-pointer">
            <input type="radio" x-model="linkType" value="page" @change="updateUrl()" class="w-4 h-4 text-primary border-gray-300 focus:ring-primary rounded">
            <span class="text-sm text-gray-700 dark:text-gray-300">Page</span>
        </label>
        <label class="flex items-center gap-2 cursor-pointer">
            <input type="radio" x-model="linkType" value="predefined" @change="updateUrl()" class="w-4 h-4 text-primary border-gray-300 focus:ring-primary rounded">
            <span class="text-sm text-gray-700 dark:text-gray-300">Predefined</span>
        </label>
        <label class="flex items-center gap-2 cursor-pointer">
            <input type="radio" x-model="linkType" value="system" @change="updateUrl()" class="w-4 h-4 text-primary border-gray-300 focus:ring-primary rounded">
            <span class="text-sm text-gray-700 dark:text-gray-300">System</span>
        </label>
        <label class="flex items-center gap-2 cursor-pointer">
            <input type="radio" x-model="linkType" value="custom" @change="updateUrl()" class="w-4 h-4 text-primary border-gray-300 focus:ring-primary rounded">
            <span class="text-sm text-gray-700 dark:text-gray-300">Custom URL</span>
        </label>
    </div>

    <div x-show="linkType === 'page'" x-transition class="space-y-1">
        <select x-model="selectedPageId" @change="updateUrl()"
                class="block w-full border border-gray-300 dark:border-white/10 rounded-md bg-white dark:bg-zinc-800 px-3 py-1.5 text-sm focus:ring-primary focus:border-primary">
            <option value="">Choose a page...</option>
            @foreach($pages as $page)
                <option value="{{ $page['id'] }}" data-url="{{ $page['url'] ?? '' }}">{{ $page['title'] }} ({{ $page['slug'] ?? '' }})</option>
            @endforeach
        </select>
    </div>

    <div x-show="linkType === 'predefined'" x-transition class="space-y-1">
        <select x-model="selectedRoute" @change="updateUrl()"
                class="block w-full border border-gray-300 dark:border-white/10 rounded-md bg-white dark:bg-zinc-800 px-3 py-1.5 text-sm focus:ring-primary focus:border-primary">
            <option value="">Choose a route...</option>
            @foreach($availableRoutes as $routeName => $routeUrl)
                <option value="{{ $routeUrl }}">{{ $routeName }}</option>
            @endforeach
        </select>
    </div>

    <div x-show="linkType === 'system'" x-transition class="space-y-1">
        <select x-model="selectedSystemContent" @change="updateUrl()"
                class="block w-full border border-gray-300 dark:border-white/10 rounded-md bg-white dark:bg-zinc-800 px-3 py-1.5 text-sm focus:ring-primary focus:border-primary">
            <option value="">Choose content...</option>
            @foreach($systemContent as $category => $items)
                <optgroup label="{{ $category }}">
                    @foreach($items as $item)
                        <option value="{{ $item['url'] }}">{{ $item['title'] }}</option>
                    @endforeach
                </optgroup>
            @endforeach
        </select>
    </div>

    <div x-show="linkType === 'custom'" x-transition class="space-y-1">
        <input type="text" x-model="customUrl" @input="updateUrl()"
               placeholder="/path or https://..."
               class="block w-full border border-gray-300 dark:border-white/10 rounded-md bg-white dark:bg-zinc-800 px-3 py-1.5 text-sm focus:ring-primary focus:border-primary">
    </div>

    <input type="hidden" :name="inputName" x-model="finalUrl">
</div>
