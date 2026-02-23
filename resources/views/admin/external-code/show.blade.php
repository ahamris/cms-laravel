<x-layouts.admin title="External Code Details">
<div>
    {{-- Header --}}
    <div class="flex items-center justify-between mb-6">
        <div class="flex items-center gap-3">
            <div class="w-12 h-12 bg-primary rounded-md flex items-center justify-center">
                <i class="fa-solid fa-eye text-white text-base"></i>
            </div>
            <div class="flex flex-col gap-1">
                <h2>{{ $externalCode->name }}</h2>
                <p>External code details and settings</p>
            </div>
        </div>
        <div class="flex space-x-3">
            <a href="{{ route('admin.external-code.edit', $externalCode) }}"
               class="px-5 py-2 rounded-md bg-primary text-white text-sm">
                <i class="fa-solid fa-edit mr-2"></i>
                Edit
            </a>
            <a href="{{ route('admin.external-code.index') }}"
               class="px-5 py-2 rounded-md bg-white border border-gray-200 text-gray-700 text-sm">
                <i class="fa-solid fa-arrow-left mr-2"></i>
                Back to List
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        {{-- Main Content --}}
        <div class="lg:col-span-2 space-y-6">
            {{-- Basic Information --}}
            <div class="bg-gray-50/50 rounded-md border border-gray-200">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-base font-semibold text-gray-900">Basic Information</h3>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        {{-- Name --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Name</label>
                            <div class="text-sm text-gray-900 bg-white px-3 py-2 rounded-md border border-gray-200">{{ $externalCode->name }}</div>
                        </div>

                        {{-- Status --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $externalCode->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                <i class="fa-solid {{ $externalCode->is_active ? 'fa-check-circle' : 'fa-times-circle' }} mr-1"></i>
                                {{ $externalCode->is_active ? 'Active' : 'Inactive' }}
                            </span>
                        </div>

                        {{-- Sort Order --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Sort Order</label>
                            <div class="text-sm text-gray-900 bg-white px-3 py-2 rounded-md border border-gray-200">#{{ $externalCode->sort_order }}</div>
                        </div>

                        {{-- Injection Points --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Injection Points</label>
                            <div class="flex flex-wrap gap-2">
                                @if($externalCode->before_header)
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                        <i class="fa-solid fa-code mr-1"></i>
                                        Header
                                    </span>
                                @endif
                                @if($externalCode->before_body)
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                        <i class="fa-solid fa-code mr-1"></i>
                                        Body
                                    </span>
                                @endif
                                @if(!$externalCode->before_header && !$externalCode->before_body)
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                        <i class="fa-solid fa-ban mr-1"></i>
                                        None
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Code Content --}}
            <div class="bg-gray-50/50 rounded-md border border-gray-200">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-base font-semibold text-gray-900">Code Content</h3>
                </div>
                <div class="p-6">
                    <div class="bg-gray-900 text-gray-100 p-4 rounded-md overflow-x-auto border border-gray-200">
                        <pre class="text-xs"><code>{{ $externalCode->content }}</code></pre>
                    </div>
                    <div class="mt-3 flex justify-between items-center">
                        <button onclick="copyToClipboard()" class="text-primary hover:text-primary/80 text-sm">
                            <i class="fa-solid fa-copy mr-1"></i>
                            Copy Code
                        </button>
                        <span class="text-xs text-gray-500">{{ strlen($externalCode->content) }} characters</span>
                    </div>
                </div>
            </div>
        </div>

        {{-- Sidebar --}}
        <div class="space-y-6">
            {{-- Metadata --}}
            <div class="bg-white rounded-md border border-gray-200">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-base font-semibold text-gray-900">Metadata</h3>
                </div>
                <div class="p-6 space-y-3">
                    <div class="flex justify-between items-center">
                        <span class="text-sm font-medium text-gray-700">Created</span>
                        <span class="text-xs text-gray-900">{{ $externalCode->created_at->format('M j, Y \a\t g:i A') }}</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-sm font-medium text-gray-700">Last Updated</span>
                        <span class="text-xs text-gray-900">{{ $externalCode->updated_at->format('M j, Y \a\t g:i A') }}</span>
                    </div>
                </div>
            </div>

            {{-- Injection Guidelines --}}
            <div class="bg-white rounded-md border border-gray-200">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-base font-semibold text-gray-900">Injection Guidelines</h3>
                </div>
                <div class="p-6 text-sm text-gray-600 space-y-3">
                    <div class="flex items-start">
                        <i class="fa-solid fa-code text-blue-500 mt-0.5 mr-2"></i>
                        <div>
                            <p class="font-medium text-gray-900 text-sm">Header Injection</p>
                            <p class="text-xs">Loads early, good for fonts, meta tags, critical CSS</p>
                        </div>
                    </div>
                    <div class="flex items-start">
                        <i class="fa-solid fa-code text-green-500 mt-0.5 mr-2"></i>
                        <div>
                            <p class="font-medium text-gray-900 text-sm">Body Injection</p>
                            <p class="text-xs">Loads late, good for analytics, widgets, non-blocking scripts</p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Actions --}}
            <div class="bg-white rounded-md border border-gray-200">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-base font-semibold text-gray-900">Actions</h3>
                </div>
                <div class="p-6 space-y-3">
                    <a href="{{ route('admin.external-code.edit', $externalCode) }}"
                       class="w-full bg-primary text-white px-4 py-2 rounded-md hover:bg-primary/90 transition-colors duration-200 text-center block text-sm">
                        <i class="fa-solid fa-edit mr-2"></i>
                        Edit External Code
                    </a>
                    <button onclick="deleteExternalCode({{ $externalCode->id }})"
                            class="w-full bg-red-600 text-white px-4 py-2 rounded-md hover:bg-red-700 transition-colors duration-200 text-sm">
                        <i class="fa-solid fa-trash mr-2"></i>
                        Delete External Code
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Delete Confirmation Modal --}}
<div id="deleteModal" class="fixed inset-0 bg-black/50 hidden z-50 flex items-center justify-center">
    <div class="bg-white rounded-md border border-gray-200 shadow-xl max-w-md w-full mx-4">
        <div class="p-6">
            <div class="flex items-center mb-3">
                <i class="fa-solid fa-exclamation-triangle text-red-500 text-xl mr-3"></i>
                <h3 class="text-base font-semibold text-gray-900">Delete External Code</h3>
            </div>
            <p class="text-gray-600 text-sm mb-4">
                Are you sure you want to delete "{{ $externalCode->name }}"? This action cannot be undone.
            </p>
            <div class="flex items-center justify-end space-x-3 border-t border-gray-200 pt-4">
                <button type="button"
                        onclick="closeDeleteModal()"
                        class="px-5 py-2 rounded-md bg-white border border-gray-200 text-gray-700 text-sm">
                    Cancel
                </button>
                <form id="deleteForm" method="POST" class="inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit"
                            class="px-5 py-2 rounded-md bg-red-600 text-white text-sm">
                        Delete
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
function deleteExternalCode(externalCodeId) {
    document.getElementById('deleteForm').action = `/admin/content/external-code/${externalCodeId}`;
    document.getElementById('deleteModal').classList.remove('hidden');
}

function closeDeleteModal() {
    document.getElementById('deleteModal').classList.add('hidden');
}

// Close modal when clicking outside
document.getElementById('deleteModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeDeleteModal();
    }
});

function copyToClipboard() {
    const codeContent = @json($externalCode->content);
    navigator.clipboard.writeText(codeContent).then(function() {
        toastr.success('Code copied to clipboard!');
    }).catch(function(err) {
        toastr.error('Failed to copy code: ' + err);
    });
}

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
