@php
    $component = $component ?? 'simple';
    $menuItem = $menuItem ?? null;
    $children = $children ?? [];
    
    // Convert menuItem to object if it's an array
    if (is_array($menuItem)) {
        $menuItem = (object) $menuItem;
    }
@endphp

@if(view()->exists('components.front.flyout-menus.' . $component))
    @include('components.front.flyout-menus.' . $component, [
        'menuItem' => $menuItem,
        'children' => $children
    ])
@else
    {{-- Fallback to simple if component doesn't exist --}}
    @include('components.front.flyout-menus.simple', [
        'menuItem' => $menuItem,
        'children' => $children
    ])
@endif
