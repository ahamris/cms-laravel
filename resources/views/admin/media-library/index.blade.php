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

        {{-- Toolbar: search / filter / sort --}}
        <div class="flex items-center justify-between gap-3 flex-wrap">
            <form method="GET" action="{{ route('admin.media-library.index') }}" class="flex items-center gap-3 flex-wrap">
                <input type="hidden" name="path" value="{{ $currentPathParam }}">

                {{-- Search --}}
                <div class="relative">
                    <i class="fa-solid fa-magnifying-glass absolute left-3 top-1/2 -translate-y-1/2 text-zinc-400 dark:text-zinc-500 text-[13px]"></i>
                    <input
                        type="text"
                        name="q"
                        value="{{ old('q', $query ?? '') }}"
                        placeholder="Search media..."
                        class="h-9 w-[260px] rounded-md border border-zinc-200 dark:border-zinc-700 bg-white dark:bg-zinc-800 pl-9 pr-3 text-sm text-zinc-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-[var(--color-accent)]"
                    />
                </div>

                {{-- Type filter --}}
                <select
                    name="type"
                    class="h-9 rounded-md border border-zinc-200 dark:border-zinc-700 bg-white dark:bg-zinc-800 px-3 text-sm text-zinc-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-[var(--color-accent)]"
                >
                    <option value="all" {{ ($typeFilter ?? 'all') === 'all' ? 'selected' : '' }}>All</option>
                    <option value="images" {{ ($typeFilter ?? 'all') === 'images' ? 'selected' : '' }}>Images</option>
                    <option value="documents" {{ ($typeFilter ?? 'all') === 'documents' ? 'selected' : '' }}>Documents</option>
                    <option value="svg" {{ ($typeFilter ?? 'all') === 'svg' ? 'selected' : '' }}>SVG</option>
                </select>

                {{-- Sort --}}
                <select
                    name="sort"
                    class="h-9 rounded-md border border-zinc-200 dark:border-zinc-700 bg-white dark:bg-zinc-800 px-3 text-sm text-zinc-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-[var(--color-accent)]"
                >
                    <option value="modified_desc" {{ ($sort ?? 'modified_desc') === 'modified_desc' ? 'selected' : '' }}>Date (newest)</option>
                    <option value="modified_asc" {{ ($sort ?? 'modified_desc') === 'modified_asc' ? 'selected' : '' }}>Date (oldest)</option>
                    <option value="name_asc" {{ ($sort ?? 'modified_desc') === 'name_asc' ? 'selected' : '' }}>Name (A-Z)</option>
                    <option value="name_desc" {{ ($sort ?? 'modified_desc') === 'name_desc' ? 'selected' : '' }}>Name (Z-A)</option>
                </select>

                <button type="submit" class="h-9 inline-flex items-center gap-2 rounded-md bg-white border border-zinc-200 dark:border-zinc-700 px-3 text-sm font-semibold text-zinc-900 dark:text-white hover:bg-zinc-50 dark:hover:bg-zinc-700 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-[var(--color-accent)]">
                    <i class="fa-solid fa-filter"></i>
                    Apply
                </button>
            </form>

            <div class="flex items-center gap-3">
                <div class="text-sm text-zinc-600 dark:text-zinc-400">
                    {{ count($folders) + count($files) }} items
                </div>
            </div>
        </div>

        {{-- Moneybird-like drop zone --}}
        <div
            class="rounded-lg border border-zinc-200 dark:border-zinc-700 bg-white dark:bg-zinc-800 p-4"
        >
            <div
                class="border-[1.5px] border-dashed rounded-md p-8 text-center cursor-pointer transition-colors"
                :class="dragActive
                    ? 'border-[var(--color-accent)] bg-[var(--color-accent)]/5 ring-2 ring-[var(--color-accent)] ring-offset-1 ring-offset-white dark:ring-offset-zinc-900'
                    : 'border-zinc-300 dark:border-zinc-600 hover:border-[var(--color-accent)] hover:bg-[var(--color-accent)]/5'"
                @click="triggerUpload()"
                @dragover.prevent="dragActive = true"
                @dragleave.prevent="dragActive = false"
                @drop.prevent="handleDrop($event)"
            >
                <div class="mx-auto mb-2 w-10 h-10 rounded-md bg-zinc-100 dark:bg-zinc-700 flex items-center justify-center text-zinc-500 dark:text-zinc-300">
                    <i class="fa-solid fa-cloud-arrow-up text-xl"></i>
                </div>
                <p class="text-sm font-semibold text-zinc-900 dark:text-white">Drop files here or click to upload</p>
                <p class="mt-1 text-xs text-zinc-500 dark:text-zinc-400">Images, PDFs, docs · Max 50MB per file</p>

                <input
                    x-ref="uploadInput"
                    type="file"
                    multiple
                    class="hidden"
                    @change="handleFiles($event.target.files)"
                />
            </div>

            <div class="mt-3 flex items-center justify-between flex-wrap gap-2">
                <div class="text-xs text-zinc-500 dark:text-zinc-400">
                    Upload destination: <span class="font-medium text-zinc-700 dark:text-zinc-200">{{ $currentRelativePath !== '' ? $currentRelativePath : 'uploads' }}</span>
                </div>
                <a
                    href="{{ route('admin.image-optimizer.index') }}"
                    class="text-xs font-semibold text-[var(--color-accent)] hover:opacity-80"
                >
                    Open image optimizer
                </a>
            </div>
        </div>

        {{-- Folder grid (no selection checkboxes) --}}
        @if (!empty($folders))
            <div class="grid grid-cols-3 sm:grid-cols-4 md:grid-cols-5 lg:grid-cols-6 xl:grid-cols-8 gap-4">
                @foreach ($folders as $folder)
                    <a href="{{ route('admin.media-library.index', ['path' => $folder['path_param']]) }}"
                       class="group flex flex-col rounded-lg border border-zinc-200 dark:border-zinc-700 bg-white dark:bg-zinc-800 overflow-hidden hover:border-[var(--color-accent)] hover:shadow-md transition-all">
                        <div class="aspect-square flex items-center justify-center bg-zinc-100 dark:bg-zinc-700/50 text-amber-500 dark:text-amber-400">
                            <i class="fa-solid fa-folder text-5xl sm:text-6xl"></i>
                        </div>
                        <div class="p-2 flex items-center justify-between gap-1 min-w-0">
                            <span class="truncate text-sm font-medium text-zinc-900 dark:text-white" title="{{ $folder['name'] }}">{{ $folder['name'] }}</span>
                            <form action="{{ route('admin.media-library.destroy') }}" method="POST" class="flex-shrink-0" onsubmit="return confirm('Delete this folder and all its contents?');" onclick="event.stopPropagation()">
                                @csrf
                                @method('DELETE')
                                <input type="hidden" name="path" value="{{ $folder['path_param'] }}">
                                <button type="submit" class="p-1 rounded text-zinc-400 hover:text-red-500 hover:bg-red-50 dark:hover:bg-red-500/10" title="Delete folder">
                                    <i class="fa-solid fa-trash text-xs"></i>
                                </button>
                            </form>
                        </div>
                    </a>
                @endforeach
            </div>
        @endif

        {{-- Bulk actions + file manager grid --}}
        <form id="bulk-delete-form" method="POST" action="{{ route('admin.media-library.bulk-destroy') }}">
            @csrf

            <div class="flex items-center justify-between gap-3 mb-3 flex-wrap">
                <div class="flex items-center gap-2 text-sm text-zinc-600 dark:text-zinc-400">
                    <span class="inline-flex items-center gap-2">
                        <i class="fa-solid fa-square-check text-[var(--color-accent)]"></i>
                        <span x-text="selectedPaths.length + ' selected'"></span>
                    </span>
                </div>

                <button type="submit"
                        class="inline-flex items-center gap-2 rounded-md bg-danger-bg border-danger-border px-4 py-2 text-sm font-semibold text-danger-text hover:opacity-90 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-danger-border"
                        x-show="selectedPaths.length > 0"
                        x-cloak
                        @click="return confirm('Delete selected item(s)?');">
                    <i class="fa-solid fa-trash"></i>
                    Delete selected
                </button>
            </div>

            @if (empty($files) && !empty($folders))
                <div class="rounded-lg border border-zinc-200 dark:border-zinc-700 bg-white dark:bg-zinc-800 px-6 py-16 text-center text-zinc-500 dark:text-zinc-400">
                    <i class="fa-solid fa-image text-5xl mb-4 opacity-50"></i>
                    <p>No media files found.</p>
                </div>
            @else
                <div class="grid grid-cols-3 sm:grid-cols-4 md:grid-cols-5 lg:grid-cols-6 xl:grid-cols-8 gap-4">
                    @foreach ($files as $file)
                    @php
                        $isImage = in_array($file['extension'], ['jpg', 'jpeg', 'png', 'gif', 'webp', 'svg'], true);
                        $previewUrl = $isImage ? route('admin.media-library.preview', ['path' => $file['path_param']]) : null;
                        $sizeFormatted = $file['size'] >= 1024 ? ($file['size'] >= 1048576 ? round($file['size'] / 1048576, 2) . ' MB' : round($file['size'] / 1024, 2) . ' KB') : $file['size'] . ' B';
                        $fileIcon = match($file['extension'] ?? '') {
                            'pdf' => 'fa-file-pdf text-red-500',
                            'doc', 'docx' => 'fa-file-word text-blue-500',
                            'xls', 'xlsx' => 'fa-file-excel text-green-600',
                            'zip', 'rar' => 'fa-file-zipper text-amber-500',
                            default => 'fa-file text-zinc-400',
                        };
                    @endphp
                    <div class="group relative flex flex-col rounded-lg border border-zinc-200 dark:border-zinc-700 bg-white dark:bg-zinc-800 overflow-hidden hover:border-[var(--color-accent)] hover:shadow-md transition-all">

                        <input type="checkbox"
                               name="paths[]"
                               value="{{ $file['path_param'] }}"
                               x-model="selectedPaths"
                               class="absolute top-2 left-2 z-10 h-4 w-4 rounded border-zinc-300 text-[var(--color-accent)] focus:ring-[var(--color-accent)] dark:border-zinc-600 bg-white dark:bg-zinc-800"
                               @click.stop
                               aria-label="Select {{ $file['name'] }}" />

                        @if ($isImage)
                            <button type="button"
                                    @click="openDetails(@js($file))"
                                    class="aspect-square w-full flex items-center justify-center bg-zinc-100 dark:bg-zinc-700/50 overflow-hidden focus:outline-none focus:ring-2 focus:ring-[var(--color-accent)] focus:ring-inset">
                                <img src="{{ $previewUrl }}" alt="" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-200" loading="lazy">
                            </button>
                        @else
                            <button type="button"
                               @click="openDetails(@js($file))"
                               class="aspect-square w-full flex items-center justify-center bg-zinc-100 dark:bg-zinc-700/50 text-zinc-500 dark:text-zinc-400">
                                <i class="fa-solid {{ $fileIcon }} text-4xl sm:text-5xl"></i>
                            </button>
                        @endif

                        <div class="p-2 min-w-0">
                            <p class="truncate text-sm font-medium text-zinc-900 dark:text-white" title="{{ $file['name'] }}">{{ $file['name'] }}</p>
                            <p class="text-xs text-zinc-500 dark:text-zinc-400">{{ $sizeFormatted }}</p>
                            <div class="mt-1.5 flex items-center gap-1 flex-wrap">
                                @if ($isImage)
                                    <button type="button"
                                            @click="openPreview('{{ $previewUrl }}', '{{ addslashes($file['name']) }}', true)"
                                            class="p-1.5 rounded text-zinc-400 hover:text-[var(--color-accent)] hover:bg-zinc-100 dark:hover:bg-zinc-700"
                                            title="Preview">
                                        <i class="fa-solid fa-eye text-xs"></i>
                                    </button>
                                    @if ($file['extension'] !== 'svg')
                                        <button type="button"
                                                @click="openResize('{{ $file['path_param'] }}', '{{ addslashes($file['name']) }}')"
                                                class="p-1.5 rounded text-zinc-400 hover:text-[var(--color-accent)] hover:bg-zinc-100 dark:hover:bg-zinc-700"
                                                title="Resize">
                                            <i class="fa-solid fa-expand text-xs"></i>
                                        </button>
                                    @endif
                                @endif
                                <a href="{{ route('admin.media-library.preview', ['path' => $file['path_param']]) }}"
                                   target="_blank"
                                   class="p-1.5 rounded text-zinc-400 hover:text-[var(--color-accent)] hover:bg-zinc-100 dark:hover:bg-zinc-700"
                                   title="Open">
                                    <i class="fa-solid fa-external-link text-xs"></i>
                                </a>
                                <a href="{{ route('admin.media-library.download', ['path' => $file['path_param']]) }}"
                                   class="p-1.5 rounded text-zinc-400 hover:text-[var(--color-accent)] hover:bg-zinc-100 dark:hover:bg-zinc-700"
                                   title="Download">
                                    <i class="fa-solid fa-download text-xs"></i>
                                </a>
                                <button type="button"
                                        class="p-1.5 rounded text-zinc-400 hover:text-[var(--color-accent)] hover:bg-zinc-100 dark:hover:bg-zinc-700"
                                        title="Details"
                                        @click="openDetails(@js($file))">
                                    <i class="fa-solid fa-circle-info text-xs"></i>
                                </button>
                                <button type="button"
                                        class="p-1.5 rounded text-zinc-400 hover:text-red-500 hover:bg-red-50 dark:hover:bg-red-500/10"
                                        title="Delete"
                                        @click="deleteSingle('{{ $file['path_param'] }}')">
                                    <i class="fa-solid fa-trash text-xs"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            @endif
        </form>

        {{-- Details drawer (metadata editor + generator) --}}
        <x-ui.drawer drawer-id="media-details-drawer" max-width="2xl">
            <div class="relative flex h-full flex-col overflow-y-auto bg-white shadow-xl dark:bg-gray-800 dark:after:absolute dark:after:inset-y-0 dark:after:left-0 dark:after:w-px dark:after:bg-white/10">
                <div class="px-4 py-6 sm:px-6 border-b border-zinc-200 dark:border-zinc-700">
                    <div class="flex items-start justify-between gap-3">
                        <div class="min-w-0">
                            <h2 class="text-base font-semibold text-zinc-900 dark:text-white truncate" x-text="details?.name || 'Media details'"></h2>
                            <p class="text-xs text-zinc-500 dark:text-zinc-400 mt-1 truncate" x-text="details?.public_url || ''"></p>
                        </div>
                        <div class="flex h-7 items-center">
                            <button type="button" command="close" commandfor="media-details-drawer"
                                class="relative rounded-md text-zinc-400 hover:text-zinc-600 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-[var(--color-accent)] dark:hover:text-white">
                                <span class="absolute -inset-2.5"></span>
                                <span class="sr-only">Close panel</span>
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" aria-hidden="true" class="size-6">
                                    <path d="M6 18 18 6M6 6l12 12" stroke-linecap="round" stroke-linejoin="round" />
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>

                <div class="relative flex-1 px-4 py-6 sm:px-6 space-y-5">
                    <template x-if="details">
                        <div class="grid grid-cols-[1fr_280px] gap-5">
                            <div class="rounded-lg border border-zinc-200 dark:border-zinc-700 bg-zinc-50 dark:bg-zinc-900/40 p-3 flex items-center justify-center min-h-[280px] overflow-hidden">
                                <template x-if="details.extension && ['jpg','jpeg','png','gif','webp','svg'].includes(details.extension)">
                                    <img :src="details.public_url" alt="" class="max-w-full max-h-[520px] object-contain rounded-md" />
                                </template>
                                <template x-if="!(details.extension && ['jpg','jpeg','png','gif','webp','svg'].includes(details.extension))">
                                    <div class="text-center text-zinc-500 dark:text-zinc-400">
                                        <i class="fa-solid fa-file text-4xl mb-2"></i>
                                        <div class="text-sm font-medium">No preview</div>
                                    </div>
                                </template>
                            </div>

                            <div class="space-y-4">
                                <div class="rounded-lg border border-zinc-200 dark:border-zinc-700 bg-white dark:bg-zinc-800 p-4">
                                    <div class="text-xs font-semibold text-zinc-700 dark:text-zinc-200 mb-3">Metadata</div>

                                    <template x-if="!details.media">
                                        <div class="space-y-3">
                                            <p class="text-xs text-zinc-500 dark:text-zinc-400">
                                                This file has no metadata record yet. Create one to edit title and alt text.
                                            </p>
                                            <button type="button"
                                                class="w-full inline-flex items-center justify-center gap-2 rounded-md bg-[var(--color-accent)] px-3 py-2 text-sm font-semibold text-white hover:opacity-90"
                                                @click="syncDetailsRecord()"
                                                :disabled="saving">
                                                <i class="fa-solid fa-wand-magic-sparkles"></i>
                                                Create metadata record
                                            </button>
                                        </div>
                                    </template>

                                    <template x-if="details.media">
                                        <div class="space-y-3">
                                            <div>
                                                <label class="block text-xs font-medium text-zinc-700 dark:text-zinc-300 mb-1">Title</label>
                                                <div class="flex gap-2">
                                                    <input type="text" x-model="meta.title"
                                                        class="flex-1 h-9 rounded-md border border-zinc-200 dark:border-zinc-700 bg-white dark:bg-zinc-800 px-3 text-sm text-zinc-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-[var(--color-accent)]" />
                                                    <button type="button"
                                                        class="h-9 px-3 rounded-md border border-zinc-200 dark:border-zinc-700 text-sm font-semibold text-zinc-700 dark:text-zinc-200 hover:bg-zinc-50 dark:hover:bg-zinc-700"
                                                        @click="generateMeta()"
                                                        title="Generate from filename">
                                                        Generate
                                                    </button>
                                                </div>
                                            </div>

                                            <div>
                                                <label class="block text-xs font-medium text-zinc-700 dark:text-zinc-300 mb-1">Alt text</label>
                                                <input type="text" x-model="meta.alt_text"
                                                    class="w-full h-9 rounded-md border border-zinc-200 dark:border-zinc-700 bg-white dark:bg-zinc-800 px-3 text-sm text-zinc-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-[var(--color-accent)]" />
                                            </div>

                                            <div class="flex items-center justify-between gap-2 pt-2">
                                                <button type="button"
                                                    class="inline-flex items-center gap-2 rounded-md border border-zinc-200 dark:border-zinc-700 px-3 py-2 text-sm font-semibold text-zinc-700 dark:text-zinc-200 hover:bg-zinc-50 dark:hover:bg-zinc-700"
                                                    @click="copyToClipboard(details.public_url)">
                                                    <i class="fa-solid fa-link"></i>
                                                    Copy URL
                                                </button>
                                                <button type="button"
                                                    class="inline-flex items-center gap-2 rounded-md bg-[var(--color-accent)] px-3 py-2 text-sm font-semibold text-white hover:opacity-90"
                                                    @click="saveMeta()"
                                                    :disabled="saving">
                                                    <i class="fa-solid fa-save"></i>
                                                    Save
                                                </button>
                                            </div>
                                        </div>
                                    </template>
                                </div>

                                <div class="rounded-lg border border-zinc-200 dark:border-zinc-700 bg-white dark:bg-zinc-800 p-4">
                                    <div class="text-xs font-semibold text-zinc-700 dark:text-zinc-200 mb-3">File info</div>
                                    <div class="text-xs text-zinc-600 dark:text-zinc-400 space-y-1">
                                        <div><span class="text-zinc-500 dark:text-zinc-500">Type:</span> <span x-text="details.extension || '-'"></span></div>
                                        <div><span class="text-zinc-500 dark:text-zinc-500">Size:</span> <span x-text="details.size || '-'"></span></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </template>
                </div>
            </div>
        </x-ui.drawer>

        @if (empty($folders) && empty($files))
            <div class="rounded-lg border border-zinc-200 dark:border-zinc-700 bg-white dark:bg-zinc-800 px-6 py-16 text-center text-zinc-500 dark:text-zinc-400">
                <i class="fa-solid fa-folder-open text-5xl mb-4 opacity-50"></i>
                <p>This folder is empty.</p>
            </div>
        @endif

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
                dragActive: false,
                saving: false,
                details: null,
                meta: { title: '', alt_text: '' },
                previewOpen: false,
                previewUrl: '',
                previewName: '',
                previewIsImage: false,
                resizeOpen: false,
                resizePath: '',
                resizeName: '',
                selectedPaths: [],
                csrfToken: @json(csrf_token()),
                uploadUrl: @json(route('admin.media-library.upload')),
                syncUrl: @json(route('admin.media-library.sync')),
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
                },
                triggerUpload() {
                    this.$refs.uploadInput?.click();
                },
                handleDrop(e) {
                    this.dragActive = false;
                    const files = e.dataTransfer?.files;
                    if (!files || files.length === 0) return;
                    this.handleFiles(files);
                },
                async handleFiles(fileList) {
                    const files = Array.from(fileList || []);
                    if (files.length === 0) return;

                    const form = new FormData();
                    files.forEach(f => form.append('files[]', f));
                    form.append('folder', @json($currentRelativePath !== '' ? $currentRelativePath : 'uploads'));
                    form.append('redirect_path', @json($currentPathParam));

                    try {
                        this.saving = true;
                        const res = await fetch(this.uploadUrl, {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': this.csrfToken,
                                'Accept': 'application/json',
                            },
                            body: form,
                        });
                        if (!res.ok) {
                            const err = await res.json().catch(() => ({}));
                            throw new Error(err.message || 'Upload failed');
                        }
                        const json = await res.json().catch(() => ({}));
                        const count = (json.data || []).length;
                        window.toastSuccess?.(`Uploaded ${count} file(s).`);
                        setTimeout(() => window.location.reload(), 300);
                    } catch (e) {
                        window.toastError?.(e?.message || 'Upload failed');
                    } finally {
                        this.saving = false;
                    }
                },
                openDetails(file) {
                    this.details = file || null;
                    this.meta = {
                        title: (file?.media?.title ?? ''),
                        alt_text: (file?.media?.alt_text ?? ''),
                    };

                    const dialog = document.getElementById('media-details-drawer');
                    dialog?.showModal?.();
                },
                generateMeta() {
                    if (!this.details?.name) return;
                    const name = String(this.details.name);
                    const base = name.replace(/\.[^.]+$/, '').replace(/[_-]+/g, ' ').trim();
                    const titled = base.replace(/\b\w/g, (m) => m.toUpperCase());

                    if (!this.meta.title) this.meta.title = titled;
                    if (!this.meta.alt_text) this.meta.alt_text = base;
                },
                async syncDetailsRecord() {
                    if (!this.details?.path_param) return;
                    try {
                        this.saving = true;
                        const res = await fetch(this.syncUrl, {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': this.csrfToken,
                                'Accept': 'application/json',
                                'Content-Type': 'application/json',
                            },
                            body: JSON.stringify({ path: this.details.path_param }),
                        });
                        if (!res.ok) {
                            const err = await res.json().catch(() => ({}));
                            throw new Error(err.message || 'Sync failed');
                        }
                        const json = await res.json();
                        const m = json.data;
                        this.details.media = {
                            id: m.id,
                            title: m.title || '',
                            alt_text: m.alt_text || '',
                            mime_type: m.mime_type || '',
                            size: m.size || 0,
                            width: m.width,
                            height: m.height,
                            url: m.url || this.details.public_url || '',
                        };
                        this.meta = { title: this.details.media.title, alt_text: this.details.media.alt_text };
                        this.generateMeta();
                        window.toastSuccess?.('Metadata record created.');
                    } catch (e) {
                        window.toastError?.(e?.message || 'Sync failed');
                    } finally {
                        this.saving = false;
                    }
                },
                async saveMeta() {
                    const id = this.details?.media?.id;
                    if (!id) {
                        await this.syncDetailsRecord();
                        return;
                    }
                    try {
                        this.saving = true;
                        const res = await fetch(`{{ route('admin.media-library.update-media', ['id' => '__ID__']) }}`.replace('__ID__', String(id)), {
                            method: 'PUT',
                            headers: {
                                'X-CSRF-TOKEN': this.csrfToken,
                                'Accept': 'application/json',
                                'Content-Type': 'application/json',
                            },
                            body: JSON.stringify({
                                title: this.meta.title || null,
                                alt_text: this.meta.alt_text || null,
                            }),
                        });
                        if (!res.ok) {
                            const err = await res.json().catch(() => ({}));
                            throw new Error(err.message || 'Save failed');
                        }
                        const json = await res.json();
                        const m = json.data;
                        this.details.media.title = m.title || '';
                        this.details.media.alt_text = m.alt_text || '';
                        window.toastSuccess?.('Metadata saved.');
                    } catch (e) {
                        window.toastError?.(e?.message || 'Save failed');
                    } finally {
                        this.saving = false;
                    }
                },
                async copyToClipboard(text) {
                    try {
                        await navigator.clipboard.writeText(text || '');
                        window.toastSuccess?.('Copied to clipboard.');
                    } catch {
                        window.toastError?.('Could not copy to clipboard.');
                    }
                },
                deleteSingle(pathParam) {
                    if (! confirm('Delete this file?')) {
                        return;
                    }

                    this.selectedPaths = [pathParam];

                    // Submit the bulk delete form immediately.
                    setTimeout(() => {
                        const form = document.getElementById('bulk-delete-form');
                        if (! form) return;
                        form.submit();
                    }, 50);
                }
            };
        }
    </script>
</x-layouts.admin>
