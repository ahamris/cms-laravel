<div>
    <div class="text-sm text-zinc-900 dark:text-white">
        @if($item->email)
            <a href="mailto:{{ $item->email }}" class="text-[var(--color-accent)] hover:underline">{{ $item->email }}</a>
        @else
            <span class="text-zinc-400 dark:text-zinc-500">No email</span>
        @endif
    </div>
    <div class="text-sm text-zinc-500 dark:text-zinc-400 flex gap-2 mt-1">
        @if($item->linkedin_url)
            <a href="{{ $item->linkedin_url }}" target="_blank" rel="noopener" class="text-blue-600 dark:text-blue-400 hover:underline" title="LinkedIn"><i class="fab fa-linkedin"></i></a>
        @endif
        @if($item->twitter_url)
            <a href="{{ $item->twitter_url }}" target="_blank" rel="noopener" class="text-sky-400 hover:underline" title="Twitter"><i class="fab fa-twitter"></i></a>
        @endif
    </div>
</div>
