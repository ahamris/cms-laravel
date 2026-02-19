<x-layouts.admin title="View Marketing Persona">
<div class="space-y-6">
    {{-- Header --}}
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">{{ $persona->name }}</h1>
            <p class="text-gray-600">Marketing Persona Details</p>
        </div>
        <div class="flex items-center space-x-3">
            <a href="{{ route('admin.marketing.persona.edit', $persona) }}"
               class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition-colors duration-200">
                <i class="fa-solid fa-edit mr-2"></i>
                Edit Persona
            </a>
            <a href="{{ route('admin.marketing.persona.index') }}"
               class="bg-gray-600 text-white px-4 py-2 rounded-lg hover:bg-gray-700 transition-colors duration-200">
                <i class="fa-solid fa-arrow-left mr-2"></i>
                Back to List
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        {{-- Main Content --}}
        <div class="lg:col-span-2 space-y-6">
            {{-- Basic Information --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-200">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900">Basic Information</h3>
                </div>
                <div class="p-6 space-y-4">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Name</label>
                            <p class="text-gray-900">{{ $persona->name }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Slug</label>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                {{ $persona->slug }}
                            </span>
                        </div>
                    </div>
                    
                    @if($persona->description)
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                            <p class="text-gray-900">{{ $persona->description }}</p>
                        </div>
                    @endif
                </div>
            </div>

            {{-- Demographics --}}
            @if($persona->demographics)
                <div class="bg-white rounded-xl shadow-sm border border-gray-200">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-900">Demographics</h3>
                    </div>
                    <div class="p-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            @if(isset($persona->demographics['age_range']))
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Age Range</label>
                                    <p class="text-gray-900">{{ $persona->demographics['age_range'] }}</p>
                                </div>
                            @endif
                            @if(isset($persona->demographics['company_size']))
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Company Size</label>
                                    <p class="text-gray-900">{{ $persona->demographics['company_size'] }}</p>
                                </div>
                            @endif
                            @if(isset($persona->demographics['industry']))
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Industry</label>
                                    <p class="text-gray-900">{{ $persona->demographics['industry'] }}</p>
                                </div>
                            @endif
                            @if(isset($persona->demographics['location']))
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Location</label>
                                    <p class="text-gray-900">{{ $persona->demographics['location'] }}</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            @endif

            {{-- Pain Points & Goals --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                {{-- Pain Points --}}
                <div class="bg-white rounded-xl shadow-sm border border-gray-200">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-900">Pain Points</h3>
                    </div>
                    <div class="p-6">
                        @if($persona->pain_points && count($persona->pain_points) > 0)
                            <ul class="space-y-2">
                                @foreach($persona->pain_points as $painPoint)
                                    <li class="flex items-start">
                                        <i class="fa-solid fa-exclamation-triangle text-red-500 mt-1 mr-2 text-sm"></i>
                                        <span class="text-gray-900">{{ $painPoint }}</span>
                                    </li>
                                @endforeach
                            </ul>
                        @else
                            <p class="text-gray-500 italic">No pain points defined</p>
                        @endif
                    </div>
                </div>

                {{-- Goals --}}
                <div class="bg-white rounded-xl shadow-sm border border-gray-200">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-900">Goals</h3>
                    </div>
                    <div class="p-6">
                        @if($persona->goals && count($persona->goals) > 0)
                            <ul class="space-y-2">
                                @foreach($persona->goals as $goal)
                                    <li class="flex items-start">
                                        <i class="fa-solid fa-bullseye text-green-500 mt-1 mr-2 text-sm"></i>
                                        <span class="text-gray-900">{{ $goal }}</span>
                                    </li>
                                @endforeach
                            </ul>
                        @else
                            <p class="text-gray-500 italic">No goals defined</p>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Preferred Channels --}}
            @if($persona->preferred_channels && count($persona->preferred_channels) > 0)
                <div class="bg-white rounded-xl shadow-sm border border-gray-200">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-900">Preferred Channels</h3>
                    </div>
                    <div class="p-6">
                        <div class="flex flex-wrap gap-2">
                            @foreach($persona->preferred_channels as $channel)
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800">
                                    <i class="fa-solid fa-broadcast-tower mr-1"></i>
                                    {{ $channel }}
                                </span>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endif
        </div>

        {{-- Sidebar --}}
        <div class="lg:col-span-1 space-y-6">
            {{-- Avatar --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-200">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900">Avatar</h3>
                </div>
                <div class="p-6 text-center">
                    @if($persona->avatar_image)
                        <img src="{{ asset('storage/' . $persona->avatar_image) }}" 
                             alt="{{ $persona->name }}" 
                             class="w-32 h-32 rounded-full object-cover mx-auto">
                    @else
                        <div class="w-32 h-32 rounded-full bg-primary/10 flex items-center justify-center mx-auto">
                            <i class="fa-solid fa-user text-primary text-4xl"></i>
                        </div>
                        <p class="text-gray-500 text-sm mt-2">No avatar uploaded</p>
                    @endif
                </div>
            </div>

            {{-- Status & Settings --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-200">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900">Status & Settings</h3>
                </div>
                <div class="p-6 space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $persona->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                            <i class="fa-solid fa-{{ $persona->is_active ? 'check' : 'times' }} mr-1"></i>
                            {{ $persona->is_active ? 'Active' : 'Inactive' }}
                        </span>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Sort Order</label>
                        <p class="text-gray-900">{{ $persona->sort_order }}</p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Created</label>
                        <p class="text-gray-900 text-sm">{{ $persona->created_at->format('M j, Y \a\t g:i A') }}</p>
                    </div>

                    @if($persona->updated_at != $persona->created_at)
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Last Updated</label>
                            <p class="text-gray-900 text-sm">{{ $persona->updated_at->format('M j, Y \a\t g:i A') }}</p>
                        </div>
                    @endif
                </div>
            </div>

            {{-- Usage Statistics --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-200">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900">Usage Statistics</h3>
                </div>
                <div class="p-6 space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Blog Articles</label>
                        <p class="text-gray-900">{{ $persona->blogs()->count() }} articles</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Pages</label>
                        <p class="text-gray-900">{{ $persona->pages()->count() }} pages</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Services</label>
                        <p class="text-gray-900">{{ $persona->services()->count() }} services</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</x-layouts.admin>
