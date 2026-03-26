<x-layouts.admin-faq-hub title="FAQ groups" active="groups">
    <div
        class="space-y-6"
        x-data="{
            open: false,
            deleteUrl: '',
            openDelete(url) {
                this.deleteUrl = url;
                this.open = true;
            },
            closeDelete() {
                this.open = false;
                this.deleteUrl = '';
            },
        }"
        @keydown.escape.window="closeDelete()"
    >
        <div class="rounded-lg border border-sky-200/80 bg-sky-50/90 px-4 py-3 text-sm text-sky-950 dark:border-sky-900/50 dark:bg-sky-950/35 dark:text-sky-100">
            <strong class="font-semibold">FAQ groups</strong> are loaded by <strong class="font-semibold">identifier</strong> (e.g. contact page). For accordion blocks inside page layouts, use <strong class="font-semibold">Questions &amp; answers</strong> in the sidebar.
        </div>

        <div class="flex flex-col gap-4 sm:flex-row sm:items-start sm:justify-between">
            <div>
                <h1 class="text-2xl font-bold text-zinc-900 dark:text-white">FAQ groups</h1>
                <p class="mt-1 text-zinc-600 dark:text-zinc-400">Identifiers, titles, and Q&amp;A sets used across the site and builder.</p>
            </div>
            <span class="inline-flex shrink-0 items-center rounded-full bg-zinc-100 px-3 py-1 text-xs font-semibold text-zinc-700 dark:bg-zinc-700 dark:text-zinc-200">
                {{ $faqs->total() }} {{ $faqs->total() === 1 ? 'group' : 'groups' }}
            </span>
        </div>

        <div class="overflow-hidden rounded-xl border border-zinc-200 bg-white shadow-sm dark:border-zinc-700 dark:bg-zinc-800/40">
            @if ($faqs->count() > 0)
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-zinc-200 dark:divide-zinc-600">
                        <thead class="bg-zinc-50 dark:bg-zinc-900/50">
                            <tr>
                                <th class="w-16 px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-zinc-500 dark:text-zinc-400">#</th>
                                <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-zinc-500 dark:text-zinc-400">Identifier</th>
                                <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-zinc-500 dark:text-zinc-400">Title</th>
                                <th class="w-28 px-6 py-3 text-center text-xs font-medium uppercase tracking-wider text-zinc-500 dark:text-zinc-400">Q&amp;A</th>
                                <th class="w-44 px-6 py-3 text-right text-xs font-medium uppercase tracking-wider text-zinc-500 dark:text-zinc-400">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-zinc-200 bg-white dark:divide-zinc-600 dark:bg-zinc-800/20">
                            @foreach ($faqs as $faq)
                                <tr class="transition-colors hover:bg-zinc-50 dark:hover:bg-zinc-800/30">
                                    <td class="whitespace-nowrap px-6 py-4 text-sm text-zinc-900 dark:text-zinc-100">{{ $faq->id }}</td>
                                    <td class="px-6 py-4">
                                        <code class="rounded-md bg-zinc-100 px-2 py-1 text-xs font-semibold text-zinc-800 dark:bg-zinc-700 dark:text-zinc-100">{{ $faq->identifier }}</code>
                                    </td>
                                    <td class="px-6 py-4">
                                        @if ($faq->title)
                                            <div class="font-medium text-zinc-900 dark:text-white">{{ $faq->title }}</div>
                                        @else
                                            <span class="text-zinc-400 italic dark:text-zinc-500">No title</span>
                                        @endif
                                        @if ($faq->subtitle)
                                            <div class="mt-0.5 text-sm text-zinc-500 dark:text-zinc-400">{{ $faq->subtitle }}</div>
                                        @endif
                                    </td>
                                    <td class="whitespace-nowrap px-6 py-4 text-center">
                                        <span class="inline-flex min-w-[2rem] items-center justify-center rounded-full bg-emerald-100 px-2 py-0.5 text-sm font-bold text-emerald-800 dark:bg-emerald-900/40 dark:text-emerald-200">
                                            {{ is_array($faq->items) ? count($faq->items) : 0 }}
                                        </span>
                                    </td>
                                    <td class="whitespace-nowrap px-6 py-4 text-right">
                                        <div class="flex items-center justify-end gap-2">
                                            <a href="{{ route('admin.faq-module.show', ['faq' => $faq]) }}" title="View">
                                                <x-button variant="sky" size="sm" icon="eye" title="View"></x-button>
                                            </a>
                                            <a href="{{ route('admin.faq-module.edit', ['faq' => $faq]) }}" title="Edit">
                                                <x-button variant="warning" size="sm" icon="edit" title="Edit"></x-button>
                                            </a>
                                            <x-button
                                                variant="error"
                                                size="sm"
                                                icon="trash"
                                                title="Delete"
                                                type="button"
                                                x-on:click="openDelete(@js(route('admin.faq-module.destroy', ['faq' => $faq])))"
                                            />
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="flex flex-col gap-3 border-t border-zinc-200 px-6 py-4 text-sm text-zinc-600 sm:flex-row sm:items-center sm:justify-between dark:border-zinc-600 dark:text-zinc-400">
                    <span>
                        Showing {{ $faqs->firstItem() }}–{{ $faqs->lastItem() }} of {{ $faqs->total() }}
                    </span>
                    {{ $faqs->links() }}
                </div>
            @else
                <div class="px-6 py-14 text-center">
                    <i class="fa-solid fa-layer-group mb-4 text-5xl text-zinc-300 dark:text-zinc-600" aria-hidden="true"></i>
                    <h3 class="text-lg font-semibold text-zinc-900 dark:text-white">No FAQ groups yet</h3>
                    <p class="mt-2 text-zinc-600 dark:text-zinc-400">
                        Run <code class="rounded bg-zinc-100 px-2 py-0.5 text-sm dark:bg-zinc-700">php artisan db:seed --class=FaqSeeder</code> to create the default contact FAQ.
                    </p>
                </div>
            @endif
        </div>

        <div
            x-show="open"
            x-cloak
            class="fixed inset-0 z-50 flex items-center justify-center bg-black/40 p-4 backdrop-blur-sm"
            x-transition.opacity
        >
            <div
                class="w-full max-w-md overflow-hidden rounded-xl border border-zinc-200 bg-white shadow-xl dark:border-zinc-600 dark:bg-zinc-800"
                @click.outside="closeDelete()"
                x-transition
            >
                <div class="border-b border-zinc-100 px-6 py-4 dark:border-zinc-700">
                    <h3 class="text-lg font-semibold text-zinc-900 dark:text-white">Delete FAQ group?</h3>
                    <p class="mt-2 text-sm text-zinc-600 dark:text-zinc-400">
                        This removes the group and all of its questions. This cannot be undone.
                    </p>
                </div>
                <div class="flex justify-end gap-2 px-6 py-4">
                    <button
                        type="button"
                        class="rounded-lg border border-zinc-300 bg-white px-4 py-2 text-sm font-medium text-zinc-700 hover:bg-zinc-50 dark:border-zinc-600 dark:bg-zinc-700 dark:text-zinc-200 dark:hover:bg-zinc-600"
                        x-on:click="closeDelete()"
                    >
                        Cancel
                    </button>
                    <form x-bind:action="deleteUrl" method="POST" class="inline">
                        @csrf
                        @method('DELETE')
                        <button
                            type="submit"
                            class="rounded-lg bg-red-600 px-4 py-2 text-sm font-semibold text-white hover:bg-red-700"
                        >
                            Delete
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-layouts.admin-faq-hub>
