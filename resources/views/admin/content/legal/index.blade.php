<x-layouts.admin title="Legal Pages">
    <div class="space-y-6">
        {{-- Page Header --}}
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-zinc-900 dark:text-white mb-2">Legal Pages</h1>
                <p class="text-zinc-600 dark:text-zinc-400">Manage static pages like Privacy Policy, Terms of Service, etc.</p>
            </div>
            <div class="flex items-center gap-3">
                <x-button variant="primary" icon="plus" icon-position="left" href="{{ route('admin.content.legal.create') }}">
                    Add Legal Page
                </x-button>
            </div>
        </div>

        {{-- Legal Pages Table --}}
        <livewire:admin.table
            resource="legal"
            :columns="[
                'title',
                'slug',
                ['key' => 'is_active', 'label' => 'Status', 'type' => 'toggle'],
                ['key' => 'created_at', 'label' => 'Created', 'format' => 'date'],
            ]"
            route-prefix="admin.content.legal"
            search-placeholder="Search legal pages..."
            :paginate="15"
            custom-actions-view="admin.content.legal.partials.table-actions"
            :search-fields="['title', 'slug']"
        />
    </div>
</x-layouts.admin>
