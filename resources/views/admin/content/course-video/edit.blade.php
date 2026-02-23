<x-layouts.admin title="Edit Course Video">
    {{-- Page Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-8">
        <div>
            <h1 class="text-3xl font-bold text-zinc-900 dark:text-white mb-2">Edit Course Video</h1>
            <p class="text-zinc-600 dark:text-zinc-400">{{ $courseVideo->title }}</p>
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ route('admin.content.course-video.show', $courseVideo) }}">
                <x-button variant="secondary" icon="eye">View Details</x-button>
            </a>
            <a href="{{ route('admin.content.course-video.index') }}">
                <x-button variant="secondary" icon="arrow-left">Back to list</x-button>
            </a>
        </div>
    </div>

    <form action="{{ route('admin.content.course-video.update', $courseVideo) }}" method="POST"
        enctype="multipart/form-data" id="course-video-form">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            {{-- Left Column - 2/3 --}}
            <div class="lg:col-span-2 space-y-8">
                {{-- Basic Information --}}
                <div
                    class="rounded-md border border-zinc-200 dark:border-white/10 bg-white dark:bg-white/5 p-6 shadow-sm">
                    <h2 class="text-lg font-bold text-gray-900 dark:text-white mb-6">Basic Information</h2>

                    <div class="space-y-6" x-data="courseChapterForm()"
                        data-chapters-url="{{ route('admin.content.courses.by-category') }}">
                        <x-ui.select name="course_category_id" label="Category" :options="['' => 'Select category'] + $categories->mapWithKeys(fn($c) => [$c->id => $c->name])->all()" :value="(string) old('course_category_id', $courseVideo->course_category_id)" required
                            :error="$errors->has('course_category_id')"
                            :errorMessage="$errors->first('course_category_id')" />

                        <x-ui.select name="course_id" id="course_id" label="Chapter" :options="$chapterOptions" :value="(string) old('course_id', $courseVideo->course_id ?? '')" :error="$errors->has('course_id')" :errorMessage="$errors->first('course_id')" />

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <x-ui.input name="title" id="title" label="Title" :value="old('title', $courseVideo->title)" required :error="$errors->has('title')"
                                :errorMessage="$errors->first('title')" />
                            <x-ui.input name="slug" id="slug" label="Slug" :value="old('slug', $courseVideo->slug)"
                                required slug-from="title" :error="$errors->has('slug')"
                                :errorMessage="$errors->first('slug')" />
                        </div>

                        <div class="space-y-2">
                            <label class="block text-sm font-medium text-zinc-900 dark:text-white">Description</label>
                            <div class="prose-container">
                                <x-editor id="description" name="description" :value="old('description', $courseVideo->description)" placeholder="Video description..." />
                            </div>
                            @error('description')<p class="mt-2 text-sm text-red-600">{{ $message }}</p>@enderror
                        </div>

                    </div>
                </div>

                {{-- Video Source --}}
                <div
                    class="rounded-md border border-zinc-200 dark:border-white/10 bg-white dark:bg-white/5 p-6 shadow-sm">
                    <div class="mb-6">
                        <h2 class="text-lg font-bold text-gray-900 dark:text-white">Video Source</h2>
                        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">Leave both empty to keep this as <strong>plain documentation</strong> (no video).</p>
                        @if($courseVideo->video_path)
                            <div
                                class="mt-2 p-3 bg-blue-50 dark:bg-blue-900/20 text-blue-700 dark:text-blue-300 rounded text-sm flex items-center gap-2">
                                <i class="fa-solid fa-file-video"></i>
                                <span>Current: uploaded file. Upload a new file below to replace it.</span>
                            </div>
                        @elseif($courseVideo->video_url)
                            <div
                                class="mt-2 p-3 bg-green-50 dark:bg-green-900/20 text-green-700 dark:text-green-300 rounded text-sm flex items-center gap-2">
                                <i class="fa-solid fa-link"></i>
                                <span>Current: external URL. Upload a file or change URL below to replace it.</span>
                            </div>
                        @endif
                        @if($courseVideo->hasVideo())
                            <div class="mt-3 flex items-center gap-2">
                                <x-ui.checkbox
                                    name="remove_video"
                                    value="1"
                                    :label="__('admin.academy_video.remove_video')"
                                    :checked="old('remove_video') === '1'"
                                    color="error"
                                />
                            </div>
                        @endif
                    </div>

                    <div class="space-y-6">
                        <div>
                            <label for="video"
                                class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-2">Video file (MP4,
                                WebM, etc.)</label>
                            <input type="file" name="video" id="video"
                                accept="video/mp4,video/webm,video/ogg,video/quicktime"
                                class="block w-full text-sm text-zinc-500 dark:text-zinc-400 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-primary/10 file:text-primary hover:file:bg-primary/20 dark:file:bg-primary/20 dark:file:text-primary-light dark:hover:file:bg-primary/30 cursor-pointer border border-zinc-200 dark:border-white/10 rounded-lg p-2 bg-zinc-50 dark:bg-white/5">
                            @error('video')<p class="mt-1 text-xs text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="relative">
                            <div class="absolute inset-0 flex items-center" aria-hidden="true">
                                <div class="w-full border-t border-zinc-200 dark:border-white/10"></div>
                            </div>
                            <div class="relative flex justify-center">
                                <span class="bg-white dark:bg-zinc-900 px-2 text-sm text-zinc-500">or</span>
                            </div>
                        </div>

                        <x-ui.input name="video_url" id="video_url" label="External video URL" type="url"
                            :value="old('video_url', $courseVideo->video_url)" placeholder="https://..."
                            :error="$errors->has('video_url')" :errorMessage="$errors->first('video_url')" />
                    </div>
                </div>
            </div>

            {{-- Right Column - 1/3 --}}
            <div class="lg:col-span-1 space-y-8">
                {{-- Publish Action --}}
                <div
                    class="rounded-md border border-zinc-200 dark:border-white/10 bg-white dark:bg-white/5 p-6 shadow-sm">
                    <h2 class="text-lg font-bold text-gray-900 dark:text-white mb-4">Actions</h2>
                    <div class="space-y-4">
                        <x-button variant="primary" type="submit" class="w-full justify-center" icon="save"
                            icon-position="left">Update Video</x-button>
                        <a href="{{ route('admin.content.course-video.index') }}" class="block">
                            <x-button variant="secondary" type="button" class="w-full justify-center">Cancel</x-button>
                        </a>
                    </div>
                </div>

                {{-- Thumbnail (cover image for video or documentation) --}}
                <div
                    class="rounded-md border border-zinc-200 dark:border-white/10 bg-white dark:bg-white/5 p-6 shadow-sm">
                    <h2 class="text-lg font-bold text-gray-900 dark:text-white mb-4">Thumbnail</h2>
                    <x-image-upload id="thumbnail" name="thumbnail" label="Cover image" :required="false"
                        help-text="Used for both videos and documentation. Upload JPG, PNG, or GIF. Max 2MB." :max-size="2048" size="small"
                        :current-image="$courseVideo->thumbnail_path ? $courseVideo->thumbnail_url : null"
                        :current-image-alt="$courseVideo->title" />
                </div>

                {{-- Settings --}}
                <div
                    class="rounded-md border border-zinc-200 dark:border-white/10 bg-white dark:bg-white/5 p-6 shadow-sm">
                    <h2 class="text-lg font-bold text-gray-900 dark:text-white mb-4">Settings</h2>
                    <div class="space-y-4">
                        <x-ui.input name="duration_seconds" id="duration_seconds" label="Duration (seconds)"
                            type="number" :value="old('duration_seconds', $courseVideo->duration_seconds)" min="0"
                            :error="$errors->has('duration_seconds')"
                            :errorMessage="$errors->first('duration_seconds')" />

                        <x-ui.input name="sort_order" id="sort_order" label="Sort order" type="number"
                            :value="old('sort_order', $courseVideo->sort_order)" min="0"
                            :error="$errors->has('sort_order')" :errorMessage="$errors->first('sort_order')" />

                        <div class="pt-2">
                            <x-ui.toggle name="is_active" label="Visible on academy" :checked="old('is_active', $courseVideo->is_active)" />
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>

    @include('admin.content.course-video.partials.chapter-form-script')
</x-layouts.admin>
