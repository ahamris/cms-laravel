<x-layouts.admin title="Tags">
    <div class="space-y-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="font-bold text-zinc-900 dark:text-white mb-2">Tags</h1>
                <p class="text-zinc-600 dark:text-zinc-400">Manage tags used across articles and pages</p>
            </div>
        </div>

        {{-- Quick Add --}}
        <div class="bg-white dark:bg-zinc-800 rounded-xl shadow-sm border border-zinc-200 dark:border-zinc-700 p-4">
            <form action="{{ route('admin.tag.store') }}" method="POST" class="flex items-end gap-4">
                @csrf
                <div class="flex-1">
                    <label class="block font-medium text-zinc-700 dark:text-zinc-300 mb-1">Tag Name</label>
                    <input type="text" name="name" required placeholder="Enter tag name..."
                        class="w-full rounded-md border-zinc-300 dark:border-zinc-600 dark:bg-zinc-700 dark:text-white shadow-sm focus:border-[var(--color-accent)] focus:ring-[var(--color-accent)]">
                </div>
                <div class="w-40">
                    <label class="block font-medium text-zinc-700 dark:text-zinc-300 mb-1">Type</label>
                    <input type="text" name="type" placeholder="topic, tech..."
                        class="w-full rounded-md border-zinc-300 dark:border-zinc-600 dark:bg-zinc-700 dark:text-white shadow-sm focus:border-[var(--color-accent)] focus:ring-[var(--color-accent)]">
                </div>
                <button type="submit"
                    class="inline-flex items-center gap-2 rounded-md bg-[var(--color-accent)] px-4 py-2 font-semibold text-white shadow-xs hover:opacity-90 transition-opacity">
                    <i class="fa-solid fa-plus"></i>
                    Add Tag
                </button>
            </form>
        </div>

        <livewire:admin.table resource="tag" :columns="[
            'id',
            'name',
            'slug',
            ['key' => 'type', 'label' => 'Type'],
            ['key' => 'created_at', 'format' => 'date'],
        ]" route-prefix="admin.tag"
            search-placeholder="Search tags..." :paginate="25"
            :search-fields="['name', 'slug']" />
    </div>
</x-layouts.admin>
