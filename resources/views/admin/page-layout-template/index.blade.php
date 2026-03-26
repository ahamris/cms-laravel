<x-layouts.admin :title="__('Layout templates')">
    <div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-start sm:justify-between">
        <div>
            <h1 class="text-2xl font-bold text-zinc-900 dark:text-white">{{ __('Layout templates') }}</h1>
            <p class="mt-1 text-sm text-zinc-600 dark:text-zinc-400">
                {{ __('Define row order (content blocks and components), shell options, and assign elements per row on each page.') }}
            </p>
        </div>
        <x-button variant="primary" icon="plus" icon-position="left" href="{{ route('admin.page-layout-template.create') }}">
            {{ __('New template') }}
        </x-button>
    </div>

    <div class="overflow-hidden rounded-lg border border-zinc-200 bg-white dark:border-zinc-700 dark:bg-zinc-900">
        <table class="min-w-full divide-y divide-zinc-200 dark:divide-zinc-700">
            <thead class="bg-zinc-50 dark:bg-zinc-800/80">
                <tr>
                    <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-zinc-600 dark:text-zinc-400">{{ __('Name') }}</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-zinc-600 dark:text-zinc-400">{{ __('Description') }}</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-zinc-600 dark:text-zinc-400">{{ __('Shell') }}</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-zinc-600 dark:text-zinc-400">{{ __('Rows') }}</th>
                    <th class="px-4 py-3 text-right text-xs font-semibold uppercase tracking-wide text-zinc-600 dark:text-zinc-400">{{ __('Actions') }}</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-zinc-200 dark:divide-zinc-700">
                @forelse($templates as $t)
                    <tr>
                        <td class="px-4 py-3 text-sm font-medium text-zinc-900 dark:text-zinc-100">{{ $t->name }}</td>
                        <td class="max-w-xs px-4 py-3 text-sm text-zinc-600 dark:text-zinc-400">
                            @if(filled($t->description))
                                {{ \Illuminate\Support\Str::limit($t->description, 100) }}
                            @else
                                <span class="text-zinc-400 dark:text-zinc-500">—</span>
                            @endif
                        </td>
                        <td class="px-4 py-3 text-sm">
                            @if($t->use_header_section)
                                <span class="rounded bg-zinc-200 px-1.5 py-0.5 text-xs font-medium text-zinc-800 dark:bg-zinc-700 dark:text-zinc-200">{{ __('Header') }}</span>
                            @elseif($t->use_hero_section)
                                <span class="rounded bg-zinc-200 px-1.5 py-0.5 text-xs font-medium text-zinc-800 dark:bg-zinc-700 dark:text-zinc-200">{{ __('Hero') }}</span>
                            @else
                                <span class="text-zinc-400 dark:text-zinc-500">—</span>
                            @endif
                        </td>
                        <td class="px-4 py-3 text-sm text-zinc-600 dark:text-zinc-400">{{ $t->rows_count }}</td>
                        <td class="px-4 py-3 text-right text-sm">
                            <a href="{{ route('admin.page-layout-template.edit', $t) }}" class="text-primary hover:underline">{{ __('Edit') }}</a>
                            <form action="{{ route('admin.page-layout-template.destroy', $t) }}" method="POST" class="ms-3 inline"
                                onsubmit="return confirm(@json(__('Delete this template? Pages using it will lose the link.')));">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:underline dark:text-red-400">{{ __('Delete') }}</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-4 py-8 text-center text-sm text-zinc-500 dark:text-zinc-400">
                            {{ __('No templates yet. Create one to use the row builder on pages.') }}
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</x-layouts.admin>
