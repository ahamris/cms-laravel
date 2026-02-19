@extends('front.layouts.app')

@section('title', 'Reset Password - OPUB User Dashboard')

@section('content')

{{-- Hero Section --}}
<section class="relative bg-cover bg-center h-[200px] flex items-center text-white" style="background-image: url('{{ asset('frontend/images/dashboard.jpg') }}')">
    <div class="absolute inset-0 bg-black/80"></div> {{-- Overlay for readability --}}
    <div class="relative container mx-auto px-6 flex justify-between items-center">
        <div class="flex flex-col items-start text-left">
            <h1 class="text-4xl lg:text-5xl font-bold leading-tight max-w-4xl pb-4">
                Reset Password
            </h1>
            <div class="text-sm text-white text-shadow-amber-200">
                <span>Enter your new password below</span>
            </div>
        </div>
    </div>
</section>

{{-- Main Content --}}
<section class="py-16 bg-gray-50">
    <div class="container mx-auto px-6">
        <div class="max-w-2xl mx-auto">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
                
                {{-- Reset Password Form --}}
                <div class="lg:col-span-12">
                    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                        <div class="bg-primary px-6 py-4">
                            <h1 class="text-2xl font-bold text-white">Set New Password</h1>
                            <p class="text-white/90 text-sm mt-1">Create a secure password for your account</p>
                        </div>

                        <div class="p-8">
                            <form method="POST" action="{{ route('dashboard.auth.password.update') }}" class="space-y-6">
                                @csrf
                                
                                {{-- Email Field (Hidden) --}}
                                <input type="hidden" name="email" value="{{ $email ?? old('email') }}">
                                
                                {{-- Token Field (Hidden) --}}
                                <input type="hidden" name="token" value="{{ $token ?? old('token') }}">

                                <div>
                                    <label for="password" class="block text-sm font-medium text-gray-700 mb-2">New Password</label>
                                    <input id="password" type="password" name="password" required autofocus autocomplete="new-password"
                                           class="block w-full rounded-lg border border-gray-200 px-4 py-3 focus:outline-none focus:border-primary/50 focus:ring-2 focus:ring-primary/20 @error('password') border-red-500 @enderror">
                                    @error('password')
                                    <p class="text-red-600 text-sm mt-2">{{ $message }}</p>
                                    @enderror
                                    <p class="text-xs text-gray-500 mt-2">Must be at least 8 characters long</p>
                                </div>

                                <div>
                                    <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-2">Confirm New Password</label>
                                    <input id="password_confirmation" type="password" name="password_confirmation" required autocomplete="new-password"
                                           class="block w-full rounded-lg border border-gray-200 px-4 py-3 focus:outline-none focus:border-primary/50 focus:ring-2 focus:ring-primary/20">
                                </div>

                                {{-- Password Requirements --}}
                                <div class="bg-gray-50 rounded-lg p-4">
                                    <h4 class="text-sm font-medium text-gray-700 mb-3">Password Requirements:</h4>
                                    <ul class="space-y-2 text-sm text-gray-600">
                                        <li class="flex items-center">
                                            <i class="fa-solid fa-check text-primary mr-2"></i>
                                            At least 8 characters long
                                        </li>
                                        <li class="flex items-center">
                                            <i class="fa-solid fa-check text-primary mr-2"></i>
                                            Contains uppercase and lowercase letters
                                        </li>
                                        <li class="flex items-center">
                                            <i class="fa-solid fa-check text-primary mr-2"></i>
                                            Includes numbers and special characters
                                        </li>
                                    </ul>
                                </div>

                                <div class="pt-4">
                                    <button type="submit" class="w-full bg-primary text-white font-semibold py-3 px-6 rounded-lg hover:bg-primary/90 focus:outline-none focus:ring-2 focus:ring-primary/20 transition-all duration-200">
                                        Reset Password
                                    </button>
                                </div>

                                <div class="text-center pt-4 border-t border-gray-200">
                                    <p class="text-sm text-gray-600">
                                        Remember your password?
                                        <a href="{{ route('dashboard.auth.login') }}" class="font-medium text-primary hover:text-primary/80 transition-colors">Back to Login</a>.
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