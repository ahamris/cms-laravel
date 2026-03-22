<x-layouts.admin title="Add Course Video">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-8">
        <div>
            <h1 class="text-3xl font-bold text-zinc-900 dark:text-white mb-2">Add Course Video</h1>
            <p class="text-zinc-600 dark:text-zinc-400">Upload a video or link to an external video</p>
        </div>
        <a href="{{ route('admin.course-video.index') }}" class="inline-flex items-center gap-2 rounded-md bg-white dark:bg-white/10 px-4 py-2 text-sm font-semibold text-gray-900 dark:text-white shadow-xs ring-1 ring-gray-300 ring-inset dark:ring-white/10 hover:bg-gray-50 dark:hover:bg-white/20">
            <i class="fa-solid fa-arrow-left"></i> Back to list
        </a>
    </div>

    <form action="{{ route('admin.course-video.store') }}" method="POST" enctype="multipart/form-data" id="course-video-form">
        @csrf
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <div class="lg:col-span-2 space-y-8">
                <div class="rounded-md border border-zinc-200 dark:border-white/10 bg-white dark:bg-white/5 p-6 shadow-sm">
                    <h2 class="text-lg font-bold text-gray-900 dark:text-white mb-6">Basic Information</h2>
                    <div class="space-y-6" x-data="courseChapterForm()" data-chapters-url="{{ route('admin.courses.by-category') }}">
                        <x-ui.select name="course_category_id" label="Category" :options="['' => 'Select category'] + $categories->mapWithKeys(fn($c) => [$c->id => $c->name])->all()" :value="(string) old('course_category_id')" required :error="$errors->has('course_category_id')" :errorMessage="$errors->first('course_category_id')" />
                        <x-ui.select name="course_id" id="course_id" label="Chapter" :options="$chapterOptions" :value="(string) old('course_id', '')" :error="$errors->has('course_id')" :errorMessage="$errors->first('course_id')" />
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <x-ui.input name="title" id="title" label="Title" :value="old('title')" required :error="$errors->has('title')" :errorMessage="$errors->first('title')" />
                            <x-ui.input name="slug" id="slug" label="Slug" :value="old('slug')" placeholder="url-friendly-title" required slug-from="title" :error="$errors->has('slug')" :errorMessage="$errors->first('slug')" />
                        </div>
                        <div class="space-y-2">
                            <label class="block text-sm font-medium text-zinc-900 dark:text-white">Description</label>
                            <div class="prose-container"><x-editor id="description" name="description" :value="old('description')" placeholder="Video description..." /></div>
                            @error('description')<p class="mt-2 text-sm text-red-600">{{ $message }}</p>@enderror
                        </div>
                    </div>
                </div>
                <div class="rounded-md border border-zinc-200 dark:border-white/10 bg-white dark:bg-white/5 p-6 shadow-sm">
                    <h2 class="text-lg font-bold text-gray-900 dark:text-white mb-6">Video Source</h2>
                    <p class="mt-1 text-sm text-gray-600 dark:text-gray-400 mb-6">Upload a file or provide an external URL. Leave both empty for plain documentation.</p>
                    <div class="space-y-6">
                        <div>
                            <label for="video" class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-2">Video file</label>
                            <input type="file" name="video" id="video" accept="video/mp4,video/webm,video/ogg,video/quicktime" class="block w-full text-sm text-zinc-500 dark:text-zinc-400 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-primary/10 file:text-primary hover:file:bg-primary/20 dark:file:bg-primary/20 dark:file:text-primary-light dark:hover:file:bg-primary/30 cursor-pointer border border-zinc-200 dark:border-white/10 rounded-lg p-2 bg-zinc-50 dark:bg-white/5">
                            @error('video')<p class="mt-1 text-xs text-red-600 dark:text-red-400">{{ $message }}</p>@enderror
                        </div>
                        <div class="relative"><div class="absolute inset-0 flex items-center"><div class="w-full border-t border-zinc-200 dark:border-white/10"></div></div><div class="relative flex justify-center"><span class="bg-white dark:bg-zinc-900 px-2 text-sm text-zinc-500">or</span></div></div>
                        <x-ui.input name="video_url" id="video_url" label="External video URL" type="url" :value="old('video_url')" placeholder="https://..." :error="$errors->has('video_url')" :errorMessage="$errors->first('video_url')" />
                    </div>
                </div>
            </div>
            <div class="lg:col-span-1 space-y-8">
                <div class="rounded-md border border-zinc-200 dark:border-white/10 bg-white dark:bg-white/5 p-6 shadow-sm">
                    <h2 class="text-lg font-bold text-gray-900 dark:text-white mb-4">Actions</h2>
                    <div class="space-y-4">
                        <x-button variant="primary" type="submit" class="w-full justify-center" icon="save" icon-position="left">Create Video</x-button>
                        <a href="{{ route('admin.course-video.index') }}" class="block"><x-button variant="secondary" type="button" class="w-full justify-center">Cancel</x-button></a>
                    </div>
                </div>
                <div class="rounded-md border border-zinc-200 dark:border-white/10 bg-white dark:bg-white/5 p-6 shadow-sm">
                    <h2 class="text-lg font-bold text-gray-900 dark:text-white mb-4">Thumbnail</h2>
                    <x-image-upload id="thumbnail" name="thumbnail" label="Cover image" :required="false" help-text="JPG, PNG, or GIF. Max 20MB." :max-size="20480" size="small" current-image-alt="Cover image" />
                </div>
                <div class="rounded-md border border-zinc-200 dark:border-white/10 bg-white dark:bg-white/5 p-6 shadow-sm">
                    <h2 class="text-lg font-bold text-gray-900 dark:text-white mb-4">Settings</h2>
                    <div class="space-y-4">
                        <x-ui.input name="duration_seconds" id="duration_seconds" label="Duration (seconds)" type="number" :value="old('duration_seconds')" min="0" placeholder="e.g. 600" :error="$errors->has('duration_seconds')" :errorMessage="$errors->first('duration_seconds')" />
                        <x-ui.input name="sort_order" id="sort_order" label="Sort order" type="number" :value="old('sort_order', 0)" min="0" :error="$errors->has('sort_order')" :errorMessage="$errors->first('sort_order')" />
                        <div class="pt-2"><x-ui.toggle name="is_active" label="Visible on academy" :checked="old('is_active', true)" /></div>
                    </div>
                </div>
            </div>
        </div>
    </form>
    @include('admin.course-video.partials.chapter-form-script')
</x-layouts.admin>
