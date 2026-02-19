@php
    $bannerEnabled = get_setting('cookie_banner_enabled', '1') == '1';
@endphp

@if($bannerEnabled && !empty($categories))
@once
    @push('styles')
        <style>[x-cloak]{display:none!important;}</style>
    @endpush

    @push('scripts')
        <script>
            document.addEventListener('alpine:init', () => {
                const baseCategories = @json($categories);

                Alpine.data('gdprBanner', () => ({
                    visible: false,
                    showTrigger: false,
                    view: 'intro',
                    categories: JSON.parse(JSON.stringify(baseCategories)),
                    init() {
                        const stored = localStorage.getItem('gdprConsent');
                        if (stored) {
                            try {
                                const parsed = JSON.parse(stored);
                                this.categories = this.categories.map(category => ({
                                    ...category,
                                    enabled: parsed[category.key] ?? category.enabled,
                                }));
                                this.visible = false;
                                this.showTrigger = true;
                            } catch (error) {
                                this.visible = true;
                                this.showTrigger = false;
                            }
                        } else {
                            this.visible = true;
                            this.showTrigger = false;
                        }
                    },
                    persist() {
                        const consent = this.categories.reduce((acc, category) => {
                            acc[category.key] = category.enabled;
                            return acc;
                        }, {});
                        localStorage.setItem('gdprConsent', JSON.stringify(consent));
                        this.visible = false;
                        this.showTrigger = true;
                    },
                    acceptAll() {
                        this.categories = this.categories.map(category => ({ ...category, enabled: true }));
                        this.persist();
                    },
                    acceptEssential() {
                        this.categories = this.categories.map(category => ({ ...category, enabled: category.locked }));
                        const consent = this.categories.reduce((acc, category) => {
                            acc[category.key] = category.enabled;
                            return acc;
                        }, {});
                        localStorage.setItem('gdprConsent', JSON.stringify(consent));
                        this.view = 'preferences';
                    },
                    savePreferences() {
                        this.persist();
                    },
                    toggle(item) {
                        if (item.locked) return;
                        item.enabled = !item.enabled;
                    },
                    openPreferences() {
                        this.view = 'preferences';
                        this.visible = true;
                        this.showTrigger = false;
                    },
                    closeOverlay() {
                        this.visible = false;
                        this.view = 'intro';
                        this.showTrigger = true;
                    }
                }));
            });
        </script>
    @endpush
@endonce

<div x-data="gdprBanner" x-cloak>
    <div
        x-show="visible"
        class="fixed inset-0 z-[9999] grid place-items-center bg-slate-900/60 backdrop-blur-sm p-4"
    >
    <template x-if="view === 'intro'">
        <div class="w-full max-w-lg rounded-md border border-primary/15 bg-white shadow-sm">
            <div class="flex items-center gap-3 border-b border-primary/10 px-6 py-5">
                <div class="flex h-12 w-12 items-center justify-center rounded-full bg-primary/10">
                    <i class="fa-solid fa-cookie-bite text-primary text-xl"></i>
                </div>
                <div>
                    <p class="text-xs uppercase tracking-wide text-primary/70 font-semibold">{{ __('frontend.gdpr.cookies_privacy') }}</p>
                    <p class="text-xl font-semibold text-primary">{{ $introTitle }}</p>
                </div>
            </div>

            <div class="px-6 py-5 space-y-4 text-slate-700 text-sm leading-6">
                <p>
                    {{ $introSummary }}
                </p>
                <p>
                    {{ __('frontend.gdpr.read_more_in_our') }}
                    <a x-ref="settingsLink" href="{{ $settingsUrl }}" class="underline underline-offset-4" style="color: var(--color-secondary)">
                        {{ $settingsLabel }}
                    </a>.
                </p>
            </div>

            <div class="flex flex-col gap-3 border-t border-slate-200 px-6 py-5 md:flex-row md:items-center md:justify-end">
                <button
                    type="button"
                    class="w-full md:w-auto rounded-md border border-primary/20 px-4 py-2 text-sm font-medium text-primary hover:bg-primary/5 transition focus:outline-none focus:ring-2 focus:ring-primary/30 focus:ring-offset-2 focus:ring-offset-white"
                    @click="openPreferences()"
                >
                    {{ __('frontend.gdpr.manage_preferences') }}
                </button>
                <button
                    type="button"
                    class="w-full md:w-auto rounded-md px-4 py-2 text-sm font-semibold text-white transition focus:outline-none focus:ring-2 focus:ring-primary/40 focus:ring-offset-2 focus:ring-offset-white"
                    style="background-color: var(--color-primary)"
                    @click="acceptAll()"
                >
                    {{ __('frontend.gdpr.accept_all') }}
                </button>
            </div>
        </div>
    </template>

    <template x-if="view === 'preferences'">
        <div class="w-full max-w-lg rounded-md border border-primary/15 bg-white shadow-sm">
            <div class="flex items-center gap-3 border-b border-primary/10 px-6 py-5">
                <button
                    type="button"
                    class="flex h-8 w-8 items-center justify-center rounded-full border border-primary/20 text-primary hover:bg-primary/5 transition"
                    @click="view = 'intro'"
                    aria-label="{{ __('frontend.gdpr.back') }}"
                >
                    <i class="fa-solid fa-chevron-left text-sm"></i>
                </button>
                <div>
                    <p class="text-xs uppercase tracking-wide text-primary/70 font-semibold">{{ __('frontend.gdpr.preferences') }}</p>
                    <p class="text-xl font-semibold text-primary">{{ $preferencesTitle }}</p>
                </div>
            </div>

            <div class="px-6 py-5 space-y-4 text-slate-700 text-sm leading-6">
                <p>
                    {{ $preferencesSummary }}
                    <a :href="$refs.settingsLink?.href ?? '#'" class="underline underline-offset-4" style="color: var(--color-secondary)">
                        {{ $settingsLabel }}
                    </a>.
                </p>

                <div class="space-y-4">
                    <template x-for="item in categories" :key="item.key">
                        <div class="rounded-md border border-primary/10 bg-primary/5 px-4 py-3">
                            <div class="flex items-start justify-between gap-4">
                                <div>
                                    <p class="text-sm font-semibold text-primary" x-text="item.label"></p>
                                    <p class="mt-1 text-xs leading-5 text-slate-600" x-text="item.description"></p>
                                </div>
                                <button
                                    type="button"
                                    class="relative inline-flex h-6 w-11 flex-shrink-0 rounded-full border-2 border-transparent transition duration-200 focus:outline-none focus:ring-2 focus:ring-primary/40 focus:ring-offset-2 focus:ring-offset-white"
                                    :class="item.enabled ? 'bg-primary' : 'bg-slate-200'"
                                    @click="toggle(item)"
                                    :disabled="item.locked"
                                >
                                    <span
                                        aria-hidden="true"
                                        class="inline-block h-5 w-5 transform rounded-full bg-white shadow ring-0 transition duration-200"
                                        :class="item.enabled ? 'translate-x-5' : 'translate-x-0'"
                                    ></span>
                                </button>
                            </div>
                        </div>
                    </template>
                </div>
            </div>

            <div class="flex flex-col gap-3 border-t border-slate-200 px-6 py-5 md:flex-row md:items-center md:justify-end">
                <button
                    type="button"
                    class="w-full md:w-auto rounded-md border border-primary/20 px-4 py-2 text-sm font-medium text-primary hover:bg-primary/5 transition focus:outline-none focus:ring-2 focus:ring-primary/30 focus:ring-offset-2 focus:ring-offset-white"
                    @click="acceptEssential()"
                >
                    {{ __('frontend.gdpr.functional_only') }}
                </button>
                <button
                    type="button"
                    class="w-full md:w-auto rounded-md px-4 py-2 text-sm font-semibold text-white transition focus:outline-none focus:ring-2 focus:ring-primary/40 focus:ring-offset-2 focus:ring-offset-white"
                    style="background-color: var(--color-primary)"
                    @click="savePreferences()"
                >
                    {{ __('frontend.gdpr.save_preferences') }}
                </button>
            </div>
        </div>
    </template>
    </div>

    <div
        x-show="showTrigger"
        x-transition.opacity
        class="fixed bottom-6 left-6 z-[9998]"
    >
    <button
        type="button"
        class="flex items-center gap-2 rounded-full border border-white bg-white px-3 py-3 text-lg font-semibold text-primary shadow-md shadow-primary/20 hover:shadow-primary/40 transition-shadow duration-200"
        @click="openPreferences()"
    >
        <i class="fa-solid fa-cookie-bite text-primary"></i>
    </button>
    </div>
</div>
@endif