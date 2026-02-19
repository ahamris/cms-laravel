@extends('front.layouts.app')

@section('title', 'Login - OPUB User Dashboard')

@section('content')

{{-- Hero Section --}}
<section class="relative bg-cover bg-center h-[200px] flex items-center text-white" style="background-image: url('{{ asset('frontend/images/dashboard.jpg') }}')">
    <div class="absolute inset-0 bg-black/80"></div> {{-- Overlay for readability --}}
    <div class="relative container mx-auto px-6 flex justify-between items-center">
        <div class="flex flex-col items-start text-left">
            <h1 class="text-4xl lg:text-5xl font-bold leading-tight max-w-4xl pb-4">
                Welcome Back
            </h1>
            <div class="text-sm text-white text-shadow-amber-200">
                <span>Enter your credentials to login to your account</span>
            </div>
        </div>
    </div>
</section>

{{-- Main Content --}}
<section class="py-16 bg-gray-50">
    <div class="container mx-auto px-6">
        <div class="max-w-2xl mx-auto">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
                
                {{-- Login Form --}}
                <div class="lg:col-span-12">
                    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                        <div class="bg-primary px-6 py-4">
                            <h1 class="text-2xl font-bold text-white">Sign In</h1>
                            <p class="text-white/90 text-sm mt-1">Access your personal dashboard</p>
                        </div>

                        <div class="p-8">
                            <form method="POST" action="{{ route('dashboard.auth.login.post') }}" class="space-y-6">
                                @csrf

                                <div>
                                    <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email Address</label>
                                    <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="email"
                                           class="block w-full rounded-lg border border-gray-200 px-4 py-3 focus:outline-none focus:border-primary/50 focus:ring-2 focus:ring-primary/20 @error('email') border-red-500 @enderror">
                                    @error('email')
                                    <p class="text-red-600 text-sm mt-2">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="password" class="block text-sm font-medium text-gray-700 mb-2">Password</label>
                                    <input id="password" type="password" name="password" required autocomplete="current-password"
                                           class="block w-full rounded-lg border border-gray-200 px-4 py-3 focus:outline-none focus:border-primary/50 focus:ring-2 focus:ring-primary/20 @error('password') border-red-500 @enderror">
                                    @error('password')
                                    <p class="text-red-600 text-sm mt-2">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div class="flex items-center justify-between">
                                    <div class="flex items-center">
                                        <input class="h-4 w-4 rounded border-gray-300 text-primary focus:ring-primary/20" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                                        <label class="ml-2 block text-sm text-gray-700" for="remember">Remember Me</label>
                                    </div>

                                    @if (Route::has('dashboard.auth.password.request'))
                                        <a class="text-sm text-primary hover:text-primary/80 font-medium transition-colors" href="{{ route('dashboard.auth.password.request') }}">Forgot Password?</a>
                                    @endif
                                </div>

                                <div class="pt-4">
                                    <button type="submit" class="w-full bg-primary text-white font-semibold py-3 px-6 rounded-lg hover:bg-primary/90 focus:outline-none focus:ring-2 focus:ring-primary/20 transition-all duration-200">
                                        Sign In
                                    </button>
                                </div>

                                <div class="text-center pt-4 border-t border-gray-200">
                                    <p class="text-sm text-gray-600">
                                        Don't have an account yet?
                                        <a href="{{ route('dashboard.auth.register') }}" class="font-medium text-primary hover:text-primary/80 transition-colors">Create one now</a>.
                                    </p>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

@endsection