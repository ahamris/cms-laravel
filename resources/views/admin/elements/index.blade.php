<x-layouts.admin :title="$heading">
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">{{ $heading }}</h1>
            <p class="text-gray-600">Strict CRUD for this element type only.</p>
        </div>
        <a href="{{ route($routeBase . '.create') }}"
           class="bg-primary text-white px-4 py-2 rounded-lg hover:bg-primary/80 transition-colors duration-200">
            <i class="fa-solid fa-plus mr-2"></i>Create Item
        </a>
    </div>

    @if($typeHelp)
        <div class="rounded-lg border border-blue-200 bg-blue-50 px-4 py-3 text-sm text-blue-900">
            {{ $typeHelp }}
        </div>
    @endif

    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        @if($elements->count() > 0)
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Title</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($elements as $element)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 text-sm text-gray-900">{{ $element->id }}</td>
                                <td class="px-6 py-4">
                                    <div class="text-sm font-medium text-gray-900">{{ $element->title ?: '-' }}</div>
                                    <div class="text-xs text-gray-500">{{ $element->sub_title ?: '-' }}</div>
                                </td>
                                <td class="px-6 py-4 text-right text-sm">
                                    <div class="inline-flex items-center gap-3">
                                        <a href="{{ route($routeBase . '.show', $element->id) }}" class="text-indigo-600 hover:text-indigo-900">View</a>
                                        <a href="{{ route($routeBase . '.edit', $element->id) }}" class="text-blue-600 hover:text-blue-900">Edit</a>
                                        <form action="{{ route($routeBase . '.destroy', $element->id) }}" method="POST" onsubmit="return confirm('Delete this element?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-900">Delete</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="px-6 py-4 border-t border-gray-200">
                {{ $elements->links() }}
            </div>
        @else
            <div class="text-center py-12 text-gray-600">
                No items found for this type.
            </div>
        @endif
    </div>
</div>
</x-layouts.admin>
