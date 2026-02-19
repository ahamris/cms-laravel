<x-layouts.admin title="Form Builder">
    {{-- Header --}}
    <div class="mb-8">
        <div class="flex items-center justify-between mb-6">
            <div>
                <h2 class="text-3xl font-bold text-gray-900">Form Builder</h2>
                <p class="text-gray-600 mt-2">Create and manage custom forms for your website</p>
            </div>
            <a href="{{ route('admin.content.form-builder.create') }}"
               class="inline-flex items-center px-6 py-3 bg-purple-600 hover:bg-purple-700 text-white font-semibold rounded-xl transition-all duration-200 shadow-lg hover:shadow-xl">
                <i class="fa fa-plus mr-2"></i>
                Create New Form
            </a>
        </div>
    </div>

    {{-- Forms List --}}
    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
        @if($forms->count() > 0)
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">#</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Form Details</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Identifier</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Submissions</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-4 text-right text-xs font-semibold text-gray-600 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($forms as $form)
                            <tr class="hover:bg-gray-50 transition-colors duration-150">
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $form->id }}
                                </td>
                                <td class="px-6 py-4">
                                    <div>
                                        <div class="font-semibold text-gray-900">{{ $form->title }}</div>
                                        @if($form->description)
                                            <div class="text-sm text-gray-500 mt-1">{{ Str::limit($form->description, 60) }}</div>
                                        @endif
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="bg-blue-100 text-blue-800 px-3 py-1 rounded-full text-xs font-mono font-semibold">
                                        {{ $form->identifier }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <a href="{{ route('admin.content.form-builder.submissions', $form) }}" 
                                       class="inline-flex items-center space-x-2 text-purple-600 hover:text-purple-800 transition-colors">
                                        <span class="bg-purple-100 text-purple-800 px-3 py-1 rounded-full text-sm font-bold">
                                            {{ $form->submissions_count }}
                                        </span>
                                        @if($form->unread_submissions_count > 0)
                                            <span class="bg-red-500 text-white px-2 py-1 rounded-full text-xs font-bold">
                                                {{ $form->unread_submissions_count }} new
                                            </span>
                                        @endif
                                    </a>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($form->is_active)
                                        <span class="bg-green-100 text-green-800 px-3 py-1 rounded-full text-xs font-semibold">
                                            Active
                                        </span>
                                    @else
                                        <span class="bg-gray-100 text-gray-800 px-3 py-1 rounded-full text-xs font-semibold">
                                            Inactive
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <div class="flex items-center justify-end space-x-2">
                                        <a href="{{ route('admin.content.form-builder.show', $form) }}"
                                           class="text-blue-600 hover:text-blue-900 transition-colors"
                                           title="View">
                                            <i class="fa fa-eye"></i>
                                        </a>
                                        <a href="{{ route('admin.content.form-builder.edit', $form) }}"
                                           class="text-purple-600 hover:text-purple-900 transition-colors"
                                           title="Edit">
                                            <i class="fa fa-edit"></i>
                                        </a>
                                        <button type="button"
                                                onclick="deleteForm({{ $form->id }})"
                                                class="text-red-600 hover:text-red-900 transition-colors"
                                                title="Delete">
                                            <i class="fa fa-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            {{-- Pagination --}}
            @if($forms->hasPages())
                <div class="px-6 py-4 border-t border-gray-200">
                    {{ $forms->links() }}
                </div>
            @endif
        @else
            {{-- Empty State --}}
            <div class="text-center py-16 px-6">
                <div class="inline-flex items-center justify-center w-20 h-20 bg-purple-100 rounded-full mb-6">
                    <i class="fa fa-envelope text-4xl text-purple-600"></i>
                </div>
                <h3 class="text-xl font-semibold text-gray-900 mb-2">No Forms Found</h3>
                <p class="text-gray-600 mb-6 max-w-md mx-auto">
                    Get started by creating your first custom form. Build contact forms, surveys, registration forms, and more!
                </p>
                <a href="{{ route('admin.content.form-builder.create') }}"
                   class="inline-flex items-center px-6 py-3 bg-purple-600 hover:bg-purple-700 text-white font-semibold rounded-xl transition-all duration-200">
                    <i class="fa fa-plus mr-2"></i>
                    Create Your First Form
                </a>
            </div>
        @endif
    </div>

    {{-- Delete Modal --}}
    <div id="deleteModal" class="hidden fixed inset-0 bg-gray-600/50 overflow-y-auto h-full w-full z-50">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-2xl bg-white">
            <div class="mt-3">
                <div class="flex items-center justify-center w-12 h-12 mx-auto bg-red-100 rounded-full mb-4">
                    <i class="fa fa-exclamation-triangle text-2xl text-red-600"></i>
                </div>
                <h3 class="text-lg font-semibold text-gray-900 text-center mb-2">Delete Form</h3>
                <p class="text-sm text-gray-600 text-center mb-6">
                    Are you sure you want to delete this form? This will also delete all submissions. This action cannot be undone.
                </p>
                <form id="deleteForm" method="POST" class="space-y-3">
                    @csrf
                    @method('DELETE')
                    <button type="submit"
                            class="w-full px-4 py-3 bg-red-600 hover:bg-red-700 text-white font-semibold rounded-xl transition-all duration-200">
                        Yes, Delete Form
                    </button>
                    <button type="button"
                            onclick="closeDeleteModal()"
                            class="w-full px-4 py-3 bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold rounded-xl transition-all duration-200">
                        Cancel
                    </button>
                </form>
            </div>
        </div>
    </div>

    <script>
    function deleteForm(formId) {
        const modal = document.getElementById('deleteModal');
        const form = document.getElementById('deleteForm');
        form.action = `/admin/content/form-builder/${formId}`;
        modal.classList.remove('hidden');
    }

    function closeDeleteModal() {
        document.getElementById('deleteModal').classList.add('hidden');
    }

    // Close modal on outside click
    document.getElementById('deleteModal')?.addEventListener('click', function(e) {
        if (e.target === this) {
            closeDeleteModal();
        }
    });
</script>
    </script>
</x-layouts.admin>
