@php
    $user = $item->user;
@endphp
<div class="text-sm">
    <div class="font-medium text-zinc-900 dark:text-white">{{ $user->name ?? '—' }}</div>
    @if($user && $user->email)
        <div class="text-xs text-zinc-500 dark:text-zinc-400">{{ $user->email }}</div>
    @endif
</div>
