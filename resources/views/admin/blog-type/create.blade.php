<x-layouts.admin title="Create Blog Type">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-8">
        <div>
            <h1 class="text-3xl font-bold text-zinc-900 dark:text-white mb-2">Create Blog Type</h1>
            <p class="text-zinc-600 dark:text-zinc-400">Add a new blog type (e.g. Article, News).</p>
        </div>
        <a href="{{ route('admin.blog-type.index') }}"
            class="inline-flex items-center gap-2 rounded-md bg-white dark:bg-white/10 px-4 py-2 text-sm font-semibold text-gray-900 dark:text-white shadow-xs ring-1 ring-gray-300 ring-inset dark:ring-white/10 hover:bg-gray-50 dark:hover:bg-white/20">
            <i class="fa-solid fa-arrow-left"></i>
            Back to Blog Types
        </a>
    </div>

    <form action="{{ route('admin.blog-type.store') }}" method="POST" class="max-w-xl">
        @csrf
        <div class="rounded-lg border border-gray-200 dark:border-white/10 bg-white dark:bg-white/5 p-6">
            <x-ui.input id="name" name="name" label="Name" :value="old('name')" placeholder="e.g. Article, News" required />
            @error('name')<p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>@enderror
        </div>
        <div class="mt-6 flex gap-3">
            <a href="{{ route('admin.blog-type.index') }}" class="inline-flex items-center rounded-md bg-white dark:bg-white/10 px-4 py-2 text-sm font-semibold text-gray-900 dark:text-white ring-1 ring-gray-300 dark:ring-white/10 hover:bg-gray-50 dark:hover:bg-white/20">Cancel</a>
            <button type="submit" class="inline-flex items-center gap-2 rounded-md bg-[var(--color-accent)] px-4 py-2 text-sm font-semibold text-white shadow-xs hover:opacity-90">
                <i class="fa-solid fa-check"></i>
                Create Blog Type
            </button>
        </div>
    </form>
</x-layouts.admin>
