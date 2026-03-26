@props([
    'alpineShow' => 'open',
    'title' => '',
    'description' => null,
    'maxWidth' => 'lg', // sm | md | lg | xl
    'titleId' => null,
])

@php
    $headingId = $titleId ?? 'slide-over-title-'.substr(uniqid('', true), -10);
    $maxWidthClass = match ($maxWidth) {
        'sm' => 'max-w-sm',
        'md' => 'max-w-md',
        'xl' => 'max-w-xl',
        default => 'max-w-lg',
    };
@endphp

<div
    x-show="{{ $alpineShow }}"
    x-cloak
    class="fixed inset-0 z-50 overflow-hidden"
    role="dialog"
    aria-modal="true"
    @keydown.escape.window="{{ $alpineShow }} = false"
>
    <div
        x-show="{{ $alpineShow }}"
        x-transition:enter="ease-out duration-200"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="ease-in duration-150"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        class="fixed inset-0 z-40 bg-black/30"
        @click="{{ $alpineShow }} = false"
        aria-hidden="true"
    ></div>

    <div class="fixed inset-y-0 right-0 z-50 flex max-w-full pl-10 sm:pl-16 pointer-events-none">
        <div
            x-show="{{ $alpineShow }}"
            x-transition:enter="transform transition ease-out duration-200"
            x-transition:enter-start="translate-x-full"
            x-transition:enter-end="translate-x-0"
            x-transition:leave="transform transition ease-in duration-150"
            x-transition:leave-start="translate-x-0"
            x-transition:leave-end="translate-x-full"
            class="pointer-events-auto w-screen {{ $maxWidthClass }} flex flex-col h-full bg-white dark:bg-zinc-900 border-l border-zinc-200 dark:border-zinc-700 shadow-xl"
            @click.stop
        >
            <header class="flex h-12 shrink-0 items-center justify-between gap-3 border-b border-zinc-200 px-5 dark:border-zinc-700">
                <div class="min-w-0">
                    <h2 id="{{ $headingId }}" class="truncate text-sm font-semibold text-zinc-900 dark:text-zinc-100">
                        {{ $title }}
                    </h2>
                    @if ($description)
                        <p class="truncate text-xs text-zinc-500 dark:text-zinc-400">{{ $description }}</p>
                    @endif
                </div>
                <button
                    type="button"
                    class="flex h-8 w-8 shrink-0 items-center justify-center rounded-md border border-zinc-200 bg-white text-zinc-500 transition-colors hover:bg-zinc-50 dark:border-zinc-600 dark:bg-zinc-800 dark:text-zinc-400 dark:hover:bg-zinc-700"
                    @click="{{ $alpineShow }} = false"
                    aria-label="{{ __('Close panel') }}"
                >
                    <i class="fa-solid fa-times text-sm" aria-hidden="true"></i>
                </button>
            </header>

            <div class="flex-1 overflow-y-auto px-5 py-4">
                {{ $slot }}
            </div>

            @isset($footer)
                <footer class="flex h-14 shrink-0 items-center justify-end gap-2 border-t border-zinc-200 bg-white px-5 dark:border-zinc-700 dark:bg-zinc-900">
                    {{ $footer }}
                </footer>
            @endisset
        </div>
    </div>
</div>
