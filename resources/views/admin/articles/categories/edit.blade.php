<x-layouts.admin title="Edit Article Category">
    <div class="space-y-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="font-bold text-zinc-900 dark:text-white mb-2">Edit Article Category</h1>
                <p class="text-zinc-600 dark:text-zinc-400">Update category: {{ $articleCategory->name }}</p>
            </div>
            <a href="{{ route('admin.article-category.index') }}"
                class="inline-flex items-center gap-2 rounded-md border border-zinc-300 dark:border-zinc-600 px-4 py-2 font-semibold text-zinc-700 dark:text-zinc-300 hover:bg-zinc-50 dark:hover:bg-zinc-700 transition-colors">
                <i class="fa-solid fa-arrow-left"></i>
                Back
            </a>
        </div>

        <div class="bg-white dark:bg-zinc-800 rounded-xl shadow-sm border border-zinc-200 dark:border-zinc-700 p-6">
            <form action="{{ route('admin.article-category.update', $articleCategory) }}" method="POST" class="space-y-6">
                @csrf
                @method('PUT')
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <x-form.form-field name="name" label="Name" :value="$articleCategory->name" required />
                    <x-form.form-field name="slug" label="Slug" :value="$articleCategory->slug" />
                    <x-form.form-field name="color" label="Color" type="color" :value="$articleCategory->color" />
                    <x-form.form-field name="icon" label="Icon" :value="$articleCategory->icon" placeholder="e.g. fa-folder" />
                    <div class="md:col-span-2">
                        <x-form.form-field name="description" label="Description" type="textarea" :value="$articleCategory->description" />
                    </div>
                    <x-form.form-field name="parent_id" label="Parent Category" type="select" :options="$parents->pluck('name', 'id')->toArray()" :value="$articleCategory->parent_id" placeholder="None (root category)" />
                    <x-form.form-field name="sort_order" label="Sort Order" type="number" :value="$articleCategory->sort_order" />
                </div>
                <div class="flex items-center gap-4">
                    <label class="flex items-center gap-2">
                        <input type="checkbox" name="is_active" value="1" {{ $articleCategory->is_active ? 'checked' : '' }} class="rounded border-zinc-300 dark:border-zinc-600">
                        <span class="text-zinc-700 dark:text-zinc-300">Active</span>
                    </label>
                </div>
                <div class="flex justify-end gap-3">
                    <button type="submit" name="submit_action" value="edit"
                        class="inline-flex items-center gap-2 rounded-md border border-zinc-300 dark:border-zinc-600 px-6 py-2 font-semibold text-zinc-700 dark:text-zinc-300 hover:bg-zinc-50 dark:hover:bg-zinc-700 transition-colors">
                        Save
                    </button>
                    <button type="submit" name="submit_action" value="index"
                        class="inline-flex items-center gap-2 rounded-md bg-[var(--color-accent)] px-6 py-2 font-semibold text-white shadow-xs hover:opacity-90 transition-opacity">
                        Save & close
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-layouts.admin>
