<x-layouts.admin title="Contact: {{ $contact->organization_name }}">
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="font-bold text-zinc-900 dark:text-white mb-2">{{ $contact->organization_name }}</h1>
            <p class="text-zinc-600 dark:text-zinc-400">{{ $contact->email }} @if($contact->funnel_fase) &middot; <span class="font-medium">{{ ucfirst($contact->funnel_fase) }}</span> @endif</p>
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ route('admin.crm.contacts.edit', $contact) }}" class="inline-flex items-center gap-2 rounded-md bg-[var(--color-accent)] px-4 py-2 font-semibold text-white shadow-xs hover:opacity-90 transition-opacity"><i class="fa-solid fa-edit"></i> Edit</a>
            <a href="{{ route('admin.crm.contacts.index') }}" class="inline-flex items-center gap-2 rounded-md border border-zinc-300 dark:border-zinc-600 px-4 py-2 font-semibold text-zinc-700 dark:text-zinc-300">Back</a>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        {{-- Contact Info --}}
        <div class="bg-white dark:bg-zinc-800 rounded-xl shadow-sm border border-zinc-200 dark:border-zinc-700 p-6">
            <h2 class="font-semibold text-zinc-900 dark:text-white mb-4">Contact Info</h2>
            <dl class="space-y-3">
                <div><dt class="font-medium text-zinc-500 dark:text-zinc-400">Phone</dt><dd class="text-zinc-900 dark:text-white">{{ $contact->phone ?? '—' }}</dd></div>
                <div><dt class="font-medium text-zinc-500 dark:text-zinc-400">Website</dt><dd class="text-zinc-900 dark:text-white">{{ $contact->website ?? '—' }}</dd></div>
                <div><dt class="font-medium text-zinc-500 dark:text-zinc-400">Lead Score</dt><dd class="text-zinc-900 dark:text-white">{{ $contact->lead_score ?? 0 }}</dd></div>
                <div><dt class="font-medium text-zinc-500 dark:text-zinc-400">Lifecycle</dt><dd class="text-zinc-900 dark:text-white">{{ ucfirst($contact->lifecycle_stage ?? 'subscriber') }}</dd></div>
                <div><dt class="font-medium text-zinc-500 dark:text-zinc-400">Lead Source</dt><dd class="text-zinc-900 dark:text-white">{{ $contact->lead_source ?? '—' }}</dd></div>
            </dl>

            {{-- Funnel Stage Update --}}
            <form action="{{ route('admin.crm.contacts.update-funnel', $contact) }}" method="POST" class="mt-4">
                @csrf @method('PUT')
                <label class="block font-medium text-zinc-500 dark:text-zinc-400 mb-1">Funnel Stage</label>
                <div class="flex gap-2">
                    <select name="funnel_fase" class="flex-1 rounded-md border-zinc-300 dark:border-zinc-600 dark:bg-zinc-700 dark:text-white shadow-sm">
                        @foreach(['interesseer' => 'Attract', 'overtuig' => 'Convert', 'activeer' => 'Close', 'inspireer' => 'Delight'] as $k => $v)
                            <option value="{{ $k }}" {{ $contact->funnel_fase === $k ? 'selected' : '' }}>{{ $v }}</option>
                        @endforeach
                    </select>
                    <button type="submit" class="px-3 py-2 rounded-md bg-[var(--color-accent)] text-white font-medium">Update</button>
                </div>
            </form>
        </div>

        {{-- Activity Timeline --}}
        <div class="lg:col-span-2 bg-white dark:bg-zinc-800 rounded-xl shadow-sm border border-zinc-200 dark:border-zinc-700 p-6">
            <h2 class="font-semibold text-zinc-900 dark:text-white mb-4">Activity Timeline</h2>
            <div class="space-y-4 max-h-[600px] overflow-y-auto">
                @forelse($timeline as $event)
                    <div class="flex items-start gap-3 pb-4 border-b border-zinc-100 dark:border-zinc-700 last:border-0">
                        <div class="w-8 h-8 rounded-full flex items-center justify-center flex-shrink-0
                            @if($event['type'] === 'deal') bg-green-100 dark:bg-green-900/30
                            @elseif($event['type'] === 'ticket') bg-red-100 dark:bg-red-900/30
                            @elseif($event['type'] === 'appointment') bg-blue-100 dark:bg-blue-900/30
                            @elseif($event['type'] === 'note') bg-yellow-100 dark:bg-yellow-900/30
                            @else bg-zinc-100 dark:bg-zinc-700 @endif">
                            <i class="fa-solid
                                @if($event['type'] === 'deal') fa-handshake text-green-600
                                @elseif($event['type'] === 'ticket') fa-ticket text-red-600
                                @elseif($event['type'] === 'appointment') fa-calendar text-blue-600
                                @elseif($event['type'] === 'note') fa-sticky-note text-yellow-600
                                @else fa-file text-zinc-500 @endif"></i>
                        </div>
                        <div class="flex-1">
                            <div class="flex items-center gap-2">
                                <span class="px-2 py-0.5 rounded bg-zinc-100 dark:bg-zinc-700 text-zinc-600 dark:text-zinc-400">{{ ucfirst($event['type']) }}</span>
                                <span class="text-zinc-400">{{ $event['date']->diffForHumans() }}</span>
                            </div>
                            <p class="text-zinc-900 dark:text-white mt-1">
                                @if($event['type'] === 'deal') {{ $event['data']->title }} — {{ $event['data']->formatted_value }}
                                @elseif($event['type'] === 'ticket') {{ $event['data']->subject }}
                                @elseif($event['type'] === 'appointment') {{ $event['data']->title }}
                                @elseif($event['type'] === 'note') {{ Str::limit($event['data']->body, 150) }}
                                @elseif($event['type'] === 'submission') Form: {{ $event['data']->form?->name }}
                                @endif
                            </p>
                        </div>
                    </div>
                @empty
                    <p class="text-zinc-500 dark:text-zinc-400">No activity yet.</p>
                @endforelse
            </div>
        </div>
    </div>

    {{-- Quick Add Note --}}
    <div class="bg-white dark:bg-zinc-800 rounded-xl shadow-sm border border-zinc-200 dark:border-zinc-700 p-6">
        <h2 class="font-semibold text-zinc-900 dark:text-white mb-4">Add Note</h2>
        <form action="{{ route('admin.crm.notes.store') }}" method="POST" class="flex gap-4">
            @csrf
            <input type="hidden" name="contact_id" value="{{ $contact->id }}">
            <textarea name="body" rows="2" required placeholder="Write a note..." class="flex-1 rounded-md border-zinc-300 dark:border-zinc-600 dark:bg-zinc-700 dark:text-white shadow-sm"></textarea>
            <button type="submit" class="self-end px-4 py-2 rounded-md bg-[var(--color-accent)] text-white font-medium">Add Note</button>
        </form>
    </div>
</div>
</x-layouts.admin>
