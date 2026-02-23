@php
    $variants = [
        'new' => 'sky',
        'improved' => 'success',
        'fixed' => 'warning',
        'api' => 'primary',
    ];
    $labels = [
        'new' => 'New Feature',
        'improved' => 'Improvement',
        'fixed' => 'Bug Fix',
        'api' => 'API Update',
    ];
    $variant = $variants[$item->status ?? ''] ?? 'secondary';
    $label = $labels[$item->status ?? ''] ?? ucfirst($item->status ?? '');
@endphp
<x-ui.badge :variant="$variant" size="sm">{{ $label }}</x-ui.badge>
