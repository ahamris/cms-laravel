@php
    $seconds = $item->duration_seconds ?? 0;
    if ($seconds >= 3600) {
        $h = (int) floor($seconds / 3600);
        $m = (int) floor(($seconds % 3600) / 60);
        $s = $seconds % 60;
        $formatted = sprintf('%d:%02d:%02d', $h, $m, $s);
    } elseif ($seconds >= 60) {
        $m = (int) floor($seconds / 60);
        $s = $seconds % 60;
        $formatted = $s > 0 ? sprintf('%d:%02d', $m, $s) : $m . ' min';
    } else {
        $formatted = $seconds ? $seconds . ' sec' : '—';
    }
@endphp
{{ $formatted }}
