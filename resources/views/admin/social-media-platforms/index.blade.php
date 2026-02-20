<x-layouts.admin title="Social Media Platforms">
    <div class="space-y-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-zinc-900 dark:text-white mb-2">Social Media Platforms</h1>
                <p class="text-zinc-600 dark:text-zinc-400">Manage social media platforms for sharing and widgets</p>
            </div>
            <a href="{{ route('admin.settings.social-media-platforms.create') }}">
                <x-button variant="primary" icon="plus" icon-position="left">Add Platform</x-button>
            </a>
        </div>

        @if (session('success'))
            <div class="rounded-lg bg-green-50 dark:bg-green-500/10 border border-green-200 dark:border-green-500/20 p-4">
                <p class="text-sm font-medium text-green-800 dark:text-green-300">{{ session('success') }}</p>
            </div>
        @endif

        <div class="rounded-lg border border-gray-200 dark:border-white/10 bg-white dark:bg-white/5 overflow-hidden">
            @if($platforms->count() > 0)
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-gray-50 dark:bg-white/5">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Order</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Name</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Slug</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Color</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Status</th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 dark:divide-white/10">
                            @foreach($platforms as $platform)
                                <tr class="hover:bg-gray-50 dark:hover:bg-white/5">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-zinc-900 dark:text-white">#{{ $platform->sort_order }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="text-sm font-medium text-zinc-900 dark:text-white">{{ $platform->name }}</span>
                                        @if($platform->icon)
                                            <span class="ml-2 text-zinc-500 dark:text-zinc-400"><i class="{{ $platform->icon }}"></i></span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-zinc-600 dark:text-zinc-400">{{ $platform->slug }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="inline-block w-6 h-6 rounded border border-gray-300 dark:border-white/20" style="background-color: {{ $platform->color }}"></span>
                                        <span class="ml-2 text-xs text-zinc-500 dark:text-zinc-400">{{ $platform->color }}</span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $platform->is_active ? 'bg-green-100 text-green-800 dark:bg-green-500/20 dark:text-green-300' : 'bg-red-100 text-red-800 dark:bg-red-500/20 dark:text-red-300' }}">
                                            {{ $platform->is_active ? 'Active' : 'Inactive' }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm">
                                        <a href="{{ route('admin.settings.social-media-platforms.edit', $platform) }}" class="text-[var(--color-accent)] hover:underline mr-3">Edit</a>
                                        <form method="POST" action="{{ route('admin.settings.social-media-platforms.destroy', $platform) }}" class="inline" onsubmit="return confirm('Delete this platform?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 dark:text-red-400 hover:underline">Delete</button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="text-center py-12 px-6">
                    <i class="fa-solid fa-share-nodes text-4xl text-zinc-400 dark:text-zinc-500 mb-4"></i>
                    <p class="text-zinc-600 dark:text-zinc-400">No social media platforms yet. Add one to get started.</p>
                    <a href="{{ route('admin.settings.social-media-platforms.create') }}" class="mt-4 inline-block">
                        <x-button variant="primary" icon="plus">Add Platform</x-button>
                    </a>
                </div>
            @endif
        </div>
    </div>
</x-layouts.admin>
