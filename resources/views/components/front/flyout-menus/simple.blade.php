@props(['item', 'index', 'triggerClass' => 'text-sm/6 font-semibold text-gray-900 dark:text-white'])

<div class="relative">
    <button type="button" popovertarget="desktop-menu-{{ $index }}" aria-haspopup="true" class="flex items-center gap-x-1 {{ $triggerClass }}">
        {{ $item['title'] }}
        <svg viewBox="0 0 20 20" fill="currentColor" data-slot="icon" aria-hidden="true"
            class="size-5 flex-none text-gray-400 dark:text-gray-500">
            <path
                d="M5.22 8.22a.75.75 0 0 1 1.06 0L10 11.94l3.72-3.72a.75.75 0 1 1 1.06 1.06l-4.25 4.25a.75.75 0 0 1-1.06 0L5.22 9.28a.75.75 0 0 1 0-1.06Z"
                clip-rule="evenodd" fill-rule="evenodd" />
        </svg>
    </button>

    <el-popover id="desktop-menu-{{ $index }}" anchor="bottom" popover
        class="w-56 overflow-visible rounded-xl bg-white p-2 shadow-lg outline-1 outline-gray-900/5 transition transition-discrete [--anchor-gap:--spacing(3)] backdrop:bg-transparent open:block data-closed:translate-y-1 data-closed:opacity-0 data-enter:duration-200 data-enter:ease-out data-leave:duration-150 data-leave:ease-in dark:bg-gray-800 dark:shadow-none dark:-outline-offset-1 dark:outline-white/10">
        @foreach($item['children'] as $child)
            <a href="{{ $child['url'] ?? '#' }}"
                class="group block rounded-lg px-3 py-2 text-sm/6 font-semibold text-gray-900 hover:bg-gray-50 dark:text-white dark:hover:bg-white/5">
                <div class="flex items-center gap-x-3">
                    @if(!empty($child['icon']))
                        <i
                            class="{{ $child['icon'] }} size-5 flex-none text-gray-400 group-hover:text-indigo-600 dark:text-gray-500 dark:group-hover:text-white"></i>
                    @endif
                    {{ $child['title'] }}
                </div>
            </a>
        @endforeach
    </el-popover>
</div>