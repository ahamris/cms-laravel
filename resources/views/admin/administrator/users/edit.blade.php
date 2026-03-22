<x-layouts.admin title="Edit User">
    <div class="space-y-6">
    {{-- Header --}}
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Edit User</h1>
            <p class="text-gray-600">Update user details and permissions</p>
        </div>
        <a href="{{ route('admin.administrator.users.index') }}" class="text-primary hover:text-primary/80">
            <i class="fa-solid fa-arrow-left mr-1"></i> Back to Users
        </a>
    </div>

    {{-- Success/Error Messages --}}
    @if (session('success'))
        <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg">
            <i class="fa-solid fa-check-circle mr-2"></i>
            {{ session('success') }}
        </div>
    @endif

    @if ($errors->any())
        <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg">
            <i class="fa-solid fa-exclamation-circle mr-2"></i>
            <ul class="list-disc list-inside">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <form action="{{ route('admin.administrator.users.update', $user) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            
            <div class="p-6 space-y-6">
                {{-- Basic Information --}}
                <div>
                    <h2 class="text-lg font-medium text-gray-900">Basic Information</h2>
                    <p class="mt-1 text-sm text-gray-500">Update the user's basic information.</p>
                </div>

                <div class="space-y-6">
                    {{-- Avatar and Name Section --}}
                    <div class="flex items-start space-x-6">
                        {{-- Avatar --}}
                        <div class="flex-shrink-0">
                            <x-image-upload 
                                id="avatar"
                                name="avatar"
                                label="Avatar"
                                :required="false"
                                help-text="Upload avatar image (max 20MB)"
                                :max-size="20480"
                                size="small"
                                :current-image="$user->avatar"
                                :current-image-alt="$user->name . ' avatar'"
                            />
                        </div>

                        {{-- Name Fields --}}
                        <div class="flex-1 space-y-4">
                            <div>
                                <label for="name" class="block text-sm font-medium text-gray-700">First Name</label>
                                <input type="text" name="name" id="name" value="{{ old('name', $user->name) }}" required
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary transition-colors duration-200 @error('name') border-red-500 @enderror"
                                    placeholder="Enter first name">
                                @error('name')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="last_name" class="block text-sm font-medium text-gray-700">Last Name</label>
                                <input type="text" name="last_name" id="last_name" value="{{ old('last_name', $user->last_name) }}" required
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary transition-colors duration-200 @error('last_name') border-red-500 @enderror"
                                    placeholder="Enter last name">
                                @error('last_name')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    {{-- Email Fields --}}
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700">Email Address</label>
                            <input type="email" name="email" id="email" value="{{ old('email', $user->email) }}" required
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary transition-colors duration-200 @error('email') border-red-500 @enderror"
                                placeholder="Enter email address">
                            @error('email')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="secondary_email" class="block text-sm font-medium text-gray-700">Secondary Email</label>
                            <input type="email" name="secondary_email" id="secondary_email" value="{{ old('secondary_email', $user->secondary_email) }}"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary transition-colors duration-200 @error('secondary_email') border-red-500 @enderror"
                                placeholder="Optional secondary email">
                            @error('secondary_email')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    {{-- Password Fields --}}
                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-700">New Password</label>
                        <div class="mt-1 relative">
                            <input type="password" name="password" id="password"
                                class="w-full px-3 py-2 pr-20 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary transition-colors duration-200 @error('password') border-red-500 @enderror"
                                placeholder="Leave blank to keep current password">
                            <div class="absolute inset-y-0 right-0 flex items-center pr-3 space-x-1">
                                <button type="button" id="generatePassword" 
                                    class="text-gray-400 hover:text-primary focus:outline-none focus:text-primary"
                                    title="Generate random password">
                                    <i class="fa-solid fa-dice text-sm"></i>
                                </button>
                                <button type="button" id="copyPassword" 
                                    class="text-gray-400 hover:text-primary focus:outline-none focus:text-primary"
                                    title="Copy password">
                                    <i class="fa-solid fa-copy text-sm"></i>
                                </button>
                                <button type="button" id="togglePassword" 
                                    class="text-gray-400 hover:text-primary focus:outline-none focus:text-primary"
                                    title="Show/hide password">
                                    <i class="fa-solid fa-eye text-sm"></i>
                                </button>
                            </div>
                        </div>
                        @error('password')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="password_confirmation" class="block text-sm font-medium text-gray-700">Confirm New Password</label>
                        <input type="password" name="password_confirmation" id="password_confirmation"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary transition-colors duration-200"
                            placeholder="Confirm new password">
                        @error('password_confirmation')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                {{-- Roles --}}
                <div class="pt-6 border-t border-gray-200">
                    <div class="mb-4">
                        <h2 class="text-lg font-medium text-gray-900">Roles</h2>
                        <p class="mt-1 text-sm text-gray-500">Assign roles to this user.</p>
                    </div>

                    <div class="space-y-2">
                        @foreach($roles as $role)
                            <div class="flex items-center">
                                <input id="role-{{ $role->id }}" name="roles[]" type="checkbox" value="{{ $role->id }}"
                                    {{ $user->hasRole($role->name) ? 'checked' : '' }}
                                    class="h-4 w-4 text-primary focus:ring-primary border-gray-300 rounded">
                                <label for="role-{{ $role->id }}" class="ml-3 block text-sm font-medium text-gray-700">
                                    {{ $role->name }}
                                </label>
                            </div>
                        @endforeach
                    </div>
                </div>

                {{-- Permissions --}}
                <div class="pt-6 border-t border-gray-200">
                    <div class="mb-4">
                        <h2 class="text-lg font-medium text-gray-900">Direct Permissions</h2>
                        <p class="mt-1 text-sm text-gray-500">Assign additional permissions to this user.</p>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                        @foreach($permissions as $permission)
                            <div class="flex items-center">
                                <input id="permission-{{ $permission->id }}" name="permissions[]" type="checkbox" 
                                    value="{{ $permission->id }}"
                                    {{ $user->hasDirectPermission($permission->name) ? 'checked' : '' }}
                                    class="h-4 w-4 text-primary focus:ring-primary border-gray-300 rounded">
                                <label for="permission-{{ $permission->id }}" class="ml-3 block text-sm font-medium text-gray-700">
                                    {{ $permission->name }}
                                </label>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            {{-- Form Actions --}}
            <div class="bg-gray-50 px-6 py-4 flex justify-end space-x-3">
                <a href="{{ route('admin.administrator.users.index') }}"
                    class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary">
                    Cancel
                </a>
                <button type="submit"
                    class="px-4 py-2 border border-transparent rounded-lg shadow-sm text-white bg-primary hover:bg-primary/90 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary">
                    Update User
                </button>
            </div>
        </form>
    </div>
</div>

    <script>
document.addEventListener('DOMContentLoaded', function() {
    const password = document.getElementById('password');
    const confirmPassword = document.getElementById('password_confirmation');
    const generateBtn = document.getElementById('generatePassword');
    const copyBtn = document.getElementById('copyPassword');
    const toggleBtn = document.getElementById('togglePassword');
    
    // Password validation
    function validatePassword() {
        if (password.value && confirmPassword.value && password.value !== confirmPassword.value) {
            confirmPassword.setCustomValidity("Passwords don't match");
        } else {
            confirmPassword.setCustomValidity('');
        }
    }
    
    password.onchange = validatePassword;
    confirmPassword.onkeyup = validatePassword;
    
    // Avatar preview
    const avatarInput = document.getElementById('avatar');
    const avatarPreview = document.getElementById('avatarPreview');
    const avatarPlaceholder = document.getElementById('avatarPlaceholder');
    
    avatarInput.addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                avatarPreview.querySelector('img').src = e.target.result;
                avatarPreview.classList.remove('hidden');
                avatarPlaceholder.classList.add('hidden');
            };
            reader.readAsDataURL(file);
        } else {
            // If no file selected, show current avatar or placeholder
            const currentAvatar = '{{ $user->avatar }}';
            if (currentAvatar) {
                avatarPreview.querySelector('img').src = currentAvatar;
                avatarPreview.classList.remove('hidden');
                avatarPlaceholder.classList.add('hidden');
            } else {
                avatarPreview.classList.add('hidden');
                avatarPlaceholder.classList.remove('hidden');
            }
        }
    });
    
    // Generate random password (10 characters with mixed case, numbers, and symbols)
    function generateRandomPassword() {
        const chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789@$!%*?&._-';
        let result = '';
        
        // Ensure at least one of each required type
        result += 'abcdefghijklmnopqrstuvwxyz'[Math.floor(Math.random() * 26)]; // lowercase
        result += 'ABCDEFGHIJKLMNOPQRSTUVWXYZ'[Math.floor(Math.random() * 26)]; // uppercase
        result += '0123456789'[Math.floor(Math.random() * 10)]; // number
        
        // Fill remaining 7 characters randomly
        for (let i = 3; i < 10; i++) {
            result += chars[Math.floor(Math.random() * chars.length)];
        }
        
        // Shuffle the result
        return result.split('').sort(() => Math.random() - 0.5).join('');
    }
    
    // Generate password button
    generateBtn.addEventListener('click', function() {
        const newPassword = generateRandomPassword();
        password.value = newPassword;
        confirmPassword.value = newPassword;
        
        // Show password temporarily
        password.type = 'text';
        confirmPassword.type = 'text';
        toggleBtn.querySelector('i').classList.remove('fa-eye');
        toggleBtn.querySelector('i').classList.add('fa-eye-slash');
        
        // Trigger validation
        validatePassword();
        
        // Show success feedback
        generateBtn.innerHTML = '<i class="fa-solid fa-check text-sm text-green-500"></i>';
        setTimeout(() => {
            generateBtn.innerHTML = '<i class="fa-solid fa-dice text-sm"></i>';
        }, 1000);
    });
    
    // Copy password button
    copyBtn.addEventListener('click', async function() {
        if (password.value) {
            try {
                await navigator.clipboard.writeText(password.value);
                
                // Show success feedback
                copyBtn.innerHTML = '<i class="fa-solid fa-check text-sm text-green-500"></i>';
                setTimeout(() => {
                    copyBtn.innerHTML = '<i class="fa-solid fa-copy text-sm"></i>';
                }, 1000);
            } catch (err) {
                // Fallback for older browsers
                password.select();
                document.execCommand('copy');
                
                copyBtn.innerHTML = '<i class="fa-solid fa-check text-sm text-green-500"></i>';
                setTimeout(() => {
                    copyBtn.innerHTML = '<i class="fa-solid fa-copy text-sm"></i>';
                }, 1000);
            }
        }
    });
    
    // Toggle password visibility
    toggleBtn.addEventListener('click', function() {
        const type = password.type === 'password' ? 'text' : 'password';
        password.type = type;
        confirmPassword.type = type;
        
        const icon = toggleBtn.querySelector('i');
        if (type === 'text') {
            icon.classList.remove('fa-eye');
            icon.classList.add('fa-eye-slash');
        } else {
            icon.classList.remove('fa-eye-slash');
            icon.classList.add('fa-eye');
        }
    });
    
    // Role-Permission Synchronization
    const rolePermissions = @json($rolePermissions);
    const roleCheckboxes = document.querySelectorAll('input[name="roles[]"]');
    const permissionCheckboxes = document.querySelectorAll('input[name="permissions[]"]');
    
    // Track which permissions were manually selected (not by role)
    const manuallySelectedPermissions = new Set();
    
    // Function to update permissions based on selected roles
    function updatePermissionsFromRoles() {
        const selectedRoles = Array.from(roleCheckboxes)
            .filter(checkbox => checkbox.checked)
            .map(checkbox => parseInt(checkbox.value));
        
        // Get all permissions that belong to selected roles
        const rolePermissionIds = selectedRoles.reduce((acc, roleId) => {
            const permissions = rolePermissions[roleId] || [];
            return [...acc, ...permissions];
        }, []);
        
        // Update permissions based on roles
        permissionCheckboxes.forEach(checkbox => {
            const permissionId = parseInt(checkbox.value);
            const isRolePermission = rolePermissionIds.includes(permissionId);
            const isManuallySelected = manuallySelectedPermissions.has(permissionId);
            
            // Check if permission should be selected (either by role or manually)
            checkbox.checked = isRolePermission || isManuallySelected;
        });
    }
    
    // Function to handle role checkbox changes
    function handleRoleChange(checkbox) {
        const roleId = parseInt(checkbox.value);
        const rolePermissionIds = rolePermissions[roleId] || [];
        
        if (checkbox.checked) {
            // Role selected - check all its permissions
            rolePermissionIds.forEach(permissionId => {
                const permissionCheckbox = document.querySelector(`input[name="permissions[]"][value="${permissionId}"]`);
                if (permissionCheckbox) {
                    permissionCheckbox.checked = true;
                }
            });
        } else {
            // Role deselected - uncheck its permissions (unless manually selected)
            rolePermissionIds.forEach(permissionId => {
                if (!manuallySelectedPermissions.has(permissionId)) {
                    const permissionCheckbox = document.querySelector(`input[name="permissions[]"][value="${permissionId}"]`);
                    if (permissionCheckbox) {
                        permissionCheckbox.checked = false;
                    }
                }
            });
        }
    }
    
    // Function to handle permission checkbox changes
    function handlePermissionChange(checkbox) {
        const permissionId = parseInt(checkbox.value);
        
        if (checkbox.checked) {
            // Permission manually selected
            manuallySelectedPermissions.add(permissionId);
        } else {
            // Permission manually deselected
            manuallySelectedPermissions.delete(permissionId);
        }
    }
    
    // Add event listeners to role checkboxes
    roleCheckboxes.forEach(checkbox => {
        checkbox.addEventListener('change', function() {
            handleRoleChange(this);
        });
    });
    
    // Add event listeners to permission checkboxes
    permissionCheckboxes.forEach(checkbox => {
        checkbox.addEventListener('change', function() {
            handlePermissionChange(this);
        });
        
        // Track initially checked permissions as manually selected
        if (checkbox.checked) {
            manuallySelectedPermissions.add(parseInt(checkbox.value));
        }
    });
    
    // Initialize permissions based on current roles
    updatePermissionsFromRoles();
});
    </script>
</x-layouts.admin>