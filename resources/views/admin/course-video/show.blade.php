<x-layouts.admin title="Course Video: {{ $courseVideo->title }}">
    {{-- Page Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-8">
        <div>
            <div class="flex items-center gap-3 mb-2">
                <h1 class="text-3xl font-bold text-zinc-900 dark:text-white">{{ $courseVideo->title }}</h1>
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                    {{ $courseVideo->is_active ? 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-300' : 'bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-300' }}">
                    {{ $courseVideo->is_active ? 'Active' : 'Inactive' }}
                </span>
            </div>
            <p class="text-zinc-600 dark:text-zinc-400">
                @if($courseVideo->category)
                    {{ $courseVideo->category->name }}
                    @if($courseVideo->duration_formatted)
                        · {{ $courseVideo->duration_formatted }}
                    @endif
                @else
                    @if($courseVideo->duration_formatted) {{ $courseVideo->duration_formatted }} @endif
                @endif
            </p>
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ route('admin.content.course-video.index') }}"
                class="inline-flex items-center gap-2 rounded-md bg-white dark:bg-white/10 px-4 py-2 text-sm font-semibold text-gray-900 dark:text-white shadow-xs ring-1 ring-gray-300 ring-inset dark:ring-white/10 hover:bg-gray-50 dark:hover:bg-white/20">
                <i class="fa-solid fa-arrow-left"></i>
                Back to list
            </a>
            <a href="{{ route('admin.content.course-video.edit', $courseVideo) }}">
                <x-button variant="primary" icon="edit">Edit Video</x-button>
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        {{-- Left Column - 2/3 --}}
        <div class="lg:col-span-2 space-y-8">
            {{-- Video Player --}}
            <div class="rounded-md border border-zinc-200 dark:border-white/10 bg-white dark:bg-white/5 p-6 shadow-sm">
                @if($courseVideo->video_source_url)
                    <div class="aspect-video bg-zinc-900 rounded-lg overflow-hidden relative shadow-lg">
                        @if(str_contains($courseVideo->video_source_url, 'youtube.com') || str_contains($courseVideo->video_source_url, 'youtu.be'))
                            @php
                                $ytId = preg_match('/(?:youtube\.com\/(?:[^\/]+\/.+\/|(?:v|e(?:mbed)?)\/|.*[?&]v=)|youtu\.be\/)([^"&?\/\s]{11})/', $courseVideo->video_source_url, $m) ? ($m[1] ?? null) : null;
                            @endphp
                            @if($ytId)
                                <iframe class="w-full h-full" src="https://www.youtube.com/embed/{{ $ytId }}" allowfullscreen></iframe>
                            @else
                                <a href="{{ $courseVideo->video_source_url }}" target="_blank" rel="noopener" class="flex items-center justify-center h-full text-white">Watch external video</a>
                            @endif
                        @elseif(str_contains($courseVideo->video_source_url, 'vimeo.com'))
                            @php
                                $vimeoId = preg_match('/vimeo\.com\/(?:video\/)?(\d+)/', $courseVideo->video_source_url, $m) ? ($m[1] ?? null) : null;
                            @endphp
                            @if($vimeoId)
                                <iframe class="w-full h-full" src="https://player.vimeo.com/video/{{ $vimeoId }}" allowfullscreen></iframe>
                            @else
                                <a href="{{ $courseVideo->video_source_url }}" target="_blank" rel="noopener" class="flex items-center justify-center h-full text-white">Watch on Vimeo</a>
                            @endif
                        @else
                            <video class="w-full h-full" controls src="{{ $courseVideo->video_source_url }}" poster="{{ $courseVideo->thumbnail_url }}">
                                Your browser does not support the video tag. <a href="{{ $courseVideo->video_source_url }}" target="_blank">Download</a>
                            </video>
                        @endif
                    </div>
                @else
                    <div class="aspect-video bg-zinc-100 dark:bg-white/5 rounded-lg flex items-center justify-center text-zinc-500 border border-dashed border-zinc-300 dark:border-white/10">
                        <div class="text-center">
                            <i class="fa-solid fa-film text-4xl mb-2 text-zinc-400"></i>
                            <p>No video source set.</p>
                        </div>
                    </div>
                @endif
            </div>

            {{-- Description --}}
            @if($courseVideo->description)
                <div class="rounded-md border border-zinc-200 dark:border-white/10 bg-white dark:bg-white/5 p-6 shadow-sm">
                    <h2 class="text-lg font-bold text-gray-900 dark:text-white mb-4">Description</h2>
                    <div class="prose prose-sm dark:prose-invert max-w-none text-zinc-600 dark:text-zinc-400">
                        {!! $courseVideo->description !!}
                    </div>
                </div>
            @endif
        </div>

        {{-- Right Column - 1/3 --}}
        <div class="lg:col-span-1 space-y-8">
            {{-- Meta Information --}}
            <div class="rounded-md border border-zinc-200 dark:border-white/10 bg-white dark:bg-white/5 p-6 shadow-sm">
                <h2 class="text-lg font-bold text-gray-900 dark:text-white mb-4">Details</h2>
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-zinc-500 dark:text-zinc-400 mb-1">Category</label>
                        <p class="text-zinc-900 dark:text-white font-medium">{{ $courseVideo->category?->name ?? 'Uncategorized' }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-zinc-500 dark:text-zinc-400 mb-1">Status</label>
                         <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                            {{ $courseVideo->is_active ? 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-300' : 'bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-300' }}">
                            {{ $courseVideo->is_active ? 'Active' : 'Inactive' }}
                        </span>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-zinc-500 dark:text-zinc-400 mb-1">Duration</label>
                        <p class="text-zinc-900 dark:text-white font-medium">{{ $courseVideo->duration_formatted ?? '—' }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-zinc-500 dark:text-zinc-400 mb-1">Slug</label>
                        <p class="text-zinc-900 dark:text-white font-mono text-sm break-all">{{ $courseVideo->slug }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-zinc-500 dark:text-zinc-400 mb-1">Creation Date</label>
                        <p class="text-zinc-900 dark:text-white font-medium">{{ $courseVideo->created_at->format('d M Y, H:i') }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-zinc-500 dark:text-zinc-400 mb-1">Last Update</label>
                        <p class="text-zinc-900 dark:text-white font-medium">{{ $courseVideo->updated_at->format('d M Y, H:i') }}</p>
                    </div>
                </div>
            </div>

            {{-- Actions --}}
            <div class="rounded-md border border-zinc-200 dark:border-white/10 bg-white dark:bg-white/5 p-6 shadow-sm">
                <h2 class="text-lg font-bold text-gray-900 dark:text-white mb-4">Actions</h2>
                <div class="space-y-3">
                    <form action="{{ route('admin.content.course-video.destroy', $courseVideo) }}" method="POST"
                          onsubmit="return confirm('Are you sure you want to delete this video? This action cannot be undone.')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" 
                                class="w-full bg-red-600 text-white px-4 py-2 rounded-lg hover:bg-red-700 transition-colors duration-200 flex items-center justify-center gap-2">
                            <i class="fa-solid fa-trash"></i>
                            Delete Video
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-layouts.admin>
