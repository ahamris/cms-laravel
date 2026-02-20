@php
    $isSticky = $isSticky ?? false;
@endphp
@php
    $logoUrl = get_image(get_setting('site_logo'), asset('front/images/logo.svg'));
    $siteName = get_setting('site_name', 'Your Company');
@endphp
<!-- Include this script tag or install `@tailwindplus/elements` via npm: -->
<!-- <script src="https://cdn.jsdelivr.net/npm/@tailwindplus/elements@1" type="module"></script> -->
<header class="bg-white dark:bg-gray-900 {{ $isSticky ? 'sticky top-0 z-50' : '' }}">
    <nav aria-label="Global" class="mx-auto flex max-w-7xl items-center justify-between p-6 lg:px-8">
        <a href="{{ route('home') }}" class="-m-1.5 p-1.5">
            <span class="sr-only">{{ $siteName }}</span>
            <img src="{{ $logoUrl }}" alt="{{ $siteName }}" class="h-8 w-auto dark:hidden" width="200" height="50" />
            <img src="{{ $logoUrl }}" alt="{{ $siteName }}" class="h-8 w-auto not-dark:hidden" width="200" height="50" />
        </a>
        <div class="flex lg:hidden">
            <button type="button" command="show-modal" commandfor="mobile-menu"
                class="-m-2.5 inline-flex items-center justify-center rounded-md p-2.5 text-gray-700 dark:text-gray-400">
                <span class="sr-only">Open main menu</span>
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" data-slot="icon"
                    aria-hidden="true" class="size-6">
                    <path d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" stroke-linecap="round"
                        stroke-linejoin="round" />
                </svg>
            </button>
        </div>
        <div class="hidden lg:flex lg:gap-x-12">
            <el-popover-group class="hidden lg:flex lg:gap-x-12">
                @if(!empty($megaMenuData))
                    @foreach($megaMenuData as $index => $menuItem)
                        @php
                            $flyoutName = 'simple';
                        @endphp
                        @if(!empty($menuItem['children']))
                            <x-dynamic-component :component="'front.flyout-menus.' . $flyoutName" :item="$menuItem"
                                :index="$index" />
                        @else
                            <a href="{{ $menuItem['url'] ?? '#' }}" class="text-sm/6 font-semibold text-gray-900 dark:text-white">
                                {{ $menuItem['title'] }}
                            </a>
                        @endif
                    @endforeach
                @endif
            </el-popover-group>
            <div class="pl-4 border-l border-gray-200">
                <x-front.header-actions />
            </div>
        </div>
    </nav>
    <el-dialog>
        <dialog id="mobile-menu" class="backdrop:bg-transparent lg:hidden">
            <div tabindex="0" class="fixed inset-0 focus:outline-none">
                <el-dialog-panel
                    class="fixed inset-y-0 right-0 z-50 w-full overflow-y-auto bg-white p-6 sm:max-w-sm sm:ring-1 sm:ring-gray-900/10 dark:bg-gray-900 dark:sm:ring-gray-100/10">
                    <div class="flex items-center justify-between">
                        <a href="{{ route('home') }}" class="-m-1.5 p-1.5">
                            <span class="sr-only">{{ $siteName }}</span>
                            <img src="{{ $logoUrl }}" alt="{{ $siteName }}" class="h-8 w-auto dark:hidden" width="200" height="50" />
                            <img src="{{ $logoUrl }}" alt="{{ $siteName }}" class="h-8 w-auto not-dark:hidden" width="200" height="50" />
                        </a>
                        <button type="button" command="close" commandfor="mobile-menu"
                            class="-m-2.5 rounded-md p-2.5 text-gray-700 dark:text-gray-400">
                            <span class="sr-only">Close menu</span>
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"
                                data-slot="icon" aria-hidden="true" class="size-6">
                                <path d="M6 18 18 6M6 6l12 12" stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                        </button>
                    </div>
                    <div class="mt-6 flow-root">
                        <div class="-my-6 divide-y divide-gray-500/10 dark:divide-white/10">
                            <div class="space-y-2 py-6">
                                @if(!empty($megaMenuData))
                                    @foreach($megaMenuData as $index => $menuItem)
                                        @if(!empty($menuItem['children']))
                                            <div class="-mx-3">
                                                <button type="button" command="--toggle" commandfor="mobile-sub-{{ $index }}"
                                                    class="flex w-full items-center justify-between rounded-lg py-2 pr-3.5 pl-3 text-base/7 font-semibold text-gray-900 hover:bg-gray-50 dark:text-white dark:hover:bg-white/5">
                                                    {{ $menuItem['title'] }}
                                                    <svg viewBox="0 0 20 20" fill="currentColor" data-slot="icon" aria-hidden="true"
                                                        class="size-5 flex-none in-aria-expanded:rotate-180">
                                                        <path
                                                            d="M5.22 8.22a.75.75 0 0 1 1.06 0L10 11.94l3.72-3.72a.75.75 0 1 1 1.06 1.06l-4.25 4.25a.75.75 0 0 1-1.06 0L5.22 9.28a.75.75 0 0 1 0-1.06Z"
                                                            clip-rule="evenodd" fill-rule="evenodd" />
                                                    </svg>
                                                </button>
                                                <el-disclosure id="mobile-sub-{{ $index }}" hidden class="mt-2 block space-y-2">
                                                    @foreach($menuItem['children'] as $child)
                                                        <a href="{{ $child['url'] ?? '#' }}"
                                                            class="block rounded-lg py-2 pr-3 pl-6 text-sm/7 font-semibold text-gray-900 hover:bg-gray-50 dark:text-white dark:hover:bg-white/5">
                                                            {{ $child['title'] }}
                                                        </a>
                                                    @endforeach
                                                </el-disclosure>
                                            </div>
                                        @else
                                            <a href="{{ $menuItem['url'] ?? '#' }}"
                                                class="-mx-3 block rounded-lg px-3 py-2 text-base/7 font-semibold text-gray-900 hover:bg-gray-50 dark:text-white dark:hover:bg-white/5">
                                                {{ $menuItem['title'] }}
                                            </a>
                                        @endif
                                    @endforeach
                                @endif
                            </div>
                            <div class="py-6">
                                <x-front.header-actions :mobile="true" />
                            </div>
                        </div>
                    </div>
                </el-dialog-panel>
            </div>
        </dialog>
    </el-dialog>
</header>