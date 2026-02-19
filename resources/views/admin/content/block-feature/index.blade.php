<x-layouts.admin title="Feature Blocks">
<div class="space-y-6">
    {{-- Header --}}
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Feature Blocks</h1>
            <p class="text-gray-600">Manage feature block sections for your website</p>
        </div>
        <a href="{{ route('admin.content.block-feature.create') }}"
           class="bg-primary text-white px-4 py-2 rounded-lg hover:bg-primary/80 transition-colors duration-200">
            <i class="fa-solid fa-plus mr-2"></i>
            Add Feature Block
        </a>
    </div>

    {{-- Stats Cards --}}
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <div class="flex items-center">
                <div class="p-2 bg-blue-100 rounded-lg">
                    <i class="fa-solid fa-th text-blue-600 text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Total Blocks</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $featureBlocks->total() }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <div class="flex items-center">
                <div class="p-2 bg-green-100 rounded-lg">
                    <i class="fa-solid fa-check-circle text-green-600 text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Active</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $featureBlocks->where('is_active', true)->count() }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <div class="flex items-center">
                <div class="p-2 bg-red-100 rounded-lg">
                    <i class="fa-solid fa-pause-circle text-red-600 text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Inactive</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $featureBlocks->where('is_active', false)->count() }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <div class="flex items-center">
                <div class="p-2 bg-purple-100 rounded-lg">
                    <i class="fa-solid fa-image text-purple-600 text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Total Items</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $featureBlocks->sum(function($item) { return count($item->items ?? []); }) }}</p>
                </div>
            </div>
        </div>
    </div>

    {{-- Feature Blocks Table --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-200">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-semibold text-gray-900">All Feature Blocks</h3>
        </div>
        
        @if($featureBlocks->count() > 0)
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Title</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Identifier</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Sort Order</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Created</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($featureBlocks as $blockFeature)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="w-10 h-10 rounded-lg bg-primary/10 flex items-center justify-center flex-shrink-0">
                                            <i class="fa-solid fa-th text-primary"></i>
                                        </div>
                                        <div class="ml-4">
                                            <div class="text-sm font-medium text-gray-900">
                                                {{ $blockFeature->section_title ?: $blockFeature->identifier }}
                                            </div>
                                            @if($blockFeature->section_subtitle)
                                                <div class="text-sm text-gray-500">{{ Str::limit($blockFeature->section_subtitle, 80) }}</div>
                                            @endif
                                            <div class="text-xs text-gray-400 mt-1">
                                                {{ count($blockFeature->items ?? []) }} item(s)
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                        {{ $blockFeature->identifier }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $blockFeature->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                        <i class="fa-solid {{ $blockFeature->is_active ? 'fa-check-circle' : 'fa-pause-circle' }} mr-1"></i>
                                        {{ $blockFeature->is_active ? 'Active' : 'Inactive' }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ $blockFeature->sort_order }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $blockFeature->created_at->format('M j, Y') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <div class="flex items-center justify-end space-x-2">
                                        <a href="{{ route('admin.content.block-feature.show', $blockFeature) }}" 
                                           class="text-blue-600 hover:text-blue-900 transition-colors duration-200">
                                            <i class="fa-solid fa-eye"></i>
                                        </a>
                                        <a href="{{ route('admin.content.block-feature.edit', $blockFeature) }}" 
                                           class="text-indigo-600 hover:text-indigo-900 transition-colors duration-200">
                                            <i class="fa-solid fa-edit"></i>
                                        </a>
                                        <form method="POST" action="{{ route('admin.content.block-feature.destroy', $blockFeature) }}" 
                                              class="inline" onsubmit="return confirm('Are you sure you want to delete this feature block?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-900 transition-colors duration-200">
                                                <i class="fa-solid fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            {{-- Pagination --}}
            @if($featureBlocks->hasPages())
                <div class="px-6 py-4 border-t border-gray-200">
                    {{ $featureBlocks->links() }}
                </div>
            @endif
        @else
            <div class="text-center py-12">
                <div class="w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fa-solid fa-th text-gray-400 text-3xl"></i>
                </div>
                <h3 class="text-lg font-medium text-gray-900 mb-2">No feature blocks found</h3>
                <p class="text-gray-500 mb-6">Get started by creating your first feature block section.</p>
                <a href="{{ route('admin.content.block-feature.create') }}"
                   class="inline-flex items-center px-4 py-2 bg-primary text-white rounded-lg hover:bg-primary/80 transition-colors duration-200">
                    <i class="fa-solid fa-plus mr-2"></i>
                    Create Feature Block
                </a>
            </div>
        @endif
    </div>
</div>
</x-layouts.admin>
