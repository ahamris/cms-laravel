<x-layouts.admin title="Edit Event">
<div class="space-y-6">
    {{-- Header --}}
    <div class="flex items-center justify-between mb-6">
        <div class="flex items-center gap-3">
            <div class="w-12 h-12 bg-primary rounded-md flex items-center justify-center">
                <i class="fa-solid fa-edit text-white text-base"></i>
            </div>
            <div class="flex flex-col gap-1">
                <h2>Edit Event</h2>
                <p>Update event information</p>
            </div>
        </div>
        <div class="flex items-center space-x-3">
            <a href="{{ route('admin.event.show', $event) }}" 
               class="px-5 py-2 rounded-md bg-white border border-gray-200 text-gray-700 text-sm hover:bg-gray-50 transition-colors duration-200 flex items-center space-x-2">
                <i class="fa fa-eye"></i>
                <span>View</span>
            </a>
            <a href="{{ route('admin.event.index') }}" 
               class="px-5 py-2 rounded-md bg-white border border-gray-200 text-gray-700 text-sm hover:bg-gray-50 transition-colors duration-200 flex items-center space-x-2">
                <i class="fa fa-arrow-left"></i>
                <span>Back to Events</span>
            </a>
        </div>
    </div>

    {{-- Form --}}
    <div class="bg-gray-50/50 rounded-md border border-gray-200">
        <form action="{{ route('admin.event.update', $event) }}" method="POST" enctype="multipart/form-data" id="eventForm">
            @csrf
            @method('PUT')
            
            <div class="p-6">
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                    {{-- Main Content --}}
                    <div class="lg:col-span-2 space-y-6">
                        {{-- Event Details Card --}}
                        <div class="bg-gray-50/50 rounded-md border border-gray-200 p-6">
                            <h3 class="text-base font-semibold text-gray-900 mb-4 flex items-center">
                                <i class="fa-solid fa-calendar mr-2 text-blue-500"></i>
                                Event Details
                            </h3>
                            <div class="space-y-4">
                                {{-- Title --}}
                                <div>
                                    <label for="title" class="block text-xs font-medium text-gray-700 mb-1">Event Title <span class="text-red-500">*</span></label>
                                    <input type="text" id="title" name="title" value="{{ old('title', $event->title) }}" class="w-full px-3 py-2 text-sm bg-white border border-gray-200 rounded-md focus:outline-none @error('title') border-red-500 @enderror" placeholder="Enter event title" required>
                                    @error('title')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                                </div>

                                {{-- Slug --}}
                                <x-ui.input id="slug" name="slug" label="Slug" :value="old('slug', $event->slug)"
                                    placeholder="event-url-slug"
                                    slug-from="title"
                                    hint="URL-friendly version of the title. Auto-generated if left blank."
                                    :error="$errors->has('slug')" :errorMessage="$errors->first('slug')" />

                                {{-- Event Organizer --}}
                                <div>
                                    <label for="user_id" class="block text-xs font-medium text-gray-700 mb-1">Event Organizer <span class="text-red-500">*</span></label>
                                    <select id="user_id" name="user_id" class="w-full px-3 py-2 text-sm bg-white border border-gray-200 rounded-md focus:outline-none @error('user_id') border-red-500 @enderror" required>
                                        <option value="">Select an organizer</option>
                                        @foreach($users as $user)
                                            <option value="{{ $user->id }}" {{ old('user_id', $event->user_id) == $user->id ? 'selected' : '' }}>{{ $user->name }} ({{ $user->email }})</option>
                                        @endforeach
                                    </select>
                                    @error('user_id')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                                </div>

                                {{-- Short Description --}}
                                <div>
                                    <label for="short_body" class="block text-xs font-medium text-gray-700 mb-1">Short Description <span class="text-red-500">*</span></label>
                                    <textarea id="short_body" name="short_body" rows="3" class="w-full px-3 py-2 text-sm bg-white border border-gray-200 rounded-md focus:outline-none @error('short_body') border-red-500 @enderror" placeholder="Brief description of the event" required>{{ old('short_body', $event->short_body) }}</textarea>
                                    @error('short_body')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                                </div>

                                {{-- Main Content --}}
                                <div>
                                    <label for="long_body" class="block text-xs font-medium text-gray-700 mb-1">Main Content <span class="text-red-500">*</span></label>
                                    <x-editor id="long_body" name="long_body" :value="$event->long_body" placeholder="Write the full content of the event here..." />
                                    @error('long_body')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                                </div>

                                {{-- Additional Description --}}
                                <div>
                                    <label for="description" class="block text-xs font-medium text-gray-700 mb-1">Additional Description</label>
                                    <textarea id="description" name="description" rows="2" class="w-full px-3 py-2 text-sm bg-white border border-gray-200 rounded-md focus:outline-none @error('description') border-red-500 @enderror" placeholder="Additional event information..." maxlength="500">{{ old('description', $event->description) }}</textarea>
                                    @error('description')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                                </div>

                                {{-- Location --}}
                                <div>
                                    <label for="location" class="block text-xs font-medium text-gray-700 mb-1">Location <span class="text-red-500">*</span></label>
                                    <input type="text" id="location" name="location" value="{{ old('location', $event->location) }}" class="w-full px-3 py-2 text-sm bg-white border border-gray-200 rounded-md focus:outline-none @error('location') border-red-500 @enderror" placeholder="Event location" required>
                                    @error('location')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                                </div>

                                {{-- Address --}}
                                <div>
                                    <label for="address" class="block text-xs font-medium text-gray-700 mb-1">Address</label>
                                    <textarea id="address" name="address" rows="2" class="w-full px-3 py-2 text-sm bg-white border border-gray-200 rounded-md focus:outline-none @error('address') border-red-500 @enderror" placeholder="Full address of the event location" maxlength="500">{{ old('address', $event->address) }}</textarea>
                                    @error('address')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                                </div>

                                {{-- Price --}}
                                <div>
                                    <label for="price" class="block text-xs font-medium text-gray-700 mb-1">Price</label>
                                    <div class="relative">
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <span class="text-gray-500 text-sm font-medium">$</span>
                                        </div>
                                        <input type="number" id="price" name="price" value="{{ old('price', $event->price) }}" step="0.01" min="0" class="w-full pl-8 pr-3 py-2 text-sm bg-white border border-gray-200 rounded-md focus:outline-none @error('price') border-red-500 @enderror" placeholder="0.00">
                                    </div>
                                    @error('price')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                                </div>

                                {{-- Registration URL --}}
                                <div>
                                    <label for="registration_url" class="block text-xs font-medium text-gray-700 mb-1">Registration URL</label>
                                    <input type="url" id="registration_url" name="registration_url" value="{{ old('registration_url', $event->registration_url) }}" class="w-full px-3 py-2 text-sm bg-white border border-gray-200 rounded-md focus:outline-none @error('registration_url') border-red-500 @enderror" placeholder="https://example.com/register">
                                    @error('registration_url')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                                </div>

                                {{-- Status --}}
                                <div>
<x-ui.toggle name="is_active" label="Status" :checked="old('is_active', $event->is_active) == 1 || old('is_active', $event->is_active) == '1'" :required="true" />
                                    @error('is_active')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Sidebar --}}
                    <div class="space-y-6">
                        {{-- Featured Image Card --}}
                        <div class="bg-gray-50/50 rounded-md border border-gray-200 p-6">
                            <h3 class="text-base font-semibold text-gray-900 mb-4 flex items-center">
                                <i class="fa-solid fa-image mr-2 text-blue-500"></i>
                                Featured Image
                            </h3>
                            <x-image-upload 
                                id="image"
                                name="image"
                                label=""
                                :required="false"
                                help-text="PNG, JPG, GIF up to 2MB"
                                :max-size="2048"
                                :current-image="$event->image ? Storage::disk('public')->url($event->image) : null"
                                :current-image-alt="$event->title"
                            />
                        </div>

                        {{-- Date & Time Card --}}
                        <div class="bg-gray-50/50 rounded-md border border-gray-200 p-6">
                            <h3 class="text-base font-semibold text-gray-900 mb-4 flex items-center">
                                <i class="fa-solid fa-clock mr-2 text-blue-500"></i>
                                Date & Time
                            </h3>
                            <div class="space-y-4">
                                {{-- Start Date --}}
                                <x-ui.date-picker
                                    name="start_date"
                                    :value="old('start_date', $event->start_date?->format('Y-m-d'))"
                                    label="Start Date"
                                    :required="true" />

                                {{-- End Date --}}
                                <x-ui.date-picker
                                    name="end_date"
                                    :value="old('end_date', $event->end_date?->format('Y-m-d'))"
                                    label="End Date"
                                    :required="true" />

                                {{-- Start Time --}}
                                <div>
                                    <label for="start_time" class="block text-xs font-medium text-gray-700 mb-1">Start Time</label>
                                    <input type="time" id="start_time" name="start_time" value="{{ old('start_time', $event->start_time?->format('H:i')) }}" class="w-full px-3 py-2 text-sm bg-white border border-gray-200 rounded-md focus:outline-none @error('start_time') border-red-500 @enderror">
                                    @error('start_time')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                                </div>

                                {{-- End Time --}}
                                <div>
                                    <label for="end_time" class="block text-xs font-medium text-gray-700 mb-1">End Time</label>
                                    <input type="time" id="end_time" name="end_time" value="{{ old('end_time', $event->end_time?->format('H:i')) }}" class="w-full px-3 py-2 text-sm bg-white border border-gray-200 rounded-md focus:outline-none @error('end_time') border-red-500 @enderror">
                                    @error('end_time')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="px-6 py-4 bg-gray-50/80 border-t border-gray-200 rounded-b-md flex items-center justify-end space-x-3">
                <a href="{{ route('admin.event.index') }}" 
                   class="px-5 py-2 rounded-md bg-white border border-gray-200 text-gray-700 text-sm hover:bg-gray-50 transition-colors duration-200">
                    Cancel
                </a>
                <button type="submit" 
                        class="px-5 py-2 rounded-md bg-primary text-white text-sm hover:bg-primary/80 transition-colors duration-200">
                    Update Event
                </button>
            </div>
        </form>
    </div>
</div>

</x-layouts.admin>
