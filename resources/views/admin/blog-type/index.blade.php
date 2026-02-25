<x-layouts.admin title="Blog Types">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-8">
        <div>
            <h1 class="text-3xl font-bold text-zinc-900 dark:text-white mb-2">Blog Types</h1>
            <p class="text-zinc-600 dark:text-zinc-400">Manage blog types (e.g. Article, News, Tutorial).</p>
        </div>
        <a href="{{ route('admin.blog-type.create') }}"
            class="inline-flex items-center gap-2 rounded-md bg-[var(--color-accent)] px-4 py-2 text-sm font-semibold text-white shadow-xs hover:opacity-90">
            <i class="fa-solid fa-plus"></i>
            Add Blog Type
        </a>
    </div>

    <div class="rounded-lg border border-gray-200 dark:border-white/10 bg-white dark:bg-white/5 overflow-hidden">
        <table class="min-w-full divide-y divide-gray-200 dark:divide-white/10">
            <thead class="bg-gray-50 dark:bg-white/5">
                <tr>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">ID</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Name</th>
                    <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200 dark:divide-white/10">
                @forelse($blogTypes as $blogType)
                <tr>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">{{ $blogType->id }}</td>
                    <td class="px-6 py-4 text-sm text-gray-900 dark:text-white">{{ $blogType->name }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm">
                        <a href="{{ route('admin.blog-type.show', $blogType) }}" class="text-[var(--color-accent)] hover:underline mr-3">View</a>
                        <a href="{{ route('admin.blog-type.edit', $blogType) }}" class="text-[var(--color-accent)] hover:underline mr-3">Edit</a>
                        <form action="{{ route('admin.blog-type.destroy', $blogType) }}" method="POST" class="inline" onsubmit="return confirm('Delete this blog type?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-600 hover:underline">Delete</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="3" class="px-6 py-8 text-center text-sm text-gray-500 dark:text-gray-400">No blog types yet. <a href="{{ route('admin.blog-type.create') }}" class="text-[var(--color-accent)] hover:underline">Create one</a>.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
        @if($blogTypes->hasPages())
        <div class="px-6 py-3 border-t border-gray-200 dark:border-white/10">
            {{ $blogTypes->links() }}
        </div>
        @endif
    </div>
</x-layouts.admin>
