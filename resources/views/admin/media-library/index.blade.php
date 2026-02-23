<x-layouts.admin title="Media Library">
    <div class="space-y-6" x-data="mediaLibrary()">
        {{-- Page Header --}}
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div class="flex items-center gap-3">
                <div class="w-12 h-12 bg-[var(--color-accent)] rounded-md flex items-center justify-center">
                    <i class="fa-solid fa-folder-open text-white text-xl"></i>
                </div>
                <div class="flex flex-col gap-1">
                    <h1 class="text-3xl font-bold text-zinc-900 dark:text-white">Media Library</h1>
                    <p class="text-zinc-600 dark:text-zinc-400">Browse and manage files in storage</p>
                </div>
            </div>
        </div>

        @if (session('success'))
            <div class="rounded-lg bg-green-50 dark:bg-green-500/10 border border-green-200 dark:border-green-500/20 p-4 flex items-center gap-3">
                <i class="fa-solid fa-check-circle text-green-500 text-lg flex-shrink-0"></i>
                <p class="text-sm font-medium text-green-800 dark:text-green-300">{{ session('success') }}</p>
            </div>
        @endif
        @if (session('error'))
            <div class="rounded-lg bg-red-50 dark:bg-red-500/10 border border-red-200 dark:border-red-500/20 p-4 flex items-center gap-3">
                <i class="fa-solid fa-exclamation-circle text-red-500 text-lg flex-shrink-0"></i>
                <p class="text-sm font-medium text-red-800 dark:text-red-300">{{ session('error') }}</p>
            </div>
        @endif
        @if (session('info'))
            <div class="rounded-lg bg-blue-50 dark:bg-blue-500/10 border border-blue-200 dark:border-blue-500/20 p-4 flex items-center gap-3">
                <i class="fa-solid fa-info-circle text-blue-500 text-lg flex-shrink-0"></i>
                <p class="text-sm font-medium text-blue-800 dark:text-blue-300">{{ session('info') }}</p>
            </div>
        @endif

        {{-- Breadcrumbs --}}
        <nav class="flex items-center gap-2 text-sm text-zinc-600 dark:text-zinc-400 flex-wrap">
            @foreach ($breadcrumbs as $crumb)
                @if (!$loop->last)
                    <a href="{{ route('admin.media-library.index', $crumb['path_param'] ? ['path' => $crumb['path_param']] : []) }}"
                       class="hover:text-[var(--color-accent)] transition-colors">
                        {{ $crumb['name'] }}
                    </a>
                    <i class="fa-solid fa-chevron-right text-zinc-400 dark:text-zinc-500"></i>
                @else
                    <span class="text-zinc-900 dark:text-white font-medium">{{ $crumb['name'] }}</span>
                @endif
            @endforeach
        </nav>

        {{-- Content --}}
        <div class="bg-white dark:bg-zinc-800 rounded-lg border border-zinc-200 dark:border-zinc-700 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-zinc-200 dark:divide-zinc-700">
                    <thead class="bg-zinc-50 dark:bg-zinc-700/50">
                        <tr>
                            <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-zinc-500 dark:text-zinc-400 uppercase tracking-wider">Name</th>
                            <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-zinc-500 dark:text-zinc-400 uppercase tracking-wider">Size</th>
                            <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-zinc-500 dark:text-zinc-400 uppercase tracking-wider">Modified</th>
                            <th scope="col" class="px-4 py-3 text-right text-xs font-medium text-zinc-500 dark:text-zinc-400 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-zinc-200 dark:divide-zinc-700">
                        @foreach ($folders as $folder)
                            <tr class="hover:bg-zinc-50 dark:hover:bg-zinc-700/30 transition-colors">
                                <td class="px-4 py-3">
                                    <a href="{{ route('admin.media-library.index', ['path' => $folder['path_param']]) }}"
                                       class="flex items-center gap-2 text-[var(--color-accent)] hover:underline font-medium">
                                        <i class="fa-solid fa-folder text-amber-500 dark:text-amber-400"></i>
                                        {{ $folder['name'] }}
                                    </a>
                                </td>
                                <td class="px-4 py-3 text-zinc-500 dark:text-zinc-400">—</td>
                                <td class="px-4 py-3 text-zinc-500 dark:text-zinc-400">{{ date('M j, Y H:i', $folder['modified']) }}</td>
                                <td class="px-4 py-3 text-right">
                                    <form action="{{ route('admin.media-library.destroy') }}" method="POST" class="inline"
                                          onsubmit="return confirm('Delete this folder and all its contents?');">
                                        @csrf
                                        @method('DELETE')
                                        <input type="hidden" name="path" value="{{ $folder['path_param'] }}">
                                        <button type="submit" class="text-red-600 dark:text-red-400 hover:underline text-sm"
                                                title="Delete folder">
                                            <i class="fa-solid fa-trash"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                        @foreach ($files as $file)
                            @php
                                $isImage = in_array($file['extension'], ['jpg', 'jpeg', 'png', 'gif', 'webp', 'svg'], true);
                            @endphp
                            <tr class="hover:bg-zinc-50 dark:hover:bg-zinc-700/30 transition-colors">
                                <td class="px-4 py-3">
                                    @if ($isImage)
                                        <button type="button"
                                                @click="openPreview('{{ route('admin.media-library.preview', ['path' => $file['path_param']]) }}', '{{ addslashes($file['name']) }}', true)"
                                                class="flex items-center gap-2 text-left font-medium text-zinc-900 dark:text-white hover:text-[var(--color-accent)] transition-colors">
                                            <i class="fa-solid fa-image text-emerald-500 dark:text-emerald-400"></i>
                                            {{ $file['name'] }}
                                        </button>
                                    @else
                                        <span class="flex items-center gap-2 font-medium text-zinc-900 dark:text-white">
                                            <i class="fa-solid {{ in_array($file['extension'], ['pdf'], true) ? 'fa-file-pdf text-red-500' : (in_array($file['extension'], ['doc', 'docx'], true) ? 'fa-file-word text-blue-500' : 'fa-file text-zinc-400') }}"></i>
                                            {{ $file['name'] }}
                                        </span>
                                    @endif
                                </td>
                                <td class="px-4 py-3 text-zinc-500 dark:text-zinc-400">
                                    {{ $file['size'] >= 1024 ? ($file['size'] >= 1048576 ? round($file['size'] / 1048576, 2) . ' MB' : round($file['size'] / 1024, 2) . ' KB') : $file['size'] . ' B' }}
                                </td>
                                <td class="px-4 py-3 text-zinc-500 dark:text-zinc-400">{{ date('M j, Y H:i', $file['modified']) }}</td>
                                <td class="px-4 py-3 text-right">
                                    <div class="flex items-center justify-end gap-2">
                                        @if ($isImage && $file['extension'] !== 'svg')
                                            <button type="button"
                                                    @click="openResize('{{ $file['path_param'] }}', '{{ addslashes($file['name']) }}')"
                                                    class="text-zinc-600 dark:text-zinc-400 hover:text-[var(--color-accent)] transition-colors"
                                                    title="Resize">
                                                <i class="fa-solid fa-expand"></i>
                                            </button>
                                        @endif
                                        <a href="{{ route('admin.media-library.preview', ['path' => $file['path_param']]) }}"
                                           target="_blank"
                                           class="text-zinc-600 dark:text-zinc-400 hover:text-[var(--color-accent)] transition-colors"
                                           title="Preview">
                                            <i class="fa-solid fa-eye"></i>
                                        </a>
                                        <a href="{{ route('admin.media-library.download', ['path' => $file['path_param']]) }}"
                                           class="text-zinc-600 dark:text-zinc-400 hover:text-[var(--color-accent)] transition-colors"
                                           title="Download">
                                            <i class="fa-solid fa-download"></i>
                                        </a>
                                        <form action="{{ route('admin.media-library.destroy') }}" method="POST" class="inline"
                                              onsubmit="return confirm('Delete this file?');">
                                            @csrf
                                            @method('DELETE')
                                            <input type="hidden" name="path" value="{{ $file['path_param'] }}">
                                            <button type="submit" class="text-red-600 dark:text-red-400 hover:underline"
                                                    title="Delete">
                                                <i class="fa-solid fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @if (empty($folders) && empty($files))
                <div class="px-6 py-12 text-center text-zinc-500 dark:text-zinc-400">
                    <i class="fa-solid fa-folder-open text-4xl mb-3 opacity-50"></i>
                    <p>This folder is empty.</p>
                </div>
            @endif
        </div>

        {{-- Preview modal --}}
        <div x-show="previewOpen"
             x-cloak
             class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/70"
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition ease-in duration-150"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             @keydown.escape.window="previewOpen = false">
            <div class="relative max-w-4xl max-h-[90vh] w-full flex flex-col bg-white dark:bg-zinc-800 rounded-lg shadow-xl overflow-hidden">
                <div class="flex items-center justify-between px-4 py-2 border-b border-zinc-200 dark:border-zinc-700">
                    <span class="font-medium text-zinc-900 dark:text-white truncate" x-text="previewName"></span>
                    <button type="button" @click="previewOpen = false" class="p-2 rounded-lg hover:bg-zinc-100 dark:hover:bg-zinc-700 text-zinc-600 dark:text-zinc-400">
                        <i class="fa-solid fa-times"></i>
                    </button>
                </div>
                <div class="flex-1 overflow-auto p-4 flex items-center justify-center min-h-0">
                    <template x-if="previewIsImage">
                        <img :src="previewUrl" alt="Preview" class="max-w-full max-h-full object-contain">
                    </template>
                    <template x-if="!previewIsImage">
                        <iframe :src="previewUrl" class="w-full h-96 border-0 rounded"></iframe>
                    </template>
                </div>
            </div>
        </div>

        {{-- Resize modal --}}
        <div x-show="resizeOpen"
             x-cloak
             class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/70"
             x-transition
             @keydown.escape.window="resizeOpen = false">
            <div class="bg-white dark:bg-zinc-800 rounded-lg shadow-xl w-full max-w-md p-6">
                <h3 class="font-semibold text-zinc-900 dark:text-white mb-2">Resize image</h3>
                <p class="text-sm text-zinc-500 dark:text-zinc-400 mb-4" x-text="resizeName"></p>
                <form :action="resizeFormAction" method="POST">
                    @csrf
                    <input type="hidden" name="path" :value="resizePath">
                    <div class="grid grid-cols-2 gap-4 mb-4">
                        <div>
                            <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-1">Max width (px)</label>
                            <input type="number" name="width" min="1" placeholder="e.g. 800" class="w-full rounded-lg border border-zinc-300 dark:border-zinc-600 bg-white dark:bg-zinc-700 text-zinc-900 dark:text-white px-3 py-2">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-1">Max height (px)</label>
                            <input type="number" name="height" min="1" placeholder="e.g. 600" class="w-full rounded-lg border border-zinc-300 dark:border-zinc-600 bg-white dark:bg-zinc-700 text-zinc-900 dark:text-white px-3 py-2">
                        </div>
                    </div>
                    <p class="text-xs text-zinc-500 dark:text-zinc-400 mb-4">Image will be scaled to fit within these dimensions (aspect ratio preserved). At least one value required.</p>
                    <div class="flex justify-end gap-2">
                        <button type="button" @click="resizeOpen = false" class="px-4 py-2 rounded-lg border border-zinc-300 dark:border-zinc-600 text-zinc-700 dark:text-zinc-300 hover:bg-zinc-50 dark:hover:bg-zinc-700">
                            Cancel
                        </button>
                        <button type="submit" class="px-4 py-2 rounded-lg bg-[var(--color-accent)] text-white hover:opacity-90">
                            Resize
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function mediaLibrary() {
            return {
                previewOpen: false,
                previewUrl: '',
                previewName: '',
                previewIsImage: false,
                resizeOpen: false,
                resizePath: '',
                resizeName: '',
                get resizeFormAction() {
                    return '{{ route("admin.media-library.resize") }}';
                },
                openPreview(url, name, isImage) {
                    this.previewUrl = url;
                    this.previewName = name;
                    this.previewIsImage = !!isImage;
                    this.previewOpen = true;
                },
                openResize(pathParam, name) {
                    this.resizePath = pathParam;
                    this.resizeName = name;
                    this.resizeOpen = true;
                }
            };
        }
    </script>
</x-layouts.admin>
