<x-layouts.admin title="Pages">
    @php
        $pageTemplates = config('page_templates.templates', []);
        $templateFilterOptions = ['' => __('All templates')];
        foreach ($pageTemplates as $key => $config) {
            $templateFilterOptions[$key] = $config['label'] ?? $key;
        }
        // Ensure "default" always exists as a sensible fallback.
        $defaultTemplateKey = config('page_templates.default', 'default');
        $templateFilterOptions[$defaultTemplateKey] = $templateFilterOptions[$defaultTemplateKey] ?? $defaultTemplateKey;
    @endphp
    <div class="space-y-6">
        {{-- Page Header --}}
        <div class="flex flex-wrap items-end justify-between gap-3">
            <div class="flex min-w-0 flex-wrap items-baseline gap-3">
                <h1 class="text-2xl font-normal tracking-tight text-zinc-900 dark:text-white">{{ __('Pages') }}</h1>
                <x-ui.button variant="secondary" size="sm" href="{{ route('admin.page.create') }}">
                    {{ __('Add page') }}
                </x-ui.button>
            </div>
            <p class="w-full text-[12.5px] text-zinc-600 dark:text-zinc-400 sm:w-auto">{{ __('Manage website pages and content') }}</p>
        </div>

        {{-- Pages Table --}}
        <livewire:admin.table
            resource="page"
            :columns="[
                ['key' => 'image', 'label' => __('Image'), 'type' => 'custom', 'view' => 'admin.page.partials.image-column'],
                ['key' => 'title', 'label' => __('Title'), 'type' => 'custom', 'view' => 'admin.page.partials.title-column'],
                ['key' => 'slug', 'label' => __('Slug'), 'type' => 'custom', 'view' => 'admin.page.partials.slug-column'],
                ['key' => 'template', 'label' => __('Template'), 'type' => 'custom', 'view' => 'admin.page.partials.template-column'],
                ['key' => 'is_homepage', 'label' => __('Home'), 'type' => 'custom', 'view' => 'admin.page.partials.homepage-column'],
                ['key' => 'created_at', 'label' => __('Date'), 'format' => 'date'],
                ['key' => 'is_active', 'label' => __('Published'), 'type' => 'toggle'],
            ]"
            route-prefix="admin.page"
            search-placeholder="{{ __('Search pages…') }}"
            :template-filter-options="$templateFilterOptions"
            :status-filter-options="['' => __('All'), 'active' => __('Published'), 'inactive' => __('Draft')]"
            :home-filter-options="['' => __('All'), 'yes' => __('Homepage'), 'no' => __('Not homepage')]"
            :paginate="15"
            word-press-list-style="true"
            hide-actions-column="true"
            :search-fields="['title', 'slug']"
            entity-count-label="pages"
            empty-state-title="No pages found"
            :empty-cta-url="route('admin.page.create')"
            empty-cta-label="Add page"
        />
    </div>

</x-layouts.admin>
