@if($item->price && (float) $item->price > 0)
    <x-ui.badge variant="success" size="sm">${{ number_format((float) $item->price, 2) }}</x-ui.badge>
@else
    <x-ui.badge variant="sky" size="sm">Free</x-ui.badge>
@endif
