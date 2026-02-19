<x-layouts.admin title="Create Subscription">
    {{-- Page Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-8">
        <div>
            <h1 class="text-3xl font-bold text-zinc-900 dark:text-white mb-2">Create Subscription</h1>
            <p class="text-zinc-600 dark:text-zinc-400">Add a new subscription to the system</p>
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ route('admin.administrator.subscriptions.index') }}"
                class="inline-flex items-center gap-2 rounded-md bg-white dark:bg-white/10 px-4 py-2 text-sm font-semibold text-gray-900 dark:text-white shadow-xs ring-1 ring-gray-300 ring-inset dark:ring-white/10 hover:bg-gray-50 dark:hover:bg-white/20">
                <i class="fa-solid fa-arrow-left"></i>
                Back to Subscriptions
            </a>
        </div>
    </div>

    <form action="{{ route('admin.administrator.subscriptions.store') }}" method="POST" id="subscriptionForm">
        @csrf

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            {{-- Left Column - 2/3 --}}
            <div class="lg:col-span-2 space-y-8">
                {{-- Contact Information Section --}}
                <div class="rounded-lg border border-gray-200 dark:border-white/10 bg-white dark:bg-white/5 p-6">
                    <div class="mb-6">
                        <h2 class="text-base/7 font-semibold text-gray-900 dark:text-white">Contact Information</h2>
                        <p class="mt-1 text-sm/6 text-gray-600 dark:text-gray-400">Personal contact details of the
                            subscriber.</p>
                    </div>

                    <div class="space-y-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            {{-- First Name --}}
                            <x-ui.input id="first_name" name="first_name" :value="old('first_name')" label="First Name"
                                placeholder="John" required />

                            {{-- Last Name --}}
                            <x-ui.input id="last_name" name="last_name" :value="old('last_name')" label="Last Name"
                                placeholder="Doe" required />
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            {{-- Email --}}
                            <x-ui.input type="email" id="email" name="email" :value="old('email')" label="Email Address"
                                placeholder="john.doe@example.com" required />

                            {{-- Phone --}}
                            <x-ui.input type="tel" id="phone" name="phone" :value="old('phone')" label="Phone Number"
                                placeholder="+1 234 567 8900" />
                        </div>

                        {{-- Job Title --}}
                        <x-ui.input id="job_title" name="job_title" :value="old('job_title')" label="Job Title"
                            placeholder="e.g., CEO, Marketing Manager" />
                    </div>
                </div>

                {{-- Company Information Section --}}
                <div class="rounded-lg border border-gray-200 dark:border-white/10 bg-white dark:bg-white/5 p-6">
                    <div class="mb-6">
                        <h2 class="text-base/7 font-semibold text-gray-900 dark:text-white">Company Information</h2>
                        <p class="mt-1 text-sm/6 text-gray-600 dark:text-gray-400">Business details of the subscriber's
                            organization.</p>
                    </div>

                    <div class="space-y-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            {{-- Company Name --}}
                            <x-ui.input id="company_name" name="company_name" :value="old('company_name')"
                                label="Company Name" placeholder="Acme Corporation" />

                            {{-- Company Size --}}
                            <div>
                                <label for="company_size"
                                    class="block text-sm/6 font-medium text-gray-900 dark:text-white">Company
                                    Size</label>
                                <div class="mt-2 grid grid-cols-1">
                                    <select id="company_size" name="company_size"
                                        class="col-start-1 row-start-1 w-full appearance-none rounded-md bg-white py-1.5 pr-8 pl-3 text-base text-gray-900 outline-1 -outline-offset-1 outline-gray-300 focus:outline-2 focus:-outline-offset-2 focus:outline-[var(--color-accent)] sm:text-sm/6 dark:bg-white/5 dark:text-white dark:outline-white/10 dark:*:bg-gray-800 dark:focus:outline-[var(--color-accent)] @error('company_size') outline-red-500 dark:outline-red-500 @enderror">
                                        <option value="">Select company size</option>
                                        <option value="small" {{ old('company_size') === 'small' ? 'selected' : '' }}>
                                            Small (1-50 employees)</option>
                                        <option value="medium" {{ old('company_size') === 'medium' ? 'selected' : '' }}>
                                            Medium (51-250 employees)</option>
                                        <option value="large" {{ old('company_size') === 'large' ? 'selected' : '' }}>
                                            Large (251-1000 employees)</option>
                                        <option value="enterprise" {{ old('company_size') === 'enterprise' ? 'selected' : '' }}>Enterprise (1000+ employees)</option>
                                    </select>
                                    <svg viewBox="0 0 16 16" fill="currentColor"
                                        class="pointer-events-none col-start-1 row-start-1 mr-2 size-5 self-center justify-self-end text-gray-500 sm:size-4 dark:text-gray-400">
                                        <path
                                            d="M4.22 6.22a.75.75 0 0 1 1.06 0L8 8.94l2.72-2.72a.75.75 0 1 1 1.06 1.06l-3.25 3.25a.75.75 0 0 1-1.06 0L4.22 7.28a.75.75 0 0 1 0-1.06Z"
                                            clip-rule="evenodd" fill-rule="evenodd" />
                                    </svg>
                                </div>
                                @error('company_size')<p class="mt-2 text-sm text-red-600 dark:text-red-400">
                                {{ $message }}</p>@enderror
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            {{-- Industry --}}
                            <x-ui.input id="industry" name="industry" :value="old('industry')" label="Industry"
                                placeholder="e.g., Technology, Healthcare" />

                            {{-- Website --}}
                            <x-ui.input type="url" id="website" name="website" :value="old('website')" label="Website"
                                placeholder="https://www.example.com" />
                        </div>
                    </div>
                </div>

                {{-- Subscription Details Section --}}
                <div class="rounded-lg border border-gray-200 dark:border-white/10 bg-white dark:bg-white/5 p-6">
                    <div class="mb-6">
                        <h2 class="text-base/7 font-semibold text-gray-900 dark:text-white">Subscription Details</h2>
                        <p class="mt-1 text-sm/6 text-gray-600 dark:text-gray-400">Information about the subscription
                            request.</p>
                    </div>

                    <div class="space-y-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            {{-- Product Interest --}}
                            <x-ui.input id="product_interest" name="product_interest" :value="old('product_interest')"
                                label="Product Interest" placeholder="Which product/service are they interested in?" />

                            {{-- Preferred Contact Method --}}
                            <div>
                                <label for="preferred_contact_method"
                                    class="block text-sm/6 font-medium text-gray-900 dark:text-white">Preferred Contact
                                    Method</label>
                                <div class="mt-2 grid grid-cols-1">
                                    <select id="preferred_contact_method" name="preferred_contact_method"
                                        class="col-start-1 row-start-1 w-full appearance-none rounded-md bg-white py-1.5 pr-8 pl-3 text-base text-gray-900 outline-1 -outline-offset-1 outline-gray-300 focus:outline-2 focus:-outline-offset-2 focus:outline-[var(--color-accent)] sm:text-sm/6 dark:bg-white/5 dark:text-white dark:outline-white/10 dark:*:bg-gray-800 dark:focus:outline-[var(--color-accent)] @error('preferred_contact_method') outline-red-500 dark:outline-red-500 @enderror">
                                        <option value="email" {{ old('preferred_contact_method', 'email') === 'email' ? 'selected' : '' }}>Email</option>
                                        <option value="phone" {{ old('preferred_contact_method') === 'phone' ? 'selected' : '' }}>Phone</option>
                                        <option value="both" {{ old('preferred_contact_method') === 'both' ? 'selected' : '' }}>Both</option>
                                    </select>
                                    <svg viewBox="0 0 16 16" fill="currentColor"
                                        class="pointer-events-none col-start-1 row-start-1 mr-2 size-5 self-center justify-self-end text-gray-500 sm:size-4 dark:text-gray-400">
                                        <path
                                            d="M4.22 6.22a.75.75 0 0 1 1.06 0L8 8.94l2.72-2.72a.75.75 0 1 1 1.06 1.06l-3.25 3.25a.75.75 0 0 1-1.06 0L4.22 7.28a.75.75 0 0 1 0-1.06Z"
                                            clip-rule="evenodd" fill-rule="evenodd" />
                                    </svg>
                                </div>
                                @error('preferred_contact_method')<p
                                class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>@enderror
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            {{-- Preferred Demo Date --}}
                            <x-ui.input type="date" id="preferred_demo_date" name="preferred_demo_date"
                                :value="old('preferred_demo_date')" label="Preferred Demo Date"
                                min="{{ date('Y-m-d', strtotime('+1 day')) }}" />

                            {{-- Preferred Demo Time --}}
                            <div>
                                <label for="preferred_demo_time"
                                    class="block text-sm/6 font-medium text-gray-900 dark:text-white">Preferred Demo
                                    Time</label>
                                <div class="mt-2 grid grid-cols-1">
                                    <select id="preferred_demo_time" name="preferred_demo_time"
                                        class="col-start-1 row-start-1 w-full appearance-none rounded-md bg-white py-1.5 pr-8 pl-3 text-base text-gray-900 outline-1 -outline-offset-1 outline-gray-300 focus:outline-2 focus:-outline-offset-2 focus:outline-[var(--color-accent)] sm:text-sm/6 dark:bg-white/5 dark:text-white dark:outline-white/10 dark:*:bg-gray-800 dark:focus:outline-[var(--color-accent)] @error('preferred_demo_time') outline-red-500 dark:outline-red-500 @enderror">
                                        <option value="">Select preferred time</option>
                                        <option value="morning" {{ old('preferred_demo_time') === 'morning' ? 'selected' : '' }}>Morning (9:00 - 12:00)</option>
                                        <option value="afternoon" {{ old('preferred_demo_time') === 'afternoon' ? 'selected' : '' }}>Afternoon (12:00 - 17:00)</option>
                                        <option value="evening" {{ old('preferred_demo_time') === 'evening' ? 'selected' : '' }}>Evening (17:00 - 20:00)</option>
                                    </select>
                                    <svg viewBox="0 0 16 16" fill="currentColor"
                                        class="pointer-events-none col-start-1 row-start-1 mr-2 size-5 self-center justify-self-end text-gray-500 sm:size-4 dark:text-gray-400">
                                        <path
                                            d="M4.22 6.22a.75.75 0 0 1 1.06 0L8 8.94l2.72-2.72a.75.75 0 1 1 1.06 1.06l-3.25 3.25a.75.75 0 0 1-1.06 0L4.22 7.28a.75.75 0 0 1 0-1.06Z"
                                            clip-rule="evenodd" fill-rule="evenodd" />
                                    </svg>
                                </div>
                                @error('preferred_demo_time')<p class="mt-2 text-sm text-red-600 dark:text-red-400">
                                {{ $message }}</p>@enderror
                            </div>
                        </div>

                        {{-- Message --}}
                        <div>
                            <div class="mb-4">
                                <h2 class="text-base/7 font-semibold text-gray-900 dark:text-white">Message</h2>
                            </div>
                            <x-ui.textarea id="message" name="message" :value="old('message')" rows="4"
                                placeholder="Additional requirements or questions..." />
                        </div>
                    </div>
                </div>
            </div>

            {{-- Right Column - 1/3 --}}
            <div class="lg:col-span-1 space-y-8">
                {{-- Subscription Settings Section --}}
                <div class="rounded-lg border border-gray-200 dark:border-white/10 bg-white dark:bg-white/5 p-6">
                    <div class="mb-6">
                        <h2 class="text-base/7 font-semibold text-gray-900 dark:text-white">Subscription Settings</h2>
                        <p class="mt-1 text-sm/6 text-gray-600 dark:text-gray-400">Configure status and preferences.</p>
                    </div>

                    <div class="space-y-6">
                        {{-- Status --}}
                        <div>
                            <label for="status"
                                class="block text-sm/6 font-medium text-gray-900 dark:text-white">Status</label>
                            <div class="mt-2 grid grid-cols-1">
                                <select id="status" name="status"
                                    class="col-start-1 row-start-1 w-full appearance-none rounded-md bg-white py-1.5 pr-8 pl-3 text-base text-gray-900 outline-1 -outline-offset-1 outline-gray-300 focus:outline-2 focus:-outline-offset-2 focus:outline-[var(--color-accent)] sm:text-sm/6 dark:bg-white/5 dark:text-white dark:outline-white/10 dark:*:bg-gray-800 dark:focus:outline-[var(--color-accent)] @error('status') outline-red-500 dark:outline-red-500 @enderror">
                                    <option value="new" {{ old('status', 'new') === 'new' ? 'selected' : '' }}>New
                                        Subscription</option>
                                    <option value="contacted" {{ old('status') === 'contacted' ? 'selected' : '' }}>
                                        Contacted</option>
                                    <option value="demo_scheduled" {{ old('status') === 'demo_scheduled' ? 'selected' : '' }}>Demo Scheduled</option>
                                    <option value="demo_completed" {{ old('status') === 'demo_completed' ? 'selected' : '' }}>Demo Completed</option>
                                    <option value="converted" {{ old('status') === 'converted' ? 'selected' : '' }}>
                                        Converted to Customer</option>
                                    <option value="rejected" {{ old('status') === 'rejected' ? 'selected' : '' }}>Rejected
                                    </option>
                                </select>
                                <svg viewBox="0 0 16 16" fill="currentColor"
                                    class="pointer-events-none col-start-1 row-start-1 mr-2 size-5 self-center justify-self-end text-gray-500 sm:size-4 dark:text-gray-400">
                                    <path
                                        d="M4.22 6.22a.75.75 0 0 1 1.06 0L8 8.94l2.72-2.72a.75.75 0 1 1 1.06 1.06l-3.25 3.25a.75.75 0 0 1-1.06 0L4.22 7.28a.75.75 0 0 1 0-1.06Z"
                                        clip-rule="evenodd" fill-rule="evenodd" />
                                </svg>
                            </div>
                            @error('status')<p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Source --}}
                        <x-ui.input id="source" name="source" :value="old('source', 'website')" label="Source"
                            placeholder="website, referral, social, etc." />

                        {{-- Admin Notes --}}
                        <div>
                            <div class="mb-4">
                                <h2 class="text-base/7 font-semibold text-gray-900 dark:text-white">Admin Notes</h2>
                            </div>
                            <x-ui.textarea id="admin_notes" name="admin_notes" :value="old('admin_notes')" rows="3"
                                placeholder="Internal notes about this subscription..." />
                        </div>

                        {{-- Active Toggle --}}
                        <div class="flex items-center justify-between">
                            <div>
                                <label class="block text-sm/6 font-medium text-gray-900 dark:text-white">Active</label>
                                <p class="text-sm/6 text-gray-600 dark:text-gray-400">Make subscription active</p>
                            </div>
                            <div>
                                <input type="hidden" name="is_active" value="0">
                                <x-ui.toggle name="is_active" :checked="old('is_active', true)" />
                            </div>
                        </div>
                        @error('is_active')<p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror

                        {{-- Newsletter Consent Toggle --}}
                        <div class="flex items-center justify-between">
                            <div>
                                <label class="block text-sm/6 font-medium text-gray-900 dark:text-white">Newsletter
                                    Consent</label>
                                <p class="text-sm/6 text-gray-600 dark:text-gray-400">Subscriber consented to newsletter
                                </p>
                            </div>
                            <div>
                                <input type="hidden" name="newsletter_consent" value="0">
                                <x-ui.toggle name="newsletter_consent" :checked="old('newsletter_consent', false)" />
                            </div>
                        </div>
                        @error('newsletter_consent')<p class="mt-2 text-sm text-red-600 dark:text-red-400">
                        {{ $message }}</p>@enderror

                        {{-- Marketing Consent Toggle --}}
                        <div class="flex items-center justify-between">
                            <div>
                                <label class="block text-sm/6 font-medium text-gray-900 dark:text-white">Marketing
                                    Consent</label>
                                <p class="text-sm/6 text-gray-600 dark:text-gray-400">Subscriber consented to marketing
                                </p>
                            </div>
                            <div>
                                <input type="hidden" name="marketing_consent" value="0">
                                <x-ui.toggle name="marketing_consent" :checked="old('marketing_consent', false)" />
                            </div>
                        </div>
                        @error('marketing_consent')<p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}
                        </p>@enderror
                    </div>
                </div>
            </div>
        </div>

        {{-- Form Action Buttons --}}
        <div class="mt-8 flex flex-col sm:flex-row items-center justify-end gap-3 px-4 sm:px-0">
            <a href="{{ route('admin.administrator.subscriptions.index') }}"
                class="w-full sm:w-auto inline-flex items-center justify-center gap-2 rounded-md bg-white dark:bg-white/10 px-4 py-2 text-sm font-semibold text-gray-900 dark:text-white shadow-xs ring-1 ring-gray-300 ring-inset dark:ring-white/10 hover:bg-gray-50 dark:hover:bg-white/20">
                Cancel
            </a>
            <button type="submit" name="action" value="save_and_stay"
                class="w-full sm:w-auto inline-flex items-center justify-center gap-2 rounded-md bg-white dark:bg-white/10 px-4 py-2 text-sm font-semibold text-gray-900 dark:text-white shadow-xs ring-1 ring-gray-300 ring-inset dark:ring-white/10 hover:bg-gray-50 dark:hover:bg-white/20">
                Save & Continue Editing
            </button>
            <button type="submit" name="action" value="save"
                class="w-full sm:w-auto inline-flex items-center justify-center gap-2 rounded-md bg-[var(--color-accent)] px-6 py-2 text-sm font-semibold text-white shadow-xs ring-1 ring-inset ring-[var(--color-accent)] hover:opacity-90 transition-opacity">
                Save
            </button>
        </div>
    </form>
</x-layouts.admin>