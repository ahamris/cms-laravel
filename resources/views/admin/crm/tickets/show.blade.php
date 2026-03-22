<x-layouts.admin title="Ticket: {{ $ticket->subject }}">
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="font-bold text-zinc-900 dark:text-white mb-2">{{ $ticket->subject }}</h1>
            <p class="text-zinc-600 dark:text-zinc-400">{{ $ticket->contact?->organization_name }} &middot; {{ ucfirst($ticket->priority) }} priority &middot; {{ ucfirst(str_replace('_', ' ', $ticket->status)) }}</p>
        </div>
        <div class="flex items-center gap-3">
            <form action="{{ route('admin.crm.tickets.change-status', $ticket) }}" method="POST" class="flex gap-2">
                @csrf
                <select name="status" class="rounded-md border-zinc-300 dark:border-zinc-600 dark:bg-zinc-700 dark:text-white shadow-sm">
                    @foreach(['open', 'in_progress', 'waiting', 'resolved', 'closed'] as $s)
                        <option value="{{ $s }}" {{ $ticket->status === $s ? 'selected' : '' }}>{{ ucfirst(str_replace('_', ' ', $s)) }}</option>
                    @endforeach
                </select>
                <button type="submit" class="px-3 py-2 rounded-md bg-[var(--color-accent)] text-white font-medium">Update</button>
            </form>
            <a href="{{ route('admin.crm.tickets.index') }}" class="px-4 py-2 rounded-md border border-zinc-300 dark:border-zinc-600 text-zinc-700 dark:text-zinc-300 font-medium">Back</a>
        </div>
    </div>

    {{-- Description --}}
    <div class="bg-white dark:bg-zinc-800 rounded-xl shadow-sm border border-zinc-200 dark:border-zinc-700 p-6">
        <p class="text-zinc-700 dark:text-zinc-300 whitespace-pre-wrap">{{ $ticket->description }}</p>
    </div>

    {{-- Thread --}}
    <div class="bg-white dark:bg-zinc-800 rounded-xl shadow-sm border border-zinc-200 dark:border-zinc-700 p-6">
        <h2 class="font-semibold text-zinc-900 dark:text-white mb-4">Replies</h2>
        <div class="space-y-4 max-h-[500px] overflow-y-auto mb-4">
            @forelse($ticket->replies as $reply)
                <div class="flex {{ $reply->direction === 'outbound' ? 'justify-end' : 'justify-start' }}">
                    <div class="max-w-[70%] p-4 rounded-lg {{ $reply->direction === 'outbound' ? 'bg-[var(--color-accent)]/10 dark:bg-[var(--color-accent)]/20' : 'bg-zinc-100 dark:bg-zinc-700' }}">
                        <div class="flex items-center gap-2 mb-1">
                            <span class="font-medium text-zinc-900 dark:text-white">{{ $reply->user?->name ?? 'Customer' }}</span>
                            @if($reply->is_ai_generated)<span class="px-1.5 py-0.5 rounded bg-purple-100 dark:bg-purple-900/30 text-purple-600 dark:text-purple-400">AI</span>@endif
                            <span class="text-zinc-400">{{ $reply->created_at->diffForHumans() }}</span>
                        </div>
                        <p class="text-zinc-700 dark:text-zinc-300 whitespace-pre-wrap">{{ $reply->body }}</p>
                    </div>
                </div>
            @empty
                <p class="text-zinc-500 dark:text-zinc-400">No replies yet.</p>
            @endforelse
        </div>

        {{-- Reply Form --}}
        <form action="{{ route('admin.crm.tickets.reply', $ticket) }}" method="POST" class="border-t border-zinc-200 dark:border-zinc-700 pt-4">
            @csrf
            <textarea name="body" rows="3" required placeholder="Write a reply..." class="w-full rounded-md border-zinc-300 dark:border-zinc-600 dark:bg-zinc-700 dark:text-white shadow-sm mb-3"></textarea>
            <div class="flex items-center gap-3">
                <button type="submit" class="px-4 py-2 rounded-md bg-[var(--color-accent)] text-white font-medium">Send Reply</button>
                <button type="button" onclick="fetchAiDraft()" class="px-4 py-2 rounded-md bg-purple-600 text-white font-medium"><i class="fa-solid fa-robot mr-1"></i> AI Draft</button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
    async function fetchAiDraft() {
        try {
            const res = await fetch('{{ route('admin.crm.tickets.ai-reply', $ticket) }}', {
                method: 'POST', headers: {'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Content-Type': 'application/json', 'Accept': 'application/json'}
            });
            const data = await res.json();
            if (data.draft) document.querySelector('textarea[name="body"]').value = data.draft;
        } catch (e) { alert('AI draft failed.'); }
    }
</script>
@endpush
</x-layouts.admin>
