<div class="space-y-6">
    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h1 class="text-2xl font-bold text-zinc-900 dark:text-white">{{ $heading }} · preview</h1>
            <p class="mt-1 text-zinc-600 dark:text-zinc-400">Record #{{ $element->id }}</p>
        </div>
        <div class="flex flex-wrap items-center gap-2">
            <a href="{{ route($routeBase . '.edit', $element->id) }}"
               class="inline-flex items-center gap-2 rounded-lg bg-[var(--color-accent)] px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:opacity-90">
                <i class="fa-solid fa-pen" aria-hidden="true"></i>
                Edit
            </a>
            <a href="{{ route($routeBase . '.index') }}"
               class="inline-flex items-center gap-2 rounded-lg border border-zinc-300 bg-white px-4 py-2.5 text-sm font-medium text-zinc-700 shadow-sm transition hover:bg-zinc-50 dark:border-zinc-600 dark:bg-zinc-800 dark:text-zinc-200 dark:hover:bg-zinc-700">
                Back to list
            </a>
        </div>
    </div>

    @if ($typeHelp)
        <div class="rounded-lg border border-blue-200 bg-blue-50 px-4 py-3 text-sm text-blue-900 dark:border-blue-800/60 dark:bg-blue-950/40 dark:text-blue-100">
            {{ $typeHelp }}
        </div>
    @endif

    <div class="space-y-4 rounded-xl border border-zinc-200 bg-white p-6 shadow-sm dark:border-zinc-700 dark:bg-zinc-800/40">
        <div><span class="font-semibold text-zinc-700 dark:text-zinc-300">Type:</span> <code class="text-sm">{{ $element->type->value }}</code></div>
        <div><span class="font-semibold text-zinc-700 dark:text-zinc-300">Title:</span> {{ $element->title ?: '—' }}</div>
        <div><span class="font-semibold text-zinc-700 dark:text-zinc-300">Sub title:</span> {{ $element->sub_title ?: '—' }}</div>
        <div>
            <span class="font-semibold text-zinc-700 dark:text-zinc-300">Description:</span>
            <p class="mt-2 whitespace-pre-wrap text-zinc-600 dark:text-zinc-400">{{ $element->description ?: '—' }}</p>
        </div>
        <div>
            <span class="mb-2 block font-semibold text-zinc-700 dark:text-zinc-300">Options</span>
            @include($showOptionsView, ['element' => $element])
        </div>
    </div>
</div>
