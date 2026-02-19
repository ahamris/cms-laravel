<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="antialiased scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? View::yieldContent('title', 'Admin Panel') }} - {{ config('app.name', 'Laravel') }}</title>
    
    {{-- Fonts --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Geist:wght@100..900&family=Outfit:wght@100..900&display=swap" rel="stylesheet">
    
    {{-- FontAwesome Pro Links --}}
    <link href="https://site-assets.fontawesome.com/releases/v6.7.2/css/all.css" rel="stylesheet"/>
    <link href="https://site-assets.fontawesome.com/releases/v6.7.2/css/brands.css" rel="stylesheet"/>
    
    {{-- Dynamic Theme Variables - Must be before @vite to override default values --}}
    @php
        use App\Helpers\ThemeHelper;
        use App\Models\Admin\AdminThemeSetting;
        echo ThemeHelper::getThemeCss();
        $themeSettings = AdminThemeSetting::getSettings();
        $accentColor = $themeSettings->accent_color;
        $themeAccentClass = 'theme-accent-' . $accentColor;
    @endphp

    {{-- FOUC Prevention - Direct JS import --}}
    <script src="{{ asset('assets/js/fouc-prevention.js') }}"></script>
    {{-- Styles / Scripts --}}
    @vite(['resources/css/admin.css', 'resources/js/admin.js', 'resources/js/tiptap.js'])
    @stack('vite')
    @livewireStyles
    @stack('styles')
    @isset($styles){!! $styles !!}@endisset
    
    {{-- Flash Messages Data --}}
    @php
        use App\Helpers\FlashHelper;
        $flashMessages = FlashHelper::getAll();
    @endphp
    @if(!empty($flashMessages))
        <script>
            window.flashMessages = @json($flashMessages);
        </script>
    @endif
</head>
<body
    class="min-h-screen {{ $themeAccentClass }} transition-colors font-geist text-zinc-900 dark:text-zinc-100 text-sm leading-5 tracking-[0.25px] bg-zinc-50 dark:bg-zinc-900"
    x-data
    x-init="$store.darkMode.init()"
    @notify.window="toastManager.show($event.detail.type || 'info', $event.detail.message || 'Notification', { title: $event.detail.title || null, icon: $event.detail.icon || null })"
>
    
    <div class="flex h-screen">
        <!-- Sidebar -->
        @livewire('admin.sidebar')

        <!-- Main Content Area -->
        <div class="flex-1 flex flex-col overflow-hidden">
            <!-- Header -->
            <x-partials.header />

            <!-- AI Service Warning Banner -->
            @if(isset($aiServiceWarning) && $aiServiceWarning)
                @php
                    $aiBannerRemindOptions = ['8h' => 'In 8 hours', '1d' => 'In 1 day', '1w' => 'In 1 week', 'never' => 'Never'];
                @endphp
                <div class="bg-yellow-50 dark:bg-yellow-900/20 border-b border-yellow-200 dark:border-yellow-800 px-6 py-3"
                     x-data="aiBannerRemind()"
                     x-init="init()"
                     x-show="bannerVisible"
                     x-cloak
                     x-transition:enter="transition ease-out duration-200"
                     x-transition:enter-start="opacity-0 -translate-y-2"
                     x-transition:enter-end="opacity-100 translate-y-0"
                     x-transition:leave="transition ease-in duration-150"
                     x-transition:leave-start="opacity-100 translate-y-0"
                     x-transition:leave-end="opacity-0 -translate-y-2">
                    <div class="flex items-center justify-between flex-wrap gap-3">
                        <div class="flex items-center space-x-3 min-w-0">
                            <i class="fa-solid fa-exclamation-triangle text-yellow-600 dark:text-yellow-400 flex-shrink-0"></i>
                            <p class="text-sm text-yellow-800 dark:text-yellow-200">
                                <strong>AI Service Not Configured:</strong> {{ $aiServiceWarning }}
                            </p>
                        </div>
                        <div class="flex items-center gap-2 flex-shrink-0">
                            <x-ui.button variant="outline-warning" @click="openModal()">
                                Remind me later
                            </x-ui.button>
                            <x-ui.button :href="route('admin.settings.ai.index')" variant="primary">
                                Configure Now
                            </x-ui.button>
                        </div>
                    </div>

                    <x-ui.modal alpineShow="modalOpen" title="Remind me later" size="sm" closeOnBackdropClick="true">
                        <p class="text-sm text-zinc-500 dark:text-zinc-400 mb-4">When do you want to see this banner again?</p>
                        <x-ui.select
                            label="When"
                            name="remind_option"
                            :options="$aiBannerRemindOptions"
                            placeholder="Select..."
                            value=""
                            x-model="remindOption"
                        />
                        <x-slot:footer>
                            <x-ui.button variant="default" @click="closeModal()">Cancel</x-ui.button>
                            <x-ui.button variant="primary" @click="confirmRemind()" ::disabled="!remindOption">Apply</x-ui.button>
                        </x-slot:footer>
                    </x-ui.modal>
                </div>
            @endif

            <!-- Main Content -->
            <main class="flex-1 overflow-x-hidden overflow-y-auto">
                <div class="px-6 py-8">
                    @isset($slot)
                        {{ $slot }}
                    @else
                        @yield('content')
                    @endisset
                </div>
            </main>
        </div>
    </div>
    
    {{-- Search Component --}}
    @livewire('admin.search')
    
    @livewireScripts
    @stack('scripts')
    @isset($scripts){!! $scripts !!}@endisset
</body>
</html>
