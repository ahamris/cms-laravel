<x-layouts.admin title="Create Organization">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-8">
        <div>
            <h1 class="text-3xl font-bold text-zinc-900 dark:text-white mb-2">Create Organization</h1>
            <p class="text-zinc-600 dark:text-zinc-400">Add a new organization (name and logo)</p>
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ route('admin.organization.index') }}"
                class="inline-flex items-center gap-2 rounded-md bg-white dark:bg-white/10 px-4 py-2 text-sm font-semibold text-gray-900 dark:text-white shadow-xs ring-1 ring-gray-300 ring-inset dark:ring-white/10 hover:bg-gray-50 dark:hover:bg-white/20">
                <i class="fa-solid fa-arrow-left"></i>
                Back to Organizations
            </a>
        </div>
    </div>

    <form action="{{ route('admin.organization.store') }}" method="POST" enctype="multipart/form-data" id="organization-form">
        @csrf

        <div class="max-w-6xl w-full space-y-8">
            <div class="rounded-lg border border-gray-200 dark:border-white/10 bg-white dark:bg-white/5 p-8 shadow-sm">
                <div class="mb-8">
                    <h2 class="text-base/7 font-semibold text-gray-900 dark:text-white">Details</h2>
                    <p class="mt-1 text-sm/6 text-gray-600 dark:text-gray-400">Name and logo for the organization.</p>
                </div>
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-10">
                    <div class="lg:col-span-2 space-y-6">
                        <x-ui.input id="name" name="name" label="Name" :value="old('name')"
                            placeholder="Organization name" required :error="$errors->has('name')"
                            :errorMessage="$errors->first('name')" />
                    </div>
                    <div class="lg:col-span-1">
                        <x-ui.image-upload
                            id="logo"
                            name="logo"
                            label="Logo"
                            :required="false"
                            help-text="JPG, PNG, GIF, WebP or SVG. Max 20MB."
                            :max-size="20480"
                            size="large"
                            current-image-alt="Organization logo"
                        />
                    </div>
                </div>
            </div>
        </div>

        <div class="mt-8 flex flex-col sm:flex-row items-center justify-end gap-3">
            <a href="{{ route('admin.organization.index') }}"
                class="w-full sm:w-auto inline-flex justify-center gap-2 rounded-md bg-white dark:bg-white/10 px-4 py-2 text-sm font-semibold text-gray-900 dark:text-white shadow-xs ring-1 ring-gray-300 ring-inset dark:ring-white/10 hover:bg-gray-50 dark:hover:bg-white/20">
                Cancel
            </a>
            <button type="submit"
                class="w-full sm:w-auto inline-flex justify-center gap-2 rounded-md bg-[var(--color-accent)] px-6 py-2 text-sm font-semibold text-white shadow-xs ring-1 ring-inset ring-[var(--color-accent)] hover:opacity-90 transition-opacity">
                Create Organization
            </button>
        </div>
    </form>
</x-layouts.admin>
