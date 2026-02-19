<x-layouts.admin title="Module List Widget Details">
<div class="space-y-6">
    {{-- Header --}}
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">{{ $moduleListWidget->title ?? 'Untitled Module List Widget' }}</h1>
            <p class="text-gray-600">View module list widget details</p>
        </div>
        <div class="flex items-center space-x-3">
            <a href="{{ route('admin.content.module-list-widgets.edit', $moduleListWidget) }}"
               class="bg-primary text-white px-4 py-2 rounded-lg hover:bg-primary/80 transition-colors duration-200">
                <i class="fa-solid fa-edit mr-2"></i>
                Edit
            </a>
            <a href="{{ route('admin.content.module-list-widgets.index') }}"
               class="bg-gray-600 text-white px-4 py-2 rounded-lg hover:bg-gray-700 transition-colors duration-200">
                <i class="fa-solid fa-arrow-left mr-2"></i>
                Back to List
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        {{-- Main Content --}}
        <div class="lg:col-span-2 space-y-6">
            {{-- Basic Information --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-200">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900">Basic Information</h3>
                </div>
                <div class="p-6 space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Title</label>
                        <p class="text-gray-900">{{ $moduleListWidget->title ?? 'No title' }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                        <p class="text-gray-900">{{ $moduleListWidget->description ?? 'No description' }}</p>
                    </div>
                </div>
            </div>

            {{-- Modules Preview --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-200">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900">Modules Preview</h3>
                    <p class="text-sm text-gray-600 mt-1">This is how the modules will appear on the frontend</p>
                </div>
                <div class="p-6">
                    @if($moduleListWidget->modules && count($moduleListWidget->modules) > 0)
                        <div class="columns-1 md:columns-2 lg:columns-3 gap-8">
                            @foreach($moduleListWidget->modules as $module)
                                <div class="break-inside-avoid p-4">
                                    <h3 class="text-primary mb-3">
                                        <a href="javascript:void(0)" class="hover:text-secondary transition-colors">
                                            {{ $module['name'] ?? 'Unnamed Module' }}
                                        </a>
                                    </h3>
                                    <ul class="list-inside space-y-2">
                                        @if(isset($module['items']) && is_array($module['items']))
                                            @foreach($module['items'] as $item)
                                                <li>
                                                    <a href="javascript:void(0)" class="hover:text-secondary transition-colors">
                                                        {{ $item }}
                                                    </a>
                                                </li>
                                            @endforeach
                                        @else
                                            <li class="text-gray-500 italic">No items</li>
                                        @endif
                                    </ul>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-8">
                            <div class="mx-auto h-12 w-12 text-gray-400">
                                <i class="fa-solid fa-cube text-3xl"></i>
                            </div>
                            <h3 class="mt-2 text-sm font-medium text-gray-900">No modules configured</h3>
                            <p class="mt-1 text-sm text-gray-500">This widget doesn't have any modules configured yet.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        {{-- Sidebar --}}
        <div class="space-y-6">
            {{-- Status & Order --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-200">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900">Status & Order</h3>
                </div>
                <div class="p-6 space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                        <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full {{ $moduleListWidget->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                            {{ $moduleListWidget->is_active ? 'Active' : 'Inactive' }}
                        </span>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Sort Order</label>
                        <p class="text-gray-900">{{ $moduleListWidget->sort_order }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Created</label>
                        <p class="text-gray-900">{{ $moduleListWidget->created_at->format('M d, Y H:i') }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Last Updated</label>
                        <p class="text-gray-900">{{ $moduleListWidget->updated_at->format('M d, Y H:i') }}</p>
                    </div>
                </div>
            </div>

            {{-- Statistics --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-200">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900">Statistics</h3>
                </div>
                <div class="p-6 space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Total Modules</label>
                        <p class="text-2xl font-bold text-primary">{{ count($moduleListWidget->modules ?? []) }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Total Items</label>
                        <p class="text-2xl font-bold text-primary">
                            {{ collect($moduleListWidget->modules ?? [])->sum(function($module) { 
                                return count($module['items'] ?? []); 
                            }) }}
                        </p>
                    </div>
                </div>
            </div>

            {{-- Actions --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-200">
                <div class="p-6">
                    <div class="space-y-3">
                        <a href="{{ route('admin.content.module-list-widgets.edit', $moduleListWidget) }}"
                           class="w-full bg-primary text-white px-4 py-2 rounded-lg hover:bg-primary/80 transition-colors duration-200 text-center block">
                            <i class="fa-solid fa-edit mr-2"></i>
                            Edit Widget
                        </a>
                        <button onclick="toggleActive({{ $moduleListWidget->id }})"
                                class="w-full bg-{{ $moduleListWidget->is_active ? 'yellow' : 'green' }}-600 text-white px-4 py-2 rounded-lg hover:bg-{{ $moduleListWidget->is_active ? 'yellow' : 'green' }}-700 transition-colors duration-200">
                            <i class="fa-solid fa-{{ $moduleListWidget->is_active ? 'pause' : 'play' }} mr-2"></i>
                            {{ $moduleListWidget->is_active ? 'Deactivate' : 'Activate' }}
                        </button>
                        <form action="{{ route('admin.content.module-list-widgets.destroy', $moduleListWidget) }}" 
                              method="POST" 
                              onsubmit="return confirm('Are you sure you want to delete this module list widget?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="w-full bg-red-600 text-white px-4 py-2 rounded-lg hover:bg-red-700 transition-colors duration-200">
                                <i class="fa-solid fa-trash mr-2"></i>
                                Delete Widget
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
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
