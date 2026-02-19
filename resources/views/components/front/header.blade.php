@props(['page' => null])

@php
    $headerComponentId = \App\Models\Setting::getValue('site_header_component_id');
    $headerSticky = \App\Models\Setting::getValue('site_header_sticky', false);
    $headerLayoutType = \App\Models\Setting::getValue('site_header_layout_type');

    // Get page from component prop, route, or view variable
    $pageObj = $page; // Use prop first
    if (!$pageObj) {
        if (request()->route('page')) {
            $pageObj = request()->route('page');
        } elseif (isset($page) && is_object($page)) {
            $pageObj = $page;
        } elseif (request()->routeIs('page.show')) {
            $routeParams = request()->route()->parameters();
            if (isset($routeParams['page'])) {
                $pageObj = $routeParams['page'];
            }
        }
    }

    // Check if page has custom header (design_type = 'custom' and header_block is set)
    // Only for static pages, not showcase pages
    $usePageCustomHeader = false;
    $pageHeaderComponent = null;
    if ($pageObj) {
        // Check if page is showcase - use method if available, otherwise check page_type property
        $isShowcase = false;
        if (method_exists($pageObj, 'isShowcase')) {
            $isShowcase = $pageObj->isShowcase();
        } elseif (isset($pageObj->page_type)) {
            $isShowcase = $pageObj->page_type === 'showcase';
        }

        if (!$isShowcase && isset($pageObj->design_type) && $pageObj->design_type === 'custom' && !empty($pageObj->header_block)) {
            $componentService = app(\App\Services\TailwindPlusComponentService::class);
            $pageHeaderComponent = $componentService->getComponentByPath($pageObj->header_block);
            if ($pageHeaderComponent) {
                $usePageCustomHeader = true;
                $headerComponentId = $pageHeaderComponent->id;
            }
        }
    }

    // If header layout type is empty/null, use page's layout_type (for both showcase and static pages)
    if (($headerLayoutType === null || $headerLayoutType === '') && $pageObj && !empty($pageObj->layout_type)) {
        $headerLayoutType = $pageObj->layout_type;
    }

    $useCustomHeader = !empty($headerComponentId);
    $headerBladeFile = null;

    if ($useCustomHeader) {
        // Use page custom header component if available, otherwise use site setting
        $headerComponent = $pageHeaderComponent ?? \App\Models\TailwindPlus::find($headerComponentId);
        if ($headerComponent) {
            // Component name to blade file mapping
            $componentNameMapping = [
                'Constrained' => 'constrained',
                'Full width' => 'full_width',
                'With call-to-action' => 'with_call_to_action',
                'With centered logo' => 'with_centered_logo',
                'With full width flyout menu' => 'with_full_width_flyout_menu',
                'With icons in mobile menu' => 'with_icons_in_mobile_menu',
                'With left-aligned nav' => 'with_left_aligned_nav',
                'With multiple flyout menus' => 'with_multiple_flyout_menus',
                'With right-aligned nav' => 'with_right_aligned_nav',
                'With stacked flyout menu' => 'with_stacked_flyout_menu',
            ];

            $componentName = $headerComponent->component_name;
            if (isset($componentNameMapping[$componentName])) {
                $headerBladeFile = 'components.front.headers.' . $componentNameMapping[$componentName];
            }
        }

        if (!$headerBladeFile) {
            $useCustomHeader = false;
        }
    }
@endphp

@if($useCustomHeader && $headerBladeFile)
    <div class="header-wrapper w-full {{ $headerSticky ? 'sticky top-0 z-50 shadow-xs' : '' }}"
        data-header-layout-type="{{ $headerLayoutType ?? '' }}">
        @include($headerBladeFile, ['megaMenuData' => $megaMenuData ?? [], 'isSticky' => $headerSticky])
    </div>
@else
    <header
        class="{{ $headerSticky ? 'sticky top-0 z-50' : '' }} transition-all duration-300 bg-white border-b border-white header-wrapper w-full"
        data-header-layout-type="{{ $headerLayoutType ?? '' }}" x-data="{ mobileMenuOpen: false, loginModalOpen: false, menuOpenLabel: '{{ addslashes(__('frontend.header.menu_open')) }}', menuCloseLabel: '{{ addslashes(__('frontend.header.menu_close')) }}' }"
        x-effect="document.body.style.overflow = mobileMenuOpen ? 'hidden' : 'auto'">
        <div class="max-w-container mx-auto px-4 sm:px-0">
            <div class="flex items-center justify-between min-h-14 sm:min-h-16 lg:h-18 gap-4">

                <div class="flex flex-row items-center gap-4 sm:gap-6 lg:gap-10 min-w-0 flex-1">
                    <!-- Logo -->
                    <div class="flex items-center flex-shrink-0">
                        <a href="{{ route('home') }}" class="flex items-center">
                            <img src="{{ get_image(get_setting('site_logo'), asset('front/images/logo.svg')) }}"
                                alt="{{ get_setting('site_name', 'OpenPublicatie') }}" class="h-7 sm:h-8 lg:h-[34px] w-auto max-h-10 object-contain" width="200" height="50">
                        </a>
                    </div>

                    <!-- Desktop Navigation -->
                    <nav class="hidden lg:flex items-center gap-6 xl:gap-8 flex-shrink-0" aria-label="{{ __('frontend.header.nav_label') }}">
                        @if(!empty($megaMenuData))
                            @foreach($megaMenuData as $menuItem)
                                <x-front.menu-item :item="$menuItem" />
                            @endforeach
                        @endif
                    </nav>
                </div>

                <!-- Desktop Right Section -->
                <div class="hidden lg:flex items-center gap-3 xl:gap-4 flex-shrink-0">

                    <x-front.header-actions />
                </div>

                <!-- Mobile Menu Button -->
                <button type="button"
                    @click="mobileMenuOpen = !mobileMenuOpen"
                    :aria-label="mobileMenuOpen ? menuCloseLabel : menuOpenLabel"
                    class="lg:hidden p-2 rounded-md text-gray-700 hover:text-gray-900 hover:bg-gray-100 transition-colors duration-200">
                    <i class="fas fa-bars text-lg" x-show="!mobileMenuOpen"></i>
                    <i class="fas fa-times text-lg" x-show="mobileMenuOpen"></i>
                </button>
            </div>

            <!-- Mobile Menu Overlay -->
            <div x-show="mobileMenuOpen" @click="mobileMenuOpen = false"
                x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0"
                x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-200"
                x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
                class="fixed inset-0 bg-black/50 z-40 lg:hidden" x-cloak>
            </div>

            <!-- Mobile Menu Sidebar -->
            <div x-show="mobileMenuOpen" x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="transform -translate-x-full" x-transition:enter-end="transform translate-x-0"
                x-transition:leave="transition ease-in duration-200" x-transition:leave-start="transform translate-x-0"
                x-transition:leave-end="transform -translate-x-full"
                class="fixed top-0 left-0 h-full w-[min(20rem,85vw)] max-w-sm bg-white shadow-xl z-50 lg:hidden" x-cloak>
                <div class="flex flex-col h-full">
                    <!-- Mobile Menu Header -->
                    <div class="flex items-center justify-between p-4 border-b border-gray-200">
                        <div class="flex items-center min-w-0 flex-1">
                            <a href="{{ route('home') }}" class="flex items-center" @click="mobileMenuOpen = false">
                                @if(get_setting('site_logo'))
                                    <img src="{{ get_image(get_setting('site_logo'), asset('front/images/logo.svg')) }}"
                                        alt="{{ get_setting('site_name', 'OpenPublicatie') }}"
                                        class="max-w-[130px] h-8 sm:h-9 object-contain object-left" width="200" height="50">
                                @else
                                    <div class="flex items-center space-x-2">
                                        <div class="w-8 h-8 bg-primary rounded-lg flex items-center justify-center">
                                            <div class="w-4 h-4 bg-white rounded-sm"></div>
                                        </div>
                                        <span class="text-lg font-semibold text-gray-800">
                                            {{ get_setting('site_name', 'OpenPublicatie') }}
                                        </span>
                                    </div>
                                @endif
                            </a>
                        </div>
                        <button @click="mobileMenuOpen = false"
                            class="p-2 rounded-md text-gray-400 hover:text-gray-600 hover:bg-gray-100">
                            <i class="fas fa-times text-lg"></i>
                        </button>
                    </div>

                    <!-- Mobile Menu Content -->
                    <div class="flex-1 overflow-y-auto">
                        <!-- Main Menu -->
                        <div class="px-3 py-3 space-y-0">
                            <!-- Navigation Links -->
                            @if(!empty($megaMenuData))
                                @foreach($megaMenuData as $menuItem)
                                    <x-front.menu-item :item="$menuItem" :is-mobile="true" />
                                @endforeach
                            @endif
                        </div>

                    </div>

                    <!-- Mobile Language Section - Fixed at Bottom -->
                    <div class="border-t border-gray-200 p-4 bg-gray-50">
                        <x-front.header-actions :mobile="true" />
                    </div>
                </div>
            </div>
        </div>

        <!-- Login Modal -->
        <div x-show="loginModalOpen" @click="loginModalOpen = false" x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
            x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0" class="fixed inset-0 bg-black/50 z-50 flex items-center justify-center p-4"
            x-cloak>

            <!-- Modal Content -->
            <div @click.stop x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
                x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100 scale-100"
                x-transition:leave-end="opacity-0 scale-95" class="bg-white rounded-lg shadow-xl w-full max-w-md">

                <!-- Modal Header -->
                <div class="flex items-center justify-between p-6 border-b border-gray-200">
                    <div class="text-xl font-bold text-gray-900">{{ __('frontend.auth.login_title') }}</div>
                    <button type="button" @click="loginModalOpen = false"
                        class="p-2 rounded-md text-gray-400 hover:text-gray-600 hover:bg-gray-100"
                        aria-label="{{ __('frontend.gdpr.back') }}">
                        <i class="fas fa-times text-lg"></i>
                    </button>
                </div>

                <!-- Modal Body -->
                <div class="p-6" x-data="{ showPassword: false }">
                    <!-- Social Login Buttons -->
                    <div class="space-y-3 mb-6">
                        <button type="button"
                            class="w-full flex items-center justify-start space-x-3 px-4 py-3 border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors duration-200">
                            <img src="https://developers.google.com/identity/images/g-logo.png" alt="Google"
                                class="w-5 h-5">
                            <span class="text-gray-700 font-medium">{{ __('frontend.auth.continue_google') }}</span>
                        </button>

                        <button type="button"
                            class="w-full flex items-center justify-start space-x-3 px-4 py-3 border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors duration-200">
                            <i class="fab fa-apple text-gray-700 text-lg"></i>
                            <span class="text-gray-700 font-medium">{{ __('frontend.auth.continue_apple') }}</span>
                        </button>

                        <button type="button"
                            class="w-full flex items-center justify-start space-x-3 px-4 py-3 border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors duration-200">
                            <i class="fab fa-microsoft text-blue-600 text-lg"></i>
                            <span class="text-gray-700 font-medium">{{ __('frontend.auth.continue_microsoft') }}</span>
                        </button>
                    </div>

                    <!-- Divider -->
                    <div class="relative mb-6">
                        <div class="absolute inset-0 flex items-center">
                            <div class="w-full border-t border-gray-300"></div>
                        </div>
                        <div class="relative flex justify-center text-sm">
                            <span class="px-2 bg-white text-gray-500">{{ __('frontend.auth.or_login_email') }}</span>
                        </div>
                    </div>

                    <!-- Email Form -->
                    <form class="space-y-4">
                        <div>
                            <label for="modal-email" class="block text-sm font-medium text-gray-700 mb-1">{{ __('frontend.auth.email') }}</label>
                            <input type="email" id="modal-email" placeholder="{{ __('frontend.auth.email') }}"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-primary transition-colors duration-200">
                        </div>

                        <div>
                            <label for="modal-password"
                                class="block text-sm font-medium text-gray-700 mb-1">{{ __('frontend.auth.password') }}</label>
                            <div class="relative">
                                <input :type="showPassword ? 'text' : 'password'" id="modal-password" placeholder="{{ __('frontend.auth.password') }}"
                                    class="w-full px-3 py-2 pr-10 border border-gray-300 rounded-lg focus:outline-none focus:border-primary transition-colors duration-200">
                                <button type="button" @click="showPassword = !showPassword"
                                    class="absolute inset-y-0 right-0 pr-3 flex items-center">
                                    <i :class="showPassword ? 'fas fa-eye-slash' : 'fas fa-eye'"
                                        class="text-gray-400 hover:text-gray-600"></i>
                                </button>
                            </div>
                        </div>

                        <button type="submit"
                            class="w-full bg-primary hover:bg-primary/90 text-white font-semibold py-3 px-4 rounded-lg transition-colors duration-200">
                            {{ __('frontend.auth.log_in') }}
                        </button>
                    </form>

                    <!-- Additional Links -->
                    <div class="mt-4 space-y-2">
                        <a href="#"
                            class="block text-sm text-primary hover:text-primary/80 transition-colors duration-200">{{ __('frontend.auth.forgot_password') }}</a>
                        <a href="{{ route('trial') }}"
                            class="block text-sm text-primary hover:text-primary/80 transition-colors duration-200">{{ __('frontend.auth.no_account_trial') }}</a>
                    </div>
                </div>
            </div>
        </div>
    </header>
@endif

@if($headerLayoutType)
    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                const layoutType = @json($headerLayoutType);
                const headerWrapper = document.querySelector('.header-wrapper[data-header-layout-type]');

                if (!headerWrapper) return;

                // Find the container element (nav, div with max-w-*, or first child)
                // For custom header, it's the included component (usually a header with nav inside)
                // For default header, it's the div inside header
                let container = null;

                if (headerWrapper.tagName === 'HEADER') {
                    // Default header - find the div or nav inside
                    container = headerWrapper.querySelector('div, nav');
                } else {
                    // Custom header - find header > nav or first element with container classes
                    const headerElement = headerWrapper.querySelector('header');
                    if (headerElement) {
                        container = headerElement.querySelector('nav, div[class*="max-w"], div[class*="container"]');
                        if (!container) {
                            container = headerElement.firstElementChild;
                        }
                    } else {
                        container = headerWrapper.firstElementChild;
                    }
                }

                if (!container) return;

                const classes = Array.from(container.classList);

                // Remove container classes (with and without responsive prefixes)
                classes.forEach(function (cls) {
                    // Remove container class
                    if (cls === 'container' || cls.endsWith(':container')) {
                        container.classList.remove(cls);
                    }

                    // Remove max-width classes (with and without responsive prefixes)
                    if (cls.startsWith('max-w-') || cls.includes(':max-w-')) {
                        container.classList.remove(cls);
                    }

                    // Remove mx-auto classes (with and without responsive prefixes)
                    if (cls === 'mx-auto' || cls.endsWith(':mx-auto')) {
                        if (layoutType !== 'container') {
                            container.classList.remove(cls);
                        }
                    }

                    // Remove horizontal padding classes (px-*, pl-*, pr-*)
                    if (cls.startsWith('px-') || cls.startsWith('pl-') || cls.startsWith('pr-') ||
                        cls.includes(':px-') || cls.includes(':pl-') || cls.includes(':pr-')) {
                        container.classList.remove(cls);
                    }
                });

                // Apply new layout type to the container
                if (layoutType === 'full-width') {
                    // Full width - ensure full width
                    if (!Array.from(container.classList).some(cls => cls === 'w-full' || cls.endsWith(':w-full'))) {
                        container.classList.add('w-full');
                    }
                } else if (layoutType === 'container') {
                    // Container - add container and mx-auto if not already present
                    if (!container.classList.contains('container') && !Array.from(container.classList).some(cls => cls.endsWith(':container'))) {
                        container.classList.add('container', 'mx-auto');
                    }
                } else if (layoutType.startsWith('max-w-')) {
                    // Specific max-width - add max-width and mx-auto if not already present
                    const maxWidthClass = layoutType;
                    if (!Array.from(container.classList).some(cls => cls === maxWidthClass || cls.endsWith(':' + maxWidthClass))) {
                        container.classList.add(maxWidthClass, 'mx-auto');
                    }
                }
            });
        </script>
    @endpush
@endif
