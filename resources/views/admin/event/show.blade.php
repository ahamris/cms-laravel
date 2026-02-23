<x-layouts.admin title="Event Details">
<div class="space-y-6">
    {{-- Header --}}
    <div class="flex items-center justify-between mb-6">
        <div class="flex items-center gap-3">
            <div class="w-12 h-12 bg-primary rounded-md flex items-center justify-center">
                <i class="fa-solid fa-info-circle text-white text-base"></i>
            </div>
            <div class="flex flex-col gap-1">
                <h2>Event Details</h2>
                <p>View event information</p>
            </div>
        </div>
        <div class="flex items-center space-x-3">
            <a href="{{ route('admin.event.edit', $event) }}" 
               class="px-5 py-2 rounded-md bg-white border border-gray-200 text-gray-700 text-sm hover:bg-gray-50 transition-colors duration-200 flex items-center space-x-2">
                <i class="fa fa-edit"></i>
                <span>Edit</span>
            </a>
            <a href="{{ route('admin.event.index') }}" 
               class="px-5 py-2 rounded-md bg-white border border-gray-200 text-gray-700 text-sm hover:bg-gray-50 transition-colors duration-200 flex items-center space-x-2">
                <i class="fa fa-arrow-left"></i>
                <span>Back to Events</span>
            </a>
        </div>
    </div>

    {{-- Event Details --}}
    <div class="bg-gray-50/50 rounded-md border border-gray-200">
        <div class="p-6 space-y-6">
            {{-- Images --}}
            @if($event->cover_image || $event->image)
                <div class="bg-gray-50/50 rounded-md border border-gray-200 p-6">
                    <h3 class="text-base font-semibold text-gray-900 mb-4 flex items-center">
                        <i class="fa-solid fa-image mr-2 text-blue-500"></i>
                        Event Images
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        @if($event->cover_image)
                            <div>
                                <label class="block text-xs font-medium text-gray-700 mb-1">Cover Image</label>
                                <img src="{{ Storage::url($event->cover_image) }}" 
                                     alt="{{ $event->title }}" 
                                     class="w-full max-w-md h-64 object-cover rounded-md">
                            </div>
                        @endif
                        
                        @if($event->image)
                            <div>
                                <label class="block text-xs font-medium text-gray-700 mb-1">Additional Image</label>
                                <img src="{{ Storage::url($event->image) }}" 
                                     alt="{{ $event->title }}" 
                                     class="w-full max-w-md h-64 object-cover rounded-md">
                            </div>
                        @endif
                    </div>
                </div>
            @endif

            {{-- Basic Information --}}
            <div class="bg-gray-50/50 rounded-md border border-gray-200 p-6">
                <h3 class="text-base font-semibold text-gray-900 mb-4 flex items-center">
                    <i class="fa-solid fa-info-circle mr-2 text-blue-500"></i>
                    Basic Information
                </h3>
                <div class="space-y-4">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-medium text-gray-700 mb-1">Title</label>
                            <p class="text-sm text-gray-900 font-medium">{{ $event->title }}</p>
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-700 mb-1">Slug</label>
                            <code class="bg-white text-gray-800 px-2 py-1 rounded text-xs border border-gray-200">{{ $event->slug }}</code>
                        </div>
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-1">Event Organizer</label>
                        <div class="text-sm">
                            <div class="font-medium text-gray-900">{{ $event->user->name ?? 'Unknown' }}</div>
                            <div class="text-gray-500 mt-1">{{ $event->user->email ?? '' }}</div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Content --}}
            <div class="bg-gray-50/50 rounded-md border border-gray-200 p-6">
                <h3 class="text-base font-semibold text-gray-900 mb-4 flex items-center">
                    <i class="fa-solid fa-file-alt mr-2 text-blue-500"></i>
                    Content
                </h3>
                <div class="space-y-4">
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-1">Short Description</label>
                        <div class="bg-white rounded-md p-4 border border-gray-200">
                            <p class="text-sm text-gray-700 whitespace-pre-wrap">{{ $event->short_body }}</p>
                        </div>
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-1">Content</label>
                        <div class="bg-white rounded-md p-4 border border-gray-200">
                            <div class="prose max-w-none">
                                {!! $event->long_body !!}
                            </div>
                        </div>
                    </div>
                    @if($event->description)
                        <div>
                            <label class="block text-xs font-medium text-gray-700 mb-1">Additional Description</label>
                            <div class="bg-white rounded-md p-4 border border-gray-200">
                                <p class="text-sm text-gray-700 whitespace-pre-wrap">{{ $event->description }}</p>
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            {{-- Date and Time --}}
            <div class="bg-gray-50/50 rounded-md border border-gray-200 p-6">
                <h3 class="text-base font-semibold text-gray-900 mb-4 flex items-center">
                    <i class="fa-solid fa-clock mr-2 text-blue-500"></i>
                    Date and Time
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-1">Start Date</label>
                        <p class="text-sm text-gray-900">{{ $event->start_date->format('M d, Y') }}</p>
                        @if($event->start_time)
                            <p class="text-xs text-gray-500 mt-1">{{ $event->start_time->format('H:i') }}</p>
                        @endif
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-1">End Date</label>
                        <p class="text-sm text-gray-900">{{ $event->end_date->format('M d, Y') }}</p>
                        @if($event->end_time)
                            <p class="text-xs text-gray-500 mt-1">{{ $event->end_time->format('H:i') }}</p>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Location --}}
            <div class="bg-gray-50/50 rounded-md border border-gray-200 p-6">
                <h3 class="text-base font-semibold text-gray-900 mb-4 flex items-center">
                    <i class="fa-solid fa-map-marker-alt mr-2 text-blue-500"></i>
                    Location
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-1">Location</label>
                        <p class="text-sm text-gray-900">{{ $event->location }}</p>
                    </div>
                    @if($event->address)
                        <div>
                            <label class="block text-xs font-medium text-gray-700 mb-1">Address</label>
                            <p class="text-sm text-gray-700">{{ $event->address }}</p>
                        </div>
                    @endif
                </div>
            </div>

            {{-- Price and Registration --}}
            <div class="bg-gray-50/50 rounded-md border border-gray-200 p-6">
                <h3 class="text-base font-semibold text-gray-900 mb-4 flex items-center">
                    <i class="fa-solid fa-dollar-sign mr-2 text-blue-500"></i>
                    Price and Registration
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-1">Price</label>
                        @if($event->price)
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                ${{ number_format($event->price, 2) }}
                            </span>
                        @else
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                Free Event
                            </span>
                        @endif
                    </div>
                    @if($event->registration_url)
                        <div>
                            <label class="block text-xs font-medium text-gray-700 mb-1">Registration URL</label>
                            <a href="{{ $event->registration_url }}" 
                               target="_blank" 
                               class="text-sm text-primary hover:text-primary/80 underline">
                                {{ $event->registration_url }}
                            </a>
                        </div>
                    @endif
                </div>
            </div>

            {{-- Status and Timestamps --}}
            <div class="bg-gray-50/50 rounded-md border border-gray-200 p-6">
                <h3 class="text-base font-semibold text-gray-900 mb-4 flex items-center">
                    <i class="fa-solid fa-chart-bar mr-2 text-blue-500"></i>
                    Status and Timestamps
                </h3>
                <div class="space-y-4">
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-1">Status</label>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $event->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                            <i class="fa-solid {{ $event->is_active ? 'fa-check-circle' : 'fa-times-circle' }} mr-1"></i>
                            {{ $event->is_active ? 'Active' : 'Inactive' }}
                        </span>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-medium text-gray-700 mb-1">Created At</label>
                            <p class="text-sm text-gray-700">{{ $event->created_at->format('M d, Y H:i:s') }}</p>
                            <p class="text-xs text-gray-500 mt-1">{{ $event->created_at->diffForHumans() }}</p>
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-700 mb-1">Updated At</label>
                            <p class="text-sm text-gray-700">{{ $event->updated_at->format('M d, Y H:i:s') }}</p>
                            <p class="text-xs text-gray-500 mt-1">{{ $event->updated_at->diffForHumans() }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Actions --}}
        <div class="px-6 py-4 bg-gray-50/80 border-t border-gray-200 rounded-b-md flex items-center justify-end space-x-3">
            <a href="{{ route('admin.event.index') }}" 
               class="px-5 py-2 rounded-md bg-white border border-gray-200 text-gray-700 text-sm hover:bg-gray-50 transition-colors duration-200">
                Back to List
            </a>
            <a href="{{ route('admin.event.edit', $event) }}" 
               class="px-5 py-2 rounded-md bg-primary text-white text-sm hover:bg-primary/80 transition-colors duration-200">
                Edit Event
            </a>
        </div>
    </div>
</div>
</x-layouts.admin>
