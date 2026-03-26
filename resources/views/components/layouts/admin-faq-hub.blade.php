@props([
    'title' => 'FAQ',
    'active' => null,
])

@php
    use Illuminate\Support\Facades\Gate;

    $resolvedActive = $active ?? (request()->routeIs('admin.element-faq*') ? 'elements' : 'groups');
    $showBothHint = Gate::allows('faq_module_access') && Gate::allows('page_access');
@endphp

<x-layouts.admin :title="$title">
    <div class="flex flex-col gap-8 lg:flex-row lg:items-start">
        <aside class="w-full shrink-0 lg:w-56" aria-label="FAQ sections">
            <div class="rounded-xl border border-zinc-200 bg-white p-2 shadow-sm dark:border-zinc-700 dark:bg-zinc-800/80">
                <p class="px-3 py-2 text-xs font-semibold uppercase tracking-wider text-zinc-500 dark:text-zinc-400">
                    FAQ
                </p>
                <nav class="space-y-1">
                    @can('faq_module_access')
                        <a
                            href="{{ route('admin.faq-module.index') }}"
                            @class([
                                'flex items-start gap-3 rounded-lg px-3 py-2.5 text-sm font-medium transition-colors',
                                'bg-[var(--color-accent)]/12 text-[var(--color-accent)] dark:bg-[var(--color-accent)]/15' => $resolvedActive === 'groups',
                                'text-zinc-700 hover:bg-zinc-100 dark:text-zinc-200 dark:hover:bg-zinc-700/50' => $resolvedActive !== 'groups',
                            ])
                        >
                            <span class="mt-0.5 flex h-8 w-8 shrink-0 items-center justify-center rounded-md bg-zinc-100 text-zinc-600 dark:bg-zinc-700/60 dark:text-zinc-300">
                                <i class="fa-solid fa-layer-group text-sm" aria-hidden="true"></i>
                            </span>
                            <span class="min-w-0 flex-1">
                                <span class="block leading-tight">FAQ groups</span>
                                <span class="mt-0.5 block text-[11px] font-normal leading-snug text-zinc-500 dark:text-zinc-400">Identifiers · contact &amp; builder</span>
                            </span>
                        </a>
                    @endcan
                    @can('page_access')
                        <a
                            href="{{ route('admin.element-faq.index') }}"
                            @class([
                                'flex items-start gap-3 rounded-lg px-3 py-2.5 text-sm font-medium transition-colors',
                                'bg-[var(--color-accent)]/12 text-[var(--color-accent)] dark:bg-[var(--color-accent)]/15' => $resolvedActive === 'elements',
                                'text-zinc-700 hover:bg-zinc-100 dark:text-zinc-200 dark:hover:bg-zinc-700/50' => $resolvedActive !== 'elements',
                            ])
                        >
                            <span class="mt-0.5 flex h-8 w-8 shrink-0 items-center justify-center rounded-md bg-zinc-100 text-zinc-600 dark:bg-zinc-700/60 dark:text-zinc-300">
                                <i class="fa-solid fa-circle-question text-sm" aria-hidden="true"></i>
                            </span>
                            <span class="min-w-0 flex-1">
                                <span class="block leading-tight">Questions &amp; answers</span>
                                <span class="mt-0.5 block text-[11px] font-normal leading-snug text-zinc-500 dark:text-zinc-400">Reusable FAQ blocks for templates</span>
                            </span>
                        </a>
                    @endcan
                </nav>
            </div>
            @if ($showBothHint)
                <p class="mt-3 px-1 text-xs leading-relaxed text-zinc-500 dark:text-zinc-400">
                    Use <strong class="font-medium text-zinc-600 dark:text-zinc-300">groups</strong> where the site loads FAQs by identifier. Use <strong class="font-medium text-zinc-600 dark:text-zinc-300">Q&amp;A blocks</strong> as page elements in the layout builder.
                </p>
            @endif
        </aside>

        <div class="min-w-0 flex-1">
            {{ $slot }}
        </div>
    </div>
</x-layouts.admin>
