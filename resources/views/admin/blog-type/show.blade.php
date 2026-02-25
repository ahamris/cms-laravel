<x-layouts.admin title="Blog Type: {{ $blogType->name }}">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-8">
        <div>
            <h1 class="text-3xl font-bold text-zinc-900 dark:text-white mb-2">{{ $blogType->name }}</h1>
            <p class="text-zinc-600 dark:text-zinc-400">Blog type details.</p>
        </div>
        <div class="flex gap-3">
            <a href="{{ route('admin.blog-type.edit', $blogType) }}" class="inline-flex items-center gap-2 rounded-md bg-[var(--color-accent)] px-4 py-2 text-sm font-semibold text-white shadow-xs hover:opacity-90">
                <i class="fa-solid fa-edit"></i>
                Edit
            </a>
            <a href="{{ route('admin.blog-type.index') }}" class="inline-flex items-center gap-2 rounded-md bg-white dark:bg-white/10 px-4 py-2 text-sm font-semibold text-gray-900 dark:text-white shadow-xs ring-1 ring-gray-300 ring-inset dark:ring-white/10 hover:bg-gray-50 dark:hover:bg-white/20">
                <i class="fa-solid fa-arrow-left"></i>
                Back to Blog Types
            </a>
        </div>
    </div>

    <div class="rounded-lg border border-gray-200 dark:border-white/10 bg-white dark:bg-white/5 p-6 max-w-xl">
        <dl class="space-y-4">
            <div>
                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">ID</dt>
                <dd class="mt-1 text-sm text-gray-900 dark:text-white">{{ $blogType->id }}</dd>
            </div>
            <div>
                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Name</dt>
                <dd class="mt-1 text-sm text-gray-900 dark:text-white">{{ $blogType->name }}</dd>
            </div>
            @if($blogType->description)
            <div>
                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Description</dt>
                <dd class="mt-1 text-sm text-gray-900 dark:text-white whitespace-pre-wrap">{{ $blogType->description }}</dd>
            </div>
            @endif
            <div>
                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Blogs using this type</dt>
                <dd class="mt-1 text-sm text-gray-900 dark:text-white">{{ $blogType->blogs()->count() }}</dd>
            </div>
        </dl>
    </div>
</x-layouts.admin>
