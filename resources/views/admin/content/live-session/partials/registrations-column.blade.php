@php
    $count = $item->registration_count ?? $item->registrations()->count();
    $max = $item->max_participants ?? 0;
    $pct = $max > 0 ? min(100, ($count / $max) * 100) : 0;
@endphp
<div>
    <div class="text-sm text-zinc-900 dark:text-white">{{ $count }}/{{ $max }}</div>
    <div class="w-full bg-zinc-200 dark:bg-zinc-700 rounded-full h-2 mt-1">
        <div class="bg-[var(--color-accent)] h-2 rounded-full" style="width: {{ $pct }}%"></div>
    </div>
</div>
