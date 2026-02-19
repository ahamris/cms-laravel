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

    <el-popover id="desktop-menu-{{ $index }}" anchor="bottom" popover
        class="w-screen max-w-md overflow-visible rounded-3xl bg-white text-sm/6 shadow-lg outline-1 outline-gray-900/5 transition transition-discrete [--anchor-gap:--spacing(3)] backdrop:bg-transparent open:block data-closed:translate-y-1 data-closed:opacity-0 data-enter:duration-200 data-enter:ease-out data-leave:duration-150 data-leave:ease-in dark:bg-gray-800 dark:shadow-none dark:-outline-offset-1 dark:outline-white/10">
        <div class="p-4">
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

        @if(!empty($item['footer_action_1_text']) || !empty($item['footer_action_2_text']))
            <div class="grid grid-cols-2 divide-x divide-gray-900/5 bg-gray-50 dark:divide-white/10 dark:bg-gray-700/50">
                @if(!empty($item['footer_action_1_text']))
                    <a href="{{ $item['footer_action_1_url'] ?? '#' }}"
                        class="flex items-center justify-center gap-x-2.5 p-3 font-semibold text-gray-900 hover:bg-gray-100 dark:text-white dark:hover:bg-gray-700/50">
                        @if(!empty($item['footer_action_1_icon']))
                            <i class="{{ $item['footer_action_1_icon'] }} text-base flex-none text-gray-400 dark:text-gray-500"></i>
                        @else
                            <svg viewBox="0 0 20 20" fill="currentColor" data-slot="icon" aria-hidden="true"
                                class="size-5 flex-none text-gray-400 dark:text-gray-500">
                                <path
                                    d="M2 10a8 8 0 1 1 16 0 8 8 0 0 1-16 0Zm6.39-2.908a.75.75 0 0 1 .766.027l3.5 2.25a.75.75 0 0 1 0 1.262l-3.5 2.25A.75.75 0 0 1 8 12.25v-4.5a.75.75 0 0 1 .39-.658Z"
                                    clip-rule="evenodd" fill-rule="evenodd" />
                            </svg>
                        @endif
                        {{ $item['footer_action_1_text'] }}
                    </a>
                @endif
                @if(!empty($item['footer_action_2_text']))
                    <a href="{{ $item['footer_action_2_url'] ?? '#' }}"
                        class="flex items-center justify-center gap-x-2.5 p-3 font-semibold text-gray-900 hover:bg-gray-100 dark:text-white dark:hover:bg-gray-700/50">
                        @if(!empty($item['footer_action_2_icon']))
                            <i class="{{ $item['footer_action_2_icon'] }} text-base flex-none text-gray-400 dark:text-gray-500"></i>
                        @else
                            <svg viewBox="0 0 20 20" fill="currentColor" data-slot="icon" aria-hidden="true"
                                class="size-5 flex-none text-gray-400 dark:text-gray-500">
                                <path
                                    d="M2 3.5A1.5 1.5 0 0 1 3.5 2h1.148a1.5 1.5 0 0 1 1.465 1.175l.716 3.223a1.5 1.5 0 0 1-1.052 1.767l-.933.267c-.41.117-.643.555-.48.95a11.542 11.542 0 0 0 6.254 6.254c.395.163.833-.07.95-.48l.267-.933a1.5 1.5 0 0 1 1.767-1.052l3.223.716A1.5 1.5 0 0 1 18 15.352V16.5a1.5 1.5 0 0 1-1.5 1.5H15c-1.149 0-2.263-.15-3.326-.43A13.022 13.022 0 0 1 2.43 8.326 13.019 13.019 0 0 1 2 5V3.5Z"
                                    clip-rule="evenodd" fill-rule="evenodd" />
                            </svg>
                        @endif
                        {{ $item['footer_action_2_text'] }}
                    </a>
                @endif
            </div>
        @endif
    </el-popover>
</div>