<div class="space-y-6" x-data="{ showDeleteModal: false, deleteAction: '' }">
    @if ($faqHubContext ?? false)
        <div class="rounded-lg border border-violet-200/80 bg-violet-50/90 px-4 py-3 text-sm text-violet-950 dark:border-violet-800/50 dark:bg-violet-950/30 dark:text-violet-100">
            <strong class="font-semibold">Template builder:</strong> these records are the FAQ element type you add to page layouts. For contact-page FAQs keyed by identifier, use <strong class="font-semibold">FAQ groups</strong> in the sidebar.
        </div>
    @endif
    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h1 class="text-xl font-semibold text-zinc-900 dark:text-white">{{ $heading }}</h1>
            <p class="mt-1 text-[12.5px] text-zinc-600 dark:text-zinc-400">Strict CRUD for this element type only.</p>
        </div>
        <x-ui.button variant="primary" icon="plus" icon-position="left" href="{{ route($routeBase . '.create') }}" class="shrink-0">
            Create item
        </x-ui.button>
    </div>

    @if ($typeHelp)
        <div class="rounded-lg border border-blue-200 bg-blue-50 px-4 py-3 text-sm text-blue-900 dark:border-blue-800/60 dark:bg-blue-950/40 dark:text-blue-100">
            {{ $typeHelp }}
        </div>
    @endif

    <div class="overflow-hidden rounded-lg border border-zinc-200 bg-white shadow-sm dark:border-zinc-700 dark:bg-zinc-800/40">
        @if ($elements->count() > 0)
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="border-b border-zinc-200 bg-zinc-50 dark:border-zinc-600 dark:bg-zinc-900/50">
                        <tr>
                            <th class="px-6 py-3 text-left text-[10.5px] font-semibold uppercase tracking-wider text-zinc-500 dark:text-zinc-400">ID</th>
                            <th class="px-6 py-3 text-left text-[10.5px] font-semibold uppercase tracking-wider text-zinc-500 dark:text-zinc-400">Title</th>
                            <th class="px-6 py-3 text-right text-[10.5px] font-semibold uppercase tracking-wider text-zinc-500 dark:text-zinc-400">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-zinc-200 dark:divide-zinc-600">
                        @foreach ($elements as $element)
                            <tr class="transition-colors duration-100 hover:bg-zinc-50 dark:hover:bg-zinc-800/30">
                                <td class="whitespace-nowrap px-6 py-4 text-[12.5px] text-zinc-900 dark:text-zinc-100">{{ $element->id }}</td>
                                <td class="px-6 py-4">
                                    <div class="text-[12.5px] font-medium text-zinc-900 dark:text-white">{{ $element->title ?: '—' }}</div>
                                    <div class="mt-0.5 text-[11px] text-zinc-500 dark:text-zinc-400">{{ $element->sub_title ?: '—' }}</div>
                                </td>
                                <td class="whitespace-nowrap px-6 py-4 text-right text-sm">
                                    <div class="flex items-center justify-end gap-1">
                                        <a href="{{ route($routeBase . '.show', $element->id) }}" title="{{ __('View') }}" aria-label="{{ __('View') }}" class="inline-flex h-[27px] w-[27px] items-center justify-center rounded border border-zinc-200 bg-white text-zinc-600 hover:bg-zinc-50 dark:border-zinc-600 dark:bg-zinc-800 dark:text-zinc-300 dark:hover:bg-zinc-700">
                                            <i class="fa-solid fa-eye text-xs" aria-hidden="true"></i>
                                        </a>
                                        <a href="{{ route($routeBase . '.edit', $element->id) }}" title="{{ __('Edit') }}" aria-label="{{ __('Edit') }}" class="inline-flex h-[27px] w-[27px] items-center justify-center rounded border border-zinc-200 bg-white text-zinc-600 hover:bg-zinc-50 dark:border-zinc-600 dark:bg-zinc-800 dark:text-zinc-300 dark:hover:bg-zinc-700">
                                            <i class="fa-solid fa-pen text-xs" aria-hidden="true"></i>
                                        </a>
                                        @if (\Illuminate\Support\Facades\Route::has($routeBase . '.clone'))
                                            <form action="{{ route($routeBase . '.clone', $element->id) }}" method="POST" class="inline">
                                                @csrf
                                                <button type="submit" title="{{ __('Duplicate') }}" aria-label="{{ __('Duplicate') }}" class="inline-flex h-[27px] w-[27px] items-center justify-center rounded border border-zinc-200 bg-white text-zinc-600 hover:bg-zinc-50 dark:border-zinc-600 dark:bg-zinc-800 dark:text-zinc-300 dark:hover:bg-zinc-700">
                                                    <i class="fa-solid fa-copy text-xs" aria-hidden="true"></i>
                                                </button>
                                            </form>
                                        @endif
                                        <button
                                            type="button"
                                            title="{{ __('Delete') }}"
                                            aria-label="{{ __('Delete') }}"
                                            class="inline-flex h-[27px] w-[27px] items-center justify-center rounded border border-zinc-200 bg-white text-zinc-600 hover:border-red-200 hover:bg-red-50 hover:text-red-600 dark:border-zinc-600 dark:bg-zinc-800 dark:text-zinc-300 dark:hover:border-red-900 dark:hover:bg-red-950/40 dark:hover:text-red-400"
                                            x-on:click="deleteAction = '{{ route($routeBase . '.destroy', $element->id) }}'; showDeleteModal = true"
                                        >
                                            <i class="fa-solid fa-trash text-xs" aria-hidden="true"></i>
                                        </button>
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

    <x-ui.modal alpine-show="showDeleteModal" size="sm" modal-id="element-index-delete">
        <x-slot:title>{{ __('Delete element') }}</x-slot:title>
        <p class="text-[13px] text-zinc-600 dark:text-zinc-400">{{ __('Are you sure you want to delete this element? This cannot be undone.') }}</p>
        <x-slot:footer>
            <x-ui.button variant="secondary" type="button" x-on:click="showDeleteModal = false">{{ __('Cancel') }}</x-ui.button>
            <form x-bind:action="deleteAction" method="POST" class="inline">
                @csrf
                @method('DELETE')
                <x-ui.button variant="primary" color="red" type="submit" x-on:click="showDeleteModal = false">{{ __('Delete') }}</x-ui.button>
            </form>
        </x-slot:footer>
    </x-ui.modal>
</div>
