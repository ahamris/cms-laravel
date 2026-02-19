<x-layouts.admin title="Submission #{{ $submission->id }}">
    <div class="mb-8">
        <div class="flex items-center justify-between mb-6">
            <div>
                <h2 class="text-3xl font-bold text-gray-900">Submission #{{ $submission->id }}</h2>
                <p class="text-gray-600 mt-2">Form: {{ $formBuilder->title }} · {{ $submission->created_at->format('M d, Y H:i') }}</p>
            </div>
            <div class="flex items-center gap-3">
                <a href="{{ route('admin.content.form-builder.submissions', $formBuilder) }}"
                   class="inline-flex items-center px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold rounded-xl transition-all">
                    <i class="fa fa-arrow-left mr-2"></i>
                    Back to Submissions
                </a>
                <a href="{{ route('admin.content.form-builder.show', $formBuilder) }}"
                   class="inline-flex items-center px-4 py-2 border border-gray-300 hover:bg-gray-50 text-gray-700 font-semibold rounded-xl transition-all">
                    Form Details
                </a>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2">
            <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                    <i class="fa fa-list-alt text-purple-600 mr-2"></i>
                    Submitted data
                </h3>
                @php $data = $submission->data ?? []; @endphp
                @if(!empty($data))
                    <dl class="space-y-4">
                        @foreach($data as $key => $value)
                            <div>
                                <dt class="text-sm font-medium text-gray-500 capitalize">{{ str_replace(['_', '-'], ' ', $key) }}</dt>
                                <dd class="mt-1 text-sm text-gray-900 break-words">
                                    @if(is_array($value))
                                        <pre class="text-xs bg-gray-50 p-3 rounded-lg overflow-x-auto">{{ json_encode($value, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</pre>
                                    @else
                                        {{ $value }}
                                    @endif
                                </dd>
                            </div>
                        @endforeach
                    </dl>
                @else
                    <p class="text-sm text-gray-500">No data stored for this submission.</p>
                @endif
            </div>
        </div>
        <div class="space-y-6">
            <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6">
                <h3 class="text-sm font-semibold text-gray-900 mb-3">Meta</h3>
                <dl class="space-y-2 text-sm">
                    <div class="flex justify-between">
                        <span class="text-gray-500">Submitted at</span>
                        <span class="text-gray-900">{{ $submission->created_at->format('M d, Y H:i') }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-500">Read</span>
                        <span class="text-gray-900">{{ $submission->is_read ? 'Yes' : 'No' }}</span>
                    </div>
                    @if($submission->ip_address)
                        <div class="flex justify-between">
                            <span class="text-gray-500">IP</span>
                            <span class="text-gray-900 font-mono text-xs">{{ $submission->ip_address }}</span>
                        </div>
                    @endif
                </dl>
            </div>
            <form action="{{ route('admin.content.form-builder.delete-submission', [$formBuilder, $submission]) }}"
                  method="POST"
                  onsubmit="return confirm('Delete this submission?');">
                @csrf
                @method('DELETE')
                <button type="submit"
                        class="w-full px-4 py-2 border border-red-200 text-red-700 hover:bg-red-50 font-medium rounded-xl transition-all">
                    <i class="fa fa-trash mr-2"></i>
                    Delete submission
                </button>
            </form>
        </div>
    </div>
</x-layouts.admin>
