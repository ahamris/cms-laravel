<x-layouts.admin title="Edit Organization Name">
    <div class="space-y-6 max-w-7xl">
        {{-- Page Header --}}
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-zinc-900 dark:text-white mb-2">Edit Organization Name</h1>
                <p class="text-zinc-600 dark:text-zinc-400">Update organization name details</p>
            </div>
            <div class="flex items-center gap-3">
                <x-button variant="outline-primary" href="{{ route('admin.content.organization-name.show', $organizationName) }}" icon="eye">
                    View
                </x-button>
                <x-button variant="default" href="{{ route('admin.content.organization-name.index') }}" icon="arrow-left">
                    Back to List
                </x-button>
            </div>
        </div>

        {{-- Form --}}
        <form action="{{ route('admin.content.organization-name.update', $organizationName) }}" method="POST">
            @csrf
            @method('PUT')
            
            <div class="space-y-12">
                {{-- Basic Information Section --}}
                <div class="grid grid-cols-1 gap-x-8 gap-y-10 border-b border-gray-900/10 pb-12 md:grid-cols-3 dark:border-white/10">
                    <div>
                        <h2 class="text-base/7 font-semibold text-gray-900 dark:text-white">Basic Information</h2>
                        <p class="mt-1 text-sm/6 text-gray-600 dark:text-gray-400">This information will be displayed publicly so be careful what you share.</p>
                    </div>

                    <div class="grid max-w-2xl grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6 md:col-span-2">
                        <div class="col-span-full">
                            <label for="name" class="block text-sm/6 font-medium text-gray-900 dark:text-white">
                                Organization Name
                                <span class="text-red-600 dark:text-red-400">*</span>
                            </label>
                            <div class="mt-2">
                                <input 
                                    id="name" 
                                    type="text" 
                                    name="name" 
                                    value="{{ old('name', $organizationName->name) }}"
                                    placeholder="e.g., United Nations Educational, Scientific and Cultural Organization"
                                    required
                                    class="block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline-2 focus:-outline-offset-2 focus:outline-[var(--color-accent)] sm:text-sm/6 dark:bg-white/5 dark:text-white dark:outline-white/10 dark:placeholder:text-gray-500 dark:focus:outline-[var(--color-accent)] @error('name') outline-red-600 dark:outline-red-500 @enderror"
                                />
                            </div>
                            @error('name')
                                <p class="mt-2 text-sm/6 text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="sm:col-span-3">
                            <label for="abbreviation" class="block text-sm/6 font-medium text-gray-900 dark:text-white">Abbreviation</label>
                            <div class="mt-2">
                                <input 
                                    id="abbreviation" 
                                    type="text" 
                                    name="abbreviation" 
                                    value="{{ old('abbreviation', $organizationName->abbreviation) }}"
                                    placeholder="e.g., UNESCO"
                                    maxlength="10"
                                    class="block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline-2 focus:-outline-offset-2 focus:outline-[var(--color-accent)] sm:text-sm/6 dark:bg-white/5 dark:text-white dark:outline-white/10 dark:placeholder:text-gray-500 dark:focus:outline-[var(--color-accent)] @error('abbreviation') outline-red-600 dark:outline-red-500 @enderror"
                                />
                            </div>
                            <p class="mt-2 text-sm/6 text-gray-600 dark:text-gray-400">Maximum 10 characters</p>
                            @error('abbreviation')
                                <p class="mt-2 text-sm/6 text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="sm:col-span-3">
                            <label for="email" class="block text-sm/6 font-medium text-gray-900 dark:text-white">Email address</label>
                            <div class="mt-2">
                                <input 
                                    id="email" 
                                    type="email" 
                                    name="email" 
                                    value="{{ old('email', $organizationName->email) }}"
                                    placeholder="e.g., contact@organization.org"
                                    class="block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline-2 focus:-outline-offset-2 focus:outline-[var(--color-accent)] sm:text-sm/6 dark:bg-white/5 dark:text-white dark:outline-white/10 dark:placeholder:text-gray-500 dark:focus:outline-[var(--color-accent)] @error('email') outline-red-600 dark:outline-red-500 @enderror"
                                />
                            </div>
                            @error('email')
                                <p class="mt-2 text-sm/6 text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="col-span-full">
                            <label for="address" class="block text-sm/6 font-medium text-gray-900 dark:text-white">Address</label>
                            <div class="mt-2">
                                <textarea 
                                    id="address" 
                                    name="address" 
                                    rows="3"
                                    placeholder="Enter the full address of the organization"
                                    class="block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline-2 focus:-outline-offset-2 focus:outline-[var(--color-accent)] sm:text-sm/6 dark:bg-white/5 dark:text-white dark:outline-white/10 dark:placeholder:text-gray-500 dark:focus:outline-[var(--color-accent)] @error('address') outline-red-600 dark:outline-red-500 @enderror"
                                >{{ old('address', $organizationName->address) }}</textarea>
                            </div>
                            <p class="mt-2 text-sm/6 text-gray-600 dark:text-gray-400">Maximum 1000 characters</p>
                            @error('address')
                                <p class="mt-2 text-sm/6 text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                {{-- Settings Section --}}
                <div class="grid grid-cols-1 gap-x-8 gap-y-10 border-b border-gray-900/10 pb-12 md:grid-cols-3 dark:border-white/10">
                    <div>
                        <h2 class="text-base/7 font-semibold text-gray-900 dark:text-white">Settings</h2>
                        <p class="mt-1 text-sm/6 text-gray-600 dark:text-gray-400">Configure how this organization name appears and behaves in the system.</p>
                    </div>

                    <div class="grid max-w-2xl grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6 md:col-span-2">
                        <div class="sm:col-span-3">
                            <label for="sort_order" class="block text-sm/6 font-medium text-gray-900 dark:text-white">Sort Order</label>
                            <div class="mt-2">
                                <input 
                                    id="sort_order" 
                                    type="number" 
                                    name="sort_order" 
                                    value="{{ old('sort_order', $organizationName->sort_order) }}"
                                    min="0"
                                    class="block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline-2 focus:-outline-offset-2 focus:outline-[var(--color-accent)] sm:text-sm/6 dark:bg-white/5 dark:text-white dark:outline-white/10 dark:placeholder:text-gray-500 dark:focus:outline-[var(--color-accent)] @error('sort_order') outline-red-600 dark:outline-red-500 @enderror"
                                />
                            </div>
                            <p class="mt-2 text-sm/6 text-gray-600 dark:text-gray-400">Lower numbers appear first</p>
                            @error('sort_order')
                                <p class="mt-2 text-sm/6 text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="sm:col-span-3">
                            <label class="block text-sm/6 font-medium text-gray-900 dark:text-white mb-2">Active Status</label>
                            <div class="mt-2 flex items-center gap-3">
                                <div class="flex h-6 shrink-0 items-center">
                                    <div class="group grid size-4 grid-cols-1">
                                        <input 
                                            id="is_active" 
                                            type="checkbox" 
                                            name="is_active" 
                                            value="1"
                                            {{ old('is_active', $organizationName->is_active) ? 'checked' : '' }}
                                            class="col-start-1 row-start-1 appearance-none rounded-sm border border-gray-300 bg-white checked:border-[var(--color-accent)] checked:bg-[var(--color-accent)] indeterminate:border-[var(--color-accent)] indeterminate:bg-[var(--color-accent)] focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-[var(--color-accent)] disabled:border-gray-300 disabled:bg-gray-100 disabled:checked:bg-gray-100 dark:border-white/10 dark:bg-white/5 dark:checked:border-[var(--color-accent)] dark:checked:bg-[var(--color-accent)] dark:indeterminate:border-[var(--color-accent)] dark:indeterminate:bg-[var(--color-accent)] dark:focus-visible:outline-[var(--color-accent)] dark:disabled:border-white/5 dark:disabled:bg-white/10 dark:disabled:checked:bg-white/10"
                                        />
                                        <svg viewBox="0 0 14 14" fill="none" class="pointer-events-none col-start-1 row-start-1 size-3.5 self-center justify-self-center stroke-white group-has-disabled:stroke-gray-950/25 dark:group-has-disabled:stroke-white/25">
                                            <path d="M3 8L6 11L11 3.5" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="opacity-0 group-has-checked:opacity-100" />
                                            <path d="M3 7H11" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="opacity-0 group-has-indeterminate:opacity-100" />
                                        </svg>
                                    </div>
                                </div>
                                <div class="text-sm/6">
                                    <label for="is_active" class="font-medium text-gray-900 dark:text-white">Active</label>
                                    <p class="text-gray-500 dark:text-gray-400">Visible in lists and searches</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Form Actions --}}
            <div class="mt-6 flex items-center justify-end gap-x-6">
                <button type="button" onclick="window.location.href='{{ route('admin.content.organization-name.index') }}'" class="text-sm/6 font-semibold text-gray-900 dark:text-white hover:text-gray-700 dark:hover:text-gray-300">
                    Cancel
                </button>
                <button type="submit" class="rounded-md bg-[var(--color-accent)] px-3 py-2 text-sm font-semibold text-[var(--color-accent-foreground)] shadow-xs hover:bg-[color-mix(in_oklab,var(--color-accent)_90%,transparent)] focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-[var(--color-accent)] dark:shadow-none">
                    Update Organization Name
                </button>
            </div>
        </form>
    </div>
</x-layouts.admin>
