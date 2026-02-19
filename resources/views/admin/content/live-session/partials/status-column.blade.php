<div class="flex flex-wrap items-center gap-1">
    @php
        $statusVariant = match($item->status ?? '') {
            'upcoming' => 'sky',
            'live' => 'success',
            'completed' => 'secondary',
            'cancelled' => 'error',
            default => 'secondary',
        };
    @endphp
    <x-ui.badge :variant="$statusVariant" size="sm">{{ $item->status_display }}</x-ui.badge>
    @if(!$item->is_active)
        <x-ui.badge variant="error" size="sm">Inactive</x-ui.badge>
    @endif
</div>
