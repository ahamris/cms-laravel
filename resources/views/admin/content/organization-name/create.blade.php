<x-layouts.admin title="Create Organization">
    <div class="space-y-6">
        {{-- Page Header --}}
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold tracking-tight text-zinc-900 dark:text-white">Create Organization</h1>
                <p class="text-sm text-zinc-500 dark:text-zinc-400">Add a new organization to your system.</p>
            </div>

            <x-ui.button variant="secondary" icon="arrow-left"
                href="{{ route('admin.content.organization-name.index') }}">
                Cancel & Return
            </x-ui.button>
        </div>

        <form action="{{ route('admin.content.organization-name.store') }}" method="POST"
            class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            @csrf

            {{-- Main Content Column --}}
            <div class="lg:col-span-2 space-y-6">

                {{-- General Information Section --}}
                <div
                    class="bg-white dark:bg-zinc-800 rounded-xl border border-zinc-200 dark:border-zinc-700 p-6 space-y-6">
                    <h3 class="text-lg font-semibold text-zinc-900 dark:text-white flex items-center gap-2">
                        <i class="fa-solid fa-building text-zinc-400"></i>
                        Organization Details
                    </h3>

                    {{-- Name Input --}}
                    <x-ui.input label="Organization Name" name="name" id="name" :value="old('name')"
                        placeholder="e.g., United Nations Educational, Scientific and Cultural Organization" required
                        hint="Full official name of the organization." />

                    {{-- Abbreviation Input --}}
                    <x-ui.input label="Abbreviation" name="abbreviation" id="abbreviation" :value="old('abbreviation')"
                        placeholder="e.g., UNESCO" maxlength="10" hint="Short form or acronym (max 10 characters)." />

                    {{-- Email Input --}}
                    <x-ui.input label="Email Address" name="email" id="email" type="email" :value="old('email')"
                        placeholder="e.g., contact@organization.org" icon="envelope" />

                    {{-- Address Textarea --}}
                    <x-ui.textarea label="Address" name="address" id="address" :value="old('address')"
                        placeholder="Enter the full address of the organization" rows="3"
                        hint="Maximum 1000 characters." />
                </div>
            </div>

            {{-- Sidebar Column --}}
            <div class="lg:col-span-1 space-y-6">

                {{-- Settings Card --}}
                <div
                    class="bg-white dark:bg-zinc-800 rounded-xl border border-zinc-200 dark:border-zinc-700 p-6 space-y-6">
                    <h3 class="text-lg font-semibold text-zinc-900 dark:text-white flex items-center gap-2">
                        <i class="fa-solid fa-sliders text-zinc-400"></i>
                        Settings
                    </h3>

                    {{-- Active Toggle --}}
                    <div class="flex items-center justify-between">
                        <div class="flex flex-col">
                            <span class="text-sm font-medium text-zinc-900 dark:text-white">Active Status</span>
                            <span class="text-xs text-zinc-500">Enable to show in lists</span>
                        </div>
                        <x-ui.toggle name="is_active" :checked="old('is_active', true)" />
                    </div>

                    <hr class="border-zinc-200 dark:border-zinc-700">

                    {{-- Sort Order --}}
                    <x-ui.input label="Sort Order" type="number" name="sort_order" id="sort_order"
                        :value="old('sort_order', 0)" placeholder="0" min="0" hint="Lower numbers appear first." />

                    <x-ui.button type="submit" variant="primary" class="w-full justify-center">
                        Create Organization
                    </x-ui.button>
                </div>

                {{-- Help Card --}}
                <div
                    class="rounded-lg border border-blue-200 bg-blue-50 p-4 dark:border-blue-900/30 dark:bg-blue-900/10">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <i class="fa-solid fa-circle-info text-blue-400"></i>
                        </div>
                        <div class="ml-3">
                            <h3 class="text-sm font-medium text-blue-800 dark:text-blue-300">Tips</h3>
                            <div class="mt-2 text-sm text-blue-700 dark:text-blue-400">
                                <ul role="list" class="list-disc space-y-1 pl-5">
                                    <li>Use the full official name for clarity.</li>
                                    <li>Abbreviations help with quick identification.</li>
                                    <li>Email is optional but useful for contact.</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</x-layouts.admin>