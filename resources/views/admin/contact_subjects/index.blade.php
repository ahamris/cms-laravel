<x-layouts.admin title="Subjects">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-8">
        <div>
            <h1 class="text-3xl font-bold text-zinc-900 dark:text-white mb-2">Subjects</h1>
            <p class="text-zinc-600 dark:text-zinc-400">Manage contact form subject options (Onderwerp dropdown).</p>
        </div>
        <a href="{{ route('admin.administrator.contact-subjects.create') }}" class="inline-flex items-center gap-2 rounded-md bg-[var(--color-accent)] px-4 py-2 text-sm font-semibold text-white shadow-xs hover:opacity-90">
            <i class="fa-solid fa-plus"></i>
            Add subject
        </a>
    </div>

    @if(session('success'))
        <div class="mb-6">
            <x-ui.alert variant="success" icon="check-circle" :message="session('success')" />
        </div>
    @endif

    <div class="rounded-lg border border-gray-200 dark:border-white/10 bg-white dark:bg-white/5 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-white/10">
                <thead class="bg-gray-50 dark:bg-white/5">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Order</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Title</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Status</th>
                        <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white dark:bg-white/5 divide-y divide-gray-200 dark:divide-white/10">
                    @forelse($subjects as $subject)
                        <tr class="hover:bg-gray-50 dark:hover:bg-white/5">
                            <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-900 dark:text-white">{{ $subject->sort_order }}</td>
                            <td class="px-4 py-3 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-white">{{ $subject->title }}</td>
                            <td class="px-4 py-3 whitespace-nowrap">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $subject->is_active ? 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400' : 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-400' }}">
                                    {{ $subject->is_active ? 'Active' : 'Inactive' }}
                                </span>
                            </td>
                            <td class="px-4 py-3 whitespace-nowrap text-right text-sm font-medium">
                                <a href="{{ route('admin.administrator.contact-subjects.edit', $subject) }}" class="text-[var(--color-accent)] hover:opacity-80 mr-3">Edit</a>
                                <form action="{{ route('admin.administrator.contact-subjects.destroy', $subject) }}" method="POST" class="inline" onsubmit="return confirm('Delete this subject?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 dark:text-red-400 hover:opacity-80">Delete</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-4 py-8 text-center text-sm text-gray-500 dark:text-gray-400">No subjects yet. Add one to populate the contact form dropdown.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</x-layouts.admin>
