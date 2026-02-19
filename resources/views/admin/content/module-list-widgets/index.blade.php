<x-layouts.admin title="Module List Widgets">
<div class="space-y-6">
    {{-- Header --}}
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Module List Widgets</h1>
            <p class="text-gray-600">Manage module list widgets for displaying module information</p>
        </div>
        <a href="{{ route('admin.content.module-list-widgets.create') }}"
           class="bg-primary text-white px-4 py-2 rounded-lg hover:bg-primary/80 transition-colors duration-200">
            <i class="fa-solid fa-plus mr-2"></i>
            Create New Module List Widget
        </a>
    </div>

    {{-- Module List Widgets List --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        @if($moduleListWidgets->count() > 0)
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Title
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Modules Count
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Status
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Sort Order
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Created
                            </th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Actions
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($moduleListWidgets as $moduleListWidget)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                    {{ $moduleListWidget->title ?? 'No title' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ count($moduleListWidget->modules) }} modules
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full {{ $moduleListWidget->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                        {{ $moduleListWidget->is_active ? 'Active' : 'Inactive' }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $moduleListWidget->sort_order }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $moduleListWidget->created_at->format('M d, Y') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <div class="flex items-center justify-end space-x-2">
                                        <a href="{{ route('admin.content.module-list-widgets.show', $moduleListWidget) }}"
                                           class="text-blue-600 hover:text-blue-900">
                                            <i class="fa-solid fa-eye"></i>
                                        </a>
                                        <a href="{{ route('admin.content.module-list-widgets.edit', $moduleListWidget) }}"
                                           class="text-indigo-600 hover:text-indigo-900">
                                            <i class="fa-solid fa-edit"></i>
                                        </a>
                                        <button onclick="toggleActive({{ $moduleListWidget->id }})"
                                                class="text-{{ $moduleListWidget->is_active ? 'yellow' : 'green' }}-600 hover:text-{{ $moduleListWidget->is_active ? 'yellow' : 'green' }}-900">
                                            <i class="fa-solid fa-{{ $moduleListWidget->is_active ? 'pause' : 'play' }}"></i>
                                        </button>
                                        <form action="{{ route('admin.content.module-list-widgets.destroy', $moduleListWidget) }}" 
                                              method="POST" class="inline" 
                                              onsubmit="return confirm('Are you sure you want to delete this module list widget?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-900">
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
            <div class="px-6 py-3 border-t border-gray-200">
                {{ $moduleListWidgets->links() }}
            </div>
        @else
            <div class="text-center py-12">
                <div class="mx-auto h-12 w-12 text-gray-400">
                    <i class="fa-solid fa-cube text-3xl"></i>
                </div>
                <h3 class="mt-2 text-sm font-medium text-gray-900">No module list widgets</h3>
                <p class="mt-1 text-sm text-gray-500">Get started by creating a new module list widget.</p>
                <div class="mt-6">
                    <a href="{{ route('admin.content.module-list-widgets.create') }}"
                       class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-primary hover:bg-primary/80 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary">
                        <i class="fa-solid fa-plus mr-2"></i>
                        Create New Module List Widget
                    </a>
                </div>
            </div>
        @endif
    </div>
</div>

<script>
function toggleActive(id) {
    fetch(`/admin/widgets/module-list-widgets/${id}/toggle-active`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Content-Type': 'application/json',
        },
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            location.reload();
        }
    })
    .catch(error => {
        console.error('Error:', error);
    });
}
</script>
</x-layouts.admin>
