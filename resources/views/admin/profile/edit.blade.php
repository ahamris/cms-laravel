<x-layouts.admin title="My Profile">
    <div class="space-y-6">
        <!-- Page Header -->
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-zinc-900 dark:text-white mb-2">My Profile</h1>
                <p class="text-zinc-600 dark:text-zinc-400">Manage your account information and security settings</p>
            </div>
        </div>

        @if (session('success'))
            <div class="rounded-md bg-green-50 dark:bg-green-900/20 p-4">
                <p class="text-sm text-green-800 dark:text-green-200">{{ session('success') }}</p>
            </div>
        @endif

        @if (session('error'))
            <div class="rounded-md bg-red-50 dark:bg-red-900/20 p-4">
                <p class="text-sm text-red-800 dark:text-red-200">{{ session('error') }}</p>
            </div>
        @endif

        <!-- Tabs -->
        <x-ui.tabs :tabs="['profile' => 'Profile Information', 'security' => 'Two-Factor Authentication']" active="profile">
            <!-- Profile Tab -->
            <x-ui.tab-panel name="profile">
                <form action="{{ route('admin.profile.update') }}" method="POST" class="space-y-6">
                    @csrf
                    @method('PUT')

                    <!-- Name Field -->
                    <div>
                        <x-input 
                            label="Name" 
                            name="name" 
                            type="text" 
                            placeholder="Enter your name"
                            icon="user"
                            value="{{ old('name', $user->name) }}"
                            required
                        />
                        @error('name')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Email Field -->
                    <div>
                        <x-input 
                            label="Email" 
                            name="email" 
                            type="email" 
                            placeholder="your@example.com"
                            icon="envelope"
                            value="{{ old('email', $user->email) }}"
                            required
                        />
                        @error('email')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Divider -->
                    <div class="border-t border-zinc-200 dark:border-zinc-700 pt-6">
                        <h3 class="text-lg font-semibold text-zinc-900 dark:text-white mb-4">Change Password</h3>
                        <p class="text-sm text-zinc-600 dark:text-zinc-400 mb-4">Leave blank if you don't want to change your password</p>
                    </div>

                    <!-- Password Field -->
                    <div>
                        <x-input 
                            label="New Password" 
                            name="password" 
                            type="password" 
                            placeholder="Enter new password"
                            icon="lock"
                        />
                        @error('password')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Password Confirmation Field -->
                    <div>
                        <x-input 
                            label="Confirm New Password" 
                            name="password_confirmation" 
                            type="password" 
                            placeholder="Confirm new password"
                            icon="lock"
                        />
                    </div>

                    <!-- Form Actions -->
                    <div class="flex items-center justify-end gap-3 pt-4 border-t border-zinc-200 dark:border-zinc-700">
                        <x-button variant="primary" type="submit" icon="save" icon-position="left">Update Profile</x-button>
                    </div>
                </form>
            </x-ui.tab-panel>

            <!-- Two-Factor Authentication Tab -->
            <x-ui.tab-panel name="security">
                <livewire:admin.two-factor-settings />
            </x-ui.tab-panel>
        </x-ui.tabs>
    </div>
</x-layouts.admin>
