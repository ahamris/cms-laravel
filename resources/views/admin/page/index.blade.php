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
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-zinc-900 dark:text-white mb-2">Pages</h1>
                <p class="text-zinc-600 dark:text-zinc-400">Manage website pages and content</p>
            </div>
            <div class="flex items-center gap-3">
                <x-button variant="primary" icon="plus" icon-position="left" href="{{ route('admin.page.create') }}">
                    Add Page
                </x-button>
            </div>
        </div>

        {{-- Pages Table --}}
        <livewire:admin.table
            resource="page"
            :columns="[
                ['key' => 'image', 'label' => 'Image', 'type' => 'custom', 'view' => 'admin.page.partials.image-column'],
                ['key' => 'title', 'label' => 'Title', 'type' => 'custom', 'view' => 'admin.page.partials.title-column'],
                ['key' => 'slug', 'label' => 'Slug', 'type' => 'custom', 'view' => 'admin.page.partials.slug-column'],
                ['key' => 'template', 'label' => 'Template', 'type' => 'custom', 'view' => 'admin.page.partials.template-column'],
                ['key' => 'is_homepage', 'label' => 'Home', 'type' => 'custom', 'view' => 'admin.page.partials.homepage-column'],
                ['key' => 'created_at', 'label' => 'Created', 'format' => 'date'],
                ['key' => 'is_active', 'label' => 'Status', 'type' => 'toggle'],
            ]"
            route-prefix="admin.page"
            search-placeholder="Search pages..."
            :template-filter-options="$templateFilterOptions"
            :status-filter-options="['' => __('All statuses'), 'active' => __('Active'), 'inactive' => __('Inactive')]"
            :home-filter-options="['' => __('All'), 'yes' => __('Homepage'), 'no' => __('Not homepage')]"
            :paginate="15"
            custom-actions-view="admin.page.partials.table-actions"
            :search-fields="['title', 'slug']"
        />
    </div>

</x-layouts.admin>
