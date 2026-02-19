@if($item->link === '#')
    <span class="text-zinc-400">#</span>
@else
    <a href="{{ $item->link }}" target="{{ $item->target }}"
        class="text-[var(--color-accent)] hover:underline truncate max-w-[200px] block">
        {{ $item->link }}
    </a>
@endif