<x-layouts.admin title="Edit External Code">
<div>
    {{-- Header --}}
    <div class="flex items-center justify-between mb-6">
        <div class="flex items-center gap-3">
            <div class="w-12 h-12 bg-primary rounded-md flex items-center justify-center">
                <i class="fa-solid fa-pen text-white text-base"></i>
            </div>
            <div class="flex flex-col gap-1">
                <h2>Edit External Code</h2>
                <p>Update external code snippet</p>
            </div>
        </div>
        <a href="{{ route('admin.external-code.index') }}"
           class="px-4 py-2 rounded-md bg-white border border-gray-200 text-gray-700 text-sm">
            <i class="fa-solid fa-arrow-left mr-2"></i>
            Back to List
        </a>
    </div>

    {{-- Form --}}
    <form action="{{ route('admin.external-code.update', $externalCode) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            {{-- Main Content --}}
            <div class="lg:col-span-2 space-y-6">
                {{-- Basic Information --}}
                <div class="bg-gray-50/50 rounded-md border border-gray-200">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-base font-semibold text-gray-900">Basic Information</h3>
                    </div>
                    <div class="p-6 space-y-4">
                        {{-- Name --}}
                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                                Name <span class="text-red-500">*</span>
                            </label>
                            <input type="text"
                                   id="name"
                                   name="name"
                                   value="{{ old('name', $externalCode->name) }}"
                                   required
                                   placeholder="e.g., Google Analytics, Facebook Pixel"
                                   class="block bg-white w-full border border-gray-200 rounded-md px-3 py-2 text-sm focus:outline-none @error('name') border-red-500 @enderror">
                            <p class="text-xs text-gray-500 mt-1">Descriptive name for this code snippet</p>
                            @error('name')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Content --}}
                        <div>
                            <label for="content" class="block text-sm font-medium text-gray-700 mb-2">
                                Code Content <span class="text-red-500">*</span>
                            </label>
                            <textarea id="content"
                                      name="content"
                                      rows="8"
                                      required
                                      placeholder="Paste your HTML/CSS/JavaScript code here..."
                                      class="block bg-white w-full border border-gray-200 rounded-md px-3 py-2 font-mono text-sm focus:outline-none @error('content') border-red-500 @enderror">{{ old('content', $externalCode->content) }}</textarea>
                            <p class="text-xs text-gray-500 mt-1">HTML, CSS, or JavaScript code to be injected</p>
                            @error('content')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                {{-- Injection Settings --}}
                <div class="bg-gray-50/50 rounded-md border border-gray-200">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-base font-semibold text-gray-900">Injection Settings</h3>
                    </div>
                    <div class="p-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <x-radio
                                    name="injection_location"
                                    value="header"
                                    :checked="old('injection_location', $externalCode->before_header ? 'header' : '') == 'header'"
                                    label="Inject before <head> tag"
                                />
                                <p class="text-xs text-gray-500 mt-1 ml-6">Typically used for meta tags or fonts</p>
                            </div>
                            <div>
                                <x-radio
                                    name="injection_location"
                                    value="body"
                                    :checked="old('injection_location', $externalCode->before_body ? 'body' : '') == 'body'"
                                    label="Inject before </body> tag"
                                />
                                <p class="text-xs text-gray-500 mt-1 ml-6">Typically used for analytics or chat widgets</p>
                            </div>
                        </div>

                        {{-- Info Alert --}}
                        <div class="bg-blue-50 border border-blue-200 rounded-md p-3 mt-4">
                            <div class="flex">
                                <i class="fa-solid fa-info-circle text-blue-400 mt-0.5 mr-2"></i>
                                <div class="text-sm text-blue-700">
                                    <p class="font-medium">Injection Guidelines:</p>
                                    <ul class="mt-1 text-xs space-y-1">
                                        <li>• Choose one or both injection points based on where your code should run</li>
                                        <li>• Header injection loads early but may slow page load</li>
                                        <li>• Body injection loads late but doesn't block rendering</li>
                                        <li>• Some scripts (like analytics) work best in body</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Sidebar --}}
            <div class="space-y-6">
                {{-- Status & Settings --}}
                <div class="bg-white rounded-md border border-gray-200">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-base font-semibold text-gray-900">Settings</h3>
                    </div>
                    <div class="p-6 space-y-4">
                        {{-- Active Status --}}
                        <x-ui.toggle name="is_active" label="Active" :checked="old('is_active', $externalCode->is_active)" />

                        {{-- Sort Order --}}
                        <div>
                            <label for="sort_order" class="block text-sm font-medium text-gray-700 mb-2">
                                Sort Order
                            </label>
                            <input type="number"
                                   id="sort_order"
                                   name="sort_order"
                                   value="{{ old('sort_order', $externalCode->sort_order) }}"
                                   min="0"
                                   class="block bg-white w-full border border-gray-200 rounded-md px-3 py-2 text-sm focus:outline-none @error('sort_order') border-red-500 @enderror">
                            <p class="text-xs text-gray-500 mt-1">Lower numbers appear first (0 = highest priority)</p>
                            @error('sort_order')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                {{-- Code Examples --}}
                <div class="bg-white rounded-md border border-gray-200">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-base font-semibold text-gray-900">Code Examples</h3>
                    </div>
                    <div class="p-6">
                        <div class="space-y-3 text-xs">
                            <div class="bg-gray-50 p-3 rounded-md border border-gray-200">
                                <p class="font-medium text-gray-900 mb-1">Google Analytics (Body):</p>
                                <code class="text-gray-700">&lt;script&gt;...&lt;/script&gt;</code>
                            </div>
                            <div class="bg-gray-50 p-3 rounded-md border border-gray-200">
                                <p class="font-medium text-gray-900 mb-1">Custom CSS (Header):</p>
                                <code class="text-gray-700">&lt;style&gt;...&lt;/style&gt;</code>
                            </div>
                            <div class="bg-gray-50 p-3 rounded-md border border-gray-200">
                                <p class="font-medium text-gray-900 mb-1">Meta Tags (Header):</p>
                                <code class="text-gray-700">&lt;meta name="..."&gt;</code>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Actions --}}
                <div class="bg-white rounded-md border border-gray-200">
                    <div class="p-6">
                        <button type="submit"
                                class="w-full bg-primary text-white px-6 py-2 rounded-md text-sm">
                            <i class="fa-solid fa-save mr-2"></i>
                            Update External Code
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

<script>
// Display session messages
document.addEventListener('DOMContentLoaded', function() {
    @if(session('success'))
        toastr.success('{{ session('success') }}');
    @endif

    @if(session('error'))
        toastr.error('{{ session('error') }}');
    @endif

    @if($errors->any())
        @foreach ($errors->all() as $error)
            toastr.error('{{ $error }}');
        @endforeach
    @endif
});
</script>
</x-layouts.admin>
