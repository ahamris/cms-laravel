@props(['item', 'index', 'triggerClass' => 'text-gray-700 hover:text-gray-900 focus:outline-none flex items-center px-3 py-2 rounded-md'])

<div class="relative group" x-data="{
            isOpen: false,
            activeTab: '{{ !empty($item['children']) ? collect($item['children'])->first()['title'] ?? 'Business Management' : 'Business Management' }}',
            hoverTimeout: null,
            leaveTimeout: null
        }"
     @mouseenter="clearTimeout(leaveTimeout); hoverTimeout = setTimeout(() => isOpen = true, 150)"
     @mouseleave="clearTimeout(hoverTimeout); leaveTimeout = setTimeout(() => isOpen = false, 200)"
     @keydown.escape="isOpen = false"
     @keydown.arrow-down.prevent="isOpen = true"
     @keydown.arrow-up.prevent="isOpen = false">

    <button @click="isOpen = !isOpen"
            @keydown.enter.prevent="isOpen = !isOpen"
            @keydown.space.prevent="isOpen = !isOpen"
            :aria-expanded="isOpen"
            aria-haspopup="true"
            class="{{ $triggerClass }}">
        <span class="relative group/inner inline-block">
            {{ $item['title'] }}
            <span class="absolute -bottom-2 left-1/2 w-0 h-0.5 bg-primary transition-all duration-300 group-hover/inner:w-full group-hover/inner:left-0 transform -translate-x-1/2 group-hover/inner:translate-x-0"></span>
        </span>
        <i class="fas fa-chevron-down text-xs ml-1 group-hover:rotate-180 transition-transform duration-200"></i>
    </button>

    {{-- Mega Menu --}}
    <div class="absolute top-full -left-80 w-screen max-w-7xl bg-white border border-gray-200 shadow-lg opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-300 z-50 mt-6 overflow-hidden rounded-lg">
        <!-- Invisible bridge to prevent gap -->
        <div class="absolute -top-6 left-0 right-0 h-6 bg-transparent"></div>
        <div class="flex">
            <!-- Left Column - Solutions by Business Type -->
            <div class="w-1/3 p-8 bg-white border-r border-gray-200">
                <div class="space-y-6">
                    @if(!empty($item['children']))
                        @foreach($item['children'] as $index => $category)
                            @php $categoryItem = (array)$category; @endphp
                            <a href="{{ $categoryItem['url'] ?? '#' }}"
                               class="block group/item cursor-pointer p-4 rounded-lg -mx-4 transition-colors duration-200"
                               :class="activeTab === '{{ $categoryItem['title'] }}' ? 'bg-primary/10' : 'hover:bg-primary/10'"
                               @mouseenter="activeTab = '{{ $categoryItem['title'] }}'"
                                    {{ !empty($categoryItem['open_in_new_tab']) ? 'target="_blank" rel="noopener noreferrer"' : '' }}>
                                <h3 class="text-lg font-semibold text-gray-900 mb-2 group-hover/item:text-primary transition-colors">
                                    {{ $categoryItem['title'] }}
                                </h3>
                                <p class="text-gray-600 text-sm">{{ $categoryItem['subtitle'] ?? 'Professionele bedrijfsoplossing' }}</p>
                            </a>
                        @endforeach
                    @endif
                </div>
            </div>

            <!-- Right Column - Dynamic Content -->
            <div class="w-2/3 p-8 bg-white">
                @if(!empty($item['children']))
                    @foreach($item['children'] as $index => $category)
                        @php $categoryItem = (array)$category; @endphp
                        <!-- Category Content -->
                        <div x-show="activeTab === '{{ $categoryItem['title'] }}'" style="display: none;">
                            <h4 class="text-sm font-medium text-gray-500 mb-4">{{ $categoryItem['title'] }}</h4>

                            <!-- Features Grid -->
                            <div class="grid grid-cols-3 gap-2 mb-4">
                                @if(!empty($categoryItem['children']))
                                    @foreach($categoryItem['children'] as $module)
                                        @php $moduleItem = (array)$module; @endphp
                                        <!-- Feature Item -->
                                        <a href="{{ $moduleItem['url'] ?? '#' }}"
                                           class="block p-3 rounded-lg border border-transparent hover:border-primary/60 cursor-pointer transition-colors duration-200 bg-white"
                                                {{ !empty($moduleItem['open_in_new_tab']) ? 'target="_blank" rel="noopener noreferrer"' : '' }}>
                                            <div class="flex items-start space-x-3">
                                                <div class="w-10 h-10 rounded-lg flex items-center justify-center flex-shrink-0" style="background-color: {{ $moduleItem['icon_bg_color'] ?? '#3B82F6' }}">
                                                    <i class="{{ $moduleItem['icon'] ?? 'fas fa-cog' }} text-white text-sm"></i>
                                                </div>
                                                <div>
                                                    <h5 class="font-semibold text-gray-900 mb-1">{{ $moduleItem['title'] }}</h5>
                                                    <p class="text-gray-600 text-sm">{{ $moduleItem['subtitle'] ?? 'Professionele bedrijfsoplossing' }}</p>
                                                </div>
                                            </div>
                                        </a>
                                    @endforeach

                                    <!-- Extra features -->
                                    <div class="p-3 rounded-lg border border-transparent hover:border-primary/60 cursor-pointer transition-colors duration-200 bg-white">
                                        <div class="flex items-start space-x-3">
                                            <div class="w-10 h-10 bg-gray-200 rounded-lg flex items-center justify-center flex-shrink-0">
                                                <i class="fas fa-plus text-gray-500 text-sm"></i>
                                            </div>
                                            <div>
                                                <h5 class="font-semibold text-gray-900 mb-1">Extra functies</h5>
                                            </div>
                                        </div>
                                    </div>
                                @else
                                    <!-- Empty State -->
                                    <div class="col-span-3 text-center py-8">
                                        <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                                            <i class="fas fa-folder-open text-gray-400 text-xl"></i>
                                        </div>
                                        <h3 class="text-lg font-medium text-gray-900 mb-2">Geen functies beschikbaar</h3>
                                        <p class="text-gray-500">Functies voor {{ $categoryItem['title'] }} komen binnenkort beschikbaar.</p>
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endforeach
                @endif
            </div>
        </div>
    </div>
</div>
