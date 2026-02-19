<x-layouts.admin title="Marketing Events">
<div class="space-y-6">
    {{-- Header --}}
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Marketing Events</h1>
            <p class="text-gray-600">Manage webinars, workshops, and marketing events</p>
        </div>
        <a href="{{ route('admin.marketing.marketing-event.create') }}"
           class="bg-primary text-white px-4 py-2 rounded-lg hover:bg-primary/80 transition-colors duration-200">
            <i class="fa-solid fa-plus mr-2"></i>
            Create New Event
        </a>
    </div>

    {{-- Marketing Events Table --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        @if($marketingEvents->count() > 0)
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50 border-b border-gray-200">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Event</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Type</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date & Time</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Location</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($marketingEvents as $event)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4">
                                    <div class="flex items-start">
                                        @if($event->featured_image)
                                            <img src="{{ asset('storage/' . $event->featured_image) }}" 
                                                 alt="{{ $event->title }}" 
                                                 class="h-10 w-16 object-cover rounded mr-3 flex-shrink-0">
                                        @else
                                            <div class="h-10 w-16 rounded bg-purple-50 flex items-center justify-center mr-3 flex-shrink-0">
                                                <i class="fa-solid fa-calendar-alt text-purple-600"></i>
                                            </div>
                                        @endif
                                        <div class="min-w-0 flex-1">
                                            <div class="text-sm font-medium text-gray-900">{{ $event->title }}</div>
                                            @if($event->description)
                                                <div class="text-sm text-gray-500 mt-1">{{ Str::limit($event->description, 80) }}</div>
                                            @endif
                                            <div class="text-xs text-gray-400 mt-1">{{ $event->slug }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                        @switch($event->type)
                                            @case('webinar') bg-blue-100 text-blue-800 @break
                                            @case('workshop') bg-green-100 text-green-800 @break
                                            @case('conference') bg-purple-100 text-purple-800 @break
                                            @case('meetup') bg-yellow-100 text-yellow-800 @break
                                            @case('online_event') bg-indigo-100 text-indigo-800 @break
                                            @default bg-gray-100 text-gray-800 @break
                                        @endswitch">
                                        @switch($event->type)
                                            @case('webinar') <i class="fa-solid fa-video mr-1"></i>Webinar @break
                                            @case('workshop') <i class="fa-solid fa-tools mr-1"></i>Workshop @break
                                            @case('conference') <i class="fa-solid fa-users mr-1"></i>Conference @break
                                            @case('meetup') <i class="fa-solid fa-handshake mr-1"></i>Meetup @break
                                            @case('online_event') <i class="fa-solid fa-globe mr-1"></i>Online Event @break
                                            @default {{ ucfirst($event->type) }} @break
                                        @endswitch
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-sm text-gray-900">
                                        <div class="flex items-center mb-1">
                                            <i class="fa-solid fa-calendar mr-1 text-gray-400"></i>
                                            {{ $event->start_date->format('M j, Y') }}
                                        </div>
                                        @if($event->start_time)
                                            <div class="flex items-center text-xs text-gray-500">
                                                <i class="fa-solid fa-clock mr-1"></i>
                                                {{ $event->start_time }}
                                                @if($event->end_time)
                                                    - {{ $event->end_time }}
                                                @endif
                                            </div>
                                        @endif
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    @if($event->meeting_url)
                                        <div class="flex items-center text-sm text-blue-600">
                                            <i class="fa-solid fa-globe mr-1"></i>
                                            Online
                                        </div>
                                    @elseif($event->location)
                                        <div class="flex items-center text-sm text-gray-900">
                                            <i class="fa-solid fa-map-marker-alt mr-1 text-gray-400"></i>
                                            {{ Str::limit($event->location, 30) }}
                                        </div>
                                    @else
                                        <span class="text-gray-400 text-sm">No location</span>
                                    @endif
                                    
                                    @if($event->max_attendees)
                                        <div class="text-xs text-gray-500 mt-1">
                                            Max: {{ $event->max_attendees }} attendees
                                        </div>
                                    @endif
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex flex-col space-y-1">
                                        {{-- Event Status --}}
                                        @php
                                            $now = now();
                                            $eventDateTime = $event->start_date;
                                        @endphp
                                        
                                        @if($eventDateTime->isPast())
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                                <i class="fa-solid fa-check mr-1"></i>Completed
                                            </span>
                                        @elseif($eventDateTime->isToday())
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                                <i class="fa-solid fa-exclamation mr-1"></i>Today
                                            </span>
                                        @elseif($eventDateTime->isTomorrow())
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-orange-100 text-orange-800">
                                                <i class="fa-solid fa-clock mr-1"></i>Tomorrow
                                            </span>
                                        @else
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                                <i class="fa-solid fa-calendar mr-1"></i>Upcoming
                                            </span>
                                        @endif

                                        {{-- Published Status --}}
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $event->is_published ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                            {{ $event->is_published ? 'Published' : 'Draft' }}
                                        </span>
                                        
                                        @if($event->is_featured)
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                                <i class="fa-solid fa-star mr-1"></i>Featured
                                            </span>
                                        @endif
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <div class="flex items-center justify-end space-x-2">
                                        @if($event->meeting_url)
                                            <a href="{{ $event->meeting_url }}" 
                                               target="_blank"
                                               class="text-green-600 hover:text-green-900"
                                               title="Meeting URL">
                                                <i class="fa-solid fa-external-link-alt"></i>
                                            </a>
                                        @endif
                                        <a href="{{ route('admin.marketing.marketing-event.show', $event) }}"
                                           class="text-blue-600 hover:text-blue-900"
                                           title="View">
                                            <i class="fa-solid fa-eye"></i>
                                        </a>
                                        <a href="{{ route('admin.marketing.marketing-event.edit', $event) }}"
                                           class="text-blue-600 hover:text-blue-900"
                                           title="Edit">
                                            <i class="fa-solid fa-edit"></i>
                                        </a>
                                        <button onclick="deleteEvent({{ $event->id }})"
                                                class="text-red-600 hover:text-red-900"
                                                title="Delete">
                                            <i class="fa-solid fa-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="text-center py-12">
                <i class="fa-solid fa-calendar-alt text-4xl text-gray-400 mb-4"></i>
                <p class="text-gray-600">No marketing events found. Create your first event!</p>
            </div>
        @endif
    </div>

    {{-- Statistics --}}
    @if($marketingEvents->count() > 0)
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <div class="flex items-center">
                    <div class="h-12 w-12 rounded-lg bg-purple-50 flex items-center justify-center">
                        <i class="fa-solid fa-calendar-alt text-purple-600 text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Total Events</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $marketingEvents->count() }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <div class="flex items-center">
                    <div class="h-12 w-12 rounded-lg bg-blue-50 flex items-center justify-center">
                        <i class="fa-solid fa-clock text-blue-600 text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Upcoming Events</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $marketingEvents->where('start_date', '>=', now())->count() }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <div class="flex items-center">
                    <div class="h-12 w-12 rounded-lg bg-yellow-50 flex items-center justify-center">
                        <i class="fa-solid fa-star text-yellow-600 text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Featured</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $marketingEvents->where('is_featured', true)->count() }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <div class="flex items-center">
                    <div class="h-12 w-12 rounded-lg bg-green-50 flex items-center justify-center">
                        <i class="fa-solid fa-globe text-green-600 text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Online Events</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $marketingEvents->whereNotNull('meeting_url')->count() }}</p>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>

{{-- Delete Modal --}}
<div id="deleteModal" class="fixed inset-0 bg-gray-600/50 overflow-y-auto h-full w-full hidden z-50">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <div class="flex items-center mb-4">
                <i class="fa-solid fa-exclamation-triangle text-red-500 text-2xl mr-3"></i>
                <h3 class="text-lg font-semibold text-gray-900">Delete Marketing Event</h3>
            </div>
            <p class="text-gray-600 mb-6">
                Are you sure you want to delete this marketing event? This action cannot be undone and will also delete any associated images.
            </p>
            <div class="flex items-center justify-end space-x-4">
                <button type="button"
                        onclick="document.getElementById('deleteModal').classList.add('hidden')"
                        class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400 transition-colors duration-200">
                    Cancel
                </button>
                <form id="deleteForm" method="POST" class="inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit"
                            class="px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700 transition-colors duration-200">
                        Delete
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
function deleteEvent(eventId) {
    document.getElementById('deleteForm').action = `/admin/marketing/marketing-event/${eventId}`;
    document.getElementById('deleteModal').classList.remove('hidden');
}
</script>
</x-layouts.admin>
