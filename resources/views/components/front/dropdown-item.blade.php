@props(['item', 'level' => 1])

@php
    $menuItem = (object) $item;
    $hasChildren = !empty($menuItem->children);
@endphp

@if($hasChildren)
    {{-- Dropdown item with submenu --}}
    <div class="relative group/sub" x-data="{ isOpen: false }" 
         @mouseenter="isOpen = true" 
         @mouseleave="isOpen = false">
        <a href="{{ $menuItem->url !== '#' ? $menuItem->url : 'javascript:void(0)' }}" 
           class="flex items-center justify-between px-4 py-3 text-sm text-gray-700 hover:text-gray-900 hover:bg-gray-50 rounded-lg mx-2 group">
            <div class="flex items-center space-x-3">
                @if($menuItem->icon)
                    <div class="w-6 h-6 rounded-md flex items-center justify-center flex-shrink-0" 
                         style="background-color: {{ $menuItem->icon_bg_color ?? '#3B82F6' }}20">
                        <i class="{{ $menuItem->icon }} text-xs" style="color: {{ $menuItem->icon_bg_color ?? '#3B82F6' }}"></i>
                    </div>
                @endif
                <span class="font-medium">{{ $menuItem->title }}</span>
            </div>
            <i class="fas fa-chevron-right text-xs"></i>
        </a>
        
        {{-- Submenu --}}
        <div x-show="isOpen" 
             class="absolute top-0 left-full min-w-56 bg-white border border-gray-200 shadow-lg rounded-lg z-50 ml-2"
             x-cloak>
            <div class="py-2">
                @foreach($menuItem->children as $child)
                    <x-front.dropdown-item :item="$child" :level="$level + 1" />
                @endforeach
            </div>
        </div>
    </div>
@else
    {{-- Simple dropdown item --}}
    <a href="{{ $menuItem->url }}" 
       {{ $menuItem->open_in_new_tab ? 'target="_blank" rel="noopener noreferrer"' : '' }}
       class="flex items-center space-x-3 px-4 py-3 text-sm text-gray-700 hover:text-gray-900 hover:bg-gray-50 rounded-lg mx-2 group"
       style="padding-left: {{ ($level * 12) + 16 }}px">
        @if($menuItem->icon)
            <div class="w-6 h-6 rounded-md flex items-center justify-center flex-shrink-0" 
                 style="background-color: {{ $menuItem->icon_bg_color ?? '#6B7280' }}20">
                <i class="{{ $menuItem->icon }} text-xs" style="color: {{ $menuItem->icon_bg_color ?? '#6B7280' }}"></i>
            </div>
        @elseif($level > 1)
            <div class="w-2 h-2 bg-gray-400 rounded-full group-hover:bg-primary"></div>
        @endif
        <span class="font-medium">{{ $menuItem->title }}</span>
        @if($menuItem->badge_text)
            <span class="ml-auto text-xs px-2 py-1 rounded-full font-medium" 
                  style="background-color: {{ $menuItem->badge_color ?? '#10B981' }}; color: white;">
                {{ $menuItem->badge_text }}
            </span>
        @endif
    </a>
@endif