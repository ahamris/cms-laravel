<x-layouts.admin title="Edit Profile">
<div class="space-y-6">
    {{-- Header --}}
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Edit Profile</h1>
            <p class="text-gray-600">Update your account information and settings</p>
        </div>
        <a href="{{ route('admin.profile.show') }}" 
           class="bg-gray-600 text-white px-4 py-2 rounded-lg hover:bg-gray-700 transition-colors duration-200 flex items-center space-x-2">
            <i class="fa-solid fa-arrow-left"></i>
            <span>Back to Profile</span>
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
        {{-- Main Form --}}
        <div class="lg:col-span-2 space-y-6">
            {{-- Profile Information Form --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-200">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                        <i class="fa-solid fa-user mr-2 text-blue-500"></i>
                        Profile Information
                    </h3>
                </div>
                <form action="{{ route('admin.profile.update') }}" method="POST" class="p-6">
                    @csrf
                    @method('PUT')
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700 mb-1">
                                Full Name <span class="text-red-500">*</span>
                            </label>
                            <input type="text" 
                                   id="name" 
                                   name="name" 
                                   value="{{ old('name', $user->name) }}"
                                   class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-primary focus:border-primary @error('name') border-red-500 @enderror"
                                   required>
                            @error('name')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700 mb-1">
                                Email Address <span class="text-red-500">*</span>
                            </label>
                            <input type="email" 
                                   id="email" 
                                   name="email" 
                                   value="{{ old('email', $user->email) }}"
                                   class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-primary focus:border-primary @error('email') border-red-500 @enderror"
                                   required>
                            @error('email')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                            @if($user->email_verified_at)
                                <p class="text-green-600 text-xs mt-1">
                                    <i class="fa-solid fa-check-circle mr-1"></i>
                                    Email verified
                                </p>
                            @else
                                <p class="text-yellow-600 text-xs mt-1">
                                    <i class="fa-solid fa-exclamation-triangle mr-1"></i>
                                    Email not verified
                                </p>
                            @endif
                        </div>
                    </div>
                    
                    <div class="mt-6 flex items-center justify-end space-x-4">
                        <button type="button" 
                                onclick="window.location.href='{{ route('admin.profile.show') }}'"
                                class="bg-gray-500 text-white px-6 py-2 rounded-lg hover:bg-gray-600 transition-colors duration-200">
                            Cancel
                        </button>
                        <button type="submit" 
                                class="bg-primary text-white px-6 py-2 rounded-lg hover:bg-primary/80 transition-colors duration-200 flex items-center space-x-2">
                            <i class="fa-solid fa-save"></i>
                            <span>Update Profile</span>
                        </button>
                    </div>
                </form>
            </div>

            {{-- Password Change Form --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-200">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                        <i class="fa-solid fa-lock mr-2 text-green-500"></i>
                        Change Password
                    </h3>
                </div>
                <form action="{{ route('admin.password.update') }}" method="POST" class="p-6">
                    @csrf
                    @method('PUT')
                    
                    <div class="space-y-6">
                        <div>
                            <label for="current_password" class="block text-sm font-medium text-gray-700 mb-1">
                                Current Password <span class="text-red-500">*</span>
                            </label>
                            <input type="password" 
                                   id="current_password" 
                                   name="current_password" 
                                   class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-primary focus:border-primary @error('current_password') border-red-500 @enderror"
                                   required>
                            @error('current_password')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="password" class="block text-sm font-medium text-gray-700 mb-1">
                                    New Password <span class="text-red-500">*</span>
                                </label>
                                <input type="password" 
                                       id="password" 
                                       name="password" 
                                       class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-primary focus:border-primary @error('password') border-red-500 @enderror"
                                       required>
                                @error('password')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            
                            <div>
                                <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-1">
                                    Confirm New Password <span class="text-red-500">*</span>
                                </label>
                                <input type="password" 
                                       id="password_confirmation" 
                                       name="password_confirmation" 
                                       class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-primary focus:border-primary"
                                       required>
                            </div>
                        </div>
                        
                        <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                            <div class="flex">
                                <i class="fa-solid fa-info-circle text-blue-500 mt-0.5 mr-3"></i>
                                <div>
                                    <h4 class="text-sm font-medium text-blue-900">Password Requirements</h4>
                                    <ul class="text-sm text-blue-700 mt-1 space-y-1">
                                        <li>• At least 8 characters long</li>
                                        <li>• Include uppercase and lowercase letters</li>
                                        <li>• Include at least one number</li>
                                        <li>• Include at least one special character</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mt-6 flex items-center justify-end">
                        <button type="submit" 
                                class="bg-green-600 text-white px-6 py-2 rounded-lg hover:bg-green-700 transition-colors duration-200 flex items-center space-x-2">
                            <i class="fa-solid fa-key"></i>
                            <span>Update Password</span>
                        </button>
                    </div>
                </form>
            </div>

            {{-- Danger Zone --}}
            <div class="bg-white rounded-xl shadow-sm border border-red-200">
                <div class="px-6 py-4 border-b border-red-200">
                    <h3 class="text-lg font-semibold text-red-900 flex items-center">
                        <i class="fa-solid fa-exclamation-triangle mr-2 text-red-500"></i>
                        Danger Zone
                    </h3>
                </div>
                <div class="p-6">
                    <div class="bg-red-50 border border-red-200 rounded-lg p-4">
                        <div class="flex items-start">
                            <i class="fa-solid fa-trash text-red-500 mt-1 mr-3"></i>
                            <div class="flex-1">
                                <h4 class="text-sm font-medium text-red-900">Delete Account</h4>
                                <p class="text-sm text-red-700 mt-1">
                                    Once you delete your account, all of your data will be permanently removed. 
                                    This action cannot be undone.
                                </p>
                                <button type="button" 
                                        onclick="confirmDelete()"
                                        class="mt-3 bg-red-600 text-white px-4 py-2 rounded-lg hover:bg-red-700 transition-colors duration-200 text-sm">
                                    Delete Account
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Sidebar --}}
        <div class="space-y-6">
            {{-- Profile Preview --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-200">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                        <i class="fa-solid fa-eye mr-2 text-indigo-500"></i>
                        Profile Preview
                    </h3>
                </div>
                <div class="p-6 text-center">
                    <div class="w-20 h-20 bg-gradient-to-br from-primary to-primary/80 rounded-full flex items-center justify-center mx-auto mb-4 shadow-lg">
                        <span class="text-white font-bold text-xl">{{ substr($user->name, 0, 2) }}</span>
                    </div>
                    <h4 class="text-lg font-semibold text-gray-900">{{ $user->name }}</h4>
                    <p class="text-sm text-gray-600">{{ $user->email }}</p>
                    <p class="text-xs text-primary font-medium mt-1">Administrator</p>
                </div>
            </div>

            {{-- Account Stats --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-200">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                        <i class="fa-solid fa-chart-bar mr-2 text-green-500"></i>
                        Account Stats
                    </h3>
                </div>
                <div class="p-6">
                    <div class="space-y-4">
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-gray-600">Member Since</span>
                            <span class="text-sm font-medium text-gray-900">{{ $user->created_at->format('M Y') }}</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-gray-600">Profile Completion</span>
                            <span class="text-sm font-medium text-green-600">85%</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-gray-600">Security Score</span>
                            <span class="text-sm font-medium text-blue-600">Good</span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Help & Support --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-200">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                        <i class="fa-solid fa-question-circle mr-2 text-yellow-500"></i>
                        Help & Support
                    </h3>
                </div>
                <div class="p-6">
                    <div class="space-y-3">
                        <a href="#" class="flex items-center text-sm text-gray-600 hover:text-primary transition-colors duration-200">
                            <i class="fa-solid fa-book mr-2"></i>
                            User Guide
                        </a>
                        <a href="#" class="flex items-center text-sm text-gray-600 hover:text-primary transition-colors duration-200">
                            <i class="fa-solid fa-envelope mr-2"></i>
                            Contact Support
                        </a>
                        <a href="#" class="flex items-center text-sm text-gray-600 hover:text-primary transition-colors duration-200">
                            <i class="fa-solid fa-shield-alt mr-2"></i>
                            Privacy Policy
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Delete Confirmation Modal --}}
<div id="deleteModal" class="fixed inset-0 bg-black/50 hidden z-50 flex items-center justify-center">
    <div class="bg-white rounded-xl shadow-xl max-w-md w-full mx-4">
        <div class="p-6">
            <div class="flex items-center mb-4">
                <i class="fa-solid fa-exclamation-triangle text-red-500 text-2xl mr-3"></i>
                <h3 class="text-lg font-semibold text-gray-900">Confirm Account Deletion</h3>
            </div>
            <p class="text-gray-600 mb-6">
                Are you sure you want to delete your account? This action cannot be undone and all your data will be permanently removed.
            </p>
            <form action="{{ route('admin.profile.destroy') }}" method="POST">
                @csrf
                @method('DELETE')
                <div class="mb-4">
                    <label for="delete_password" class="block text-sm font-medium text-gray-700 mb-1">
                        Enter your password to confirm
                    </label>
                    <input type="password" 
                           id="delete_password" 
                           name="password" 
                           class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-red-500 focus:border-red-500"
                           required>
                </div>
                <div class="flex items-center justify-end space-x-4">
                    <button type="button" 
                            onclick="closeDeleteModal()"
                            class="bg-gray-500 text-white px-4 py-2 rounded-lg hover:bg-gray-600 transition-colors duration-200">
                        Cancel
                    </button>
                    <button type="submit" 
                            class="bg-red-600 text-white px-4 py-2 rounded-lg hover:bg-red-700 transition-colors duration-200">
                        Delete Account
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function confirmDelete() {
    document.getElementById('deleteModal').classList.remove('hidden');
}

function closeDeleteModal() {
    document.getElementById('deleteModal').classList.add('hidden');
}

// Close modal when clicking outside
document.getElementById('deleteModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeDeleteModal();
    }
});
</script>
</x-layouts.admin>
