<x-layouts.admin title="View Legal Page">
<div class="space-y-6">
    {{-- Header --}}
    <div class="flex items-center justify-between">
        <div class="flex items-center gap-3">
            <div class="w-12 h-12 bg-primary rounded-md flex items-center justify-center">
                <i class="fa-solid fa-info-circle text-white text-base"></i>
            </div>
            <div class="flex flex-col gap-1">
                <h2>{{ $legal->title }}</h2>
                <p>Legal page details and content</p>
            </div>
        </div>
        <div class="flex items-center space-x-3">
            <a href="{{ route('admin.content.legal.edit', $legal) }}"
               class="px-5 py-2 rounded-md bg-primary text-white text-sm hover:bg-primary/80 transition-colors duration-200 flex items-center space-x-2">
                <i class="fa-solid fa-edit"></i>
                <span>Edit Page</span>
            </a>
            <a href="{{ route('admin.content.legal.index') }}"
               class="px-5 py-2 rounded-md bg-white border border-gray-200 text-gray-700 text-sm hover:bg-gray-50 transition-colors duration-200 flex items-center space-x-2">
                <i class="fa-solid fa-arrow-left"></i>
                <span>Back to Legal Pages</span>
            </a>
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

                @if($legal->image)
                    <div class="mb-6">
                        <img src="{{ Storage::url($legal->image) }}"
                             alt="{{ $legal->title }}"
                             class="w-full max-w-md h-64 object-cover rounded-md border border-gray-200">
                    </div>
                @endif

                <div class="bg-white rounded-md p-4 border border-gray-200 prose max-w-none">
                    {!! html_entity_decode($legal->body) !!}
                </div>
            </div>

        </div>

        {{-- Sidebar --}}
        <div class="space-y-6">
            {{-- Page Info Card --}}
            <div class="bg-gray-50/50 rounded-md border border-gray-200 p-6">
                <h3 class="text-base font-semibold text-gray-900 mb-4 flex items-center">
                    <i class="fa-solid fa-info-circle mr-2 text-blue-500"></i>
                    Page Information
                </h3>

                <div class="space-y-4">
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-1">Title</label>
                        <p class="text-sm text-gray-900">{{ $legal->title }}</p>
                    </div>

                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-1">Slug</label>
                        <p class="text-sm text-gray-900 font-mono">{{ $legal->slug }}</p>
                    </div>

                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-1">Status</label>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $legal->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                            {{ $legal->is_active ? 'Active' : 'Inactive' }}
                        </span>
                    </div>

                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-1">Created</label>
                        <p class="text-xs text-gray-900">{{ $legal->created_at->format('M d, Y \a\t g:i A') }}</p>
                    </div>

                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-1">Last Updated</label>
                        <p class="text-xs text-gray-900">{{ $legal->updated_at->format('M d, Y \a\t g:i A') }}</p>
                    </div>
                </div>
            </div>

            {{-- SEO Card --}}
            @if($legal->meta_title || $legal->meta_description)
            <div class="bg-gray-50/50 rounded-md border border-gray-200 p-6">
                <h3 class="text-base font-semibold text-gray-900 mb-4 flex items-center">
                    <i class="fa-solid fa-search mr-2 text-green-500"></i>
                    SEO Information
                </h3>

                <div class="space-y-4">
                    @if($legal->meta_title)
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-1">Meta Title</label>
                        <p class="text-sm text-gray-900">{{ $legal->meta_title }}</p>
                    </div>
                    @endif

                    @if($legal->meta_description)
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-1">Meta Description</label>
                        <p class="text-sm text-gray-900">{{ $legal->meta_description }}</p>
                    </div>
                    @endif

                    @if($legal->keywords)
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-1">SEO Keywords</label>
                        <p class="text-sm text-gray-900">{{ $legal->keywords }}</p>
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
                    <a href="{{ route('admin.content.legal.edit', $legal) }}"
                       class="w-full inline-flex items-center justify-center px-4 py-2 text-sm bg-primary text-white rounded-md hover:bg-primary/80 transition-colors duration-200">
                        <i class="fa-solid fa-edit mr-2"></i>
                        Edit Page
                    </a>

                    <form action="{{ route('admin.content.legal.toggle-active', $legal) }}" method="POST" class="w-full">
                        @csrf
                        <button type="submit"
                                class="w-full inline-flex items-center justify-center px-4 py-2 text-sm rounded-md focus:outline-none {{ $legal->is_active ? 'bg-yellow-100 text-yellow-800 hover:bg-yellow-200' : 'bg-green-100 text-green-800 hover:bg-green-200' }} transition-colors duration-200">
                            <i class="fa-solid fa-{{ $legal->is_active ? 'pause' : 'play' }} mr-2"></i>
                            {{ $legal->is_active ? 'Deactivate' : 'Activate' }} Page
                        </button>
                    </form>

                    <form action="{{ route('admin.content.legal.destroy', $legal) }}"
                          method="POST"
                          class="w-full"
                          onsubmit="return confirm('Are you sure you want to delete this legal page? This action cannot be undone.')">
                        @csrf
                        @method('DELETE')
                        <button type="submit"
                                class="w-full inline-flex items-center justify-center px-4 py-2 text-sm bg-red-100 text-red-800 rounded-md hover:bg-red-200 transition-colors duration-200 focus:outline-none">
                            <i class="fa-solid fa-trash mr-2"></i>
                            Delete Page
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
</script>
</x-layouts.admin>
