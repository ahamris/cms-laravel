<x-layouts.admin title="Sticky Menu Items">
    <div class="space-y-6">
        {{-- Page Header --}}
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-zinc-900 dark:text-white mb-2">Sticky Menu Items</h1>
                <p class="text-zinc-600 dark:text-zinc-400">Manage the items in the sticky menu</p>
            </div>
            <div class="flex items-center gap-3">
                <x-ui.button variant="primary" icon="plus" icon-position="left" href="{{ route('admin.content.sticky-menu-item.create') }}">
                    New Item
                </x-ui.button>
            </div>
        </div>

        <livewire:admin.table
            :resource="\App\Models\StickyMenuItem::class"
            :columns="[
                ['key' => 'title', 'label' => 'Item', 'type' => 'custom', 'view' => 'admin.content.sticky-menu-item.partials.title-column'],
                ['key' => 'link', 'label' => 'Link', 'type' => 'custom', 'view' => 'admin.content.sticky-menu-item.partials.link-column'],
                ['key' => 'link_type', 'label' => 'Type', 'type' => 'custom', 'view' => 'admin.content.sticky-menu-item.partials.type-column'],
                ['key' => 'is_active', 'label' => 'Status', 'type' => 'toggle'],
                ['key' => 'sort_order', 'label' => 'Order'],
            ]"
            route-prefix="admin.content.sticky-menu-item"
            :search-fields="['title', 'link']"
            :paginate="15"
        />
    </div>
</x-layouts.admin>