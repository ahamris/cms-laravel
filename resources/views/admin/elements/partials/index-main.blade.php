<div class="space-y-6">
    @if ($faqHubContext ?? false)
        <div class="rounded-lg border border-violet-200/80 bg-violet-50/90 px-4 py-3 text-sm text-violet-950 dark:border-violet-800/50 dark:bg-violet-950/30 dark:text-violet-100">
            <strong class="font-semibold">Template builder:</strong> these records are the FAQ element type you add to page layouts. For contact-page FAQs keyed by identifier, use <strong class="font-semibold">FAQ groups</strong> in the sidebar.
        </div>
    @endif
    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h1 class="text-2xl font-bold text-zinc-900 dark:text-white">{{ $heading }}</h1>
            <p class="mt-1 text-zinc-600 dark:text-zinc-400">Strict CRUD for this element type only.</p>
        </div>
        <a href="{{ route($routeBase . '.create') }}"
           class="inline-flex shrink-0 items-center justify-center gap-2 rounded-lg bg-[var(--color-accent)] px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:opacity-90">
            <i class="fa-solid fa-plus" aria-hidden="true"></i>
            Create item
        </a>
    </div>

    @if ($typeHelp)
        <div class="rounded-lg border border-blue-200 bg-blue-50 px-4 py-3 text-sm text-blue-900 dark:border-blue-800/60 dark:bg-blue-950/40 dark:text-blue-100">
            {{ $typeHelp }}
        </div>
    @endif

    <div class="overflow-hidden rounded-xl border border-zinc-200 bg-white shadow-sm dark:border-zinc-700 dark:bg-zinc-800/40">
        @if ($elements->count() > 0)
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="border-b border-zinc-200 bg-zinc-50 dark:border-zinc-600 dark:bg-zinc-900/50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-zinc-500 dark:text-zinc-400">ID</th>
                            <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-zinc-500 dark:text-zinc-400">Title</th>
                            <th class="px-6 py-3 text-right text-xs font-medium uppercase tracking-wider text-zinc-500 dark:text-zinc-400">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-zinc-200 dark:divide-zinc-600">
                        @foreach ($elements as $element)
                            <tr class="transition-colors hover:bg-zinc-50 dark:hover:bg-zinc-800/30">
                                <td class="whitespace-nowrap px-6 py-4 text-sm text-zinc-900 dark:text-zinc-100">{{ $element->id }}</td>
                                <td class="px-6 py-4">
                                    <div class="text-sm font-medium text-zinc-900 dark:text-white">{{ $element->title ?: '—' }}</div>
                                    <div class="text-xs text-zinc-500 dark:text-zinc-400">{{ $element->sub_title ?: '—' }}</div>
                                </td>
                                <td class="whitespace-nowrap px-6 py-4 text-right text-sm">
                                    <div class="flex items-center justify-end gap-2">
                                        <a href="{{ route($routeBase . '.show', $element->id) }}" title="View">
                                            <x-button variant="sky" size="sm" icon="eye" title="View"></x-button>
                                        </a>
                                        <a href="{{ route($routeBase . '.edit', $element->id) }}" title="Edit">
                                            <x-button variant="warning" size="sm" icon="edit" title="Edit"></x-button>
                                        </a>
                                        <form action="{{ route($routeBase . '.destroy', $element->id) }}" method="POST" class="inline" onsubmit="return confirm('Delete this element?')">
                                            @csrf
                                            @method('DELETE')
                                            <x-button variant="error" size="sm" icon="trash" title="Delete" type="submit"></x-button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="border-t border-zinc-200 px-6 py-4 dark:border-zinc-600">
                {{ $elements->links() }}
            </div>
        @else
            <div class="px-6 py-14 text-center text-zinc-600 dark:text-zinc-400">
                <p class="font-medium text-zinc-900 dark:text-white">No items found</p>
                <p class="mt-1 text-sm">Create an item to get started.</p>
            </div>
        @endif
    </div>
</div>
