<div class="space-y-6">
    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h1 class="text-2xl font-bold text-zinc-900 dark:text-white">Create {{ $heading }}</h1>
            <p class="mt-1 text-zinc-600 dark:text-zinc-400">Saved as type <code class="rounded bg-zinc-100 px-1.5 py-0.5 text-xs dark:bg-zinc-700">{{ is_object($type) ? $type->value : $type }}</code>.</p>
        </div>
        <a href="{{ route($routeBase . '.index') }}"
           class="inline-flex shrink-0 items-center justify-center gap-2 rounded-lg border border-zinc-300 bg-white px-4 py-2.5 text-sm font-medium text-zinc-700 shadow-sm transition hover:bg-zinc-50 dark:border-zinc-600 dark:bg-zinc-800 dark:text-zinc-200 dark:hover:bg-zinc-700">
            <i class="fa-solid fa-arrow-left" aria-hidden="true"></i>
            Back to list
        </a>
    </div>

    @if ($typeHelp)
        <div class="rounded-lg border border-blue-200 bg-blue-50 px-4 py-3 text-sm text-blue-900 dark:border-blue-800/60 dark:bg-blue-950/40 dark:text-blue-100">
            {{ $typeHelp }}
        </div>
    @endif

    <form action="{{ route($routeBase . '.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
        @csrf
        <div class="space-y-4 rounded-xl border border-zinc-200 bg-white p-6 shadow-sm dark:border-zinc-700 dark:bg-zinc-800/40">
            @include('admin.elements.partials.base-fields', ['element' => $element])
            @include($optionsFormView, ['element' => $element])
        </div>

        <button type="submit"
                class="inline-flex items-center gap-2 rounded-lg bg-[var(--color-accent)] px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:opacity-90">
            <i class="fa-solid fa-floppy-disk" aria-hidden="true"></i>
            Create item
        </button>
    </form>
</div>
