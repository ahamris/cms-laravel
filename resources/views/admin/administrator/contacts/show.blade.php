<x-layouts.admin title="Contact Details">
    {{-- Page Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-8">
        <div>
            <h1 class="text-3xl font-bold text-zinc-900 dark:text-white mb-2">{{ $contact->organization_name }}</h1>
            <p class="text-zinc-600 dark:text-zinc-400">Contact details and information</p>
        </div>
        <div class="flex flex-wrap items-center gap-3">
            <a href="{{ route('admin.administrator.contacts.index') }}" class="inline-flex items-center gap-2 rounded-md bg-white dark:bg-white/10 px-4 py-2 text-sm font-semibold text-gray-900 dark:text-white shadow-xs ring-1 ring-gray-300 ring-inset dark:ring-white/10 hover:bg-gray-50 dark:hover:bg-white/20">
                <i class="fa-solid fa-arrow-left"></i>
                Back to Contacts
            </a>
            <a href="{{ route('admin.administrator.contacts.edit', $contact) }}" class="inline-flex items-center gap-2 rounded-md bg-gray-600 px-4 py-2 text-sm font-semibold text-white shadow-xs hover:bg-gray-500">
                <i class="fa-solid fa-edit"></i>
                Edit
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        {{-- Left Column - 2/3 --}}
        <div class="lg:col-span-2 space-y-8">
            {{-- Organization Identity Section --}}
            <div class="rounded-lg border border-gray-200 dark:border-white/10 bg-white dark:bg-white/5 p-6">
                <div class="mb-6">
                    <h2 class="text-base/7 font-semibold text-gray-900 dark:text-white">Organization Identity</h2>
                    <p class="mt-1 text-sm/6 text-gray-600 dark:text-gray-400">Basic information about the organization.</p>
                </div>

                <div class="space-y-6">
                    {{-- Organization Name --}}
                    <div>
                        <label class="block text-sm/6 font-medium text-gray-900 dark:text-white">Organization Name</label>
                        <div class="mt-2">
                            <p class="text-lg font-medium text-gray-900 dark:text-white">{{ $contact->organization_name }}</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        {{-- Alias --}}
                        @if($contact->alias)
                        <div>
                            <label class="block text-sm/6 font-medium text-gray-900 dark:text-white">Alias</label>
                            <div class="mt-2">
                                <p class="text-sm text-gray-700 dark:text-gray-300">{{ $contact->alias }}</p>
                            </div>
                        </div>
                        @endif

                        {{-- Slug --}}
                        @if($contact->slug)
                        <div>
                            <label class="block text-sm/6 font-medium text-gray-900 dark:text-white">Slug</label>
                            <div class="mt-2">
                                <code class="inline-flex items-center rounded-md bg-gray-100 dark:bg-white/10 py-1.5 text-sm font-mono text-gray-800 dark:text-gray-200">{{ $contact->slug }}</code>
                            </div>
                        </div>
                        @endif

                        {{-- Email --}}
                        @if($contact->email)
                        <div>
                            <label class="block text-sm/6 font-medium text-gray-900 dark:text-white">Email</label>
                            <div class="mt-2">
                                <a href="mailto:{{ $contact->email }}" class="text-sm text-[var(--color-accent)] hover:underline">
                                    {{ $contact->email }}
                                </a>
                            </div>
                        </div>
                        @endif

                        {{-- Phone --}}
                        @if($contact->phone)
                        <div>
                            <label class="block text-sm/6 font-medium text-gray-900 dark:text-white">Phone</label>
                            <div class="mt-2">
                                <a href="tel:{{ $contact->phone }}" class="text-sm text-[var(--color-accent)] hover:underline">
                                    {{ $contact->phone }}
                                </a>
                            </div>
                        </div>
                        @endif
                    </div>

                    {{-- Website --}}
                    @if($contact->website)
                    <div>
                        <label class="block text-sm/6 font-medium text-gray-900 dark:text-white">Website</label>
                        <div class="mt-2">
                            <a href="{{ $contact->website }}" target="_blank" class="text-sm text-[var(--color-accent)] hover:underline inline-flex items-center gap-1">
                                {{ $contact->website }}
                                <i class="fa-solid fa-external-link-alt text-xs"></i>
                            </a>
                        </div>
                    </div>
                    @endif
                </div>
            </div>

            {{-- Registration & Tax Section --}}
            @if($contact->chamber_of_commerce || $contact->tax_number)
            <div class="rounded-lg border border-gray-200 dark:border-white/10 bg-white dark:bg-white/5 p-6">
                <div class="mb-6">
                    <h2 class="text-base/7 font-semibold text-gray-900 dark:text-white">Registration & Tax</h2>
                    <p class="mt-1 text-sm/6 text-gray-600 dark:text-gray-400">Official registration and tax information.</p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    @if($contact->chamber_of_commerce)
                    <div>
                        <label class="block text-sm/6 font-medium text-gray-900 dark:text-white">Chamber of Commerce (KVK)</label>
                        <div class="mt-2">
                            <code class="inline-flex items-center rounded-md bg-gray-100 dark:bg-white/10 py-1.5 text-sm font-mono text-gray-800 dark:text-gray-200">{{ $contact->chamber_of_commerce }}</code>
                        </div>
                    </div>
                    @endif

                    @if($contact->tax_number)
                    <div>
                        <label class="block text-sm/6 font-medium text-gray-900 dark:text-white">Tax Number (VAT)</label>
                        <div class="mt-2">
                            <code class="inline-flex items-center rounded-md bg-gray-100 dark:bg-white/10 py-1.5 text-sm font-mono text-gray-800 dark:text-gray-200">{{ $contact->tax_number }}</code>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
            @endif

            {{-- Invoicing Preferences Section --}}
            <div class="rounded-lg border border-gray-200 dark:border-white/10 bg-white dark:bg-white/5 p-6">
                <div class="mb-6">
                    <h2 class="text-base/7 font-semibold text-gray-900 dark:text-white">Invoicing Preferences</h2>
                    <p class="mt-1 text-sm/6 text-gray-600 dark:text-gray-400">Configure invoicing and payment settings.</p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    @if($contact->invoice_email)
                    <div>
                        <label class="block text-sm/6 font-medium text-gray-900 dark:text-white">Invoice Email</label>
                        <div class="mt-2">
                            <a href="mailto:{{ $contact->invoice_email }}" class="text-sm text-[var(--color-accent)] hover:underline">
                                {{ $contact->invoice_email }}
                            </a>
                        </div>
                    </div>
                    @endif

                    <div>
                        <label class="block text-sm/6 font-medium text-gray-900 dark:text-white">Payment Due Days</label>
                        <div class="mt-2">
                            <p class="text-sm text-gray-700 dark:text-gray-300">{{ $contact->payment_due_days }} days</p>
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm/6 font-medium text-gray-900 dark:text-white">Currency</label>
                        <div class="mt-2">
                            <code class="inline-flex items-center rounded-md bg-gray-100 dark:bg-white/10 py-1.5 text-sm font-mono text-gray-800 dark:text-gray-200">{{ $contact->currency }}</code>
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm/6 font-medium text-gray-900 dark:text-white">Preferred Language</label>
                        <div class="mt-2">
                            <p class="text-sm text-gray-700 dark:text-gray-300">
                                @switch($contact->preferred_language)
                                    @case('nl')
                                        Dutch
                                        @break
                                    @case('en')
                                        English
                                        @break
                                    @case('de')
                                        German
                                        @break
                                    @case('fr')
                                        French
                                        @break
                                    @default
                                        {{ $contact->preferred_language }}
                                @endswitch
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Banking Information Section --}}
            @if($contact->iban || $contact->bic)
            <div class="rounded-lg border border-gray-200 dark:border-white/10 bg-white dark:bg-white/5 p-6">
                <div class="mb-6">
                    <h2 class="text-base/7 font-semibold text-gray-900 dark:text-white">Banking Information</h2>
                    <p class="mt-1 text-sm/6 text-gray-600 dark:text-gray-400">Bank account details for invoicing.</p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    @if($contact->iban)
                    <div>
                        <label class="block text-sm/6 font-medium text-gray-900 dark:text-white">IBAN</label>
                        <div class="mt-2">
                            <code class="inline-flex items-center rounded-md bg-gray-100 dark:bg-white/10 py-1.5 text-sm font-mono text-gray-800 dark:text-gray-200">{{ $contact->iban }}</code>
                        </div>
                    </div>
                    @endif

                    @if($contact->bic)
                    <div>
                        <label class="block text-sm/6 font-medium text-gray-900 dark:text-white">BIC</label>
                        <div class="mt-2">
                            <code class="inline-flex items-center rounded-md bg-gray-100 dark:bg-white/10 py-1.5 text-sm font-mono text-gray-800 dark:text-gray-200">{{ $contact->bic }}</code>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
            @endif

            {{-- Notes Section --}}
            @if($contact->notes)
            <div class="rounded-lg border border-gray-200 dark:border-white/10 bg-white dark:bg-white/5 p-6">
                <div class="mb-6">
                    <h2 class="text-base/7 font-semibold text-gray-900 dark:text-white">Notes</h2>
                    <p class="mt-1 text-sm/6 text-gray-600 dark:text-gray-400">Additional notes about this contact.</p>
                </div>

                <div class="mt-2 rounded-lg bg-gray-50 dark:bg-white/5 p-4 border border-gray-200 dark:border-white/10">
                    <p class="text-sm text-gray-700 dark:text-gray-300 whitespace-pre-wrap">{{ $contact->notes }}</p>
                </div>
            </div>
            @endif
        </div>

        {{-- Right Column - 1/3 --}}
        <div class="lg:col-span-1 space-y-8">
            {{-- Contact Type & Status Section --}}
            <div class="rounded-lg border border-gray-200 dark:border-white/10 bg-white dark:bg-white/5 p-6">
                <div class="mb-6">
                    <h2 class="text-base/7 font-semibold text-gray-900 dark:text-white">Contact Type & Status</h2>
                    <p class="mt-1 text-sm/6 text-gray-600 dark:text-gray-400">Contact classification and visibility.</p>
                </div>

                <div class="space-y-6">
                    {{-- Status --}}
                    <div class="flex items-center justify-between">
                        <label class="block text-sm/6 font-medium text-gray-900 dark:text-white">Status</label>
                        @if($contact->is_active)
                            <span class="inline-flex items-center gap-x-1.5 rounded-full bg-green-100 dark:bg-green-500/10 px-3 py-1.5 text-sm font-medium text-green-700 dark:text-green-400">
                                <svg class="size-2 fill-green-500" viewBox="0 0 6 6"><circle cx="3" cy="3" r="3" /></svg>
                                Active
                            </span>
                        @else
                            <span class="inline-flex items-center gap-x-1.5 rounded-full bg-red-100 dark:bg-red-500/10 px-3 py-1.5 text-sm font-medium text-red-700 dark:text-red-400">
                                <svg class="size-2 fill-red-500" viewBox="0 0 6 6"><circle cx="3" cy="3" r="3" /></svg>
                                Inactive
                            </span>
                        @endif
                    </div>

                    {{-- Contact Type --}}
                    <div>
                        <label class="block text-sm/6 font-medium text-gray-900 dark:text-white">Contact Type</label>
                        <div class="mt-2 flex flex-wrap gap-2">
                            @if($contact->is_customer)
                                <span class="inline-flex items-center gap-x-1.5 rounded-full bg-green-100 dark:bg-green-500/10 px-3 py-1.5 text-sm font-medium text-green-700 dark:text-green-400">
                                    <i class="fa-solid fa-user-tie text-xs"></i>
                                    Customer
                                </span>
                            @endif
                            @if($contact->is_supplier)
                                <span class="inline-flex items-center gap-x-1.5 rounded-full bg-blue-100 dark:bg-blue-500/10 px-3 py-1.5 text-sm font-medium text-blue-700 dark:text-blue-400">
                                    <i class="fa-solid fa-truck text-xs"></i>
                                    Supplier
                                </span>
                            @endif
                            @if(!$contact->is_customer && !$contact->is_supplier)
                                <span class="inline-flex items-center gap-x-1.5 rounded-full bg-gray-100 dark:bg-gray-500/10 px-3 py-1.5 text-sm font-medium text-gray-600 dark:text-gray-400">
                                    <i class="fa-solid fa-question text-xs"></i>
                                    No type assigned
                                </span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            {{-- Timestamps Section --}}
            <div class="rounded-lg border border-gray-200 dark:border-white/10 bg-white dark:bg-white/5 p-6">
                <div class="mb-6">
                    <h2 class="text-base/7 font-semibold text-gray-900 dark:text-white">Timestamps</h2>
                    <p class="mt-1 text-sm/6 text-gray-600 dark:text-gray-400">Creation and modification dates.</p>
                </div>

                <div class="space-y-6">
                    {{-- Created At --}}
                    <div>
                        <label class="block text-sm/6 font-medium text-gray-900 dark:text-white">Created At</label>
                        <div class="mt-2">
                            <p class="text-sm text-gray-700 dark:text-gray-300">{{ $contact->created_at->format('M d, Y H:i:s') }}</p>
                            <p class="text-xs text-gray-500 dark:text-gray-400">{{ $contact->created_at->diffForHumans() }}</p>
                        </div>
                    </div>

                    {{-- Updated At --}}
                    <div>
                        <label class="block text-sm/6 font-medium text-gray-900 dark:text-white">Updated At</label>
                        <div class="mt-2">
                            <p class="text-sm text-gray-700 dark:text-gray-300">{{ $contact->updated_at->format('M d, Y H:i:s') }}</p>
                            <p class="text-xs text-gray-500 dark:text-gray-400">{{ $contact->updated_at->diffForHumans() }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-layouts.admin>
