<x-layouts.admin title="Feature Details">
    {{-- Page Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-8">
        <div>
            <h1 class="text-3xl font-bold text-zinc-900 dark:text-white mb-2">{{ $feature->title }}</h1>
            <p class="text-zinc-600 dark:text-zinc-400">View the feature information</p>
        </div>
        <div class="flex flex-wrap items-center gap-3">
            <a href="{{ route('admin.feature.index') }}" class="inline-flex items-center gap-2 rounded-md bg-white dark:bg-white/10 px-4 py-2 text-sm font-semibold text-gray-900 dark:text-white shadow-xs ring-1 ring-gray-300 ring-inset dark:ring-white/10 hover:bg-gray-50 dark:hover:bg-white/20">
                <i class="fa-solid fa-arrow-left"></i>
                Back to Features
            </a>
            <a href="{{ route('admin.feature.edit', $feature) }}" class="inline-flex items-center gap-2 rounded-md bg-gray-600 px-4 py-2 text-sm font-semibold text-white shadow-xs hover:bg-gray-500">
                <i class="fa-solid fa-edit"></i>
                Edit
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        {{-- Left Column - 2/3 --}}
        <div class="lg:col-span-2 space-y-8">
            {{-- Basic Information Section --}}
            <div class="rounded-lg border border-gray-200 dark:border-white/10 bg-white dark:bg-white/5 p-6">
                <div class="mb-6">
                    <h2 class="text-base/7 font-semibold text-gray-900 dark:text-white">Basic Information</h2>
                    <p class="mt-1 text-sm/6 text-gray-600 dark:text-gray-400">Feature details and description.</p>
                </div>

                <div class="space-y-6">
                    {{-- Title --}}
                    <div>
                        <label class="block text-sm/6 font-medium text-gray-900 dark:text-white">Title</label>
                        <div class="mt-2">
                            <p class="text-lg font-medium text-gray-900 dark:text-white">{{ $feature->title }}</p>
                        </div>
                    </div>

                    {{-- Icon --}}
                    @if($feature->icon)
                    <div>
                        <label class="block text-sm/6 font-medium text-gray-900 dark:text-white">Icon</label>
                        <div class="mt-2 flex items-center gap-3">
                            <div class="w-10 h-10 rounded-lg bg-[var(--color-accent)]/10 flex items-center justify-center">
                                <i class="{{ $feature->icon }} text-[var(--color-accent)] text-lg"></i>
                            </div>
                            <code class="inline-flex items-center rounded-md bg-gray-100 dark:bg-white/10 px-3 py-1.5 text-sm font-mono text-gray-800 dark:text-gray-200">{{ $feature->icon }}</code>
                        </div>
                    </div>
                    @endif

                    {{-- Description --}}
                    @if($feature->description)
                    <div>
                        <label class="block text-sm/6 font-medium text-gray-900 dark:text-white">Description</label>
                        <div class="mt-2 rounded-lg bg-gray-50 dark:bg-white/5 p-4 border border-gray-200 dark:border-white/10">
                            <p class="text-sm text-gray-700 dark:text-gray-300 whitespace-pre-wrap">{{ $feature->description }}</p>
                        </div>
                    </div>
                    @endif
                </div>
            </div>

            {{-- Associated Modules Section --}}
            <div class="rounded-lg border border-gray-200 dark:border-white/10 bg-white dark:bg-white/5 p-6">
                <div class="mb-6">
                    <h2 class="text-base/7 font-semibold text-gray-900 dark:text-white">Associated Modules</h2>
                    <p class="mt-1 text-sm/6 text-gray-600 dark:text-gray-400">Modules that include this feature.</p>
                </div>

                <div>
                    @if($feature->modules->count() > 0)
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                            @foreach($feature->modules as $module)
                                <div class="flex items-start gap-3 p-3 rounded-lg border border-gray-200 dark:border-white/10 bg-gray-50 dark:bg-white/5 hover:bg-gray-100 dark:hover:bg-white/10 transition-colors">
                                    <div class="flex-shrink-0 w-8 h-8 rounded-lg bg-[var(--color-accent)]/10 flex items-center justify-center">
                                        <i class="fas fa-cube text-[var(--color-accent)] text-sm"></i>
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <div class="text-sm font-medium text-gray-900 dark:text-white">{{ $module->title }}</div>
                                        @if($module->short_body)
                                            <div class="text-xs text-gray-500 dark:text-gray-400 mt-1">{{ Str::limit($module->short_body, 60) }}</div>
                                        @endif
                                    </div>
                                    <a href="{{ route('admin.module.show', $module) }}" 
                                       class="flex-shrink-0 text-[var(--color-accent)] hover:text-[var(--color-accent)]/80 text-sm">
                                        <i class="fas fa-external-link-alt"></i>
                                    </a>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-8">
                            <div class="text-gray-400 dark:text-gray-500 mb-2">
                                <i class="fas fa-cube text-3xl"></i>
                            </div>
                            <p class="text-sm text-gray-500 dark:text-gray-400">This feature is not associated with any modules yet.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        {{-- Right Column - 1/3 --}}
        <div class="lg:col-span-1 space-y-8">
            {{-- Status & Settings Section --}}
            <div class="rounded-lg border border-gray-200 dark:border-white/10 bg-white dark:bg-white/5 p-6">
                <div class="mb-6">
                    <h2 class="text-base/7 font-semibold text-gray-900 dark:text-white">Status & Settings</h2>
                    <p class="mt-1 text-sm/6 text-gray-600 dark:text-gray-400">Feature status and configuration.</p>
                </div>

                <div class="space-y-6">
                    {{-- Status --}}
                    <div class="flex items-center justify-between">
                        <label class="block text-sm/6 font-medium text-gray-900 dark:text-white">Status</label>
                        @if($feature->is_active)
                            <span class="inline-flex items-center gap-x-1.5 rounded-full bg-green-100 dark:bg-green-500/10 px-3 py-1.5 text-sm font-medium text-green-700 dark:text-green-400">
                                <svg class="size-2 fill-green-500" viewBox="0 0 6 6"><circle cx="3" cy="3" r="3" /></svg>
                                Active
                            </span>
                        @else
                            <span class="inline-flex items-center gap-x-1.5 rounded-full bg-red-100 dark:bg-red-500/10 px-3 py-1.5 text-sm font-medium text-red-700 dark:text-red-400">
                                <svg class="size-2 fill-red-500" viewBox="0 0 6 6"><circle cx="3" cy="3" r="3" /></svg>
                                Inactive
                            </span>
                        @endif
                    </div>

                    {{-- Sort Order --}}
                    <div>
                        <label class="block text-sm/6 font-medium text-gray-900 dark:text-white">Sort Order</label>
                        <div class="mt-2">
                            <p class="text-sm text-gray-700 dark:text-gray-300">{{ $feature->sort_order ?? 0 }}</p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Timestamps Section --}}
            <div class="rounded-lg border border-gray-200 dark:border-white/10 bg-white dark:bg-white/5 p-6">
                <div class="mb-6">
                    <h2 class="text-base/7 font-semibold text-gray-900 dark:text-white">Timestamps</h2>
                    <p class="mt-1 text-sm/6 text-gray-600 dark:text-gray-400">Creation and modification dates.</p>
                </div>

                <div class="space-y-6">
                    {{-- Created At --}}
                    <div>
                        <label class="block text-sm/6 font-medium text-gray-900 dark:text-white">Created At</label>
                        <div class="mt-2">
                            <p class="text-sm text-gray-700 dark:text-gray-300">{{ $feature->created_at->format('M d, Y H:i:s') }}</p>
                            <p class="text-xs text-gray-500 dark:text-gray-400">{{ $feature->created_at->diffForHumans() }}</p>
                        </div>
                    </div>

                    {{-- Updated At --}}
                    <div>
                        <label class="block text-sm/6 font-medium text-gray-900 dark:text-white">Updated At</label>
                        <div class="mt-2">
                            <p class="text-sm text-gray-700 dark:text-gray-300">{{ $feature->updated_at->format('M d, Y H:i:s') }}</p>
                            <p class="text-xs text-gray-500 dark:text-gray-400">{{ $feature->updated_at->diffForHumans() }}</p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Quick Actions Section --}}
            <div class="rounded-lg border border-gray-200 dark:border-white/10 bg-white dark:bg-white/5 p-6">
                <div class="mb-6">
                    <h2 class="text-base/7 font-semibold text-gray-900 dark:text-white">Quick Actions</h2>
                    <p class="mt-1 text-sm/6 text-gray-600 dark:text-gray-400">Common actions for this feature.</p>
                </div>

                <div class="space-y-3">
                    <a href="{{ route('admin.feature.edit', $feature) }}"
                       class="w-full inline-flex items-center justify-center gap-2 rounded-md bg-gray-600 px-4 py-2 text-sm font-semibold text-white shadow-xs hover:bg-gray-500">
                        <i class="fa-solid fa-edit"></i>
                        Edit Feature
                    </a>

                    <form action="{{ route('admin.feature.destroy', $feature) }}" 
                          method="POST" 
                          x-data="{ confirmDelete() { return confirm('Are you sure you want to delete this feature? This action cannot be undone.'); } }"
                          @submit="if(!confirmDelete()) { $event.preventDefault(); }">
                        @csrf
                        @method('DELETE')
                        <button type="submit"
                                class="w-full inline-flex items-center justify-center gap-2 rounded-md bg-red-600 px-4 py-2 text-sm font-semibold text-white shadow-xs hover:bg-red-500">
                            <i class="fa-solid fa-trash"></i>
                            Delete Feature
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

</x-layouts.admin>
