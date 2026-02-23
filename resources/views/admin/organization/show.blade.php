<x-layouts.admin title="Organization Details">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-8">
        <div>
            <h1 class="text-3xl font-bold text-zinc-900 dark:text-white mb-2">{{ $organization->name }}</h1>
            <p class="text-zinc-600 dark:text-zinc-400">Organization details</p>
        </div>
        <div class="flex flex-wrap items-center gap-3">
            <a href="{{ route('admin.organization.index') }}"
                class="inline-flex items-center gap-2 rounded-md bg-white dark:bg-white/10 px-4 py-2 text-sm font-semibold text-gray-900 dark:text-white shadow-xs ring-1 ring-gray-300 ring-inset dark:ring-white/10 hover:bg-gray-50 dark:hover:bg-white/20">
                <i class="fa-solid fa-arrow-left"></i>
                Back to Organizations
            </a>
            <a href="{{ route('admin.organization.edit', $organization) }}"
                class="inline-flex items-center gap-2 rounded-md bg-[var(--color-accent)] px-4 py-2 text-sm font-semibold text-white shadow-xs hover:opacity-90 transition-opacity">
                <i class="fa-solid fa-edit"></i>
                Edit
            </a>
        </div>
    </div>

    <div class="max-w-6xl w-full space-y-8">
        <div class="rounded-lg border border-gray-200 dark:border-white/10 bg-white dark:bg-white/5 p-8 shadow-sm">
            <h2 class="text-base/7 font-semibold text-gray-900 dark:text-white mb-6">Details</h2>
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-10">
                <div class="lg:col-span-2 space-y-6">
                    <dl class="divide-y divide-gray-200 dark:divide-white/10 border-t border-b border-gray-200 dark:border-white/10">
                        <div class="flex justify-between py-4 text-sm font-medium">
                            <dt class="text-gray-500 dark:text-gray-400">Name</dt>
                            <dd class="text-gray-900 dark:text-white">{{ $organization->name }}</dd>
                        </div>
                        <div class="flex justify-between py-4 text-sm font-medium">
                            <dt class="text-gray-500 dark:text-gray-400">Created</dt>
                            <dd class="text-gray-900 dark:text-white">{{ $organization->created_at?->format('M j, Y') }}</dd>
                        </div>
                        <div class="flex justify-between py-4 text-sm font-medium">
                            <dt class="text-gray-500 dark:text-gray-400">Updated</dt>
                            <dd class="text-gray-900 dark:text-white">{{ $organization->updated_at?->format('M j, Y') }}</dd>
                        </div>
                    </dl>
                </div>
                <div class="lg:col-span-1">
                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-3">Logo</dt>
                    @if($organization->logo_url)
                        <div class="rounded-lg border border-gray-200 dark:border-white/10 bg-gray-50 dark:bg-white/5 p-6 flex items-center justify-center min-h-[200px]">
                            <img src="{{ $organization->logo_url }}" alt="{{ $organization->name }} logo" class="max-h-48 w-auto object-contain" />
                        </div>
                    @else
                        <div class="rounded-lg border border-dashed border-gray-300 dark:border-white/10 bg-gray-50 dark:bg-white/5 p-8 flex items-center justify-center min-h-[200px]">
                            <span class="text-zinc-500 dark:text-zinc-400">No logo</span>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-layouts.admin>
