<x-layouts.admin title="Edit Case Study">
<div class="space-y-6">
    {{-- Header --}}
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Edit Case Study</h1>
            <p class="text-gray-600">Update {{ $caseStudy->title }}</p>
        </div>
        <a href="{{ route('admin.marketing.case-study.index') }}"
           class="bg-gray-600 text-white px-4 py-2 rounded-lg hover:bg-gray-700 transition-colors duration-200">
            <i class="fa-solid fa-arrow-left mr-2"></i>
            Back to List
        </a>
    </div>

    {{-- Form --}}
    <form action="{{ route('admin.marketing.case-study.update', $caseStudy) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            {{-- Main Content --}}
            <div class="lg:col-span-2 space-y-6">
                {{-- Basic Information --}}
                <div class="bg-white rounded-xl shadow-sm border border-gray-200">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-900">Basic Information</h3>
                    </div>
                    <div class="p-6 space-y-4">
                        {{-- Title --}}
                        <div>
                            <label for="title" class="block text-sm font-medium text-gray-700 mb-2">
                                Case Study Title <span class="text-red-500">*</span>
                            </label>
                            <input type="text"
                                   id="title"
                                   name="title"
                                   value="{{ old('title', $caseStudy->title) }}"
                                   required
                                   placeholder="e.g., How Company X Increased Revenue by 300%"
                                   class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-primary focus:border-primary @error('title') border-red-500 @enderror">
                            @error('title')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                        </div>

                        {{-- Slug --}}
                        <div>
                            <label for="slug" class="block text-sm font-medium text-gray-700 mb-2">Slug</label>
                            <input type="text"
                                   id="slug"
                                   name="slug"
                                   value="{{ old('slug', $caseStudy->slug) }}"
                                   placeholder="Auto-generated from title"
                                   class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-primary focus:border-primary @error('slug') border-red-500 @enderror">
                            @error('slug')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            {{-- Client Name --}}
                            <div>
                                <label for="client_name" class="block text-sm font-medium text-gray-700 mb-2">
                                    Client Name <span class="text-red-500">*</span>
                                </label>
                                <input type="text"
                                       id="client_name"
                                       name="client_name"
                                       value="{{ old('client_name', $caseStudy->client_name) }}"
                                       required
                                       placeholder="e.g., TechCorp BV"
                                       class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-primary focus:border-primary @error('client_name') border-red-500 @enderror">
                                @error('client_name')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                            </div>

                            {{-- Client Industry --}}
                            <div>
                                <label for="client_industry" class="block text-sm font-medium text-gray-700 mb-2">Industry</label>
                                <input type="text"
                                       id="client_industry"
                                       name="client_industry"
                                       value="{{ old('client_industry', $caseStudy->client_industry) }}"
                                       placeholder="e.g., Technology, Healthcare"
                                       class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-primary focus:border-primary @error('client_industry') border-red-500 @enderror">
                                @error('client_industry')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                            </div>

                            {{-- Client Size --}}
                            <div>
                                <label for="client_size" class="block text-sm font-medium text-gray-700 mb-2">Company Size</label>
                                <input type="text"
                                       id="client_size"
                                       name="client_size"
                                       value="{{ old('client_size', $caseStudy->client_size) }}"
                                       placeholder="e.g., 50-100 employees"
                                       class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-primary focus:border-primary @error('client_size') border-red-500 @enderror">
                                @error('client_size')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Case Study Content --}}
                <div class="bg-white rounded-xl shadow-sm border border-gray-200">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-900">Case Study Content</h3>
                    </div>
                    <div class="p-6 space-y-4">
                        {{-- Challenge --}}
                        <div>
                            <label for="challenge" class="block text-sm font-medium text-gray-700 mb-2">
                                Challenge <span class="text-red-500">*</span>
                            </label>
                            <textarea id="challenge"
                                      name="challenge"
                                      rows="4"
                                      required
                                      placeholder="Describe the client's main challenge or problem..."
                                      class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-primary focus:border-primary @error('challenge') border-red-500 @enderror">{{ old('challenge', $caseStudy->challenge) }}</textarea>
                            @error('challenge')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                        </div>

                        {{-- Solution --}}
                        <div>
                            <label for="solution" class="block text-sm font-medium text-gray-700 mb-2">
                                Solution <span class="text-red-500">*</span>
                            </label>
                            <textarea id="solution"
                                      name="solution"
                                      rows="4"
                                      required
                                      placeholder="Describe how you solved the client's problem..."
                                      class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-primary focus:border-primary @error('solution') border-red-500 @enderror">{{ old('solution', $caseStudy->solution) }}</textarea>
                            @error('solution')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                        </div>

                        {{-- Results --}}
                        <div>
                            <label for="results" class="block text-sm font-medium text-gray-700 mb-2">
                                Results <span class="text-red-500">*</span>
                            </label>
                            <textarea id="results"
                                      name="results"
                                      rows="4"
                                      required
                                      placeholder="Describe the outcomes and benefits achieved..."
                                      class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-primary focus:border-primary @error('results') border-red-500 @enderror">{{ old('results', $caseStudy->results) }}</textarea>
                            @error('results')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                        </div>
                    </div>
                </div>

                {{-- Metrics --}}
                <div class="bg-white rounded-xl shadow-sm border border-gray-200">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-900">Key Metrics</h3>
                        <p class="text-sm text-gray-600">Add quantifiable results and achievements</p>
                    </div>
                    <div class="p-6">
                        <div id="metrics-container">
                            @if($caseStudy->metrics && count($caseStudy->metrics) > 0)
                                @foreach($caseStudy->metrics as $index => $metric)
                                    <div class="metric-item flex items-center space-x-2 mb-2">
                                        <input type="text"
                                               name="metrics[]"
                                               value="{{ old('metrics.' . $index, $metric) }}"
                                               placeholder="Enter a metric"
                                               class="flex-1 border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-primary focus:border-primary">
                                        <button type="button" onclick="removeMetric(this)" class="text-red-600 hover:text-red-800">
                                            <i class="fa-solid fa-times"></i>
                                        </button>
                                    </div>
                                @endforeach
                            @else
                                <div class="metric-item flex items-center space-x-2 mb-2">
                                    <input type="text"
                                           name="metrics[]"
                                           placeholder="Enter a metric"
                                           class="flex-1 border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-primary focus:border-primary">
                                    <button type="button" onclick="removeMetric(this)" class="text-red-600 hover:text-red-800">
                                        <i class="fa-solid fa-times"></i>
                                    </button>
                                </div>
                            @endif
                        </div>
                        <button type="button" onclick="addMetric()" class="text-primary hover:text-primary/80 text-sm">
                            <i class="fa-solid fa-plus mr-1"></i> Add Metric
                        </button>
                    </div>
                </div>

                {{-- Product Features --}}
                <div class="bg-white rounded-xl shadow-sm border border-gray-200">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-900">Related Product Features</h3>
                        <p class="text-sm text-gray-600">Select features used in this case study</p>
                    </div>
                    <div class="p-6">
                        @if($productFeatures->count() > 0)
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                                @foreach($productFeatures as $feature)
                                    <div class="flex items-center">
                                        <input type="checkbox"
                                               id="feature_{{ $feature->id }}"
                                               name="product_features[]"
                                               value="{{ $feature->id }}"
                                               {{ in_array($feature->id, old('product_features', $caseStudy->productFeatures->pluck('id')->toArray())) ? 'checked' : '' }}
                                               class="h-4 w-4 text-primary focus:ring-primary border-gray-300 rounded">
                                        <label for="feature_{{ $feature->id }}" class="ml-2 block text-sm text-gray-900">
                                            @if($feature->icon)
                                                <i class="fa-solid {{ $feature->icon }} mr-1"></i>
                                            @endif
                                            {{ $feature->name }}
                                            @if($feature->is_premium)
                                                <span class="inline-flex items-center px-1.5 py-0.5 rounded text-xs font-medium bg-yellow-100 text-yellow-800 ml-1">
                                                    <i class="fa-solid fa-crown mr-1"></i>Premium
                                                </span>
                                            @endif
                                        </label>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <p class="text-gray-500 text-sm">No product features available. Create some features first.</p>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Sidebar --}}
            <div class="lg:col-span-1 space-y-6">
                {{-- Current Images --}}
                <div class="bg-white rounded-xl shadow-sm border border-gray-200">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-900">Current Images</h3>
                    </div>
                    <div class="p-6 space-y-4">
                        {{-- Current Client Logo --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Current Client Logo</label>
                            @if($caseStudy->client_logo)
                                <img src="{{ asset('storage/' . $caseStudy->client_logo) }}" 
                                     alt="{{ $caseStudy->client_name }}" 
                                     class="w-20 h-16 object-contain bg-gray-50 rounded">
                            @else
                                <p class="text-gray-500 text-sm">No logo uploaded</p>
                            @endif
                        </div>

                        {{-- Current Featured Image --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Current Featured Image</label>
                            @if($caseStudy->featured_image)
                                <img src="{{ asset('storage/' . $caseStudy->featured_image) }}" 
                                     alt="{{ $caseStudy->title }}" 
                                     class="w-full h-32 object-cover rounded">
                            @else
                                <p class="text-gray-500 text-sm">No featured image uploaded</p>
                            @endif
                        </div>
                    </div>
                </div>

                {{-- Update Images --}}
                <div class="bg-white rounded-xl shadow-sm border border-gray-200">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-900">Update Images</h3>
                    </div>
                    <div class="p-6 space-y-4">
                        {{-- Client Logo --}}
                        <div>
                            <label for="client_logo" class="block text-sm font-medium text-gray-700 mb-2">New Client Logo</label>
                            <input type="file"
                                   id="client_logo"
                                   name="client_logo"
                                   accept="image/*"
                                   class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-primary focus:border-primary @error('client_logo') border-red-500 @enderror">
                            <p class="text-xs text-gray-500 mt-1">Max 20MB. JPG, PNG, GIF, SVG</p>
                            @error('client_logo')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                        </div>

                        {{-- Featured Image --}}
                        <div>
                            <label for="featured_image" class="block text-sm font-medium text-gray-700 mb-2">New Featured Image</label>
                            <input type="file"
                                   id="featured_image"
                                   name="featured_image"
                                   accept="image/*"
                                   class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-primary focus:border-primary @error('featured_image') border-red-500 @enderror">
                            <p class="text-xs text-gray-500 mt-1">Max 20MB. JPG, PNG, GIF, SVG</p>
                            @error('featured_image')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                        </div>
                    </div>
                </div>

                {{-- Settings --}}
                <div class="bg-white rounded-xl shadow-sm border border-gray-200">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-900">Settings</h3>
                    </div>
                    <div class="p-6 space-y-4">
                        {{-- Featured --}}
                        <div class="flex items-center">
                            <input type="checkbox"
                                   id="is_featured"
                                   name="is_featured"
                                   value="1"
                                   {{ old('is_featured', $caseStudy->is_featured) ? 'checked' : '' }}
                                   class="h-4 w-4 text-primary focus:ring-primary border-gray-300 rounded">
                            <label for="is_featured" class="ml-2 block text-sm text-gray-900">
                                <i class="fa-solid fa-star text-yellow-500 mr-1"></i>
                                Featured Case Study
                            </label>
                        </div>

                        {{-- Active Status --}}
                        <div class="flex items-center">
                            <input type="checkbox"
                                   id="is_active"
                                   name="is_active"
                                   value="1"
                                   {{ old('is_active', $caseStudy->is_active) ? 'checked' : '' }}
                                   class="h-4 w-4 text-primary focus:ring-primary border-gray-300 rounded">
                            <label for="is_active" class="ml-2 block text-sm text-gray-900">Active</label>
                        </div>

                        {{-- Sort Order --}}
                        <div>
                            <label for="sort_order" class="block text-sm font-medium text-gray-700 mb-2">Sort Order</label>
                            <input type="number"
                                   id="sort_order"
                                   name="sort_order"
                                   value="{{ old('sort_order', $caseStudy->sort_order) }}"
                                   min="0"
                                   class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-primary focus:border-primary">
                        </div>

                        {{-- Metadata --}}
                        <div class="pt-4 border-t border-gray-200">
                            <div class="text-sm text-gray-600">
                                <p><strong>Created:</strong> {{ $caseStudy->created_at->format('M j, Y \a\t g:i A') }}</p>
                                @if($caseStudy->updated_at != $caseStudy->created_at)
                                    <p><strong>Updated:</strong> {{ $caseStudy->updated_at->format('M j, Y \a\t g:i A') }}</p>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Submit --}}
                <div class="bg-white rounded-xl shadow-sm border border-gray-200">
                    <div class="p-6">
                        <button type="submit"
                                class="w-full bg-primary text-white py-2 px-4 rounded-lg hover:bg-primary/80 transition-colors duration-200">
                            <i class="fa-solid fa-save mr-2"></i>
                            Update Case Study
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

<script>
// Metric management functions
function addMetric() {
    const container = document.getElementById('metrics-container');
    const div = document.createElement('div');
    div.className = 'metric-item flex items-center space-x-2 mb-2';
    div.innerHTML = `
        <input type="text" name="metrics[]" placeholder="Enter a metric" 
               class="flex-1 border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-primary focus:border-primary">
        <button type="button" onclick="removeMetric(this)" class="text-red-600 hover:text-red-800">
            <i class="fa-solid fa-times"></i>
        </button>
    `;
    container.appendChild(div);
}

function removeMetric(button) {
    button.closest('.metric-item').remove();
}

// Tag management functions
function addTag() {
    const container = document.getElementById('tags-container');
    const div = document.createElement('div');
    div.className = 'tag-item flex items-center space-x-2 mb-2';
    div.innerHTML = `
        <input type="text" name="tags[]" placeholder="Enter a tag" 
               class="flex-1 border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-primary focus:border-primary">
        <button type="button" onclick="removeTag(this)" class="text-red-600 hover:text-red-800">
            <i class="fa-solid fa-times"></i>
        </button>
    `;
    container.appendChild(div);
}

function removeTag(button) {
    button.closest('.tag-item').remove();
}
</script>
</x-layouts.admin>
