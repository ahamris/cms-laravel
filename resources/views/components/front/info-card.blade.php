<div
        x-data="{ open: false }"
        @mouseenter="open = true"
        @mouseleave="open = false"
        class="fixed top-1/2 -translate-y-1/2 right-0 z-50 hidden md:block"
>
    <div class="bg-primary rounded-l-xl shadow-lg overflow-hidden transition-all duration-300 ease-in-out"
         :class="open ? 'max-w-xs' : 'max-w-[4rem]'">

        <div class="flex flex-col">

            @foreach ($items as $item)
                <a href="{{ $item->link }}"
                   target="{{ $item->target }}"
                   rel="{{ $item->rel ?? ($item->target === '_blank' ? 'noopener noreferrer' : '') }}"
                   class="group flex items-center h-16 px-5 text-white hover:bg-black/20 hover:text-white transition-colors duration-200">
                    <div class="flex-shrink-0">
                        <i class="{{ $item->icon }} text-xl w-6 text-center text-white group-hover:text-white transition-colors"></i>
                    </div>
                    <div class="ml-3 transition-opacity duration-300" :class="open ? 'opacity-100' : 'opacity-0'">
                        <span class="font-semibold whitespace-nowrap text-sm">{{ $item->title }}</span>
                    </div>
                </a>
            @endforeach

        </div>
    </div>
</div>