@props(['mobile' => false])

<div class="{{ $mobile ? 'flex flex-col space-y-4' : 'flex items-center gap-4' }}">
    <!-- Search Dropdown (Alpine.data from front.js) -->
    <div class="relative" x-data="searchDropdown" data-search-url="{{ route('search.suggestions') }}">
        <button type="button"
            @click="searchOpen = !searchOpen"
            aria-label="{{ __('frontend.header.search') }}"
            class="flex items-center text-gray-700 hover:text-gray-900 cursor-pointer transition-colors duration-200 dark:text-gray-400 dark:hover:text-white">
            <i class="fas fa-search text-lg"></i>
            @if($mobile) <span class="ml-2">{{ __('frontend.search_button_simple') }}</span> @endif
        </button>

        <!-- Search Dropdown Menu -->
        <div x-show="searchOpen" @click.away="searchOpen = false" x-transition:enter="transition ease-out duration-200"
            x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
            x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100 scale-100"
            x-transition:leave-end="opacity-0 scale-95"
            class="absolute {{ $mobile ? 'left-0 w-full' : 'right-0 w-80' }} mt-2 bg-white rounded-lg shadow-lg border border-gray-200 py-4 z-50">

            <form action="{{ route('search') }}" method="GET" class="px-4 pb-3">
                <div class="text-md font-semibold text-primary mb-3">{{ __('frontend.header.search') }}</div>
                <div class="relative">
                    <input type="text" id="header-search-q" name="q" x-model="query" @input.debounce.300ms="fetchSuggestions()"
                        placeholder="Zoek naar artikelen, modules, help..."
                        aria-label="{{ __('frontend.search_placeholder') }}"
                        class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:border-primary focus:outline-none transition-colors duration-200 text-sm">
                    <i
                        class="fas fa-search absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400 text-sm"></i>
                </div>
            </form>

            <!-- Search Suggestions -->
            <div x-show="suggestions.length > 0" class="border-t border-gray-100 pt-3 mb-3">
                <div class="px-4">
                    <p class="text-xs font-medium text-gray-500 uppercase tracking-wider mb-2">Resultaten</p>
                    <div class="space-y-1">
                        <template x-for="suggestion in suggestions" :key="suggestion.url">
                            <a :href="suggestion.url"
                                class="block px-2 py-2 text-sm text-gray-700 hover:bg-gray-50 rounded transition-colors duration-200">
                                <i :class="'fas ' + suggestion.icon" class="text-gray-400 mr-2 text-xs"></i>
                                <span x-text="suggestion.title"></span>
                                <span class="text-xs text-gray-500 ml-2" x-text="'(' + suggestion.type + ')'"></span>
                            </a>
                        </template>
                    </div>
                </div>
            </div>

            <!-- Most Searched Terms -->
            <div x-show="mostSearched.length > 0" class="border-t border-gray-100 pt-3">
                <div class="px-4">
                    <p class="text-xs font-medium text-gray-500 uppercase tracking-wider mb-2">Populaire zoektermen</p>
                    <div class="space-y-1">
                        <template x-for="item in mostSearched" :key="item.term">
                            <a :href="item.url"
                                class="block px-2 py-1 text-sm text-gray-700 hover:bg-gray-50 rounded transition-colors duration-200">
                                <i :class="'fas ' + item.icon" class="text-gray-400 mr-2 text-xs"></i>
                                <span x-text="item.term"></span>
                            </a>
                        </template>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Language Selector -->
    <div class="relative group" x-data="{ open: false }">
        <button type="button"
            @click="open = !open"
            :aria-expanded="open"
            aria-label="{{ __('frontend.header.language_choose') }}"
            class="flex items-center space-x-1 text-gray-700 hover:text-gray-900 cursor-pointer transition-colors duration-200 dark:text-gray-400 dark:hover:text-white">
            <i class="fas fa-language text-lg"></i>
            <span class="text-sm">{{ current_locale() === 'nl' ? 'NL' : 'EN' }}</span>
            <i class="fas fa-chevron-down text-xs ml-1 group-hover:rotate-180 transition-transform duration-200"></i>
        </button>

        <div x-show="open" @click.away="open = false" x-transition:enter="transition ease-out duration-200"
            x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
            x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100 scale-100"
            x-transition:leave-end="opacity-0 scale-95"
            class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg border border-gray-200 z-50">

            <div class="py-2">
                @foreach(available_locales() as $code => $name)
                    <form method="POST" action="{{ route('language.switch') }}" class="inline-block w-full">
                        @csrf
                        <input type="hidden" name="locale" value="{{ $code }}">
                        <button type="submit"
                            class="w-full flex items-center cursor-pointer justify-between px-4 py-3 text-left hover:bg-gray-50 transition-colors duration-200">
                            <div class="text-sm font-medium text-gray-900">{{ $name }}</div>
                            @if(current_locale() === $code)
                                <i class="fas fa-check text-primary"></i>
                            @endif
                        </button>
                    </form>
                @endforeach
            </div>
        </div>
    </div>

</div>
