@extends('front.layouts.app')

@section('title', 'Register - OPUB User Dashboard')

@section('content')

{{-- Hero Section --}}
<section class="relative bg-cover bg-center h-[200px] flex items-center text-white" style="background-image: url('{{ asset('frontend/images/dashboard.jpg') }}')">
    <div class="absolute inset-0 bg-black/80"></div> {{-- Overlay for readability --}}
    <div class="relative container mx-auto px-6 flex justify-between items-center">
        <div class="flex flex-col items-start text-left">
            <h1 class="text-4xl lg:text-5xl font-bold leading-tight max-w-4xl pb-4">
                Create Account
            </h1>
            <div class="text-sm text-white text-shadow-amber-200">
                <span>Join us and access your personal dashboard</span>
            </div>
        </div>
    </div>
</section>

{{-- Main Content --}}
<section class="py-16 bg-gray-50">
    <div class="container mx-auto px-6">
        <div class="max-w-2xl mx-auto">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
                
                {{-- Registration Form --}}
                <div class="lg:col-span-12">
                    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                        <div class="bg-primary px-6 py-4">
                            <h1 class="text-2xl font-bold text-white">Create New Account</h1>
                            <p class="text-white/90 text-sm mt-1">Join our platform and start your journey</p>
                        </div>

                        <div class="p-8">
                            <form method="POST" action="{{ route('dashboard.auth.register.post') }}" class="space-y-6">
                                @csrf

                                <div>
                                    <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Full Name</label>
                                    <input id="name" type="text" name="name" value="{{ old('name') }}" required autofocus autocomplete="name"
                                           class="block w-full rounded-lg border border-gray-200 px-4 py-3 focus:outline-none focus:border-primary/50 focus:ring-2 focus:ring-primary/20 @error('name') border-red-500 @enderror">
                                    @error('name')
                                    <p class="text-red-600 text-sm mt-2">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email Address</label>
                                    <input id="email" type="email" name="email" value="{{ old('email') }}" required autocomplete="email"
                                           class="block w-full rounded-lg border border-gray-200 px-4 py-3 focus:outline-none focus:border-primary/50 focus:ring-2 focus:ring-primary/20 @error('email') border-red-500 @enderror">
                                    @error('email')
                                    <p class="text-red-600 text-sm mt-2">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="password" class="block text-sm font-medium text-gray-700 mb-2">Password</label>
                                    <input id="password" type="password" name="password" required autocomplete="new-password"
                                           class="block w-full rounded-lg border border-gray-200 px-4 py-3 focus:outline-none focus:border-primary/50 focus:ring-2 focus:ring-primary/20 @error('password') border-red-500 @enderror">
                                    @error('password')
                                    <p class="text-red-600 text-sm mt-2">{{ $message }}</p>
                                    @enderror
                                    <p class="text-xs text-gray-500 mt-2">Must be at least 8 characters long</p>
                                </div>

                                <div>
                                    <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-2">Confirm Password</label>
                                    <input id="password_confirmation" type="password" name="password_confirmation" required autocomplete="new-password"
                                           class="block w-full rounded-lg border border-gray-200 px-4 py-3 focus:outline-none focus:border-primary/50 focus:ring-2 focus:ring-primary/20">
                                </div>

                                <div class="flex items-start">
                                    <input class="h-4 w-4 rounded border-gray-300 text-primary focus:ring-primary/20 mt-1" type="checkbox" name="terms" id="terms" required>
                                    <label class="ml-3 block text-sm text-gray-700" for="terms">
                                        I agree to the 
                                        <a href="#" class="text-primary hover:text-primary/80 transition-colors">Terms of Service</a>
                                        and 
                                        <a href="#" class="text-primary hover:text-primary/80 transition-colors">Privacy Policy</a>
                                    </label>
                                </div>

                                <div class="pt-4">
                                    <button type="submit" class="w-full bg-primary text-white font-semibold py-3 px-6 rounded-lg hover:bg-primary/90 focus:outline-none focus:ring-2 focus:ring-primary/20 transition-all duration-200">
                                        Create Account
                                    </button>
                                </div>

                                <div class="text-center pt-4 border-t border-gray-200">
                                    <p class="text-sm text-gray-600">
                                        Already have an account?
                                        <a href="{{ route('dashboard.auth.login') }}" class="font-medium text-primary hover:text-primary/80 transition-colors">Sign in here</a>.
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