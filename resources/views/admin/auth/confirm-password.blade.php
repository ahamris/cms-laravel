@extends('admin.layouts.auth')

@section('title', 'Confirm Password')
@section('heading', 'Confirm Password')
@section('subheading', 'This is a secure area of the application. Please confirm your password before continuing.')
@section('image_alt', 'Confirm Password Illustration')

@section('content')
<form method="POST" action="{{ route('admin.password.confirm') }}" class="space-y-5">
    @csrf

    <div>
        <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Password</label>
        <input id="password" type="password" name="password" required autocomplete="current-password"
               class="block w-full rounded-md border border-gray-200 px-3 py-2.5 focus:outline-none focus:border-primary/50 @error('password') border-red-500 @enderror">
        @error('password')
        <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
        @enderror
    </div>

    <div class="!mt-8">
        <button type="submit" class="w-full py-2.5 px-3 bg-primary text-white font-semibold rounded-md hover:bg-primary/90 focus:outline-none transition-all duration-300 cursor-pointer">
            Confirm
        </button>
    </div>
</form>
@endsection
