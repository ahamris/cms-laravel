<x-layouts.admin title="Page Block Presets">
<div class="space-y-6">
    {{-- Header --}}
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Page Block Presets</h1>
            <p class="text-gray-600">Manage saved block configurations for showcase pages</p>
        </div>
        <a href="{{ route('admin.content.page-block-preset.create') }}" 
           class="bg-primary text-white px-4 py-2 rounded-lg hover:bg-primary/80 transition-colors duration-200 flex items-center space-x-2">
            <i class="fa-solid fa-plus"></i>
            <span>Add Preset</span>
        </a>
    </div>

    {{-- Success/Error Messages --}}
    @if(session('success'))
        <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg">
            <i class="fa-solid fa-check-circle mr-2"></i>
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg">
            <i class="fa-solid fa-exclamation-circle mr-2"></i>
            {{ session('error') }}
        </div>
    @endif

    {{-- Presets Table --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-200">
        @if($presets->count() > 0)
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50 border-b border-gray-200">
                        <tr>
                            <th class="text-left py-3 px-4 font-semibold text-gray-700">Name</th>
                            <th class="text-left py-3 px-4 font-semibold text-gray-700">Type</th>
                            <th class="text-left py-3 px-4 font-semibold text-gray-700">Description</th>
                            <th class="text-left py-3 px-4 font-semibold text-gray-700">Blocks</th>
                            <th class="text-left py-3 px-4 font-semibold text-gray-700">Created By</th>
                            <th class="text-left py-3 px-4 font-semibold text-gray-700">Status</th>
                            <th class="text-left py-3 px-4 font-semibold text-gray-700">Created</th>
                            <th class="text-left py-3 px-4 font-semibold text-gray-700">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @foreach($presets as $preset)
                            <tr class="hover:bg-gray-50 transition-colors duration-200">
                                <td class="py-3 px-4">
                                    <div class="font-medium text-gray-900">{{ $preset->name }}</div>
                                </td>
                                <td class="py-3 px-4">
                                    @if($preset->type === 'header')
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                            <i class="fa-solid fa-heading mr-1"></i>
                                            Header
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                            <i class="fa-solid fa-cube mr-1"></i>
                                            Body
                                        </span>
                                    @endif
                                </td>
                                <td class="py-3 px-4">
                                    <div class="text-gray-600 max-w-xs truncate">
                                        {{ $preset->description ?? '—' }}
                                    </div>
                                </td>
                                <td class="py-3 px-4">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                        {{ count($preset->blocks ?? []) }} block(s)
                                    </span>
                                </td>
                                <td class="py-3 px-4">
                                    <div class="text-sm text-gray-600">
                                        {{ $preset->creator->name ?? 'System' }}
                                    </div>
                                </td>
                                <td class="py-3 px-4">
                                    @if($preset->is_active)
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                            Active
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                            Inactive
                                        </span>
                                    @endif
                                </td>
                                <td class="py-3 px-4">
                                    <span class="text-sm text-gray-600">
                                        {{ $preset->created_at->format('M d, Y') }}
                                    </span>
                                </td>
                                <td class="py-3 px-4">
                                    <div class="flex items-center space-x-2">
                                        <a href="{{ route('admin.content.page-block-preset.edit', $preset) }}" 
                                           class="text-gray-600 hover:text-blue-600 transition-colors duration-200" 
                                           title="Edit">
                                            <i class="fa-solid fa-edit"></i>
                                        </a>
                                        <form action="{{ route('admin.content.page-block-preset.destroy', $preset) }}" 
                                              method="POST" 
                                              class="inline"
                                              onsubmit="return confirm('Are you sure you want to delete this preset?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" 
                                                    class="text-gray-600 hover:text-red-600 transition-colors duration-200" 
                                                    title="Delete">
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
        @else
            <div class="text-center py-12">
                <i class="fa-solid fa-bookmark text-4xl text-gray-300 mb-4"></i>
                <h3 class="text-lg font-medium text-gray-900 mb-2">No presets yet</h3>
                <p class="text-gray-600 mb-4">Get started by creating your first preset.</p>
                <a href="{{ route('admin.content.page-block-preset.create') }}" 
                   class="bg-primary text-white px-4 py-2 rounded-lg hover:bg-primary/80 transition-colors duration-200 inline-flex items-center space-x-2">
                    <i class="fa-solid fa-plus"></i>
                    <span>Add Preset</span>
                </a>
            </div>
        @endif
    </div>
</div>
</x-layouts.admin>

