@props(['item', 'index', 'triggerClass' => 'text-sm/6 font-semibold text-gray-900 dark:text-white'])

@php
    // Get header layout type from setting or use default max-w-7xl
    $headerLayoutType = \App\Models\Setting::getValue('site_header_layout_type');

    // Get page layout if header layout is empty
    if (empty($headerLayoutType)) {
        $pageObj = request()->route('page');
        if ($pageObj && !empty($pageObj->layout_type)) {
            $headerLayoutType = $pageObj->layout_type;
        }
    }

    // Map layout type to container class
    $containerClass = match ($headerLayoutType) {
        'full-width' => 'w-full',
        'container' => 'max-w-container',
        'max-w-2xl' => 'max-w-2xl',
        'max-w-4xl' => 'max-w-4xl',
        'max-w-6xl' => 'max-w-6xl',
        'max-w-7xl' => 'max-w-7xl',
        default => 'max-w-7xl',
    };
@endphp

<div class="relative">
    <button popovertarget="desktop-menu-{{ $index }}" class="flex items-center gap-x-1 {{ $triggerClass }}">
        {{ $item['title'] }}
        <svg viewBox="0 0 20 20" fill="currentColor" data-slot="icon" aria-hidden="true"
            class="size-5 flex-none text-gray-400 dark:text-gray-500">
            <path
                d="M5.22 8.22a.75.75 0 0 1 1.06 0L10 11.94l3.72-3.72a.75.75 0 1 1 1.06 1.06l-4.25 4.25a.75.75 0 0 1-1.06 0L5.22 9.28a.75.75 0 0 1 0-1.06Z"
                clip-rule="evenodd" fill-rule="evenodd" />
        </svg>
    </button>

    {{-- Full-width flyout: spans entire viewport, with centered inner container --}}
    <el-popover id="desktop-menu-{{ $index }}" popover
        class="fixed left-0 right-0 top-16 w-screen overflow-visible bg-white shadow-lg outline-1 outline-gray-900/5 transition transition-discrete backdrop:bg-transparent open:block data-closed:translate-y-1 data-closed:opacity-0 data-enter:duration-200 data-enter:ease-out data-leave:duration-150 data-leave:ease-in dark:bg-gray-800 dark:shadow-none dark:-outline-offset-1 dark:outline-white/10">

        {{-- Inner container with dynamic max-width --}}
        <div class="{{ $containerClass }} mx-auto px-6 lg:px-8">
            <div class="grid grid-cols-4 gap-x-4 py-10 xl:gap-x-8">
                @foreach($item['children'] as $child)
                    <div class="group relative rounded-lg p-6 text-sm/6 hover:bg-gray-50 dark:hover:bg-white/5">
                        <div
                            class="flex size-11 items-center justify-center rounded-lg bg-gray-50 group-hover:bg-white dark:bg-gray-700/50 dark:group-hover:bg-gray-700">
                            @if(!empty($child['icon']))
                                <i
                                    class="{{ $child['icon'] }} text-lg text-gray-600 group-hover:text-indigo-600 dark:text-gray-400 dark:group-hover:text-white"></i>
                            @else
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" data-slot="icon"
                                    aria-hidden="true"
                                    class="size-6 text-gray-600 group-hover:text-indigo-600 dark:text-gray-400 dark:group-hover:text-white">
                                    <path d="M10.5 6a7.5 7.5 0 1 0 7.5 7.5h-7.5V6Z" stroke-linecap="round"
                                        stroke-linejoin="round" />
                                    <path d="M13.5 10.5H21A7.5 7.5 0 0 0 13.5 3v7.5Z" stroke-linecap="round"
                                        stroke-linejoin="round" />
                                </svg>
                            @endif
                        </div>
                        <a href="{{ $child['url'] ?? '#' }}"
                            class="mt-4 block font-semibold text-gray-900 text-sm/6 dark:text-white">
                            {{ $child['title'] }}
                            <span class="absolute inset-0"></span>
                        </a>
                        @if(!empty($child['description']))
                            <p class="mt-1 text-gray-500 text-xs/5 dark:text-gray-400">{{ $child['description'] }}</p>
                        @endif
                    </div>
                @endforeach
            </div>
        </div>
    </el-popover>
</div>