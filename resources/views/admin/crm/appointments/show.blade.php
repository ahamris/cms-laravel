<x-layouts.admin title="Appointment: {{ $appointment->title }}">
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="font-bold text-zinc-900 dark:text-white mb-2">{{ $appointment->title }}</h1>
            <p class="text-zinc-600 dark:text-zinc-400">
                {{ ucfirst(str_replace('_', ' ', $appointment->type)) }}
                &middot; {{ ucfirst(str_replace('_', ' ', $appointment->status)) }}
                @if($appointment->contact) &middot; {{ $appointment->contact->organization_name }} @endif
            </p>
        </div>
        <div class="flex items-center gap-3">
            @if($appointment->status === 'scheduled')
                <form action="{{ route('admin.crm.appointments.complete', $appointment) }}" method="POST" class="inline">
                    @csrf
                    <button type="submit" class="px-4 py-2 rounded-md bg-green-600 text-white font-medium hover:bg-green-700">Complete</button>
                </form>
            @endif
            <a href="{{ route('admin.crm.appointments.edit', $appointment) }}" class="px-4 py-2 rounded-md bg-[var(--color-accent)] text-white font-medium">Edit</a>
            <a href="{{ route('admin.crm.appointments.index') }}" class="px-4 py-2 rounded-md border border-zinc-300 dark:border-zinc-600 text-zinc-700 dark:text-zinc-300 font-medium">Back</a>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <div class="bg-white dark:bg-zinc-800 rounded-xl shadow-sm border border-zinc-200 dark:border-zinc-700 p-6">
            <h2 class="font-semibold text-zinc-900 dark:text-white mb-4">Details</h2>
            <dl class="space-y-3">
                <div>
                    <dt class="font-medium text-zinc-500 dark:text-zinc-400">Type</dt>
                    <dd class="text-zinc-900 dark:text-white">
                        <span class="px-2 py-0.5 rounded bg-blue-100 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400">{{ ucfirst(str_replace('_', ' ', $appointment->type)) }}</span>
                    </dd>
                </div>
                <div>
                    <dt class="font-medium text-zinc-500 dark:text-zinc-400">Status</dt>
                    <dd class="text-zinc-900 dark:text-white">{{ ucfirst(str_replace('_', ' ', $appointment->status)) }}</dd>
                </div>
                <div>
                    <dt class="font-medium text-zinc-500 dark:text-zinc-400">Start</dt>
                    <dd class="text-zinc-900 dark:text-white">{{ $appointment->starts_at->format('M j, Y \a\t H:i') }}</dd>
                </div>
                @if($appointment->ends_at)
                    <div>
                        <dt class="font-medium text-zinc-500 dark:text-zinc-400">End</dt>
                        <dd class="text-zinc-900 dark:text-white">{{ $appointment->ends_at->format('M j, Y \a\t H:i') }}</dd>
                    </div>
                @endif
                <div>
                    <dt class="font-medium text-zinc-500 dark:text-zinc-400">Location</dt>
                    <dd class="text-zinc-900 dark:text-white">
                        {{ $appointment->location ?? '—' }}
                        @if($appointment->is_online) <span class="ml-1 px-2 py-0.5 rounded bg-green-100 dark:bg-green-900/30 text-green-600 dark:text-green-400">Online</span> @endif
                    </dd>
                </div>
                <div>
                    <dt class="font-medium text-zinc-500 dark:text-zinc-400">Assigned To</dt>
                    <dd class="text-zinc-900 dark:text-white">{{ $appointment->assignedTo?->name ?? '—' }}</dd>
                </div>
            </dl>
        </div>

        <div class="space-y-6">
            @if($appointment->contact)
                <div class="bg-white dark:bg-zinc-800 rounded-xl shadow-sm border border-zinc-200 dark:border-zinc-700 p-6">
                    <h2 class="font-semibold text-zinc-900 dark:text-white mb-4">Contact</h2>
                    <p class="text-zinc-900 dark:text-white font-medium">{{ $appointment->contact->organization_name }}</p>
                    @if($appointment->contact->email)
                        <p class="text-zinc-600 dark:text-zinc-400 mt-1">{{ $appointment->contact->email }}</p>
                    @endif
                    <a href="{{ route('admin.crm.contacts.show', $appointment->contact) }}" class="text-[var(--color-accent)] hover:underline mt-2 inline-block">View Contact</a>
                </div>
            @endif

            @if($appointment->deal)
                <div class="bg-white dark:bg-zinc-800 rounded-xl shadow-sm border border-zinc-200 dark:border-zinc-700 p-6">
                    <h2 class="font-semibold text-zinc-900 dark:text-white mb-4">Linked Deal</h2>
                    <p class="text-zinc-900 dark:text-white font-medium">{{ $appointment->deal->title }}</p>
                    <p class="text-zinc-600 dark:text-zinc-400 mt-1">{{ ucfirst($appointment->deal->stage) }} &middot; {{ $appointment->deal->formatted_value }}</p>
                    <a href="{{ route('admin.crm.deals.show', $appointment->deal) }}" class="text-[var(--color-accent)] hover:underline mt-2 inline-block">View Deal</a>
                </div>
            @endif

            @if($appointment->notes)
                <div class="bg-white dark:bg-zinc-800 rounded-xl shadow-sm border border-zinc-200 dark:border-zinc-700 p-6">
                    <h2 class="font-semibold text-zinc-900 dark:text-white mb-4">Notes</h2>
                    <p class="text-zinc-700 dark:text-zinc-300 whitespace-pre-wrap">{{ $appointment->notes }}</p>
                </div>
            @endif
        </div>
    </div>
</div>
</x-layouts.admin>
