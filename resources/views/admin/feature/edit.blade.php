<x-layouts.admin title="Edit Feature">
    {{-- Page Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-8">
        <div>
            <h1 class="text-3xl font-bold text-zinc-900 dark:text-white mb-2">Edit Feature</h1>
            <p class="text-zinc-600 dark:text-zinc-400">Edit feature: {{ $feature->title }}</p>
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ route('admin.content.feature.index') }}" class="inline-flex items-center gap-2 rounded-md bg-white dark:bg-white/10 px-4 py-2 text-sm font-semibold text-gray-900 dark:text-white shadow-xs ring-1 ring-gray-300 ring-inset dark:ring-white/10 hover:bg-gray-50 dark:hover:bg-white/20">
                <i class="fa-solid fa-arrow-left"></i>
                Back to Features
            </a>
        </div>
    </div>

    <form action="{{ route('admin.content.feature.update', $feature) }}" method="POST">
        @csrf
        @method('PUT')

        @if($errors->any())
            <div class="mb-6">
                <x-ui.alert variant="error" icon="circle-exclamation" :title="__('admin.form_fix_errors_title')">
                    <ul class="list-disc list-inside space-y-1">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </x-ui.alert>
            </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            {{-- Left Column - 2/3 --}}
            <div class="lg:col-span-2 space-y-8">
                {{-- Basic Information Section --}}
                <div class="rounded-lg border border-gray-200 dark:border-white/10 bg-white dark:bg-white/5 p-6">
                    <div class="mb-6">
                        <h2 class="text-base/7 font-semibold text-gray-900 dark:text-white">Basic Information</h2>
                        <p class="mt-1 text-sm/6 text-gray-600 dark:text-gray-400">Enter the basic details for your feature.</p>
                    </div>

                    <div class="space-y-6">
                        {{-- Title --}}
                        <div>
                            <x-ui.input
                                id="title"
                                name="title"
                                :value="old('title', $feature->title)"
                                label="Title"
                                placeholder="Feature title"
                                required
                                :error="$errors->has('title')"
                                :errorMessage="$errors->first('title')"
                            />
                        </div>

                        {{-- Icon --}}
                        <div>
                            <x-icon-picker
                                id="icon"
                                name="icon"
                                :value="old('icon', $feature->icon)"
                                label="Icon"
                                help-text="FontAwesome icon class (e.g., fa-solid fa-star)"
                                :required="false"
                            />
                            @error('icon')<p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>@enderror
                        </div>

                        {{-- Description --}}
                        <div>
                            <x-ui.textarea
                                id="description"
                                name="description"
                                :value="old('description', $feature->description)"
                                label="Description"
                                placeholder="Feature description"
                                rows="4"
                                :error="$errors->has('description')"
                                :errorMessage="$errors->first('description')"
                            />
                        </div>
                    </div>
                </div>

                {{-- Associated Modules Section --}}
                <div class="rounded-lg border border-gray-200 dark:border-white/10 bg-white dark:bg-white/5 p-6">
                    <div class="mb-6">
                        <h2 class="text-base/7 font-semibold text-gray-900 dark:text-white">Associated Modules</h2>
                        <p class="mt-1 text-sm/6 text-gray-600 dark:text-gray-400">Select modules that this feature belongs to</p>
                    </div>

                    <div>
                        @if($modules->count() > 0)
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                @foreach($modules as $module)
                                <div class="flex items-start gap-3 p-3 rounded-lg border border-gray-200 dark:border-white/10 bg-gray-50 dark:bg-white/5 hover:bg-gray-100 dark:hover:bg-white/10 transition-colors">
                                    <div class="mt-1">
                                        <x-ui.checkbox
                                            name="modules[]"
                                            value="{{ $module->id }}"
                                            id="module_{{ $module->id }}"
                                            :checked="in_array($module->id, old('modules', $feature->modules->pluck('id')->toArray()))"
                                            label=""
                                            color="primary"
                                        />
                                    </div>
                                    <div class="flex-1">
                                        <label for="module_{{ $module->id }}" class="block cursor-pointer">
                                            <span class="text-sm font-medium text-gray-900 dark:text-white">{{ $module->title }}</span>
                                        </label>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-8">
                                <div class="text-gray-400 dark:text-gray-500 mb-2">
                                    <i class="fas fa-cube text-3xl"></i>
                                </div>
                                <p class="text-sm text-gray-500 dark:text-gray-400">No modules available</p>
                                <p class="text-xs text-gray-400 dark:text-gray-500 mt-1">
                                    <a href="{{ route('admin.content.module.create') }}" class="text-[var(--color-accent)] hover:underline">Create one first</a>
                                </p>
                            </div>
                        @endif
                        @error('modules')<p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>@enderror
                    </div>
                </div>
            </div>

            {{-- Right Column - 1/3 --}}
            <div class="lg:col-span-1 space-y-8">
                {{-- Settings Section --}}
                <div class="rounded-lg border border-gray-200 dark:border-white/10 bg-white dark:bg-white/5 p-6">
                    <div class="mb-6">
                        <h2 class="text-base/7 font-semibold text-gray-900 dark:text-white">Settings</h2>
                        <p class="mt-1 text-sm/6 text-gray-600 dark:text-gray-400">Configure feature settings.</p>
                    </div>

                    <div class="space-y-6">
                        {{-- Active Status --}}
                        <div class="flex items-center justify-between">
                            <div>
                                <label class="block text-sm/6 font-medium text-gray-900 dark:text-white">Status</label>
                                <p class="text-sm/6 text-gray-600 dark:text-gray-400">Make feature visible</p>
                            </div>
                            <div>
                                <input type="hidden" name="is_active" value="0">
                                <x-ui.toggle 
                                    name="is_active"
                                    :checked="old('is_active', $feature->is_active)"
                                />
                            </div>
                        </div>

                        {{-- Sort Order --}}
                        <div>
                            <x-ui.input
                                id="sort_order"
                                name="sort_order"
                                type="number"
                                :value="old('sort_order', $feature->sort_order)"
                                label="Sort Order"
                                hint="Display order (lower numbers appear first)"
                                min="0"
                                :error="$errors->has('sort_order')"
                                :errorMessage="$errors->first('sort_order')"
                            />
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Form Actions --}}
        <div class="mt-8 flex flex-col sm:flex-row items-center justify-end gap-4 border-t border-gray-200 dark:border-white/10 pt-6">
            <a href="{{ route('admin.content.feature.index') }}" class="text-sm/6 font-semibold text-gray-900 dark:text-white hover:text-gray-600 dark:hover:text-gray-300">Cancel</a>
            <button type="submit" name="action" value="save" class="inline-flex items-center gap-2 rounded-md bg-[var(--color-accent)] px-4 py-2 text-sm font-semibold text-white shadow-xs hover:opacity-90 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-[var(--color-accent)]">
                <i class="fa-solid fa-check"></i>
                Update Feature
            </button>
            <button type="submit" name="action" value="save_and_stay" class="inline-flex items-center gap-2 rounded-md bg-gray-600 px-4 py-2 text-sm font-semibold text-white shadow-xs hover:bg-gray-500 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-gray-600">
                <i class="fa-solid fa-floppy-disk"></i>
                Update & Continue Editing
            </button>
        </div>
    </form>

</x-layouts.admin>

