<x-layouts.admin title="Changelog Entry Details">
<div class="space-y-6">
    {{-- Header --}}
    <div class="flex items-center justify-between">
        <div class="flex items-center gap-3">
            <div class="w-12 h-12 bg-primary rounded-md flex items-center justify-center">
                <i class="fa-solid fa-info-circle text-white text-base"></i>
            </div>
            <div class="flex flex-col gap-1">
                <h2>Changelog Entry Details</h2>
                <p>View changelog entry information</p>
            </div>
        </div>
        <div class="flex items-center space-x-3">
            <a href="{{ route('admin.content.changelog.edit', $changelog) }}" 
               class="px-5 py-2 rounded-md bg-yellow-600 text-white text-sm hover:bg-yellow-700 transition-colors duration-200 flex items-center space-x-2">
                <i class="fa-solid fa-edit"></i>
                <span>Edit</span>
            </a>
            <a href="{{ route('admin.content.changelog.index') }}" 
               class="px-5 py-2 rounded-md bg-white border border-gray-200 text-gray-700 text-sm hover:bg-gray-50 transition-colors duration-200 flex items-center space-x-2">
                <i class="fa-solid fa-arrow-left"></i>
                <span>Back to List</span>
            </a>
        </div>
    </div>

    {{-- Changelog Details --}}
    <div class="bg-gray-50/50 rounded-md border border-gray-200">
        <div class="p-6 space-y-6">
            {{-- Basic Information --}}
            <div>
                <h3 class="text-base font-semibold text-gray-900 mb-4 flex items-center">
                    <i class="fa-solid fa-info-circle mr-2 text-blue-500"></i>
                    Basic Information
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-1">Title</label>
                        <p class="text-sm text-gray-900 font-medium">{{ $changelog->title }}</p>
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-1">Date</label>
                        <p class="text-sm text-gray-900">{{ $changelog->date ? $changelog->date->format('M d, Y') : '-' }}</p>
                    </div>
                </div>
                <div class="mt-4">
                    <label class="block text-xs font-medium text-gray-700 mb-1">Description</label>
                    <div class="bg-white rounded-md p-4 border border-gray-200">
                        <p class="text-xs text-gray-900 whitespace-pre-wrap">{{ $changelog->description }}</p>
                    </div>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mt-4">
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-1">Status</label>
                        @php
                            $statusColors = [
                                'new' => 'bg-blue-100 text-blue-800',
                                'improved' => 'bg-green-100 text-green-800',
                                'fixed' => 'bg-yellow-100 text-yellow-800',
                                'api' => 'bg-purple-100 text-purple-800',
                            ];
                            $statusLabels = [
                                'new' => 'New Feature',
                                'improved' => 'Improvement',
                                'fixed' => 'Bug Fix',
                                'api' => 'API Update',
                            ];
                        @endphp
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $statusColors[$changelog->status] ?? 'bg-gray-100 text-gray-800' }}">
                            {{ $statusLabels[$changelog->status] ?? ucfirst($changelog->status) }}
                        </span>
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-1">Active Status</label>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $changelog->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                            {{ $changelog->is_active ? 'Active' : 'Inactive' }}
                        </span>
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-1">Sort Order</label>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                            {{ $changelog->sort_order }}
                        </span>
                    </div>
                </div>
                <div class="mt-4">
                    <label class="block text-xs font-medium text-gray-700 mb-1">Slug</label>
                    <code class="bg-white text-gray-800 px-2 py-1 rounded text-xs border border-gray-200">{{ $changelog->slug }}</code>
                </div>
            </div>

            {{-- Content --}}
            @if($changelog->content)
                <div>
                    <h3 class="text-base font-semibold text-gray-900 mb-4 flex items-center">
                        <i class="fa-solid fa-file-alt mr-2 text-green-500"></i>
                        Detailed Content
                    </h3>
                    <div class="bg-white rounded-md p-4 border border-gray-200">
                        <div class="prose max-w-none text-xs">
                            {!! $changelog->content !!}
                        </div>
                    </div>
                </div>
            @endif

            {{-- Video URL --}}
            @if($changelog->video_url)
                <div>
                    <h3 class="text-base font-semibold text-gray-900 mb-4 flex items-center">
                        <i class="fa-solid fa-video mr-2 text-red-500"></i>
                        Video URL
                    </h3>
                    <div class="bg-white rounded-md p-4 border border-gray-200">
                        <a href="{{ $changelog->video_url }}" target="_blank" class="text-xs text-blue-600 hover:text-blue-800">
                            {{ $changelog->video_url }}
                        </a>
                    </div>
                </div>
            @endif

            {{-- Features --}}
            @if(!empty($changelog->features))
                <div>
                    <h3 class="text-base font-semibold text-gray-900 mb-4 flex items-center">
                        <i class="fa-solid fa-star mr-2 text-yellow-500"></i>
                        Features
                    </h3>
                    <div class="bg-white rounded-md p-4 border border-gray-200">
                        <ul class="space-y-2">
                            @foreach($changelog->features as $index => $feature)
                                <li class="flex items-start text-xs">
                                    <span class="inline-flex items-center justify-center w-5 h-5 rounded-full bg-yellow-100 text-yellow-800 font-medium mr-2 mt-0.5 flex-shrink-0">{{ $index + 1 }}</span>
                                    <span class="text-gray-900">{{ $feature }}</span>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            @endif

            {{-- Implementation Steps --}}
            @if(!empty($changelog->steps))
                <div>
                    <h3 class="text-base font-semibold text-gray-900 mb-4 flex items-center">
                        <i class="fa-solid fa-list-ol mr-2 text-green-500"></i>
                        Implementation Steps
                    </h3>
                    <div class="bg-white rounded-md p-4 border border-gray-200">
                        <ol class="space-y-2">
                            @foreach($changelog->steps as $index => $step)
                                <li class="flex items-start text-xs">
                                    <span class="inline-flex items-center justify-center w-5 h-5 rounded-full bg-green-100 text-green-800 font-medium mr-2 mt-0.5 flex-shrink-0">{{ $index + 1 }}</span>
                                    <span class="text-gray-900">{{ $step }}</span>
                                </li>
                            @endforeach
                        </ol>
                    </div>
                </div>
            @endif

            {{-- Timestamps --}}
            <div>
                <h3 class="text-base font-semibold text-gray-900 mb-4 flex items-center">
                    <i class="fa-solid fa-clock mr-2 text-gray-500"></i>
                    Timestamps
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-1">Created At</label>
                        <p class="text-xs text-gray-900">{{ $changelog->created_at->format('M d, Y H:i:s') }}</p>
                        <p class="text-xs text-gray-500">{{ $changelog->created_at->diffForHumans() }}</p>
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-1">Updated At</label>
                        <p class="text-xs text-gray-900">{{ $changelog->updated_at->format('M d, Y H:i:s') }}</p>
                        <p class="text-xs text-gray-500">{{ $changelog->updated_at->diffForHumans() }}</p>
                    </div>
                </div>
            </div>

            {{-- Frontend URL --}}
            <div>
                <h3 class="text-base font-semibold text-gray-900 mb-4 flex items-center">
                    <i class="fa-solid fa-link mr-2 text-purple-500"></i>
                    Frontend URL
                </h3>
                <div class="bg-white rounded-md p-4 border border-gray-200">
                    <a href="{{ url('/changelog/' . $changelog->slug) }}" 
                       target="_blank" 
                       class="text-xs text-blue-600 hover:text-blue-800 inline-flex items-center">
                        <i class="fa-solid fa-external-link-alt mr-2"></i>
                        {{ url('/changelog/' . $changelog->slug) }}
                    </a>
                </div>
            </div>
        </div>

        {{-- Actions --}}
        <div class="px-6 py-4 bg-gray-50/80 border-t border-gray-200 rounded-b-md flex items-center justify-end space-x-3">
            <a href="{{ route('admin.content.changelog.index') }}" 
               class="px-5 py-2 text-sm text-gray-700 bg-white border border-gray-200 rounded-md hover:bg-gray-50 transition-colors duration-200">
                Back to List
            </a>
            <a href="{{ route('admin.content.changelog.edit', $changelog) }}" 
               class="px-5 py-2 text-sm text-white bg-primary rounded-md hover:bg-primary/80 transition-colors duration-200">
                Edit Entry
            </a>
            <form action="{{ route('admin.content.changelog.destroy', $changelog) }}" 
                  method="POST" 
                  class="inline"
                  onsubmit="return confirm('Are you sure you want to delete this changelog entry?')">
                @csrf
                @method('DELETE')
                <button type="submit" 
                        class="px-5 py-2 text-sm text-white bg-red-600 rounded-md hover:bg-red-700 transition-colors duration-200 focus:outline-none">
                    Delete Entry
                </button>
            </form>
        </div>
    </div>
</div>
</x-layouts.admin>
