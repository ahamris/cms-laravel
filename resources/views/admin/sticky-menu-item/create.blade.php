<x-layouts.admin title="Create Sticky Menu Item">
    <div class="space-y-6">
        {{-- Page Header --}}
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold tracking-tight text-zinc-900 dark:text-white">Create Sticky Menu Item</h1>
                <p class="text-sm text-zinc-500 dark:text-zinc-400">Add a new item to your website's sticky menu
                    configuration.</p>
            </div>

            <x-ui.button variant="secondary" icon="arrow-left"
                href="{{ route('admin.sticky-menu-item.index') }}">
                Cancel & Return
            </x-ui.button>
        </div>

        <form action="{{ route('admin.sticky-menu-item.store') }}" method="POST"
            class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            @csrf

            {{-- Main Content Column --}}
            <div class="lg:col-span-2 space-y-6">

                {{-- General Information Section --}}
                <div
                    class="bg-white dark:bg-zinc-800 rounded-xl border border-zinc-200 dark:border-zinc-700 p-6 space-y-6">
                    <h3 class="text-lg font-semibold text-zinc-900 dark:text-white flex items-center gap-2">
                        <i class="fa-solid fa-pen-to-square text-zinc-400"></i>
                        General Information
                    </h3>

                    {{-- Title Input --}}
                    <x-ui.input label="Display Title" name="title" id="title" :value="old('title')"
                        placeholder="Enter menu item title (e.g. 'Support')" required
                        hint="This title will be visible to users in the sticky menu." />

                    {{-- Link Input --}}
                    <x-ui.input label="Destination URL" name="link" id="link" :value="old('link')"
                        placeholder="https://example.com/page or /internal-path" required icon="link" />

                    {{-- Icon Selection --}}
                    <div>
                        <x-icon-picker label="Icon" name="icon" :value="old('icon')" required />
                        @error('icon')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                        <p class="mt-1 text-xs text-zinc-500">Select an icon that best represents this menu item.</p>
                    </div>
                </div>


            </div>

            {{-- Sidebar Column --}}
            <div class="lg:col-span-1 space-y-6">

                {{-- Publish Settings --}}
                <div
                    class="bg-white dark:bg-zinc-800 rounded-xl border border-zinc-200 dark:border-zinc-700 p-6 space-y-6">
                    <h3 class="text-lg font-semibold text-zinc-900 dark:text-white flex items-center gap-2">
                        <i class="fa-solid fa-globe text-zinc-400"></i>
                        Publish Settings
                    </h3>

                    {{-- Active Toggle --}}
                    <div class="flex items-center justify-between">
                        <div class="flex flex-col">
                            <span class="text-sm font-medium text-zinc-900 dark:text-white">Active Status</span>
                            <span class="text-xs text-zinc-500">Enable to show on site</span>
                        </div>
                        <x-ui.toggle name="is_active" :checked="old('is_active', true)" />
                    </div>

                    {{-- External Link Toggle --}}
                    <div class="flex items-center justify-between">
                        <div class="flex flex-col">
                            <span class="text-sm font-medium text-zinc-900 dark:text-white">Open in New Tab</span>
                            <span class="text-xs text-zinc-500">External link behavior</span>
                        </div>
                        <x-ui.toggle name="is_external" :checked="old('is_external', false)" />
                    </div>

                    <hr class="border-zinc-200 dark:border-zinc-700">

                    {{-- Sort Order --}}
                    <x-ui.input label="Sort Order" type="number" name="sort_order" id="sort_order"
                        :value="old('sort_order', 0)" placeholder="0" min="0"
                        hint="Higher numbers appear later in the list." />

                    <x-ui.button type="submit" variant="primary" class="w-full justify-center">
                        Create Menu Item
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
                                    <li>Use short, clear titles for better UX.</li>
                                    <li>Ensure external links start with <code>https://</code>.</li>
                                    <li>Check the sort order to control positioning.</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</x-layouts.admin>