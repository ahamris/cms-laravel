@props(['page' => null])

@php
    $footerLayoutType = \App\Models\Setting::getValue('site_footer_layout_type');

    $pageObj = $page;
    if (!$pageObj && request()->route('page')) {
        $pageObj = request()->route('page');
    } elseif (!$pageObj && isset($page) && is_object($page)) {
        $pageObj = $page;
    } elseif (!$pageObj && request()->routeIs('page.show')) {
        $routeParams = request()->route()->parameters();
        $pageObj = $routeParams['page'] ?? null;
    }

    if (($footerLayoutType === null || $footerLayoutType === '') && $pageObj && !empty($pageObj->layout_type)) {
        $footerLayoutType = $pageObj->layout_type;
    }

    $useCustomFooter = false;
    $footerBladeFile = null;
@endphp

@if($useCustomFooter && $footerBladeFile)
    <div class="footer-wrapper w-full" data-footer-layout-type="{{ $footerLayoutType ?? '' }}">
        @include($footerBladeFile, ['footerLinks' => $footerLinks ?? []])
    </div>
@else
    <footer class="bg-primary py-10 footer-wrapper w-full" data-footer-layout-type="{{ $footerLayoutType ?? '' }}">
        <div class="max-w-container mx-auto my-10 px-4 sm:px-6 lg:px-8">
            <!-- Top Section - 4 Columns -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">

                @if(isset($footerLinks) && $footerLinks->count() > 0)
                    {{-- Dynamic footer links available --}}
                    @for($column = 1; $column <= 4; $column++)
                        @if(isset($footerLinks[$column]) && $footerLinks[$column]->count() > 0)
                            <div>
                                <h2 class="text-lg font-semibold font-outfit text-white uppercase mb-4">
                                    @switch($column)
                                        @case(1) {{ get_setting('footer_column_1_title', 'Services & Ondersteuning') }} @break
                                        @case(2) {{ get_setting('footer_column_2_title', 'Informatie') }} @break
                                        @case(3) {{ get_setting('footer_column_3_title', 'Juridisch & Beleid') }} @break
                                        @case(4) {{ get_setting('footer_column_4_title', 'Verbinden & Volgen') }} @break
                                    @endswitch
                                </h2>
                                @if($column == 4)
                                    <div class="mb-6">
                                        <x-front.social-links />
                                    </div>
                                @endif
                                <ul class="list-none space-y-3">
                                    @foreach($footerLinks[$column] as $link)
                                        <li>
                                            <a href="{{ $link->url }}" class="text-white hover:text-secondary transition-colors duration-200">
                                                {{ $link->title }}
                                            </a>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                    @endfor
                    {{-- Reviews Column
                    <div class="flex flex-col px-12 py-6 border-l border-primary/20 text-white justify-center">
                        <h3 class="text-white mb-4">Reviews</h3>

                        <!-- Rating Score -->
                        <div class="mb-4">
                            <div class="text-3xl font-bold">
                                <span class="text-4xl">4.2</span>
                                <span class="text-xl">/ 5</span>
                            </div>
                        </div>

                        <!-- Star Rating -->
                        <div class="flex items-center mb-4">
                            <div class="flex space-x-1">
                                <!-- Filled Stars -->
                                <i class="fas fa-star text-yellow-400"></i>
                                <i class="fas fa-star text-yellow-400"></i>
                                <i class="fas fa-star text-yellow-400"></i>
                                <i class="fas fa-star text-yellow-400"></i>
                                <!-- Partial Star (Half Star) -->
                                <i class="fas fa-star-half-alt text-yellow-400"></i>
                            </div>
                        </div>

                        <!-- Review Count -->
                        <p>868 beoordelingen</p>
                    </div>
                    --}}
                @else
                @endif
            </div>
            {{-- NLDS: mandatory footer links (Toegankelijkheid, Privacy, Cookies, Contact) --}}
            <div class="flex flex-wrap justify-center gap-6 text-sm text-white/90 mt-8 pt-8 border-t border-white/20" role="navigation" aria-label="Juridisch en contact">
                <a href="{{ url('/legal/toegankelijkheid') }}" class="hover:text-white focus:outline-none focus:underline">Toegankelijkheid</a>
                <a href="{{ url('/legal/privacy') }}" class="hover:text-white focus:outline-none focus:underline">Privacy</a>
                <a href="{{ url('/legal/cookies') }}" class="hover:text-white focus:outline-none focus:underline">Cookies</a>
                <a href="{{ route('contact') }}" class="hover:text-white focus:outline-none focus:underline">Contact</a>
            </div>
            @if(get_setting('copyright_footer'))
                <div class="flex items-start">
                    <p class="text-center text-white mt-10">&copy; {{ now()->year }} {{ get_setting('copyright_footer') }}</p>
                </div>
            @endif
        </div>
    </footer>
@endif

@if($footerLayoutType)
    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const layoutType = @json($footerLayoutType);
                const footerWrapper = document.querySelector('.footer-wrapper[data-footer-layout-type]');

                if (!footerWrapper) return;

                // Find the container element (footer, div with max-w-*, or first child)
                // For custom footer, it's the included component (usually a footer with div inside)
                // For default footer, it's the div inside footer
                let container = null;

                if (footerWrapper.tagName === 'FOOTER') {
                    // Default footer - find the div inside
                    container = footerWrapper.querySelector('div');
                } else {
                    // Custom footer - find footer > div or first element with container classes
                    const footerElement = footerWrapper.querySelector('footer');
                    if (footerElement) {
                        container = footerElement.querySelector('div[class*="max-w"], div[class*="container"], div');
                        if (!container) {
                            container = footerElement.firstElementChild;
                        }
                    } else {
                        container = footerWrapper.firstElementChild;
                    }
                }

                if (!container) return;

                const classes = Array.from(container.classList);

                // Remove container classes (with and without responsive prefixes)
                classes.forEach(function(cls) {
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