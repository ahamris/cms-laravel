<x-layouts.admin :title="'Submissions: ' . $formBuilder->title">
    <div class="mb-8">
        <div class="flex items-center justify-between mb-6">
            <div>
                <h2 class="text-3xl font-bold text-gray-900">Submissions</h2>
                <p class="text-gray-600 mt-2">{{ $formBuilder->title }} ({{ $formBuilder->identifier }})</p>
            </div>
            <div class="flex items-center gap-3">
                <a href="{{ route('admin.content.form-builder.show', $formBuilder) }}"
                   class="inline-flex items-center px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold rounded-xl transition-all">
                    <i class="fa fa-arrow-left mr-2"></i>
                    Back to Form
                </a>
                <a href="{{ route('admin.content.form-builder.index') }}"
                   class="inline-flex items-center px-4 py-2 border border-gray-300 hover:bg-gray-50 text-gray-700 font-semibold rounded-xl transition-all">
                    All Forms
                </a>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
        @if($submissions->count() > 0)
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">#</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Submitted</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Read</th>
                            <th class="px-6 py-4 text-right text-xs font-semibold text-gray-600 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($submissions as $sub)
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 font-medium">{{ $sub->id }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ $sub->created_at->format('M d, Y H:i') }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($sub->is_read)
                                        <span class="text-xs text-gray-500">Yes</span>
                                    @else
                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-red-100 text-red-800">Unread</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <a href="{{ route('admin.content.form-builder.view-submission', [$formBuilder, $sub]) }}"
                                       class="text-purple-600 hover:text-purple-900">View</a>
                                    <form action="{{ route('admin.content.form-builder.delete-submission', [$formBuilder, $sub]) }}"
                                          method="POST"
                                          class="inline-block ml-2"
                                          onsubmit="return confirm('Delete this submission?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-900">Delete</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="px-6 py-4 border-t border-gray-200">
                {{ $submissions->links() }}
            </div>
        @else
            <div class="text-center py-16 px-6">
                <div class="inline-flex items-center justify-center w-20 h-20 bg-purple-100 rounded-full mb-6">
                    <i class="fa fa-inbox text-4xl text-purple-600"></i>
                </div>
                <h3 class="text-xl font-semibold text-gray-900 mb-2">No submissions yet</h3>
                <p class="text-gray-600 mb-6">Submissions for this form will appear here.</p>
                <a href="{{ route('admin.content.form-builder.show', $formBuilder) }}"
                   class="inline-flex items-center px-6 py-3 bg-purple-600 hover:bg-purple-700 text-white font-semibold rounded-xl transition-all">
                    <i class="fa fa-arrow-left mr-2"></i>
                    Back to Form
                </a>
            </div>
        @endif
    </div>
</x-layouts.admin>
