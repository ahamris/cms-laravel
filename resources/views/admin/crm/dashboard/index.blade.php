<x-layouts.admin title="CRM Dashboard">
<div class="space-y-6">
    {{-- Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="font-bold text-zinc-900 dark:text-white">CRM Dashboard</h1>
            <p class="text-zinc-500 dark:text-zinc-400 mt-1">Attract &rarr; Convert &rarr; Close &rarr; Delight</p>
        </div>
        <div class="flex items-center gap-2">
            <a href="{{ route('admin.crm.contacts.create') }}" class="inline-flex items-center gap-2 rounded-md bg-white dark:bg-white/10 px-3 py-2 font-medium text-zinc-700 dark:text-zinc-200 ring-1 ring-zinc-300 dark:ring-white/10 hover:bg-zinc-50 dark:hover:bg-white/20 transition-all">
                <i class="fa-solid fa-user-plus"></i> New Contact
            </a>
            <a href="{{ route('admin.crm.deals.create') }}" class="inline-flex items-center gap-2 rounded-md bg-[var(--color-accent)] px-3 py-2 font-medium text-white hover:opacity-90 transition-all">
                <i class="fa-solid fa-plus"></i> New Deal
            </a>
        </div>
    </div>

    {{-- Funnel Pipeline --}}
    <div class="rounded-xl border border-zinc-200 dark:border-white/10 bg-white dark:bg-white/5 p-6 shadow-sm">
        <h2 class="font-semibold text-zinc-500 dark:text-zinc-400 uppercase tracking-widest mb-5">Inbound Funnel</h2>
        <div class="grid grid-cols-4 gap-4">
            @foreach($funnelStages as $key => $stage)
                <div class="text-center p-4 rounded-lg bg-zinc-50 dark:bg-zinc-800">
                    <div class="font-bold text-zinc-900 dark:text-white">{{ $stage['count'] }}</div>
                    <div class="font-semibold text-zinc-500 dark:text-zinc-400 mt-1">{{ $stage['label'] }}</div>
                    <div class="text-zinc-400 mt-0.5">{{ ucfirst($key) }}</div>
                </div>
            @endforeach
        </div>
    </div>

    {{-- Stats Grid --}}
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
        <div class="rounded-xl border border-zinc-200 dark:border-white/10 bg-white dark:bg-white/5 p-5">
            <div class="text-zinc-500 dark:text-zinc-400"><i class="fa-solid fa-users mr-2"></i>Total Contacts</div>
            <div class="font-bold text-zinc-900 dark:text-white mt-2">{{ $stats['total_contacts'] }}</div>
        </div>
        <div class="rounded-xl border border-zinc-200 dark:border-white/10 bg-white dark:bg-white/5 p-5">
            <div class="text-zinc-500 dark:text-zinc-400"><i class="fa-solid fa-handshake mr-2"></i>Open Deals</div>
            <div class="font-bold text-zinc-900 dark:text-white mt-2">{{ $stats['open_deals'] }}</div>
            <div class="text-zinc-400 mt-1">&euro;{{ number_format($stats['pipeline_value'] / 100, 0, ',', '.') }} pipeline</div>
        </div>
        <div class="rounded-xl border border-zinc-200 dark:border-white/10 bg-white dark:bg-white/5 p-5">
            <div class="text-zinc-500 dark:text-zinc-400"><i class="fa-solid fa-ticket mr-2"></i>Open Tickets</div>
            <div class="font-bold text-zinc-900 dark:text-white mt-2">{{ $stats['open_tickets'] }}</div>
        </div>
        <div class="rounded-xl border border-zinc-200 dark:border-white/10 bg-white dark:bg-white/5 p-5">
            <div class="text-zinc-500 dark:text-zinc-400"><i class="fa-solid fa-envelope mr-2"></i>Unread Messages</div>
            <div class="font-bold text-zinc-900 dark:text-white mt-2">{{ $stats['unread_messages'] }}</div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        {{-- Deal Pipeline Preview --}}
        <div class="rounded-xl border border-zinc-200 dark:border-white/10 bg-white dark:bg-white/5 p-6">
            <h2 class="font-semibold text-zinc-900 dark:text-white mb-4">Deal Pipeline</h2>
            <div class="space-y-3">
                @foreach($dealsByStage as $stage => $count)
                    <div class="flex items-center justify-between">
                        <span class="font-medium text-zinc-700 dark:text-zinc-300">{{ ucfirst($stage) }}</span>
                        <div class="flex items-center gap-3">
                            <div class="w-32 bg-zinc-200 dark:bg-zinc-700 rounded-full h-2">
                                <div class="bg-[var(--color-accent)] h-2 rounded-full" style="width: {{ $stats['open_deals'] > 0 ? ($count / $stats['open_deals']) * 100 : 0 }}%"></div>
                            </div>
                            <span class="font-medium text-zinc-900 dark:text-white w-8 text-right">{{ $count }}</span>
                        </div>
                    </div>
                @endforeach
            </div>
            <a href="{{ route('admin.crm.deals.index') }}" class="inline-block mt-4 text-[var(--color-accent)] hover:underline font-medium">View all deals &rarr;</a>
        </div>

        {{-- Upcoming Appointments --}}
        <div class="rounded-xl border border-zinc-200 dark:border-white/10 bg-white dark:bg-white/5 p-6">
            <h2 class="font-semibold text-zinc-900 dark:text-white mb-4">Upcoming Appointments</h2>
            @forelse($upcomingAppointments as $apt)
                <div class="flex items-center justify-between py-2 border-b border-zinc-100 dark:border-zinc-700 last:border-0">
                    <div>
                        <div class="font-medium text-zinc-900 dark:text-white">{{ $apt->title }}</div>
                        <div class="text-zinc-500 dark:text-zinc-400">{{ $apt->contact?->organization_name }} &middot; {{ $apt->starts_at->format('M j, H:i') }}</div>
                    </div>
                    <span class="px-2 py-0.5 rounded bg-blue-100 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400">{{ ucfirst($apt->type) }}</span>
                </div>
            @empty
                <p class="text-zinc-500 dark:text-zinc-400">No upcoming appointments.</p>
            @endforelse
            <a href="{{ route('admin.crm.appointments.index') }}" class="inline-block mt-4 text-[var(--color-accent)] hover:underline font-medium">View calendar &rarr;</a>
        </div>
    </div>

    {{-- Recent Activity --}}
    <div class="rounded-xl border border-zinc-200 dark:border-white/10 bg-white dark:bg-white/5 p-6">
        <h2 class="font-semibold text-zinc-900 dark:text-white mb-4">Recent Activity</h2>
        <div class="space-y-3">
            @forelse($recentActivity as $note)
                <div class="flex items-start gap-3 py-2 border-b border-zinc-100 dark:border-zinc-700 last:border-0">
                    <div class="w-8 h-8 rounded-full bg-zinc-200 dark:bg-zinc-700 flex items-center justify-center flex-shrink-0">
                        <i class="fa-solid fa-sticky-note text-zinc-500 dark:text-zinc-400"></i>
                    </div>
                    <div class="flex-1 min-w-0">
                        <div class="font-medium text-zinc-900 dark:text-white">{{ $note->user?->name ?? 'System' }}</div>
                        <p class="text-zinc-600 dark:text-zinc-400 truncate">{{ Str::limit($note->body, 100) }}</p>
                        <div class="text-zinc-400 mt-1">{{ $note->created_at->diffForHumans() }} @if($note->contact) &middot; {{ $note->contact->organization_name }} @endif</div>
                    </div>
                </div>
            @empty
                <p class="text-zinc-500 dark:text-zinc-400">No recent activity.</p>
            @endforelse
        </div>
    </div>
</div>
</x-layouts.admin>
