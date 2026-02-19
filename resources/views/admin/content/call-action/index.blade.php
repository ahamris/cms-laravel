<x-layouts.admin title="Call Actions">
<div class="space-y-6">
    {{-- Header --}}
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Call Actions</h1>
            <p class="text-gray-600">Manage call-to-action sections for your website</p>
        </div>
        <a href="{{ route('admin.content.call-action.create') }}"
           class="bg-primary text-white px-4 py-2 rounded-lg hover:bg-primary/80 transition-colors duration-200">
            <i class="fa-solid fa-plus mr-2"></i>
            Add Call Action
        </a>
    </div>

    {{-- Stats Cards --}}
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <div class="flex items-center">
                <div class="p-2 bg-blue-100 rounded-lg">
                    <i class="fa-solid fa-bullhorn text-blue-600 text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Total Call Actions</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $callActions->total() }}</p>
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
                    <p class="text-2xl font-bold text-gray-900">{{ $callActions->where('is_active', true)->count() }}</p>
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
                    <p class="text-2xl font-bold text-gray-900">{{ $callActions->where('is_active', false)->count() }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <div class="flex items-center">
                <div class="p-2 bg-purple-100 rounded-lg">
                    <i class="fa-solid fa-mouse-pointer text-purple-600 text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">With Buttons</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $callActions->filter(function($item) { return !empty($item->primary_button_text) || !empty($item->secondary_button_text); })->count() }}</p>
                </div>
            </div>
        </div>
    </div>

    {{-- Call Actions Table --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-200">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-semibold text-gray-900">All Call Actions</h3>
        </div>
        
        @if($callActions->count() > 0)
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
                        @foreach($callActions as $callAction)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="w-10 h-10 rounded-lg flex items-center justify-center flex-shrink-0" 
                                             style="background-color: {{ $callAction->background_color }}20">
                                            <i class="fa-solid fa-bullhorn" style="color: {{ $callAction->background_color }}"></i>
                                        </div>
                                        <div class="ml-4">
                                            <div class="text-sm font-medium text-gray-900">{{ Str::limit($callAction->title, 50) }}</div>
                                            @if($callAction->content)
                                                <div class="text-sm text-gray-500">{{ Str::limit($callAction->content, 80) }}</div>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                        {{ $callAction->section_identifier }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <form method="POST" action="{{ route('admin.content.call-action.toggle-status', $callAction) }}" class="inline">
                                        @csrf
                                        <button type="submit" class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium transition-colors duration-200 {{ $callAction->is_active ? 'bg-green-100 text-green-800 hover:bg-green-200' : 'bg-red-100 text-red-800 hover:bg-red-200' }}">
                                            <i class="fa-solid {{ $callAction->is_active ? 'fa-check-circle' : 'fa-pause-circle' }} mr-1"></i>
                                            {{ $callAction->is_active ? 'Active' : 'Inactive' }}
                                        </button>
                                    </form>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ $callAction->sort_order }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $callAction->created_at->format('M j, Y') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <div class="flex items-center justify-end space-x-2">
                                        <a href="{{ route('admin.content.call-action.show', $callAction) }}" 
                                           class="text-blue-600 hover:text-blue-900 transition-colors duration-200">
                                            <i class="fa-solid fa-eye"></i>
                                        </a>
                                        <a href="{{ route('admin.content.call-action.edit', $callAction) }}" 
                                           class="text-indigo-600 hover:text-indigo-900 transition-colors duration-200">
                                            <i class="fa-solid fa-edit"></i>
                                        </a>
                                        <form method="POST" action="{{ route('admin.content.call-action.destroy', $callAction) }}" 
                                              class="inline" onsubmit="return confirm('Are you sure you want to delete this call action?')">
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
            @if($callActions->hasPages())
                <div class="px-6 py-4 border-t border-gray-200">
                    {{ $callActions->links() }}
                </div>
            @endif
        @else
            <div class="text-center py-12">
                <div class="w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fa-solid fa-bullhorn text-gray-400 text-3xl"></i>
                </div>
                <h3 class="text-lg font-medium text-gray-900 mb-2">No call actions found</h3>
                <p class="text-gray-500 mb-6">Get started by creating your first call action section.</p>
                <a href="{{ route('admin.content.call-action.create') }}"
                   class="inline-flex items-center px-4 py-2 bg-primary text-white rounded-lg hover:bg-primary/80 transition-colors duration-200">
                    <i class="fa-solid fa-plus mr-2"></i>
                    Create Call Action
                </a>
            </div>
        @endif
    </div>
</div>
</x-layouts.admin>
