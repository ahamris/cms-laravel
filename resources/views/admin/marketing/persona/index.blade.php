<x-layouts.admin title="Marketing Personas">
<div class="space-y-6">
    {{-- Header --}}
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Marketing Personas</h1>
            <p class="text-gray-600">Manage customer personas for targeted content strategy</p>
        </div>
        <a href="{{ route('admin.marketing.persona.create') }}"
           class="bg-primary text-white px-4 py-2 rounded-lg hover:bg-primary/80 transition-colors duration-200">
            <i class="fa-solid fa-plus mr-2"></i>
            Create New Persona
        </a>
    </div>

    {{-- Personas Table --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        @if($personas->count() > 0)
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50 border-b border-gray-200">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Persona</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Demographics</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Pain Points</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Order</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($personas as $persona)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4">
                                    <div class="flex items-center">
                                        @if($persona->avatar_image)
                                            <img class="h-10 w-10 rounded-full mr-3" src="{{ asset('storage/' . $persona->avatar_image) }}" alt="{{ $persona->name }}">
                                        @else
                                            <div class="h-10 w-10 rounded-full bg-primary/10 flex items-center justify-center mr-3">
                                                <i class="fa-solid fa-user text-primary"></i>
                                            </div>
                                        @endif
                                        <div>
                                            <div class="text-sm font-medium text-gray-900">{{ $persona->name }}</div>
                                            <div class="text-sm text-gray-500">{{ $persona->slug }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    @if($persona->demographics)
                                        <div class="text-sm text-gray-900">
                                            @if(isset($persona->demographics['age_range']))
                                                <span class="block">Age: {{ $persona->demographics['age_range'] }}</span>
                                            @endif
                                            @if(isset($persona->demographics['company_size']))
                                                <span class="block">Size: {{ $persona->demographics['company_size'] }}</span>
                                            @endif
                                        </div>
                                    @else
                                        <span class="text-gray-400 text-sm">No demographics</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4">
                                    @if($persona->pain_points && count($persona->pain_points) > 0)
                                        <div class="text-sm text-gray-900">
                                            {{ count($persona->pain_points) }} pain points
                                        </div>
                                    @else
                                        <span class="text-gray-400 text-sm">No pain points</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $persona->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                        {{ $persona->is_active ? 'Active' : 'Inactive' }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-900">
                                    {{ $persona->sort_order }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <div class="flex items-center justify-end space-x-2">
                                        <a href="{{ route('admin.marketing.persona.show', $persona) }}"
                                           class="text-blue-600 hover:text-blue-900"
                                           title="View">
                                            <i class="fa-solid fa-eye"></i>
                                        </a>
                                        <a href="{{ route('admin.marketing.persona.edit', $persona) }}"
                                           class="text-blue-600 hover:text-blue-900"
                                           title="Edit">
                                            <i class="fa-solid fa-edit"></i>
                                        </a>
                                        <button onclick="deletePersona({{ $persona->id }})"
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
                <i class="fa-solid fa-user-friends text-4xl text-gray-400 mb-4"></i>
                <p class="text-gray-600">No marketing personas found. Create your first persona!</p>
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
                <h3 class="text-lg font-semibold text-gray-900">Delete Marketing Persona</h3>
            </div>
            <p class="text-gray-600 mb-6">
                Are you sure you want to delete this marketing persona? This action cannot be undone.
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
function deletePersona(personaId) {
    document.getElementById('deleteForm').action = `/admin/marketing/persona/${personaId}`;
    document.getElementById('deleteModal').classList.remove('hidden');
}
</script>
</x-layouts.admin>
