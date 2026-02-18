@props([
    'name' => '',
])

<div
    x-cloak
    x-show="active === '{{ $name }}'"
    id="{{ $name }}-tab-pane"
    role="tabpanel"
    aria-labelledby="{{ $name }}-tab"
    tabindex="0"
    {{ $attributes }}
>
    {{ $slot }}
</div>
