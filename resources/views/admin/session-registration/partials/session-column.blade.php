@php
    $session = $item->liveSession;
@endphp
<div>
    <div class="text-sm text-zinc-900 dark:text-white">{{ $session?->title ?? '—' }}</div>
    @if($session?->formatted_date)
        <div class="text-sm text-zinc-500 dark:text-zinc-400">{{ $session->formatted_date }}</div>
    @endif
</div>
