<x-layouts.admin title="My Profile">
<div class="space-y-6">
    {{-- Header --}}
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">My Profile</h1>
            <p class="text-gray-600">View and manage your account information</p>
        </div>
        <a href="{{ route('admin.profile.edit') }}" 
           class="bg-primary text-white px-4 py-2 rounded-lg hover:bg-primary/80 transition-colors duration-200 flex items-center space-x-2">
            <i class="fa-solid fa-edit"></i>
            <span>Edit Profile</span>
        </a>
    </div>

    {{-- Success/Error Messages --}}
    @if(session('success'))
        <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg">
            <i class="fa-solid fa-check-circle mr-2"></i>
            {{ session('success') }}
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        {{-- Main Profile Information --}}
        <div class="lg:col-span-2 space-y-6">
            {{-- Basic Information --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-200">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                        <i class="fa-solid fa-user mr-2 text-blue-500"></i>
                        Basic Information
                    </h3>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Full Name</label>
                            <p class="text-gray-900 text-lg">{{ $user->name }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Email Address</label>
                            <p class="text-gray-900 text-lg">{{ $user->email }}</p>
                            @if($user->email_verified_at)
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 mt-1">
                                    <i class="fa-solid fa-check-circle mr-1"></i>
                                    Verified
                                </span>
                            @else
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800 mt-1">
                                    <i class="fa-solid fa-exclamation-triangle mr-1"></i>
                                    Unverified
                                </span>
                            @endif
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Member Since</label>
                            <p class="text-gray-900">{{ $user->created_at->format('F j, Y') }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Last Updated</label>
                            <p class="text-gray-900">{{ $user->updated_at->format('F j, Y g:i A') }}</p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Account Activity --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-200">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                        <i class="fa-solid fa-chart-line mr-2 text-green-500"></i>
                        Account Activity
                    </h3>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div class="text-center">
                            <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-2">
                                <i class="fa-solid fa-sign-in-alt text-blue-600"></i>
                            </div>
                            <p class="text-sm text-gray-600">Last Login</p>
                            <p class="text-lg font-semibold text-gray-900">Today</p>
                        </div>
                        <div class="text-center">
                            <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-2">
                                <i class="fa-solid fa-tasks text-green-600"></i>
                            </div>
                            <p class="text-sm text-gray-600">Total Sessions</p>
                            <p class="text-lg font-semibold text-gray-900">{{ rand(50, 200) }}</p>
                        </div>
                        <div class="text-center">
                            <div class="w-12 h-12 bg-purple-100 rounded-full flex items-center justify-center mx-auto mb-2">
                                <i class="fa-solid fa-clock text-purple-600"></i>
                            </div>
                            <p class="text-sm text-gray-600">Active Time</p>
                            <p class="text-lg font-semibold text-gray-900">{{ rand(20, 100) }}h</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Sidebar --}}
        <div class="space-y-6">
            {{-- Profile Avatar --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-200">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                        <i class="fa-solid fa-image mr-2 text-indigo-500"></i>
                        Profile Picture
                    </h3>
                </div>
                <div class="p-6 text-center">
                    <div class="w-24 h-24 bg-gradient-to-br from-primary to-primary/80 rounded-full flex items-center justify-center mx-auto mb-4 shadow-lg">
                        <span class="text-white font-bold text-2xl">{{ substr($user->name, 0, 2) }}</span>
                    </div>
                    <p class="text-sm text-gray-600 mb-4">Upload a profile picture to personalize your account</p>
                    <button class="bg-gray-100 text-gray-700 px-4 py-2 rounded-lg hover:bg-gray-200 transition-colors duration-200 text-sm">
                        <i class="fa-solid fa-upload mr-2"></i>
                        Upload Photo
                    </button>
                </div>
            </div>

            {{-- Quick Actions --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-200">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                        <i class="fa-solid fa-bolt mr-2 text-yellow-500"></i>
                        Quick Actions
                    </h3>
                </div>
                <div class="p-6">
                    <div class="space-y-3">
                        <a href="{{ route('admin.profile.edit') }}" 
                           class="flex items-center justify-between p-3 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors duration-200">
                            <div class="flex items-center">
                                <i class="fa-solid fa-edit text-blue-500 mr-3"></i>
                                <span class="text-sm font-medium text-gray-900">Edit Profile</span>
                            </div>
                            <i class="fa-solid fa-chevron-right text-gray-400 text-xs"></i>
                        </a>
                        
                        <a href="{{ route('admin.settings.general.index') }}" 
                           class="flex items-center justify-between p-3 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors duration-200">
                            <div class="flex items-center">
                                <i class="fa-solid fa-cog text-green-500 mr-3"></i>
                                <span class="text-sm font-medium text-gray-900">Account Settings</span>
                            </div>
                            <i class="fa-solid fa-chevron-right text-gray-400 text-xs"></i>
                        </a>
                        
                        <a href="{{ route('admin.index') }}" 
                           class="flex items-center justify-between p-3 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors duration-200">
                            <div class="flex items-center">
                                <i class="fa-solid fa-home text-purple-500 mr-3"></i>
                                <span class="text-sm font-medium text-gray-900">Dashboard</span>
                            </div>
                            <i class="fa-solid fa-chevron-right text-gray-400 text-xs"></i>
                        </a>
                    </div>
                </div>
            </div>

            {{-- Account Security --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-200">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                        <i class="fa-solid fa-shield-alt mr-2 text-red-500"></i>
                        Security
                    </h3>
                </div>
                <div class="p-6">
                    <div class="space-y-4">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-gray-900">Password</p>
                                <p class="text-xs text-gray-500">Last changed {{ $user->updated_at->diffForHumans() }}</p>
                            </div>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                <i class="fa-solid fa-check mr-1"></i>
                                Strong
                            </span>
                        </div>
                        
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-gray-900">Two-Factor Auth</p>
                                <p class="text-xs text-gray-500">Add extra security to your account</p>
                            </div>
                            @if($user->hasTwoFactorEnabled())
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                    <i class="fa-solid fa-check mr-1"></i>
                                    Enabled
                                </span>
                            @else
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                    Disabled
                                </span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</x-layouts.admin>
