<x-layouts.admin title="Two-Factor Authentication">
<div class="space-y-6">
    {{-- Header --}}
    <div class="flex items-center justify-between">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 bg-primary rounded-md flex items-center justify-center">
                <i class="fa-solid fa-shield-halved text-white text-base"></i>
            </div>
            <div class="flex flex-col gap-1">
                <h2>Two-Factor Authentication</h2>
                <p>Add an extra layer of security to your account</p>
            </div>
        </div>
        <div class="flex items-center space-x-2">
            @if($hasTwoFactor)
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                    <i class="fa-solid fa-shield-check mr-1"></i>
                    Enabled
                </span>
            @else
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                    <i class="fa-solid fa-shield-exclamation mr-1"></i>
                    Disabled
                </span>
            @endif
        </div>
    </div>

    {{-- Success/Error Messages --}}
    <div id="alertContainer"></div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        {{-- Two-Factor Status --}}
        <div class="bg-gray-50/50 rounded-md border border-gray-200">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-base font-semibold text-gray-900 flex items-center">
                    <i class="fa-solid fa-shield-alt mr-2 text-blue-500"></i>
                    Authentication Status
                </h3>
            </div>
            <div class="p-6">
                @if($hasTwoFactor)
                    <div class="flex items-start space-x-4">
                        <div class="w-10 h-10 bg-green-100 rounded-full flex items-center justify-center flex-shrink-0">
                            <i class="fa-solid fa-check text-green-600 text-base"></i>
                        </div>
                        <div class="flex-1">
                            <h4 class="text-sm font-semibold text-gray-900">Two-Factor Authentication is Enabled</h4>
                            <p class="text-gray-600 text-sm mt-1">Your account is protected with two-factor authentication.</p>
                            <div class="mt-3 space-y-2">
                                <button onclick="showRecoveryCodes()" 
                                        class="w-full bg-secondary text-white px-4 py-2 rounded-md hover:bg-secondary/90 transition-colors duration-200 text-sm">
                                    <i class="fa-solid fa-key mr-2"></i>
                                    View Recovery Codes
                                </button>
                                <button onclick="generateNewRecoveryCodes()" 
                                        class="w-full bg-yellow-600 text-white px-4 py-2 rounded-md hover:bg-yellow-700 transition-colors duration-200 text-sm">
                                    <i class="fa-solid fa-refresh mr-2"></i>
                                    Generate New Codes
                                </button>
                                <button onclick="testTwoFactor()" 
                                        class="w-full bg-green-600 text-white px-4 py-2 rounded-md hover:bg-green-700 transition-colors duration-200 text-sm">
                                    <i class="fa-solid fa-vial mr-2"></i>
                                    Test Code
                                </button>
                                <button onclick="disableTwoFactor()" 
                                        class="w-full bg-red-600 text-white px-4 py-2 rounded-md hover:bg-red-700 transition-colors duration-200 text-sm">
                                    <i class="fa-solid fa-times mr-2"></i>
                                    Disable 2FA
                                </button>
                            </div>
                        </div>
                    </div>
                @else
                    <div class="flex items-start space-x-4">
                        <div class="w-10 h-10 bg-yellow-100 rounded-full flex items-center justify-center flex-shrink-0">
                            <i class="fa-solid fa-exclamation-triangle text-yellow-600 text-base"></i>
                        </div>
                        <div class="flex-1">
                            <h4 class="text-sm font-semibold text-gray-900">Two-Factor Authentication is Disabled</h4>
                            <p class="text-gray-600 text-sm mt-1">Secure your account by enabling two-factor authentication.</p>
                            <div class="mt-4">
                                <button onclick="enableTwoFactor()" 
                                        class="w-full bg-primary text-white px-6 py-2 rounded-md hover:bg-primary/80 transition-colors duration-200 text-sm">
                                    <i class="fa-solid fa-shield-alt mr-2"></i>
                                    Enable Two-Factor Authentication
                                </button>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>

        {{-- Security Tips --}}
        <div class="bg-white rounded-md border border-gray-200">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-base font-semibold text-gray-900 flex items-center">
                    <i class="fa-solid fa-lightbulb mr-2 text-yellow-500"></i>
                    Security Tips
                </h3>
            </div>
            <div class="p-6">
                <div class="space-y-3">
                    <div class="flex items-start space-x-3">
                        <i class="fa-solid fa-mobile-alt text-blue-500 mt-1"></i>
                        <div>
                            <h4 class="text-sm font-medium text-gray-900">Use a Trusted Device</h4>
                            <p class="text-xs text-gray-600">Install your authenticator app on a device you always have with you.</p>
                        </div>
                    </div>
                    <div class="flex items-start space-x-3">
                        <i class="fa-solid fa-backup text-green-500 mt-1"></i>
                        <div>
                            <h4 class="text-sm font-medium text-gray-900">Backup Your Codes</h4>
                            <p class="text-xs text-gray-600">Save your recovery codes in multiple secure locations.</p>
                        </div>
                    </div>
                    <div class="flex items-start space-x-3">
                        <i class="fa-solid fa-sync text-purple-500 mt-1"></i>
                        <div>
                            <h4 class="text-sm font-medium text-gray-900">Keep Apps Updated</h4>
                            <p class="text-xs text-gray-600">Regularly update your authenticator app for security.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Enable 2FA Modal --}}
<div id="enableModal" class="fixed inset-0 bg-black/50 hidden z-50 flex items-center justify-center">
    <div class="bg-white rounded-md border border-gray-200 shadow-xl max-w-md w-full mx-4">
        <div class="p-6">
            <div class="flex items-center mb-4">
                <i class="fa-solid fa-shield-alt text-primary text-2xl mr-3"></i>
                <h3 class="text-base font-semibold text-gray-900">Enable Two-Factor Authentication</h3>
            </div>
            
            <div id="qrStep" class="hidden">
                <p class="text-gray-600 mb-4">Scan this QR code with your authenticator app:</p>
                <div class="text-center mb-4">
                    <div id="qrCode" class="inline-block p-4 bg-white border-2 border-gray-200 rounded-lg"></div>
                </div>
                <p class="text-sm text-gray-500 mb-4">Or manually enter this secret key:</p>
                <div class="bg-gray-100 p-3 rounded-md mb-4 border border-gray-200">
                    <code id="secretKey" class="text-xs font-mono break-all"></code>
                </div>
                
                <div class="space-y-4">
                    <div>
                        <label for="confirmCode" class="block text-sm font-medium text-gray-700 mb-2">
                            Enter the 6-digit code from your app
                        </label>
                        <input type="text" 
                               id="confirmCode" 
                               maxlength="6"
                               class="w-full border border-gray-200 rounded-md px-3 py-2 focus:outline-none text-center text-base font-mono"
                               placeholder="000000">
                    </div>
                    <div>
                        <label for="confirmPassword" class="block text-sm font-medium text-gray-700 mb-2">
                            Confirm your password
                        </label>
                        <input type="password" 
                               id="confirmPassword" 
                               class="w-full border border-gray-200 rounded-md px-3 py-2 focus:outline-none">
                    </div>
                </div>
            </div>
            
            <div class="flex items-center justify-end space-x-3 mt-6 border-t border-gray-200 pt-4">
                <button type="button" 
                        onclick="closeEnableModal()"
                        class="px-5 py-2 rounded-md bg-white border border-gray-200 text-gray-700 text-sm">
                    Cancel
                </button>
                <button id="confirmButton" 
                        onclick="confirmTwoFactor()"
                        class="px-5 py-2 rounded-md bg-primary text-white text-sm hidden">
                    Enable 2FA
                </button>
            </div>
        </div>
    </div>
</div>

{{-- Password Confirmation Modal --}}
<div id="passwordModal" class="fixed inset-0 bg-black/50 hidden z-50 flex items-center justify-center">
    <div class="bg-white rounded-md border border-gray-200 shadow-xl max-w-md w-full mx-4">
        <div class="p-6">
            <div class="flex items-center mb-4">
                <i class="fa-solid fa-lock text-yellow-500 text-2xl mr-3"></i>
                <h3 id="passwordModalTitle" class="text-base font-semibold text-gray-900">Confirm Password</h3>
            </div>
            <p id="passwordModalText" class="text-gray-600 text-sm mb-4">Please enter your password to continue.</p>
            <div>
                <label for="modalPassword" class="block text-sm font-medium text-gray-700 mb-2">Password</label>
                <input type="password" 
                       id="modalPassword" 
                       class="w-full border border-gray-200 rounded-md px-3 py-2 focus:outline-none">
            </div>
            <div class="flex items-center justify-end space-x-3 mt-6 border-t border-gray-200 pt-4">
                <button type="button" 
                        onclick="closePasswordModal()"
                        class="px-5 py-2 rounded-md bg-white border border-gray-200 text-gray-700 text-sm">
                    Cancel
                </button>
                <button id="passwordConfirmButton" 
                        class="px-5 py-2 rounded-md bg-primary text-white text-sm">
                    Confirm
                </button>
            </div>
        </div>
    </div>
</div>

{{-- Test Code Modal --}}
<div id="testModal" class="fixed inset-0 bg-black/50 hidden z-50 flex items-center justify-center">
    <div class="bg-white rounded-md border border-gray-200 shadow-xl max-w-md w-full mx-4">
        <div class="p-6">
            <div class="flex items-center mb-4">
                <i class="fa-solid fa-vial text-green-500 text-2xl mr-3"></i>
                <h3 class="text-base font-semibold text-gray-900">Test Two-Factor Code</h3>
            </div>
            <p class="text-gray-600 text-sm mb-4">Enter a code from your authenticator app to test if it's working correctly.</p>
            <div>
                <label for="testCode" class="block text-sm font-medium text-gray-700 mb-2">6-digit code</label>
                <input type="text" 
                       id="testCode" 
                       maxlength="6"
                       class="w-full border border-gray-200 rounded-md px-3 py-2 focus:outline-none text-center text-base font-mono"
                       placeholder="000000">
            </div>
            <div class="flex items-center justify-end space-x-3 mt-6 border-t border-gray-200 pt-4">
                <button type="button" 
                        onclick="closeTestModal()"
                        class="px-5 py-2 rounded-md bg-white border border-gray-200 text-gray-700 text-sm">
                    Cancel
                </button>
                <button onclick="submitTestCode()" 
                        class="px-5 py-2 rounded-md bg-green-600 text-white text-sm">
                    Test Code
                </button>
            </div>
        </div>
    </div>
</div>

<script>
let currentAction = null;

function showAlert(message, type = 'success') {
    const alertContainer = document.getElementById('alertContainer');
    const alertClass = type === 'success' ? 'bg-green-50 border-green-200 text-green-700' : 'bg-red-50 border-red-200 text-red-700';
    const iconClass = type === 'success' ? 'fa-check-circle' : 'fa-exclamation-circle';
    
    alertContainer.innerHTML = `
        <div class="${alertClass} px-4 py-3 rounded-lg border">
            <i class="fa-solid ${iconClass} mr-2"></i>
            ${message}
        </div>
    `;
    
    setTimeout(() => {
        alertContainer.innerHTML = '';
    }, 5000);
}

function enableTwoFactor() {
    fetch('/admin/security/two-factor/enable', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            document.getElementById('secretKey').textContent = data.secret;
            document.getElementById('qrCode').innerHTML = data.qr_code_svg;
            
            document.getElementById('qrStep').classList.remove('hidden');
            document.getElementById('confirmButton').classList.remove('hidden');
            document.getElementById('enableModal').classList.remove('hidden');
        } else {
            showAlert(data.message, 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showAlert('An error occurred while enabling 2FA', 'error');
    });
}

function confirmTwoFactor() {
    const code = document.getElementById('confirmCode').value;
    const password = document.getElementById('confirmPassword').value;
    
    if (!code || !password) {
        showAlert('Please fill in all fields', 'error');
        return;
    }
    
    fetch('/admin/security/two-factor/confirm', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({
            code: code,
            password: password
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showAlert(data.message);
            closeEnableModal();
            setTimeout(() => location.reload(), 1000);
        } else {
            showAlert(data.message, 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showAlert('An error occurred while confirming 2FA', 'error');
    });
}

function disableTwoFactor() {
    currentAction = 'disable';
    document.getElementById('passwordModalTitle').textContent = 'Disable Two-Factor Authentication';
    document.getElementById('passwordModalText').textContent = 'Enter your password to disable two-factor authentication.';
    document.getElementById('passwordModal').classList.remove('hidden');
    
    document.getElementById('passwordConfirmButton').onclick = function() {
        const password = document.getElementById('modalPassword').value;
        
        if (!password) {
            showAlert('Please enter your password', 'error');
            return;
        }
        
        fetch('/admin/security/two-factor/disable', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({ password: password })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showAlert(data.message);
                closePasswordModal();
                setTimeout(() => location.reload(), 1000);
            } else {
                showAlert(data.message, 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showAlert('An error occurred while disabling 2FA', 'error');
        });
    };
}

function showRecoveryCodes() {
    currentAction = 'showCodes';
    document.getElementById('passwordModalTitle').textContent = 'View Recovery Codes';
    document.getElementById('passwordModalText').textContent = 'Enter your password to view your recovery codes.';
    document.getElementById('passwordModal').classList.remove('hidden');
    
    document.getElementById('passwordConfirmButton').onclick = function() {
        const password = document.getElementById('modalPassword').value;
        
        if (!password) {
            showAlert('Please enter your password', 'error');
            return;
        }
        
        fetch('/admin/security/two-factor/recovery-codes/show', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({ password: password })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                closePasswordModal();
                let codesHtml = '<div class="bg-gray-100 p-4 rounded-lg"><h4 class="font-semibold mb-2">Recovery Codes:</h4><div class="grid grid-cols-2 gap-2 font-mono text-sm">';
                data.recovery_codes.forEach(code => {
                    codesHtml += `<div class="bg-white p-2 rounded border">${code}</div>`;
                });
                codesHtml += '</div><p class="text-xs text-gray-600 mt-2">Save these codes in a secure location. Each code can only be used once.</p></div>';
                
                showAlert(codesHtml);
            } else {
                showAlert(data.message, 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showAlert('An error occurred while fetching recovery codes', 'error');
        });
    };
}

function generateNewRecoveryCodes() {
    currentAction = 'generateCodes';
    document.getElementById('passwordModalTitle').textContent = 'Generate New Recovery Codes';
    document.getElementById('passwordModalText').textContent = 'Enter your password to generate new recovery codes. This will invalidate your existing codes.';
    document.getElementById('passwordModal').classList.remove('hidden');
    
    document.getElementById('passwordConfirmButton').onclick = function() {
        const password = document.getElementById('modalPassword').value;
        
        if (!password) {
            showAlert('Please enter your password', 'error');
            return;
        }
        
        fetch('/admin/security/two-factor/recovery-codes', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({ password: password })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                closePasswordModal();
                let codesHtml = '<div class="bg-gray-100 p-4 rounded-lg"><h4 class="font-semibold mb-2">New Recovery Codes:</h4><div class="grid grid-cols-2 gap-2 font-mono text-sm">';
                data.recovery_codes.forEach(code => {
                    codesHtml += `<div class="bg-white p-2 rounded border">${code}</div>`;
                });
                codesHtml += '</div><p class="text-xs text-gray-600 mt-2">Save these codes in a secure location. Your old codes are no longer valid.</p></div>';
                
                showAlert(codesHtml);
            } else {
                showAlert(data.message, 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showAlert('An error occurred while generating recovery codes', 'error');
        });
    };
}

function testTwoFactor() {
    document.getElementById('testModal').classList.remove('hidden');
}

function submitTestCode() {
    const code = document.getElementById('testCode').value;
    
    if (!code) {
        showAlert('Please enter a code', 'error');
        return;
    }
    
    fetch('/admin/security/two-factor/test', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({ code: code })
    })
    .then(response => response.json())
    .then(data => {
        showAlert(data.message, data.success ? 'success' : 'error');
        closeTestModal();
    })
    .catch(error => {
        console.error('Error:', error);
        showAlert('An error occurred while testing the code', 'error');
    });
}

function closeEnableModal() {
    document.getElementById('enableModal').classList.add('hidden');
    document.getElementById('qrStep').classList.add('hidden');
    document.getElementById('confirmButton').classList.add('hidden');
    document.getElementById('confirmCode').value = '';
    document.getElementById('confirmPassword').value = '';
}

function closePasswordModal() {
    document.getElementById('passwordModal').classList.add('hidden');
    document.getElementById('modalPassword').value = '';
    currentAction = null;
}

function closeTestModal() {
    document.getElementById('testModal').classList.add('hidden');
    document.getElementById('testCode').value = '';
}

// Close modals when clicking outside
document.getElementById('enableModal').addEventListener('click', function(e) {
    if (e.target === this) closeEnableModal();
});

document.getElementById('passwordModal').addEventListener('click', function(e) {
    if (e.target === this) closePasswordModal();
});

document.getElementById('testModal').addEventListener('click', function(e) {
    if (e.target === this) closeTestModal();
});
</script>
</x-layouts.admin>
