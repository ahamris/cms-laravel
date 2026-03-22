<x-layouts.admin title="Article Category: {{ $articleCategory->name }}">
    <div class="space-y-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="font-bold text-zinc-900 dark:text-white mb-2">{{ $articleCategory->name }}</h1>
                <p class="text-zinc-600 dark:text-zinc-400">{{ $articleCategory->articles_count ?? 0 }} articles in this category</p>
            </div>
            <div class="flex items-center gap-3">
                <a href="{{ route('admin.article-category.edit', $articleCategory) }}"
                    class="inline-flex items-center gap-2 rounded-md bg-[var(--color-accent)] px-4 py-2 font-semibold text-white shadow-xs hover:opacity-90 transition-opacity">
                    <i class="fa-solid fa-edit"></i>
                    Edit
                </a>
                <a href="{{ route('admin.article-category.index') }}"
                    class="inline-flex items-center gap-2 rounded-md border border-zinc-300 dark:border-zinc-600 px-4 py-2 font-semibold text-zinc-700 dark:text-zinc-300 hover:bg-zinc-50 dark:hover:bg-zinc-700 transition-colors">
                    <i class="fa-solid fa-arrow-left"></i>
                    Back
                </a>
            </div>
        </div>

        <div class="bg-white dark:bg-zinc-800 rounded-xl shadow-sm border border-zinc-200 dark:border-zinc-700 p-6">
            <dl class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div><dt class="font-medium text-zinc-500 dark:text-zinc-400">Name</dt><dd class="text-zinc-900 dark:text-white">{{ $articleCategory->name }}</dd></div>
                <div><dt class="font-medium text-zinc-500 dark:text-zinc-400">Slug</dt><dd class="text-zinc-900 dark:text-white">{{ $articleCategory->slug }}</dd></div>
                <div><dt class="font-medium text-zinc-500 dark:text-zinc-400">Color</dt><dd><span class="inline-block w-6 h-6 rounded" style="background-color: {{ $articleCategory->color ?? '#ccc' }}"></span></dd></div>
                <div><dt class="font-medium text-zinc-500 dark:text-zinc-400">Status</dt><dd>@if($articleCategory->is_active)<span class="text-green-600">Active</span>@else<span class="text-red-600">Inactive</span>@endif</dd></div>
                <div class="md:col-span-2"><dt class="font-medium text-zinc-500 dark:text-zinc-400">Description</dt><dd class="text-zinc-900 dark:text-white">{{ $articleCategory->description ?? '—' }}</dd></div>
            </dl>
        </div>
    </div>
</x-layouts.admin>
