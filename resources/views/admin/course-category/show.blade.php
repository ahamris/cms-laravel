<x-layouts.admin title="Category: {{ $courseCategory->name }}">


    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">{{ $courseCategory->name }}</h1>
            <p class="text-sm text-gray-500 dark:text-gray-400">Course Category Details</p>
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ route('admin.content.course-category.edit', $courseCategory) }}">
                <x-button variant="secondary" icon="pencil">Edit Category</x-button>
            </a>
            <form action="{{ route('admin.content.course-category.destroy', $courseCategory) }}" method="POST"
                onsubmit="return confirm('Are you sure you want to delete this category?');">
                @csrf
                @method('DELETE')
                <x-button variant="danger" type="submit" icon="trash">Delete</x-button>
            </form>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        {{-- Main Column --}}
        <div class="lg:col-span-2 space-y-8">
            {{-- Details --}}
            <div class="rounded-md border border-zinc-200 dark:border-white/10 bg-white dark:bg-white/5 p-6 shadow-sm">
                <h2 class="text-lg font-bold text-gray-900 dark:text-white mb-6">Details</h2>
                <div class="space-y-4">
                    <div>
                        <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">Description</h3>
                        <p class="mt-1 text-sm text-gray-900 dark:text-gray-200 whitespace-pre-wrap">
                            {{ $courseCategory->description ?: 'No description available.' }}</p>
                    </div>
                </div>
            </div>

            {{-- Videos (Simple List or Link) --}}
            <div class="rounded-md border border-zinc-200 dark:border-white/10 bg-white dark:bg-white/5 p-6 shadow-sm">
                <div class="flex items-center justify-between mb-6">
                    <h2 class="text-lg font-bold text-gray-900 dark:text-white">Videos in this Category</h2>
                    <a href="{{ route('admin.content.course-video.index') }}?category={{ $courseCategory->id }}">
                        <x-button variant="secondary" size="sm" icon="external-link">Manage Videos</x-button>
                    </a>
                </div>

                @if($courseCategory->videos->count() > 0)
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-zinc-200 dark:divide-white/10">
                            <thead class="bg-zinc-50 dark:bg-white/5">
                                <tr>
                                    <th scope="col"
                                        class="px-3 py-3 text-left text-xs font-medium text-zinc-500 uppercase tracking-wider">
                                        Title</th>
                                    <th scope="col"
                                        class="px-3 py-3 text-left text-xs font-medium text-zinc-500 uppercase tracking-wider">
                                        Duration</th>
                                    <th scope="col"
                                        class="px-3 py-3 text-left text-xs font-medium text-zinc-500 uppercase tracking-wider">
                                        Status</th>
                                    <th scope="col" class="relative px-3 py-3"><span class="sr-only">Actions</span></th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-zinc-200 dark:divide-white/10 bg-white dark:bg-transparent">
                                @foreach($courseCategory->videos->take(5) as $video)
                                    <tr>
                                        <td class="px-3 py-3 whitespace-nowrap text-sm text-zinc-900 dark:text-white">
                                            {{ $video->title }}</td>
                                        <td class="px-3 py-3 whitespace-nowrap text-sm text-zinc-500 dark:text-zinc-400">
                                            {{ $video->duration_formatted ?? '-' }}</td>
                                        <td class="px-3 py-3 whitespace-nowrap text-sm">
                                            <x-ui.badge :variant="$video->is_active ? 'success' : 'secondary'">{{ $video->is_active ? 'Active' : 'Draft' }}</x-ui.badge>
                                        </td>
                                        <td class="px-3 py-3 whitespace-nowrap text-right text-sm font-medium">
                                            <a href="{{ route('admin.content.course-video.show', $video) }}"
                                                class="text-primary hover:text-primary-dark">View</a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @if($courseCategory->videos->count() > 5)
                        <div class="mt-4 text-center">
                            <p class="text-sm text-gray-500">Showing 5 of {{ $courseCategory->videos->count() }} videos.</p>
                        </div>
                    @endif
                @else
                    <p class="text-sm text-gray-500 italic">No videos in this category yet.</p>
                @endif
            </div>
        </div>

        {{-- Sidebar --}}
        <div class="lg:col-span-1 space-y-8">
            {{-- Image --}}
            <div class="rounded-md border border-zinc-200 dark:border-white/10 bg-white dark:bg-white/5 p-6 shadow-sm">
                <h2 class="text-lg font-bold text-gray-900 dark:text-white mb-4">Thumbnail</h2>
                @if($courseCategory->image_url)
                    <img src="{{ $courseCategory->image_url }}" alt="{{ $courseCategory->name }}"
                        class="w-full h-auto rounded-md border border-zinc-200 dark:border-white/10">
                @else
                    <div
                        class="w-full aspect-video bg-zinc-100 dark:bg-zinc-800 rounded-md flex items-center justify-center text-zinc-400">
                        <i class="fa-solid fa-image text-3xl"></i>
                    </div>
                    <p class="mt-2 text-xs text-center text-gray-500">No image uploaded</p>
                @endif
            </div>

            {{-- Info --}}
            <div class="rounded-md border border-zinc-200 dark:border-white/10 bg-white dark:bg-white/5 p-6 shadow-sm">
                <h2 class="text-lg font-bold text-gray-900 dark:text-white mb-4">Meta</h2>
                <dl class="space-y-3 text-sm">
                    <div>
                        <dt class="text-zinc-500 dark:text-zinc-400">Slug</dt>
                        <dd class="font-mono text-zinc-900 dark:text-white break-all">{{ $courseCategory->slug }}</dd>
                    </div>
                    <div>
                        <dt class="text-zinc-500 dark:text-zinc-400">Status</dt>
                        <dd class="mt-1"><x-ui.badge :variant="$courseCategory->is_active ? 'success' : 'secondary'">{{ $courseCategory->is_active ? 'Active' : 'Inactive' }}</x-ui.badge></dd>
                    </div>
                    <div>
                        <dt class="text-zinc-500 dark:text-zinc-400">Sort Order</dt>
                        <dd class="text-zinc-900 dark:text-white">{{ $courseCategory->sort_order }}</dd>
                    </div>
                    <div>
                        <dt class="text-zinc-500 dark:text-zinc-400">Total Videos</dt>
                        <dd class="text-zinc-900 dark:text-white">{{ $courseCategory->videos->count() }}</dd>
                    </div>
                </dl>
            </div>
        </div>
    </div>
</x-layouts.admin>
