<x-layouts.admin title="Session Details">
    <div class="space-y-6">
        {{-- Header --}}
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">{{ $liveSession->title }}</h1>
                <p class="text-gray-600">{{ $liveSession->type_display }} • {{ $liveSession->formatted_date }}</p>
            </div>
            <div class="flex space-x-2">
                <a href="{{ route('admin.content.live-session.edit', $liveSession) }}"
                    class="bg-primary text-white px-4 py-2 rounded-lg hover:bg-primary/80 transition-colors duration-200">
                    <i class="fa-solid fa-edit mr-2"></i>
                    Edit Session
                </a>
                <a href="{{ route('admin.content.live-session.index') }}"
                    class="bg-gray-500 text-white px-4 py-2 rounded-lg hover:bg-gray-600 transition-colors duration-200">
                    <i class="fa-solid fa-arrow-left mr-2"></i>
                    Back to List
                </a>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            {{-- Main Content --}}
            <div class="lg:col-span-2 space-y-6">
                {{-- Session Information --}}
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Session Information</h3>

                    @if($liveSession->description)
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-500 mb-1">Description</label>
                            <p class="text-gray-900">{{ $liveSession->description }}</p>
                        </div>
                    @endif

                    @if($liveSession->content)
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-500 mb-1">Content</label>
                            <div class="text-gray-900 prose dark:prose-invert max-w-none">{!! $liveSession->content !!}
                            </div>
                        </div>
                    @endif

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-500 mb-1">Session Date</label>
                            <p class="text-gray-900">{{ $liveSession->formatted_date }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-500 mb-1">Duration</label>
                            <p class="text-gray-900">{{ $liveSession->duration_minutes }} minutes</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-500 mb-1">Type</label>
                            <p class="text-gray-900">{{ $liveSession->type_display }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-500 mb-1">Max Participants</label>
                            <p class="text-gray-900">{{ $liveSession->max_participants }}</p>
                        </div>
                    </div>
                </div>

                {{-- Presenters --}}
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Presenters
                        ({{ $liveSession->presenters->count() }})</h3>
                    @if($liveSession->presenters->count() > 0)
                        <div class="space-y-3">
                            @foreach($liveSession->presenters as $presenter)
                                <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                                    <div class="flex items-center">
                                        <img class="h-10 w-10 rounded-full" src="{{ $presenter->avatar_url }}"
                                            alt="{{ $presenter->name }}">
                                        <div class="ml-4">
                                            <h4 class="font-medium text-gray-900">{{ $presenter->name }}</h4>
                                            <p class="text-sm text-gray-500">{{ $presenter->title ?? 'No title' }}</p>
                                        </div>
                                    </div>
                                    <div class="flex items-center space-x-2">
                                        @if($presenter->pivot->is_primary)
                                            <span
                                                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-primary/10 text-primary">
                                                Primary
                                            </span>
                                        @endif
                                        <a href="{{ route('admin.content.presenter.show', $presenter) }}"
                                            class="text-primary hover:text-primary/80" title="View Presenter">
                                            <i class="fa-solid fa-external-link-alt"></i>
                                        </a>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-gray-500">No presenters assigned yet.</p>
                    @endif
                </div>

                {{-- Registrations --}}
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-semibold text-gray-900">Registrations
                            ({{ $liveSession->registrations->count() }})</h3>
                        <a href="{{ route('admin.content.session-registration.index', ['session_id' => $liveSession->id]) }}"
                            class="text-primary hover:text-primary/80">
                            <i class="fa-solid fa-external-link-alt mr-1"></i>
                            View All
                        </a>
                    </div>

                    {{-- Registration Progress --}}
                    <div class="mb-4">
                        <div class="flex justify-between text-sm text-gray-600 mb-1">
                            <span>Capacity</span>
                            <span>{{ $liveSession->registrations->count() }}/{{ $liveSession->max_participants }}</span>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-2">
                            <div class="bg-primary h-2 rounded-full"
                                style="width: {{ ($liveSession->registrations->count() / $liveSession->max_participants) * 100 }}%">
                            </div>
                        </div>
                    </div>

                    @if($liveSession->registrations->count() > 0)
                        <div class="space-y-2">
                            @foreach($liveSession->registrations->take(5) as $registration)
                                <div class="flex items-center justify-between p-2 bg-gray-50 rounded">
                                    <div>
                                        <span class="font-medium text-gray-900">{{ $registration->name }}</span>
                                        <span class="text-sm text-gray-500">• {{ $registration->organization }}</span>
                                    </div>
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                            @if($registration->status === 'registered') bg-blue-100 text-blue-800
                                            @elseif($registration->status === 'attended') bg-green-100 text-green-800
                                            @elseif($registration->status === 'no_show') bg-yellow-100 text-yellow-800
                                            @else bg-red-100 text-red-800 @endif">
                                        {{ $registration->status_display }}
                                    </span>
                                </div>
                            @endforeach
                            @if($liveSession->registrations->count() > 5)
                                <p class="text-sm text-gray-500 text-center">
                                    And {{ $liveSession->registrations->count() - 5 }} more...
                                </p>
                            @endif
                        </div>
                    @else
                        <p class="text-gray-500">No registrations yet.</p>
                    @endif
                </div>
            </div>

            {{-- Sidebar --}}
            <div class="space-y-6">
                {{-- Status & Settings --}}
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Status & Settings</h3>
                    <div class="space-y-3">
                        <div>
                            <label class="block text-sm font-medium text-gray-500 mb-1">Status</label>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                            @if($liveSession->status === 'upcoming') bg-blue-100 text-blue-800
                            @elseif($liveSession->status === 'live') bg-green-100 text-green-800
                            @elseif($liveSession->status === 'completed') bg-gray-100 text-gray-800
                            @else bg-red-100 text-red-800 @endif">
                                {{ $liveSession->status_display }}
                            </span>
                            @if(!$liveSession->is_active)
                                <span
                                    class="ml-2 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                    Inactive
                                </span>
                            @endif
                            @if($liveSession->is_featured)
                                <span
                                    class="ml-2 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                    <i class="fa-solid fa-star mr-1"></i>
                                    Featured
                                </span>
                            @endif
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-500 mb-1">Sort Order</label>
                            <p class="text-gray-900">{{ $liveSession->sort_order }}</p>
                        </div>
                        @if($liveSession->icon)
                            <div>
                                <label class="block text-sm font-medium text-gray-500 mb-1">Icon</label>
                                <p class="text-gray-900">
                                    <i class="{{ $liveSession->icon }}" style="color: {{ $liveSession->color }}"></i>
                                    {{ $liveSession->icon }}
                                </p>
                            </div>
                        @endif
                        <div>
                            <label class="block text-sm font-medium text-gray-500 mb-1">Color</label>
                            <div class="flex items-center">
                                <div class="w-6 h-6 rounded border border-gray-300 mr-2"
                                    style="background-color: {{ $liveSession->color }}"></div>
                                <span class="text-gray-900">{{ $liveSession->color }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Links --}}
                @if($liveSession->meeting_url || $liveSession->recording_url)
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Links</h3>
                        <div class="space-y-3">
                            @if($liveSession->meeting_url)
                                <div>
                                    <label class="block text-sm font-medium text-gray-500 mb-1">Meeting URL</label>
                                    <a href="{{ $liveSession->meeting_url }}" target="_blank"
                                        class="text-primary hover:text-primary/80 break-all">
                                        {{ $liveSession->meeting_url }}
                                    </a>
                                </div>
                            @endif
                            @if($liveSession->recording_url)
                                <div>
                                    <label class="block text-sm font-medium text-gray-500 mb-1">Recording URL</label>
                                    <a href="{{ $liveSession->recording_url }}" target="_blank"
                                        class="text-primary hover:text-primary/80 break-all">
                                        {{ $liveSession->recording_url }}
                                    </a>
                                </div>
                            @endif
                        </div>
                    </div>
                @endif

                {{-- Management --}}
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Management</h3>
                    <div class="space-y-3">
                        <div>
                            <label class="block text-sm font-medium text-gray-500 mb-1">Slug</label>
                            <p class="text-gray-900 font-mono text-sm">{{ $liveSession->slug }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-500 mb-1">Created</label>
                            <p class="text-gray-900">{{ $liveSession->created_at->format('d-m-Y H:i') }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-500 mb-1">Last Updated</label>
                            <p class="text-gray-900">{{ $liveSession->updated_at->format('d-m-Y H:i') }}</p>
                        </div>
                    </div>
                </div>

                {{-- Actions --}}
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Actions</h3>
                    <div class="space-y-3">
                        <form action="{{ route('admin.content.live-session.toggle-status', $liveSession) }}"
                            method="POST">
                            @csrf
                            <button type="submit"
                                class="w-full bg-{{ $liveSession->is_active ? 'red' : 'green' }}-600 text-white px-4 py-2 rounded-lg hover:bg-{{ $liveSession->is_active ? 'red' : 'green' }}-700 transition-colors duration-200">
                                <i class="fa-solid fa-{{ $liveSession->is_active ? 'eye-slash' : 'eye' }} mr-2"></i>
                                {{ $liveSession->is_active ? 'Deactivate' : 'Activate' }}
                            </button>
                        </form>

                        <form action="{{ route('admin.content.live-session.update-session-status', $liveSession) }}"
                            method="POST">
                            @csrf
                            <button type="submit"
                                class="w-full bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition-colors duration-200">
                                <i class="fa-solid fa-sync mr-2"></i>
                                Update Status
                            </button>
                        </form>

                        <form action="{{ route('admin.content.live-session.destroy', $liveSession) }}" method="POST"
                            onsubmit="return confirm('Are you sure you want to delete this session? This action cannot be undone.')">
                            @csrf
                            @method('DELETE')
                            <button type="submit"
                                class="w-full bg-red-600 text-white px-4 py-2 rounded-lg hover:bg-red-700 transition-colors duration-200">
                                <i class="fa-solid fa-trash mr-2"></i>
                                Delete Session
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @if(session('success'))
        <script>
            toastr.success('{{ session('success') }}');
        </script>
    @endif
</x-layouts.admin>