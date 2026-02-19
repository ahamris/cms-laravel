@extends('admin.layouts.auth')

@section('title', 'Verify Email')
@section('heading', 'Verify Your Email')
@section('subheading', 'Thanks for signing up! Before getting started, could you verify your email address by clicking on the link we just emailed to you?')
@section('image_alt', 'Verify Email Illustration')

@section('content')
@if (session('status') == 'verification-link-sent')
    <div class="mb-4 text-sm text-green-600 bg-green-50 border border-green-200 rounded-md p-3">
        A new verification link has been sent to the email address you provided during registration.
    </div>
@endif

<div class="space-y-5">
    <form method="POST" action="{{ route('admin.verification.send') }}">
        @csrf
        <button type="submit" class="w-full py-2.5 px-3 bg-primary text-white font-semibold rounded-md hover:bg-primary/90 focus:outline-none transition-all duration-300 cursor-pointer">
            Resend Verification Email
        </button>
    </form>

    <form method="POST" action="{{ route('admin.logout') }}">
        @csrf
        <button type="submit" class="w-full py-2.5 px-3 bg-gray-200 text-gray-700 font-semibold rounded-md hover:bg-gray-300 focus:outline-none transition-all duration-300 cursor-pointer">
            Log Out
        </button>
    </form>
</div>
@endsection
