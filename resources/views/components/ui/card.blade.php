{{-- Card component with slot support (header, body, footer) or stat card style --}}


@if($loading)
    {{-- Loading state --}}
    <div class="{{ $classes }} divide-y divide-gray-200 overflow-hidden dark:divide-white/10" {{ $attributes->except(['class']) }}>
        <div class="px-4 py-5 sm:p-6">
            <div class="animate-pulse space-y-4">
                <div class="flex items-start gap-4">
                    <div class="w-12 h-12 rounded-lg bg-zinc-200 dark:bg-zinc-700"></div>
                    <div class="flex-1 space-y-2">
                        <div class="h-4 bg-zinc-200 dark:bg-zinc-700 rounded w-3/4"></div>
                        <div class="h-6 bg-zinc-200 dark:bg-zinc-700 rounded w-1/2"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@elseif(isset($header) || isset($body) || isset($footer) || ($slot->isNotEmpty() && empty($title) && empty($value)))
    {{-- Slot-based card (flexible usage with header, body, footer slots) --}}
    @php
        $hasMultipleSections = (isset($header) ? 1 : 0) + (isset($body) || $slot->isNotEmpty() ? 1 : 0) + (isset($footer) ? 1 : 0) > 1;
        $divideClasses = $hasMultipleSections ? 'divide-y divide-gray-200 dark:divide-white/10' : '';
    @endphp
    @if($clickable && $clickUrl)
        <a href="{{ $clickUrl }}" class="{{ $classes }} {{ $divideClasses }} overflow-hidden block" {{ $attributes->except(['class']) }}>
    @else
        <div class="{{ $classes }} {{ $divideClasses }} overflow-hidden" {{ $attributes->except(['class']) }}>
    @endif
        @if($image)
            @php
                $imageMargin = match($size) {
                    'sm' => '-mx-3 -mt-3',
                    'lg' => '-mx-6 -mt-6',
                    default => '-mx-5 -mt-5',
                };
            @endphp
            <div class="mb-4 {{ $imageMargin }} rounded-t-lg overflow-hidden">
                <img src="{{ $image }}" alt="{{ $imageAlt ?? '' }}" class="w-full h-auto object-cover">
            </div>
        @endif

        @if(isset($header))
            <div class="px-4 py-5 sm:px-6">
                {{ $header }}
            </div>
        @endif

        @if(isset($body))
            <div class="px-4 py-5 sm:p-6">
                {{ $body }}
            </div>
        @elseif($slot->isNotEmpty())
            <div class="px-4 py-5 sm:p-6">
                {{ $slot }}
            </div>
        @endif

        @if(isset($footer))
            <div class="px-4 py-4 sm:px-6">
                {{ $footer }}
            </div>
        @endif
    @if($clickable && $clickUrl)
        </a>
    @else
        </div>
    @endif
@else
    {{-- Props-based card (stat card style) --}}
    @if($clickable && $clickUrl)
        <a href="{{ $clickUrl }}" class="{{ $classes }} block" {{ $attributes->except(['class']) }}>
    @else
        <div class="{{ $classes }}" {{ $attributes->except(['class']) }}>
    @endif
        @if($image)
            @php
                $imageMargin = match($size) {
                    'sm' => '-mx-3 -mt-3',
                    'lg' => '-mx-6 -mt-6',
                    default => '-mx-5 -mt-5',
                };
            @endphp
            <div class="mb-4 {{ $imageMargin }} rounded-t-lg overflow-hidden">
                <img src="{{ $image }}" alt="{{ $imageAlt ?? '' }}" class="w-full h-auto object-cover">
            </div>
        @endif

        <div class="{{ $paddingClasses }}">
            <div class="flex items-start gap-4">
                @if($icon)
                    @php
                        $iconSize = match($size) {
                            'sm' => 'w-10 h-10',
                            'lg' => 'w-14 h-14',
                            default => 'w-12 h-12',
                        };
                        $iconTextSize = match($size) {
                            'sm' => 'text-base',
                            'lg' => 'text-2xl',
                            default => 'text-xl',
                        };
                    @endphp
                    <div class="{{ $iconSize }} rounded-lg {{ $colorClasses['bg'] }} {{ $colorClasses['bgDark'] }} flex items-center justify-center flex-shrink-0 shadow-sm">
                        <i class="fas fa-{{ $icon }} {{ $colorClasses['text'] }} {{ $colorClasses['textDark'] }} {{ $iconTextSize }}"></i>
                    </div>
                @endif
                <div class="flex-1">
                    @if($title)
                        @php
                            $titleSize = match($size) {
                                'sm' => 'text-xs',
                                'lg' => 'text-base',
                                default => 'text-sm',
                            };
                        @endphp
                        <p class="font-medium {{ $titleSize }} text-zinc-600 dark:text-zinc-400 mb-1">{{ $title }}</p>
                    @endif
                    @if($value)
                        @php
                            $valueSize = match($size) {
                                'sm' => 'text-xl',
                                'lg' => 'text-3xl',
                                default => 'text-2xl',
                            };
                        @endphp
                        <p class="{{ $valueSize }} font-semibold text-zinc-900 dark:text-zinc-100">{{ $value }}</p>
                    @endif
                </div>
            </div>
            @if($actionText && $actionUrl && $actionUrl !== 'javascript:void(0)')
                <div class="border-t border-dashed border-zinc-200 dark:border-zinc-700 mt-4 pt-4">
                    @php
                        $actionTextSize = match($size) {
                            'sm' => 'text-xs',
                            default => 'text-sm',
                        };
                    @endphp
                    <a href="{{ $actionUrl }}" class="flex items-center justify-between {{ $actionTextSize }} font-medium text-zinc-600 dark:text-zinc-400 hover:text-indigo-600 dark:hover:text-indigo-400 group transition-colors duration-200" onclick="event.stopPropagation();">
                        <span>{{ $actionText }}</span>
                        <i class="fas fa-chevron-right text-xs group-hover:translate-x-1 transition-transform duration-200"></i>
                    </a>
                </div>
            @endif
        </div>
    @if($clickable && $clickUrl)
        </a>
    @else
        </div>
    @endif
@endif
