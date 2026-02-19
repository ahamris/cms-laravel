<x-layouts.admin title="Social Settings">
    {{-- Page Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-8">
        <div class="flex items-center gap-3">
            <div class="w-12 h-12 bg-[var(--color-accent)] rounded-md flex items-center justify-center">
                <i class="fa-solid fa-share-nodes text-white text-xl"></i>
            </div>
            <div class="flex flex-col gap-1">
                <h1 class="text-3xl font-bold text-zinc-900 dark:text-white">Social Settings</h1>
                <p class="text-zinc-600 dark:text-gray-400">Manage your social media links and settings</p>
            </div>
        </div>
        <div class="flex items-center gap-3" 
             x-data="{ 
                 showClearCacheSuccess: false,
                 clearCacheUrl: '{{ route('admin.social-settings.clear-cache') }}',
                 csrfToken: '{{ csrf_token() }}',
                 clearCache() {
                     fetch(this.clearCacheUrl, {
                         method: 'POST',
                         headers: {
                             'Content-Type': 'application/json',
                             'X-CSRF-TOKEN': this.csrfToken
                         }
                     })
                     .then(response => response.json())
                     .then(data => {
                         if (data.success) {
                             this.showClearCacheSuccess = true;
                             setTimeout(() => { this.showClearCacheSuccess = false; }, 3000);
                         }
                     })
                     .catch(error => console.error('Error:', error));
                 }
             }">
            <x-button variant="warning" @click="clearCache()" icon="sync" x-show="!showClearCacheSuccess">
                Clear Cache
            </x-button>
            <div x-show="showClearCacheSuccess" x-transition class="text-sm text-green-600 dark:text-green-400 flex items-center gap-2">
                <i class="fas fa-check-circle"></i>
                <span>Cache cleared!</span>
            </div>
            <x-button variant="primary" :href="route('admin.social-settings.create')" icon="plus">
                Add Social Setting
            </x-button>
        </div>
    </div>

    @if (session('success'))
        <div class="mb-6 rounded-lg bg-green-50 dark:bg-green-500/10 border border-green-200 dark:border-green-500/20 p-4">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <i class="fa-solid fa-check-circle text-green-500 text-lg"></i>
                </div>
                <div class="ml-3">
                    <p class="text-sm font-medium text-green-800 dark:text-green-300">
                        {{ session('success') }}
                    </p>
                </div>
            </div>
        </div>
    @endif

    {{-- Statistics Card --}}
    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-4 mb-8">
        <x-ui.card>
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs font-semibold text-[var(--color-accent)] uppercase mb-1">Total Platforms</p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $stats['total'] ?? 0 }}</p>
                </div>
                <div class="text-gray-300 dark:text-gray-600">
                    <i class="fas fa-share-nodes text-xl"></i>
                </div>
            </div>
        </x-ui.card>
    </div>

    {{-- Social Settings Table --}}
    <x-ui.card>
        @if($socialSettings->count() > 0)
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50 dark:bg-white/5 border-b border-gray-200 dark:border-white/10">
                        <tr>
                            <th class="text-left py-3 px-4 text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Name</th>
                            <th class="text-left py-3 px-4 text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">URL</th>
                            <th class="text-left py-3 px-4 text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Icon</th>
                            <th class="text-left py-3 px-4 text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Preview</th>
                            <th class="text-right py-3 px-4 text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-white/5 divide-y divide-gray-100 dark:divide-white/10">
                        @foreach($socialSettings as $setting)
                            <tr class="hover:bg-gray-50 dark:hover:bg-white/5 transition-colors duration-200">
                                <td class="py-3 px-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900 dark:text-white">{{ $setting->name }}</div>
                                </td>
                                <td class="py-3 px-4">
                                    <div class="text-sm text-gray-900 dark:text-white break-all">
                                        <a href="{{ $setting->url }}" target="_blank" class="text-[var(--color-accent)] hover:text-[var(--color-accent)]/80 inline-flex items-center gap-1">
                                            {{ Str::limit($setting->url, 50) }}
                                            <i class="fas fa-external-link-alt text-xs"></i>
                                        </a>
                                    </div>
                                </td>
                                <td class="py-3 px-4 whitespace-nowrap">
                                    <code class="px-2 py-1 rounded text-xs font-mono bg-gray-100 dark:bg-white/10 text-gray-800 dark:text-gray-300 border border-gray-200 dark:border-white/10">
                                        {{ $setting->icon }}
                                    </code>
                                </td>
                                <td class="py-3 px-4 whitespace-nowrap">
                                    <div class="flex items-center gap-2">
                                        <i class="{{ $setting->icon }} text-lg text-gray-700 dark:text-gray-300"></i>
                                        <span class="text-sm text-gray-700 dark:text-gray-300">{{ $setting->name }}</span>
                                    </div>
                                </td>
                                <td class="py-3 px-4 whitespace-nowrap text-right">
                                    <div class="flex items-center justify-end gap-3">
                                        <a href="{{ route('admin.social-settings.edit', $setting) }}"
                                           class="text-[var(--color-accent)] hover:text-[var(--color-accent)]/80 text-sm inline-flex items-center gap-1"
                                           title="Edit">
                                            <i class="fas fa-edit"></i>
                                            <span>Edit</span>
                                        </a>
                                        <div x-data="{ showDeleteModal{{ $setting->id }}: false }">
                                            <button type="button"
                                                    @click="showDeleteModal{{ $setting->id }} = true"
                                                    class="text-red-600 dark:text-red-400 hover:text-red-800 dark:hover:text-red-300 text-sm inline-flex items-center gap-1"
                                                    title="Delete">
                                                <i class="fas fa-trash"></i>
                                                <span>Delete</span>
                                            </button>

                                            {{-- Delete Confirmation Modal --}}
                                            <x-ui.modal 
                                                alpineShow="showDeleteModal{{ $setting->id }}"
                                                title="Delete Social Setting"
                                                modalId="delete-modal-{{ $setting->id }}"
                                            >
                                                <p class="text-sm text-gray-600 dark:text-gray-400">
                                                    Are you sure you want to delete <strong class="text-gray-900 dark:text-white">{{ $setting->name }}</strong>? This action cannot be undone.
                                                </p>
                                                <x-slot:footer>
                                                    <x-button variant="outline-secondary" @click="showDeleteModal{{ $setting->id }} = false">
                                                        Cancel
                                                    </x-button>
                                                    <form action="{{ route('admin.social-settings.destroy', $setting) }}" method="POST" class="inline">
                                                        @csrf
                                                        @method('DELETE')
                                                        <x-button variant="error" type="submit">
                                                            Delete
                                                        </x-button>
                                                    </form>
                                                </x-slot:footer>
                                            </x-ui.modal>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="text-center py-12">
                <i class="fas fa-share-alt text-4xl text-gray-300 dark:text-gray-600 mb-4"></i>
                <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-2">No Social Settings</h3>
                <p class="text-gray-500 dark:text-gray-400 mb-4">Get started by creating your first social media setting.</p>
                <x-button variant="primary" :href="route('admin.social-settings.create')" icon="plus">
                    Add Social Setting
                </x-button>
            </div>
        @endif
    </x-ui.card>
</x-layouts.admin>
