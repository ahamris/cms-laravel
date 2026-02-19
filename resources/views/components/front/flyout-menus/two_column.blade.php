@props(['item', 'index', 'triggerClass' => 'text-sm/6 font-semibold text-gray-900 dark:text-white'])

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

    <el-popover id="desktop-menu-{{ $index }}" anchor="bottom-start" popover
        class="w-screen max-w-md overflow-visible rounded-3xl bg-white text-sm/6 shadow-lg outline-1 outline-gray-900/5 lg:max-w-3xl transition transition-discrete [--anchor-gap:--spacing(3)] backdrop:bg-transparent open:block data-closed:translate-y-1 data-closed:opacity-0 data-enter:duration-200 data-enter:ease-out data-leave:duration-150 data-leave:ease-in dark:bg-gray-800 dark:shadow-none dark:-outline-offset-1 dark:outline-white/10">
        <div class="grid grid-cols-1 gap-x-6 gap-y-1 p-4 lg:grid-cols-2">
            @foreach($item['children'] as $child)
                <div class="group relative flex gap-x-6 rounded-lg p-4 hover:bg-gray-50 dark:hover:bg-white/5">
                    <div
                        class="flex size-11 flex-none items-center justify-center rounded-lg bg-gray-50 group-hover:bg-white dark:bg-gray-700/50 dark:group-hover:bg-gray-700">
                        @if(!empty($child['icon']))
                            <i
                                class="{{ $child['icon'] }} text-lg text-gray-600 group-hover:text-indigo-600 dark:text-gray-400 dark:group-hover:text-white"></i>
                        @else
                            <svg class="size-6 text-gray-600 group-hover:text-indigo-600 dark:text-gray-400 dark:group-hover:text-white"
                                fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 6a7.5 7.5 0 107.5 7.5h-7.5V6z" />
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M13.5 10.5H21A7.5 7.5 0 0013.5 3v7.5z" />
                            </svg>
                        @endif
                    </div>
                    <div>
                        <a href="{{ $child['url'] ?? '#' }}"
                            class="block font-semibold text-gray-900 text-sm/6 dark:text-white">
                            {{ $child['title'] }}
                            <span class="absolute inset-0"></span>
                        </a>
                        @if(!empty($child['description']))
                            <p class="mt-1 text-gray-500 text-xs/5 dark:text-gray-400">{{ $child['description'] }}</p>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    </el-popover>
</div>