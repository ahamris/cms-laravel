<x-layouts.admin title="CRM Notes">
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="font-bold text-zinc-900 dark:text-white mb-2">Notes</h1>
            <p class="text-zinc-600 dark:text-zinc-400">{{ $notes->total() }} notes across all contacts and deals</p>
        </div>
    </div>

    {{-- Quick Add --}}
    <div class="bg-white dark:bg-zinc-800 rounded-xl shadow-sm border border-zinc-200 dark:border-zinc-700 p-4">
        <form action="{{ route('admin.crm.notes.store') }}" method="POST" class="flex items-end gap-4">
            @csrf
            <div class="flex-1">
                <label class="block font-medium text-zinc-700 dark:text-zinc-300 mb-1">Note</label>
                <textarea name="body" rows="2" required placeholder="Write a note..." class="w-full rounded-md border-zinc-300 dark:border-zinc-600 dark:bg-zinc-700 dark:text-white shadow-sm"></textarea>
            </div>
            <div class="w-40">
                <label class="block font-medium text-zinc-700 dark:text-zinc-300 mb-1">Type</label>
                <select name="type" class="w-full rounded-md border-zinc-300 dark:border-zinc-600 dark:bg-zinc-700 dark:text-white shadow-sm">
                    <option value="note">Note</option>
                    <option value="call_log">Call Log</option>
                    <option value="email_log">Email Log</option>
                    <option value="meeting_log">Meeting Log</option>
                </select>
            </div>
            <button type="submit" class="px-4 py-2 rounded-md bg-[var(--color-accent)] text-white font-semibold hover:opacity-90">Add</button>
        </form>
    </div>

    {{-- Notes List --}}
    <div class="space-y-3">
        @forelse($notes as $note)
            <div class="bg-white dark:bg-zinc-800 rounded-xl shadow-sm border border-zinc-200 dark:border-zinc-700 p-4 {{ $note->is_pinned ? 'ring-2 ring-yellow-400' : '' }}">
                <div class="flex items-center justify-between mb-2">
                    <div class="flex items-center gap-3">
                        @if($note->is_pinned) <i class="fa-solid fa-thumbtack text-yellow-500"></i> @endif
                        <span class="font-medium text-zinc-900 dark:text-white">{{ $note->user?->name ?? 'System' }}</span>
                        <span class="px-2 py-0.5 rounded bg-zinc-100 dark:bg-zinc-700 text-zinc-500 dark:text-zinc-400">{{ ucfirst(str_replace('_', ' ', $note->type)) }}</span>
                        <span class="text-zinc-400">{{ $note->created_at->diffForHumans() }}</span>
                    </div>
                    <div class="flex items-center gap-2">
                        @if($note->contact)
                            <a href="{{ route('admin.crm.contacts.show', $note->contact) }}" class="text-[var(--color-accent)] hover:underline">{{ $note->contact->organization_name }}</a>
                        @endif
                        <form action="{{ route('admin.crm.notes.toggle-pin', $note) }}" method="POST" class="inline">@csrf<button type="submit" class="text-zinc-400 hover:text-yellow-500"><i class="fa-solid fa-thumbtack"></i></button></form>
                        <form action="{{ route('admin.crm.notes.destroy', $note) }}" method="POST" class="inline">@csrf @method('DELETE')<button type="submit" class="text-zinc-400 hover:text-red-500"><i class="fa-solid fa-trash"></i></button></form>
                    </div>
                </div>
                <p class="text-zinc-700 dark:text-zinc-300">{{ $note->body }}</p>
            </div>
        @empty
            <div class="text-center py-8 text-zinc-500 dark:text-zinc-400">No notes yet.</div>
        @endforelse
    </div>
    <div class="mt-4">{{ $notes->links() }}</div>
</div>
</x-layouts.admin>
