<x-layouts.admin title="Product Features">
<div class="space-y-6">
    {{-- Header --}}
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Product Features</h1>
            <p class="text-gray-600">Manage product features taxonomy for content organization</p>
        </div>
        <a href="{{ route('admin.marketing.product-feature.create') }}"
           class="bg-primary text-white px-4 py-2 rounded-lg hover:bg-primary/80 transition-colors duration-200">
            <i class="fa-solid fa-plus mr-2"></i>
            Create New Feature
        </a>
    </div>

    {{-- Features Table --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        @if($productFeatures->count() > 0)
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50 border-b border-gray-200">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Feature</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Category</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Benefits</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Type</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($productFeatures as $feature)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4">
                                    <div class="flex items-center">
                                        @if($feature->icon)
                                            <div class="h-10 w-10 rounded-lg bg-blue-50 flex items-center justify-center mr-3">
                                                <i class="fa-solid {{ $feature->icon }} text-blue-600"></i>
                                            </div>
                                        @else
                                            <div class="h-10 w-10 rounded-lg bg-gray-100 flex items-center justify-center mr-3">
                                                <i class="fa-solid fa-star text-gray-400"></i>
                                            </div>
                                        @endif
                                        <div>
                                            <div class="text-sm font-medium text-gray-900">{{ $feature->name }}</div>
                                            <div class="text-sm text-gray-500">{{ $feature->slug }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    @if($feature->category)
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                            {{ $feature->category }}
                                        </span>
                                    @else
                                        <span class="text-gray-400 text-sm">No category</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4">
                                    @if($feature->benefits && count($feature->benefits) > 0)
                                        <div class="text-sm text-gray-900">
                                            {{ count($feature->benefits) }} benefits
                                        </div>
                                        <div class="text-xs text-gray-500">
                                            {{ Str::limit(implode(', ', $feature->benefits), 50) }}
                                        </div>
                                    @else
                                        <span class="text-gray-400 text-sm">No benefits</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $feature->is_premium ? 'bg-yellow-100 text-yellow-800' : 'bg-green-100 text-green-800' }}">
                                        <i class="fa-solid fa-{{ $feature->is_premium ? 'crown' : 'check' }} mr-1"></i>
                                        {{ $feature->is_premium ? 'Premium' : 'Free' }}
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $feature->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                        {{ $feature->is_active ? 'Active' : 'Inactive' }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <div class="flex items-center justify-end space-x-2">
                                        <a href="{{ route('admin.marketing.product-feature.show', $feature) }}"
                                           class="text-blue-600 hover:text-blue-900"
                                           title="View">
                                            <i class="fa-solid fa-eye"></i>
                                        </a>
                                        <a href="{{ route('admin.marketing.product-feature.edit', $feature) }}"
                                           class="text-blue-600 hover:text-blue-900"
                                           title="Edit">
                                            <i class="fa-solid fa-edit"></i>
                                        </a>
                                        <button onclick="deleteFeature({{ $feature->id }})"
                                                class="text-red-600 hover:text-red-900"
                                                title="Delete">
                                            <i class="fa-solid fa-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="text-center py-12">
                <i class="fa-solid fa-star text-4xl text-gray-400 mb-4"></i>
                <p class="text-gray-600">No product features found. Create your first feature!</p>
            </div>
        @endif
    </div>

    {{-- Categories Summary --}}
    @if($categories->count() > 0)
        <div class="bg-white rounded-xl shadow-sm border border-gray-200">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900">Feature Categories</h3>
            </div>
            <div class="p-6">
                <div class="flex flex-wrap gap-2">
                    @foreach($categories as $category)
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800">
                            {{ $category }}
                            <span class="ml-1 text-xs">({{ $productFeatures->where('category', $category)->count() }})</span>
                        </span>
                    @endforeach
                </div>
            </div>
        </div>
    @endif
</div>

{{-- Delete Modal --}}
<div id="deleteModal" class="fixed inset-0 bg-gray-600/50 overflow-y-auto h-full w-full hidden z-50">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <div class="flex items-center mb-4">
                <i class="fa-solid fa-exclamation-triangle text-red-500 text-2xl mr-3"></i>
                <h3 class="text-lg font-semibold text-gray-900">Delete Product Feature</h3>
            </div>
            <p class="text-gray-600 mb-6">
                Are you sure you want to delete this product feature? This action cannot be undone and may affect related content.
            </p>
            <div class="flex items-center justify-end space-x-4">
                <button type="button"
                        onclick="document.getElementById('deleteModal').classList.add('hidden')"
                        class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400 transition-colors duration-200">
                    Cancel
                </button>
                <form id="deleteForm" method="POST" class="inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit"
                            class="px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700 transition-colors duration-200">
                        Delete
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
function deleteFeature(featureId) {
    document.getElementById('deleteForm').action = `/admin/marketing/product-feature/${featureId}`;
    document.getElementById('deleteModal').classList.remove('hidden');
}
</script>
</x-layouts.admin>
