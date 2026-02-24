<x-layouts.admin title="Create Solution">
    {{-- Page Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-8">
        <div>
            <h1 class="text-3xl font-bold text-zinc-900 dark:text-white mb-2">Create Solution</h1>
            <p class="text-zinc-600 dark:text-zinc-400">Add a new solution section to your homepage</p>
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ route('admin.solution.index') }}" class="inline-flex items-center gap-2 rounded-md bg-white dark:bg-white/10 px-4 py-2 text-sm font-semibold text-gray-900 dark:text-white shadow-xs ring-1 ring-gray-300 ring-inset dark:ring-white/10 hover:bg-gray-50 dark:hover:bg-white/20">
                <i class="fa-solid fa-arrow-left"></i>
                Back to Solutions
            </a>
        </div>
    </div>

    <form action="{{ route('admin.solution.store') }}" method="POST" enctype="multipart/form-data" id="solution-form">
        @csrf

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
                {{-- Solution Details Section --}}
                <div class="rounded-lg border border-gray-200 dark:border-white/10 bg-white dark:bg-white/5 p-6">
                    <div class="mb-6">
                        <h2 class="text-base/7 font-semibold text-gray-900 dark:text-white">Solution Details</h2>
                        <p class="mt-1 text-sm/6 text-gray-600 dark:text-gray-400">Basic information about your solution including anchor, title, and content.</p>
                    </div>

                    <div class="space-y-6">
                        {{-- Anchor & Nav Title --}}
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <x-ui.input
                                    id="anchor"
                                    name="anchor"
                                    :value="old('anchor')"
                                    label="Anchor ID"
                                    hint="Used for #anchor navigation (lowercase, no spaces)"
                                    placeholder="e.g., crm, invoices"
                                    required
                                />
                                @error('anchor')<p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>@enderror
                            </div>

                            <div>
                                <x-ui.input
                                    id="nav_title"
                                    name="nav_title"
                                    :value="old('nav_title')"
                                    label="Navigation Title"
                                    hint="Shown in top navigation menu"
                                    placeholder="e.g., CRM, Invoices"
                                    required
                                />
                                @error('nav_title')<p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>@enderror
                            </div>
                        </div>

                        {{-- Title --}}
                        <div>
                            <x-ui.input
                                id="title"
                                name="title"
                                :value="old('title')"
                                label="Title"
                                placeholder="Main heading for this section"
                                :required="true"
                                :error="$errors->has('title')"
                                :errorMessage="$errors->first('title')"
                            />
                        </div>

                        {{-- Slug --}}
                        <div>
                            <x-ui.input
                                id="slug"
                                name="slug"
                                :value="old('slug')"
                                slug-from="title"
                                label="Slug"
                                hint="URL-friendly version. Auto-generated from title if left blank."
                                placeholder="url-friendly-slug"
                            />
                        </div>

                        {{-- Link Text --}}
                        <div>
                            <x-ui.input
                                id="link_text"
                                name="link_text"
                                :value="old('link_text')"
                                label="Link Text"
                                hint="Link URL will be automatically generated from the anchor field"
                                placeholder="e.g., More about CRM →"
                            />
                            @error('link_text')<p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>@enderror
                        </div>

                        {{-- Subtitle --}}
                        <div>
                            <x-ui.textarea
                                id="subtitle"
                                name="subtitle"
                                :value="old('subtitle')"
                                label="Subtitle"
                                placeholder="Optional description text"
                                :rows="3"
                                :error="$errors->has('subtitle')"
                                :errorMessage="$errors->first('subtitle')"
                            />
                        </div>

                        {{-- Short Body --}}
                        <div>
                            <x-ui.textarea
                                id="short_body"
                                name="short_body"
                                :value="old('short_body')"
                                label="Short Body"
                                placeholder="Brief content summary or introduction"
                                :rows="4"
                                :error="$errors->has('short_body')"
                                :errorMessage="$errors->first('short_body')"
                            />
                        </div>

                        {{-- Long Body --}}
                        <div>
                            <label for="long_body" class="block text-sm/6 font-medium text-gray-900 dark:text-white">Long Body</label>
                            <div class="mt-2">
                                <x-editor 
                                    id="long_body" 
                                    name="long_body" 
                                    :value="old('long_body', '')"
                                    placeholder="Detailed content description"
                                />
                            </div>
                            @error('long_body')<p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>@enderror
                        </div>

                        {{-- Key Features --}}
                        <div>
                            <label class="block text-sm/6 font-medium text-gray-900 dark:text-white mb-2">Key Features</label>
                            <div id="list-items-container" class="space-y-2">
                                <div class="flex gap-2">
                                    <input type="text"
                                           name="list_items[0]"
                                           placeholder="Enter key feature"
                                           class="flex-1 block rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline-2 focus:-outline-offset-2 focus:outline-[var(--color-accent)] sm:text-sm/6 dark:bg-white/5 dark:text-white dark:outline-white/10 dark:placeholder:text-gray-500 dark:focus:outline-[var(--color-accent)]">
                                    <button type="button" onclick="removeListItem(this)" class="inline-flex items-center justify-center rounded-md bg-red-600 px-3 py-1.5 text-sm font-semibold text-white shadow-xs hover:bg-red-500 dark:bg-red-600 dark:hover:bg-red-500">
                                        <i class="fa-solid fa-trash"></i>
                                    </button>
                                </div>
                            </div>
                            <button type="button" onclick="addListItem()" class="mt-3 inline-flex items-center gap-2 rounded-md bg-[var(--color-accent)] px-4 py-2 text-sm font-semibold text-white shadow-xs hover:opacity-90">
                                <i class="fa-solid fa-plus"></i>
                                Add Key Feature
                            </button>
                        </div>
                    </div>
                </div>

                {{-- Testimonial Section --}}
                <div class="rounded-lg border border-gray-200 dark:border-white/10 bg-white dark:bg-white/5 p-6">
                    <div class="mb-6">
                        <h2 class="text-base/7 font-semibold text-gray-900 dark:text-white">Testimonial</h2>
                        <p class="mt-1 text-sm/6 text-gray-600 dark:text-gray-400">Optional customer testimonial for this solution.</p>
                    </div>

                    <div class="space-y-6">
                        <div>
                            <x-ui.textarea
                                id="testimonial_quote"
                                name="testimonial_quote"
                                :value="old('testimonial_quote')"
                                label="Quote"
                                placeholder="Customer testimonial quote"
                                :rows="3"
                            />
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <x-ui.input
                                    id="testimonial_author"
                                    name="testimonial_author"
                                    :value="old('testimonial_author')"
                                    label="Author Name"
                                    placeholder="e.g., M. Demir"
                                />
                            </div>
                            <div>
                                <x-ui.input
                                    id="testimonial_company"
                                    name="testimonial_company"
                                    :value="old('testimonial_company')"
                                    label="Company"
                                    placeholder="e.g., SAM Online Marketing"
                                />
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Associated Features Section --}}
                <div class="rounded-lg border border-gray-200 dark:border-white/10 bg-white dark:bg-white/5 p-6">
                    <div class="mb-6">
                        <h2 class="text-base/7 font-semibold text-gray-900 dark:text-white">Associated Features</h2>
                        <p class="mt-1 text-sm/6 text-gray-600 dark:text-gray-400">Select features that belong to this solution</p>
                    </div>

                    <div>
                        @if($features->count() > 0)
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                @foreach($features as $feature)
                                <div class="flex items-start gap-3 p-3 rounded-lg border border-gray-200 dark:border-white/10 bg-gray-50 dark:bg-white/5 hover:bg-gray-100 dark:hover:bg-white/10 transition-colors">
                                    <div class="mt-1">
                                        <x-ui.checkbox
                                            name="features[]"
                                            value="{{ $feature->id }}"
                                            id="feature_{{ $feature->id }}"
                                            :checked="in_array($feature->id, old('features', []))"
                                            label=""
                                            color="primary"
                                        />
                                    </div>
                                    <div class="flex-1">
                                        <label for="feature_{{ $feature->id }}" class="block cursor-pointer">
                                            <span class="text-sm font-medium text-gray-900 dark:text-white">{{ $feature->title }}</span>
                                            @if($feature->description)
                                                <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">{{ Str::limit($feature->description, 80) }}</p>
                                            @endif
                                        </label>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-8">
                                <div class="text-gray-400 dark:text-gray-500 mb-2">
                                    <i class="fas fa-puzzle-piece text-3xl"></i>
                                </div>
                                <p class="text-sm text-gray-500 dark:text-gray-400">No features available</p>
                                <p class="text-xs text-gray-400 dark:text-gray-500 mt-1">Create features first to associate them with solutions</p>
                            </div>
                        @endif
                        @error('features')<p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>@enderror
                    </div>
                </div>

                {{-- SEO Settings Section --}}
                <div class="rounded-lg border border-gray-200 dark:border-white/10 bg-white dark:bg-white/5 p-6">
                    <div class="mb-6">
                        <h2 class="text-base/7 font-semibold text-gray-900 dark:text-white">SEO Settings</h2>
                        <p class="mt-1 text-sm/6 text-gray-600 dark:text-gray-400">Optimize for search engines.</p>
                    </div>

                    <div class="space-y-6">
                        @php
                            $metaKeywordsTags = array_values(array_filter(array_map('trim', explode(',', (string) old('meta_keywords', '')))));
                        @endphp

                        {{-- Meta Title --}}
                        <div>
                            <x-ui.input
                                id="meta_title"
                                name="meta_title"
                                :value="old('meta_title')"
                                label="Meta Title"
                                placeholder="SEO title for search engines (max 60 characters)"
                                hint="Maximum 60 characters for optimal SEO."
                                :error="$errors->has('meta_title')"
                                :errorMessage="$errors->first('meta_title')"
                            />
                        </div>

                        {{-- Meta Description --}}
                        <div>
                            <x-ui.textarea
                                id="meta_description"
                                name="meta_description"
                                :value="old('meta_description')"
                                label="Meta Description"
                                placeholder="Brief description for search engines (max 160 characters)"
                                hint="Maximum 160 characters for optimal SEO."
                                :rows="3"
                                :maxLength="160"
                                :showCharacterCount="true"
                                :error="$errors->has('meta_description')"
                                :errorMessage="$errors->first('meta_description')"
                            />
                        </div>

                        {{-- Meta Keywords --}}
                        <div>
                            <x-ui.tag-input
                                id="meta_keywords"
                                name="meta_keywords"
                                :tags="$metaKeywordsTags"
                                label="Meta Keywords"
                                placeholder="Add keyword and press Enter"
                                hint="Comma-separated keywords for SEO."
                                :error="$errors->has('meta_keywords')"
                                :errorMessage="$errors->first('meta_keywords')"
                            />
                        </div>
                    </div>
                </div>
            </div>

            {{-- Right Column - 1/3 --}}
            <div class="lg:col-span-1 space-y-8">
                {{-- Featured Image Section --}}
                <div class="rounded-lg border border-gray-200 dark:border-white/10 bg-white dark:bg-white/5 p-6">
                    <div class="mb-6">
                        <h2 class="text-base/7 font-semibold text-gray-900 dark:text-white">Featured Image</h2>
                        <p class="mt-1 text-sm/6 text-gray-600 dark:text-gray-400">Upload a featured image for your solution.</p>
                    </div>

                    <x-image-upload 
                        id="image"
                        name="image"
                        label=""
                        :required="false"
                        help-text="PNG, JPG, GIF, WEBP, SVG up to 2MB"
                        :max-size="2048"
                        current-image-alt="Solution image"
                    />
                </div>

                {{-- Settings Section --}}
                <div class="rounded-lg border border-gray-200 dark:border-white/10 bg-white dark:bg-white/5 p-6">
                    <div class="mb-6">
                        <h2 class="text-base/7 font-semibold text-gray-900 dark:text-white">Settings</h2>
                        <p class="mt-1 text-sm/6 text-gray-600 dark:text-gray-400">Configure display options.</p>
                    </div>

                    <div class="space-y-6">
                        {{-- Image Position --}}
                        <div>
                            <label class="block text-sm/6 font-medium text-gray-900 dark:text-white mb-2">
                                Image Position <span class="text-red-500">*</span>
                            </label>
                            <div class="flex gap-4">
                                <x-ui.radio
                                    name="image_position"
                                    value="left"
                                    :checked="old('image_position', 'left') === 'left'"
                                    label="Left"
                                    color="primary"
                                />
                                <x-ui.radio
                                    name="image_position"
                                    value="right"
                                    :checked="old('image_position') === 'right'"
                                    label="Right"
                                    color="primary"
                                />
                            </div>
                        </div>

                        {{-- Sort Order --}}
                        <div>
                            <x-ui.input
                                id="sort_order"
                                name="sort_order"
                                type="number"
                                :value="old('sort_order', 0)"
                                label="Sort Order"
                                hint="Display order (lower numbers appear first)"
                            />
                        </div>

                        {{-- Active Status --}}
                        <div class="flex items-center justify-between">
                            <div>
                                <label class="block text-sm/6 font-medium text-gray-900 dark:text-white">Status</label>
                                <p class="text-sm/6 text-gray-600 dark:text-gray-400">Make solution visible</p>
                            </div>
                            <div>
                                <input type="hidden" name="is_active" value="0">
                                <x-ui.toggle 
                                    name="is_active"
                                    :checked="old('is_active', true)"
                                />
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>

        {{-- Form Actions --}}
        <div class="mt-8 flex flex-col sm:flex-row items-center justify-end gap-4 border-t border-gray-200 dark:border-white/10 pt-6">
            <a href="{{ route('admin.solution.index') }}" class="text-sm/6 font-semibold text-gray-900 dark:text-white hover:text-gray-600 dark:hover:text-gray-300">Cancel</a>
            <button type="submit" name="action" value="save" class="inline-flex items-center gap-2 rounded-md bg-[var(--color-accent)] px-4 py-2 text-sm font-semibold text-white shadow-xs hover:opacity-90 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-[var(--color-accent)]">
                <i class="fa-solid fa-check"></i>
                Save
            </button>
            <button type="submit" name="action" value="save_and_stay" class="inline-flex items-center gap-2 rounded-md bg-gray-600 px-4 py-2 text-sm font-semibold text-white shadow-xs hover:bg-gray-500 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-gray-600">
                <i class="fa-solid fa-floppy-disk"></i>
                Save & Continue Editing
            </button>
        </div>
    </form>

    <script>
    let listItemIndex = 1;

    function addListItem() {
        const container = document.getElementById('list-items-container');
        const listItemHtml = `
            <div class="flex gap-2">
                <input type="text"
                       name="list_items[${listItemIndex}]"
                       placeholder="Enter key feature"
                       class="flex-1 block rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline-2 focus:-outline-offset-2 focus:outline-[var(--color-accent)] sm:text-sm/6 dark:bg-white/5 dark:text-white dark:outline-white/10 dark:placeholder:text-gray-500 dark:focus:outline-[var(--color-accent)]">
                <button type="button" onclick="removeListItem(this)" class="inline-flex items-center justify-center rounded-md bg-red-600 px-3 py-1.5 text-sm font-semibold text-white shadow-xs hover:bg-red-500 dark:bg-red-600 dark:hover:bg-red-500">
                    <i class="fa-solid fa-trash"></i>
                </button>
            </div>
        `;
        container.insertAdjacentHTML('beforeend', listItemHtml);
        listItemIndex++;
    }

    function removeListItem(button) {
        button.parentElement.remove();
    }

    </script>
</x-layouts.admin>