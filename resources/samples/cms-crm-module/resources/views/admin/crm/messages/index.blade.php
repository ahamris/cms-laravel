<x-layouts.admin title="CRM · Messages">

<div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
    <div>
        <h1 class="text-2xl font-bold text-zinc-900 dark:text-white">Messages</h1>
        <p class="text-sm text-zinc-500 dark:text-zinc-400 mt-1">
            Inbound form submissions · Convert phase
        </p>
    </div>
    <div class="flex items-center gap-2">
        <a href="{{ route('admin.crm.dashboard') }}"
           class="inline-flex items-center gap-2 rounded-md bg-white dark:bg-white/10 px-3 py-2 text-sm font-medium text-zinc-700 dark:text-zinc-200 ring-1 ring-zinc-300 dark:ring-white/10 hover:bg-zinc-50 dark:hover:bg-white/20 transition-all">
            <i class="fa-solid fa-arrow-left text-xs"></i> CRM
        </a>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 h-[calc(100vh-210px)] gap-0 rounded-xl border border-zinc-200 dark:border-white/10 overflow-hidden shadow-sm bg-white dark:bg-white/5"
     x-data="{ selected: {{ $messages->first()?->id ?? 'null' }} }">

    {{-- LEFT: Message List --}}
    <div class="border-r border-zinc-100 dark:border-white/5 flex flex-col overflow-hidden">
        <div class="p-3 border-b border-zinc-100 dark:border-white/5 space-y-2">
            <div class="relative">
                <i class="fa-solid fa-magnifying-glass absolute left-3 top-1/2 -translate-y-1/2 text-zinc-400 text-xs"></i>
                <input placeholder="Search messages…"
                       class="w-full rounded-md border border-zinc-200 dark:border-white/10 bg-zinc-50 dark:bg-white/5 pl-8 pr-3 py-1.5 text-sm focus:outline-none focus:ring-1 focus:ring-[var(--color-accent)]">
            </div>
            <div class="flex gap-1">
                @foreach(['All','New','Contacted','Resolved'] as $s)
                <button class="flex-1 text-xs py-1 rounded font-medium transition-all
                               {{ $loop->first ? 'bg-[var(--color-accent)] text-white' : 'text-zinc-500 hover:bg-zinc-100 dark:hover:bg-white/10' }}">
                    {{ $s }}
                </button>
                @endforeach
            </div>
        </div>

        <div class="overflow-y-auto flex-1">
            @forelse($messages as $message)
            <div @click="selected = {{ $message->id }}"
                 :class="selected === {{ $message->id }} ? 'bg-[var(--color-accent)]/10 border-l-2 border-[var(--color-accent)]' : 'hover:bg-zinc-50 dark:hover:bg-white/5'"
                 class="p-3 border-b border-zinc-50 dark:border-white/5 cursor-pointer transition-all">
                <div class="flex items-start justify-between gap-2 mb-1">
                    <span class="text-sm font-{{ $message->status === 'new' ? 'semibold' : 'medium' }} text-zinc-900 dark:text-white truncate">
                        {{ $message->full_name }}
                    </span>
                    <div class="flex items-center gap-1 shrink-0">
                        <span class="text-[10px] text-zinc-400">{{ $message->created_at->diffForHumans(null, true) }}</span>
                        @if($message->status === 'new')
                            <span class="w-2 h-2 rounded-full bg-[var(--color-accent)] shrink-0"></span>
                        @endif
                    </div>
                </div>
                <div class="text-xs text-zinc-500 dark:text-zinc-400 truncate">
                    {{ $message->reden }} — {{ Str::limit($message->bericht, 55) }}
                </div>
                <div class="mt-1.5 flex items-center gap-1.5">
                    @php
                    $statusStyles = [
                        'new'       => 'bg-orange-100 text-orange-700 dark:bg-orange-500/20 dark:text-orange-300',
                        'contacted' => 'bg-blue-100 text-blue-700 dark:bg-blue-500/20 dark:text-blue-300',
                        'resolved'  => 'bg-emerald-100 text-emerald-700 dark:bg-emerald-500/20 dark:text-emerald-300',
                        'closed'    => 'bg-zinc-100 text-zinc-500',
                    ];
                    @endphp
                    <span class="text-[10px] font-semibold px-1.5 py-0.5 rounded-full {{ $statusStyles[$message->status] ?? 'bg-zinc-100 text-zinc-500' }}">
                        {{ ucfirst($message->status) }}
                    </span>
                </div>
            </div>
            @empty
            <div class="p-8 text-center text-zinc-400">
                <i class="fa-solid fa-inbox text-2xl block mb-2 opacity-30"></i>
                <p class="text-sm">No messages yet</p>
            </div>
            @endforelse
        </div>
    </div>

    {{-- RIGHT: Message Detail --}}
    <div class="lg:col-span-2 flex flex-col overflow-hidden">
        @if($activeMessage = $messages->first())
        <div class="flex items-center justify-between p-4 border-b border-zinc-100 dark:border-white/5">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-full bg-gradient-to-br from-orange-400 to-orange-600 flex items-center justify-center text-white font-bold text-sm">
                    {{ strtoupper(substr($activeMessage->first_name, 0, 1)) }}{{ strtoupper(substr($activeMessage->last_name, 0, 1)) }}
                </div>
                <div>
                    <div class="font-semibold text-zinc-900 dark:text-white">{{ $activeMessage->full_name }}</div>
                    <div class="text-xs text-zinc-500">{{ $activeMessage->email }}{{ $activeMessage->company_name ? ' · ' . $activeMessage->company_name : '' }}</div>
                </div>
            </div>
            <div class="flex items-center gap-2">
                {{-- Convert to contact --}}
                <form method="POST" action="{{ route('admin.crm.messages.to-deal', $activeMessage) }}">
                    @csrf
                    <button type="submit"
                            class="inline-flex items-center gap-1.5 text-xs font-medium px-3 py-1.5 rounded-md border border-emerald-200 dark:border-emerald-500/30 bg-emerald-50 dark:bg-emerald-500/10 text-emerald-700 dark:text-emerald-300 hover:bg-emerald-100 transition-all">
                        <i class="fa-solid fa-chart-line text-[10px]"></i> → Deal
                    </button>
                </form>
                {{-- Convert to ticket --}}
                <form method="POST" action="{{ route('admin.crm.messages.to-ticket', $activeMessage) }}">
                    @csrf
                    <button type="submit"
                            class="inline-flex items-center gap-1.5 text-xs font-medium px-3 py-1.5 rounded-md border border-amber-200 dark:border-amber-500/30 bg-amber-50 dark:bg-amber-500/10 text-amber-700 dark:text-amber-300 hover:bg-amber-100 transition-all">
                        <i class="fa-solid fa-ticket text-[10px]"></i> → Ticket
                    </button>
                </form>
                <form method="POST" action="{{ route('admin.crm.messages.resolve', $activeMessage) }}">
                    @csrf
                    <button type="submit"
                            class="inline-flex items-center gap-1.5 text-xs font-medium px-3 py-1.5 rounded-md border border-zinc-200 dark:border-white/10 bg-zinc-50 dark:bg-white/5 text-zinc-600 dark:text-zinc-300 hover:bg-zinc-100 transition-all">
                        <i class="fa-solid fa-check text-[10px]"></i> Resolve
                    </button>
                </form>
            </div>
        </div>

        <div class="flex-1 overflow-y-auto p-5">
            {{-- Message meta --}}
            <div class="grid grid-cols-3 gap-3 mb-5">
                <div class="bg-zinc-50 dark:bg-white/5 rounded-lg p-3">
                    <div class="text-[10px] text-zinc-400 uppercase tracking-wider mb-1">Subject</div>
                    <div class="text-sm font-medium text-zinc-800 dark:text-zinc-100">{{ $activeMessage->reden }}</div>
                </div>
                <div class="bg-zinc-50 dark:bg-white/5 rounded-lg p-3">
                    <div class="text-[10px] text-zinc-400 uppercase tracking-wider mb-1">Received</div>
                    <div class="text-sm font-medium text-zinc-800 dark:text-zinc-100">{{ $activeMessage->created_at->format('D j M · H:i') }}</div>
                </div>
                <div class="bg-zinc-50 dark:bg-white/5 rounded-lg p-3">
                    <div class="text-[10px] text-zinc-400 uppercase tracking-wider mb-1">Funnel Stage</div>
                    <span class="text-xs font-semibold px-2 py-0.5 rounded-full bg-orange-100 text-orange-700 dark:bg-orange-500/20 dark:text-orange-300">Convert</span>
                </div>
            </div>

            {{-- Message body --}}
            <div class="bg-zinc-50 dark:bg-white/5 rounded-xl border border-zinc-100 dark:border-white/5 p-5 mb-5">
                <p class="text-sm text-zinc-700 dark:text-zinc-300 leading-relaxed whitespace-pre-wrap">{{ $activeMessage->bericht }}</p>
            </div>

            {{-- Reply thread (from ContactFormMessage) --}}
            @foreach($activeMessage->messages ?? [] as $reply)
            <div class="mb-3 flex {{ $reply->direction === 'outbound' ? 'justify-end' : '' }}">
                <div class="max-w-lg rounded-xl px-4 py-3 text-sm {{ $reply->direction === 'outbound' ? 'bg-[var(--color-accent)] text-white' : 'bg-zinc-100 dark:bg-white/10 text-zinc-800 dark:text-zinc-100' }}">
                    {{ $reply->message }}
                    <div class="text-[10px] opacity-60 mt-1">{{ $reply->created_at->diffForHumans() }}
                        @if($reply->direction === 'outbound') · {{ $reply->user?->name ?? 'Admin' }} @endif
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        {{-- Reply box --}}
        <div class="border-t border-zinc-100 dark:border-white/5 bg-white dark:bg-white/5">
            <form method="POST" action="{{ route('admin.crm.messages.reply', $activeMessage) }}"
                  x-data="{ body: '' }">
                @csrf
                <textarea name="message"
                          x-model="body"
                          placeholder="Write your reply…"
                          rows="3"
                          class="w-full px-4 py-3 text-sm bg-transparent border-0 resize-none focus:outline-none text-zinc-800 dark:text-zinc-100 placeholder:text-zinc-400"></textarea>
                <div class="flex items-center justify-between px-4 pb-3">
                    <button type="button"
                            hx-post="{{ route('admin.crm.messages.ai-reply', $activeMessage) }}"
                            hx-target="[name=message]"
                            hx-swap="value"
                            onclick="aiReply(this)"
                            class="inline-flex items-center gap-2 text-xs font-medium text-[var(--color-accent)] hover:underline">
                        <i class="fa-solid fa-wand-magic-sparkles text-[10px]"></i> AI Draft Reply
                    </button>
                    <button type="submit"
                            class="inline-flex items-center gap-2 px-4 py-1.5 rounded-md bg-[var(--color-accent)] text-white text-sm font-medium hover:opacity-90 transition-all">
                        <i class="fa-solid fa-paper-plane text-xs"></i> Send
                    </button>
                </div>
            </form>
        </div>
        @else
        <div class="flex-1 flex items-center justify-center text-zinc-400">
            <div class="text-center">
                <i class="fa-solid fa-inbox text-4xl block mb-3 opacity-20"></i>
                <p class="text-sm">Select a message</p>
            </div>
        </div>
        @endif
    </div>
</div>

@push('scripts')
<script>
function aiReply(btn) {
    btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin text-[10px]"></i> Generating…';
    btn.disabled = true;
    setTimeout(() => {
        const ta = btn.closest('form').querySelector('textarea');
        ta.value = 'Goedemiddag,\n\nBedankt voor uw bericht. Ik kom hier graag op terug.\n\nOp basis van uw vraag zou ik u het volgende willen toelichten...\n\nMet vriendelijke groet,\nStudio CMS';
        btn.innerHTML = '<i class="fa-solid fa-wand-magic-sparkles text-[10px]"></i> AI Draft Reply';
        btn.disabled = false;
    }, 1400);
}
</script>
@endpush

</x-layouts.admin>
