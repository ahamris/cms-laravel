<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Profile - OPUB Admin Panel</title>

    {{-- Fonts --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    {{-- FontAwesome Pro Links --}}
    <link href="https://site-assets.fontawesome.com/releases/v6.7.2/css/all.css" rel="stylesheet"/>
    <link href="https://site-assets.fontawesome.com/releases/v6.7.2/css/brands.css" rel="stylesheet"/>

    {{-- Styles / Scripts --}}
    @vite(['resources/css/admin.css', 'resources/js/admin.js'])
</head>
<body class="bg-gradient-to-br from-gray-50 via-blue-50/30 to-indigo-50/30 text-gray-900 font-sans min-h-screen flex items-center justify-center">

    <div class="container mx-auto flex justify-center items-center py-10 px-4 sm:px-6 lg:px-8">
        <div class="w-full bg-white shadow-sm rounded-md overflow-hidden lg:flex">
            <div class="hidden lg:block lg:w-1/2">
                <img src="{{ asset('frontend/images/login-image.jpg') }}" alt="Profile Illustration" class="w-full h-full object-cover">
            </div>

            <div class="w-full lg:w-1/2 p-8 sm:p-12">
                <h2 class="text-3xl font-bold mb-2 text-slate-800">Profile Information</h2>
                <p class="text-sm text-slate-500 mb-8">Update your account's profile information and email address.</p>

                @if (session('status') == 'profile-updated')
                    <div class="mb-4 text-sm text-green-600 bg-green-50 border border-green-200 rounded-md p-3">
                        Profile updated successfully!
                    </div>
                @endif

                <form method="POST" action="{{ route('admin.profile.update') }}" class="space-y-5">
                    @csrf
                    @method('PUT')

                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Name</label>
                        <input id="name" type="text" name="name" value="{{ old('name', $user->name) }}" required autocomplete="name"
                               class="block w-full rounded-md border border-gray-200 px-3 py-2.5 focus:outline-none focus:border-primary/50 @error('name') border-red-500 @enderror">
                        @error('name')
                        <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                        <input id="email" type="email" name="email" value="{{ old('email', $user->email) }}" required autocomplete="username"
                               class="block w-full rounded-md border border-gray-200 px-3 py-2.5 focus:outline-none focus:border-primary/50 @error('email') border-red-500 @enderror">
                        @error('email')
                        <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="!mt-8">
                        <button type="submit" class="w-full py-2.5 px-3 bg-primary text-white font-semibold rounded-md hover:bg-primary/90 focus:outline-none transition-all duration-300 cursor-pointer">
                            Update Profile
                        </button>
                    </div>
                </form>

                <div class="mt-8 pt-8 border-t border-gray-200">
                    <h3 class="text-xl font-semibold mb-4 text-slate-800">Update Password</h3>
                    <p class="text-sm text-slate-500 mb-6">Ensure your account is using a long, random password to stay secure.</p>

                    <form method="POST" action="{{ route('admin.password.update') }}" class="space-y-5">
                        @csrf
                        @method('PUT')

                        <div>
                            <label for="current_password" class="block text-sm font-medium text-gray-700 mb-1">Current Password</label>
                            <input id="current_password" type="password" name="current_password" required autocomplete="current-password"
                                   class="block w-full rounded-md border border-gray-200 px-3 py-2.5 focus:outline-none focus:border-primary/50 @error('current_password') border-red-500 @enderror">
                            @error('current_password')
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
                                Update Password
                            </button>
                        </div>
                    </form>
                </div>

                <div class="mt-8 pt-8 border-t border-gray-200">
                    <h3 class="text-xl font-semibold mb-4 text-red-800">Delete Account</h3>
                    <p class="text-sm text-slate-500 mb-6">Once your account is deleted, all of its resources and data will be permanently deleted.</p>

                    <form method="POST" action="{{ route('admin.profile.destroy') }}" class="space-y-5">
                        @csrf
                        @method('DELETE')

                        <div>
                            <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Password</label>
                            <input id="delete_password" type="password" name="password" required
                                   class="block w-full rounded-md border border-gray-200 px-3 py-2.5 focus:outline-none focus:border-primary/50 @error('delete_password') border-red-500 @enderror"
                                   placeholder="Enter your password to confirm">
                            @error('delete_password')
                            <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="!mt-8">
                            <button type="submit" class="w-full py-2.5 px-3 bg-red-600 text-white font-semibold rounded-md hover:bg-red-700 focus:outline-none transition-all duration-300 cursor-pointer"
                                    onclick="return confirm('Are you sure you want to delete your account? This action cannot be undone.')">
                                Delete Account
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

</body>
</html>
