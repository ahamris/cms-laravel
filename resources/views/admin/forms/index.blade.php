<x-layouts.admin title="Forms">
    <div class="space-y-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="font-bold text-zinc-900 dark:text-white mb-2">Forms</h1>
                <p class="text-zinc-600 dark:text-zinc-400">Manage dynamic forms and view submissions</p>
            </div>
            <a href="{{ route('admin.form.create') }}"
                class="inline-flex items-center gap-2 rounded-md bg-[var(--color-accent)] px-4 py-2 font-semibold text-white shadow-xs hover:opacity-90 transition-opacity">
                <i class="fa-solid fa-plus"></i>
                Create Form
            </a>
        </div>

        <livewire:admin.table resource="form" :columns="[
            'id',
            'name',
            'slug',
            ['key' => 'type', 'label' => 'Type'],
            ['key' => 'is_active', 'type' => 'toggle'],
            ['key' => 'created_at', 'format' => 'date'],
        ]" route-prefix="admin.form"
            search-placeholder="Search forms..." :paginate="15"
            :search-fields="['name', 'slug', 'type']" />
    </div>
</x-layouts.admin>
