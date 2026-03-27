<x-layouts.admin title="Edit Article">
    <div x-data="{ showAiOverwriteModal: false }" @ai-generate-request.window="showAiOverwriteModal = true">
    {{-- Page Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-8">
        <div>
            <h1 class="mb-1 text-xl font-semibold text-zinc-900 dark:text-white">Edit Article</h1>
            <p class="text-[12.5px] text-zinc-600 dark:text-zinc-400">Update article information</p>
        </div>
        <div class="flex flex-wrap items-center gap-3">
            <a href="{{ route('admin.blog.show', $blog) }}"
                class="inline-flex items-center gap-2 rounded-md bg-white dark:bg-white/10 px-4 py-2 text-sm font-semibold text-gray-900 dark:text-white shadow-xs ring-1 ring-gray-300 ring-inset dark:ring-white/10 hover:bg-gray-50 dark:hover:bg-white/20 transition-all">
                <i class="fa-solid fa-eye text-blue-500"></i>
                View
            </a>
            <a href="{{ route('admin.blog.index') }}"
                class="inline-flex items-center gap-2 rounded-md bg-white dark:bg-white/10 px-4 py-2 text-sm font-semibold text-gray-900 dark:text-white shadow-xs ring-1 ring-gray-300 ring-inset dark:ring-white/10 hover:bg-gray-50 dark:hover:bg-white/20 transition-all">
                <i class="fa-solid fa-arrow-left"></i>
                Back to Articles
            </a>
        </div>
    </div>

    <form action="{{ route('admin.blog.update', $blog) }}" method="POST" enctype="multipart/form-data"
        id="blogForm"
        class="rounded-lg border border-zinc-200 bg-zinc-100/90 p-4 shadow-sm dark:border-zinc-700 dark:bg-zinc-900/50">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 gap-8 lg:grid-cols-3">
            {{-- Left Column - 2/3 --}}
            <div class="space-y-6 lg:col-span-2">
                <div>
                    <label for="title" class="mb-1 block text-xs font-medium text-zinc-600 dark:text-zinc-400">{{ __('Article title') }} <span class="text-red-500">*</span></label>
                    <input
                        type="text"
                        id="title"
                        name="title"
                        value="{{ old('title', $blog->title) }}"
                        required
                        class="w-full border-0 border-b border-zinc-200 bg-transparent px-0 py-2 text-2xl font-normal text-zinc-900 placeholder:text-zinc-400 focus:border-sky-600 focus:outline-none focus:ring-0 dark:border-zinc-600 dark:text-zinc-100 dark:placeholder:text-zinc-500 dark:focus:border-sky-500"
                        placeholder="{{ __('Add title') }}"
                    />
                    @error('title')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Post Details Section --}}
                <div
                    class="rounded-sm border border-zinc-300 bg-white p-6 shadow-sm dark:border-zinc-600 dark:bg-zinc-800/90">
                    <div class="mb-6">
                        <h2 class="border-b border-zinc-200 pb-2 text-[11px] font-semibold uppercase tracking-wide text-zinc-600 dark:border-zinc-600 dark:text-zinc-400">{{ __('Article details') }}</h2>
                    </div>

                    <div class="space-y-6">

                        {{-- Slug --}}
                        <x-ui.input id="slug" name="slug" label="URL Slug" :value="old('slug', $blog->slug)"
                            placeholder="url-friendly-slug" required
                            slug-from="title"
                            hint="URL-friendly version of the title. Auto-generated if left blank."
                            :error="$errors->has('slug')" :errorMessage="$errors->first('slug')" />

                        {{-- Short Description --}}
                        <x-ui.textarea id="short_body" name="short_body" label="Short Description" rows="3"
                            :value="old('short_body', $blog->short_body)" placeholder="Brief summary of the post..."
                            required :maxLength="150" :showCharacterCount="true"
                            hint="Summary length is important for SEO snippets." />

                        {{-- Main Content --}}
                        <div class="space-y-2">
                            <label class="block text-sm font-medium text-gray-900 dark:text-white">Main Content <span
                                    class="text-red-500">*</span></label>
                            <div class="prose-container">
                                <x-editor id="long_body" name="long_body" :value="$blog->long_body"
                                    placeholder="Write your amazing content here..." />
                            </div>
                            @error('long_body')<p class="mt-2 text-sm text-red-600">{{ $message }}</p>@enderror
                        </div>
                    </div>
                </div>

                {{-- SEO Settings Section --}}
                @php
                    $seoAssistConfig = [
                        'metaTitleHasValue' => filled(old('meta_title', $blog->meta_title)),
                        'metaDescHasValue' => filled(old('meta_description', $blog->meta_description)),
                    ];
                @endphp
                <div
                    class="overflow-hidden rounded-sm border border-zinc-300 bg-white shadow-sm dark:border-zinc-600 dark:bg-zinc-800/90"
                    x-data="seoAssistFromSummary({{ \Illuminate\Support\Js::from($seoAssistConfig) }})"
                    x-init="init()"
                >
                    <div class="border-b border-zinc-200 bg-zinc-100 px-3 py-2 text-[11px] font-semibold uppercase tracking-wide text-zinc-600 dark:border-zinc-600 dark:bg-zinc-800 dark:text-zinc-300">
                        {{ __('SEO') }}
                    </div>
                    <div class="p-6">
                    <div class="mb-4 flex flex-wrap items-start justify-between gap-2 border-b border-zinc-100 pb-3 dark:border-zinc-700">
                        <p class="max-w-xl text-[11px] leading-relaxed text-zinc-500 dark:text-zinc-400">
                            {{ __('Meta title follows your article title (trimmed ~60 characters). Meta description is built from the short description (~158 characters). Clear a field or use the button to re-apply.') }}
                        </p>
                        <button
                            type="button"
                            class="shrink-0 rounded border border-zinc-200 bg-white px-2.5 py-1 text-[11px] font-medium text-sky-700 shadow-sm hover:bg-zinc-50 dark:border-zinc-600 dark:bg-zinc-800 dark:text-sky-400 dark:hover:bg-zinc-700"
                            @click="syncFromSummary()"
                        >
                            {{ __('Sync SEO from title & summary') }}
                        </button>
                    </div>

                    @if(isset($seoRecommendations) && count($seoRecommendations) > 0)
                        <div
                            class="mb-6 p-4 bg-amber-50 dark:bg-amber-900/10 border border-amber-200 dark:border-amber-800 rounded-md">
                            <h3 class="text-sm font-bold text-amber-900 dark:text-amber-200 mb-2">
                                <i class="fa-solid fa-lightbulb mr-2"></i>SEO Recommendations
                            </h3>
                            <ul class="space-y-2 text-xs text-amber-800 dark:text-amber-300">
                                @foreach($seoRecommendations as $recommendation)
                                    <li class="flex items-start gap-2">
                                        <i class="fa-solid fa-circle-check mt-0.5 text-amber-500"></i>
                                        <span>{{ $recommendation['message'] }}</span>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <div class="space-y-6">
                        <x-ui.input id="meta_title" name="meta_title" :value="old('meta_title', $blog->meta_title)"
                            label="Meta Title" placeholder="Search engine title (50-60 chars recommended)" />

                        <x-ui.textarea id="meta_description" name="meta_description" :value="old('meta_description', $blog->meta_description)" label="Meta Description" rows="3"
                            placeholder="Brief description for search engine results (150-160 chars recommended)" />

                        <x-ui.tag-input id="meta_keywords" name="meta_keywords" :tags="array_filter(explode(',', old('meta_keywords', $blog->meta_keywords ?? '')))" label="Meta Keywords"
                            placeholder="Add keyword and press enter..."
                            hint="Important keywords that describe your content." />
                    </div>
                    </div>
                </div>
            </div>

            {{-- Right Column - 1/3 --}}
            <div class="space-y-4 lg:col-span-1">
                {{-- AI Content Generator Section --}}
                @if ($aiServiceConfigured ?? false)
                    <div
                        class="rounded-md border border-purple-200 dark:border-purple-500/20 bg-gradient-to-br from-purple-50 to-white dark:from-purple-900/10 dark:to-zinc-900/10 p-5 shadow-sm overflow-hidden">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-3">
                                <div
                                    class="flex h-10 w-10 items-center justify-center rounded-md bg-purple-600 text-white shadow-sm">
                                    <i class="fa-solid fa-wand-magic-sparkles"></i>
                                </div>
                                <h2 class="text-base font-semibold text-gray-900 dark:text-white">AI Content</h2>
                            </div>
                            <x-ui.button type="button" x-data="{ open: false }"
                                @click="open = !open; $dispatch('toggle-ai-panel')" variant="default" size="sm"
                                class="!px-2 !py-1">
                                <span x-text="open ? 'Hide' : 'Open'">Open</span>
                                <i class="fa-solid" :class="open ? 'fa-chevron-up' : 'fa-chevron-down' ? 'ml-1' : ''"></i>
                            </x-ui.button>
                        </div>

                        <div id="aiGeneratorPanel"
                            class="hidden mt-6 space-y-4 pt-6 border-t border-purple-200 dark:border-purple-500/20">
                            <div
                                class="p-3 rounded-md bg-amber-50 dark:bg-amber-900/20 border border-amber-200 dark:border-amber-800 text-xs text-amber-700 dark:text-amber-300">
                                <i class="fa-solid fa-triangle-exclamation mr-2"></i>
                                <strong>Note:</strong> Re-generating content will overwrite your current edits.
                            </div>

                            <x-ui.input id="ai_topic" label="Topic" required :value="$blog->title"
                                placeholder="Topic or idea..." />

                            <x-ui.input id="ai_keywords" label="Target Keywords" :value="$blog->meta_keywords ?? ''"
                                placeholder="Keywords..." />

                            <div class="grid grid-cols-2 gap-3">
                                <div>
                                    <x-ui.select id="ai_tone" label="Tone" size="sm">
                                        <option value="professional">Professional</option>
                                        <option value="casual">Casual</option>
                                        <option value="expert">Expert</option>
                                        <option value="persuasive">Persuasive</option>
                                        <option value="neutral">Neutral</option>
                                    </x-ui.select>
                                </div>

                                <div>
                                    <x-ui.select id="ai_length" label="Length" size="sm">
                                        <option value="short">Short</option>
                                        <option value="medium" selected>Medium</option>
                                        <option value="long">Long</option>
                                    </x-ui.select>
                                </div>
                            </div>

                            <x-ui.button type="button" onclick="generateWithAI()" id="aiGenerateBtn" variant="primary"
                                color="purple" class="w-full mt-2" icon="wand-magic-sparkles">
                                <span id="aiGenerateBtnText">Regenerate Now</span>
                            </x-ui.button>

                            <div id="aiErrorMessage"
                                class="hidden p-3 rounded-md bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 text-xs text-red-700 dark:text-red-300">
                                <span id="aiErrorText"></span>
                            </div>
                        </div>
                    </div>
                @endif

                {{-- SEO Score Card --}}
                @if(isset($blog->seo_status) && $blog->seo_status)
                    <div
                        class="overflow-hidden rounded-sm border border-zinc-300 bg-white shadow-sm dark:border-zinc-600 dark:bg-zinc-800/90">
                        <div class="border-b border-zinc-200 bg-zinc-100 px-3 py-2 text-[11px] font-semibold uppercase tracking-wide text-zinc-600 dark:border-zinc-600 dark:bg-zinc-800 dark:text-zinc-300">
                            {{ __('SEO health') }}
                        </div>
                        <div class="p-6">
                        <div class="mb-4">
                            <h2 class="sr-only">{{ __('SEO health') }}</h2>
                            <div class="flex flex-col items-center justify-center py-4">
                                <div
                                    class="relative inline-flex items-center justify-center p-1 rounded-full border-4 {{ $blog->seo_score > 80 ? 'border-green-500' : ($blog->seo_score > 50 ? 'border-amber-500' : 'border-red-500') }} mb-4">
                                    <div
                                        class="w-16 h-16 rounded-full flex items-center justify-center bg-gray-50 dark:bg-zinc-800">
                                        <span
                                            class="text-xl font-bold {{ $blog->seo_score > 80 ? 'text-green-600' : ($blog->seo_score > 50 ? 'text-amber-600' : 'text-red-600') }}">
                                            {{ $blog->seo_score ?? '0' }}
                                        </span>
                                    </div>
                                </div>
                                <p class="text-sm font-bold text-gray-900 dark:text-white capitalize">
                                    {{ str_replace('-', ' ', $blog->seo_status) }}
                                </p>
                            </div>
                        </div>
                        <button type="button" onclick="analyzeSEO({{ $blog->id }})" id="analyzeBtn"
                            class="w-full flex items-center justify-center gap-2 rounded-md bg-zinc-100 dark:bg-zinc-800 px-4 py-2 text-sm font-bold text-gray-900 dark:text-white hover:bg-zinc-200 dark:hover:bg-zinc-700 transition-all">
                            <i class="fa-solid fa-arrows-rotate" id="analyzeIcon"></i>
                            Re-analyze SEO
                        </button>
                        </div>
                    </div>
                @endif

                {{-- Publishing Settings Section --}}
                <div
                    class="overflow-hidden rounded-sm border border-zinc-300 bg-white shadow-sm dark:border-zinc-600 dark:bg-zinc-800/90">
                    <div class="border-b border-zinc-200 bg-zinc-100 px-3 py-2 text-[11px] font-semibold uppercase tracking-wide text-zinc-600 dark:border-zinc-600 dark:bg-zinc-800 dark:text-zinc-300">
                        {{ __('Publish') }}
                    </div>

                    <div class="space-y-6 p-4">
                        {{-- Blog Category --}}
                        <x-ui.select id="blog_category_id" name="blog_category_id" label="Category" required
                            placeholder="Select a category" :value="old('blog_category_id', $blog->blog_category_id)">
                            @foreach ($blogCategories as $category)
                                <option value="{{ $category->id }}" {{ old('blog_category_id', $blog->blog_category_id) == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </x-ui.select>

                        {{-- Blog Type --}}
                        <x-ui.select id="blog_type_id" name="blog_type_id" label="Blog Type"
                            placeholder="Select a type (optional)" :value="old('blog_type_id', $blog->blog_type_id)">
                            <option value="">— None —</option>
                            @foreach ($blogTypes as $type)
                                <option value="{{ $type->id }}" {{ old('blog_type_id', $blog->blog_type_id) == $type->id ? 'selected' : '' }}>{{ $type->name }}</option>
                            @endforeach
                        </x-ui.select>

                        {{-- Author --}}
                        <x-ui.select id="author_id" name="author_id" label="Author" required
                            placeholder="Select an author" :value="old('author_id', $blog->author_id)">
                            @foreach ($authors as $author)
                                <option value="{{ $author->id }}" {{ old('author_id', $blog->author_id) == $author->id ? 'selected' : '' }}>
                                    {{ $author->name }}
                                </option>
                            @endforeach
                        </x-ui.select>

                        {{-- Featured Status --}}
                        <div
                            class="flex items-center justify-between py-4 border-y border-gray-100 dark:border-white/5">
                            <div>
                                <label class="block text-sm font-medium text-gray-900 dark:text-white">Featured
                                    article</label>
                                <p class="text-xs text-gray-500 dark:text-gray-400">Show on homepage</p>
                            </div>
                            <x-ui.toggle name="is_featured" :checked="old('is_featured', $blog->is_featured) == 1" />
                        </div>

                        {{-- Status Toggle --}}
                        <div class="flex items-center justify-between">
                            <div>
                                <label class="block text-sm font-medium text-gray-900 dark:text-white">Publication
                                    status</label>
                                <p class="text-xs text-gray-500 dark:text-gray-400">Active and visible to public</p>
                            </div>
                            <x-ui.toggle name="is_active" :checked="old('is_active', $blog->is_active) == 1" />
                        </div>
                    </div>
                </div>

                {{-- Featured Image Section --}}
                <div
                    class="overflow-hidden rounded-sm border border-zinc-300 bg-white shadow-sm dark:border-zinc-600 dark:bg-zinc-800/90">
                    <div class="border-b border-zinc-200 bg-zinc-100 px-3 py-2 text-[11px] font-semibold uppercase tracking-wide text-zinc-600 dark:border-zinc-600 dark:bg-zinc-800 dark:text-zinc-300">
                        {{ __('Featured image') }}
                    </div>

                    <div class="space-y-4 p-4">
                        <x-image-upload id="image" name="image" label="" :required="false"
                            help-text="Optimal: 1200x630px (Max 20MB)" :max-size="20480"
                            :current-image="$blog->image ? Storage::disk('public')->url($blog->image) : null"
                            :current-image-alt="$blog->title" />
                    </div>
                </div>

                {{-- Form Actions --}}
                <div class="sticky top-24 space-y-4">
                    <x-ui.button type="submit" name="submit_action" value="index" variant="primary"
                        class="w-full !py-3 font-bold" icon="save">
                        Save & close
                    </x-ui.button>

                    <x-ui.button type="submit" name="submit_action" value="edit" variant="default"
                        class="w-full !py-3 font-bold" icon="save">
                        Save
                    </x-ui.button>

                    <a href="{{ route('admin.blog.index') }}"
                        class="block w-full text-center text-sm font-semibold text-gray-500 dark:text-zinc-400 hover:text-gray-700 dark:hover:text-zinc-200 transition-all">
                        Cancel Changes
                    </a>
                </div>
            </div>
        </div>
    </form>

    <x-ui.modal alpine-show="showAiOverwriteModal" modal-id="ai-overwrite-modal" size="sm">
        <x-slot:title>Replace existing content?</x-slot:title>
        <p class="text-[13px] text-zinc-600 dark:text-zinc-400">Generated text will replace the current title, summary, and main content.</p>
        <x-slot:footer>
            <x-ui.button variant="secondary" type="button" @click="showAiOverwriteModal = false">Cancel</x-ui.button>
            <x-ui.button variant="primary" color="red" type="button" @click="showAiOverwriteModal = false; window.__executeAiGenerate && window.__executeAiGenerate();">Continue</x-ui.button>
        </x-slot:footer>
    </x-ui.modal>
    </div>

    @push('scripts')
        <script>
            // AI Panel Toggle Logic
            window.addEventListener('toggle-ai-panel', () => {
                const panel = document.getElementById('aiGeneratorPanel');
                panel.classList.toggle('hidden');
            });

            function analyzeSEO(blogId) {
                const btn = document.getElementById('analyzeBtn');
                const icon = document.getElementById('analyzeIcon');
                if (!btn || !icon) return;

                btn.disabled = true;
                icon.classList.add('fa-spin');

                fetch(`/admin/content/blog/${blogId}/analyze-seo`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Content-Type': 'application/json',
                    },
                })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            location.reload();
                        } else if (typeof toastManager !== 'undefined') {
                            toastManager.show('error', 'Failed to analyze SEO: ' + (data.error || 'Unknown error'));
                        }
                    })
                    .catch(error => {
                        if (typeof toastManager !== 'undefined') {
                            toastManager.show('error', 'Error analyzing SEO: ' + error.message);
                        }
                    })
                    .finally(() => {
                        btn.disabled = false;
                        icon.classList.remove('fa-spin');
                    });
            }

            function generateWithAI() {
                const topic = document.getElementById('ai_topic').value.trim();
                const keywords = document.getElementById('ai_keywords').value.trim();
                const tone = document.getElementById('ai_tone').value;
                const length = document.getElementById('ai_length').value;

                const btn = document.getElementById('aiGenerateBtn');
                const btnText = document.getElementById('aiGenerateBtnText');
                const btnIcon = document.getElementById('aiGenerateIcon');
                const errorDiv = document.getElementById('aiErrorMessage');
                const errorText = document.getElementById('aiErrorText');

                if (!topic) {
                    errorDiv.classList.remove('hidden');
                    errorText.textContent = 'Please enter a topic or title idea.';
                    return;
                }

                window.__executeAiGenerate = function() {
                    errorDiv.classList.add('hidden');
                    btn.disabled = true;
                    btnText.textContent = 'Generating...';
                    btnIcon.classList.remove('fa-wand-magic-sparkles');
                    btnIcon.classList.add('fa-spinner', 'fa-spin');

                    fetch('{{ route('admin.blog.generate-with-ai') }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Accept': 'application/json',
                        },
                        body: JSON.stringify({ topic, keywords, tone, length }),
                    })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                if (data.data.title) {
                                    document.getElementById('title').value = data.data.title;
                                    document.getElementById('title').dispatchEvent(new Event('input'));
                                }
                                if (data.data.short_body) {
                                    document.getElementById('short_body').value = data.data.short_body.substring(0, 150);
                                    document.getElementById('short_body').dispatchEvent(new Event('input'));
                                }
                                if (data.data.long_body) {
                                    const longBodyInput = document.getElementById('input-long_body');
                                    if (longBodyInput) {
                                        longBodyInput.value = data.data.long_body;
                                        longBodyInput.dispatchEvent(new Event('input', { bubbles: true }));
                                        const editorWrapper = longBodyInput.closest('[x-data]');
                                        if (editorWrapper && editorWrapper._x_dataStack) {
                                            const alpineData = editorWrapper._x_dataStack[0];
                                            if (alpineData && alpineData.setHTML) {
                                                alpineData.setHTML(data.data.long_body);
                                            }
                                        }
                                    }
                                }
                                if (data.data.meta_title) document.getElementById('meta_title').value = data.data.meta_title;
                                if (data.data.meta_description) document.getElementById('meta_description').value = data.data.meta_description;

                                if (data.data.meta_keywords) {
                                    const tagInputContainer = document.getElementById('meta_keywords').closest('[x-data]');
                                    if (tagInputContainer && tagInputContainer._x_dataStack) {
                                        const tagData = tagInputContainer._x_dataStack[0];
                                        const keywordsArray = data.data.meta_keywords.split(',').map(k => k.trim()).filter(k => k);
                                        tagData.tags = keywordsArray;
                                    }
                                }

                                if (typeof toastManager !== 'undefined') {
                                    toastManager.show('success', 'Content generated successfully.');
                                }
                            } else {
                                errorDiv.classList.remove('hidden');
                                errorText.textContent = data.error || 'Failed to generate content.';
                            }
                        })
                        .catch(() => {
                            errorDiv.classList.remove('hidden');
                            errorText.textContent = 'An error occurred during generation.';
                        })
                        .finally(() => {
                            btn.disabled = false;
                            btnText.textContent = 'Regenerate';
                            btnIcon.classList.remove('fa-spinner', 'fa-spin');
                            btnIcon.classList.add('fa-wand-magic-sparkles');
                            window.__executeAiGenerate = null;
                        });
                };

                window.dispatchEvent(new CustomEvent('ai-generate-request'));
            }
        </script>
    @endpush
</x-layouts.admin>