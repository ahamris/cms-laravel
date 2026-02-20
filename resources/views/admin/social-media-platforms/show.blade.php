<x-layouts.admin title="{{ $socialMediaPlatform->name }}">
    <div class="space-y-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-zinc-900 dark:text-white mb-2">{{ $socialMediaPlatform->name }}</h1>
                <p class="text-zinc-600 dark:text-zinc-400">Social media platform details</p>
            </div>
            <div class="flex gap-3">
                <a href="{{ route('admin.settings.social-media-platforms.edit', $socialMediaPlatform) }}">
                    <x-button variant="primary" icon="edit">Edit</x-button>
                </a>
                <a href="{{ route('admin.settings.social-media-platforms.index') }}">
                    <x-button variant="secondary">Back to list</x-button>
                </a>
            </div>
        </div>

        <div class="rounded-lg border border-gray-200 dark:border-white/10 bg-white dark:bg-white/5 p-6 max-w-2xl">
            <dl class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                <div>
                    <dt class="text-sm font-medium text-zinc-500 dark:text-zinc-400">Name</dt>
                    <dd class="mt-1 text-sm text-zinc-900 dark:text-white">{{ $socialMediaPlatform->name }}</dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-zinc-500 dark:text-zinc-400">Slug</dt>
                    <dd class="mt-1 text-sm text-zinc-900 dark:text-white">{{ $socialMediaPlatform->slug }}</dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-zinc-500 dark:text-zinc-400">Icon</dt>
                    <dd class="mt-1 text-sm text-zinc-900 dark:text-white">
                        @if($socialMediaPlatform->icon)
                            <i class="{{ $socialMediaPlatform->icon }}"></i> {{ $socialMediaPlatform->icon }}
                        @else
                            —
                        @endif
                    </dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-zinc-500 dark:text-zinc-400">Color</dt>
                    <dd class="mt-1 flex items-center gap-2">
                        <span class="inline-block w-6 h-6 rounded border border-gray-300 dark:border-white/20" style="background-color: {{ $socialMediaPlatform->color }}"></span>
                        {{ $socialMediaPlatform->color }}
                    </dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-zinc-500 dark:text-zinc-400">Status</dt>
                    <dd class="mt-1">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $socialMediaPlatform->is_active ? 'bg-green-100 text-green-800 dark:bg-green-500/20 dark:text-green-300' : 'bg-red-100 text-red-800 dark:bg-red-500/20 dark:text-red-300' }}">
                            {{ $socialMediaPlatform->is_active ? 'Active' : 'Inactive' }}
                        </span>
                    </dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-zinc-500 dark:text-zinc-400">Sort order</dt>
                    <dd class="mt-1 text-sm text-zinc-900 dark:text-white">{{ $socialMediaPlatform->sort_order }}</dd>
                </div>
            </dl>
        </div>
    </div>
</x-layouts.admin>
