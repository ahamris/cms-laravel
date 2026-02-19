@extends('front.layouts.app')

@section('title', 'Verify Email - OPUB User Dashboard')

@section('content')

{{-- Hero Section --}}
<section class="relative bg-cover bg-center h-[200px] flex items-center text-white" style="background-image: url('{{ asset('frontend/images/dashboard.jpg') }}')">
    <div class="absolute inset-0 bg-black/80"></div> {{-- Overlay for readability --}}
    <div class="relative container mx-auto px-6 flex justify-between items-center">
        <div class="flex flex-col items-start text-left">
            <h1 class="text-4xl lg:text-5xl font-bold leading-tight max-w-4xl pb-4">
                Verify Your Email
            </h1>
            <div class="text-sm text-white text-shadow-amber-200">
                <span>We've sent you a verification link</span>
            </div>
        </div>
    </div>
</section>

{{-- Main Content --}}
<section class="py-16 bg-gray-50">
    <div class="container mx-auto px-6">
        <div class="max-w-2xl mx-auto">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
                
                {{-- Email Verification --}}
                <div class="lg:col-span-12">
                    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                        <div class="bg-primary px-6 py-4">
                            <h1 class="text-2xl font-bold text-white">Email Verification Required</h1>
                            <p class="text-white/90 text-sm mt-1">Complete your account setup</p>
                        </div>

                        <div class="p-8">
                            @if (session('resent'))
                                <div class="mb-6 p-4 bg-green-50 border border-green-200 rounded-lg">
                                    <div class="flex items-center">
                                        <i class="fa-solid fa-check-circle text-green-500 mr-3"></i>
                                        <p class="text-sm text-green-700">A fresh verification link has been sent to your email address.</p>
                                    </div>
                                </div>
                            @endif

                            <div class="space-y-6">
                                {{-- Main Message --}}
                                <div class="text-center">
                                    <h3 class="text-lg font-semibold text-gray-900 mb-3">Check Your Email</h3>
                                    <p class="text-gray-600 mb-4">
                                        We've sent a verification link to <strong>{{ auth()->user()->email ?? 'your email address' }}</strong>
                                    </p>
                                    <p class="text-sm text-gray-500">
                                        Click the link in the email to verify your account and access your dashboard.
                                    </p>
                                </div>

                                {{-- Email Icon --}}
                                <div class="w-24 h-24 mx-auto bg-primary/10 rounded-full flex items-center justify-center">
                                    <i class="fa-solid fa-envelope text-primary text-3xl"></i>
                                </div>

                                {{-- Instructions --}}
                                <div class="bg-gray-50 rounded-lg p-6 text-left">
                                    <h4 class="text-sm font-medium text-gray-700 mb-4">What to do next:</h4>
                                    <ol class="space-y-3 text-sm text-gray-600">
                                        <li class="flex items-start">
                                            <span class="bg-primary text-white text-xs rounded-full w-6 h-6 flex items-center justify-center mr-3 mt-0.5 flex-shrink-0">1</span>
                                            Check your email inbox (and spam folder)
                                        </li>
                                        <li class="flex items-start">
                                            <span class="bg-primary text-white text-xs rounded-full w-6 h-6 flex items-center justify-center mr-3 mt-0.5 flex-shrink-0">2</span>
                                            Click the verification link in the email
                                        </li>
                                        <li class="flex items-start">
                                            <span class="bg-primary text-white text-xs rounded-full w-6 h-6 flex items-center justify-center mr-3 mt-0.5 flex-shrink-0">3</span>
                                            Return here to access your dashboard
                                        </li>
                                    </ol>
                                </div>

                                {{-- Resend Button --}}
                                <form method="POST" action="{{ route('dashboard.auth.verification.send') }}">
                                    @csrf
                                    <button type="submit" class="w-full bg-primary text-white font-semibold py-3 px-6 rounded-lg hover:bg-primary/90 focus:outline-none focus:ring-2 focus:ring-primary/20 transition-all duration-200">
                                        Resend Verification Email
                                    </button>
                                </form>

                                {{-- Alternative Actions --}}
                                <div class="pt-4 border-t border-gray-200">
                                    <p class="text-sm text-gray-600 mb-3 text-center">Didn't receive the email?</p>
                                    <div class="space-y-2">
                                        <button type="button" class="w-full text-primary hover:text-primary/80 font-medium transition-colors text-sm py-2">
                                            <i class="fa-solid fa-sync-alt mr-2"></i>
                                            Check Spam Folder
                                        </button>
                                        <button type="button" class="w-full text-primary hover:text-primary/80 font-medium transition-colors text-sm py-2">
                                            <i class="fa-solid fa-edit mr-2"></i>
                                            Update Email Address
                                        </button>
                                    </div>
                                </div>

                                {{-- Logout Option --}}
                                <div class="pt-4 border-t border-gray-200 text-center">
                                    <p class="text-sm text-gray-600 mb-3">Need to use a different account?</p>
                                    <form method="POST" action="{{ route('dashboard.auth.logout') }}" class="inline">
                                        @csrf
                                        <button type="submit" class="inline-flex items-center text-primary hover:text-primary/80 font-medium transition-colors">
                                            <i class="fa-solid fa-sign-out-alt mr-2"></i>
                                            Logout
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

@endsection