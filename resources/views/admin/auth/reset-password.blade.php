@extends('admin.layouts.auth')

@section('title', 'Reset Password')
@section('heading', 'Reset Password')
@section('subheading', 'Enter your new password to reset your account')
@section('image_alt', 'Reset Password Illustration')

@section('content')
<form method="POST" action="{{ route('admin.password.store') }}" class="space-y-5">
    @csrf

    <!-- Password Reset Token -->
    <input type="hidden" name="token" value="{{ $request->route('token') }}">

    <div>
        <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email Address</label>
        <input id="email" type="email" name="email" value="{{ old('email', $request->email) }}" required autofocus autocomplete="username"
               class="block w-full rounded-md border border-gray-200 px-3 py-2.5 focus:outline-none focus:border-primary/50 @error('email') border-red-500 @enderror">
        @error('email')
        <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label for="password" class="block text-sm font-medium text-gray-700 mb-1">New Password</label>
        <input id="password" type="password" name="password" required autocomplete="new-password"
               class="block w-full rounded-md border border-gray-200 px-3 py-2.5 focus:outline-none focus:border-primary/50 @error('password') border-red-500 @enderror">
        @error('password')
        <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-1">Confirm New Password</label>
        <input id="password_confirmation" type="password" name="password_confirmation" required autocomplete="new-password"
               class="block w-full rounded-md border border-gray-200 px-3 py-2.5 focus:outline-none focus:border-primary/50 @error('password_confirmation') border-red-500 @enderror">
        @error('password_confirmation')
        <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
        @enderror
    </div>

    <div class="!mt-8">
        <button type="submit" class="w-full py-2.5 px-3 bg-primary text-white font-semibold rounded-md hover:bg-primary/90 focus:outline-none transition-all duration-300 cursor-pointer">
            Reset Password
        </button>
    </div>

    <div class="text-center">
        <p class="text-sm text-slate-500">
            Remember your password?
            <a href="{{ route('admin.login') }}" class="font-medium text-primary hover:underline">Sign in here</a>.
        </p>
    </div>
</form>
@endsection
