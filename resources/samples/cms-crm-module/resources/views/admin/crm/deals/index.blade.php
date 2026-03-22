<x-layouts.admin title="CRM · Deals Pipeline">

<div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
    <div>
        <h1 class="text-2xl font-bold text-zinc-900 dark:text-white">Deals Pipeline</h1>
        <p class="text-sm text-zinc-500 dark:text-zinc-400 mt-1">
            Close phase · Total pipeline: <strong class="text-emerald-600 dark:text-emerald-400">€{{ number_format($totalValue / 100) }}</strong>
        </p>
    </div>
    <div class="flex items-center gap-2">
        <a href="{{ route('admin.crm.dashboard') }}"
           class="inline-flex items-center gap-2 rounded-md bg-white dark:bg-white/10 px-3 py-2 text-sm font-medium text-zinc-700 dark:text-zinc-200 ring-1 ring-zinc-300 dark:ring-white/10 hover:bg-zinc-50 dark:hover:bg-white/20 transition-all">
            <i class="fa-solid fa-arrow-left text-xs"></i> CRM
        </a>
        <a href="{{ route('admin.crm.deals.create') }}"
           class="inline-flex items-center gap-2 rounded-md bg-[var(--color-accent)] px-3 py-2 text-sm font-medium text-white hover:opacity-90 transition-all">
            <i class="fa-solid fa-plus text-xs"></i> New Deal
        </a>
    </div>
</div>

{{-- Kanban board --}}
<div class="grid grid-cols-5 gap-3 overflow-x-auto pb-4" style="min-width: 900px;">
    @php
    $stages = [
        'lead'        => ['label'=>'Lead',        'color'=>'bg-zinc-100 dark:bg-zinc-700',     'text'=>'text-zinc-600 dark:text-zinc-300',    'dot'=>'bg-zinc-400'],
        'qualified'   => ['label'=>'Qualified',   'color'=>'bg-indigo-50 dark:bg-indigo-500/20','text'=>'text-indigo-600 dark:text-indigo-300','dot'=>'bg-indigo-500'],
        'proposal'    => ['label'=>'Proposal',    'color'=>'bg-orange-50 dark:bg-orange-500/20','text'=>'text-orange-600 dark:text-orange-300','dot'=>'bg-orange-500'],
        'negotiation' => ['label'=>'Negotiation', 'color'=>'bg-amber-50 dark:bg-amber-500/20',  'text'=>'text-amber-600 dark:text-amber-300',  'dot'=>'bg-amber-500'],
        'won'         => ['label'=>'Won ✓',        'color'=>'bg-emerald-50 dark:bg-emerald-500/20','text'=>'text-emerald-600 dark:text-emerald-300','dot'=>'bg-emerald-500'],
    ];
    @endphp

    @foreach($stages as $stageKey => $stageMeta)
    <div class="flex flex-col min-h-[400px] rounded-xl {{ $stageMeta['color'] }} p-2.5 border border-zinc-200 dark:border-white/5">
        {{-- Column header --}}
        <div class="flex items-center justify-between mb-3 px-0.5">
            <div class="flex items-center gap-2">
                <span class="w-2 h-2 rounded-full {{ $stageMeta['dot'] }}"></span>
                <span class="text-xs font-semibold {{ $stageMeta['text'] }}">{{ $stageMeta['label'] }}</span>
            </div>
            <span class="text-xs font-bold text-zinc-400 bg-white dark:bg-black/20 px-1.5 py-0.5 rounded-full">
                {{ ($dealsByStage[$stageKey] ?? collect())->count() }}
            </span>
        </div>

        {{-- Deal cards --}}
        <div class="space-y-2 flex-1" id="stage-{{ $stageKey }}">
            @forelse($dealsByStage[$stageKey] ?? [] as $deal)
            <a href="{{ route('admin.crm.deals.show', $deal) }}"
               class="block bg-white dark:bg-zinc-800 rounded-lg border border-zinc-200 dark:border-white/10 p-3 shadow-sm hover:shadow-md hover:border-[var(--color-accent)]/30 transition-all group">

                {{-- Deal title --}}
                <div class="text-sm font-semibold text-zinc-900 dark:text-white mb-1 group-hover:text-[var(--color-accent)] transition-colors">
                    {{ $deal->title }}
                </div>

                {{-- Contact --}}
                @if($deal->contact)
                <div class="flex items-center gap-1.5 text-xs text-zinc-500 dark:text-zinc-400 mb-2">
                    <div class="w-5 h-5 rounded-full bg-gradient-to-br from-indigo-400 to-purple-500 flex items-center justify-center text-white text-[9px] font-bold shrink-0">
                        {{ strtoupper(substr($deal->contact->first_name ?? 'C', 0, 1)) }}
                    </div>
                    {{ $deal->contact->first_name }} {{ $deal->contact->last_name }}
                    @if($deal->contact->organisation_name)
                    · <span class="truncate">{{ $deal->contact->organisation_name }}</span>
                    @endif
                </div>
                @endif

                {{-- Value + probability --}}
                <div class="flex items-center justify-between">
                    <span class="text-sm font-bold text-emerald-600 dark:text-emerald-400">
                        €{{ number_format($deal->value / 100) }}
                        @if($deal->currency === 'EUR')/mo @endif
                    </span>
                    <div class="flex items-center gap-1">
                        <div class="w-14 h-1.5 bg-zinc-100 dark:bg-zinc-700 rounded-full overflow-hidden">
                            <div class="h-full rounded-full {{ $deal->probability >= 70 ? 'bg-emerald-500' : ($deal->probability >= 40 ? 'bg-amber-400' : 'bg-zinc-300') }}"
                                 style="width: {{ $deal->probability }}%"></div>
                        </div>
                        <span class="text-[10px] text-zinc-400">{{ $deal->probability }}%</span>
                    </div>
                </div>

                @if($deal->expected_close_date)
                <div class="mt-1.5 text-[10px] text-zinc-400 flex items-center gap-1">
                    <i class="fa-regular fa-calendar"></i>
                    Close: {{ $deal->expected_close_date->format('d M') }}
                </div>
                @endif

                {{-- Stage move actions --}}
                @if($stageKey !== 'won')
                <div class="mt-2 pt-2 border-t border-zinc-50 dark:border-white/5 flex gap-1 opacity-0 group-hover:opacity-100 transition-opacity">
                    <form method="POST" action="{{ route('admin.crm.deals.move-stage', $deal) }}" class="flex-1">
                        @csrf
                        @php $nextStages = array_keys($stages); $nextIndex = array_search($stageKey, $nextStages); $nextStage = $nextStages[$nextIndex + 1] ?? null; @endphp
                        @if($nextStage)
                        <input type="hidden" name="stage" value="{{ $nextStage }}">
                        <button type="submit"
                                class="w-full text-[10px] font-medium py-1 rounded bg-zinc-50 dark:bg-white/10 text-zinc-500 hover:bg-[var(--color-accent)] hover:text-white transition-all">
                            → {{ $stages[$nextStage]['label'] }}
                        </button>
                        @endif
                    </form>
                    @if($stageKey === 'negotiation')
                    <form method="POST" action="{{ route('admin.crm.deals.mark-won', $deal) }}">
                        @csrf
                        <button type="submit"
                                class="px-2 text-[10px] font-medium py-1 rounded bg-emerald-50 dark:bg-emerald-500/20 text-emerald-600 dark:text-emerald-300 hover:bg-emerald-500 hover:text-white transition-all">
                            Won ✓
                        </button>
                    </form>
                    @endif
                </div>
                @else
                <div class="mt-1.5">
                    <span class="text-[10px] font-semibold text-emerald-500 flex items-center gap-1">
                        <i class="fa-solid fa-circle-check"></i> Closed won
                    </span>
                </div>
                @endif
            </a>
            @empty
            <div class="text-center py-6 text-xs text-zinc-400 opacity-50">
                <i class="fa-solid fa-inbox block mb-1"></i>
                No deals
            </div>
            @endforelse
        </div>

        {{-- Add deal in this stage --}}
        <a href="{{ route('admin.crm.deals.create', ['stage' => $stageKey]) }}"
           class="mt-2 w-full text-xs text-center py-2 rounded-lg border border-dashed border-zinc-300 dark:border-white/10 text-zinc-400 hover:border-[var(--color-accent)] hover:text-[var(--color-accent)] transition-all">
            + Add deal
        </a>
    </div>
    @endforeach
</div>

</x-layouts.admin>
