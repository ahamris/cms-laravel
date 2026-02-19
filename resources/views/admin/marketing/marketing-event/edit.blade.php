<x-layouts.admin title="Edit Marketing Event">
<div class="space-y-6">
    {{-- Header --}}
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Edit Marketing Event</h1>
            <p class="text-gray-600">Update {{ $marketingEvent->title }}</p>
        </div>
        <a href="{{ route('admin.marketing.marketing-event.index') }}"
           class="bg-gray-600 text-white px-4 py-2 rounded-lg hover:bg-gray-700 transition-colors duration-200">
            <i class="fa-solid fa-arrow-left mr-2"></i>
            Back to List
        </a>
    </div>

    {{-- Form --}}
    <form action="{{ route('admin.marketing.marketing-event.update', $marketingEvent) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            {{-- Main Content --}}
            <div class="lg:col-span-2 space-y-6">
                {{-- Basic Information --}}
                <div class="bg-white rounded-xl shadow-sm border border-gray-200">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-900">Basic Information</h3>
                    </div>
                    <div class="p-6 space-y-4">
                        {{-- Title --}}
                        <div>
                            <label for="title" class="block text-sm font-medium text-gray-700 mb-2">
                                Event Title <span class="text-red-500">*</span>
                            </label>
                            <input type="text"
                                   id="title"
                                   name="title"
                                   value="{{ old('title', $marketingEvent->title) }}"
                                   required
                                   placeholder="e.g., Digital Marketing Masterclass 2024"
                                   class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-primary focus:border-primary @error('title') border-red-500 @enderror">
                            @error('title')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                        </div>

                        {{-- Slug --}}
                        <div>
                            <label for="slug" class="block text-sm font-medium text-gray-700 mb-2">Slug</label>
                            <input type="text"
                                   id="slug"
                                   name="slug"
                                   value="{{ old('slug', $marketingEvent->slug) }}"
                                   placeholder="Auto-generated from title"
                                   class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-primary focus:border-primary @error('slug') border-red-500 @enderror">
                            @error('slug')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                        </div>

                        {{-- Description --}}
                        <div>
                            <label for="description" class="block text-sm font-medium text-gray-700 mb-2">Description</label>
                            <textarea id="description"
                                      name="description"
                                      rows="4"
                                      placeholder="Brief description of the event..."
                                      class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-primary focus:border-primary @error('description') border-red-500 @enderror">{{ old('description', $marketingEvent->description) }}</textarea>
                            @error('description')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            {{-- Event Type --}}
                            <div>
                                <label for="event_type" class="block text-sm font-medium text-gray-700 mb-2">
                                    Event Type <span class="text-red-500">*</span>
                                </label>
                                <select id="event_type"
                                        name="event_type"
                                        required
                                        class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-primary focus:border-primary @error('event_type') border-red-500 @enderror">
                                    <option value="">Select event type</option>
                                    <option value="webinar" {{ old('event_type', $marketingEvent->event_type) == 'webinar' ? 'selected' : '' }}>Webinar</option>
                                    <option value="workshop" {{ old('event_type', $marketingEvent->event_type) == 'workshop' ? 'selected' : '' }}>Workshop</option>
                                    <option value="conference" {{ old('event_type', $marketingEvent->event_type) == 'conference' ? 'selected' : '' }}>Conference</option>
                                    <option value="meetup" {{ old('event_type', $marketingEvent->event_type) == 'meetup' ? 'selected' : '' }}>Meetup</option>
                                    <option value="online_event" {{ old('event_type', $marketingEvent->event_type) == 'online_event' ? 'selected' : '' }}>Online Event</option>
                                </select>
                                @error('event_type')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                            </div>

                            {{-- Registration URL --}}
                            <div>
                                <label for="registration_url" class="block text-sm font-medium text-gray-700 mb-2">Registration URL</label>
                                <input type="url"
                                       id="registration_url"
                                       name="registration_url"
                                       value="{{ old('registration_url', $marketingEvent->registration_url) }}"
                                       placeholder="https://example.com/register"
                                       class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-primary focus:border-primary @error('registration_url') border-red-500 @enderror">
                                @error('registration_url')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Date & Time --}}
                <div class="bg-white rounded-xl shadow-sm border border-gray-200">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-900">Date & Time</h3>
                    </div>
                    <div class="p-6 space-y-4">
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            {{-- Event Date --}}
                            <div>
                                <label for="event_date" class="block text-sm font-medium text-gray-700 mb-2">
                                    Event Date <span class="text-red-500">*</span>
                                </label>
                                <input type="date"
                                       id="event_date"
                                       name="event_date"
                                       value="{{ old('event_date', $marketingEvent->event_date->format('Y-m-d')) }}"
                                       required
                                       class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-primary focus:border-primary @error('event_date') border-red-500 @enderror">
                                @error('event_date')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                            </div>

                            {{-- Start Time --}}
                            <div>
                                <label for="start_time" class="block text-sm font-medium text-gray-700 mb-2">Start Time</label>
                                <input type="time"
                                       id="start_time"
                                       name="start_time"
                                       value="{{ old('start_time', $marketingEvent->start_time) }}"
                                       class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-primary focus:border-primary @error('start_time') border-red-500 @enderror">
                                @error('start_time')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                            </div>

                            {{-- End Time --}}
                            <div>
                                <label for="end_time" class="block text-sm font-medium text-gray-700 mb-2">End Time</label>
                                <input type="time"
                                       id="end_time"
                                       name="end_time"
                                       value="{{ old('end_time', $marketingEvent->end_time) }}"
                                       class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-primary focus:border-primary @error('end_time') border-red-500 @enderror">
                                @error('end_time')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Location --}}
                <div class="bg-white rounded-xl shadow-sm border border-gray-200">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-900">Location</h3>
                    </div>
                    <div class="p-6 space-y-4">
                        {{-- Online Event Toggle --}}
                        <div class="flex items-center">
                            <input type="checkbox"
                                   id="is_online"
                                   name="is_online"
                                   value="1"
                                   {{ old('is_online', $marketingEvent->is_online) ? 'checked' : '' }}
                                   class="h-4 w-4 text-primary focus:ring-primary border-gray-300 rounded">
                            <label for="is_online" class="ml-2 block text-sm text-gray-900">
                                <i class="fa-solid fa-globe text-blue-600 mr-1"></i>
                                This is an online event
                            </label>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            {{-- Location --}}
                            <div>
                                <label for="location" class="block text-sm font-medium text-gray-700 mb-2">Location/Venue</label>
                                <input type="text"
                                       id="location"
                                       name="location"
                                       value="{{ old('location', $marketingEvent->location) }}"
                                       placeholder="e.g., Conference Center Amsterdam"
                                       class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-primary focus:border-primary @error('location') border-red-500 @enderror">
                                @error('location')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                            </div>

                            {{-- Max Attendees --}}
                            <div>
                                <label for="max_attendees" class="block text-sm font-medium text-gray-700 mb-2">Max Attendees</label>
                                <input type="number"
                                       id="max_attendees"
                                       name="max_attendees"
                                       value="{{ old('max_attendees', $marketingEvent->max_attendees) }}"
                                       min="1"
                                       placeholder="e.g., 100"
                                       class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-primary focus:border-primary @error('max_attendees') border-red-500 @enderror">
                                @error('max_attendees')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Speakers --}}
                <div class="bg-white rounded-xl shadow-sm border border-gray-200">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-900">Speakers</h3>
                        <p class="text-sm text-gray-600">Add event speakers and presenters</p>
                    </div>
                    <div class="p-6">
                        <div id="speakers-container">
                            @if($marketingEvent->speakers && count($marketingEvent->speakers) > 0)
                                @foreach($marketingEvent->speakers as $index => $speaker)
                                    <div class="speaker-item flex items-center space-x-2 mb-2">
                                        <input type="text"
                                               name="speakers[]"
                                               value="{{ old('speakers.' . $index, $speaker) }}"
                                               placeholder="Enter speaker name and title"
                                               class="flex-1 border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-primary focus:border-primary">
                                        <button type="button" onclick="removeSpeaker(this)" class="text-red-600 hover:text-red-800">
                                            <i class="fa-solid fa-times"></i>
                                        </button>
                                    </div>
                                @endforeach
                            @else
                                <div class="speaker-item flex items-center space-x-2 mb-2">
                                    <input type="text"
                                           name="speakers[]"
                                           placeholder="Enter speaker name and title"
                                           class="flex-1 border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-primary focus:border-primary">
                                    <button type="button" onclick="removeSpeaker(this)" class="text-red-600 hover:text-red-800">
                                        <i class="fa-solid fa-times"></i>
                                    </button>
                                </div>
                            @endif
                        </div>
                        <button type="button" onclick="addSpeaker()" class="text-primary hover:text-primary/80 text-sm">
                            <i class="fa-solid fa-plus mr-1"></i> Add Speaker
                        </button>
                    </div>
                </div>

                {{-- Agenda --}}
                <div class="bg-white rounded-xl shadow-sm border border-gray-200">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-900">Agenda</h3>
                        <p class="text-sm text-gray-600">Add agenda items and schedule</p>
                    </div>
                    <div class="p-6">
                        <div id="agenda-container">
                            @if($marketingEvent->agenda && count($marketingEvent->agenda) > 0)
                                @foreach($marketingEvent->agenda as $index => $agendaItem)
                                    <div class="agenda-item flex items-center space-x-2 mb-2">
                                        <input type="text"
                                               name="agenda[]"
                                               value="{{ old('agenda.' . $index, $agendaItem) }}"
                                               placeholder="Enter agenda item"
                                               class="flex-1 border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-primary focus:border-primary">
                                        <button type="button" onclick="removeAgenda(this)" class="text-red-600 hover:text-red-800">
                                            <i class="fa-solid fa-times"></i>
                                        </button>
                                    </div>
                                @endforeach
                            @else
                                <div class="agenda-item flex items-center space-x-2 mb-2">
                                    <input type="text"
                                           name="agenda[]"
                                           placeholder="Enter agenda item"
                                           class="flex-1 border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-primary focus:border-primary">
                                    <button type="button" onclick="removeAgenda(this)" class="text-red-600 hover:text-red-800">
                                        <i class="fa-solid fa-times"></i>
                                    </button>
                                </div>
                            @endif
                        </div>
                        <button type="button" onclick="addAgenda()" class="text-primary hover:text-primary/80 text-sm">
                            <i class="fa-solid fa-plus mr-1"></i> Add Agenda Item
                        </button>
                    </div>
                </div>
            </div>

            {{-- Sidebar --}}
            <div class="lg:col-span-1 space-y-6">
                {{-- Current Featured Image --}}
                @if($marketingEvent->featured_image)
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200">
                        <div class="px-6 py-4 border-b border-gray-200">
                            <h3 class="text-lg font-semibold text-gray-900">Current Image</h3>
                        </div>
                        <div class="p-6">
                            <img src="{{ asset('storage/' . $marketingEvent->featured_image) }}" 
                                 alt="{{ $marketingEvent->title }}" 
                                 class="w-full h-32 object-cover rounded">
                        </div>
                    </div>
                @endif

                {{-- Featured Image --}}
                <div class="bg-white rounded-xl shadow-sm border border-gray-200">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-900">{{ $marketingEvent->featured_image ? 'Update' : 'Add' }} Featured Image</h3>
                    </div>
                    <div class="p-6">
                        <div>
                            <label for="featured_image" class="block text-sm font-medium text-gray-700 mb-2">Event Image</label>
                            <input type="file"
                                   id="featured_image"
                                   name="featured_image"
                                   accept="image/*"
                                   class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-primary focus:border-primary @error('featured_image') border-red-500 @enderror">
                            <p class="text-xs text-gray-500 mt-1">Max 2MB. JPG, PNG, GIF, SVG</p>
                            @error('featured_image')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                        </div>
                    </div>
                </div>

                {{-- Settings --}}
                <div class="bg-white rounded-xl shadow-sm border border-gray-200">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-900">Settings</h3>
                    </div>
                    <div class="p-6 space-y-4">
                        {{-- Featured --}}
                        <div class="flex items-center">
                            <input type="checkbox"
                                   id="is_featured"
                                   name="is_featured"
                                   value="1"
                                   {{ old('is_featured', $marketingEvent->is_featured) ? 'checked' : '' }}
                                   class="h-4 w-4 text-primary focus:ring-primary border-gray-300 rounded">
                            <label for="is_featured" class="ml-2 block text-sm text-gray-900">
                                <i class="fa-solid fa-star text-yellow-500 mr-1"></i>
                                Featured Event
                            </label>
                        </div>

                        {{-- Active Status --}}
                        <div class="flex items-center">
                            <input type="checkbox"
                                   id="is_active"
                                   name="is_active"
                                   value="1"
                                   {{ old('is_active', $marketingEvent->is_active) ? 'checked' : '' }}
                                   class="h-4 w-4 text-primary focus:ring-primary border-gray-300 rounded">
                            <label for="is_active" class="ml-2 block text-sm text-gray-900">Active</label>
                        </div>

                        {{-- Sort Order --}}
                        <div>
                            <label for="sort_order" class="block text-sm font-medium text-gray-700 mb-2">Sort Order</label>
                            <input type="number"
                                   id="sort_order"
                                   name="sort_order"
                                   value="{{ old('sort_order', $marketingEvent->sort_order) }}"
                                   min="0"
                                   class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-primary focus:border-primary">
                        </div>

                        {{-- Metadata --}}
                        <div class="pt-4 border-t border-gray-200">
                            <div class="text-sm text-gray-600">
                                <p><strong>Created:</strong> {{ $marketingEvent->created_at->format('M j, Y \a\t g:i A') }}</p>
                                @if($marketingEvent->updated_at != $marketingEvent->created_at)
                                    <p><strong>Updated:</strong> {{ $marketingEvent->updated_at->format('M j, Y \a\t g:i A') }}</p>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Submit --}}
                <div class="bg-white rounded-xl shadow-sm border border-gray-200">
                    <div class="p-6">
                        <button type="submit"
                                class="w-full bg-primary text-white py-2 px-4 rounded-lg hover:bg-primary/80 transition-colors duration-200">
                            <i class="fa-solid fa-save mr-2"></i>
                            Update Event
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

<script>
// Speaker management functions
function addSpeaker() {
    const container = document.getElementById('speakers-container');
    const div = document.createElement('div');
    div.className = 'speaker-item flex items-center space-x-2 mb-2';
    div.innerHTML = `
        <input type="text" name="speakers[]" placeholder="Enter speaker name and title" 
               class="flex-1 border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-primary focus:border-primary">
        <button type="button" onclick="removeSpeaker(this)" class="text-red-600 hover:text-red-800">
            <i class="fa-solid fa-times"></i>
        </button>
    `;
    container.appendChild(div);
}

function removeSpeaker(button) {
    button.closest('.speaker-item').remove();
}

// Agenda management functions
function addAgenda() {
    const container = document.getElementById('agenda-container');
    const div = document.createElement('div');
    div.className = 'agenda-item flex items-center space-x-2 mb-2';
    div.innerHTML = `
        <input type="text" name="agenda[]" placeholder="Enter agenda item" 
               class="flex-1 border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-primary focus:border-primary">
        <button type="button" onclick="removeAgenda(this)" class="text-red-600 hover:text-red-800">
            <i class="fa-solid fa-times"></i>
        </button>
    `;
    container.appendChild(div);
}

function removeAgenda(button) {
    button.closest('.agenda-item').remove();
}
</script>
</x-layouts.admin>
