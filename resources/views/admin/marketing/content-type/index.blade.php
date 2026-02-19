<x-layouts.admin title="Content Types">
<div class="space-y-6">
    {{-- Header --}}
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Content Types</h1>
            <p class="text-gray-600">Manage content classification system for strategic content planning</p>
        </div>
        <a href="{{ route('admin.marketing.content-type.create') }}"
           class="bg-primary text-white px-4 py-2 rounded-lg hover:bg-primary/80 transition-colors duration-200">
            <i class="fa-solid fa-plus mr-2"></i>
            Create New Type
        </a>
    </div>

    {{-- Content Types Table --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        @if($contentTypes->count() > 0)
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50 border-b border-gray-200">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Type</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Description</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Applicable Models</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Order</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($contentTypes as $contentType)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4">
                                    <div class="flex items-center">
                                        @if($contentType->icon)
                                            <div class="h-10 w-10 rounded-lg flex items-center justify-center mr-3" style="background-color: {{ $contentType->color }}20;">
                                                <i class="fa-solid {{ $contentType->icon }}" style="color: {{ $contentType->color }};"></i>
                                            </div>
                                        @else
                                            <div class="h-10 w-10 rounded-lg bg-gray-100 flex items-center justify-center mr-3">
                                                <i class="fa-solid fa-tag text-gray-400"></i>
                                            </div>
                                        @endif
                                        <div>
                                            <div class="text-sm font-medium text-gray-900">{{ $contentType->name }}</div>
                                            <div class="text-sm text-gray-500">{{ $contentType->slug }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    @if($contentType->description)
                                        <div class="text-sm text-gray-900">
                                            {{ Str::limit($contentType->description, 60) }}
                                        </div>
                                    @else
                                        <span class="text-gray-400 text-sm">No description</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4">
                                    @if($contentType->applicable_models && count($contentType->applicable_models) > 0)
                                        <div class="flex flex-wrap gap-1">
                                            @foreach($contentType->applicable_models as $model)
                                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                                    {{ class_basename($model) }}
                                                </span>
                                            @endforeach
                                        </div>
                                    @else
                                        <span class="text-gray-400 text-sm">All models</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $contentType->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                        {{ $contentType->is_active ? 'Active' : 'Inactive' }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-900">
                                    {{ $contentType->sort_order }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <div class="flex items-center justify-end space-x-2">
                                        <a href="{{ route('admin.marketing.content-type.show', $contentType) }}"
                                           class="text-blue-600 hover:text-blue-900"
                                           title="View">
                                            <i class="fa-solid fa-eye"></i>
                                        </a>
                                        <a href="{{ route('admin.marketing.content-type.edit', $contentType) }}"
                                           class="text-blue-600 hover:text-blue-900"
                                           title="Edit">
                                            <i class="fa-solid fa-edit"></i>
                                        </a>
                                        <button onclick="deleteContentType({{ $contentType->id }})"
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
                <i class="fa-solid fa-tags text-4xl text-gray-400 mb-4"></i>
                <p class="text-gray-600">No content types found. Create your first content type!</p>
            </div>
        @endif
    </div>
</div>

{{-- Delete Modal --}}
<div id="deleteModal" class="fixed inset-0 bg-gray-600/50 overflow-y-auto h-full w-full hidden z-50">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <div class="flex items-center mb-4">
                <i class="fa-solid fa-exclamation-triangle text-red-500 text-2xl mr-3"></i>
                <h3 class="text-lg font-semibold text-gray-900">Delete Content Type</h3>
            </div>
            <p class="text-gray-600 mb-6">
                Are you sure you want to delete this content type? This action cannot be undone and may affect existing content.
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
function deleteContentType(contentTypeId) {
    document.getElementById('deleteForm').action = `/admin/marketing/content-type/${contentTypeId}`;
    document.getElementById('deleteModal').classList.remove('hidden');
}
</script>
</x-layouts.admin>
