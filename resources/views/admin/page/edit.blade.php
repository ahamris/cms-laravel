<x-layouts.admin title="Edit Page">
    <div class="space-y-6">
        <div class="flex items-center justify-between mb-6">
            <div class="flex flex-col gap-1">
                <h1 class="text-2xl font-bold text-gray-900">Edit Page</h1>
                <p class="text-gray-600">{{ $page->title }}</p>
            </div>
            <a href="{{ route('admin.page.index') }}"
                class="px-4 py-2 rounded-md bg-white border border-gray-200 text-gray-700 text-sm hover:bg-gray-50 transition-colors duration-200 flex items-center gap-2">
                <i class="fa fa-arrow-left"></i>
                Back to List
            </a>
        </div>

        <form action="{{ route('admin.page.update', $page) }}" method="POST" enctype="multipart/form-data" class="space-y-6"
            x-data="{
                templates: {{ Js::from($templates ?? []) }},
                currentTemplate: {{ Js::from($currentTemplate ?? 'default') }},
                get visibleSections() {
                    const t = this.templates[this.currentTemplate];
                    const def = this.templates['default'];
                    const fallback = ['page_info', 'body', 'marketing', 'sidebar_settings', 'sidebar_image', 'seo'];
                    return (t && t.sections) ? t.sections : (def && def.sections) ? def.sections : fallback;
                }
            }"
        >
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <div class="lg:col-span-2 space-y-6">
                    {{-- Page Information --}}
                    <div class="bg-gray-50/50 rounded-md border border-gray-200 p-6" data-section="page_info" x-show="visibleSections.includes('page_info')" x-transition>
                        <h3 class="text-sm font-semibold text-gray-800 mb-4 flex items-center">
                            <span class="flex items-center justify-center w-7 h-7 rounded-md bg-primary/10 mr-2.5">
                                <i class="fa-solid fa-file-lines text-primary text-xs"></i>
                            </span>
                            Page Information
                        </h3>
                        <div class="space-y-4">
                            <div>
                                <label for="template" class="block text-xs font-medium text-gray-600 mb-1.5">Template</label>
                                <select id="template" name="template" x-model="currentTemplate"
                                    class="w-full px-3 py-2 text-sm bg-white border border-gray-200 rounded-md focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary/20 mb-4">
                                    @foreach($templates ?? [] as $key => $config)
                                        <option value="{{ $key }}">{{ $config['label'] ?? $key }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label for="title" class="block text-xs font-medium text-gray-600 mb-1.5">Title <span class="text-red-500">*</span></label>
                                <input type="text" id="title" name="title" value="{{ old('title', $page->title) }}" required
                                    class="w-full px-3 py-2 text-sm bg-white border border-gray-200 rounded-md focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary/20 @error('title') border-red-500 @enderror"
                                    placeholder="Enter page title">
                                @error('title')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label for="slug" class="block text-xs font-medium text-gray-600 mb-1.5">URL Slug <span class="text-red-500">*</span></label>
                                <input type="text" id="slug" name="slug" value="{{ old('slug', $page->slug) }}" required
                                    data-slug-from="title"
                                    class="w-full px-3 py-2 text-sm bg-white border border-gray-200 rounded-md focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary/20 @error('slug') border-red-500 @enderror"
                                    placeholder="url-slug">
                                @error('slug')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    {{-- Short Body & Long Body --}}
                    <div class="bg-gray-50/50 rounded-md border border-gray-200 p-6 space-y-6" data-section="body" x-show="visibleSections.includes('body')" x-transition>
                        <div>
                            <label for="short_body" class="block text-xs font-medium text-gray-700 mb-1">Short Body <span class="text-red-500">*</span></label>
                            <textarea id="short_body" name="short_body" rows="4" required
                                class="w-full px-3 py-2 text-sm bg-white border border-gray-200 rounded-md focus:outline-none @error('short_body') border-red-500 @enderror"
                                placeholder="Brief summary">{{ old('short_body', $page->short_body) }}</textarea>
                            @error('short_body')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label for="long_body" class="block text-xs font-medium text-gray-700 mb-1">Long Body <span class="text-red-500">*</span></label>
                            <x-editor id="long_body" name="long_body" :value="old('long_body', $page->long_body)" placeholder="Write the full content here..." />
                            @error('long_body')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    {{-- Marketing Automation --}}
                    <div class="bg-gray-50/50 rounded-md border border-gray-200 p-6" data-section="marketing" x-show="visibleSections.includes('marketing')" x-transition>
                        <h3 class="text-sm font-semibold text-gray-800 mb-4 flex items-center">
                            <span class="flex items-center justify-center w-7 h-7 rounded-md bg-purple-50 mr-2.5">
                                <i class="fa-solid fa-bullhorn text-purple-500 text-xs"></i>
                            </span>
                            Marketing Automation
                        </h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label for="funnel_fase" class="block text-xs font-medium text-gray-700 mb-1">Funnel Phase</label>
                                <select id="funnel_fase" name="funnel_fase"
                                    class="w-full px-3 py-2 text-sm bg-white border border-gray-200 rounded-md focus:outline-none">
                                    <option value="">Select funnel phase</option>
                                    <option value="interesseer" @selected(old('funnel_fase', $page->funnel_fase) === 'interesseer')>Interesseer</option>
                                    <option value="overtuig" @selected(old('funnel_fase', $page->funnel_fase) === 'overtuig')>Overtuig</option>
                                    <option value="activeer" @selected(old('funnel_fase', $page->funnel_fase) === 'activeer')>Activeer</option>
                                    <option value="inspireer" @selected(old('funnel_fase', $page->funnel_fase) === 'inspireer')>Inspireer</option>
                                </select>
                            </div>
                            <div>
                                <label for="marketing_persona_id" class="block text-xs font-medium text-gray-700 mb-1">Target Persona</label>
                                <select id="marketing_persona_id" name="marketing_persona_id"
                                    class="w-full px-3 py-2 text-sm bg-white border border-gray-200 rounded-md focus:outline-none">
                                    <option value="">Select persona</option>
                                    @foreach($marketingPersonas as $persona)
                                        <option value="{{ $persona->id }}" @selected(old('marketing_persona_id', $page->marketing_persona_id) == $persona->id)>{{ $persona->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label for="content_type_id" class="block text-xs font-medium text-gray-700 mb-1">Content Type</label>
                                <select id="content_type_id" name="content_type_id"
                                    class="w-full px-3 py-2 text-sm bg-white border border-gray-200 rounded-md focus:outline-none">
                                    <option value="">Select content type</option>
                                    @foreach($contentTypes as $type)
                                        <option value="{{ $type->id }}" @selected(old('content_type_id', $page->content_type_id) == $type->id)>{{ $type->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label for="primary_keyword" class="block text-xs font-medium text-gray-700 mb-1">Primary Keyword</label>
                                <input type="text" id="primary_keyword" name="primary_keyword" value="{{ old('primary_keyword', $page->primary_keyword) }}"
                                    class="w-full px-3 py-2 text-sm bg-white border border-gray-200 rounded-md focus:outline-none">
                            </div>
                            <div class="md:col-span-2">
                                <label for="ai_briefing" class="block text-xs font-medium text-gray-700 mb-1">AI Briefing</label>
                                <textarea id="ai_briefing" name="ai_briefing" rows="3"
                                    class="w-full px-3 py-2 text-sm bg-white border border-gray-200 rounded-md focus:outline-none">{{ old('ai_briefing', $page->ai_briefing) }}</textarea>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Sidebar --}}
                <div class="space-y-6">
                    {{-- Page Settings --}}
                    <div class="bg-gray-50/50 rounded-md border border-gray-200 p-6" data-section="sidebar_settings" x-show="visibleSections.includes('sidebar_settings')" x-transition>
                        <h3 class="text-sm font-semibold text-gray-800 mb-4 flex items-center">
                            <span class="flex items-center justify-center w-7 h-7 rounded-md bg-gray-100 mr-2.5">
                                <i class="fa-solid fa-cog text-gray-500 text-xs"></i>
                            </span>
                            Page Settings
                        </h3>
                        <div class="flex items-center justify-between">
                            <span class="text-sm font-medium text-gray-700">Status</span>
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="hidden" name="is_active" value="0">
                                <input type="checkbox" name="is_active" value="1" {{ old('is_active', $page->is_active) ? 'checked' : '' }} class="sr-only peer">
                                <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600"></div>
                            </label>
                        </div>
                        <p class="text-xs text-gray-500 mt-2">Page URL: {{ url('/pagina/' . ($page->slug ?? '')) }}</p>
                    </div>

                    {{-- Page Image --}}
                    <div class="bg-gray-50/50 rounded-md border border-gray-200 p-6" data-section="sidebar_image" x-show="visibleSections.includes('sidebar_image')" x-transition>
                        <x-image-upload
                            id="image"
                            name="image"
                            label="Page Image"
                            help-text="Optional featured image (max 20MB)."
                            :max-size="20480"
                            :required="false"
                            :current-image="$page->image ? asset('storage/' . $page->image) : null"
                            :current-image-alt="$page->title"
                        />
                    </div>

                    {{-- SEO Settings --}}
                    <div class="bg-gray-50/50 rounded-md border border-gray-200 p-6 space-y-4" data-section="seo" x-show="visibleSections.includes('seo')" x-transition>
                        <h3 class="text-sm font-semibold text-gray-800 mb-4 flex items-center">
                            <span class="flex items-center justify-center w-7 h-7 rounded-md bg-blue-50 mr-2.5">
                                <i class="fa-solid fa-search text-blue-500 text-xs"></i>
                            </span>
                            SEO Settings
                        </h3>
                        <div>
                            <label for="meta_title" class="block text-xs font-medium text-gray-700 mb-1">Meta Title</label>
                            <input type="text" id="meta_title" name="meta_title" value="{{ old('meta_title', $page->meta_title) }}"
                                class="w-full px-3 py-2 text-sm bg-white border border-gray-200 rounded-md focus:outline-none">
                        </div>
                        <div>
                            <label for="meta_body" class="block text-xs font-medium text-gray-700 mb-1">Meta Description</label>
                            <textarea id="meta_body" name="meta_body" rows="3"
                                class="w-full px-3 py-2 text-sm bg-white border border-gray-200 rounded-md focus:outline-none">{{ old('meta_body', $page->meta_body) }}</textarea>
                        </div>
                        <div>
                            <label for="meta_keywords" class="block text-xs font-medium text-gray-700 mb-1">Meta Keywords</label>
                            <textarea id="meta_keywords" name="meta_keywords" rows="3"
                                class="w-full px-3 py-2 text-sm bg-white border border-gray-200 rounded-md focus:outline-none">{{ old('meta_keywords', $page->meta_keywords) }}</textarea>
                        </div>
                    </div>
                </div>
            </div>

            <div class="flex justify-end gap-3 pt-6">
                <a href="{{ route('admin.page.index') }}"
                    class="px-4 py-2 text-sm text-gray-800 bg-white border border-gray-200 rounded-md hover:bg-gray-50 transition-colors">Cancel</a>
                <button type="submit"
                    class="px-4 py-2 text-sm text-white bg-primary rounded-md hover:bg-primary/80 transition-colors flex items-center gap-2">
                    <i class="fas fa-save"></i>
                    Update Page
                </button>
            </div>
        </form>
    </div>
@push('scripts')
<x-ui.slug-script />
@endpush
</x-layouts.admin>
