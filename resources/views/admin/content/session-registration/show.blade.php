<x-layouts.admin title="Registration Details">
    {{-- Page Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-8">
        <div>
            <div class="flex items-center gap-3 mb-2">
                <h1 class="text-3xl font-bold text-zinc-900 dark:text-white">Registration Details</h1>
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                    @if($sessionRegistration->status === 'registered') bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-300
                    @elseif($sessionRegistration->status === 'attended') bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-300
                    @elseif($sessionRegistration->status === 'no_show') bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-300
                    @else bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-300 @endif">
                    {{ $sessionRegistration->status_display }}
                </span>
            </div>
            <p class="text-zinc-600 dark:text-zinc-400">
                Participation details for
                <a href="{{ route('admin.content.live-session.show', $sessionRegistration->liveSession) }}"
                    class="text-primary hover:text-primary/80 font-medium">
                    {{ $sessionRegistration->liveSession->title }}
                </a>
            </p>
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ route('admin.content.session-registration.index') }}"
                class="inline-flex items-center gap-2 rounded-md bg-white dark:bg-white/10 px-4 py-2 text-sm font-semibold text-gray-900 dark:text-white shadow-xs ring-1 ring-gray-300 ring-inset dark:ring-white/10 hover:bg-gray-50 dark:hover:bg-white/20">
                <i class="fa-solid fa-arrow-left"></i>
                Back to List
            </a>
            <a href="{{ route('admin.content.session-registration.edit', $sessionRegistration) }}">
                <x-button variant="primary" icon="edit">Edit Registration</x-button>
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        {{-- Left Column - 2/3 --}}
        <div class="lg:col-span-2 space-y-8">
            {{-- Participant Information --}}
            <div class="rounded-md border border-zinc-200 dark:border-white/10 bg-white dark:bg-white/5 p-6 shadow-sm">
                <h2 class="text-lg font-bold text-gray-900 dark:text-white mb-6">Participant Information</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-zinc-500 dark:text-zinc-400 mb-1">Full Name</label>
                        <p class="text-zinc-900 dark:text-white font-medium">{{ $sessionRegistration->name }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-zinc-500 dark:text-zinc-400 mb-1">Email
                            Address</label>
                        <a href="mailto:{{ $sessionRegistration->email }}"
                            class="text-primary hover:text-primary/80 font-medium break-all">
                            {{ $sessionRegistration->email }}
                        </a>
                    </div>
                    <div class="md:col-span-2">
                        <label
                            class="block text-sm font-medium text-zinc-500 dark:text-zinc-400 mb-1">Organization</label>
                        <p class="text-zinc-900 dark:text-white font-medium">
                            {{ $sessionRegistration->organization ?? '-' }}</p>
                    </div>
                </div>
            </div>

            {{-- Session Information --}}
            <div class="rounded-md border border-zinc-200 dark:border-white/10 bg-white dark:bg-white/5 p-6 shadow-sm">
                <div class="flex items-center justify-between mb-6">
                    <h2 class="text-lg font-bold text-gray-900 dark:text-white">Session Information</h2>
                    <a href="{{ route('admin.content.live-session.show', $sessionRegistration->liveSession) }}"
                        class="text-sm text-primary hover:text-primary/80 hover:underline">
                        View Session <i class="fa-solid fa-arrow-right ml-1"></i>
                    </a>
                </div>

                <div class="bg-zinc-50 dark:bg-white/5 rounded-lg p-4 border border-zinc-100 dark:border-white/5">
                    <h3 class="font-bold text-zinc-900 dark:text-white text-lg mb-2">
                        {{ $sessionRegistration->liveSession->title }}</h3>
                    <div class="flex flex-wrap gap-4 text-sm text-zinc-600 dark:text-zinc-400 mb-4">
                        <span
                            class="flex items-center gap-1.5 bg-white dark:bg-white/5 px-2.5 py-1 rounded-md border border-zinc-200 dark:border-white/10">
                            <i class="fa-regular fa-calendar"></i>
                            {{ $sessionRegistration->liveSession->formatted_date }}
                        </span>
                        <span
                            class="flex items-center gap-1.5 bg-white dark:bg-white/5 px-2.5 py-1 rounded-md border border-zinc-200 dark:border-white/10">
                            <i class="fa-regular fa-clock"></i>
                            {{ $sessionRegistration->liveSession->duration_minutes }} min
                        </span>
                        <span
                            class="flex items-center gap-1.5 bg-white dark:bg-white/5 px-2.5 py-1 rounded-md border border-zinc-200 dark:border-white/10">
                            <i class="fa-solid fa-tag"></i>
                            {{ $sessionRegistration->liveSession->type_display }}
                        </span>
                    </div>
                    @if($sessionRegistration->liveSession->description)
                        <p class="text-zinc-600 dark:text-zinc-400 text-sm line-clamp-2">
                            {{ $sessionRegistration->liveSession->description }}</p>
                    @endif
                </div>
            </div>

            {{-- Notes --}}
            @if($sessionRegistration->notes)
                <div class="rounded-md border border-zinc-200 dark:border-white/10 bg-white dark:bg-white/5 p-6 shadow-sm">
                    <h2 class="text-lg font-bold text-gray-900 dark:text-white mb-4">Notes</h2>
                    <div class="bg-zinc-50 dark:bg-white/5 rounded-lg p-4 border border-zinc-100 dark:border-white/5">
                        <p class="text-zinc-900 dark:text-white whitespace-pre-wrap text-sm">
                            {{ $sessionRegistration->notes }}</p>
                    </div>
                </div>
            @endif
        </div>

        {{-- Right Column - 1/3 --}}
        <div class="lg:col-span-1 space-y-6">
            {{-- Quick Actions --}}
            <div class="rounded-md border border-zinc-200 dark:border-white/10 bg-white dark:bg-white/5 p-6 shadow-sm">
                <h2 class="text-lg font-bold text-gray-900 dark:text-white mb-4">Quick Actions</h2>
                <div class="space-y-3">
                    @if($sessionRegistration->status === 'registered')
                        <form action="{{ route('admin.content.session-registration.mark-attended', $sessionRegistration) }}"
                            method="POST">
                            @csrf
                            <button type="submit"
                                class="w-full flex items-center justify-center gap-2 bg-green-600 hover:bg-green-500 text-white px-4 py-2 rounded-md transition-colors text-sm font-medium">
                                <i class="fa-solid fa-check"></i> Mark as Attended
                            </button>
                        </form>

                        <form action="{{ route('admin.content.session-registration.mark-no-show', $sessionRegistration) }}"
                            method="POST">
                            @csrf
                            <button type="submit"
                                class="w-full flex items-center justify-center gap-2 bg-yellow-600 hover:bg-yellow-500 text-white px-4 py-2 rounded-md transition-colors text-sm font-medium">
                                <i class="fa-solid fa-user-xmark"></i> Mark as No Show
                            </button>
                        </form>
                    @endif

                    @if($sessionRegistration->status !== 'cancelled')
                        <form action="{{ route('admin.content.session-registration.cancel', $sessionRegistration) }}"
                            method="POST" onsubmit="return confirm('Are you sure?');">
                            @csrf
                            <button type="submit"
                                class="w-full flex items-center justify-center gap-2 bg-zinc-100 hover:bg-zinc-200 dark:bg-white/10 dark:hover:bg-white/20 text-zinc-700 dark:text-zinc-300 px-4 py-2 rounded-md transition-colors text-sm font-medium">
                                <i class="fa-solid fa-ban"></i> Cancel Registration
                            </button>
                        </form>
                    @endif
                </div>
            </div>

            {{-- History & Meta --}}
            <div class="rounded-md border border-zinc-200 dark:border-white/10 bg-white dark:bg-white/5 p-6 shadow-sm">
                <h2 class="text-lg font-bold text-gray-900 dark:text-white mb-4">History & Meta</h2>
                <div class="space-y-3 text-sm">
                    <div class="flex justify-between py-2 border-b border-zinc-100 dark:border-white/5">
                        <span class="text-zinc-500 dark:text-zinc-400">Created</span>
                        <span
                            class="text-zinc-900 dark:text-white font-medium">{{ $sessionRegistration->registered_at->format('d M Y, H:i') }}</span>
                    </div>
                    @if($sessionRegistration->attended_at)
                        <div class="flex justify-between py-2 border-b border-zinc-100 dark:border-white/5">
                            <span class="text-zinc-500 dark:text-zinc-400">Attended</span>
                            <span
                                class="text-green-600 dark:text-green-400 font-medium">{{ $sessionRegistration->attended_at->format('d M Y, H:i') }}</span>
                        </div>
                    @endif
                    <div class="flex justify-between py-2 border-b border-zinc-100 dark:border-white/5">
                        <span class="text-zinc-500 dark:text-zinc-400">Marketing Consent</span>
                        @if($sessionRegistration->marketing_consent)
                            <span class="text-green-600 dark:text-green-400 font-medium flex items-center gap-1"><i
                                    class="fa-solid fa-check"></i> Granted</span>
                        @else
                            <span class="text-zinc-500 dark:text-zinc-400 font-medium flex items-center gap-1"><i
                                    class="fa-solid fa-xmark"></i> Not Granted</span>
                        @endif
                    </div>
                    <div class="pt-2">
                        <form action="{{ route('admin.content.session-registration.destroy', $sessionRegistration) }}"
                            method="POST"
                            onsubmit="return confirm('Are you sure to delete this registration? This cannot be undone.')">
                            @csrf
                            @method('DELETE')
                            <button type="submit"
                                class="w-full flex items-center justify-center gap-2 text-red-600 hover:text-red-700 hover:bg-red-50 dark:hover:bg-red-900/20 px-4 py-2 rounded-md transition-colors text-sm font-medium">
                                <i class="fa-solid fa-trash"></i> Delete Registration
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @if(session('success'))
        <script>
            document.addEventListener('DOMContentLoaded', () => {
                window.$wireui.notify({
                    title: 'Success',
                    description: '{{ session('success') }}',
                    icon: 'success'
                });
            });
        </script>
    @endif
</x-layouts.admin>