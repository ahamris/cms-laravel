@extends('admin.layouts.auth')

@section('title', 'Recovery Code')
@section('heading', 'Use Recovery Code')
@section('subheading', 'Enter one of your recovery codes to access your account')
@section('image_alt', 'Recovery Code Authentication')

@section('content')
<form class="space-y-5" method="POST" action="{{ route('admin.two-factor.recovery.verify') }}">
    @csrf
    
    <div>
        <label for="recovery_code" class="block text-sm font-medium text-gray-700 mb-1">Recovery Code</label>
        <input id="recovery_code" 
               name="recovery_code" 
               type="text" 
               placeholder="Enter recovery code"
               class="block w-full rounded-md border border-gray-200 px-3 py-2.5 focus:outline-none focus:border-primary/50 font-mono text-center @error('recovery_code') border-red-500 @enderror"
               required
               autofocus>
        @error('recovery_code')
            <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
        @enderror
    </div>

    <div class="!mt-8">
        <button type="submit" class="w-full py-2.5 px-3 bg-primary text-white font-semibold rounded-md hover:bg-primary/90 focus:outline-none transition-all duration-300 cursor-pointer">
            Use Recovery Code
        </button>
    </div>

    <div class="text-center">
        <p class="text-sm text-slate-500">
            Have your device back?
            <a href="{{ route('admin.two-factor.challenge') }}" class="font-medium text-primary hover:underline">
                Use authenticator code
            </a>
        </p>
    </div>

    <div class="text-center">
        <a href="{{ route('admin.login') }}" class="text-sm text-slate-500 hover:text-slate-700">
            ← Back to login
        </a>
    </div>
</form>
@endsection
