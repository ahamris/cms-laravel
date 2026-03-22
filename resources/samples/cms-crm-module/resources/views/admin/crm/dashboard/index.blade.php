<x-layouts.admin title="CRM — Inbound Marketing">

{{-- ══════════════════════════════════════════════════════════════
     PAGE HEADER
══════════════════════════════════════════════════════════════ --}}
<div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
    <div>
        <h1 class="text-2xl font-bold text-zinc-900 dark:text-white">CRM · Inbound Marketing</h1>
        <p class="text-sm text-zinc-500 dark:text-zinc-400 mt-1">
            Attract → Convert → Close → Delight
        </p>
    </div>
    <div class="flex items-center gap-2">
        <a href="{{ route('admin.crm.contacts.create') }}"
           class="inline-flex items-center gap-2 rounded-md bg-white dark:bg-white/10 px-3 py-2 text-sm font-medium text-zinc-700 dark:text-zinc-200 ring-1 ring-zinc-300 dark:ring-white/10 hover:bg-zinc-50 dark:hover:bg-white/20 transition-all">
            <i class="fa-solid fa-user-plus text-xs"></i> New Contact
        </a>
        <a href="{{ route('admin.crm.deals.create') }}"
           class="inline-flex items-center gap-2 rounded-md bg-[var(--color-accent)] px-3 py-2 text-sm font-medium text-white hover:opacity-90 transition-all">
            <i class="fa-solid fa-plus text-xs"></i> New Deal
        </a>
    </div>
</div>

{{-- ══════════════════════════════════════════════════════════════
     FUNNEL PIPELINE VISUAL
══════════════════════════════════════════════════════════════ --}}
<div class="rounded-xl border border-zinc-200 dark:border-white/10 bg-white dark:bg-white/5 p-6 mb-6 shadow-sm">
    <div class="flex items-center justify-between mb-5">
        <div class="text-sm font-semibold text-zinc-500 dark:text-zinc-400 uppercase tracking-widest">
            Inbound Funnel · Last 30 days
        </div>
        <select class="text-xs rounded-md border border-zinc-200 dark:border-white/10 bg-white dark:bg-white/10 px-3 py-1.5 text-zinc-700 dark:text-zinc-300">
            <option>Last 30 days</option>
            <option>Last 90 days</option>
            <option>This year</option>
        </select>
    </div>

    {{-- Funnel stages row --}}
    <div class="grid grid-cols-5 gap-0 items-center">

        {{-- Strangers --}}
        <div class="text-center px-2">
            <div class="w-16 h-16 mx-auto rounded-full border-2 border-dashed border-zinc-300 dark:border-zinc-600 flex items-center justify-center mb-2 bg-zinc-50 dark:bg-zinc-800">
                <i class="fa-solid fa-users text-zinc-400 text-xl"></i>
            </div>
            <div class="font-bold text-lg text-zinc-500">∞</div>
            <div class="text-xs font-semibold text-zinc-400 mt-0.5">Strangers</div>
            <div class="text-[10px] text-zinc-400 mt-1">Web traffic</div>
        </div>

        <div class="text-zinc-300 dark:text-zinc-600 text-2xl text-center pb-4">›</div>

        {{-- Visitors (Attract) --}}
        <a href="{{ route('admin.crm.funnel.attract') }}" class="text-center px-2 group">
            <div class="w-16 h-16 mx-auto rounded-full bg-gradient-to-br from-indigo-400 to-indigo-600 flex items-center justify-center mb-2 shadow-md group-hover:scale-105 transition-transform">
                <i class="fa-solid fa-eye text-white text-xl"></i>
            </div>
            <div class="font-bold text-xl text-indigo-600 dark:text-indigo-400">{{ number_format($stats['visitors'] ?? 2841) }}</div>
            <div class="text-xs font-semibold text-indigo-500 mt-0.5">Visitors</div>
            <div class="text-[10px] text-zinc-400 mt-1">Blog · SEO · Social</div>
        </a>

        <div class="text-zinc-300 dark:text-zinc-600 text-2xl text-center pb-4">›</div>

        {{-- Leads (Convert) --}}
        <a href="{{ route('admin.crm.funnel.convert') }}" class="text-center px-2 group">
            <div class="w-16 h-16 mx-auto rounded-full bg-gradient-to-br from-orange-400 to-orange-600 flex items-center justify-center mb-2 shadow-md group-hover:scale-105 transition-transform">
                <i class="fa-solid fa-user-plus text-white text-xl"></i>
            </div>
            <div class="font-bold text-xl text-orange-500">{{ number_format($stats['leads'] ?? 184) }}</div>
            <div class="text-xs font-semibold text-orange-500 mt-0.5">Leads</div>
            <div class="text-[10px] text-zinc-400 mt-1">Forms · CTAs · Pages</div>
        </a>

        <div class="text-zinc-300 dark:text-zinc-600 text-2xl text-center pb-4">›</div>

        {{-- Customers (Close) --}}
        <a href="{{ route('admin.crm.funnel.close') }}" class="text-center px-2 group">
            <div class="w-16 h-16 mx-auto rounded-full bg-gradient-to-br from-emerald-400 to-emerald-600 flex items-center justify-center mb-2 shadow-md group-hover:scale-105 transition-transform">
                <i class="fa-solid fa-handshake text-white text-xl"></i>
            </div>
            <div class="font-bold text-xl text-emerald-600 dark:text-emerald-400">{{ number_format($stats['customers'] ?? 23) }}</div>
            <div class="text-xs font-semibold text-emerald-500 mt-0.5">Customers</div>
            <div class="text-[10px] text-zinc-400 mt-1">CRM · Email · Deals</div>
        </a>

        <div class="text-zinc-300 dark:text-zinc-600 text-2xl text-center pb-4">›</div>

        {{-- Promoters (Delight) --}}
        <a href="{{ route('admin.crm.funnel.delight') }}" class="text-center px-2 group">
            <div class="w-16 h-16 mx-auto rounded-full bg-gradient-to-br from-purple-400 to-purple-600 flex items-center justify-center mb-2 shadow-md group-hover:scale-105 transition-transform">
                <i class="fa-solid fa-star text-white text-xl"></i>
            </div>
            <div class="font-bold text-xl text-purple-500">{{ number_format($stats['promoters'] ?? 9) }}</div>
            <div class="text-xs font-semibold text-purple-500 mt-0.5">Promoters</div>
            <div class="text-[10px] text-zinc-400 mt-1">Surveys · Smart Content</div>
        </a>
    </div>

    {{-- Conversion rates bar --}}
    <div class="mt-5 pt-4 border-t border-zinc-100 dark:border-white/5 grid grid-cols-4 gap-3 text-center">
        <div>
            <div class="text-xs text-zinc-400 mb-1">Strangers → Visitors</div>
            <div class="h-1.5 bg-zinc-100 dark:bg-zinc-700 rounded-full"><div class="h-full bg-indigo-500 rounded-full" style="width:100%"></div></div>
            <div class="text-xs font-semibold text-zinc-600 dark:text-zinc-300 mt-1">Organic traffic</div>
        </div>
        <div>
            <div class="text-xs text-zinc-400 mb-1">Visitors → Leads</div>
            <div class="h-1.5 bg-zinc-100 dark:bg-zinc-700 rounded-full"><div class="h-full bg-orange-500 rounded-full" style="width:6.5%"></div></div>
            <div class="text-xs font-semibold text-orange-500 mt-1">{{ $stats['visitor_to_lead_rate'] ?? '6.5%' }}</div>
        </div>
        <div>
            <div class="text-xs text-zinc-400 mb-1">Leads → Customers</div>
            <div class="h-1.5 bg-zinc-100 dark:bg-zinc-700 rounded-full"><div class="h-full bg-emerald-500 rounded-full" style="width:12.5%"></div></div>
            <div class="text-xs font-semibold text-emerald-500 mt-1">{{ $stats['lead_to_customer_rate'] ?? '12.5%' }}</div>
        </div>
        <div>
            <div class="text-xs text-zinc-400 mb-1">Customers → Promoters</div>
            <div class="h-1.5 bg-zinc-100 dark:bg-zinc-700 rounded-full"><div class="h-full bg-purple-500 rounded-full" style="width:39%"></div></div>
            <div class="text-xs font-semibold text-purple-500 mt-1">{{ $stats['nps_score'] ?? '72 NPS' }}</div>
        </div>
    </div>
</div>

{{-- ══════════════════════════════════════════════════════════════
     STAT CARDS
══════════════════════════════════════════════════════════════ --}}
<div class="grid grid-cols-2 sm:grid-cols-4 gap-4 mb-6">
    <a href="{{ route('admin.crm.contacts.index') }}"
       class="rounded-xl border border-zinc-200 dark:border-white/10 bg-white dark:bg-white/5 p-4 shadow-sm hover:shadow-md transition-all group">
        <div class="flex items-center gap-3 mb-3">
            <div class="w-9 h-9 rounded-lg bg-indigo-50 dark:bg-indigo-500/20 flex items-center justify-center">
                <i class="fa-solid fa-users text-indigo-500 text-sm"></i>
            </div>
            <span class="text-xs font-semibold text-zinc-500 dark:text-zinc-400 uppercase tracking-wide">Contacts</span>
        </div>
        <div class="text-2xl font-bold text-zinc-900 dark:text-white">{{ $stats['total_contacts'] ?? 34 }}</div>
        <div class="text-xs text-emerald-500 mt-1 flex items-center gap-1"><i class="fa-solid fa-arrow-up text-[10px]"></i> +5 this month</div>
    </a>

    <a href="{{ route('admin.crm.deals.index') }}"
       class="rounded-xl border border-zinc-200 dark:border-white/10 bg-white dark:bg-white/5 p-4 shadow-sm hover:shadow-md transition-all group">
        <div class="flex items-center gap-3 mb-3">
            <div class="w-9 h-9 rounded-lg bg-emerald-50 dark:bg-emerald-500/20 flex items-center justify-center">
                <i class="fa-solid fa-chart-line text-emerald-500 text-sm"></i>
            </div>
            <span class="text-xs font-semibold text-zinc-500 dark:text-zinc-400 uppercase tracking-wide">Pipeline</span>
        </div>
        <div class="text-2xl font-bold text-zinc-900 dark:text-white">€{{ number_format($stats['pipeline_value'] ?? 38400) }}</div>
        <div class="text-xs text-emerald-500 mt-1 flex items-center gap-1"><i class="fa-solid fa-arrow-up text-[10px]"></i> +€8k vs last month</div>
    </a>

    <a href="{{ route('admin.crm.tickets.index') }}"
       class="rounded-xl border border-zinc-200 dark:border-white/10 bg-white dark:bg-white/5 p-4 shadow-sm hover:shadow-md transition-all group">
        <div class="flex items-center gap-3 mb-3">
            <div class="w-9 h-9 rounded-lg bg-amber-50 dark:bg-amber-500/20 flex items-center justify-center">
                <i class="fa-solid fa-ticket text-amber-500 text-sm"></i>
            </div>
            <span class="text-xs font-semibold text-zinc-500 dark:text-zinc-400 uppercase tracking-wide">Open Tickets</span>
        </div>
        <div class="text-2xl font-bold text-zinc-900 dark:text-white">{{ $stats['open_tickets'] ?? 3 }}</div>
        <div class="text-xs text-red-500 mt-1 flex items-center gap-1"><i class="fa-solid fa-circle-exclamation text-[10px]"></i> 1 high priority</div>
    </a>

    <a href="{{ route('admin.crm.messages.index') }}"
       class="rounded-xl border border-zinc-200 dark:border-white/10 bg-white dark:bg-white/5 p-4 shadow-sm hover:shadow-md transition-all group">
        <div class="flex items-center gap-3 mb-3">
            <div class="w-9 h-9 rounded-lg bg-blue-50 dark:bg-blue-500/20 flex items-center justify-center">
                <i class="fa-solid fa-envelope text-blue-500 text-sm"></i>
            </div>
            <span class="text-xs font-semibold text-zinc-500 dark:text-zinc-400 uppercase tracking-wide">Unread</span>
        </div>
        <div class="text-2xl font-bold text-zinc-900 dark:text-white">{{ $stats['unread_messages'] ?? 5 }}</div>
        <div class="text-xs text-amber-500 mt-1 flex items-center gap-1"><i class="fa-solid fa-clock text-[10px]"></i> 2 need reply</div>
    </a>
</div>

{{-- ══════════════════════════════════════════════════════════════
     MAIN CONTENT GRID
══════════════════════════════════════════════════════════════ --}}
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

    {{-- LEFT: Activity Feed --}}
    <div class="lg:col-span-2 space-y-6">

        {{-- Recent CRM Activity --}}
        <div class="rounded-xl border border-zinc-200 dark:border-white/10 bg-white dark:bg-white/5 shadow-sm overflow-hidden">
            <div class="flex items-center justify-between px-5 py-4 border-b border-zinc-100 dark:border-white/5">
                <h2 class="text-sm font-semibold text-zinc-900 dark:text-white flex items-center gap-2">
                    <i class="fa-solid fa-bolt text-[var(--color-accent)] text-xs"></i>
                    Funnel Activity
                </h2>
                <div class="flex gap-1">
                    @foreach(['All','Attract','Convert','Close','Delight'] as $stage)
                    <button onclick="filterActivity(this, '{{ strtolower($stage) }}')"
                            class="text-xs px-2 py-1 rounded-md {{ $loop->first ? 'bg-[var(--color-accent)] text-white' : 'text-zinc-500 hover:bg-zinc-100 dark:hover:bg-white/10' }} transition-all">
                        {{ $stage }}
                    </button>
                    @endforeach
                </div>
            </div>
            <div class="divide-y divide-zinc-50 dark:divide-white/5" id="activity-feed">
                @php
                $activities = [
                    ['icon'=>'fa-file-pen','color'=>'indigo','stage'=>'attract','title'=>'Article published','sub'=>'"10 redenen voor headless CMS" · autopilot · SEO: 88/100','time'=>'2 min ago','badge'=>'Attract','badge_color'=>'bg-indigo-100 text-indigo-700 dark:bg-indigo-500/20 dark:text-indigo-300'],
                    ['icon'=>'fa-envelope','color'=>'orange','stage'=>'convert','title'=>'Form submitted','sub'=>'Sophie van Dam · Van Dam Agency · Demo aanvragen','time'=>'18 min ago','badge'=>'Convert','badge_color'=>'bg-orange-100 text-orange-700 dark:bg-orange-500/20 dark:text-orange-300'],
                    ['icon'=>'fa-chart-line','color'=>'emerald','stage'=>'close','title'=>'Deal moved to Proposal','sub'=>'Martijn de Vries · Innovate B.V. · €3,500/mo','time'=>'1 hour ago','badge'=>'Close','badge_color'=>'bg-emerald-100 text-emerald-700 dark:bg-emerald-500/20 dark:text-emerald-300'],
                    ['icon'=>'fa-calendar-check','color'=>'blue','stage'=>'close','title'=>'Demo booked','sub'=>'Anna Bakker · TechStart AMS · Thu 14:00–15:00','time'=>'2 hours ago','badge'=>'Close','badge_color'=>'bg-emerald-100 text-emerald-700 dark:bg-emerald-500/20 dark:text-emerald-300'],
                    ['icon'=>'fa-star','color'=>'purple','stage'=>'delight','title'=>'NPS score submitted','sub'=>'Jan Pieterse · 9/10 · "Excellent platform, highly recommend"','time'=>'Yesterday','badge'=>'Delight','badge_color'=>'bg-purple-100 text-purple-700 dark:bg-purple-500/20 dark:text-purple-300'],
                    ['icon'=>'fa-robot','color'=>'indigo','stage'=>'attract','title'=>'AI social post published','sub'=>'LinkedIn · "Headless CMS voordelen" · 124 impressions','time'=>'Yesterday','badge'=>'Attract','badge_color'=>'bg-indigo-100 text-indigo-700 dark:bg-indigo-500/20 dark:text-indigo-300'],
                ];
                @endphp

                @foreach($activities as $act)
                <div class="flex items-start gap-3 px-5 py-3.5 hover:bg-zinc-50 dark:hover:bg-white/5 transition-colors activity-row" data-stage="{{ $act['stage'] }}">
                    <div class="w-8 h-8 rounded-lg bg-{{ $act['color'] }}-50 dark:bg-{{ $act['color'] }}-500/20 flex items-center justify-center shrink-0 mt-0.5">
                        <i class="fa-solid {{ $act['icon'] }} text-{{ $act['color'] }}-500 text-xs"></i>
                    </div>
                    <div class="flex-1 min-w-0">
                        <div class="flex items-center gap-2">
                            <span class="text-sm font-medium text-zinc-900 dark:text-white">{{ $act['title'] }}</span>
                            <span class="text-[10px] font-semibold px-1.5 py-0.5 rounded-full {{ $act['badge_color'] }}">{{ $act['badge'] }}</span>
                        </div>
                        <div class="text-xs text-zinc-500 dark:text-zinc-400 mt-0.5 truncate">{{ $act['sub'] }}</div>
                    </div>
                    <div class="text-xs text-zinc-400 whitespace-nowrap shrink-0">{{ $act['time'] }}</div>
                </div>
                @endforeach
            </div>
        </div>

        {{-- AI Automation Status --}}
        <div class="rounded-xl border border-zinc-200 dark:border-white/10 bg-white dark:bg-white/5 shadow-sm overflow-hidden">
            <div class="flex items-center justify-between px-5 py-4 border-b border-zinc-100 dark:border-white/5">
                <h2 class="text-sm font-semibold text-zinc-900 dark:text-white flex items-center gap-2">
                    <i class="fa-solid fa-robot text-[var(--color-accent)] text-xs"></i>
                    AI Automation — Active Workflows
                </h2>
                <a href="{{ route('admin.marketing.intent-briefs.create') }}"
                   class="text-xs font-medium text-[var(--color-accent)] hover:underline">
                    + Intent Brief
                </a>
            </div>
            <div class="p-4 grid grid-cols-1 sm:grid-cols-2 gap-3">
                @php
                $workflows = [
                    ['icon'=>'fa-file-pen','color'=>'indigo','name'=>'Content Autopilot','desc'=>'3 articles generated this week','status'=>'active','status_label'=>'Running','next'=>'Next: Tue 09:00'],
                    ['icon'=>'fa-envelope-open-text','color'=>'emerald','name'=>'Lead Nurture Sequence','desc'=>'47 contacts · 5 active sequences','status'=>'active','status_label'=>'Running','next'=>'Step 3/5 in progress'],
                    ['icon'=>'fa-share-nodes','color'=>'blue','name'=>'Social Auto-posting','desc'=>'LinkedIn + X · 12 posts scheduled','status'=>'active','status_label'=>'Scheduled','next'=>'Next: Today 17:00'],
                    ['icon'=>'fa-magnifying-glass-chart','color'=>'amber','name'=>'SEO Gap Analysis','desc'=>'Last run: 2 days ago · 8 found','status'=>'idle','status_label'=>'Idle','next'=>'Manual trigger'],
                ];
                @endphp
                @foreach($workflows as $wf)
                <div class="flex items-start gap-3 rounded-lg border border-zinc-100 dark:border-white/5 p-3 bg-zinc-50 dark:bg-white/5">
                    <div class="w-8 h-8 rounded-lg bg-{{ $wf['color'] }}-100 dark:bg-{{ $wf['color'] }}-500/20 flex items-center justify-center shrink-0">
                        <i class="fa-solid {{ $wf['icon'] }} text-{{ $wf['color'] }}-500 text-xs"></i>
                    </div>
                    <div class="flex-1 min-w-0">
                        <div class="flex items-center justify-between gap-2">
                            <span class="text-xs font-semibold text-zinc-800 dark:text-zinc-100">{{ $wf['name'] }}</span>
                            @if($wf['status'] === 'active')
                                <span class="flex items-center gap-1 text-[10px] font-semibold text-emerald-600 dark:text-emerald-400">
                                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span>{{ $wf['status_label'] }}
                                </span>
                            @else
                                <span class="text-[10px] font-medium text-zinc-400">{{ $wf['status_label'] }}</span>
                            @endif
                        </div>
                        <div class="text-xs text-zinc-500 dark:text-zinc-400 mt-0.5">{{ $wf['desc'] }}</div>
                        <div class="text-[10px] text-zinc-400 mt-1">{{ $wf['next'] }}</div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>

    {{-- RIGHT: Quick access + pipeline --}}
    <div class="space-y-5">

        {{-- Quick CRM links --}}
        <div class="rounded-xl border border-zinc-200 dark:border-white/10 bg-white dark:bg-white/5 shadow-sm overflow-hidden">
            <div class="px-5 py-4 border-b border-zinc-100 dark:border-white/5">
                <h2 class="text-sm font-semibold text-zinc-900 dark:text-white flex items-center gap-2">
                    <i class="fa-solid fa-grid-2 text-[var(--color-accent)] text-xs"></i> CRM Modules
                </h2>
            </div>
            <div class="p-3 grid grid-cols-2 gap-2">
                @php
                $modules = [
                    ['icon'=>'fa-users','color'=>'indigo','label'=>'Contacts','route'=>'admin.crm.contacts.index','count'=>34],
                    ['icon'=>'fa-chart-line','color'=>'emerald','label'=>'Deals','route'=>'admin.crm.deals.index','count'=>'€38k'],
                    ['icon'=>'fa-ticket','color'=>'amber','label'=>'Tickets','route'=>'admin.crm.tickets.index','count'=>3],
                    ['icon'=>'fa-envelope','color'=>'blue','label'=>'Messages','route'=>'admin.crm.messages.index','count'=>5],
                    ['icon'=>'fa-calendar-days','color'=>'purple','label'=>'Appointments','route'=>'admin.crm.appointments.index','count'=>2],
                    ['icon'=>'fa-note-sticky','color'=>'yellow','label'=>'Notes','route'=>'admin.crm.notes.index','count'=>18],
                ];
                @endphp
                @foreach($modules as $mod)
                <a href="{{ route($mod['route']) }}"
                   class="flex flex-col items-center gap-1.5 rounded-lg border border-zinc-100 dark:border-white/5 p-3 text-center hover:border-[var(--color-accent)] hover:bg-indigo-50 dark:hover:bg-[var(--color-accent)]/10 transition-all group">
                    <div class="w-8 h-8 rounded-lg bg-{{ $mod['color'] }}-50 dark:bg-{{ $mod['color'] }}-500/20 flex items-center justify-center group-hover:scale-110 transition-transform">
                        <i class="fa-solid {{ $mod['icon'] }} text-{{ $mod['color'] }}-500 text-sm"></i>
                    </div>
                    <span class="text-xs font-medium text-zinc-700 dark:text-zinc-300">{{ $mod['label'] }}</span>
                    <span class="text-[10px] font-bold text-zinc-400">{{ $mod['count'] }}</span>
                </a>
                @endforeach
            </div>
        </div>

        {{-- Deal Pipeline --}}
        <div class="rounded-xl border border-zinc-200 dark:border-white/10 bg-white dark:bg-white/5 shadow-sm overflow-hidden">
            <div class="flex items-center justify-between px-5 py-4 border-b border-zinc-100 dark:border-white/5">
                <h2 class="text-sm font-semibold text-zinc-900 dark:text-white flex items-center gap-2">
                    <i class="fa-solid fa-funnel-dollar text-[var(--color-accent)] text-xs"></i> Pipeline
                </h2>
                <a href="{{ route('admin.crm.deals.index') }}" class="text-xs text-[var(--color-accent)] hover:underline">View all</a>
            </div>
            <div class="p-4 space-y-3">
                @php
                $deals = [
                    ['name'=>'Martijn de Vries','org'=>'Innovate B.V.','stage'=>'Proposal','value'=>'€3,500/mo','pct'=>65,'color'=>'emerald'],
                    ['name'=>'Sophie van Dam','org'=>'Van Dam Agency','stage'=>'Qualified','value'=>'€1,200/mo','pct'=>35,'color'=>'amber'],
                    ['name'=>'Anna Bakker','org'=>'TechStart AMS','stage'=>'Lead','value'=>'€1,800/mo','pct'=>15,'color'=>'zinc'],
                ];
                @endphp
                @foreach($deals as $deal)
                <div>
                    <div class="flex items-center justify-between text-xs mb-1">
                        <span class="font-medium text-zinc-800 dark:text-zinc-100">{{ $deal['name'] }}</span>
                        <span class="font-semibold text-{{ $deal['color'] === 'zinc' ? 'zinc-500' : $deal['color'].'-500' }}">{{ $deal['value'] }}</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <div class="flex-1 h-1.5 bg-zinc-100 dark:bg-zinc-700 rounded-full overflow-hidden">
                            <div class="h-full bg-{{ $deal['color'] === 'zinc' ? 'zinc-400' : $deal['color'].'-500' }} rounded-full transition-all"
                                 style="width: {{ $deal['pct'] }}%"></div>
                        </div>
                        <span class="text-[10px] text-zinc-400 whitespace-nowrap">{{ $deal['stage'] }}</span>
                    </div>
                </div>
                @endforeach
            </div>
        </div>

        {{-- Upcoming appointments --}}
        <div class="rounded-xl border border-zinc-200 dark:border-white/10 bg-white dark:bg-white/5 shadow-sm overflow-hidden">
            <div class="flex items-center justify-between px-5 py-4 border-b border-zinc-100 dark:border-white/5">
                <h2 class="text-sm font-semibold text-zinc-900 dark:text-white flex items-center gap-2">
                    <i class="fa-solid fa-calendar-check text-[var(--color-accent)] text-xs"></i> Upcoming
                </h2>
                <a href="{{ route('admin.crm.appointments.index') }}" class="text-xs text-[var(--color-accent)] hover:underline">Calendar</a>
            </div>
            <div class="divide-y divide-zinc-50 dark:divide-white/5">
                @php
                $appointments = [
                    ['title'=>'Demo · Anna Bakker','time'=>'Today · 09:00','color'=>'blue','icon'=>'fa-laptop'],
                    ['title'=>'Follow-up · Martijn de Vries','time'=>'Mon 24 Mar · 15:00','color'=>'purple','icon'=>'fa-phone'],
                    ['title'=>'Demo · Sophie van Dam','time'=>'Wed 26 Mar · 14:00','color'=>'amber','icon'=>'fa-laptop'],
                ];
                @endphp
                @foreach($appointments as $apt)
                <div class="flex items-center gap-3 px-4 py-3">
                    <div class="w-1.5 h-1.5 rounded-full bg-{{ $apt['color'] }}-500 shrink-0"></div>
                    <div class="flex-1">
                        <div class="text-xs font-medium text-zinc-800 dark:text-zinc-100">{{ $apt['title'] }}</div>
                        <div class="text-[10px] text-zinc-400 mt-0.5">{{ $apt['time'] }}</div>
                    </div>
                    <i class="fa-solid {{ $apt['icon'] }} text-zinc-300 text-xs"></i>
                </div>
                @endforeach
            </div>
        </div>

    </div>
</div>

@push('scripts')
<script>
function filterActivity(btn, stage) {
    // Update button styles
    document.querySelectorAll('#activity-feed').forEach(() => {});
    btn.closest('.flex').querySelectorAll('button').forEach(b => {
        b.className = b.className.replace('bg-[var(--color-accent)] text-white', 'text-zinc-500 hover:bg-zinc-100 dark:hover:bg-white/10');
    });
    btn.className = btn.className.replace('text-zinc-500 hover:bg-zinc-100 dark:hover:bg-white/10', 'bg-[var(--color-accent)] text-white');

    // Filter rows
    document.querySelectorAll('.activity-row').forEach(row => {
        if (stage === 'all' || row.dataset.stage === stage) {
            row.style.display = '';
        } else {
            row.style.display = 'none';
        }
    });
}
</script>
@endpush

</x-layouts.admin>
