<x-layouts.admin title="Create Article Category">
    <div class="space-y-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="font-bold text-zinc-900 dark:text-white mb-2">Create Article Category</h1>
                <p class="text-zinc-600 dark:text-zinc-400">Add a new category for articles</p>
            </div>
            <a href="{{ route('admin.article-category.index') }}"
                class="inline-flex items-center gap-2 rounded-md border border-zinc-300 dark:border-zinc-600 px-4 py-2 font-semibold text-zinc-700 dark:text-zinc-300 hover:bg-zinc-50 dark:hover:bg-zinc-700 transition-colors">
                <i class="fa-solid fa-arrow-left"></i>
                Back
            </a>
        </div>

        <div class="bg-white dark:bg-zinc-800 rounded-xl shadow-sm border border-zinc-200 dark:border-zinc-700 p-6">
            <form action="{{ route('admin.article-category.store') }}" method="POST" class="space-y-6">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <x-form.form-field name="name" label="Name" required />
                    <x-form.form-field name="slug" label="Slug" helper="Leave empty to auto-generate" />
                    <x-form.form-field name="color" label="Color" type="color" />
                    <x-form.form-field name="icon" label="Icon" placeholder="e.g. fa-folder" />
                    <div class="md:col-span-2">
                        <x-form.form-field name="description" label="Description" type="textarea" />
                    </div>
                    <x-form.form-field name="parent_id" label="Parent Category" type="select" :options="$parents->pluck('name', 'id')->toArray()" placeholder="None (root category)" />
                    <x-form.form-field name="sort_order" label="Sort Order" type="number" value="0" />
                </div>
                <div class="flex items-center gap-4">
                    <label class="flex items-center gap-2">
                        <input type="checkbox" name="is_active" value="1" checked class="rounded border-zinc-300 dark:border-zinc-600">
                        <span class="text-zinc-700 dark:text-zinc-300">Active</span>
                    </label>
                </div>
                <div class="flex justify-end">
                    <button type="submit"
                        class="inline-flex items-center gap-2 rounded-md bg-[var(--color-accent)] px-6 py-2 font-semibold text-white shadow-xs hover:opacity-90 transition-opacity">
                        <i class="fa-solid fa-check"></i>
                        Create Category
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-layouts.admin>
