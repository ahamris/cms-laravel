<x-layouts.admin title="View Marketing Event">
<div class="space-y-6">
    {{-- Header --}}
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">{{ $marketingEvent->title }}</h1>
            <p class="text-gray-600">Marketing Event Details</p>
        </div>
        <div class="flex items-center space-x-3">
            @if($marketingEvent->registration_url)
                <a href="{{ $marketingEvent->registration_url }}" 
                   target="_blank"
                   class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 transition-colors duration-200">
                    <i class="fa-solid fa-external-link-alt mr-2"></i>
                    Register
                </a>
            @endif
            <a href="{{ route('admin.marketing.marketing-event.edit', $marketingEvent) }}"
               class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition-colors duration-200">
                <i class="fa-solid fa-edit mr-2"></i>
                Edit Event
            </a>
            <a href="{{ route('admin.marketing.marketing-event.index') }}"
               class="bg-gray-600 text-white px-4 py-2 rounded-lg hover:bg-gray-700 transition-colors duration-200">
                <i class="fa-solid fa-arrow-left mr-2"></i>
                Back to List
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        {{-- Main Content --}}
        <div class="lg:col-span-2 space-y-6">
            {{-- Event Header --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-200">
                <div class="p-8">
                    <div class="flex items-start">
                        @if($marketingEvent->featured_image)
                            <img src="{{ asset('storage/' . $marketingEvent->featured_image) }}" 
                                 alt="{{ $marketingEvent->title }}" 
                                 class="w-32 h-20 object-cover rounded-lg mr-6 flex-shrink-0">
                        @else
                            <div class="h-20 w-32 rounded-lg bg-purple-50 flex items-center justify-center mr-6 flex-shrink-0">
                                <i class="fa-solid fa-calendar-alt text-purple-600 text-3xl"></i>
                            </div>
                        @endif
                        
                        <div class="flex-1">
                            <div class="flex items-center space-x-3 mb-2">
                                <h2 class="text-2xl font-bold text-gray-900">{{ $marketingEvent->title }}</h2>
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                    @switch($marketingEvent->event_type)
                                        @case('webinar') bg-blue-100 text-blue-800 @break
                                        @case('workshop') bg-green-100 text-green-800 @break
                                        @case('conference') bg-purple-100 text-purple-800 @break
                                        @case('meetup') bg-yellow-100 text-yellow-800 @break
                                        @case('online_event') bg-indigo-100 text-indigo-800 @break
                                        @default bg-gray-100 text-gray-800 @break
                                    @endswitch">
                                    @switch($marketingEvent->event_type)
                                        @case('webinar') <i class="fa-solid fa-video mr-1"></i>Webinar @break
                                        @case('workshop') <i class="fa-solid fa-tools mr-1"></i>Workshop @break
                                        @case('conference') <i class="fa-solid fa-users mr-1"></i>Conference @break
                                        @case('meetup') <i class="fa-solid fa-handshake mr-1"></i>Meetup @break
                                        @case('online_event') <i class="fa-solid fa-globe mr-1"></i>Online Event @break
                                        @default {{ ucfirst($marketingEvent->event_type) }} @break
                                    @endswitch
                                </span>
                                @if($marketingEvent->is_featured)
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-yellow-100 text-yellow-800">
                                        <i class="fa-solid fa-star mr-1"></i>Featured
                                    </span>
                                @endif
                            </div>
                            
                            @if($marketingEvent->description)
                                <p class="text-gray-700 leading-relaxed mb-4">{{ $marketingEvent->description }}</p>
                            @endif
                            
                            <div class="flex items-center space-x-6 text-sm text-gray-500">
                                <div class="flex items-center">
                                    <i class="fa-solid fa-calendar mr-2"></i>
                                    {{ $marketingEvent->event_date->format('M j, Y') }}
                                </div>
                                @if($marketingEvent->start_time)
                                    <div class="flex items-center">
                                        <i class="fa-solid fa-clock mr-2"></i>
                                        {{ $marketingEvent->start_time }}
                                        @if($marketingEvent->end_time)
                                            - {{ $marketingEvent->end_time }}
                                        @endif
                                    </div>
                                @endif
                                @if($marketingEvent->is_online)
                                    <div class="flex items-center text-blue-600">
                                        <i class="fa-solid fa-globe mr-2"></i>
                                        Online Event
                                    </div>
                                @elseif($marketingEvent->location)
                                    <div class="flex items-center">
                                        <i class="fa-solid fa-map-marker-alt mr-2"></i>
                                        {{ $marketingEvent->location }}
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Speakers --}}
            @if($marketingEvent->speakers && count($marketingEvent->speakers) > 0)
                <div class="bg-white rounded-xl shadow-sm border border-gray-200">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <div class="flex items-center">
                            <div class="h-8 w-8 rounded-lg bg-blue-50 flex items-center justify-center mr-3">
                                <i class="fa-solid fa-microphone text-blue-600"></i>
                            </div>
                            <h3 class="text-lg font-semibold text-gray-900">Speakers</h3>
                        </div>
                    </div>
                    <div class="p-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            @foreach($marketingEvent->speakers as $speaker)
                                <div class="flex items-center p-3 bg-gray-50 rounded-lg">
                                    <i class="fa-solid fa-user-tie text-blue-600 mr-3"></i>
                                    <span class="text-gray-900">{{ $speaker }}</span>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endif

            {{-- Agenda --}}
            @if($marketingEvent->agenda && count($marketingEvent->agenda) > 0)
                <div class="bg-white rounded-xl shadow-sm border border-gray-200">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <div class="flex items-center">
                            <div class="h-8 w-8 rounded-lg bg-green-50 flex items-center justify-center mr-3">
                                <i class="fa-solid fa-list text-green-600"></i>
                            </div>
                            <h3 class="text-lg font-semibold text-gray-900">Agenda</h3>
                        </div>
                    </div>
                    <div class="p-6">
                        <div class="space-y-3">
                            @foreach($marketingEvent->agenda as $agendaItem)
                                <div class="flex items-start p-3 border border-gray-200 rounded-lg">
                                    <i class="fa-solid fa-clock text-gray-400 mr-3 mt-1"></i>
                                    <span class="text-gray-900">{{ $agendaItem }}</span>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endif

            {{-- Tags --}}
            @if($marketingEvent->tags && count($marketingEvent->tags) > 0)
                <div class="bg-white rounded-xl shadow-sm border border-gray-200">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-900">Tags</h3>
                    </div>
                    <div class="p-6">
                        <div class="flex flex-wrap gap-2">
                            @foreach($marketingEvent->tags as $tag)
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-gray-100 text-gray-800">
                                    <i class="fa-solid fa-tag mr-1"></i>
                                    {{ $tag }}
                                </span>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endif
        </div>

        {{-- Sidebar --}}
        <div class="lg:col-span-1 space-y-6">
            {{-- Event Status --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-200">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900">Event Status</h3>
                </div>
                <div class="p-6 space-y-4">
                    @php
                        $now = now();
                        $eventDateTime = $marketingEvent->event_date;
                        if ($marketingEvent->start_time) {
                            $eventDateTime = $marketingEvent->event_date->setTimeFromTimeString($marketingEvent->start_time);
                        }
                    @endphp
                    
                    <div class="text-center">
                        @if($eventDateTime->isPast())
                            <div class="w-16 h-16 rounded-full bg-gray-100 flex items-center justify-center mx-auto mb-3">
                                <i class="fa-solid fa-check text-gray-600 text-2xl"></i>
                            </div>
                            <h4 class="font-semibold text-gray-900">Event Completed</h4>
                            <p class="text-sm text-gray-600">This event has ended</p>
                        @elseif($eventDateTime->isToday())
                            <div class="w-16 h-16 rounded-full bg-red-100 flex items-center justify-center mx-auto mb-3">
                                <i class="fa-solid fa-exclamation text-red-600 text-2xl"></i>
                            </div>
                            <h4 class="font-semibold text-gray-900">Event Today!</h4>
                            <p class="text-sm text-gray-600">Happening today</p>
                        @elseif($eventDateTime->isTomorrow())
                            <div class="w-16 h-16 rounded-full bg-orange-100 flex items-center justify-center mx-auto mb-3">
                                <i class="fa-solid fa-clock text-orange-600 text-2xl"></i>
                            </div>
                            <h4 class="font-semibold text-gray-900">Event Tomorrow</h4>
                            <p class="text-sm text-gray-600">Starting tomorrow</p>
                        @else
                            <div class="w-16 h-16 rounded-full bg-blue-100 flex items-center justify-center mx-auto mb-3">
                                <i class="fa-solid fa-calendar text-blue-600 text-2xl"></i>
                            </div>
                            <h4 class="font-semibold text-gray-900">Upcoming Event</h4>
                            <p class="text-sm text-gray-600">{{ $eventDateTime->diffForHumans() }}</p>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Event Details --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-200">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900">Event Details</h3>
                </div>
                <div class="p-6 space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Event Type</label>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                            @switch($marketingEvent->event_type)
                                @case('webinar') bg-blue-100 text-blue-800 @break
                                @case('workshop') bg-green-100 text-green-800 @break
                                @case('conference') bg-purple-100 text-purple-800 @break
                                @case('meetup') bg-yellow-100 text-yellow-800 @break
                                @case('online_event') bg-indigo-100 text-indigo-800 @break
                                @default bg-gray-100 text-gray-800 @break
                            @endswitch">
                            {{ ucfirst(str_replace('_', ' ', $marketingEvent->event_type)) }}
                        </span>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Date & Time</label>
                        <div class="text-gray-900">
                            <div class="flex items-center mb-1">
                                <i class="fa-solid fa-calendar mr-2 text-gray-400"></i>
                                {{ $marketingEvent->event_date->format('l, F j, Y') }}
                            </div>
                            @if($marketingEvent->start_time)
                                <div class="flex items-center text-sm">
                                    <i class="fa-solid fa-clock mr-2 text-gray-400"></i>
                                    {{ $marketingEvent->start_time }}
                                    @if($marketingEvent->end_time)
                                        - {{ $marketingEvent->end_time }}
                                    @endif
                                </div>
                            @endif
                        </div>
                    </div>

                    @if($marketingEvent->location || $marketingEvent->is_online)
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Location</label>
                            @if($marketingEvent->is_online)
                                <div class="flex items-center text-blue-600">
                                    <i class="fa-solid fa-globe mr-2"></i>
                                    Online Event
                                </div>
                            @else
                                <div class="flex items-center text-gray-900">
                                    <i class="fa-solid fa-map-marker-alt mr-2 text-gray-400"></i>
                                    {{ $marketingEvent->location }}
                                </div>
                            @endif
                        </div>
                    @endif

                    @if($marketingEvent->max_attendees)
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Capacity</label>
                            <div class="flex items-center text-gray-900">
                                <i class="fa-solid fa-users mr-2 text-gray-400"></i>
                                {{ $marketingEvent->max_attendees }} attendees
                            </div>
                        </div>
                    @endif

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Slug</label>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                            {{ $marketingEvent->slug }}
                        </span>
                    </div>
                </div>
            </div>

            {{-- Status & Settings --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-200">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900">Status & Settings</h3>
                </div>
                <div class="p-6 space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $marketingEvent->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                            <i class="fa-solid fa-{{ $marketingEvent->is_active ? 'check' : 'times' }} mr-1"></i>
                            {{ $marketingEvent->is_active ? 'Active' : 'Inactive' }}
                        </span>
                    </div>

                    @if($marketingEvent->is_featured)
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Featured</label>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                <i class="fa-solid fa-star mr-1"></i>
                                Featured Event
                            </span>
                        </div>
                    @endif

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Sort Order</label>
                        <p class="text-gray-900">{{ $marketingEvent->sort_order }}</p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Created</label>
                        <p class="text-gray-900 text-sm">{{ $marketingEvent->created_at->format('M j, Y \a\t g:i A') }}</p>
                    </div>

                    @if($marketingEvent->updated_at != $marketingEvent->created_at)
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Last Updated</label>
                            <p class="text-gray-900 text-sm">{{ $marketingEvent->updated_at->format('M j, Y \a\t g:i A') }}</p>
                        </div>
                    @endif
                </div>
            </div>

            {{-- Statistics --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-200">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900">Statistics</h3>
                </div>
                <div class="p-6 space-y-4">
                    @if($marketingEvent->speakers)
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Speakers</label>
                            <p class="text-gray-900">{{ count($marketingEvent->speakers) }} speakers</p>
                        </div>
                    @endif
                    
                    @if($marketingEvent->agenda)
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Agenda Items</label>
                            <p class="text-gray-900">{{ count($marketingEvent->agenda) }} items</p>
                        </div>
                    @endif

                    @if($marketingEvent->tags)
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Tags</label>
                            <p class="text-gray-900">{{ count($marketingEvent->tags) }} tags</p>
                        </div>
                    @endif
                </div>
            </div>

            {{-- Quick Actions --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-200">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900">Quick Actions</h3>
                </div>
                <div class="p-6 space-y-3">
                    @if($marketingEvent->registration_url)
                        <a href="{{ $marketingEvent->registration_url }}" 
                           target="_blank"
                           class="w-full bg-green-600 text-white py-2 px-4 rounded-lg hover:bg-green-700 transition-colors duration-200 text-center block">
                            <i class="fa-solid fa-external-link-alt mr-2"></i>
                            Open Registration
                        </a>
                    @endif
                    
                    <button onclick="copyEventDetails()"
                            class="w-full bg-gray-600 text-white py-2 px-4 rounded-lg hover:bg-gray-700 transition-colors duration-200">
                        <i class="fa-solid fa-copy mr-2"></i>
                        Copy Event Details
                    </button>
                    
                    <button onclick="copyUrl()"
                            class="w-full bg-blue-600 text-white py-2 px-4 rounded-lg hover:bg-blue-700 transition-colors duration-200">
                        <i class="fa-solid fa-link mr-2"></i>
                        Copy Event URL
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function copyEventDetails() {
    const eventDetails = `{{ $marketingEvent->title }}\n\nDate: {{ $marketingEvent->event_date->format('F j, Y') }}\n@if($marketingEvent->start_time)Time: {{ $marketingEvent->start_time }}@if($marketingEvent->end_time) - {{ $marketingEvent->end_time }}@endif\n@endif@if($marketingEvent->is_online)Location: Online Event\n@elseif($marketingEvent->location)Location: {{ $marketingEvent->location }}\n@endif@if($marketingEvent->description)\n{{ $marketingEvent->description }}@endif@if($marketingEvent->registration_url)\n\nRegister: {{ $marketingEvent->registration_url }}@endif`;
    navigator.clipboard.writeText(eventDetails).then(function() {
        showCopySuccess('Event details copied to clipboard!');
    });
}

function copyUrl() {
    const url = `{{ url('/events/' . $marketingEvent->slug) }}`;
    navigator.clipboard.writeText(url).then(function() {
        showCopySuccess('URL copied to clipboard!');
    });
}

function showCopySuccess(message) {
    // Create a temporary success message
    const toast = document.createElement('div');
    toast.className = 'fixed top-4 right-4 bg-green-500 text-white px-4 py-2 rounded-lg shadow-lg z-50';
    toast.textContent = message;
    document.body.appendChild(toast);
    
    setTimeout(() => {
        toast.remove();
    }, 3000);
}
</script>
</x-layouts.admin>
