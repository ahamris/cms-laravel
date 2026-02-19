<x-layouts.admin title="Mail Settings">
    {{-- Header with Title --}}
    <div class="mb-6">
        <div class="flex items-center justify-between mb-4">
            <div class="flex flex-col gap-1">
                <h2>Mail Settings</h2>
                <p>Configure email settings and SMTP configuration</p>
            </div>
            <div class="w-12 h-12 bg-primary rounded-md flex items-center justify-center">
                <i class="fa-solid fa-envelope text-white text-base"></i>
            </div>
        </div>
    </div>

    {{-- Main Content --}}
    <div class="grid grid-cols-1 xl:grid-cols-12 gap-8">

        {{-- Left Column: Mail Settings --}}
        <div class="xl:col-span-8">
            {{-- Mail Configuration Card --}}
            <div class="bg-gray-50/50 rounded-md border border-gray-200 p-6 mb-6">
                <div class="flex items-center space-x-3 mb-4">
                    <div class="w-10 h-10 bg-secondary rounded-md flex items-center justify-center">
                        <i class="fa-solid fa-server text-white text-lg"></i>
                    </div>
                    <div>
                        <h3 class="text-base font-semibold text-gray-900">Mail Configuration</h3>
                        <p class="text-gray-600 text-sm">Configure SMTP settings and email preferences</p>
                    </div>
                </div>

                @if (session('success'))
                    <div class="mb-6 bg-green-50 border border-green-200 rounded-xl p-4">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <i class="fa-solid fa-check-circle text-green-500 text-lg"></i>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm font-medium text-green-800">
                                    {{ session('success') }}
                                </p>
                            </div>
                        </div>
                    </div>
                @endif

                <form method="POST" action="{{ route('admin.mail.update') }}" class="space-y-6">
                    @csrf
                    @method('PUT')

                    {{-- SMTP Settings --}}
                    <div>
                        <h4 class="mb-4 flex items-center text-gray-900 font-semibold">
                            <i class="fa-solid fa-cog text-primary mr-2"></i>
                            SMTP Configuration
                        </h4>

                        <div class="space-y-4">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label for="mail_mailer" class="block text-sm font-medium text-gray-700 mb-2">Mail Driver</label>
                                    <select id="mail_mailer" name="mail_mailer"
                                            class="block bg-white w-full px-3 py-2 border border-gray-200 rounded-md text-gray-900 focus:outline-none">
                                        <option value="smtp" {{ old('mail_mailer', $settings->mail_mailer) == 'smtp' ? 'selected' : '' }}>SMTP</option>
                                        <option value="sendmail" {{ old('mail_mailer', $settings->mail_mailer) == 'sendmail' ? 'selected' : '' }}>Sendmail</option>
                                        <option value="mailgun" {{ old('mail_mailer', $settings->mail_mailer) == 'mailgun' ? 'selected' : '' }}>Mailgun</option>
                                        <option value="ses" {{ old('mail_mailer', $settings->mail_mailer) == 'ses' ? 'selected' : '' }}>Amazon SES</option>
                                    </select>
                                    @error('mail_mailer')
                                    <p class="mt-1 text-xs text-red-600 flex items-center">
                                        <i class="fa-solid fa-exclamation-circle mr-1"></i>
                                        {{ $message }}
                                    </p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="mail_host" class="block text-sm font-medium text-gray-700 mb-2">SMTP Host</label>
                                    <input id="mail_host" type="text" name="mail_host"
                                           value="{{ old('mail_host', $settings->mail_host) }}"
                                           class="block bg-white w-full px-3 py-2 border border-gray-200 rounded-md text-gray-900 placeholder-gray-500 focus:outline-none"
                                           placeholder="smtp.mailtrap.io">
                                    @error('mail_host')
                                    <p class="mt-1 text-xs text-red-600 flex items-center">
                                        <i class="fa-solid fa-exclamation-circle mr-1"></i>
                                        {{ $message }}
                                    </p>
                                    @enderror
                                </div>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                <div>
                                    <label for="mail_port" class="block text-sm font-medium text-gray-700 mb-2">SMTP Port</label>
                                    <input id="mail_port" type="number" name="mail_port" min="1" max="65535"
                                           value="{{ old('mail_port', $settings->mail_port) }}"
                                           class="block bg-white w-full px-3 py-2 border border-gray-200 rounded-md text-gray-900 placeholder-gray-500 focus:outline-none"
                                           placeholder="587">
                                    @error('mail_port')
                                    <p class="mt-1 text-xs text-red-600 flex items-center">
                                        <i class="fa-solid fa-exclamation-circle mr-1"></i>
                                        {{ $message }}
                                    </p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="mail_encryption" class="block text-sm font-medium text-gray-700 mb-2">Encryption</label>
                                    <select id="mail_encryption" name="mail_encryption"
                                            class="block bg-white w-full px-3 py-2 border border-gray-200 rounded-md text-gray-900 focus:outline-none">
                                        <option value="">None</option>
                                        <option value="tls" {{ old('mail_encryption', $settings->mail_encryption) == 'tls' ? 'selected' : '' }}>TLS</option>
                                        <option value="ssl" {{ old('mail_encryption', $settings->mail_encryption) == 'ssl' ? 'selected' : '' }}>SSL</option>
                                    </select>
                                    @error('mail_encryption')
                                    <p class="mt-1 text-xs text-red-600 flex items-center">
                                        <i class="fa-solid fa-exclamation-circle mr-1"></i>
                                        {{ $message }}
                                    </p>
                                    @enderror
                                </div>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label for="mail_username" class="block text-sm font-medium text-gray-700 mb-2">SMTP Username</label>
                                    <input id="mail_username" type="text" name="mail_username"
                                           value="{{ old('mail_username', $settings->mail_username) }}"
                                           class="block bg-white w-full px-3 py-2 border border-gray-200 rounded-md text-gray-900 placeholder-gray-500 focus:outline-none"
                                           placeholder="your-username">
                                    @error('mail_username')
                                    <p class="mt-1 text-xs text-red-600 flex items-center">
                                        <i class="fa-solid fa-exclamation-circle mr-1"></i>
                                        {{ $message }}
                                    </p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="mail_password" class="block text-sm font-medium text-gray-700 mb-2">SMTP Password</label>
                                    <input id="mail_password" type="password" name="mail_password"
                                           value="{{ old('mail_password', $settings->mail_password) }}"
                                           class="block bg-white w-full px-3 py-2 border border-gray-200 rounded-md text-gray-900 placeholder-gray-500 focus:outline-none"
                                           placeholder="your-password">
                                    @error('mail_password')
                                    <p class="mt-1 text-xs text-red-600 flex items-center">
                                        <i class="fa-solid fa-exclamation-circle mr-1"></i>
                                        {{ $message }}
                                    </p>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Email Settings --}}
                    <div class="border-t border-gray-200 pt-6">
                        <h4 class="mb-4 flex items-center text-gray-900 font-semibold">
                            <i class="fa-solid fa-envelope text-primary mr-2"></i>
                            Email Settings
                        </h4>

                        <div class="space-y-4">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label for="mail_from_address" class="block text-sm font-medium text-gray-700 mb-2">From Email Address</label>
                                    <input id="mail_from_address" type="email" name="mail_from_address"
                                           value="{{ old('mail_from_address', $settings->mail_from_address) }}"
                                           class="block bg-white w-full px-3 py-2 border border-gray-200 rounded-md text-gray-900 placeholder-gray-500 focus:outline-none"
                                           placeholder="noreply@example.com">
                                    @error('mail_from_address')
                                    <p class="mt-1 text-xs text-red-600 flex items-center">
                                        <i class="fa-solid fa-exclamation-circle mr-1"></i>
                                        {{ $message }}
                                    </p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="mail_from_name" class="block text-sm font-medium text-gray-700 mb-2">From Name</label>
                                    <input id="mail_from_name" type="text" name="mail_from_name"
                                           value="{{ old('mail_from_name', $settings->mail_from_name) }}"
                                           class="block bg-white w-full px-3 py-2 border border-gray-200 rounded-md text-gray-900 placeholder-gray-500 focus:outline-none"
                                           placeholder="Open Publicatie">
                                    @error('mail_from_name')
                                    <p class="mt-1 text-xs text-red-600 flex items-center">
                                        <i class="fa-solid fa-exclamation-circle mr-1"></i>
                                        {{ $message }}
                                    </p>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="flex justify-end space-x-3 pt-4 border-t border-gray-200">
                        <button type="submit"
                                class="bg-primary text-white px-6 py-2 rounded-md font-medium hover:bg-primary/90 focus:outline-none text-sm">
                            <i class="fa-solid fa-save mr-2"></i>
                            Save Settings
                        </button>
                    </div>
                </form>
            </div>
        </div>

        {{-- Right Column: Test & Info --}}
        <div class="xl:col-span-4">
            {{-- Test Email Card --}}
            <div class="bg-gray-50/50 rounded-md border border-gray-200 p-6 mb-6">
                <h3 class="text-base font-semibold text-gray-900 mb-2 flex items-center">
                    <i class="fa-solid fa-paper-plane text-primary mr-2"></i>
                    Test Email
                </h3>
                <p class="text-gray-600 text-xs mb-3">Send a test email to verify your configuration</p>

                <form id="testEmailForm" class="space-y-4">
                    @csrf
                    <div>
                        <label for="test_email" class="block text-sm font-medium text-gray-700 mb-2">Test Email Address</label>
                        <input id="test_email" type="email" name="test_email"
                               class="block bg-white w-full px-3 py-2 border border-gray-200 rounded-md text-gray-900 placeholder-gray-500 focus:outline-none"
                               placeholder="test@example.com" required>
                    </div>

                    <button type="submit" id="testEmailBtn"
                            class="w-full bg-secondary text-white px-4 py-2 rounded-md font-medium hover:bg-secondary/90 focus:outline-none text-sm">
                        <i class="fa-solid fa-paper-plane mr-2"></i>
                        Send Test Email
                    </button>
                </form>

                <div id="testResult" class="mt-4 hidden"></div>
            </div>

            {{-- Mail Info Card --}}
            <div class="bg-white rounded-md border border-gray-200 p-6">
                <h3 class="text-base font-semibold text-gray-900 mb-3 flex items-center">
                    <i class="fa-solid fa-info-circle text-primary mr-2"></i>
                    Configuration Help
                </h3>
                <div class="space-y-3 text-sm">
                    <div>
                        <h4 class="font-medium text-gray-900 mb-1">Common SMTP Ports:</h4>
                        <ul class="text-gray-600 space-y-1">
                            <li>• <strong>587:</strong> TLS (recommended)</li>
                            <li>• <strong>465:</strong> SSL</li>
                            <li>• <strong>25:</strong> No encryption</li>
                        </ul>
                    </div>

                    <div>
                        <h4 class="font-medium text-gray-900 mb-1">Popular Providers:</h4>
                        <ul class="text-gray-600 space-y-1">
                            <li>• <strong>Gmail:</strong> smtp.gmail.com:587</li>
                            <li>• <strong>Outlook:</strong> smtp-mail.outlook.com:587</li>
                            <li>• <strong>Yahoo:</strong> smtp.mail.yahoo.com:587</li>
                        </ul>
                    </div>

                    <div class="bg-yellow-50 border border-yellow-200 rounded-md p-3">
                        <p class="text-yellow-800 text-xs">
                            <i class="fa-solid fa-exclamation-triangle mr-1"></i>
                            <strong>Note:</strong> Save settings before testing email configuration.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
document.getElementById('testEmailForm').addEventListener('submit', function(e) {
    e.preventDefault();

    const btn = document.getElementById('testEmailBtn');
    const result = document.getElementById('testResult');
    const email = document.getElementById('test_email').value;

    // Update button state
    btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin mr-2"></i>Sending...';
    btn.disabled = true;

    // Hide previous result
    result.classList.add('hidden');

    // Send test email
    fetch('{{ route("admin.mail.test") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({
            test_email: email
        })
    })
    .then(response => response.json())
    .then(data => {
        // Show result
        result.classList.remove('hidden');

        if (data.success) {
            result.innerHTML = `
                <div class="bg-green-50 border border-green-200 rounded-lg p-3">
                    <div class="flex items-center">
                        <i class="fa-solid fa-check-circle text-green-500 mr-2"></i>
                        <span class="text-green-800 text-sm">${data.message}</span>
                    </div>
                </div>
            `;
        } else {
            result.innerHTML = `
                <div class="bg-red-50 border border-red-200 rounded-lg p-3">
                    <div class="flex items-start">
                        <i class="fa-solid fa-exclamation-circle text-red-500 mr-2 mt-0.5"></i>
                        <span class="text-red-800 text-sm">${data.message}</span>
                    </div>
                </div>
            `;
        }
    })
    .catch(error => {
        result.classList.remove('hidden');
        result.innerHTML = `
            <div class="bg-red-50 border border-red-200 rounded-lg p-3">
                <div class="flex items-start">
                    <i class="fa-solid fa-exclamation-circle text-red-500 mr-2 mt-0.5"></i>
                    <span class="text-red-800 text-sm">Error: ${error.message}</span>
                </div>
            </div>
        `;
    })
    .finally(() => {
        // Reset button
        btn.innerHTML = '<i class="fa-solid fa-paper-plane mr-2"></i>Send Test Email';
        btn.disabled = false;
    });
});
</script>
    </script>
</x-layouts.admin>
