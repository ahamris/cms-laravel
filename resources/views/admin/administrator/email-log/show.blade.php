<x-layouts.admin title="Email Log Details">
<div class="space-y-6">
    {{-- Header --}}
    <div class="flex items-center justify-between">
        <div class="flex items-center gap-3">
            <div class="w-12 h-12 bg-primary rounded-md flex items-center justify-center">
                <i class="fa-solid fa-envelope text-white text-base"></i>
            </div>
            <div class="flex flex-col gap-1">
                <h2>Email Log Details</h2>
                <p>View complete email information</p>
            </div>
        </div>
        <a href="{{ route('admin.administrator.email-logs.index') }}"
           class="px-5 py-2 rounded-md bg-white border border-gray-200 text-gray-700 text-sm flex items-center space-x-2">
            <i class="fa-solid fa-arrow-left"></i>
            <span>Back to List</span>
        </a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        {{-- Main Content --}}
        <div class="lg:col-span-2 space-y-6">
            {{-- Status & Header Info --}}
            <div class="bg-gray-50/50 rounded-md border border-gray-200">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-base font-semibold text-gray-900">Email Status</h3>
                </div>
                <div class="p-6">
                    <div class="flex items-center justify-between mb-4">
                        <div class="flex items-center space-x-3">
                            @if($emailLog->status === 'sent')
                                <span class="px-3 py-1.5 text-xs font-semibold rounded-full bg-green-100 text-green-800 flex items-center">
                                    <i class="fas fa-check-circle mr-1.5"></i>Sent Successfully
                                </span>
                                @if($emailLog->sent_at)
                                    <span class="text-xs text-gray-600">
                                        {{ format_localized_datetime($emailLog->sent_at) }}
                                    </span>
                                @endif
                            @elseif($emailLog->status === 'failed')
                                <span class="px-3 py-1.5 text-xs font-semibold rounded-full bg-red-100 text-red-800 flex items-center">
                                    <i class="fas fa-times-circle mr-1.5"></i>Failed
                                </span>
                                @if($emailLog->failed_at)
                                    <span class="text-xs text-gray-600">
                                        {{ format_localized_datetime($emailLog->failed_at) }}
                                    </span>
                                @endif
                            @else
                                <span class="px-3 py-1.5 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800 flex items-center">
                                    <i class="fas fa-clock mr-1.5"></i>Pending
                                </span>
                            @endif
                        </div>
                        @if($emailLog->subject)
                            <div class="text-right">
                                <p class="text-xs text-gray-500">Subject</p>
                                <p class="text-xs font-medium text-gray-900 max-w-md">{{ $emailLog->subject }}</p>
                            </div>
                        @endif
                    </div>

                    @if($emailLog->error_message)
                        <div class="mt-4 p-3 bg-red-50 border border-red-200 rounded-md">
                            <h3 class="text-xs font-semibold text-red-800 mb-1">Error Message:</h3>
                            <p class="text-xs text-red-700 font-mono">{{ $emailLog->error_message }}</p>
                        </div>
                    @endif
                </div>
            </div>

            {{-- Email Information --}}
            <div class="bg-gray-50/50 rounded-md border border-gray-200">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-base font-semibold text-gray-900">Email Information</h3>
                </div>
                <div class="p-6 space-y-4">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-medium text-gray-700 mb-1">To</label>
                            <p class="text-sm text-gray-900">{{ $emailLog->to_email }}</p>
                            @if($emailLog->to_name)
                                <p class="text-xs text-gray-600 mt-1">{{ $emailLog->to_name }}</p>
                            @endif
                        </div>

                        <div>
                            <label class="block text-xs font-medium text-gray-700 mb-1">From</label>
                            <p class="text-sm text-gray-900">{{ $emailLog->from_email }}</p>
                            @if($emailLog->from_name)
                                <p class="text-xs text-gray-600 mt-1">{{ $emailLog->from_name }}</p>
                            @endif
                        </div>
                    </div>

                    @if($emailLog->cc || $emailLog->bcc)
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 pt-4 border-t border-gray-200">
                            @if($emailLog->cc)
                                <div>
                                    <label class="block text-xs font-medium text-gray-700 mb-1">CC</label>
                                    <p class="text-xs text-gray-900">{{ $emailLog->cc }}</p>
                                </div>
                            @endif

                            @if($emailLog->bcc)
                                <div>
                                    <label class="block text-xs font-medium text-gray-700 mb-1">BCC</label>
                                    <p class="text-xs text-gray-900">{{ $emailLog->bcc }}</p>
                                </div>
                            @endif
                        </div>
                    @endif
                </div>
            </div>

            {{-- Email Body --}}
            <div class="bg-gray-50/50 rounded-md border border-gray-200">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-base font-semibold text-gray-900">Email Content</h3>
                </div>
                <div class="p-6">
                    @if($emailLog->body_html)
                        <div class="border border-gray-200 rounded-md p-4 bg-white overflow-auto" style="max-height: 700px;">
                            <div class="prose prose-sm max-w-none">
                                {!! $emailLog->body_html !!}
                            </div>
                        </div>
                    @elseif($emailLog->body_text)
                        <div class="border border-gray-200 rounded-md p-4 bg-white overflow-auto whitespace-pre-wrap font-mono text-xs" style="max-height: 700px;">
                            {{ $emailLog->body_text }}
                        </div>
                    @else
                        <div class="text-center py-8">
                            <i class="fas fa-inbox text-3xl text-gray-300 mb-2"></i>
                            <p class="text-xs text-gray-500">No email body content available</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        {{-- Sidebar --}}
        <div class="space-y-6">
            {{-- Technical Details --}}
            <div class="bg-gray-50/50 rounded-md border border-gray-200">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-base font-semibold text-gray-900">Technical Details</h3>
                </div>
                <div class="p-6 space-y-4">
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-1">Email Type</label>
                        <p class="text-sm text-gray-900">
                            {{ $emailLog->mail_class ? class_basename($emailLog->mail_class) : 'N/A' }}
                        </p>
                    </div>

                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-1">Created</label>
                        <p class="text-sm text-gray-900">{{ format_localized_datetime($emailLog->created_at) }}</p>
                    </div>

                    @if($emailLog->sent_at)
                        <div>
                            <label class="block text-xs font-medium text-gray-700 mb-1">Sent At</label>
                            <p class="text-sm text-gray-900">{{ format_localized_datetime($emailLog->sent_at) }}</p>
                        </div>
                    @endif

                    @if($emailLog->failed_at)
                        <div>
                            <label class="block text-xs font-medium text-gray-700 mb-1">Failed At</label>
                            <p class="text-sm text-gray-900">{{ format_localized_datetime($emailLog->failed_at) }}</p>
                        </div>
                    @endif
                </div>
            </div>

            {{-- Related Model --}}
            @if($emailLog->related)
                <div class="bg-gray-50/50 rounded-md border border-gray-200">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-base font-semibold text-gray-900">Related Record</h3>
                    </div>
                    <div class="p-6 space-y-4">
                        <div>
                            <label class="block text-xs font-medium text-gray-700 mb-1">Type</label>
                            <p class="text-sm text-gray-900">{{ class_basename($emailLog->related_type) }}</p>
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-700 mb-1">ID</label>
                            <p class="text-sm text-gray-900 font-mono">{{ $emailLog->related_id }}</p>
                        </div>
                    </div>
                </div>
            @endif

            {{-- Metadata --}}
            @if($emailLog->metadata)
                <div class="bg-gray-50/50 rounded-md border border-gray-200">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-base font-semibold text-gray-900">Additional Data</h3>
                    </div>
                    <div class="p-6 space-y-4">
                        @foreach($emailLog->metadata as $key => $value)
                            <div>
                                <label class="block text-xs font-medium text-gray-700 mb-1">
                                    {{ ucwords(str_replace('_', ' ', $key)) }}
                                </label>
                                <p class="text-xs text-gray-900">
                                    {{ is_bool($value) ? ($value ? 'Yes' : 'No') : (is_array($value) ? json_encode($value, JSON_PRETTY_PRINT) : $value) }}
                                </p>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            {{-- Actions --}}
            <div class="bg-gray-50/50 rounded-md border border-gray-200">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-base font-semibold text-gray-900">Actions</h3>
                </div>
                <div class="p-6">
                    <form action="{{ route('admin.administrator.email-logs.destroy', $emailLog) }}"
                          method="POST"
                          onsubmit="return confirm('Are you sure you want to delete this email log?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit"
                                class="w-full bg-red-600 text-white px-4 py-2 rounded-md text-sm">
                            <i class="fa-solid fa-trash mr-2"></i>
                            Delete Log
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
</x-layouts.admin>
