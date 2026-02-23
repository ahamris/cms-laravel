<div class="flex flex-wrap items-center gap-1">
    @php
        $statusVariant = match($item->status ?? '') {
            'registered' => 'sky',
            'attended' => 'success',
            'no_show' => 'warning',
            'cancelled' => 'error',
            default => 'secondary',
        };
    @endphp
    <x-ui.badge :variant="$statusVariant" size="sm">{{ $item->status_display }}</x-ui.badge>
    @if($item->marketing_consent)
        <x-ui.badge variant="success" size="sm">Marketing OK</x-ui.badge>
    @endif
</div>
