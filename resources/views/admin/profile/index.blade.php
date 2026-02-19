<x-layouts.admin title="Profile">
    {{-- Profile Content --}}
    @if(!auth()->check())
        <div class="flex items-center justify-center h-64">
            <div class="text-center">
                <h3 class="text-lg font-medium text-gray-900 mb-2">Authentication Required</h3>
                <p class="text-gray-600 mb-4">Please login to access your profile.</p>
                <a href="{{ route('admin.login') }}" class="bg-primary text-white px-4 py-2 rounded-lg hover:bg-primary/90 transition-colors">
                    Go to Login
                </a>
            </div>
        </div>
    @else
        {{-- Profile Management Section --}}
        <div class="mb-8">
            {{-- Header with Title --}}
            <div class="flex items-center justify-between mb-8">
                <div>
                    <h2 class="text-3xl font-bold text-gray-900">Profile Settings</h2>
                    <p class="text-gray-600 mt-2">Manage your account settings and preferences</p>
                </div>
                <div class="flex items-center space-x-3">
                    <div class="w-12 h-12 bg-gradient-to-br from-primary to-primary/80 rounded-xl flex items-center justify-center shadow-sm">
                        <span class="text-white font-bold text-lg">{{ substr(auth()->user()->name, 0, 2) }}</span>
                    </div>
                </div>
            </div>

            {{-- Main Grid Layout --}}
            <div class="grid grid-cols-1 xl:grid-cols-12 gap-8">

                {{-- Left Column: Profile Information --}}
                <div class="xl:col-span-6">
                    {{-- Profile Information Card --}}
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-8">
                        <div class="flex items-center space-x-4 mb-8">
                            <div class="w-16 h-16 bg-gradient-to-br from-primary to-primary/80 rounded-2xl flex items-center justify-center shadow-sm">
                                <i class="fa-solid fa-user text-white text-2xl"></i>
                            </div>
                            <div>
                                <h3 class="text-xl font-semibold text-gray-900">Profile Information</h3>
                                <p class="text-gray-600">Update your account's profile information and contact details.</p>
                            </div>
                        </div>

                        @if ( session('status') === 'profile-updated' )
                            <div class="mb-6 bg-green-50 border border-green-200 rounded-xl p-4">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0">
                                        <i class="fa-solid fa-check-circle text-green-500 text-lg"></i>
                                    </div>
                                    <div class="ml-3">
                                        <p class="text-sm font-medium text-green-800">
                                            Profile updated successfully!
                                        </p>
                                    </div>
                                </div>
                            </div>
                        @endif

                        <form method="POST" action="{{ route('admin.profile.update') }}" class="space-y-8">
                            @csrf
                            @method('PUT')

                            {{-- Personal Information Section --}}
                            <div>
                                <h4 class="text-lg font-semibold mb-6 text-gray-900 flex items-center">
                                    <i class="fa-solid fa-user-circle text-primary mr-3"></i>
                                    Personal Information
                                </h4>

                                <div class="space-y-6">
                                    <div>
                                        <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Full Name</label>
                                        <input id="name" type="text" name="name" value="{{ old('name', auth()->check() ? $user->name : '') }}" required autocomplete="name"
                                               class="block w-full px-4 py-3 border border-gray-300 rounded-xl text-gray-900 placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all duration-200"
                                               placeholder="Enter your full name">
                                        @error('name')
                                        <p class="mt-2 text-sm text-red-600 flex items-center">
                                            <i class="fa-solid fa-exclamation-circle mr-1"></i>
                                            {{ $message }}
                                        </p>
                                        @enderror
                                    </div>

                                    <div>
                                        <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email Address</label>
                                        <input id="email" type="email" name="email" value="{{ old('email', auth()->check() ? $user->email : '') }}" required autocomplete="username"
                                               class="block w-full px-4 py-3 border border-gray-300 rounded-xl text-gray-900 placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all duration-200"
                                               placeholder="Enter your email address">
                                        @error('email')
                                        <p class="mt-2 text-sm text-red-600 flex items-center">
                                            <i class="fa-solid fa-exclamation-circle mr-1"></i>
                                            {{ $message }}
                                        </p>
                                        @enderror
                                    </div>

                                    <div>
                                        <label for="type" class="block text-sm font-medium text-gray-700 mb-2">User Type</label>
                                        <input id="type" type="text" name="type" value="{{ old('type', auth()->check() ? $user->type : '') }}" autocomplete="organization-title"
                                               class="block w-full px-4 py-3 border border-gray-300 rounded-xl text-gray-900 placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all duration-200"
                                               placeholder="Enter your user type">
                                        @error('type')
                                        <p class="mt-2 text-sm text-red-600 flex items-center">
                                            <i class="fa-solid fa-exclamation-circle mr-1"></i>
                                            {{ $message }}
                                        </p>
                                        @enderror
                                    </div>

                                    <div>
                                        <label for="telefoon" class="block text-sm font-medium text-gray-700 mb-2">Phone Number</label>
                                        <input id="telefoon" type="tel" name="telefoon" value="{{ old('telefoon', auth()->check() ? $user->telefoon : '') }}" autocomplete="tel"
                                               class="block w-full px-4 py-3 border border-gray-300 rounded-xl text-gray-900 placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all duration-200"
                                               placeholder="Enter your phone number">
                                        @error('telefoon')
                                        <p class="mt-2 text-sm text-red-600 flex items-center">
                                            <i class="fa-solid fa-exclamation-circle mr-1"></i>
                                            {{ $message }}
                                        </p>
                                        @enderror
                                    </div>

                                    <div>
                                        <label for="website" class="block text-sm font-medium text-gray-700 mb-2">Website</label>
                                        <input id="website" type="url" name="website" value="{{ old('website', auth()->check() ? $user->website : '') }}" autocomplete="url"
                                               class="block w-full px-4 py-3 border border-gray-300 rounded-xl text-gray-900 placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all duration-200"
                                               placeholder="Enter your website URL">
                                        @error('website')
                                        <p class="mt-2 text-sm text-red-600 flex items-center">
                                            <i class="fa-solid fa-exclamation-circle mr-1"></i>
                                            {{ $message }}
                                        </p>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            {{-- Business Information Section --}}
                            <div class="border-t border-gray-200 pt-8">
                                <h4 class="text-lg font-semibold mb-6 text-gray-900 flex items-center">
                                    <i class="fa-solid fa-building text-primary mr-3"></i>
                                    Business Information
                                </h4>

                                <div class="space-y-6">
                                    <div>
                                        <label for="bedrijfsnaam" class="block text-sm font-medium text-gray-700 mb-2">Company Name</label>
                                        <input id="bedrijfsnaam" type="text" name="bedrijfsnaam" value="{{ old('bedrijfsnaam', auth()->check() ? $user->bedrijfsnaam : '') }}" autocomplete="organization"
                                               class="block w-full px-4 py-3 border border-gray-300 rounded-xl text-gray-900 placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all duration-200"
                                               placeholder="Enter company name">
                                        @error('bedrijfsnaam')
                                        <p class="mt-2 text-sm text-red-600 flex items-center">
                                            <i class="fa-solid fa-exclamation-circle mr-1"></i>
                                            {{ $message }}
                                        </p>
                                        @enderror
                                    </div>

                                    <div>
                                        <label for="kvk_nummer" class="block text-sm font-medium text-gray-700 mb-2">KvK Number</label>
                                        <input id="kvk_nummer" type="text" name="kvk_nummer" value="{{ old('kvk_nummer', auth()->check() ? $user->kvk_nummer : '') }}"
                                               class="block w-full px-4 py-3 border border-gray-300 rounded-xl text-gray-900 placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all duration-200"
                                               placeholder="Enter KvK number">
                                        @error('kvk_nummer')
                                        <p class="mt-2 text-sm text-red-600 flex items-center">
                                            <i class="fa-solid fa-exclamation-circle mr-1"></i>
                                            {{ $message }}
                                        </p>
                                        @enderror
                                    </div>

                                    <div>
                                        <label for="btw_nummer" class="block text-sm font-medium text-gray-700 mb-2">VAT Number</label>
                                        <input id="btw_nummer" type="text" name="btw_nummer" value="{{ old('btw_nummer', auth()->check() ? $user->btw_nummer : '') }}"
                                               class="block w-full px-4 py-3 border border-gray-300 rounded-xl text-gray-900 placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all duration-200"
                                               placeholder="Enter VAT number">
                                        @error('btw_nummer')
                                        <p class="mt-2 text-sm text-red-600 flex items-center">
                                            <i class="fa-solid fa-exclamation-circle mr-1"></i>
                                            {{ $message }}
                                        </p>
                                        @enderror
                                    </div>

                                    <div>
                                        <label for="iban" class="block text-sm font-medium text-gray-700 mb-2">IBAN</label>
                                        <input id="iban" type="text" name="iban" value="{{ old('iban', auth()->check() ? $user->iban : '') }}"
                                               class="block w-full px-4 py-3 border border-gray-300 rounded-xl text-gray-900 placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all duration-200"
                                               placeholder="Enter IBAN">
                                        @error('iban')
                                        <p class="mt-2 text-sm text-red-600 flex items-center">
                                            <i class="fa-solid fa-exclamation-circle mr-1"></i>
                                            {{ $message }}
                                        </p>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            {{-- Address Information Section --}}
                            <div class="border-t border-gray-200 pt-8">
                                <h4 class="text-lg font-semibold mb-6 text-gray-900 flex items-center">
                                    <i class="fa-solid fa-map-marker-alt text-primary mr-3"></i>
                                    Address Information
                                </h4>

                                <div class="space-y-6">
                                    <div>
                                        <label for="adres" class="block text-sm font-medium text-gray-700 mb-2">Street Address</label>
                                        <input id="adres" type="text" name="adres" value="{{ old('adres', auth()->check() ? $user->adres : '') }}" autocomplete="street-address"
                                               class="block w-full px-4 py-3 border border-gray-300 rounded-xl text-gray-900 placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all duration-200"
                                               placeholder="Enter street address">
                                        @error('adres')
                                        <p class="mt-2 text-sm text-red-600 flex items-center">
                                            <i class="fa-solid fa-exclamation-circle mr-1"></i>
                                            {{ $message }}
                                        </p>
                                        @enderror
                                    </div>

                                    <div>
                                        <label for="postcode" class="block text-sm font-medium text-gray-700 mb-2">Postal Code</label>
                                        <input id="postcode" type="text" name="postcode" value="{{ old('postcode', auth()->check() ? $user->postcode : '') }}" autocomplete="postal-code"
                                               class="block w-full px-4 py-3 border border-gray-300 rounded-xl text-gray-900 placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all duration-200"
                                               placeholder="Enter postal code">
                                        @error('postcode')
                                        <p class="mt-2 text-sm text-red-600 flex items-center">
                                            <i class="fa-solid fa-exclamation-circle mr-1"></i>
                                            {{ $message }}
                                        </p>
                                        @enderror
                                    </div>

                                    <div>
                                        <label for="plaats" class="block text-sm font-medium text-gray-700 mb-2">City</label>
                                        <input id="plaats" type="text" name="plaats" value="{{ old('plaats', auth()->check() ? $user->plaats : '') }}" autocomplete="address-level2"
                                               class="block w-full px-4 py-3 border border-gray-300 rounded-xl text-gray-900 placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all duration-200"
                                               placeholder="Enter city">
                                        @error('plaats')
                                        <p class="mt-2 text-sm text-red-600 flex items-center">
                                            <i class="fa-solid fa-exclamation-circle mr-1"></i>
                                            {{ $message }}
                                        </p>
                                        @enderror
                                    </div>

                                    <div>
                                        <label for="land" class="block text-sm font-medium text-gray-700 mb-2">Country</label>
                                        <input id="land" type="text" name="land" value="{{ old('land', auth()->check() ? $user->land : '') }}" autocomplete="country-name"
                                               class="block w-full px-4 py-3 border border-gray-300 rounded-xl text-gray-900 placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all duration-200"
                                               placeholder="Enter country">
                                        @error('land')
                                        <p class="mt-2 text-sm text-red-600 flex items-center">
                                            <i class="fa-solid fa-exclamation-circle mr-1"></i>
                                            {{ $message }}
                                        </p>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="flex justify-end pt-6">
                                <button type="submit"
                                        class="bg-primary text-white px-8 py-3 rounded-xl font-medium hover:bg-primary/90 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary transition-all duration-200 shadow-sm hover:shadow-md">
                                    <i class="fa-solid fa-save mr-2"></i>
                                    Save Changes
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                {{-- Right Column: Security Settings & Account Management --}}
                <div class="xl:col-span-6">
                    {{-- Update Password Card --}}
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-8 mb-8">
                        <div class="flex items-center space-x-4 mb-8">
                            <div class="w-16 h-16 bg-gradient-to-br from-blue-500 to-blue-600 rounded-2xl flex items-center justify-center shadow-sm">
                                <i class="fa-solid fa-lock text-white text-2xl"></i>
                            </div>
                            <div>
                                <h3 class="text-xl font-semibold text-gray-900">Update Password</h3>
                                <p class="text-gray-600">Ensure your account is using a long, random password to stay secure.</p>
                            </div>
                        </div>

                        @if ( session('status') === 'password-updated' )
                            <div class="mb-6 bg-green-50 border border-green-200 rounded-xl p-4">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0">
                                        <i class="fa-solid fa-check-circle text-green-500 text-lg"></i>
                                    </div>
                                    <div class="ml-3">
                                        <p class="text-sm font-medium text-green-800">
                                            Password updated successfully!
                                        </p>
                                    </div>
                                </div>
                            </div>
                        @endif

                        <form method="POST" action="{{ route('admin.password.update') }}" class="space-y-6">
                            @csrf
                            @method('PUT')

                            <div class="space-y-6">
                                <div>
                                    <label for="current_password" class="block text-sm font-medium text-gray-700 mb-2">Current Password</label>
                                    <input id="current_password" type="password" name="current_password" required autocomplete="current-password"
                                           class="block w-full px-4 py-3 border border-gray-300 rounded-xl text-gray-900 placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all duration-200"
                                           placeholder="Enter current password">
                                    @error('current_password')
                                    <p class="mt-2 text-sm text-red-600 flex items-center">
                                        <i class="fa-solid fa-exclamation-circle mr-1"></i>
                                        {{ $message }}
                                    </p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="password" class="block text-sm font-medium text-gray-700 mb-2">New Password</label>
                                    <input id="password" type="password" name="password" required autocomplete="new-password"
                                           class="block w-full px-4 py-3 border border-gray-300 rounded-xl text-gray-900 placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all duration-200"
                                           placeholder="Enter new password">
                                    @error('password')
                                    <p class="mt-2 text-sm text-red-600 flex items-center">
                                        <i class="fa-solid fa-exclamation-circle mr-1"></i>
                                        {{ $message }}
                                    </p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-2">Confirm New Password</label>
                                    <input id="password_confirmation" type="password" name="password_confirmation" required autocomplete="new-password"
                                           class="block w-full px-4 py-3 border border-gray-300 rounded-xl text-gray-900 placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all duration-200"
                                           placeholder="Confirm new password">
                                    @error('password_confirmation')
                                    <p class="mt-2 text-sm text-red-600 flex items-center">
                                        <i class="fa-solid fa-exclamation-circle mr-1"></i>
                                        {{ $message }}
                                    </p>
                                    @enderror
                                </div>
                            </div>

                            <div class="flex justify-end pt-6">
                                <button type="submit"
                                        class="bg-blue-600 text-white px-8 py-3 rounded-xl font-medium hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all duration-200 shadow-sm hover:shadow-md">
                                    <i class="fa-solid fa-key mr-2"></i>
                                    Update Password
                                </button>
                            </div>
                        </form>
                    </div>

                    {{-- Two-Factor Authentication Card --}}
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-8 mb-8">
                        <div class="flex items-center space-x-4 mb-8">
                            <div class="w-16 h-16 bg-gradient-to-br from-green-500 to-green-600 rounded-2xl flex items-center justify-center shadow-sm">
                                <i class="fa-solid fa-shield-halved text-white text-2xl"></i>
                            </div>
                            <div>
                                <h3 class="text-xl font-semibold text-gray-900">Two-Factor Authentication</h3>
                                <p class="text-gray-600">Add additional security to your account using two-factor authentication.</p>
                            </div>
                        </div>

                        @if ( session('status') === '2fa-enabled' )
                            <div class="mb-6 bg-green-50 border border-green-200 rounded-xl p-4">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0">
                                        <i class="fa-solid fa-check-circle text-green-500 text-lg"></i>
                                    </div>
                                    <div class="ml-3">
                                        <p class="text-sm font-medium text-green-800">
                                            Two-factor authentication has been enabled successfully!
                                        </p>
                                    </div>
                                </div>
                            </div>
                        @endif

                        @if ( session('status') === '2fa-disabled' )
                            <div class="mb-6 bg-yellow-50 border border-yellow-200 rounded-xl p-4">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0">
                                        <i class="fa-solid fa-exclamation-triangle text-yellow-500 text-lg"></i>
                                    </div>
                                    <div class="ml-3">
                                        <p class="text-sm font-medium text-yellow-800">
                                            Two-factor authentication has been disabled.
                                        </p>
                                    </div>
                                </div>
                            </div>
                        @endif

                        {{-- 2FA Not Enabled Section --}}
                        @if(!$user->two_factor_confirmed_at)
                            {{-- Enable 2FA Section --}}
                            <div class="bg-blue-50 border border-blue-200 rounded-xl p-6 mb-6">
                                <div class="flex">
                                    <div class="flex-shrink-0">
                                        <i class="fa-solid fa-info-circle text-blue-500 text-xl"></i>
                                    </div>
                                    <div class="ml-3">
                                        <h4 class="text-sm font-medium text-blue-800">
                                            Enable Two-Factor Authentication
                                        </h4>
                                        <div class="mt-2 text-sm text-blue-700">
                                            <p>When two-factor authentication is enabled, you will be prompted for a secure, random token during authentication. You may retrieve this token from your phone's Google Authenticator application.</p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- Enable 2FA Button --}}
                            @if(!$user->two_factor_confirmed_at && !$user->two_factor_secret)
                                <form method="POST" action="{{ route('admin.profile.2fa.setup') }}" class="inline">
                                    @csrf
                                    <button type="submit"
                                            class="bg-green-600 text-white px-8 py-3 rounded-xl font-medium hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-all duration-200 shadow-sm hover:shadow-md">
                                        <i class="fa-solid fa-shield-check mr-2"></i>
                                        Enable Two-Factor Authentication
                                    </button>
                                </form>
                            @elseif($user->two_factor_secret && !$user->two_factor_confirmed_at)
                                {{-- 2FA Setup in Progress - Show QR Code and Verification --}}
                                <div class="bg-blue-50 border border-blue-200 rounded-xl p-6 mb-6">
                                    <div class="flex">
                                        <div class="flex-shrink-0">
                                            <i class="fa-solid fa-info-circle text-blue-500 text-xl"></i>
                                        </div>
                                        <div class="ml-3">
                                            <h4 class="text-sm font-medium text-blue-800">
                                                Complete Two-Factor Authentication Setup
                                            </h4>
                                            <div class="mt-2 text-sm text-blue-700">
                                                <p>Please scan the QR code below and enter the verification code to complete setup.</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        @else
                            {{-- 2FA Enabled Section --}}
                            <div class="bg-green-50 border border-green-200 rounded-xl p-6 mb-6">
                                <div class="flex">
                                    <div class="flex-shrink-0">
                                        <i class="fa-solid fa-shield-check text-green-500 text-xl"></i>
                                    </div>
                                    <div class="ml-3">
                                        <h4 class="text-sm font-medium text-green-800">
                                            Two-Factor Authentication is Enabled
                                        </h4>
                                        <div class="mt-2 text-sm text-green-700">
                                            <p>Two-factor authentication is currently enabled for your account. Your account is more secure with 2FA enabled.</p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- 2FA Management Buttons for Enabled 2FA --}}
                            <div class="flex flex-col sm:flex-row gap-4 justify-end">
                                <form method="POST" action="{{ route('admin.profile.2fa.recovery-codes') }}" class="inline">
                                    @csrf
                                    <button type="submit"
                                            class="bg-blue-600 text-white px-6 py-3 rounded-xl font-medium hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all duration-200 shadow-sm hover:shadow-md">
                                        <i class="fa-solid fa-refresh mr-2"></i>
                                        Regenerate Recovery Codes
                                    </button>
                                </form>

                                <form method="POST" action="{{ route('admin.profile.2fa.disable') }}" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                            class="bg-red-600 text-white px-6 py-3 rounded-xl font-medium hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition-all duration-200 shadow-sm hover:shadow-md"
                                            onclick="return confirm('Are you sure you want to disable two-factor authentication? This will make your account less secure.')">
                                        <i class="fa-solid fa-shield-xmark mr-2"></i>
                                        Disable Two-Factor Authentication
                                    </button>
                                </form>
                            </div>
                        @endif

                        {{-- Show QR Code and Verification Form if in setup mode --}}
                        @if(session('qrCode') || ($user->two_factor_secret && !$user->two_factor_confirmed_at))
                            <div class="bg-white border border-gray-200 rounded-xl p-6 mb-6">
                                <h4 class="text-lg font-semibold mb-6 text-gray-900">Setup Your Authenticator App</h4>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                                    <div class="text-center">
                                        <p class="text-sm text-gray-600 mb-4">Scan this QR code with your authenticator app:</p>
                                        <div class="inline-block p-4 bg-white border border-gray-200 rounded-xl">
                                            @if(session('qrCode'))
                                                {!! session('qrCode') !!}
                                            @else
                                                <div class="w-48 h-48 bg-gray-100 flex items-center justify-center text-gray-500 rounded-xl">
                                                    <span class="text-sm">QR Code will appear here</span>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                    <div>
                                        <p class="text-sm text-gray-600 mb-4">Or enter this secret key manually:</p>
                                        <div class="bg-gray-50 border border-gray-200 rounded-xl p-3 mb-4">
                                            <code class="text-sm font-mono break-all">{{ session('secret') ?? '••••••••••••••••' }}</code>
                                        </div>
                                        <p class="text-xs text-gray-500">
                                            Store this secret key in a safe place. You can use it to set up 2FA on multiple devices.
                                        </p>
                                    </div>
                                </div>

                                {{-- Verification Form --}}
                                <div class="border-t border-gray-200 pt-6">
                                    <h5 class="text-md font-semibold mb-4 text-gray-900">Verify Your Setup</h5>
                                    <p class="text-sm text-gray-600 mb-4">Enter the 6-digit code from your authenticator app to complete the setup:</p>

                                    <form method="POST" action="{{ route('admin.profile.2fa.confirm') }}" class="space-y-4">
                                        @csrf
                                        <div>
                                            <label for="code" class="block text-sm font-medium text-gray-700 mb-2">Verification Code</label>
                                            <input type="text"
                                                   id="code"
                                                   name="code"
                                                   maxlength="6"
                                                   pattern="[0-9]{6}"
                                                   placeholder="000000"
                                                   class="block w-full px-4 py-3 border border-gray-300 rounded-xl shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 text-center text-lg tracking-widest font-mono"
                                                   required>
                                            @error('code')
                                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                            @enderror
                                        </div>

                                        <div class="flex space-x-4">
                                            <button type="submit"
                                                    class="bg-green-600 text-white px-8 py-3 rounded-xl font-medium hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-all duration-200 shadow-sm hover:shadow-md">
                                                <i class="fa-solid fa-check mr-2"></i>
                                                Verify and Enable 2FA
                                            </button>
                                        </div>
                                    </form>

                                    {{-- Cancel Setup Form --}}
                                    <form method="POST" action="{{ route('admin.profile.2fa.cancel') }}" class="mt-4">
                                        @csrf
                                        <button type="submit"
                                                class="bg-gray-600 text-white px-6 py-3 rounded-xl font-medium hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 transition-all duration-200 shadow-sm hover:shadow-md">
                                            <i class="fa-solid fa-times mr-2"></i>
                                            Cancel Setup
                                        </button>
                                    </form>
                                </div>
                            </div>
                        @endif

                        {{-- Show Recovery Codes if just enabled --}}
                        @if(session('recoveryCodes'))
                            <div class="bg-yellow-50 border border-yellow-200 rounded-xl p-6 mb-6">
                                <h4 class="text-lg font-semibold mb-4 text-yellow-800">Recovery Codes</h4>
                                <p class="text-sm text-yellow-700 mb-4">
                                    Store these recovery codes in a secure password manager. They can be used to recover access to your account if your two-factor authentication device is lost.
                                </p>
                                <div class="bg-white border border-yellow-200 rounded-xl p-4">
                                    <div class="grid grid-cols-2 gap-2 font-mono text-sm">
                                        @foreach(session('recoveryCodes') as $code)
                                            <div class="bg-gray-50 p-2 rounded-lg text-center">{{ $code }}</div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>

                    {{-- Delete Account Card --}}
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-8">
                        <div class="flex items-center space-x-4 mb-8">
                            <div class="w-16 h-16 bg-gradient-to-br from-red-500 to-red-600 rounded-2xl flex items-center justify-center shadow-sm">
                                <i class="fa-solid fa-exclamation-triangle text-white text-2xl"></i>
                            </div>
                            <div>
                                <h3 class="text-xl font-semibold text-gray-900">Delete Account</h3>
                                <p class="text-gray-600">Once your account is deleted, all of its resources and data will be permanently deleted.</p>
                            </div>
                        </div>

                        <div class="bg-red-50 border border-red-200 rounded-xl p-4 mb-6">
                            <div class="flex">
                                <div class="flex-shrink-0">
                                    <i class="fa-solid fa-exclamation-triangle text-red-500 text-lg"></i>
                                </div>
                                <div class="ml-3">
                                    <h4 class="text-sm font-medium text-red-800">
                                        Warning
                                    </h4>
                                    <div class="mt-2 text-sm text-red-700">
                                        <p>This action cannot be undone. This will permanently delete your account and remove your data from our servers.</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <form method="POST" action="{{ route('admin.profile.destroy') }}" class="space-y-6">
                            @csrf
                            @method('DELETE')

                            <div>
                                <label for="delete_password" class="block text-sm font-medium text-gray-700 mb-2">Password</label>
                                <input id="delete_password" type="password" name="password" required
                                       class="block w-full px-4 py-3 border border-gray-300 rounded-xl text-gray-900 placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all duration-200"
                                       placeholder="Enter your password to confirm">
                                @error('delete_password')
                                <p class="mt-2 text-sm text-red-600 flex items-center">
                                    <i class="fa-solid fa-exclamation-circle mr-1"></i>
                                    {{ $message }}
                                </p>
                                @enderror
                            </div>

                            <div class="flex justify-end">
                                <button type="submit"
                                        class="bg-red-600 text-white px-8 py-3 rounded-xl font-medium hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition-all duration-200 shadow-sm hover:shadow-md"
                                        onclick="return confirm('Are you sure you want to delete your account? This action cannot be undone.')">
                                    <i class="fa-solid fa-trash mr-2"></i>
                                    Delete Account
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <style>


    <script>

    </script>
</x-layouts.admin>
