@props([
    'item',
    'depth' => 0,
])

@php
    $isSection = $item->item_type === 'section';
    $children = $item->childrenRecursive ?? collect();
    $hasChildren = $children->isNotEmpty();
    
    // Depth'e göre görsel derinlik
    $depthColors = [
        0 => 'bg-white dark:bg-gray-900 border-gray-200 dark:border-gray-700',
        1 => 'bg-sky-50 dark:bg-sky-800/50 border-sky-200 dark:border-sky-700',
        2 => 'bg-teal-100 dark:bg-teal-800/50 border-teal-200 dark:border-teal-700',
        3 => 'bg-amber-100 dark:bg-amber-800/50 border-amber-200 dark:border-amber-700',
        4 => 'bg-pink-100 dark:bg-pink-800/50 border-pink-200 dark:border-pink-700',
    ];
    $bgColor = $depthColors[min($depth, 4)] ?? $depthColors[4];
    
    $shadowClass = $depth === 0 ? 'shadow-sm' : ($depth === 1 ? 'shadow' : '');
@endphp

@if($isSection)
    {{-- Section/Header: Ayırıcı olarak göster --}}
    <li
        class="relative {{ $depth > 0 ? 'mt-4' : '' }}"
        wire:key="menu-item-{{ $item->id }}"
        data-item-id="{{ $item->id }}"
        x-sort:item="{{ $item->id }}"
        style="margin-left: {{ $depth * 1 }}rem;"
    >
        <div class="rounded-md transition-colors duration-200" 
             :style="isExpanded({{ $item->id }}) && {{ $hasChildren ? 'true' : 'false' }} ? 'background-color: color-mix(in srgb, var(--color-accent) 10%, transparent);' : ''">
        <div class="flex items-center gap-2 py-2 group" 
             :class="isExpanded({{ $item->id }}) && {{ $hasChildren ? 'true' : 'false' }} ? 'px-2 pt-2' : ''">
            <x-button 
                variant="secondary" 
                size="sm" 
                icon="grip-vertical" 
                x-sort:handle
                title="Drag to reorder"
                class="shrink-0 cursor-move opacity-40 group-hover:opacity-100 transition-opacity"
            ></x-button>

            @if($hasChildren)
                <x-button 
                    variant="secondary" 
                    size="sm" 
                    icon="chevron-right"
                    @click="toggleExpand({{ $item->id }})"
                    class="shrink-0 opacity-40 group-hover:opacity-100 transition-opacity transition-transform duration-200"
                    ::class="isExpanded({{ $item->id }}) ? 'rotate-90' : ''"
                ></x-button>
            @endif

            <div class="flex-1 flex items-center gap-2">
                <div class="flex-1 border-t border-gray-300 dark:border-gray-600"></div>
                <span class="text-xs font-semibold uppercase tracking-wider text-gray-500 dark:text-gray-400 px-2 flex items-center gap-2">
                    {{ $item->label }}
                    @if(! $item->is_active)
                        <x-badge variant="secondary" size="xs">Inactive</x-badge>
                    @endif
                </span>
                <div class="flex-1 border-t border-gray-300 dark:border-gray-600"></div>
            </div>

            <div class="flex items-center gap-1 shrink-0 opacity-40 group-hover:opacity-100 transition-opacity">
                <x-button variant="secondary" size="xs" icon="pencil" wire:click="edit({{ $item->id }})" title="Edit"></x-button>
                <x-button variant="error" size="xs" icon="trash" wire:click="confirmDelete({{ $item->id }})" title="Delete"></x-button>
            </div>
        </div>

        @if($hasChildren)
            <div class="px-2 pb-2" x-show="isExpanded({{ $item->id }})" x-collapse>
                <ul
                    class="space-y-2.5 list-none relative min-h-[10px]"
                    x-sort="handleSort"
                    x-sort:group="'menu-items'"
                    data-parent-id="{{ $item->id }}"
                >
                    @foreach($children as $child)
                        @include('livewire.admin.partials.menu-tree-item', [
                            'item' => $child,
                            'depth' => $depth + 1,
                        ])
                    @endforeach
                </ul>
            </div>
        @endif
        </div>
    </li>
@else
    {{-- Normal Menu Item --}}
    {{-- Normal Menu Item --}}
    <li
        class="border rounded-md {{ $bgColor }} {{ $shadowClass }} px-3 py-2.5 transition-all hover:shadow-xs"
        wire:key="menu-item-{{ $item->id }}"
        data-item-id="{{ $item->id }}"
        x-sort:item="{{ $item->id }}"
        style="margin-left: {{ $depth * 1 }}rem;"
    >
        <div class="flex items-start gap-3">
            <div class="flex items-center gap-2">
                <x-button 
                    variant="secondary" 
                    size="sm" 
                    icon="grip-vertical" 
                    x-sort:handle
                    title="Drag to reorder"
                    class="shrink-0 cursor-move"
                ></x-button>

                @if($hasChildren)
                    <x-button 
                        variant="secondary" 
                        size="sm" 
                        icon="chevron-right"
                        @click="toggleExpand({{ $item->id }})"
                        class="shrink-0 transition-transform duration-200"
                        ::class="isExpanded({{ $item->id }}) ? 'rotate-90' : ''"
                    ></x-button>
                @endif

                <div class="flex-1">
                    <div class="flex items-center gap-2">
                        @if($item->icon)
                            <i class="fa-solid fa-{{ $item->icon }} text-gray-500 dark:text-gray-400"></i>
                        @endif
                        <span class="font-medium text-gray-900 dark:text-gray-100">{{ $item->label }}</span>
                        @if(! $item->is_active)
                            <x-badge variant="secondary">Inactive</x-badge>
                        @endif
                        @if($item->badge_text)
                            <x-badge variant="{{ $item->badge_color ?? 'primary' }}">{{ $item->badge_text }}</x-badge>
                        @endif
                    </div>
                </div>

                <div class="flex items-center gap-1 shrink-0">
                    <x-button variant="secondary" size="sm" icon="pencil" wire:click="edit({{ $item->id }})" title="Edit"></x-button>
                    <x-button variant="error" size="sm" icon="trash" wire:click="confirmDelete({{ $item->id }})" title="Delete"></x-button>
                </div>
            </div>
        </div>

        @if($hasChildren && !$isSection)
            <div class="mt-3 relative" x-show="isExpanded({{ $item->id }})" x-collapse>
                {{-- Görsel bağlantı çizgisi - sadece normal item'lar için --}}
                <div class="absolute left-0 top-0 bottom-0 w-px {{ $depth === 0 ? 'bg-indigo-200 dark:bg-indigo-700' : ($depth === 1 ? 'bg-gray-300 dark:bg-gray-600' : 'bg-gray-300/50 dark:bg-gray-600/50') }} ml-4"></div>
                <ul
                    class="space-y-2.5 list-none pl-6 relative min-h-[10px]"
                    x-sort="handleSort"
                    x-sort:group="'menu-items'"
                    data-parent-id="{{ $item->id }}"
                >
                    @foreach($children as $child)
                        @include('livewire.admin.partials.menu-tree-item', [
                            'item' => $child,
                            'depth' => $depth + 1,
                        ])
                    @endforeach
                </ul>
            </div>
        @endif
    </li>
@endif
