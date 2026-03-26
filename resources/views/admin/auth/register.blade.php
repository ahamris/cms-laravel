<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Register - OPUB Admin Panel</title>

    {{-- Fonts --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <x-fontawesome-pro />

    {{-- Styles / Scripts --}}
    @vite(['resources/css/front.css', 'resources/js/front.js'])
</head>
<body class="bg-gradient-to-br from-gray-50 via-blue-50/30 to-indigo-50/30 text-gray-900 font-sans min-h-screen flex items-center justify-center">

    <div class="container mx-auto flex justify-center items-center py-10 px-4 sm:px-6 lg:px-8">
        <div class="w-full bg-white shadow-sm rounded-md overflow-hidden lg:flex">
            <div class="hidden lg:block lg:w-1/2">
                <img src="{{ asset('frontend/images/login-image.jpg') }}" alt="Register Illustration" class="w-full h-full object-cover">
            </div>

            <div class="w-full lg:w-1/2 p-8 sm:p-12">
                <h2 class="text-3xl font-bold mb-2 text-slate-800">Create Account</h2>
                <p class="text-sm text-slate-500 mb-8">Fill in your information to create your account</p>

                <form method="POST" action="{{ route('admin.register.post') }}" class="space-y-5">
                    @csrf

                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Full Name</label>
                        <input id="name" type="text" name="name" value="{{ old('name') }}" required autocomplete="name" autofocus
                               class="block w-full rounded-md border border-gray-200 px-3 py-2.5 focus:outline-none focus:border-primary/50 @error('name') border-red-500 @enderror">
                        @error('name')
                        <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email Address</label>
                        <input id="email" type="email" name="email" value="{{ old('email') }}" required autocomplete="email"
                               class="block w-full rounded-md border border-gray-200 px-3 py-2.5 focus:outline-none focus:border-primary/50 @error('email') border-red-500 @enderror">
                        @error('email')
                        <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Password</label>
                        <input id="password" type="password" name="password" required autocomplete="new-password"
                               class="block w-full rounded-md border border-gray-200 px-3 py-2.5 focus:outline-none focus:border-primary/50 @error('password') border-red-500 @enderror">
                        @error('password')
                        <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-1">Confirm Password</label>
                        <input id="password_confirmation" type="password" name="password_confirmation" required autocomplete="new-password"
                               class="block w-full rounded-md border border-gray-200 px-3 py-2.5 focus:outline-none focus:border-primary/50 @error('password_confirmation') border-red-500 @enderror">
                        @error('password_confirmation')
                        <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="!mt-8">
                        <button type="submit" class="w-full py-2.5 px-3 bg-primary text-white font-semibold rounded-md hover:bg-primary/90 focus:outline-none transition-all duration-300 cursor-pointer">
                            Create Account
                        </button>
                    </div>

                    <div class="text-center">
                        <p class="text-sm text-slate-500">
                            Already have an account?
                            <a href="{{ route('admin.login') }}" class="font-medium text-primary hover:underline">Sign in here</a>.
                        </p>
                    </div>
                </form>
            </div>
        </div>
    </div>

</body>
</html>
