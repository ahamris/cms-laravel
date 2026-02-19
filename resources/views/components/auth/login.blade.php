@if($mode === 'white')
    {{-- White Form Style: Form 1/2 (left), Image 1/2 (right) --}}
    <div class="relative w-screen min-h-screen overflow-hidden bg-gray-50">
        <div class="grid grid-cols-1 lg:grid-cols-2 min-h-screen items-stretch">
            {{-- White Form Card (1/2) --}}
            <div class="lg:col-span-1 min-h-screen bg-white">
                <div class="p-6 sm:p-8 md:p-12 lg:p-16 xl:p-20 h-full flex flex-col justify-center max-w-xl mx-auto">
                    <div class="mb-10">
                        <img src="{{ $logoUrl }}" alt="Logo" class="h-10 sm:h-12 w-auto">
                    </div>
                    <h1 class="text-primary mb-2">{{ $pageTitle }}</h1>
                    <p class="text-primary font-medium mb-8">{{ $pageSubtitle }}</p>
                    <form method="POST" action="{{ route('admin.login.post') }}" class="space-y-5">
                        @csrf

                        <div>
                            <label for="email" class="block font-medium text-gray-700 mb-1">Email</label>
                            <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="email"
                                   class="block w-full rounded-md border border-gray-300 bg-white text-gray-900 placeholder-gray-400 px-3 py-2.5 focus:outline-none @error('email') border-red-500 focus:border-red-500 @enderror">
                            @error('email')
                            <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="password" class="block font-medium text-gray-700 mb-1">Password</label>
                            <input id="password" type="password" name="password" required autocomplete="current-password"
                                   class="block w-full rounded-md border border-gray-300 bg-white text-gray-900 placeholder-gray-400 px-3 py-2.5 focus:outline-none @error('password') border-red-500 focus:border-red-500 @enderror">
                            @error('password')
                            <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="flex items-center justify-between">
                            @if($enableRememberMe)
                                <div class="flex items-center">
                                    <input class="h-4 w-4 rounded border-gray-300 text-primary accent-primary" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                                    <label class="ml-2 block text-gray-700 font-medium" for="remember">Remember me</label>
                                </div>
                            @else
                                <div></div>
                            @endif

                            @if($enableForgotPassword && Route::has('admin.forgot-password'))
                                <a class="text-sm text-primary font-medium" href="{{ route('admin.forgot-password') }}">
                                    <span class="relative group/inner inline-block">
                                        Forgot your password?
                                        <span class="absolute -bottom-1 left-1/2 w-0 h-0.5 bg-primary transition-all duration-300 group-hover/inner:w-full group-hover/inner:left-0 transform -translate-x-1/2 group-hover/inner:translate-x-0"></span>
                                    </span>
                                </a>
                            @endif
                        </div>

                        <div class="!mt-8">
                            <button type="submit" class="w-full py-2.5 px-3 bg-primary text-white font-semibold rounded-md hover:bg-primary/90 focus:outline-none transition-all duration-300 cursor-pointer">
                                Log in
                            </button>
                        </div>
                    </form>
                    {{-- Footer Links --}}
                    <div class="mt-8 text-center">
                        @if($footerCopyright)
                            <p class="text-gray-600 text-sm">{!! $footerCopyright !!}</p>
                        @endif
                        @if(count($footerLinks) > 0)
                            <div class="mt-2 flex flex-wrap items-center justify-center gap-x-4 gap-y-1 text-xs">
                                @foreach($footerLinks as $link)
                                    @php
                                        $linkUrl = $link['link'] ?? '#';
                                        // If it's not already a full URL, make it relative to site root
                                        if (!str_starts_with($linkUrl, 'http://') && !str_starts_with($linkUrl, 'https://')) {
                                            $linkUrl = url($linkUrl);
                                        }
                                    @endphp
                                    <a href="{{ $linkUrl }}" 
                                       target="{{ $link['target'] ?? '_self' }}"
                                       class="text-primary/80 hover:text-primary">
                                        <span class="relative group/inner inline-block">
                                            {{ $link['title'] }}
                                            <span class="absolute -bottom-1 left-1/2 w-0 h-0.5 bg-primary transition-all duration-300 group-hover/inner:w-full group-hover/inner:left-0 transform -translate-x-1/2 group-hover/inner:translate-x-0"></span>
                                        </span>
                                    </a>
                                @endforeach
                            </div>
                        @endif
                    </div>
                </div>
            </div>
            {{-- Image Area (1/2) --}}
            <div class="hidden lg:block lg:col-span-1 min-h-screen">
                <img src="{{ $backgroundImageUrl }}" alt="Admin Panel" class="w-full h-full object-cover">
            </div>
        </div>
    </div>
@else
    {{-- Glass Form Style: Fullscreen background with glass form 1/3 --}}
    <div class="relative w-screen min-h-screen overflow-hidden">
        {{-- Fullscreen Background Image --}}
        <img src="{{ $backgroundImageUrl }}" alt="Admin Panel" class="absolute inset-0 w-full h-full object-cover">
        <div class="absolute inset-0 bg-black/40"></div>

        {{-- Foreground Grid --}}
        <div class="relative z-10 grid grid-cols-1 lg:grid-cols-2 min-h-screen items-stretch">
            {{-- Glass Form Card (1/2) --}}
            <div class="lg:col-span-1 min-h-screen">
                <div class="bg-black/5 backdrop-blur-lg shadow-xl p-6 sm:p-8 md:p-12 lg:p-16 xl:p-20 h-full flex flex-col justify-center max-w-xl mx-auto transition-all duration-300">
                    <div class="mb-10">
                        <img src="{{ $logoUrl }}" alt="Logo" class="h-10 sm:h-12 w-auto">
                    </div>
                    <h1 class="text-white/70 mb-2">{{ $pageTitle }}</h1>
                    <p class="text-white/70 font-medium mb-8">{{ $pageSubtitle }}</p>
                    <form method="POST" action="{{ route('admin.login.post') }}" class="space-y-5">
                        @csrf

                        <div>
                            <label for="email" class="block font-medium text-white/90 mb-1">Email</label>
                            <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="email"
                                   class="block w-full rounded-md border border-white/30 bg-white/10 text-white placeholder-white/70 px-3 py-2.5 focus:outline-none focus:bg-white/20 @error('email') border-red-500 focus:border-red-500 @enderror">
                            @error('email')
                            <p class="text-red-300 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="password" class="block font-medium text-white/90 mb-1">Password</label>
                            <input id="password" type="password" name="password" required autocomplete="current-password"
                                   class="block w-full rounded-md border border-white/30 bg-white/10 text-white placeholder-white/70 px-3 py-2.5 focus:outline-none focus:bg-white/20 @error('password') border-red-500 focus:border-red-500 @enderror">
                            @error('password')
                            <p class="text-red-300 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="flex items-center justify-between">
                            @if($enableRememberMe)
                                <div class="flex items-center">
                                    <input class="h-4 w-4 rounded border-white/30 bg-white/10 text-primary accent-primary" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                                    <label class="ml-2 block text-white/90 font-medium" for="remember">Remember me</label>
                                </div>
                            @else
                                <div></div>
                            @endif

                            @if($enableForgotPassword && Route::has('admin.forgot-password'))
                                <a class="text-sm text-white/70 hover:text-white font-medium" href="{{ route('admin.forgot-password') }}">
                                    <span class="relative group/inner inline-block">
                                        Forgot your password?
                                        <span class="absolute -bottom-1 left-1/2 w-0 h-0.5 bg-white transition-all duration-300 group-hover/inner:w-full group-hover/inner:left-0 transform -translate-x-1/2 group-hover/inner:translate-x-0"></span>
                                    </span>
                                </a>
                            @endif
                        </div>

                        <div class="!mt-8">
                            <button type="submit" class="w-full py-2.5 px-3 bg-primary text-white font-semibold rounded-md hover:bg-secondary focus:outline-none transition-all duration-300 cursor-pointer">
                                Log in
                            </button>
                        </div>
                    </form>
                    {{-- Footer Links --}}
                    <div class="mt-8 text-center">
                        @if($footerCopyright)
                            <p class="text-white/60">{!! $footerCopyright !!}</p>
                        @endif
                        @if(count($footerLinks) > 0)
                            <div class="mt-2 flex flex-wrap items-center justify-center gap-x-4 gap-y-1 text-xs">
                                @foreach($footerLinks as $link)
                                    @php
                                        $linkUrl = $link['link'] ?? '#';
                                        // If it's not already a full URL, make it relative to site root
                                        if (!str_starts_with($linkUrl, 'http://') && !str_starts_with($linkUrl, 'https://')) {
                                            $linkUrl = url($linkUrl);
                                        }
                                    @endphp
                                    <a href="{{ $linkUrl }}" 
                                       target="{{ $link['target'] ?? '_self' }}"
                                       class="text-white/70 hover:text-white">
                                        <span class="relative group/inner inline-block">
                                            {{ $link['title'] }}
                                            <span class="absolute -bottom-1 left-1/2 w-0 h-0.5 bg-white transition-all duration-300 group-hover/inner:w-full group-hover/inner:left-0 transform -translate-x-1/2 group-hover/inner:translate-x-0"></span>
                                        </span>
                                    </a>
                                @endforeach
                            </div>
                        @endif
                    </div>
                </div>
            </div>
            {{-- Empty space keeps 1/2 proportion on large screens --}}
            <div class="hidden lg:block lg:col-span-1"></div>
        </div>
    </div>
@endif