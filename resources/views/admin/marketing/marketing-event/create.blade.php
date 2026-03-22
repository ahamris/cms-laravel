<x-layouts.admin title="Create Marketing Event">
<div class="space-y-6">
    {{-- Header --}}
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Create Marketing Event</h1>
            <p class="text-gray-600">Add a new webinar, workshop, or marketing event</p>
        </div>
        <a href="{{ route('admin.marketing.marketing-event.index') }}"
           class="bg-gray-600 text-white px-4 py-2 rounded-lg hover:bg-gray-700 transition-colors duration-200">
            <i class="fa-solid fa-arrow-left mr-2"></i>
            Back to List
        </a>
    </div>

    {{-- Form --}}
    <form action="{{ route('admin.marketing.marketing-event.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
        @csrf

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
                                   value="{{ old('title') }}"
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
                                   value="{{ old('slug') }}"
                                   placeholder="Auto-generated from title"
                                   class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-primary focus:border-primary @error('slug') border-red-500 @enderror">
                            <p class="text-xs text-gray-500 mt-1">Leave empty to auto-generate</p>
                            @error('slug')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                        </div>

                        {{-- Description --}}
                        <div>
                            <label for="description" class="block text-sm font-medium text-gray-700 mb-2">Description</label>
                            <textarea id="description"
                                      name="description"
                                      rows="4"
                                      placeholder="Brief description of the event..."
                                      class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-primary focus:border-primary @error('description') border-red-500 @enderror">{{ old('description') }}</textarea>
                            @error('description')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            {{-- Event Type --}}
                            <div>
                                <label for="type" class="block text-sm font-medium text-gray-700 mb-2">
                                    Event Type <span class="text-red-500">*</span>
                                </label>
                                <select id="type"
                                        name="type"
                                        required
                                        class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-primary focus:border-primary @error('type') border-red-500 @enderror">
                                    <option value="">Select event type</option>
                                    <option value="webinar" {{ old('type') == 'webinar' ? 'selected' : '' }}>Webinar</option>
                                    <option value="workshop" {{ old('type') == 'workshop' ? 'selected' : '' }}>Workshop</option>
                                    <option value="conference" {{ old('type') == 'conference' ? 'selected' : '' }}>Conference</option>
                                    <option value="meetup" {{ old('type') == 'meetup' ? 'selected' : '' }}>Meetup</option>
                                    <option value="online_event" {{ old('type') == 'online_event' ? 'selected' : '' }}>Online Event</option>
                                </select>
                                @error('type')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                            </div>

                            {{-- Meeting URL --}}
                            <div>
                                <label for="meeting_url" class="block text-sm font-medium text-gray-700 mb-2">Meeting URL</label>
                                <input type="url"
                                       id="meeting_url"
                                       name="meeting_url"
                                       value="{{ old('meeting_url') }}"
                                       placeholder="https://zoom.us/j/123456789"
                                       class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-primary focus:border-primary @error('meeting_url') border-red-500 @enderror">
                                @error('meeting_url')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
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
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            {{-- Start Date --}}
                            <div>
                                <label for="start_date" class="block text-sm font-medium text-gray-700 mb-2">
                                    Start Date & Time <span class="text-red-500">*</span>
                                </label>
                                <input type="datetime-local"
                                       id="start_date"
                                       name="start_date"
                                       value="{{ old('start_date') }}"
                                       required
                                       class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-primary focus:border-primary @error('start_date') border-red-500 @enderror">
                                @error('start_date')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                            </div>

                            {{-- End Date --}}
                            <div>
                                <label for="end_date" class="block text-sm font-medium text-gray-700 mb-2">End Date & Time</label>
                                <input type="datetime-local"
                                       id="end_date"
                                       name="end_date"
                                       value="{{ old('end_date') }}"
                                       class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-primary focus:border-primary @error('end_date') border-red-500 @enderror">
                                @error('end_date')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
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
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            {{-- Location --}}
                            <div>
                                <label for="location" class="block text-sm font-medium text-gray-700 mb-2">Location/Venue</label>
                                <input type="text"
                                       id="location"
                                       name="location"
                                       value="{{ old('location') }}"
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
                                       value="{{ old('max_attendees') }}"
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
                            <div class="speaker-item flex items-center space-x-2 mb-2">
                                <input type="text"
                                       name="speakers[]"
                                       value="{{ old('speakers.0') }}"
                                       placeholder="e.g., John Smith - Marketing Expert"
                                       class="flex-1 border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-primary focus:border-primary">
                                <button type="button" onclick="removeSpeaker(this)" class="text-red-600 hover:text-red-800">
                                    <i class="fa-solid fa-times"></i>
                                </button>
                            </div>
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
                        <p class="text-sm text-gray-600">Event schedule and agenda</p>
                    </div>
                    <div class="p-6">
                        <div>
                            <label for="agenda" class="block text-sm font-medium text-gray-700 mb-2">Agenda</label>
                            <textarea id="agenda"
                                      name="agenda"
                                      rows="6"
                                      placeholder="09:00 - Welcome & Introduction&#10;09:30 - Keynote Presentation&#10;10:30 - Coffee Break&#10;11:00 - Workshop Session&#10;12:00 - Q&A Session&#10;12:30 - Closing Remarks"
                                      class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-primary focus:border-primary @error('agenda') border-red-500 @enderror">{{ old('agenda') }}</textarea>
                            @error('agenda')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                        </div>
                    </div>
                </div>

                {{-- Tags --}}
                <div class="bg-white rounded-xl shadow-sm border border-gray-200">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-900">Tags</h3>
                    </div>
                    <div class="p-6">
                        <div id="tags-container">
                            <div class="tag-item flex items-center space-x-2 mb-2">
                                <input type="text"
                                       name="tags[]"
                                       value="{{ old('tags.0') }}"
                                       placeholder="e.g., Digital Marketing, SEO, Content Strategy"
                                       class="flex-1 border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-primary focus:border-primary">
                                <button type="button" onclick="removeTag(this)" class="text-red-600 hover:text-red-800">
                                    <i class="fa-solid fa-times"></i>
                                </button>
                            </div>
                        </div>
                        <button type="button" onclick="addTag()" class="text-primary hover:text-primary/80 text-sm">
                            <i class="fa-solid fa-plus mr-1"></i> Add Tag
                        </button>
                    </div>
                </div>
            </div>

            {{-- Sidebar --}}
            <div class="lg:col-span-1 space-y-6">
                {{-- Featured Image --}}
                <div class="bg-white rounded-xl shadow-sm border border-gray-200">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-900">Featured Image</h3>
                    </div>
                    <div class="p-6">
                        <div>
                            <label for="featured_image" class="block text-sm font-medium text-gray-700 mb-2">Event Image</label>
                            <input type="file"
                                   id="featured_image"
                                   name="featured_image"
                                   accept="image/*"
                                   class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-primary focus:border-primary @error('featured_image') border-red-500 @enderror">
                            <p class="text-xs text-gray-500 mt-1">Max 20MB. JPG, PNG, GIF, SVG</p>
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
                                   {{ old('is_featured') ? 'checked' : '' }}
                                   class="h-4 w-4 text-primary focus:ring-primary border-gray-300 rounded">
                            <label for="is_featured" class="ml-2 block text-sm text-gray-900">
                                <i class="fa-solid fa-star text-yellow-500 mr-1"></i>
                                Featured Event
                            </label>
                        </div>

                        {{-- Published Status --}}
                        <div class="flex items-center">
                            <input type="checkbox"
                                   id="is_published"
                                   name="is_published"
                                   value="1"
                                   {{ old('is_published', true) ? 'checked' : '' }}
                                   class="h-4 w-4 text-primary focus:ring-primary border-gray-300 rounded">
                            <label for="is_published" class="ml-2 block text-sm text-gray-900">Published</label>
                        </div>

                        {{-- Registration Open --}}
                        <div class="flex items-center">
                            <input type="checkbox"
                                   id="registration_open"
                                   name="registration_open"
                                   value="1"
                                   {{ old('registration_open', true) ? 'checked' : '' }}
                                   class="h-4 w-4 text-primary focus:ring-primary border-gray-300 rounded">
                            <label for="registration_open" class="ml-2 block text-sm text-gray-900">Registration Open</label>
                        </div>
                    </div>
                </div>

                {{-- Submit --}}
                <div class="bg-white rounded-xl shadow-sm border border-gray-200">
                    <div class="p-6">
                        <button type="submit"
                                class="w-full bg-primary text-white py-2 px-4 rounded-lg hover:bg-primary/80 transition-colors duration-200">
                            <i class="fa-solid fa-save mr-2"></i>
                            Create Event
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

// Tag management functions
function addTag() {
    const container = document.getElementById('tags-container');
    const div = document.createElement('div');
    div.className = 'tag-item flex items-center space-x-2 mb-2';
    div.innerHTML = `
        <input type="text" name="tags[]" placeholder="Enter a tag" 
               class="flex-1 border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-primary focus:border-primary">
        <button type="button" onclick="removeTag(this)" class="text-red-600 hover:text-red-800">
            <i class="fa-solid fa-times"></i>
        </button>
    `;
    container.appendChild(div);
}

function removeTag(button) {
    button.closest('.tag-item').remove();
}
</script>
</x-layouts.admin>
