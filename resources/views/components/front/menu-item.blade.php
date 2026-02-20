@props([
    'item' => null,
    'level' => 0,
    'isMobile' => false
])

@php
    $menuItem = (object) $item;
    $hasChildren = !empty($menuItem->children);
    $isDropdown = $hasChildren && $level === 0;
@endphp

@if($isMobile)
    {{-- Mobile Menu Item --}}
    @if($hasChildren)
        <div x-data="{ open: false }" class="relative">
            <button @click="open = !open"
                    @keydown.enter.prevent="open = !open"
                    @keydown.space.prevent="open = !open"
                    :aria-expanded="open"
                    class="w-full flex items-center justify-between px-4 py-3 text-sm text-gray-700 hover:text-gray-900 hover:bg-gray-50 rounded-lg focus:outline-none"
                    style="padding-left: {{ ($level * 20) + 16 }}px">
                <div class="flex items-center space-x-3">
                    @if($menuItem->icon && $level === 0)
                        <div class="w-8 h-8 rounded-lg flex items-center justify-center flex-shrink-0"
                             style="background-color: {{ $menuItem->icon_bg_color ?? '#6B7280' }}20">
                            <i class="{{ $menuItem->icon }} text-sm"
                               style="color: {{ $menuItem->icon_bg_color ?? '#6B7280' }}"></i>
                        </div>
                    @endif
                    <span class="font-medium">{{ $menuItem->title }}</span>

                    <i class="fas fa-chevron-down text-xs"
                       :class="{ 'rotate-180': open }"></i>
                </div>
            </button>

            <div x-show="open"
                 class="mt-2 space-y-1 border-l-2 border-gray-100 ml-4">
                @foreach($menuItem->children as $child)
                    <x-front.menu-item :item="$child" :level="$level + 1" :is-mobile="true" />
                @endforeach
            </div>
        </div>
    @else
        <a href="{{ $menuItem->url }}"
           {{ $menuItem->open_in_new_tab ? 'target="_blank" rel="noopener noreferrer"' : '' }}
           class="flex items-center justify-between px-4 py-3 text-sm text-gray-700 hover:text-gray-900 hover:bg-gray-50 rounded-lg focus:outline-none"
           style="padding-left: {{ ($level * 20) + 16 }}px">
            <div class="flex items-center space-x-3">
                @if($menuItem->icon && $level <= 1)
                    <div class="w-8 h-8 rounded-lg flex items-center justify-center flex-shrink-0"
                         style="background-color: {{ $menuItem->icon_bg_color ?? '#6B7280' }}20">
                        <i class="{{ $menuItem->icon }} text-sm"
                           style="color: {{ $menuItem->icon_bg_color ?? '#6B7280' }}"></i>
                    </div>
                @endif
                <span class="font-medium">{{ $menuItem->title }}</span>

            </div>
        </a>
    @endif
@else
    {{-- Desktop Menu Item --}}
    @if($isDropdown)
        @php
            $flyoutName = 'simple';
            if (!view()->exists("components.front.flyout-menus.$flyoutName")) {
                $flyoutName = 'simple';
            }
        @endphp

        <x-dynamic-component
            :component="'front.flyout-menus.' . $flyoutName"
            :item="(array)$menuItem"
            :index="$loop->index ?? 0"
        />
    @else
        {{-- Simple Link --}}
        <a href="{{ $menuItem->url }}"
           {{ $menuItem->open_in_new_tab ? 'target="_blank" rel="noopener noreferrer"' : '' }}
           class="flex items-center gap-x-1 text-sm/6 font-semibold text-gray-900 hover:text-gray-600 dark:text-white dark:hover:text-gray-300">
            {{ $menuItem->title }}
        </a>
    @endif
@endif