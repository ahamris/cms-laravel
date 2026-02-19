@props([
    'id' => 'icon',
    'name' => 'icon',
    'value' => '',
    'label' => 'Icon',
    'helpText' => 'Select a FontAwesome icon',
    'required' => false,
])

@php
    // Flatten all icons into a single array for pagination
    $allIcons = [
        // Basic UI
        'fa-solid fa-square',
        'fa-solid fa-circle',
        'fa-solid fa-circle-dot',
        'fa-solid fa-anchor',
        'fa-solid fa-box',
        'fa-solid fa-mountain',
        'fa-solid fa-crosshairs',
        'fa-solid fa-arrows-left-right',
        'fa-solid fa-arrows-up-down',
        'fa-solid fa-car',
        'fa-solid fa-asterisk',
        'fa-solid fa-at',
        'fa-solid fa-ban',
        'fa-solid fa-building',
        'fa-solid fa-chart-bar',
        'fa-solid fa-barcode',
        'fa-solid fa-bars',
        'fa-solid fa-grip',
        'fa-solid fa-grip-vertical',
        'fa-solid fa-ellipsis',
        'fa-solid fa-ellipsis-vertical',
        
        // Navigation & Home
        'fa-solid fa-home',
        'fa-solid fa-house',
        'fa-solid fa-house-chimney',
        'fa-solid fa-door-open',
        'fa-solid fa-door-closed',
        'fa-solid fa-sitemap',
        'fa-solid fa-layer-group',
        'fa-solid fa-table-cells',
        'fa-solid fa-table-columns',
        'fa-solid fa-table-list',
        
        // Users & People
        'fa-solid fa-user',
        'fa-solid fa-users',
        'fa-solid fa-user-group',
        'fa-solid fa-user-circle',
        'fa-solid fa-user-plus',
        'fa-solid fa-user-minus',
        'fa-solid fa-user-check',
        'fa-solid fa-user-xmark',
        'fa-solid fa-user-pen',
        'fa-solid fa-user-gear',
        'fa-solid fa-user-shield',
        'fa-solid fa-user-tie',
        'fa-solid fa-user-secret',
        'fa-solid fa-people-group',
        'fa-solid fa-person',
        'fa-solid fa-children',
        'fa-solid fa-address-book',
        'fa-solid fa-address-card',
        'fa-solid fa-id-card',
        'fa-solid fa-id-badge',
        
        // Communication
        'fa-solid fa-envelope',
        'fa-solid fa-envelope-open',
        'fa-solid fa-envelopes',
        'fa-solid fa-inbox',
        'fa-solid fa-paper-plane',
        'fa-solid fa-phone',
        'fa-solid fa-phone-flip',
        'fa-solid fa-phone-slash',
        'fa-solid fa-phone-volume',
        'fa-solid fa-message',
        'fa-solid fa-messages',
        'fa-solid fa-comment',
        'fa-solid fa-comments',
        'fa-solid fa-comment-dots',
        'fa-solid fa-comment-sms',
        'fa-solid fa-hashtag',
        'fa-solid fa-bullhorn',
        'fa-solid fa-megaphone',
        'fa-solid fa-fax',
        'fa-solid fa-walkie-talkie',
        'fa-solid fa-radio',
        'fa-solid fa-broadcast-tower',
        
        // Time & Calendar
        'fa-solid fa-calendar',
        'fa-solid fa-calendar-days',
        'fa-solid fa-calendar-check',
        'fa-solid fa-calendar-plus',
        'fa-solid fa-calendar-minus',
        'fa-solid fa-calendar-xmark',
        'fa-solid fa-calendar-week',
        'fa-solid fa-clock',
        'fa-solid fa-stopwatch',
        'fa-solid fa-hourglass',
        'fa-solid fa-hourglass-half',
        'fa-solid fa-timer',
        'fa-solid fa-alarm-clock',
        'fa-solid fa-history',
        
        // Actions & Favorites
        'fa-solid fa-star',
        'fa-solid fa-star-half',
        'fa-solid fa-heart',
        'fa-solid fa-thumbs-up',
        'fa-solid fa-thumbs-down',
        'fa-solid fa-bookmark',
        'fa-solid fa-share',
        'fa-solid fa-share-nodes',
        'fa-solid fa-share-from-square',
        'fa-solid fa-retweet',
        'fa-solid fa-reply',
        'fa-solid fa-reply-all',
        'fa-solid fa-download',
        'fa-solid fa-upload',
        'fa-solid fa-cloud-arrow-up',
        'fa-solid fa-cloud-arrow-down',
        'fa-solid fa-search',
        'fa-solid fa-magnifying-glass',
        'fa-solid fa-magnifying-glass-plus',
        'fa-solid fa-magnifying-glass-minus',
        'fa-solid fa-filter',
        'fa-solid fa-sliders',
        'fa-solid fa-gear',
        'fa-solid fa-gears',
        'fa-solid fa-bell',
        'fa-solid fa-bell-slash',
        
        // Media
        'fa-solid fa-image',
        'fa-solid fa-images',
        'fa-solid fa-photo-film',
        'fa-solid fa-camera',
        'fa-solid fa-camera-retro',
        'fa-solid fa-video',
        'fa-solid fa-video-slash',
        'fa-solid fa-microphone',
        'fa-solid fa-microphone-lines',
        'fa-solid fa-microphone-slash',
        'fa-solid fa-headphones',
        'fa-solid fa-headset',
        'fa-solid fa-music',
        'fa-solid fa-film',
        'fa-solid fa-clapperboard',
        'fa-solid fa-play',
        'fa-solid fa-pause',
        'fa-solid fa-stop',
        'fa-solid fa-forward',
        'fa-solid fa-backward',
        'fa-solid fa-circle-play',
        'fa-solid fa-circle-pause',
        'fa-solid fa-volume-high',
        'fa-solid fa-volume-low',
        'fa-solid fa-volume-off',
        'fa-solid fa-volume-xmark',
        'fa-solid fa-compact-disc',
        
        // Files & Documents
        'fa-solid fa-file',
        'fa-solid fa-file-lines',
        'fa-solid fa-file-pdf',
        'fa-solid fa-file-word',
        'fa-solid fa-file-excel',
        'fa-solid fa-file-powerpoint',
        'fa-solid fa-file-image',
        'fa-solid fa-file-video',
        'fa-solid fa-file-audio',
        'fa-solid fa-file-code',
        'fa-solid fa-file-csv',
        'fa-solid fa-file-zipper',
        'fa-solid fa-file-arrow-up',
        'fa-solid fa-file-arrow-down',
        'fa-solid fa-file-import',
        'fa-solid fa-file-export',
        'fa-solid fa-file-circle-check',
        'fa-solid fa-file-circle-plus',
        'fa-solid fa-folder',
        'fa-solid fa-folder-open',
        'fa-solid fa-folder-plus',
        'fa-solid fa-folder-minus',
        'fa-solid fa-copy',
        'fa-solid fa-paste',
        'fa-solid fa-clipboard',
        'fa-solid fa-clipboard-check',
        'fa-solid fa-clipboard-list',
        'fa-solid fa-note-sticky',
        'fa-solid fa-box-archive',
        
        // Editing
        'fa-solid fa-link',
        'fa-solid fa-link-slash',
        'fa-solid fa-lock',
        'fa-solid fa-lock-open',
        'fa-solid fa-unlock',
        'fa-solid fa-trash',
        'fa-solid fa-trash-can',
        'fa-solid fa-trash-arrow-up',
        'fa-solid fa-pen',
        'fa-solid fa-pen-to-square',
        'fa-solid fa-pencil',
        'fa-solid fa-eraser',
        'fa-solid fa-plus',
        'fa-solid fa-minus',
        'fa-solid fa-plus-minus',
        'fa-solid fa-check',
        'fa-solid fa-xmark',
        'fa-solid fa-circle-check',
        'fa-solid fa-circle-xmark',
        'fa-solid fa-square-check',
        'fa-solid fa-square-xmark',
        'fa-solid fa-rotate',
        'fa-solid fa-rotate-left',
        'fa-solid fa-rotate-right',
        'fa-solid fa-arrows-rotate',
        
        // Arrows & Navigation
        'fa-solid fa-arrow-right',
        'fa-solid fa-arrow-left',
        'fa-solid fa-arrow-up',
        'fa-solid fa-arrow-down',
        'fa-solid fa-arrow-up-right-from-square',
        'fa-solid fa-arrow-right-from-bracket',
        'fa-solid fa-arrow-right-to-bracket',
        'fa-solid fa-arrows-up-down-left-right',
        'fa-solid fa-up-down-left-right',
        'fa-solid fa-chevron-right',
        'fa-solid fa-chevron-left',
        'fa-solid fa-chevron-up',
        'fa-solid fa-chevron-down',
        'fa-solid fa-angle-right',
        'fa-solid fa-angle-left',
        'fa-solid fa-angle-up',
        'fa-solid fa-angle-down',
        'fa-solid fa-angles-right',
        'fa-solid fa-angles-left',
        'fa-solid fa-caret-right',
        'fa-solid fa-caret-left',
        'fa-solid fa-caret-up',
        'fa-solid fa-caret-down',
        'fa-solid fa-sort',
        'fa-solid fa-sort-up',
        'fa-solid fa-sort-down',
        'fa-solid fa-arrow-pointer',
        'fa-solid fa-hand-pointer',
        
        // Business & Finance
        'fa-solid fa-briefcase',
        'fa-solid fa-building',
        'fa-solid fa-building-columns',
        'fa-solid fa-city',
        'fa-solid fa-landmark',
        'fa-solid fa-chart-line',
        'fa-solid fa-chart-pie',
        'fa-solid fa-chart-area',
        'fa-solid fa-chart-column',
        'fa-solid fa-chart-simple',
        'fa-solid fa-coins',
        'fa-solid fa-dollar-sign',
        'fa-solid fa-euro-sign',
        'fa-solid fa-credit-card',
        'fa-solid fa-wallet',
        'fa-solid fa-receipt',
        'fa-solid fa-money-bill',
        'fa-solid fa-money-bill-wave',
        'fa-solid fa-money-check',
        'fa-solid fa-piggy-bank',
        'fa-solid fa-vault',
        'fa-solid fa-sack-dollar',
        'fa-solid fa-hand-holding-dollar',
        'fa-solid fa-circle-dollar-to-slot',
        'fa-solid fa-store',
        'fa-solid fa-shop',
        'fa-solid fa-cart-shopping',
        'fa-solid fa-cart-plus',
        'fa-solid fa-cart-arrow-down',
        'fa-solid fa-bag-shopping',
        'fa-solid fa-basket-shopping',
        'fa-solid fa-truck',
        'fa-solid fa-truck-fast',
        'fa-solid fa-box',
        'fa-solid fa-boxes-stacked',
        'fa-solid fa-warehouse',
        'fa-solid fa-barcode',
        'fa-solid fa-qrcode',
        'fa-solid fa-handshake',
        'fa-solid fa-file-invoice',
        'fa-solid fa-file-invoice-dollar',
        'fa-solid fa-file-contract',
        'fa-solid fa-certificate',
        'fa-solid fa-award',
        'fa-solid fa-trophy',
        'fa-solid fa-medal',
        'fa-solid fa-ranking-star',
        
        // Technology
        'fa-solid fa-desktop',
        'fa-solid fa-laptop',
        'fa-solid fa-tablet-screen-button',
        'fa-solid fa-mobile-screen-button',
        'fa-solid fa-display',
        'fa-solid fa-tv',
        'fa-solid fa-keyboard',
        'fa-solid fa-computer-mouse',
        'fa-solid fa-print',
        'fa-solid fa-server',
        'fa-solid fa-database',
        'fa-solid fa-hdd',
        'fa-solid fa-sd-card',
        'fa-solid fa-sim-card',
        'fa-solid fa-cloud',
        'fa-solid fa-network-wired',
        'fa-solid fa-wifi',
        'fa-solid fa-signal',
        'fa-solid fa-satellite',
        'fa-solid fa-satellite-dish',
        'fa-solid fa-code',
        'fa-solid fa-code-branch',
        'fa-solid fa-code-commit',
        'fa-solid fa-code-merge',
        'fa-solid fa-code-pull-request',
        'fa-solid fa-terminal',
        'fa-solid fa-bug',
        'fa-solid fa-bug-slash',
        'fa-solid fa-robot',
        'fa-solid fa-microchip',
        'fa-solid fa-plug',
        'fa-solid fa-battery-full',
        'fa-solid fa-battery-half',
        'fa-solid fa-battery-empty',
        'fa-solid fa-power-off',
        
        // Security
        'fa-solid fa-shield',
        'fa-solid fa-shield-halved',
        'fa-solid fa-shield-check',
        'fa-solid fa-key',
        'fa-solid fa-lock',
        'fa-solid fa-unlock-keyhole',
        'fa-solid fa-fingerprint',
        'fa-solid fa-eye',
        'fa-solid fa-eye-slash',
        'fa-solid fa-user-lock',
        'fa-solid fa-user-shield',
        
        // Maps & Location
        'fa-solid fa-globe',
        'fa-solid fa-earth-americas',
        'fa-solid fa-earth-europe',
        'fa-solid fa-earth-asia',
        'fa-solid fa-map',
        'fa-solid fa-map-location-dot',
        'fa-solid fa-location-dot',
        'fa-solid fa-location-pin',
        'fa-solid fa-location-crosshairs',
        'fa-solid fa-compass',
        'fa-solid fa-route',
        'fa-solid fa-signs-post',
        'fa-solid fa-diamond-turn-right',
        'fa-solid fa-street-view',
        'fa-solid fa-flag',
        'fa-solid fa-flag-checkered',
        
        // Status & Alerts
        'fa-solid fa-circle-check',
        'fa-solid fa-circle-xmark',
        'fa-solid fa-circle-exclamation',
        'fa-solid fa-circle-question',
        'fa-solid fa-circle-info',
        'fa-solid fa-triangle-exclamation',
        'fa-solid fa-exclamation',
        'fa-solid fa-question',
        'fa-solid fa-info',
        'fa-solid fa-check-double',
        'fa-solid fa-spinner',
        'fa-solid fa-circle-notch',
        
        // Education
        'fa-solid fa-graduation-cap',
        'fa-solid fa-school',
        'fa-solid fa-book',
        'fa-solid fa-book-open',
        'fa-solid fa-book-bookmark',
        'fa-solid fa-bookmark',
        'fa-solid fa-user-graduate',
        'fa-solid fa-chalkboard',
        'fa-solid fa-chalkboard-user',
        'fa-solid fa-lightbulb',
        'fa-solid fa-pen-ruler',
        'fa-solid fa-ruler',
        'fa-solid fa-calculator',
        'fa-solid fa-newspaper',
        'fa-solid fa-rss',
        'fa-solid fa-blog',
        
        // Healthcare
        'fa-solid fa-hospital',
        'fa-solid fa-user-doctor',
        'fa-solid fa-stethoscope',
        'fa-solid fa-heart-pulse',
        'fa-solid fa-pills',
        'fa-solid fa-capsules',
        'fa-solid fa-syringe',
        'fa-solid fa-vial',
        'fa-solid fa-microscope',
        'fa-solid fa-dna',
        'fa-solid fa-truck-medical',
        'fa-solid fa-bandage',
        'fa-solid fa-kit-medical',
        'fa-solid fa-tooth',
        'fa-solid fa-brain',
        'fa-solid fa-lungs',
        'fa-solid fa-bone',
        
        // Transportation
        'fa-solid fa-car',
        'fa-solid fa-car-side',
        'fa-solid fa-taxi',
        'fa-solid fa-motorcycle',
        'fa-solid fa-bicycle',
        'fa-solid fa-bus',
        'fa-solid fa-train',
        'fa-solid fa-train-subway',
        'fa-solid fa-plane',
        'fa-solid fa-plane-departure',
        'fa-solid fa-plane-arrival',
        'fa-solid fa-helicopter',
        'fa-solid fa-rocket',
        'fa-solid fa-ship',
        'fa-solid fa-ferry',
        'fa-solid fa-anchor',
        'fa-solid fa-road',
        'fa-solid fa-gas-pump',
        'fa-solid fa-charging-station',
        'fa-solid fa-square-parking',
        'fa-solid fa-traffic-light',
        
        // Food & Drink
        'fa-solid fa-utensils',
        'fa-solid fa-pizza-slice',
        'fa-solid fa-burger',
        'fa-solid fa-hotdog',
        'fa-solid fa-bowl-food',
        'fa-solid fa-plate-wheat',
        'fa-solid fa-mug-hot',
        'fa-solid fa-mug-saucer',
        'fa-solid fa-wine-glass',
        'fa-solid fa-martini-glass',
        'fa-solid fa-beer-mug-empty',
        'fa-solid fa-bottle-water',
        'fa-solid fa-cake-candles',
        'fa-solid fa-ice-cream',
        'fa-solid fa-cookie',
        'fa-solid fa-apple-whole',
        'fa-solid fa-carrot',
        'fa-solid fa-lemon',
        
        // Weather & Nature
        'fa-solid fa-sun',
        'fa-solid fa-moon',
        'fa-solid fa-cloud',
        'fa-solid fa-cloud-sun',
        'fa-solid fa-cloud-moon',
        'fa-solid fa-cloud-rain',
        'fa-solid fa-cloud-showers-heavy',
        'fa-solid fa-cloud-bolt',
        'fa-solid fa-snowflake',
        'fa-solid fa-temperature-high',
        'fa-solid fa-temperature-low',
        'fa-solid fa-umbrella',
        'fa-solid fa-wind',
        'fa-solid fa-tornado',
        'fa-solid fa-fire',
        'fa-solid fa-fire-flame-curved',
        'fa-solid fa-droplet',
        'fa-solid fa-water',
        'fa-solid fa-leaf',
        'fa-solid fa-tree',
        'fa-solid fa-seedling',
        'fa-solid fa-mountain',
        'fa-solid fa-mountain-sun',
        
        // Sports & Activities
        'fa-solid fa-futbol',
        'fa-solid fa-basketball',
        'fa-solid fa-baseball',
        'fa-solid fa-football',
        'fa-solid fa-volleyball',
        'fa-solid fa-table-tennis-paddle-ball',
        'fa-solid fa-golf-ball-tee',
        'fa-solid fa-bowling-ball',
        'fa-solid fa-dumbbell',
        'fa-solid fa-person-running',
        'fa-solid fa-person-swimming',
        'fa-solid fa-person-biking',
        'fa-solid fa-person-hiking',
        'fa-solid fa-person-skiing',
        'fa-solid fa-gamepad',
        'fa-solid fa-chess',
        'fa-solid fa-dice',
        'fa-solid fa-puzzle-piece',
        
        // Tools
        'fa-solid fa-wrench',
        'fa-solid fa-screwdriver',
        'fa-solid fa-screwdriver-wrench',
        'fa-solid fa-hammer',
        'fa-solid fa-toolbox',
        'fa-solid fa-paintbrush',
        'fa-solid fa-paint-roller',
        'fa-solid fa-palette',
        'fa-solid fa-brush',
        'fa-solid fa-scissors',
        'fa-solid fa-ruler-combined',
        'fa-solid fa-flask',
        'fa-solid fa-flask-vial',
        'fa-solid fa-broom',
        'fa-solid fa-spray-can',
        
        // Objects
        'fa-solid fa-gift',
        'fa-solid fa-cake-candles',
        'fa-solid fa-gem',
        'fa-solid fa-crown',
        'fa-solid fa-ring',
        'fa-solid fa-glasses',
        'fa-solid fa-umbrella',
        'fa-solid fa-bag-shopping',
        'fa-solid fa-suitcase',
        'fa-solid fa-suitcase-rolling',
        'fa-solid fa-wand-magic-sparkles',
        'fa-solid fa-magic',
        'fa-solid fa-hat-wizard',
        'fa-solid fa-infinity',
        
        // Social Brands
        'fa-brands fa-facebook',
        'fa-brands fa-facebook-f',
        'fa-brands fa-instagram',
        'fa-brands fa-twitter',
        'fa-brands fa-x-twitter',
        'fa-brands fa-linkedin',
        'fa-brands fa-linkedin-in',
        'fa-brands fa-youtube',
        'fa-brands fa-tiktok',
        'fa-brands fa-snapchat',
        'fa-brands fa-pinterest',
        'fa-brands fa-pinterest-p',
        'fa-brands fa-reddit',
        'fa-brands fa-reddit-alien',
        'fa-brands fa-discord',
        'fa-brands fa-slack',
        'fa-brands fa-whatsapp',
        'fa-brands fa-telegram',
        'fa-brands fa-skype',
        'fa-brands fa-viber',
        'fa-brands fa-threads',
        'fa-brands fa-mastodon',
        
        // Tech Brands
        'fa-brands fa-github',
        'fa-brands fa-gitlab',
        'fa-brands fa-bitbucket',
        'fa-brands fa-stack-overflow',
        'fa-brands fa-docker',
        'fa-brands fa-npm',
        'fa-brands fa-node-js',
        'fa-brands fa-php',
        'fa-brands fa-python',
        'fa-brands fa-java',
        'fa-brands fa-js',
        'fa-brands fa-react',
        'fa-brands fa-vuejs',
        'fa-brands fa-angular',
        'fa-brands fa-laravel',
        'fa-brands fa-wordpress',
        'fa-brands fa-shopify',
        'fa-brands fa-figma',
        'fa-brands fa-sketch',
        'fa-brands fa-dribbble',
        'fa-brands fa-behance',
        
        // Company Brands
        'fa-brands fa-apple',
        'fa-brands fa-google',
        'fa-brands fa-microsoft',
        'fa-brands fa-amazon',
        'fa-brands fa-aws',
        'fa-brands fa-digital-ocean',
        'fa-brands fa-cloudflare',
        'fa-brands fa-dropbox',
        'fa-brands fa-google-drive',
        'fa-brands fa-paypal',
        'fa-brands fa-stripe',
        'fa-brands fa-cc-visa',
        'fa-brands fa-cc-mastercard',
        'fa-brands fa-cc-amex',
        'fa-brands fa-spotify',
        'fa-brands fa-twitch',
        'fa-brands fa-steam',
        'fa-brands fa-xbox',
        'fa-brands fa-playstation',
        'fa-brands fa-android',
        'fa-brands fa-app-store-ios',
        'fa-brands fa-chrome',
        'fa-brands fa-firefox',
        'fa-brands fa-safari',
        'fa-brands fa-edge',
        'fa-brands fa-opera',
    ];

    
    $iconsPerPage = 24;
    $totalIcons = count($allIcons);
    $totalPages = ceil($totalIcons / $iconsPerPage);
@endphp

<div class="relative">
    @if($label)
        <label for="{{ $id }}" class="{{ $required ? '' : '' }}">
            {{ $label }}
            @if($required)
                <span class="text-red-600 dark:text-red-400">*</span>
            @endif
        </label>
    @endif
    
    {{-- Hidden input for form submission --}}
    <input type="hidden" 
           id="{{ $id }}" 
           name="{{ $name }}" 
           value="{{ old($name, $value) }}"
           {{ $required ? 'required' : '' }}>

    {{-- Dropdown Trigger Button --}}
    <div class="relative inline-block w-full {{ $label ? 'mt-2' : '' }}">
        <button type="button" 
                onclick="window.toggleIconPicker_{{ $id }}('{{ $id }}')"
                class="w-full px-4 py-2.5 bg-white dark:bg-zinc-800 border border-zinc-300 dark:border-zinc-700 text-zinc-900 dark:text-zinc-100 rounded-md hover:border-zinc-400 dark:hover:border-zinc-600 transition-all duration-200 flex items-center gap-3 justify-between focus:outline-none focus:ring-1 focus:ring-[var(--color-accent)] focus:border-[var(--color-accent)]"
                id="{{ $id }}_trigger_btn">
            <span class="flex items-center gap-3 flex-1 min-w-0">
                <i id="{{ $id }}_trigger_icon" class="{{ old($name, $value) ? old($name, $value) : 'fa-solid fa-icons' }} text-zinc-500 dark:text-zinc-400 flex-shrink-0"></i>
                <span id="{{ $id }}_trigger_text" class="text-base leading-6 tracking-[0.5px] text-zinc-700 dark:text-zinc-300 truncate">{{ old($name, $value) ? 'Change Icon' : 'Select Icon' }}</span>
            </span>
            <i class="fa-solid fa-chevron-down text-xs text-zinc-400 dark:text-zinc-500 flex-shrink-0 transition-transform duration-200" id="{{ $id }}_chevron"></i>
        </button>
        
        {{-- Dropdown Panel --}}
        <div id="{{ $id }}_picker" 
             class="hidden absolute top-full left-0 mt-1 bg-white dark:bg-zinc-800 rounded-lg border border-zinc-200 dark:border-zinc-700 z-50 w-80">
            
            <div class="p-3">
                {{-- Search Box --}}
                <div class="mb-3">
                    <div class="relative">
                        <i class="fa-solid fa-search absolute left-2.5 top-1/2 transform -translate-y-1/2 text-zinc-400 text-xs"></i>
                        <input type="text" 
                               id="{{ $id }}_search" 
                               placeholder="Search..."
                               class="w-full pl-8 pr-3 py-1.5 border border-zinc-200 dark:border-zinc-700 rounded text-sm bg-zinc-50 dark:bg-zinc-900 text-zinc-900 dark:text-zinc-100 placeholder:text-zinc-400 focus:outline-none focus:border-[var(--color-accent)]"
                               onkeyup="window.filterIcons_{{ $id }}('{{ $id }}')">
                    </div>
                </div>

                {{-- Icon Grid (6 per row, compact) --}}
                <div class="grid grid-cols-6 gap-1 mb-3 max-h-48 overflow-y-auto" id="{{ $id }}_icon_grid">
                    @for($i = 0; $i < min($iconsPerPage, $totalIcons); $i++)
                        @php
                            $isSelected = old($name, $value) === $allIcons[$i];
                        @endphp
                        <button type="button"
                                onclick="window.selectIcon_{{ $id }}('{{ $id }}', '{{ $allIcons[$i] }}')"
                                class="icon-item flex items-center justify-center w-10 h-10 rounded {{ $isSelected ? 'bg-[var(--color-accent)] text-white' : 'text-zinc-500 dark:text-zinc-400 hover:bg-zinc-100 dark:hover:bg-zinc-700' }}"
                                data-icon="{{ $allIcons[$i] }}"
                                data-index="{{ $i }}"
                                title="{{ str_replace(['fa-solid fa-', 'fa-regular fa-', 'fa-brands fa-'], '', $allIcons[$i]) }}">
                            <i class="{{ $allIcons[$i] }} text-base"></i>
                        </button>
                    @endfor
                </div>

                {{-- Pagination Controls --}}
                <div class="flex items-center justify-between pt-2 border-t border-zinc-100 dark:border-zinc-700">
                    <button type="button" 
                            onclick="window.changePage_{{ $id }}('{{ $id }}', -1)"
                            class="px-2 py-1 text-xs text-zinc-500 hover:text-zinc-700 dark:hover:text-zinc-300 disabled:opacity-40"
                            id="{{ $id }}_prev_btn">
                        <i class="fa-solid fa-chevron-left mr-1"></i>Prev
                    </button>
                    <span class="text-xs text-zinc-400" id="{{ $id }}_page_info">1 / {{ $totalPages }}</span>
                    <button type="button" 
                            onclick="window.changePage_{{ $id }}('{{ $id }}', 1)"
                            class="px-2 py-1 text-xs text-zinc-500 hover:text-zinc-700 dark:hover:text-zinc-300 disabled:opacity-40"
                            id="{{ $id }}_next_btn">
                        Next<i class="fa-solid fa-chevron-right ml-1"></i>
                    </button>
                </div>
            </div>
        </div>

    </div>

    @error($name)
        <div class="text-xs leading-4 tracking-[0.4px] text-red-600 dark:text-red-400 flex items-center gap-1 mt-1.5">
            <i class="fas fa-exclamation-circle"></i>
            {{ $message }}
        </div>
    @enderror
    @if($helpText && !$errors->has($name))
        <div class="text-xs leading-4 tracking-[0.4px] text-zinc-600 dark:text-zinc-400 mt-1.5">{{ $helpText }}</div>
    @endif
</div>


@push('scripts')
<script>
(function() {
    const pickerId = '{{ $id }}';
    const iconData = @json($allIcons);
    const iconsPerPage = {{ $iconsPerPage }};
    const totalIcons = {{ $totalIcons }};
    const totalPages = {{ $totalPages }};
    
    let currentPage = 1;
    let filteredIcons = iconData;
    
    function toggleIconPicker(id) {
        const picker = document.getElementById(id + '_picker');
        const chevron = document.getElementById(id + '_chevron');
        if (picker) {
            const isHidden = picker.classList.toggle('hidden');
            if (chevron) {
                if (isHidden) {
                    chevron.classList.remove('rotate-180');
                } else {
                    chevron.classList.add('rotate-180');
                    renderIcons(id);
                }
            } else if (!isHidden) {
                renderIcons(id);
            }
        }
    }
    
    function renderIcons(id) {
        const iconGrid = document.getElementById(id + '_icon_grid');
        const pageInfo = document.getElementById(id + '_page_info');
        const countInfo = document.getElementById(id + '_count');
        const prevBtn = document.getElementById(id + '_prev_btn');
        const nextBtn = document.getElementById(id + '_next_btn');
        const input = document.getElementById(id);
        const selectedIcon = input ? input.value : '';
        
        if (!iconGrid) return;
        
        const startIndex = (currentPage - 1) * iconsPerPage;
        const endIndex = Math.min(startIndex + iconsPerPage, filteredIcons.length);
        const currentIcons = filteredIcons.slice(startIndex, endIndex);
        
        // Clear grid
        iconGrid.innerHTML = '';
        
        // Render icons
        currentIcons.forEach((icon) => {
            const isSelected = icon === selectedIcon;
            const button = document.createElement('button');
            button.type = 'button';
            const iconName = icon.replace(/^fa-(solid|regular|brands)\s+fa-/, '');
            button.className = `icon-item flex items-center justify-center w-10 h-10 rounded ${isSelected ? 'bg-[var(--color-accent)] text-white' : 'text-zinc-500 dark:text-zinc-400 hover:bg-zinc-100 dark:hover:bg-zinc-700'}`;
            button.setAttribute('onclick', `window.selectIcon_${pickerId}('${id}', '${icon.replace(/'/g, "\\'")}')`);
            button.setAttribute('data-icon', icon);
            button.setAttribute('title', iconName);
            button.innerHTML = `<i class="${icon} text-base"></i>`;
            iconGrid.appendChild(button);
        });
        
        // Update pagination info
        if (pageInfo) {
            const totalFilteredPages = Math.ceil(filteredIcons.length / iconsPerPage);
            pageInfo.textContent = `${currentPage} / ${totalFilteredPages || 1}`;
        }
        
        // Update count (moved to pagination section)
        if (countInfo) {
            const start = filteredIcons.length > 0 ? startIndex + 1 : 0;
            const end = endIndex;
            countInfo.textContent = `${start} - ${end} of ${filteredIcons.length}`;
        }
        
        // Update button states
        if (prevBtn) {
            prevBtn.disabled = currentPage <= 1;
            prevBtn.classList.toggle('opacity-50', currentPage <= 1);
            prevBtn.classList.toggle('cursor-not-allowed', currentPage <= 1);
        }
        
        if (nextBtn) {
            const totalFilteredPages = Math.ceil(filteredIcons.length / iconsPerPage);
            nextBtn.disabled = currentPage >= totalFilteredPages;
            nextBtn.classList.toggle('opacity-50', currentPage >= totalFilteredPages);
            nextBtn.classList.toggle('cursor-not-allowed', currentPage >= totalFilteredPages);
        }
    }
    
    function changePage(id, direction) {
        const totalFilteredPages = Math.ceil(filteredIcons.length / iconsPerPage);
        const newPage = currentPage + direction;
        
        if (newPage >= 1 && newPage <= totalFilteredPages) {
            currentPage = newPage;
            renderIcons(id);
        }
    }
    
    function updateTriggerButton(id) {
        const input = document.getElementById(id);
        const triggerIcon = document.getElementById(id + '_trigger_icon');
        const triggerText = document.getElementById(id + '_trigger_text');
        
        if (input && triggerIcon && triggerText) {
            const iconClass = input.value || 'fa-solid fa-question-circle';
            triggerIcon.className = iconClass;
            triggerText.textContent = input.value ? 'Change Icon' : 'Select Icon';
        }
    }
    
    function selectIcon(id, iconClass) {
        const input = document.getElementById(id);
        
        if (input) {
            input.value = iconClass;
            // Trigger change event
            input.dispatchEvent(new Event('change', { bubbles: true }));
        }
        
        // Update trigger button preview
        updateTriggerButton(id);
        
        // Re-render to update selected state
        renderIcons(id);
        
        // Close picker
        toggleIconPicker(id);
    }
    
    function filterIcons(id) {
        const searchInput = document.getElementById(id + '_search');
        const searchTerm = (searchInput ? searchInput.value : '').toLowerCase();
        
        if (searchTerm === '') {
            filteredIcons = iconData;
        } else {
            filteredIcons = iconData.filter(icon => {
                const iconName = icon.replace(/^fa-(solid|regular|brands)\s+fa-/, '').toLowerCase();
                return iconName.includes(searchTerm) || icon.toLowerCase().includes(searchTerm);
            });
        }
        
        // Reset to first page when filtering
        currentPage = 1;
        renderIcons(id);
    }
    
    // Expose functions globally with unique names
    window['toggleIconPicker_' + pickerId] = toggleIconPicker;
    window['changePage_' + pickerId] = changePage;
    window['selectIcon_' + pickerId] = selectIcon;
    window['filterIcons_' + pickerId] = filterIcons;
    
    // Initialize on page load
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', function() {
            renderIcons(pickerId);
            updateTriggerButton(pickerId);
        });
    } else {
        renderIcons(pickerId);
        updateTriggerButton(pickerId);
    }
    
    // Listen for external changes to the input (e.g., form reset)
    const input = document.getElementById(pickerId);
    if (input) {
        input.addEventListener('change', function() {
            updateTriggerButton(pickerId);
            renderIcons(pickerId);
        });
    }
    
    // Close dropdown when clicking outside
    document.addEventListener('click', function(event) {
        const picker = document.getElementById(pickerId + '_picker');
        const trigger = event.target.closest('button[onclick*="toggleIconPicker_' + pickerId + '"]');
        
        if (picker && !picker.contains(event.target) && !trigger && !picker.classList.contains('hidden')) {
            picker.classList.add('hidden');
            const chevron = document.getElementById(pickerId + '_chevron');
            if (chevron) {
                chevron.classList.remove('rotate-180');
            }
        }
    });
    
    // Close on ESC key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            const picker = document.getElementById(pickerId + '_picker');
            if (picker && !picker.classList.contains('hidden')) {
                picker.classList.add('hidden');
                const chevron = document.getElementById(pickerId + '_chevron');
                if (chevron) {
                    chevron.classList.remove('rotate-180');
                }
            }
        }
    });
})();
</script>
@endpush
