<div>
    @if (session()->has('success'))
        <div class="mb-4 rounded-md bg-green-50 dark:bg-green-900/20 p-4">
            <p class="text-sm text-green-800 dark:text-green-200">{{ session('success') }}</p>
        </div>
    @endif

    @if (session()->has('error'))
        <div class="mb-4 rounded-md bg-red-50 dark:bg-red-900/20 p-4">
            <p class="text-sm text-red-800 dark:text-red-200">{{ session('error') }}</p>
        </div>
    @endif

    @if ($newRecoveryCodes)
        <div class="mb-6 rounded-md bg-yellow-50 dark:bg-yellow-900/20 p-4 border border-yellow-200 dark:border-yellow-800">
            <div class="flex items-start gap-3">
                <i class="fa-solid fa-exclamation-triangle text-yellow-600 dark:text-yellow-400 mt-0.5"></i>
                <div class="flex-1">
                    <h4 class="font-semibold text-yellow-800 dark:text-yellow-200 mb-2">Save Your Recovery Codes</h4>
                    <p class="text-sm text-yellow-700 dark:text-yellow-300 mb-3">
                        Store these recovery codes in a safe place. You can use them to access your account if you lose your device.
                    </p>
                    <div class="bg-white dark:bg-zinc-900 rounded p-3 mb-3">
                        <div class="grid grid-cols-2 gap-2 font-mono text-sm">
                            @foreach ($newRecoveryCodes as $code)
                                <div class="text-zinc-900 dark:text-zinc-100">{{ $code }}</div>
                            @endforeach
                        </div>
                    </div>
                    <p class="text-xs text-yellow-600 dark:text-yellow-400">These codes will not be shown again.</p>
                </div>
            </div>
        </div>
    @endif

    <div class="flex items-center gap-3 mb-6">
        <div class="p-3 {{ $twoFactorEnabled ? 'bg-green-100 dark:bg-green-900/30' : 'bg-zinc-100 dark:bg-zinc-700' }} rounded-lg">
            <i class="fa-solid fa-shield-halved {{ $twoFactorEnabled ? 'text-green-600 dark:text-green-400' : 'text-zinc-500 dark:text-zinc-400' }} text-xl"></i>
        </div>
        <div>
            <h3 class="text-lg font-semibold text-zinc-900 dark:text-white">Two-Factor Authentication</h3>
            <p class="text-sm text-zinc-600 dark:text-zinc-400">
                {{ $twoFactorEnabled ? 'Your account is protected with 2FA' : 'Add an extra layer of security to your account' }}
            </p>
        </div>
    </div>

    @if ($twoFactorEnabled)
        <!-- 2FA Enabled State -->
        @if ($recoveryCodes && count($recoveryCodes) > 0)
            <div class="mb-6 p-4 bg-zinc-50 dark:bg-zinc-900/50 rounded-lg border border-zinc-200 dark:border-zinc-700">
                <h4 class="font-semibold text-zinc-900 dark:text-white mb-2">Recovery Codes</h4>
                <p class="text-sm text-zinc-600 dark:text-zinc-400 mb-3">
                    You have {{ count($recoveryCodes) }} recovery codes remaining.
                </p>
                <form wire:submit="regenerateRecoveryCodes">
                    <div class="mb-3">
                        <label for="regenerate-password" class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-1">Confirm Password</label>
                        <input 
                            type="password" 
                            id="regenerate-password"
                            wire:model="password"
                            placeholder="Enter your password"
                            required
                            class="block w-full rounded-lg border border-zinc-200 px-3 py-2 text-sm placeholder-zinc-500 focus:border-[var(--color-accent)] focus:ring-2 focus:ring-[var(--color-accent)]/50 dark:border-zinc-600 dark:bg-zinc-900 dark:placeholder-zinc-400"
                        />
                        @error('password') <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
                    </div>
                    <button 
                        type="submit" 
                        wire:loading.attr="disabled"
                        class="inline-flex items-center gap-2 rounded-lg border border-[var(--color-accent)] px-4 py-2 text-sm font-medium text-[var(--color-accent)] hover:bg-[var(--color-accent)]/10 focus:outline-none focus:ring-2 focus:ring-[var(--color-accent)]/50 disabled:opacity-50"
                    >
                        <span wire:loading.remove wire:target="regenerateRecoveryCodes">
                            <i class="fa-solid fa-arrows-rotate"></i> Regenerate Recovery Codes
                        </span>
                        <span wire:loading wire:target="regenerateRecoveryCodes">
                            <i class="fa-solid fa-spinner fa-spin"></i> Regenerating...
                        </span>
                    </button>
                </form>
            </div>
        @endif

        <form wire:submit="disableTwoFactor" x-data x-on:submit="if (!confirm('Are you sure you want to disable two-factor authentication?')) $event.preventDefault()">
            <div class="mb-4">
                <label for="disable-password" class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-1">Confirm Password</label>
                <input 
                    type="password" 
                    id="disable-password"
                    wire:model="password"
                    placeholder="Enter your password to disable 2FA"
                    required
                    class="block w-full rounded-lg border border-zinc-200 px-3 py-2 text-sm placeholder-zinc-500 focus:border-[var(--color-accent)] focus:ring-2 focus:ring-[var(--color-accent)]/50 dark:border-zinc-600 dark:bg-zinc-900 dark:placeholder-zinc-400"
                />
                @error('password') <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
            </div>
            <button 
                type="submit" 
                wire:loading.attr="disabled"
                class="inline-flex items-center gap-2 rounded-lg bg-red-600 px-4 py-2 text-sm font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500/50 disabled:opacity-50"
            >
                <span wire:loading.remove wire:target="disableTwoFactor">
                    <i class="fa-solid fa-shield-xmark"></i> Disable Two-Factor Authentication
                </span>
                <span wire:loading wire:target="disableTwoFactor">
                    <i class="fa-solid fa-spinner fa-spin"></i> Disabling...
                </span>
            </button>
        </form>
    @else
        <!-- 2FA Setup State -->
        <div class="space-y-6">
            <div class="flex justify-center p-6 bg-zinc-50 dark:bg-zinc-900/50 rounded-lg border border-zinc-200 dark:border-zinc-700">
                <div class="text-center">
                    <div class="inline-block p-4 bg-white dark:bg-zinc-800 rounded-lg border border-zinc-200 dark:border-zinc-700">
                        {!! $qrCode !!}
                    </div>
                    <p class="mt-4 text-sm text-zinc-600 dark:text-zinc-400">
                        Can't scan? Enter this code manually:
                    </p>
                    <div class="mt-2 font-mono text-lg font-semibold text-zinc-900 dark:text-white bg-zinc-100 dark:bg-zinc-900 px-4 py-2 rounded inline-block">
                        {{ $secret }}
                    </div>
                </div>
            </div>

            <div class="bg-blue-50 dark:bg-blue-900/20 p-4 rounded-lg border border-blue-200 dark:border-blue-800">
                <h4 class="font-semibold text-blue-900 dark:text-blue-200 mb-2">Instructions:</h4>
                <ol class="list-decimal list-inside space-y-1 text-sm text-blue-800 dark:text-blue-300">
                    <li>Install an authenticator app (Google Authenticator, Authy, etc.)</li>
                    <li>Open the app and scan the QR code above</li>
                    <li>Enter the 6-digit code from your app below</li>
                </ol>
            </div>

            <form wire:submit="enableTwoFactor">
                <div class="mb-4">
                    <label for="enable-code" class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-1">Enter 6-digit code</label>
                    <input 
                        type="text" 
                        id="enable-code"
                        wire:model="code"
                        placeholder="000000"
                        maxlength="6"
                        pattern="[0-9]{6}"
                        required
                        autocomplete="off"
                        class="block w-full rounded-lg border border-zinc-200 px-3 py-2 text-sm placeholder-zinc-500 focus:border-[var(--color-accent)] focus:ring-2 focus:ring-[var(--color-accent)]/50 dark:border-zinc-600 dark:bg-zinc-900 dark:placeholder-zinc-400"
                    />
                    @error('code') <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
                </div>
                <button 
                    type="submit" 
                    wire:loading.attr="disabled"
                    class="inline-flex items-center gap-2 rounded-lg bg-[var(--color-accent)] px-4 py-2 text-sm font-medium text-[var(--color-accent-foreground)] hover:opacity-90 focus:outline-none focus:ring-2 focus:ring-[var(--color-accent)]/50 disabled:opacity-50"
                >
                    <span wire:loading.remove wire:target="enableTwoFactor">
                        <i class="fa-solid fa-shield-check"></i> Enable Two-Factor Authentication
                    </span>
                    <span wire:loading wire:target="enableTwoFactor">
                        <i class="fa-solid fa-spinner fa-spin"></i> Enabling...
                    </span>
                </button>
            </form>
        </div>
    @endif
</div>
