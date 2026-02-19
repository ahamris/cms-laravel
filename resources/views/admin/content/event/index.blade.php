<x-layouts.admin title="Events">
    <div class="space-y-6">
        {{-- Header --}}
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-zinc-900 dark:text-white mb-2">Events</h1>
                <p class="text-zinc-600 dark:text-zinc-400">Manage events and activities</p>
            </div>
            <x-button variant="primary" icon="plus" icon-position="left" href="{{ route('admin.content.event.create') }}">
                Add Event
            </x-button>
        </div>

        {{-- Events Table --}}
        <livewire:admin.table
            resource="event"
            :columns="[
                ['key' => 'cover_image', 'label' => 'Image', 'type' => 'custom', 'view' => 'admin.content.event.partials.image-column'],
                ['key' => 'title', 'label' => 'Title', 'type' => 'custom', 'view' => 'admin.content.event.partials.title-column'],
                ['key' => 'location', 'label' => 'Location', 'type' => 'custom', 'view' => 'admin.content.event.partials.location-column'],
                ['key' => 'start_date', 'label' => 'Start Date', 'type' => 'custom', 'view' => 'admin.content.event.partials.start-date-column'],
                ['key' => 'user.name', 'label' => 'Organizer', 'type' => 'custom', 'view' => 'admin.content.event.partials.organizer-column'],
                ['key' => 'price', 'label' => 'Price', 'type' => 'custom', 'view' => 'admin.content.event.partials.price-column'],
                ['key' => 'is_active', 'label' => 'Status', 'type' => 'toggle'],
            ]"
            route-prefix="admin.content.event"
            search-placeholder="Search events..."
            :paginate="15"
            custom-actions-view="admin.content.event.partials.table-actions"
            :search-fields="['title', 'slug', 'location', 'address']"
            sort-field="start_date"
            sort-direction="desc"
        />
    </div>
</x-layouts.admin>
