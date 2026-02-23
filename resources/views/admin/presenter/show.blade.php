<x-layouts.admin title="Presenter Details">
    {{-- Page Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-8">
        <div>
            <div class="flex items-center gap-3 mb-2">
                <h1 class="text-3xl font-bold text-zinc-900 dark:text-white">{{ $presenter->name }}</h1>
                <span
                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                    {{ $presenter->is_active ? 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-300' : 'bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-300' }}">
                    {{ $presenter->is_active ? 'Active' : 'Inactive' }}
                </span>
            </div>
            <p class="text-zinc-600 dark:text-zinc-400">
                {{ $presenter->title ?? 'No title' }} {{ $presenter->company ? 'at ' . $presenter->company : '' }}
            </p>
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ route('admin.presenter.index') }}"
                class="inline-flex items-center gap-2 rounded-md bg-white dark:bg-white/10 px-4 py-2 text-sm font-semibold text-gray-900 dark:text-white shadow-xs ring-1 ring-gray-300 ring-inset dark:ring-white/10 hover:bg-gray-50 dark:hover:bg-white/20">
                <i class="fa-solid fa-arrow-left"></i>
                Back to List
            </a>
            <a href="{{ route('admin.presenter.edit', $presenter) }}">
                <x-button variant="primary" icon="edit">Edit Presenter</x-button>
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        {{-- Left Column - 2/3 --}}
        <div class="lg:col-span-2 space-y-8">
            {{-- Basic Information --}}
            <div class="rounded-md border border-zinc-200 dark:border-white/10 bg-white dark:bg-white/5 p-6 shadow-sm">
                <h2 class="text-lg font-bold text-gray-900 dark:text-white mb-6">Basic Information</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-zinc-500 dark:text-zinc-400 mb-1">Name</label>
                        <p class="text-zinc-900 dark:text-white font-medium">{{ $presenter->name }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-zinc-500 dark:text-zinc-400 mb-1">Title</label>
                        <p class="text-zinc-900 dark:text-white font-medium">{{ $presenter->title ?: 'Not specified' }}
                        </p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-zinc-500 dark:text-zinc-400 mb-1">Company</label>
                        <p class="text-zinc-900 dark:text-white font-medium">
                            {{ $presenter->company ?: 'Not specified' }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-zinc-500 dark:text-zinc-400 mb-1">Email</label>
                        @if($presenter->email)
                            <a href="mailto:{{ $presenter->email }}" class="text-primary hover:text-primary/80 font-medium">
                                {{ $presenter->email }}
                            </a>
                        @else
                            <p class="text-zinc-900 dark:text-white font-medium text-opacity-50">Not specified</p>
                        @endif
                    </div>
                </div>

                @if($presenter->bio)
                    <div class="mt-6 pt-6 border-t border-zinc-100 dark:border-white/5">
                        <label class="block text-sm font-medium text-zinc-500 dark:text-zinc-400 mb-2">Biography</label>
                        <div class="prose prose-sm dark:prose-invert max-w-none">
                            <p class="whitespace-pre-wrap">{{ $presenter->bio }}</p>
                        </div>
                    </div>
                @endif
            </div>

            {{-- Live Sessions --}}
            <div class="rounded-md border border-zinc-200 dark:border-white/10 bg-white dark:bg-white/5 p-6 shadow-sm">
                <h2 class="text-lg font-bold text-gray-900 dark:text-white mb-6">Live Sessions
                    ({{ $presenter->liveSessions->count() }})</h2>
                @if($presenter->liveSessions->count() > 0)
                    <div class="space-y-4">
                        @foreach($presenter->liveSessions as $session)
                            <div
                                class="flex flex-col sm:flex-row sm:items-center justify-between p-4 bg-zinc-50 dark:bg-white/5 rounded-lg border border-zinc-100 dark:border-white/5 gap-4">
                                <div>
                                    <h4 class="font-bold text-zinc-900 dark:text-white">{{ $session->title }}</h4>
                                    <div class="flex flex-wrap gap-3 mt-1 text-xs text-zinc-500 dark:text-zinc-400">
                                        <span><i class="fa-regular fa-calendar mr-1"></i> {{ $session->formatted_date }}</span>
                                        <span><i class="fa-solid fa-tag mr-1"></i> {{ $session->type_display }}</span>
                                    </div>
                                </div>
                                <div class="flex items-center gap-3">
                                    @if($session->pivot->is_primary)
                                        <span
                                            class="inline-flex items-center px-2 py-0.5 rounded-md text-xs font-medium bg-primary/10 text-primary border border-primary/20">
                                            Primary
                                        </span>
                                    @endif
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                                @if($session->status === 'upcoming') bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-300
                                                @elseif($session->status === 'live') bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-300
                                                @elseif($session->status === 'completed') bg-zinc-100 text-zinc-800 dark:bg-zinc-700 dark:text-zinc-300
                                                @else bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-300 @endif">
                                        {{ $session->status_display }}
                                    </span>
                                    <a href="{{ route('admin.live-session.show', $session) }}"
                                        class="text-zinc-400 hover:text-primary transition-colors" title="View Session">
                                        <i class="fa-solid fa-arrow-right-long"></i>
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-6 text-zinc-500 dark:text-zinc-400 italic">
                        No sessions assigned to this presenter yet.
                    </div>
                @endif
            </div>
        </div>

        {{-- Sidebar --}}
        <div class="lg:col-span-1 space-y-8">
            {{-- Avatar --}}
            <div class="rounded-md border border-zinc-200 dark:border-white/10 bg-white dark:bg-white/5 p-6 shadow-sm">
                <div class="text-center">
                    <img class="mx-auto h-32 w-32 rounded-full object-cover border-4 border-zinc-100 dark:border-white/10 shadow-sm"
                        src="{{ $presenter->avatar_url }}" alt="{{ $presenter->name }}">
                    <h3 class="mt-4 text-xl font-bold text-zinc-900 dark:text-white">{{ $presenter->name }}</h3>
                </div>
            </div>

            {{-- Social Links --}}
            @if($presenter->linkedin_url || $presenter->twitter_url)
                <div class="rounded-md border border-zinc-200 dark:border-white/10 bg-white dark:bg-white/5 p-6 shadow-sm">
                    <h2 class="text-lg font-bold text-gray-900 dark:text-white mb-4">Social Links</h2>
                    <div class="space-y-3">
                        @if($presenter->linkedin_url)
                            <a href="{{ $presenter->linkedin_url }}" target="_blank"
                                class="flex items-center gap-3 p-3 rounded-md bg-[#0077b5]/10 text-[#0077b5] hover:bg-[#0077b5]/20 transition-colors font-medium">
                                <i class="fab fa-linkedin text-xl"></i>
                                LinkedIn Profile
                            </a>
                        @endif
                        @if($presenter->twitter_url)
                            <a href="{{ $presenter->twitter_url }}" target="_blank"
                                class="flex items-center gap-3 p-3 rounded-md bg-[#1da1f2]/10 text-[#1da1f2] hover:bg-[#1da1f2]/20 transition-colors font-medium">
                                <i class="fab fa-twitter text-xl"></i>
                                Twitter Profile
                            </a>
                        @endif
                    </div>
                </div>
            @endif

            {{-- Quick Actions --}}
            <div class="rounded-md border border-zinc-200 dark:border-white/10 bg-white dark:bg-white/5 p-6 shadow-sm">
                <h2 class="text-lg font-bold text-gray-900 dark:text-white mb-4">Actions</h2>
                <div class="space-y-3">
                    <form action="{{ route('admin.presenter.toggle-status', $presenter) }}" method="POST">
                        @csrf
                        <button type="submit"
                            class="w-full flex items-center justify-center gap-2 px-4 py-2 rounded-md transition-colors text-sm font-medium
                                {{ $presenter->is_active ? 'bg-red-50 text-red-600 hover:bg-red-100 dark:bg-red-900/20 dark:hover:bg-red-900/30' : 'bg-green-50 text-green-600 hover:bg-green-100 dark:bg-green-900/20 dark:hover:bg-green-900/30' }}">
                            <i class="fa-solid fa-{{ $presenter->is_active ? 'eye-slash' : 'eye' }}"></i>
                            {{ $presenter->is_active ? 'Deactivate Presenter' : 'Activate Presenter' }}
                        </button>
                    </form>

                    @if($presenter->avatar)
                        <form action="{{ route('admin.presenter.remove-avatar', $presenter) }}" method="POST"
                            onsubmit="return confirm('Are you sure you want to remove the avatar?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit"
                                class="w-full flex items-center justify-center gap-2 bg-yellow-50 hover:bg-yellow-100 text-yellow-600 dark:bg-yellow-900/20 dark:hover:bg-yellow-900/30 px-4 py-2 rounded-md transition-colors text-sm font-medium">
                                <i class="fa-solid fa-image"></i> Remove Avatar
                            </button>
                        </form>
                    @endif
                </div>
            </div>

            {{-- Meta & Danger --}}
            <div class="rounded-md border border-zinc-200 dark:border-white/10 bg-white dark:bg-white/5 p-6 shadow-sm">
                <h2 class="text-lg font-bold text-gray-900 dark:text-white mb-4">Meta</h2>
                <div class="space-y-3 text-sm">
                    <div class="flex justify-between py-2 border-b border-zinc-100 dark:border-white/5">
                        <span class="text-zinc-500 dark:text-zinc-400">Sort Order</span>
                        <span class="text-zinc-900 dark:text-white font-medium">{{ $presenter->sort_order }}</span>
                    </div>
                    <div class="flex justify-between py-2 border-b border-zinc-100 dark:border-white/5">
                        <span class="text-zinc-500 dark:text-zinc-400">Created</span>
                        <span
                            class="text-zinc-900 dark:text-white font-medium">{{ $presenter->created_at->format('d M Y, H:i') }}</span>
                    </div>
                    <div class="flex justify-between py-2 border-b border-zinc-100 dark:border-white/5">
                        <span class="text-zinc-500 dark:text-zinc-400">Last Updated</span>
                        <span
                            class="text-zinc-900 dark:text-white font-medium">{{ $presenter->updated_at->format('d M Y, H:i') }}</span>
                    </div>
                    <div class="pt-2">
                        <form action="{{ route('admin.presenter.destroy', $presenter) }}" method="POST"
                            onsubmit="return confirm('Are you sure you want to delete this presenter? This action cannot be undone.')">
                            @csrf
                            @method('DELETE')
                            <button type="submit"
                                class="w-full flex items-center justify-center gap-2 text-red-600 hover:text-red-700 hover:bg-red-50 dark:hover:bg-red-900/20 px-4 py-2 rounded-md transition-colors text-sm font-medium">
                                <i class="fa-solid fa-trash"></i> Delete Presenter
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