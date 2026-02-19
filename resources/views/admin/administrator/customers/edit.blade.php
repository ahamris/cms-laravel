@php use App\Helpers\Variable; @endphp
<x-layouts.admin title="Edit Customer: {{ $customer->name }}">
    <div class="space-y-6">
        {{-- Header --}}
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Edit Customer</h1>
                <p class="text-gray-600">Update customer information</p>
            </div>
            <a href="{{ route('admin.administrator.customers.index') }}"
                class="bg-gray-100 text-gray-800 px-4 py-2 rounded-lg hover:bg-gray-200 transition-colors duration-200 flex items-center justify-center space-x-2">
                <i class="fa-solid fa-arrow-left"></i>
                <span>Back to List</span>
            </a>
        </div>

        {{-- Success/Error Messages --}}
        @if (session('success'))
            <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg flex items-start">
                <i class="fa-solid fa-check-circle mt-0.5 mr-2"></i>
                <div>{{ session('success') }}</div>
                <button type="button" class="ml-auto text-green-500 hover:text-green-700"
                    onclick="this.parentElement.remove()">
                    <i class="fa-solid fa-times"></i>
                </button>
            </div>
        @endif

        @if ($errors->any())
            <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg">
                <div class="flex items-center">
                    <i class="fa-solid fa-exclamation-circle mr-2"></i>
                    <span>Please fix the following errors:</span>
                </div>
                <ul class="mt-2 list-disc list-inside text-sm">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{-- Form --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            <form action="{{ route('admin.administrator.customers.update', $customer) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="p-6 space-y-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        {{-- First Name --}}
                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700 mb-1">First Name <span
                                    class="text-red-500">*</span></label>
                            <input type="text" name="name" id="name" value="{{ old('name', $customer->name) }}"
                                required
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary transition-colors duration-200">
                        </div>

                        {{-- Last Name --}}
                        <div>
                            <label for="last_name" class="block text-sm font-medium text-gray-700 mb-1">Last Name</label>
                            <input type="text" name="last_name" id="last_name"
                                value="{{ old('last_name', $customer->last_name) }}"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary transition-colors duration-200">
                        </div>

                        {{-- Email --}}
                        <div class="md:col-span-2">
                            <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email <span
                                    class="text-red-500">*</span></label>
                            <div class="relative">
                                <input type="email" name="email" id="email"
                                    value="{{ old('email', $customer->email) }}" required
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary transition-colors duration-200">
                                @if ($customer->email_verified_at)
                                    <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                        <span class="text-green-500">
                                            <i class="fa-solid fa-check-circle"></i>
                                        </span>
                                    </div>
                                @endif
                            </div>
                            @if ($customer->email_verified_at)
                                <p class="mt-1 text-xs text-green-600">Email verified on
                                    {{ $customer->email_verified_at->format('M d, Y') }}</p>
                            @else
                                <p class="mt-1 text-xs text-yellow-600">Email not verified</p>
                            @endif
                        </div>

                        {{-- Secondary Email --}}
                        <div class="md:col-span-2">
                            <label for="secondary_email" class="block text-sm font-medium text-gray-700 mb-1">Secondary
                                Email</label>
                            <input type="email" name="secondary_email" id="secondary_email"
                                value="{{ old('secondary_email', $customer->secondary_email) }}"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary transition-colors duration-200">
                        </div>

                        {{-- Email Verification Date --}}
                        <div class="md:col-span-2">
                            <label for="email_verified_at" class="block text-sm font-medium text-gray-700 mb-1">
                                Email Verification Date
                            </label>
                            <div class="flex flex-col sm:flex-row gap-3 items-start sm:items-end">
                                <div class="relative w-full sm:w-64">
                                    <input type="datetime-local" name="email_verified_at" id="email_verified_at"
                                        value="{{ old('email_verified_at', $customer->email_verified_at ? $customer->email_verified_at->format('Y-m-d\TH:i') : '') }}"
                                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary transition-colors duration-200">
                                </div>
                                <div class="flex gap-2">
                                    <button type="button" onclick="setVerificationDate('now')"
                                        class="px-3 py-2 bg-blue-100 text-blue-800 hover:bg-blue-200 rounded-lg text-sm font-medium transition-colors duration-200 whitespace-nowrap">
                                        Set to Now
                                    </button>
                                    <button type="button" onclick="setVerificationDate('clear')"
                                        class="px-3 py-2 bg-gray-100 text-gray-800 hover:bg-gray-200 rounded-lg text-sm font-medium transition-colors duration-200 whitespace-nowrap">
                                        Clear
                                    </button>
                                </div>
                            </div>
                            <p class="mt-1 text-xs text-gray-500">
                                Set the email verification date. Leave empty if email is not verified.
                            </p>
                        </div>
                    </div>

                    {{-- Current Status --}}
                    @if ($customer->email_verified_at)
                        <div class="mt-6 p-4 bg-green-50 border-l-4 border-green-400 rounded">
                            <div class="flex items-start">
                                <div class="flex-shrink-0 pt-0.5">
                                    <i class="fa-solid fa-check-circle text-green-400"></i>
                                </div>
                                <div class="ml-3">
                                    <p class="text-sm text-green-700">
                                        Current status: Verified on
                                        {{ $customer->email_verified_at->format('M d, Y \a\t H:i') }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    @else
                        <div class="mt-6 p-4 bg-yellow-50 border-l-4 border-yellow-400 rounded">
                            <div class="flex items-start">
                                <div class="flex-shrink-0 pt-0.5">
                                    <i class="fa-solid fa-exclamation-triangle text-yellow-400"></i>
                                </div>
                                <div class="ml-3">
                                    <p class="text-sm text-yellow-700">
                                        Current status: Email not verified
                                    </p>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>

                {{-- Form Actions --}}
                <div
                    class="px-6 py-4 bg-gray-50 border-t border-gray-200 flex flex-col sm:flex-row justify-between items-center space-y-3 sm:space-y-0">
                    <div class="text-sm text-gray-500">
                        Last updated: {{ $customer->updated_at->diffForHumans() }}
                    </div>
                    <div class="flex space-x-3">
                        <a href="{{ route('admin.administrator.customers.index') }}"
                            class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary transition-colors duration-200">
                            Cancel
                        </a>
                        <button type="submit"
                            class="px-4 py-2 border border-transparent rounded-lg shadow-sm text-white bg-primary hover:bg-primary/90 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary transition-colors duration-200 flex items-center">
                            <i class="fa-solid fa-save mr-2"></i>
                            Save Changes
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <script>
        function setVerificationDate(action) {
            const dateInput = document.getElementById('email_verified_at');

            if (action === 'now') {
                // Şu anki tarih ve saati ayarla
                const now = new Date();
                // Timezone offset'ini düzelt
                const timezoneOffset = now.getTimezoneOffset() * 60000;
                const localTime = new Date(now - timezoneOffset);
                dateInput.value = localTime.toISOString().slice(0, 16);
            } else if (action === 'clear') {
                dateInput.value = '';
            }
        }

        document.addEventListener('DOMContentLoaded', function() {
            // Sayfa yüklendiğinde input formatını kontrol et
            const dateInput = document.getElementById('email_verified_at');
            if (dateInput.value) {
                // Eğer değer varsa, formatı düzelt
                const date = new Date(dateInput.value);
                if (!isNaN(date.getTime())) {
                    const timezoneOffset = date.getTimezoneOffset() * 60000;
                    const localTime = new Date(date - timezoneOffset);
                    dateInput.value = localTime.toISOString().slice(0, 16);
                }
            }
        });
    </script>
</x-layouts.admin>
