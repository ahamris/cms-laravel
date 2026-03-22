<x-layouts.admin title="CRM · Contacts">

<div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
    <div>
        <h1 class="text-2xl font-bold text-zinc-900 dark:text-white">Contacts</h1>
        <p class="text-sm text-zinc-500 dark:text-zinc-400 mt-1">{{ $contacts->total() }} contacts · CRM database</p>
    </div>
    <div class="flex items-center gap-2">
        <a href="{{ route('admin.crm.dashboard') }}"
           class="inline-flex items-center gap-2 rounded-md bg-white dark:bg-white/10 px-3 py-2 text-sm font-medium text-zinc-700 dark:text-zinc-200 ring-1 ring-zinc-300 dark:ring-white/10 hover:bg-zinc-50 dark:hover:bg-white/20 transition-all">
            <i class="fa-solid fa-arrow-left text-xs"></i> CRM
        </a>
        <a href="{{ route('admin.crm.contacts.create') }}"
           class="inline-flex items-center gap-2 rounded-md bg-[var(--color-accent)] px-3 py-2 text-sm font-medium text-white hover:opacity-90 transition-all">
            <i class="fa-solid fa-user-plus text-xs"></i> New Contact
        </a>
    </div>
</div>

{{-- Filters bar --}}
<div class="flex flex-wrap items-center gap-3 mb-5">
    <div class="flex-1 min-w-[220px]">
        <div class="relative">
            <i class="fa-solid fa-magnifying-glass absolute left-3 top-1/2 -translate-y-1/2 text-zinc-400 text-xs"></i>
            <input type="text"
                   placeholder="Search contacts, organisation, email…"
                   class="w-full rounded-md border border-zinc-200 dark:border-white/10 bg-white dark:bg-white/5 pl-9 pr-4 py-2 text-sm text-zinc-900 dark:text-zinc-100 placeholder:text-zinc-400 focus:outline-none focus:ring-2 focus:ring-[var(--color-accent)]/30 focus:border-[var(--color-accent)]">
        </div>
    </div>
    <div class="flex gap-1 flex-wrap">
        @foreach(['All','Customer','Lead','Prospect','Supplier'] as $type)
        <button class="text-xs px-3 py-1.5 rounded-md border font-medium transition-all
                       {{ $loop->first ? 'border-[var(--color-accent)] bg-[var(--color-accent)]/10 text-[var(--color-accent)]' : 'border-zinc-200 dark:border-white/10 text-zinc-500 hover:border-zinc-300 dark:hover:border-white/20' }}">
            {{ $type }}
        </button>
        @endforeach
    </div>
    <select class="text-sm rounded-md border border-zinc-200 dark:border-white/10 bg-white dark:bg-white/5 px-3 py-2 text-zinc-700 dark:text-zinc-300">
        <option>Funnel stage: All</option>
        <option>interesseer (Attract)</option>
        <option>overtuig (Convert)</option>
        <option>activeer (Close)</option>
        <option>inspireer (Delight)</option>
    </select>
</div>

{{-- Contact cards (grid view) + table toggle --}}
<div x-data="{ view: 'table' }" class="space-y-4">

    {{-- View toggle --}}
    <div class="flex justify-end gap-2">
        <button @click="view = 'cards'"
                :class="view === 'cards' ? 'bg-[var(--color-accent)] text-white' : 'bg-white dark:bg-white/10 text-zinc-500'"
                class="px-2.5 py-1.5 rounded-md text-xs border border-zinc-200 dark:border-white/10 transition-all">
            <i class="fa-solid fa-grid-2"></i>
        </button>
        <button @click="view = 'table'"
                :class="view === 'table' ? 'bg-[var(--color-accent)] text-white' : 'bg-white dark:bg-white/10 text-zinc-500'"
                class="px-2.5 py-1.5 rounded-md text-xs border border-zinc-200 dark:border-white/10 transition-all">
            <i class="fa-solid fa-list"></i>
        </button>
    </div>

    {{-- CARDS VIEW --}}
    <div x-show="view === 'cards'" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
        @forelse($contacts as $contact)
        <a href="{{ route('admin.crm.contacts.show', $contact) }}"
           class="rounded-xl border border-zinc-200 dark:border-white/10 bg-white dark:bg-white/5 p-5 shadow-sm hover:shadow-md hover:border-[var(--color-accent)]/30 transition-all block group">
            <div class="flex items-center gap-3 mb-4">
                {{-- SVG Avatar --}}
                <div class="w-11 h-11 rounded-full flex items-center justify-center text-white font-bold text-sm shrink-0"
                     style="background: linear-gradient(135deg, {{ $contact->avatar_color ?? '#6366f1' }}, {{ $contact->avatar_color2 ?? '#8b5cf6' }})">
                    {{ strtoupper(substr($contact->first_name ?? 'C', 0, 1)) }}{{ strtoupper(substr($contact->last_name ?? '', 0, 1)) }}
                </div>
                <div class="min-w-0">
                    <div class="text-sm font-semibold text-zinc-900 dark:text-white truncate">
                        {{ $contact->full_name ?? ($contact->first_name . ' ' . $contact->last_name) }}
                    </div>
                    <div class="text-xs text-zinc-500 dark:text-zinc-400 truncate">{{ $contact->organisation_name ?? $contact->company_name }}</div>
                </div>
            </div>
            <div class="space-y-1.5 text-xs text-zinc-500 dark:text-zinc-400 mb-4">
                @if($contact->email)
                <div class="flex items-center gap-2"><i class="fa-solid fa-envelope w-3"></i> {{ $contact->email }}</div>
                @endif
                @if($contact->phone)
                <div class="flex items-center gap-2"><i class="fa-solid fa-phone w-3"></i> {{ $contact->phone }}</div>
                @endif
            </div>
            <div class="flex items-center justify-between">
                <div class="flex gap-1 flex-wrap">
                    @if($contact->is_customer)
                    <span class="text-[10px] font-semibold px-2 py-0.5 rounded-full bg-emerald-100 text-emerald-700 dark:bg-emerald-500/20 dark:text-emerald-300">Customer</span>
                    @endif
                    @if($contact->is_supplier ?? false)
                    <span class="text-[10px] font-semibold px-2 py-0.5 rounded-full bg-blue-100 text-blue-700 dark:bg-blue-500/20 dark:text-blue-300">Supplier</span>
                    @endif
                    @if(!($contact->is_customer) && !($contact->is_supplier ?? false))
                    <span class="text-[10px] font-semibold px-2 py-0.5 rounded-full bg-zinc-100 text-zinc-500 dark:bg-zinc-700 dark:text-zinc-400">Prospect</span>
                    @endif
                </div>
                <span class="text-[10px] text-zinc-400 group-hover:text-[var(--color-accent)] transition-colors">View →</span>
            </div>
        </a>
        @empty
        <div class="col-span-3 text-center py-16 text-zinc-400">
            <i class="fa-solid fa-users text-4xl mb-3 block opacity-30"></i>
            <p class="font-medium text-zinc-600 dark:text-zinc-300">No contacts yet</p>
            <p class="text-sm mt-1">Add your first contact to get started</p>
            <a href="{{ route('admin.crm.contacts.create') }}" class="mt-4 inline-flex items-center gap-2 text-sm font-medium text-[var(--color-accent)] hover:underline">
                <i class="fa-solid fa-plus text-xs"></i> Add contact
            </a>
        </div>
        @endforelse
    </div>

    {{-- TABLE VIEW --}}
    <div x-show="view === 'table'"
         class="rounded-xl border border-zinc-200 dark:border-white/10 bg-white dark:bg-white/5 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-zinc-50 dark:bg-white/5 border-b border-zinc-100 dark:border-white/5">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-zinc-500 dark:text-zinc-400 uppercase tracking-wider">Contact</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-zinc-500 dark:text-zinc-400 uppercase tracking-wider">Organisation</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-zinc-500 dark:text-zinc-400 uppercase tracking-wider">Funnel Stage</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-zinc-500 dark:text-zinc-400 uppercase tracking-wider">Type</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-zinc-500 dark:text-zinc-400 uppercase tracking-wider">Status</th>
                        <th class="px-4 py-3 text-right text-xs font-semibold text-zinc-500 dark:text-zinc-400 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-zinc-50 dark:divide-white/5">
                    @forelse($contacts as $contact)
                    <tr class="hover:bg-zinc-50 dark:hover:bg-white/5 transition-colors group">
                        <td class="px-4 py-3">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded-full flex items-center justify-center text-white text-xs font-bold shrink-0"
                                     style="background: linear-gradient(135deg, {{ $contact->avatar_color ?? '#6366f1' }}, {{ $contact->avatar_color2 ?? '#8b5cf6' }})">
                                    {{ strtoupper(substr($contact->first_name ?? 'C', 0, 1)) }}{{ strtoupper(substr($contact->last_name ?? '', 0, 1)) }}
                                </div>
                                <div>
                                    <a href="{{ route('admin.crm.contacts.show', $contact) }}"
                                       class="font-medium text-zinc-900 dark:text-white hover:text-[var(--color-accent)] transition-colors">
                                        {{ $contact->full_name ?? ($contact->first_name . ' ' . $contact->last_name) }}
                                    </a>
                                    <div class="text-xs text-zinc-400">{{ $contact->email }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-4 py-3 text-zinc-600 dark:text-zinc-400">{{ $contact->organisation_name ?? $contact->company_name ?? '—' }}</td>
                        <td class="px-4 py-3">
                            @php
                            $stageMap = ['interesseer'=>['Attract','bg-indigo-100 text-indigo-700 dark:bg-indigo-500/20 dark:text-indigo-300'],'overtuig'=>['Convert','bg-orange-100 text-orange-700 dark:bg-orange-500/20 dark:text-orange-300'],'activeer'=>['Close','bg-emerald-100 text-emerald-700 dark:bg-emerald-500/20 dark:text-emerald-300'],'inspireer'=>['Delight','bg-purple-100 text-purple-700 dark:bg-purple-500/20 dark:text-purple-300']];
                            $stage = $stageMap[$contact->funnel_fase ?? ''] ?? ['—','bg-zinc-100 text-zinc-500'];
                            @endphp
                            <span class="text-xs font-medium px-2 py-0.5 rounded-full {{ $stage[1] }}">{{ $stage[0] }}</span>
                        </td>
                        <td class="px-4 py-3">
                            @if($contact->is_customer)
                                <span class="text-xs font-medium px-2 py-0.5 rounded-full bg-emerald-100 text-emerald-700 dark:bg-emerald-500/20 dark:text-emerald-300">Customer</span>
                            @else
                                <span class="text-xs font-medium px-2 py-0.5 rounded-full bg-zinc-100 text-zinc-500 dark:bg-zinc-700 dark:text-zinc-400">Prospect</span>
                            @endif
                        </td>
                        <td class="px-4 py-3">
                            @if($contact->is_active ?? true)
                                <span class="flex items-center gap-1.5 text-xs text-emerald-600 dark:text-emerald-400">
                                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span> Active
                                </span>
                            @else
                                <span class="flex items-center gap-1.5 text-xs text-zinc-400">
                                    <span class="w-1.5 h-1.5 rounded-full bg-zinc-400"></span> Inactive
                                </span>
                            @endif
                        </td>
                        <td class="px-4 py-3">
                            <div class="flex items-center justify-end gap-1 opacity-0 group-hover:opacity-100 transition-opacity">
                                <a href="{{ route('admin.crm.contacts.show', $contact) }}"
                                   class="w-7 h-7 rounded-md bg-zinc-100 dark:bg-white/10 flex items-center justify-center text-zinc-500 hover:text-[var(--color-accent)] hover:bg-indigo-50 transition-all" title="View">
                                    <i class="fa-solid fa-eye text-xs"></i>
                                </a>
                                <a href="{{ route('admin.crm.contacts.edit', $contact) }}"
                                   class="w-7 h-7 rounded-md bg-zinc-100 dark:bg-white/10 flex items-center justify-center text-zinc-500 hover:text-[var(--color-accent)] hover:bg-indigo-50 transition-all" title="Edit">
                                    <i class="fa-solid fa-pen text-xs"></i>
                                </a>
                                <form method="POST" action="{{ route('admin.crm.contacts.destroy', $contact) }}" onsubmit="return confirm('Delete this contact?')" class="inline">
                                    @csrf @method('DELETE')
                                    <button type="submit"
                                            class="w-7 h-7 rounded-md bg-zinc-100 dark:bg-white/10 flex items-center justify-center text-zinc-500 hover:text-red-500 hover:bg-red-50 transition-all" title="Delete">
                                        <i class="fa-solid fa-trash text-xs"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-4 py-16 text-center text-zinc-400">
                            <i class="fa-solid fa-users text-3xl block mb-2 opacity-30"></i>
                            No contacts found
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        @if($contacts->hasPages())
        <div class="px-4 py-3 border-t border-zinc-100 dark:border-white/5">
            {{ $contacts->links() }}
        </div>
        @endif
    </div>
</div>

</x-layouts.admin>
