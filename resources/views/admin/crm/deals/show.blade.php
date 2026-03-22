<x-layouts.admin title="Deal: {{ $deal->title }}">
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="font-bold text-zinc-900 dark:text-white mb-2">{{ $deal->title }}</h1>
            <p class="text-zinc-600 dark:text-zinc-400">{{ $deal->contact?->organization_name }} &middot; {{ $deal->formatted_value }}</p>
        </div>
        <div class="flex items-center gap-3">
            @if(!in_array($deal->stage, ['won', 'lost']))
                <form action="{{ route('admin.crm.deals.mark-won', $deal) }}" method="POST" class="inline">@csrf<button type="submit" class="px-4 py-2 rounded-md bg-green-600 text-white font-medium hover:bg-green-700">Mark Won</button></form>
                <form action="{{ route('admin.crm.deals.mark-lost', $deal) }}" method="POST" class="inline">@csrf<button type="submit" class="px-4 py-2 rounded-md bg-red-600 text-white font-medium hover:bg-red-700">Mark Lost</button></form>
            @endif
            <a href="{{ route('admin.crm.deals.edit', $deal) }}" class="px-4 py-2 rounded-md bg-[var(--color-accent)] text-white font-medium">Edit</a>
            <a href="{{ route('admin.crm.deals.index') }}" class="px-4 py-2 rounded-md border border-zinc-300 dark:border-zinc-600 text-zinc-700 dark:text-zinc-300 font-medium">Back</a>
        </div>
    </div>

    {{-- Stage Progression --}}
    <div class="flex items-center gap-1">
        @foreach(['lead', 'qualified', 'proposal', 'negotiation', 'won'] as $s)
            <div class="flex-1 h-2 rounded {{ $deal->stage === $s || array_search($s, ['lead','qualified','proposal','negotiation','won']) <= array_search($deal->stage, ['lead','qualified','proposal','negotiation','won']) ? 'bg-[var(--color-accent)]' : 'bg-zinc-200 dark:bg-zinc-700' }}"></div>
        @endforeach
    </div>
    <div class="flex justify-between">
        @foreach(['Lead', 'Qualified', 'Proposal', 'Negotiation', 'Won'] as $label)
            <span class="text-zinc-500 dark:text-zinc-400">{{ $label }}</span>
        @endforeach
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <div class="bg-white dark:bg-zinc-800 rounded-xl shadow-sm border border-zinc-200 dark:border-zinc-700 p-6">
            <h2 class="font-semibold text-zinc-900 dark:text-white mb-4">Details</h2>
            <dl class="space-y-3">
                <div><dt class="font-medium text-zinc-500 dark:text-zinc-400">Stage</dt><dd class="text-zinc-900 dark:text-white">{{ ucfirst($deal->stage) }}</dd></div>
                <div><dt class="font-medium text-zinc-500 dark:text-zinc-400">Value</dt><dd class="text-zinc-900 dark:text-white">{{ $deal->formatted_value }}</dd></div>
                <div><dt class="font-medium text-zinc-500 dark:text-zinc-400">Probability</dt><dd class="text-zinc-900 dark:text-white">{{ $deal->probability }}%</dd></div>
                <div><dt class="font-medium text-zinc-500 dark:text-zinc-400">Expected Close</dt><dd class="text-zinc-900 dark:text-white">{{ $deal->expected_close_date?->format('M j, Y') ?? '—' }}</dd></div>
                <div><dt class="font-medium text-zinc-500 dark:text-zinc-400">Assigned To</dt><dd class="text-zinc-900 dark:text-white">{{ $deal->assignedTo?->name ?? '—' }}</dd></div>
                @if($deal->description)<div class="col-span-2"><dt class="font-medium text-zinc-500 dark:text-zinc-400">Description</dt><dd class="text-zinc-900 dark:text-white">{{ $deal->description }}</dd></div>@endif
            </dl>

            @if(!in_array($deal->stage, ['won', 'lost']))
                <form action="{{ route('admin.crm.deals.move-stage', $deal) }}" method="POST" class="mt-4 flex gap-2">
                    @csrf
                    <select name="stage" class="flex-1 rounded-md border-zinc-300 dark:border-zinc-600 dark:bg-zinc-700 dark:text-white shadow-sm">
                        @foreach(['lead', 'qualified', 'proposal', 'negotiation'] as $s)
                            <option value="{{ $s }}" {{ $deal->stage === $s ? 'selected' : '' }}>{{ ucfirst($s) }}</option>
                        @endforeach
                    </select>
                    <button type="submit" class="px-4 py-2 rounded-md bg-[var(--color-accent)] text-white font-medium">Move Stage</button>
                </form>
            @endif
        </div>

        <div class="bg-white dark:bg-zinc-800 rounded-xl shadow-sm border border-zinc-200 dark:border-zinc-700 p-6">
            <h2 class="font-semibold text-zinc-900 dark:text-white mb-4">Notes</h2>
            <form action="{{ route('admin.crm.notes.store') }}" method="POST" class="mb-4">
                @csrf
                <input type="hidden" name="deal_id" value="{{ $deal->id }}">
                <input type="hidden" name="contact_id" value="{{ $deal->contact_id }}">
                <textarea name="body" rows="2" required placeholder="Add a note..." class="w-full rounded-md border-zinc-300 dark:border-zinc-600 dark:bg-zinc-700 dark:text-white shadow-sm mb-2"></textarea>
                <button type="submit" class="px-4 py-2 rounded-md bg-zinc-100 dark:bg-zinc-700 text-zinc-700 dark:text-zinc-300 font-medium">Add Note</button>
            </form>
            <div class="space-y-3 max-h-80 overflow-y-auto">
                @forelse($deal->notes as $note)
                    <div class="p-3 rounded-lg bg-zinc-50 dark:bg-zinc-700/50">
                        <div class="flex justify-between">
                            <span class="font-medium text-zinc-900 dark:text-white">{{ $note->user?->name ?? 'System' }}</span>
                            <span class="text-zinc-400">{{ $note->created_at->diffForHumans() }}</span>
                        </div>
                        <p class="text-zinc-600 dark:text-zinc-400 mt-1">{{ $note->body }}</p>
                    </div>
                @empty
                    <p class="text-zinc-500 dark:text-zinc-400">No notes yet.</p>
                @endforelse
            </div>
        </div>
    </div>
</div>
</x-layouts.admin>
