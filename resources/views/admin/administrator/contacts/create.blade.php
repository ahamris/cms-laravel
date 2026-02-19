<x-layouts.admin title="Create Contact">
    {{-- Page Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-8">
        <div>
            <h1 class="text-3xl font-bold text-zinc-900 dark:text-white mb-2">Create Contact</h1>
            <p class="text-zinc-600 dark:text-zinc-400">Add a new organization contact</p>
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ route('admin.administrator.contacts.index') }}"
                class="inline-flex items-center gap-2 rounded-md bg-white dark:bg-white/10 px-4 py-2 text-sm font-semibold text-gray-900 dark:text-white shadow-xs ring-1 ring-gray-300 ring-inset dark:ring-white/10 hover:bg-gray-50 dark:hover:bg-white/20">
                <i class="fa-solid fa-arrow-left"></i>
                Back to Contacts
            </a>
        </div>
    </div>

    <form action="{{ route('admin.administrator.contacts.store') }}" method="POST" id="contactForm">
        @csrf

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            {{-- Left Column - 2/3 --}}
            <div class="lg:col-span-2 space-y-8">
                {{-- Organization Identity Section --}}
                <div class="rounded-lg border border-gray-200 dark:border-white/10 bg-white dark:bg-white/5 p-6">
                    <div class="mb-6">
                        <h2 class="text-base/7 font-semibold text-gray-900 dark:text-white">Organization Identity</h2>
                        <p class="mt-1 text-sm/6 text-gray-600 dark:text-gray-400">Basic information about the
                            organization.</p>
                    </div>

                    <div class="space-y-6">
                        <div
                            class="space-y-6"
                            x-data="{
                                fieldsLocked: false,
                                generateSlug(text) {
                                    if (!text) return '';
                                    return text.toLowerCase()
                                        .replace(/[^a-z0-9 -]/g, '')
                                        .replace(/\s+/g, '-')
                                        .replace(/-+/g, '-')
                                        .replace(/^-+|-+$/g, '');
                                },
                                lockFields() {
                                    this.fieldsLocked = true;
                                    ['alias', 'slug', 'email', 'phone', 'website'].forEach(id => {
                                        const el = document.getElementById(id);
                                        if (el) el.readOnly = true;
                                    });
                                },
                                unlockFields() {
                                    this.fieldsLocked = false;
                                    ['alias', 'slug', 'email', 'phone', 'website'].forEach(id => {
                                        const el = document.getElementById(id);
                                        if (el) el.readOnly = false;
                                    });
                                },
                                fillFromOrg(opt) {
                                    const aliasEl = document.getElementById('alias');
                                    const slugEl = document.getElementById('slug');
                                    const emailEl = document.getElementById('email');
                                    const phoneEl = document.getElementById('phone');
                                    const websiteEl = document.getElementById('website');
                                    if (aliasEl) aliasEl.value = opt.abbreviation || '';
                                    if (slugEl) slugEl.value = this.generateSlug(opt.value || '');
                                    if (emailEl) emailEl.value = opt.email || '';
                                    if (phoneEl) phoneEl.value = '';
                                    if (websiteEl) websiteEl.value = '';
                                    this.lockFields();
                                },
                                clearFields() {
                                    ['alias', 'slug', 'email', 'phone', 'website'].forEach(id => {
                                        const el = document.getElementById(id);
                                        if (el) { el.value = ''; el.readOnly = false; }
                                    });
                                    this.fieldsLocked = false;
                                }
                            }"
                            @combobox-selected.window="fillFromOrg($event.detail)"
                            @combobox-cleared.window="clearFields(); unlockFields()"
                        >
                            {{-- Organization Name --}}
                            <x-ui.combobox
                                id="organization_name"
                                name="organization_name"
                                :value="old('organization_name')"
                                label="Organization Name"
                                :options="$organizationOptions"
                                placeholder="Search organization name..."
                                :required="true"
                                :clearable="true"
                                :error="$errors->has('organization_name')"
                                :errorMessage="$errors->first('organization_name')"
                                search-placeholder="Search organization name..."
                                no-results-text="No organizations found"
                            />

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                {{-- Alias --}}
                                <x-ui.input id="alias" name="alias" :value="old('alias')" label="Alias"
                                    placeholder="Short name or alias" />

                                {{-- Slug --}}
                                <x-ui.input id="slug" name="slug" :value="old('slug')" label="Slug"
                                    slug-from="organization_name"
                                    hint="URL-friendly version of the organization name. Auto-generated from organization name if left blank."
                                    placeholder="url-friendly-name" />
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                {{-- Email --}}
                                <x-ui.input type="email" id="email" name="email" :value="old('email')" label="Email"
                                    placeholder="contact@organization.com" />

                                {{-- Phone --}}
                                <x-ui.input id="phone" name="phone" :value="old('phone')" label="Phone"
                                    placeholder="+31 20 123 4567" />
                            </div>

                            {{-- Website --}}
                            <x-ui.input type="url" id="website" name="website" :value="old('website')" label="Website"
                                placeholder="https://www.organization.com" />
                        </div>
                    </div>
                </div>

                {{-- Registration & Tax Section --}}
                <div class="rounded-lg border border-gray-200 dark:border-white/10 bg-white dark:bg-white/5 p-6">
                    <div class="mb-6">
                        <h2 class="text-base/7 font-semibold text-gray-900 dark:text-white">Registration & Tax</h2>
                        <p class="mt-1 text-sm/6 text-gray-600 dark:text-gray-400">Official registration and tax
                            information.</p>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        {{-- Chamber of Commerce --}}
                        <x-ui.input id="chamber_of_commerce" name="chamber_of_commerce"
                            :value="old('chamber_of_commerce')" label="Chamber of Commerce (KVK)"
                            placeholder="12345678" />

                        {{-- Tax Number --}}
                        <x-ui.input id="tax_number" name="tax_number" :value="old('tax_number')"
                            label="Tax Number (VAT)" placeholder="NL001234567B01" />
                    </div>
                </div>

                {{-- Invoicing Preferences Section --}}
                <div class="rounded-lg border border-gray-200 dark:border-white/10 bg-white dark:bg-white/5 p-6">
                    <div class="mb-6">
                        <h2 class="text-base/7 font-semibold text-gray-900 dark:text-white">Invoicing Preferences</h2>
                        <p class="mt-1 text-sm/6 text-gray-600 dark:text-gray-400">Configure invoicing and payment
                            settings.</p>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        {{-- Invoice Email --}}
                        <x-ui.input type="email" id="invoice_email" name="invoice_email" :value="old('invoice_email')"
                            label="Invoice Email" placeholder="invoices@organization.com" />

                        {{-- Payment Due Days --}}
                        <x-ui.input type="number" id="payment_due_days" name="payment_due_days"
                            :value="old('payment_due_days', 14)" label="Payment Due Days" max="365" min="0" required />

                        {{-- Currency --}}
                        <div>
                            <label for="currency"
                                class="block text-sm/6 font-medium text-gray-900 dark:text-white">Currency <span
                                    class="text-red-500">*</span></label>
                            <div class="mt-2 grid grid-cols-1">
                                <select id="currency" name="currency" required
                                    class="col-start-1 row-start-1 w-full appearance-none rounded-md bg-white py-1.5 pr-8 pl-3 text-base text-gray-900 outline-1 -outline-offset-1 outline-gray-300 focus:outline-2 focus:-outline-offset-2 focus:outline-[var(--color-accent)] sm:text-sm/6 dark:bg-white/5 dark:text-white dark:outline-white/10 dark:*:bg-gray-800 dark:focus:outline-[var(--color-accent)] @error('currency') outline-red-500 dark:outline-red-500 @enderror">
                                    <option value="EUR" {{ old('currency', 'EUR') == 'EUR' ? 'selected' : '' }}>EUR - Euro
                                    </option>
                                    <option value="USD" {{ old('currency') == 'USD' ? 'selected' : '' }}>USD - US Dollar
                                    </option>
                                    <option value="GBP" {{ old('currency') == 'GBP' ? 'selected' : '' }}>GBP - British
                                        Pound</option>
                                </select>
                                <svg viewBox="0 0 16 16" fill="currentColor"
                                    class="pointer-events-none col-start-1 row-start-1 mr-2 size-5 self-center justify-self-end text-gray-500 sm:size-4 dark:text-gray-400">
                                    <path
                                        d="M4.22 6.22a.75.75 0 0 1 1.06 0L8 8.94l2.72-2.72a.75.75 0 1 1 1.06 1.06l-3.25 3.25a.75.75 0 0 1-1.06 0L4.22 7.28a.75.75 0 0 1 0-1.06Z"
                                        clip-rule="evenodd" fill-rule="evenodd" />
                                </svg>
                            </div>
                            @error('currency')<p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Preferred Language --}}
                        <div>
                            <label for="preferred_language"
                                class="block text-sm/6 font-medium text-gray-900 dark:text-white">Preferred Language
                                <span class="text-red-500">*</span></label>
                            <div class="mt-2 grid grid-cols-1">
                                <select id="preferred_language" name="preferred_language" required
                                    class="col-start-1 row-start-1 w-full appearance-none rounded-md bg-white py-1.5 pr-8 pl-3 text-base text-gray-900 outline-1 -outline-offset-1 outline-gray-300 focus:outline-2 focus:-outline-offset-2 focus:outline-[var(--color-accent)] sm:text-sm/6 dark:bg-white/5 dark:text-white dark:outline-white/10 dark:*:bg-gray-800 dark:focus:outline-[var(--color-accent)] @error('preferred_language') outline-red-500 dark:outline-red-500 @enderror">
                                    <option value="nl" {{ old('preferred_language', 'nl') == 'nl' ? 'selected' : '' }}>
                                        Dutch</option>
                                    <option value="en" {{ old('preferred_language') == 'en' ? 'selected' : '' }}>English
                                    </option>
                                    <option value="de" {{ old('preferred_language') == 'de' ? 'selected' : '' }}>German
                                    </option>
                                    <option value="fr" {{ old('preferred_language') == 'fr' ? 'selected' : '' }}>French
                                    </option>
                                </select>
                                <svg viewBox="0 0 16 16" fill="currentColor"
                                    class="pointer-events-none col-start-1 row-start-1 mr-2 size-5 self-center justify-self-end text-gray-500 sm:size-4 dark:text-gray-400">
                                    <path
                                        d="M4.22 6.22a.75.75 0 0 1 1.06 0L8 8.94l2.72-2.72a.75.75 0 1 1 1.06 1.06l-3.25 3.25a.75.75 0 0 1-1.06 0L4.22 7.28a.75.75 0 0 1 0-1.06Z"
                                        clip-rule="evenodd" fill-rule="evenodd" />
                                </svg>
                            </div>
                            @error('preferred_language')<p class="mt-2 text-sm text-red-600 dark:text-red-400">
                                {{ $message }}
                            </p>@enderror
                        </div>
                    </div>
                </div>

                {{-- Notes Section --}}
                <div
                    class="rounded-lg border border-gray-200 dark:border-white/10 bg-white dark:bg-white/5 p-6 pb-24 lg:pb-6">
                    <div class="mb-6">
                        <h2 class="text-base/7 font-semibold text-gray-900 dark:text-white">Notes</h2>
                    </div>

                    <x-ui.textarea id="notes" name="notes" :value="old('notes')" rows="4"
                        placeholder="Additional notes about this contact..." />
                </div>
            </div>

            {{-- Right Column - 1/3 --}}
            <div class="space-y-8">
                {{-- Contact Type & Status Section --}}
                <div class="rounded-lg border border-gray-200 dark:border-white/10 bg-white dark:bg-white/5 p-6">
                    <div class="mb-6">
                        <h2 class="text-base/7 font-semibold text-gray-900 dark:text-white">Contact Type & Status</h2>
                        <p class="mt-1 text-sm/6 text-gray-600 dark:text-gray-400">Configure contact type and
                            visibility.</p>
                    </div>

                    <div class="space-y-6">
                        {{-- Is Customer --}}
                        <div>
                            <label for="is_customer"
                                class="block text-sm/6 font-medium text-gray-900 dark:text-white">Customer <span
                                    class="text-red-500">*</span></label>
                            <div class="mt-2 grid grid-cols-1">
                                <select id="is_customer" name="is_customer" required
                                    class="col-start-1 row-start-1 w-full appearance-none rounded-md bg-white py-1.5 pr-8 pl-3 text-base text-gray-900 outline-1 -outline-offset-1 outline-gray-300 focus:outline-2 focus:-outline-offset-2 focus:outline-[var(--color-accent)] sm:text-sm/6 dark:bg-white/5 dark:text-white dark:outline-white/10 dark:*:bg-gray-800 dark:focus:outline-[var(--color-accent)] @error('is_customer') outline-red-500 dark:outline-red-500 @enderror">
                                    <option value="1" {{ old('is_customer', '1') == '1' ? 'selected' : '' }}>Yes</option>
                                    <option value="0" {{ old('is_customer') == '0' ? 'selected' : '' }}>No</option>
                                </select>
                                <svg viewBox="0 0 16 16" fill="currentColor"
                                    class="pointer-events-none col-start-1 row-start-1 mr-2 size-5 self-center justify-self-end text-gray-500 sm:size-4 dark:text-gray-400">
                                    <path
                                        d="M4.22 6.22a.75.75 0 0 1 1.06 0L8 8.94l2.72-2.72a.75.75 0 1 1 1.06 1.06l-3.25 3.25a.75.75 0 0 1-1.06 0L4.22 7.28a.75.75 0 0 1 0-1.06Z"
                                        clip-rule="evenodd" fill-rule="evenodd" />
                                </svg>
                            </div>
                            @error('is_customer')<p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}
                            </p>@enderror
                        </div>

                        {{-- Is Supplier --}}
                        <div>
                            <label for="is_supplier"
                                class="block text-sm/6 font-medium text-gray-900 dark:text-white">Supplier <span
                                    class="text-red-500">*</span></label>
                            <div class="mt-2 grid grid-cols-1">
                                <select id="is_supplier" name="is_supplier" required
                                    class="col-start-1 row-start-1 w-full appearance-none rounded-md bg-white py-1.5 pr-8 pl-3 text-base text-gray-900 outline-1 -outline-offset-1 outline-gray-300 focus:outline-2 focus:-outline-offset-2 focus:outline-[var(--color-accent)] sm:text-sm/6 dark:bg-white/5 dark:text-white dark:outline-white/10 dark:*:bg-gray-800 dark:focus:outline-[var(--color-accent)] @error('is_supplier') outline-red-500 dark:outline-red-500 @enderror">
                                    <option value="0" {{ old('is_supplier', '0') == '0' ? 'selected' : '' }}>No</option>
                                    <option value="1" {{ old('is_supplier') == '1' ? 'selected' : '' }}>Yes</option>
                                </select>
                                <svg viewBox="0 0 16 16" fill="currentColor"
                                    class="pointer-events-none col-start-1 row-start-1 mr-2 size-5 self-center justify-self-end text-gray-500 sm:size-4 dark:text-gray-400">
                                    <path
                                        d="M4.22 6.22a.75.75 0 0 1 1.06 0L8 8.94l2.72-2.72a.75.75 0 1 1 1.06 1.06l-3.25 3.25a.75.75 0 0 1-1.06 0L4.22 7.28a.75.75 0 0 1 0-1.06Z"
                                        clip-rule="evenodd" fill-rule="evenodd" />
                                </svg>
                            </div>
                            @error('is_supplier')<p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}
                            </p>@enderror
                        </div>

                        {{-- Status Toggle --}}
                        <div class="flex items-center justify-between">
                            <div>
                                <label class="block text-sm/6 font-medium text-gray-900 dark:text-white">Status</label>
                                <p class="text-sm/6 text-gray-600 dark:text-gray-400">Make contact active</p>
                            </div>
                            <div>
                                <input type="hidden" name="is_active" value="0">
                                <x-ui.toggle name="is_active" :checked="old('is_active', '1') == '1'" />
                            </div>
                        </div>
                        @error('is_active')<p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>
        </div>

        {{-- Form Action Buttons --}}
        <div class="mt-8 flex flex-col sm:flex-row items-center justify-end gap-3 px-4 sm:px-0">
            <a href="{{ route('admin.administrator.contacts.index') }}"
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