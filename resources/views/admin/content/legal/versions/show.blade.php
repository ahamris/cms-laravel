<x-layouts.admin title="Version {{ $version->version_label }} - {{ $legal->title }}">
<div class="space-y-6">
    {{-- Header --}}
    <div class="flex items-center justify-between">
        <div class="flex items-center gap-3">
            <div class="w-12 h-12 bg-primary rounded-md flex items-center justify-center">
                <i class="fa-solid fa-clock-rotate-left text-white text-base"></i>
            </div>
            <div class="flex flex-col gap-1">
                <h2>Version {{ $version->version_label }}</h2>
                <p>{{ $legal->title }} - {{ $version->created_at->format('M d, Y \a\t g:i A') }}</p>
            </div>
        </div>
        <div class="flex items-center space-x-3">
            @if($version->version_number != $legal->current_version)
                <form action="{{ route('admin.content.legal.version.restore', [$legal, $version->version_number]) }}"
                      method="POST" class="inline"
                      onsubmit="return confirm('Are you sure you want to restore version {{ $version->version_number }}? This will create a new version with the current state before restoring.')">
                    @csrf
                    <button type="submit"
                            class="px-5 py-2 rounded-md bg-green-600 text-white text-sm hover:bg-green-700 transition-colors duration-200 flex items-center space-x-2">
                        <i class="fa-solid fa-rotate-left"></i>
                        <span>Restore This Version</span>
                    </button>
                </form>
            @else
                <span class="px-5 py-2 rounded-md bg-blue-100 text-blue-800 text-sm flex items-center space-x-2">
                    <i class="fa-solid fa-check-circle"></i>
                    <span>Current Version</span>
                </span>
            @endif
            <a href="{{ route('admin.content.legal.versions', $legal) }}"
               class="px-5 py-2 rounded-md bg-white border border-gray-200 text-gray-700 text-sm hover:bg-gray-50 transition-colors duration-200 flex items-center space-x-2">
                <i class="fa-solid fa-arrow-left"></i>
                <span>Back to Versions</span>
            </a>
        </div>
    </div>

    {{-- Version Info Banner --}}
    <div class="bg-blue-50 border border-blue-200 rounded-md p-4">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-sm">
            <div>
                <span class="text-blue-700 font-medium">Version Number:</span>
                <span class="text-blue-900 ml-2">{{ $version->version_label }}</span>
            </div>
            <div>
                <span class="text-blue-700 font-medium">Created By:</span>
                <span class="text-blue-900 ml-2">{{ $version->creator ? $version->creator->name : 'System' }}</span>
            </div>
            <div>
                <span class="text-blue-700 font-medium">Created At:</span>
                <span class="text-blue-900 ml-2">{{ $version->created_at->format('M d, Y \a\t g:i A') }}</span>
            </div>
            @if($version->version_notes)
            <div class="md:col-span-3">
                <span class="text-blue-700 font-medium">Notes:</span>
                <span class="text-blue-900 ml-2">{{ $version->version_notes }}</span>
            </div>
            @endif
        </div>
    </div>

    {{-- Page Details --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        {{-- Main Content --}}
        <div class="lg:col-span-2 space-y-6">
            {{-- Content Card --}}
            <div class="bg-gray-50/50 rounded-md border border-gray-200 p-6">
                <h3 class="text-base font-semibold text-gray-900 mb-4 flex items-center">
                    <i class="fa-solid fa-file-alt mr-2 text-blue-500"></i>
                    Content
                </h3>

                @if($version->image)
                    <div class="mb-6">
                        <img src="{{ Storage::disk('public')->url($version->image) }}"
                             alt="{{ $version->title }}"
                             class="w-full max-w-md h-64 object-cover rounded-md border border-gray-200">
                    </div>
                @endif

                <div class="bg-white rounded-md p-4 border border-gray-200 prose max-w-none">
                    {!! html_entity_decode($version->body) !!}
                </div>
            </div>

        </div>

        {{-- Sidebar --}}
        <div class="space-y-6">
            {{-- Version Info Card --}}
            <div class="bg-gray-50/50 rounded-md border border-gray-200 p-6">
                <h3 class="text-base font-semibold text-gray-900 mb-4 flex items-center">
                    <i class="fa-solid fa-info-circle mr-2 text-blue-500"></i>
                    Version Information
                </h3>

                <div class="space-y-4">
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-1">Title</label>
                        <p class="text-sm text-gray-900">{{ $version->title }}</p>
                    </div>

                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-1">Slug</label>
                        <p class="text-sm text-gray-900 font-mono">{{ $version->slug }}</p>
                    </div>

                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-1">Status</label>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $version->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                            {{ $version->is_active ? 'Active' : 'Inactive' }}
                        </span>
                    </div>

                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-1">Created At</label>
                        <p class="text-xs text-gray-900">{{ $version->created_at->format('M d, Y \a\t g:i A') }}</p>
                    </div>

                    @if($version->version_notes)
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-1">Version Notes</label>
                        <p class="text-xs text-gray-900">{{ $version->version_notes }}</p>
                    </div>
                    @endif
                </div>
            </div>

            {{-- SEO Card --}}
            @if($version->meta_title || $version->meta_description)
            <div class="bg-gray-50/50 rounded-md border border-gray-200 p-6">
                <h3 class="text-base font-semibold text-gray-900 mb-4 flex items-center">
                    <i class="fa-solid fa-search mr-2 text-green-500"></i>
                    SEO Information
                </h3>

                <div class="space-y-4">
                    @if($version->meta_title)
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-1">Meta Title</label>
                        <p class="text-sm text-gray-900">{{ $version->meta_title }}</p>
                    </div>
                    @endif

                    @if($version->meta_description)
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-1">Meta Description</label>
                        <p class="text-sm text-gray-900">{{ $version->meta_description }}</p>
                    </div>
                    @endif

                    @if($version->keywords)
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-1">SEO Keywords</label>
                        <p class="text-sm text-gray-900">{{ $version->keywords }}</p>
                    </div>
                    @endif
                </div>
            </div>
            @endif

            {{-- Actions Card --}}
            <div class="bg-gray-50/50 rounded-md border border-gray-200 p-6">
                <h3 class="text-base font-semibold text-gray-900 mb-4 flex items-center">
                    <i class="fa-solid fa-bolt mr-2 text-yellow-500"></i>
                    Actions
                </h3>

                <div class="space-y-2">
                    @if($version->version_number != $legal->current_version)
                        <form action="{{ route('admin.content.legal.version.restore', [$legal, $version->version_number]) }}"
                              method="POST"
                              onsubmit="return confirm('Are you sure you want to restore version {{ $version->version_number }}? This will create a new version with the current state before restoring.')">
                            @csrf
                            <button type="submit"
                                    class="w-full inline-flex items-center justify-center px-4 py-2 text-sm bg-green-600 text-white rounded-md hover:bg-green-700 transition-colors duration-200">
                                <i class="fa-solid fa-rotate-left mr-2"></i>
                                Restore This Version
                            </button>
                        </form>
                    @endif

                    <a href="{{ route('admin.content.legal.versions', $legal) }}"
                       class="w-full inline-flex items-center justify-center px-4 py-2 text-sm bg-white border border-gray-200 text-gray-700 rounded-md hover:bg-gray-50 transition-colors duration-200">
                        <i class="fa-solid fa-arrow-left mr-2"></i>
                        Back to Versions
                    </a>

                    <a href="{{ route('admin.content.legal.edit', $legal) }}"
                       class="w-full inline-flex items-center justify-center px-4 py-2 text-sm bg-primary text-white rounded-md hover:bg-primary/80 transition-colors duration-200">
                        <i class="fa-solid fa-edit mr-2"></i>
                        Edit Current Page
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
</x-layouts.admin>

