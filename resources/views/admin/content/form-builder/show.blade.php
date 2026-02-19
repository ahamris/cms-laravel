<x-layouts.admin :title="'Form: ' . $formBuilder->title">
    <div class="mb-8">
        <div class="flex items-center justify-between mb-6">
            <div>
                <h2 class="text-3xl font-bold text-gray-900">{{ $formBuilder->title }}</h2>
                <p class="text-gray-600 mt-2">{{ $formBuilder->description ?: 'Form details and recent submissions' }}</p>
            </div>
            <div class="flex items-center gap-3">
                <a href="{{ route('admin.content.form-builder.submissions', $formBuilder) }}"
                   class="inline-flex items-center px-4 py-2 bg-purple-600 hover:bg-purple-700 text-white font-semibold rounded-xl transition-all">
                    <i class="fa fa-list mr-2"></i>
                    All Submissions ({{ $formBuilder->submissions_count }})
                </a>
                <a href="{{ route('admin.content.form-builder.edit', $formBuilder) }}"
                   class="inline-flex items-center px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold rounded-xl transition-all">
                    <i class="fa fa-edit mr-2"></i>
                    Edit
                </a>
                <a href="{{ route('admin.content.form-builder.index') }}"
                   class="inline-flex items-center px-4 py-2 border border-gray-300 hover:bg-gray-50 text-gray-700 font-semibold rounded-xl transition-all">
                    <i class="fa fa-arrow-left mr-2"></i>
                    Back to Forms
                </a>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2 space-y-6">
            <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                    <i class="fa fa-info-circle text-purple-600 mr-2"></i>
                    Form details
                </h3>
                <dl class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Identifier</dt>
                        <dd class="mt-1 text-sm font-mono font-semibold text-gray-900">{{ $formBuilder->identifier }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Status</dt>
                        <dd class="mt-1">
                            @if($formBuilder->is_active)
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">Active</span>
                            @else
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">Inactive</span>
                            @endif
                        </dd>
                    </div>
                    <div class="sm:col-span-2">
                        <dt class="text-sm font-medium text-gray-500">Submit button text</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $formBuilder->submit_button_text ?: 'Submit' }}</dd>
                    </div>
                </dl>
            </div>

            <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                    <i class="fa fa-inbox text-purple-600 mr-2"></i>
                    Recent submissions
                </h3>
                @if($recentSubmissions->count() > 0)
                    <ul class="divide-y divide-gray-200">
                        @foreach($recentSubmissions as $sub)
                            <li class="py-3 flex items-center justify-between">
                                <div class="flex-1 min-w-0">
                                    <a href="{{ route('admin.content.form-builder.view-submission', [$formBuilder, $sub]) }}"
                                       class="text-sm font-medium text-purple-600 hover:text-purple-800 truncate block">
                                        Submission #{{ $sub->id }}
                                    </a>
                                    <p class="text-xs text-gray-500 mt-0.5">{{ $sub->created_at->format('M d, Y H:i') }}</p>
                                </div>
                                @if(!$sub->is_read)
                                    <span class="ml-2 inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-red-100 text-red-800">New</span>
                                @endif
                            </li>
                        @endforeach
                    </ul>
                    <a href="{{ route('admin.content.form-builder.submissions', $formBuilder) }}"
                       class="mt-4 inline-flex items-center text-sm font-medium text-purple-600 hover:text-purple-800">
                        View all submissions
                        <i class="fa fa-arrow-right ml-1"></i>
                    </a>
                @else
                    <p class="text-sm text-gray-500">No submissions yet.</p>
                @endif
            </div>
        </div>
        <div class="space-y-6">
            <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6">
                <h3 class="text-sm font-semibold text-gray-900 mb-3">Quick stats</h3>
                <div class="space-y-2">
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-500">Total submissions</span>
                        <span class="font-semibold text-gray-900">{{ $formBuilder->submissions_count }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-layouts.admin>
