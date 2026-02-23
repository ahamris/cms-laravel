<x-layouts.admin title="Edit Presenter">
    {{-- Page Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-8">
        <div>
            <h1 class="text-3xl font-bold text-zinc-900 dark:text-white mb-2">Edit Presenter</h1>
            <p class="text-zinc-600 dark:text-zinc-400">Update presenter information</p>
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ route('admin.presenter.show', $presenter) }}">
                <x-button variant="secondary" icon="eye">View Details</x-button>
            </a>
            <a href="{{ route('admin.presenter.index') }}">
                <x-button variant="secondary" icon="arrow-left">Back to List</x-button>
            </a>
        </div>
    </div>

    <form action="{{ route('admin.presenter.update', $presenter) }}" method="POST"
        enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            {{-- Left Column - 2/3 --}}
            <div class="lg:col-span-2 space-y-8">
                {{-- Basic Information --}}
                <div
                    class="rounded-md border border-zinc-200 dark:border-white/10 bg-white dark:bg-white/5 p-6 shadow-sm">
                    <div class="mb-6">
                        <h2 class="text-lg font-bold text-gray-900 dark:text-white">Basic Information</h2>
                        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">Personal details and professional info.
                        </p>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        <x-ui.input name="name" id="name" label="Name" :value="old('name', $presenter->name)"
                            placeholder="Enter presenter name" required :error="$errors->has('name')"
                            :errorMessage="$errors->first('name')" />
                        <x-ui.input name="title" id="title" label="Job Title" :value="old('title', $presenter->title)"
                            placeholder="e.g., Senior Developer" :error="$errors->has('title')"
                            :errorMessage="$errors->first('title')" />
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        <x-ui.input name="company" id="company" label="Company" :value="old('company', $presenter->company)" placeholder="Company or organization name"
                            :error="$errors->has('company')" :errorMessage="$errors->first('company')" />
                        <x-ui.input name="email" id="email" label="Email Address" type="email" :value="old('email', $presenter->email)" placeholder="presenter@example.com" :error="$errors->has('email')"
                            :errorMessage="$errors->first('email')" />
                    </div>

                    <x-ui.textarea name="bio" id="bio" label="Biography" :value="old('bio', $presenter->bio)"
                        placeholder="Brief biography or description of the presenter..." :rows="4"
                        hint="Maximum 2000 characters" :error="$errors->has('bio')"
                        :errorMessage="$errors->first('bio')" />
                </div>

                {{-- Social Links --}}
                <div
                    class="rounded-md border border-zinc-200 dark:border-white/10 bg-white dark:bg-white/5 p-6 shadow-sm">
                    <div class="mb-6">
                        <h2 class="text-lg font-bold text-gray-900 dark:text-white">Social Media</h2>
                        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">Links to presenter's social profiles.
                        </p>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <x-ui.input name="linkedin_url" id="linkedin_url" label="LinkedIn URL" type="url"
                            :value="old('linkedin_url', $presenter->linkedin_url)"
                            placeholder="https://linkedin.com/in/username" :error="$errors->has('linkedin_url')"
                            :errorMessage="$errors->first('linkedin_url')" />
                        <x-ui.input name="twitter_url" id="twitter_url" label="Twitter URL" type="url"
                            :value="old('twitter_url', $presenter->twitter_url)"
                            placeholder="https://twitter.com/username" :error="$errors->has('twitter_url')"
                            :errorMessage="$errors->first('twitter_url')" />
                    </div>
                </div>
            </div>

            {{-- Right Column - 1/3 --}}
            <div class="lg:col-span-1 space-y-8">
                {{-- Publish Action --}}
                <div
                    class="rounded-md border border-zinc-200 dark:border-white/10 bg-white dark:bg-white/5 p-6 shadow-sm">
                    <h2 class="text-lg font-bold text-gray-900 dark:text-white mb-4">Actions</h2>
                    <div class="space-y-4">
                        <x-button variant="primary" type="submit" class="w-full justify-center" icon="save"
                            icon-position="left">Update Presenter</x-button>
                        <a href="{{ route('admin.presenter.index') }}" class="block">
                            <x-button variant="secondary" type="button" class="w-full justify-center">Cancel</x-button>
                        </a>
                    </div>
                </div>

                {{-- Avatar --}}
                <div
                    class="rounded-md border border-zinc-200 dark:border-white/10 bg-white dark:bg-white/5 p-6 shadow-sm">
                    <h2 class="text-lg font-bold text-gray-900 dark:text-white mb-4">Profile Picture</h2>
                    <x-image-upload id="avatar" name="avatar" label="Avatar" :required="false"
                        help-text="Upload JPG, PNG, or GIF. Max 2MB." :max-size="2048" size="small"
                        :current-image="$presenter->avatar ? $presenter->avatar_url : null"
                        :current-image-alt="$presenter->name . ' avatar'" />
                </div>

                {{-- Settings --}}
                <div
                    class="rounded-md border border-zinc-200 dark:border-white/10 bg-white dark:bg-white/5 p-6 shadow-sm">
                    <h2 class="text-lg font-bold text-gray-900 dark:text-white mb-4">Settings</h2>
                    <div class="space-y-4">
                        <x-ui.input name="sort_order" id="sort_order" label="Sort Order" type="number"
                            :value="old('sort_order', $presenter->sort_order)" min="0" hint="Lower numbers appear first"
                            :error="$errors->has('sort_order')" :errorMessage="$errors->first('sort_order')" />

                        <div>
                            <x-ui.toggle name="is_active" label="Active Status" :checked="old('is_active', $presenter->is_active)" />
                            <p class="text-xs text-zinc-500 dark:text-zinc-400 mt-2">Presenter will be visible on the
                                website</p>
                        </div>
                    </div>
                </div>

                {{-- Meta --}}
                <div
                    class="rounded-md border border-zinc-200 dark:border-white/10 bg-white dark:bg-white/5 p-6 shadow-sm">
                    <h2 class="text-lg font-bold text-gray-900 dark:text-white mb-4">Meta</h2>
                    <div class="space-y-3 text-sm">
                        <div class="flex justify-between py-2 border-b border-zinc-100 dark:border-white/5">
                            <span class="text-zinc-500 dark:text-zinc-400">Created</span>
                            <span
                                class="text-zinc-900 dark:text-white font-medium">{{ $presenter->created_at->format('d M Y, H:i') }}</span>
                        </div>
                        <div class="flex justify-between py-2 border-b border-zinc-100 dark:border-white/5">
                            <span class="text-zinc-500 dark:text-zinc-400">Last Updated</span>
                            <span
                                class="text-zinc-900 dark:text-white font-medium">{{ $presenter->updated_at->format('d M Y, H:i') }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</x-layouts.admin>