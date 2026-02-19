<div>
    <div class="text-sm text-zinc-900 dark:text-white">{{ $item->registered_at?->format('d-m-Y H:i') ?? '—' }}</div>
    @if($item->attended_at)
        <div class="text-sm text-green-600 dark:text-green-400">Attended: {{ $item->attended_at->format('d-m-Y H:i') }}</div>
    @endif
</div>
