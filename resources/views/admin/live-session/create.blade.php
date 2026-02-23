<x-layouts.admin title="Create Live Session">
    {{-- Page Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-8">
        <div>
            <h1 class="text-3xl font-bold text-zinc-900 dark:text-white mb-2">Create Live Session</h1>
            <p class="text-zinc-600 dark:text-zinc-400">Schedule a new academy live session</p>
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ route('admin.content.live-session.index') }}"
                class="inline-flex items-center gap-2 rounded-md bg-white dark:bg-white/10 px-4 py-2 text-sm font-semibold text-gray-900 dark:text-white shadow-xs ring-1 ring-gray-300 ring-inset dark:ring-white/10 hover:bg-gray-50 dark:hover:bg-white/20">
                <i class="fa-solid fa-arrow-left"></i>
                Back to Sessions
            </a>
        </div>
    </div>

    <form action="{{ route('admin.content.live-session.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            {{-- Left Column - 2/3 --}}
            <div class="lg:col-span-2 space-y-8">
                {{-- Session Details Section --}}
                <div
                    class="rounded-md border border-zinc-200 dark:border-white/10 bg-white dark:bg-white/5 p-6 shadow-sm">
                    <div class="mb-6">
                        <h2 class="text-lg font-bold text-gray-900 dark:text-white">Session Details</h2>
                        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">Basic information about the session.
                        </p>
                    </div>

                    <div class="space-y-6">
                        <x-ui.input name="title" id="title" label="Session Title" :value="old('title')"
                            placeholder="Enter session title" required :error="$errors->has('title')"
                            :errorMessage="$errors->first('title')" />

                        <x-ui.textarea name="description" id="description" label="Short Description"
                            :value="old('description')" placeholder="Brief description of the session..." :rows="3"
                            :error="$errors->has('description')" :errorMessage="$errors->first('description')" />

                        <div class="space-y-2">
                            <label class="block text-sm font-medium text-gray-900 dark:text-white">Content <span
                                    class="text-red-500">*</span></label>
                            <div class="prose-container">
                                <x-editor name="content" id="content"
                                    placeholder="Detailed session content, agenda, learning objectives..."
                                    :value="old('content')" />
                            </div>
                            @error('content')<p class="mt-2 text-sm text-red-600">{{ $message }}</p>@enderror
                        </div>
                    </div>
                </div>

                {{-- Meeting Config Section --}}
                <div
                    class="rounded-md border border-zinc-200 dark:border-white/10 bg-white dark:bg-white/5 p-6 shadow-sm">
                    <div class="mb-6">
                        <h2 class="text-lg font-bold text-gray-900 dark:text-white">Meeting Configuration</h2>
                        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">Links for the live meeting and
                            recording.</p>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <x-ui.input name="meeting_url" id="meeting_url" label="Meeting URL" type="url"
                            :value="old('meeting_url')" placeholder="https://zoom.us/j/..."
                            :error="$errors->has('meeting_url')" :errorMessage="$errors->first('meeting_url')" />
                        <x-ui.input name="recording_url" id="recording_url" label="Recording URL" type="url"
                            :value="old('recording_url')" placeholder="https://youtu.be/..."
                            :error="$errors->has('recording_url')" :errorMessage="$errors->first('recording_url')" />
                    </div>
                </div>
            </div>

            {{-- Right Column - 1/3 --}}
            <div class="lg:col-span-1 space-y-8">
                {{-- Publish Action --}}
                <div
                    class="rounded-md border border-zinc-200 dark:border-white/10 bg-white dark:bg-white/5 p-6 shadow-sm">
                    <h2 class="text-lg font-bold text-gray-900 dark:text-white mb-4">Publish</h2>
                    <div class="space-y-4">
                        <x-button variant="primary" type="submit" class="w-full justify-center" icon="save"
                            icon-position="left">Create Session</x-button>
                        <a href="{{ route('admin.content.live-session.index') }}" class="block">
                            <x-button variant="secondary" type="button" class="w-full justify-center">Cancel</x-button>
                        </a>
                    </div>
                </div>

                {{-- Status & Schedule --}}
                <div
                    class="rounded-md border border-zinc-200 dark:border-white/10 bg-white dark:bg-white/5 p-6 shadow-sm">
                    <h2 class="text-lg font-bold text-gray-900 dark:text-white mb-4">Status & Schedule</h2>
                    <div class="space-y-4">
                        <x-ui.select name="status" label="Status" :options="['upcoming' => 'Upcoming', 'live' => 'Live', 'completed' => 'Completed', 'cancelled' => 'Cancelled']" :value="old('status', 'upcoming')"
                            required />

                        <x-ui.select name="type" label="Session Type" :options="['' => 'Select Type', 'introduction' => 'Introduction', 'webinar' => 'Webinar', 'workshop' => 'Workshop', 'qa' => 'Q&A Session']"
                            :value="old('type')" required />

                        <x-ui.input name="session_date" id="session_date" label="Date & Time" type="datetime-local"
                            :value="old('session_date')" required />

                        <div class="grid grid-cols-2 gap-4">
                            <x-ui.input name="duration_minutes" id="duration_minutes" label="Duration (min)"
                                type="number" :value="old('duration_minutes', 60)" min="15" />
                            <x-ui.input name="max_participants" id="max_participants" label="Max Users" type="number"
                                :value="old('max_participants', 50)" min="1" />
                        </div>

                        <div class="pt-2">
                            <x-ui.toggle name="is_active" label="Active" :checked="old('is_active', true)" />
                        </div>
                        <div class="pt-2">
                            <x-ui.checkbox name="is_featured" id="is_featured" value="1" label="Featured Session"
                                :checked="(bool) old('is_featured')" />
                        </div>
                    </div>
                </div>

                {{-- Thumbnail --}}
                <div
                    class="rounded-md border border-zinc-200 dark:border-white/10 bg-white dark:bg-white/5 p-6 shadow-sm">
                    <h2 class="text-lg font-bold text-gray-900 dark:text-white mb-4">Thumbnail</h2>
                    <div class="space-y-4">
                        <div x-data="{ preview: null }" class="space-y-3">
                            <!-- Image Preview -->
                            <div x-show="preview"
                                class="relative aspect-video w-full overflow-hidden rounded-lg bg-gray-100 dark:bg-white/5 border border-gray-200 dark:border-white/10">
                                <img :src="preview" class="h-full w-full object-cover">
                                <button type="button" @click="preview = null; $refs.fileInput.value = ''"
                                    class="absolute top-2 right-2 rounded-full bg-red-500 p-1.5 text-white shadow-sm hover:bg-red-600 transition-colors">
                                    <i class="fa-solid fa-times text-xs"></i>
                                </button>
                            </div>

                            <!-- Placeholder -->
                            <div x-show="!preview"
                                class="flex aspect-video w-full items-center justify-center rounded-lg border-2 border-dashed border-gray-300 dark:border-gray-600 bg-gray-50 dark:bg-white/5 hover:bg-gray-100 dark:hover:bg-white/10 transition-colors cursor-pointer"
                                @click="$refs.fileInput.click()">
                                <div class="text-center">
                                    <i class="fa-regular fa-image text-3xl text-gray-400 mb-2"></i>
                                    <p class="text-sm text-gray-500">Click to upload thumbnail</p>
                                </div>
                            </div>

                            <input type="file" name="thumbnail" x-ref="fileInput" class="hidden" accept="image/*"
                                @change="const file = $event.target.files[0]; if(file){ const reader = new FileReader(); reader.onload = (e) => preview = e.target.result; reader.readAsDataURL(file); }">
                            @error('thumbnail')<p class="text-sm text-red-600">{{ $message }}</p>@enderror
                        </div>
                    </div>
                </div>

                {{-- Presenters --}}
                <div class="rounded-md border border-zinc-200 dark:border-white/10 bg-white dark:bg-white/5 p-6 shadow-sm">
                    <h2 class="text-lg font-bold text-gray-900 dark:text-white mb-4">Presenters</h2>
                    <div>
                        <x-ui.combobox 
                            name="presenters" 
                            label="Select Presenters" 
                            :options="$presenters->pluck('name', 'id')->toArray()" 
                            :value="old('presenters', [])" 
                            multiple 
                            searchable 
                            placeholder="Select presenters..." 
                        />
                        <p class="text-xs text-zinc-500 dark:text-zinc-400 mt-1.5">Select one or more presenters.</p>
                    </div>
                </div>

                {{-- Display Settings --}}
                <div
                    class="rounded-md border border-zinc-200 dark:border-white/10 bg-white dark:bg-white/5 p-6 shadow-sm">
                    <h2 class="text-lg font-bold text-gray-900 dark:text-white mb-4">Appearance</h2>
                    <div class="space-y-4">
                        <x-icon-picker id="icon" name="icon" :value="old('icon')" label="Icon Class"
                            help-text="Optional icon" :required="false" />
                        <x-ui.colorpicker name="color" id="color" label="Accent Color" :value="old('color', '#3B82F6')"
                            required />
                    </div>
                </div>

            </div>
        </div>
    </form>
</x-layouts.admin>