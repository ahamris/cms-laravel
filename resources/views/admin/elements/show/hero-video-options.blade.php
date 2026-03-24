@php
    $o = $element->options ?? [];
    $videoPath = $o['video_path'] ?? null;
    $videoUrl = $videoPath ? asset('storage/' . $videoPath) : null;
@endphp
<dl class="grid grid-cols-1 sm:grid-cols-2 gap-3 text-sm">
    <div>
        <dt class="font-medium text-gray-500">Video file</dt>
        <dd class="text-gray-900 break-all">{{ $videoPath ?? '—' }}</dd>
    </div>
    <div>
        <dt class="font-medium text-gray-500">Primary button text</dt>
        <dd class="text-gray-900">{{ $o['primary_button_text'] ?? '—' }}</dd>
    </div>
    <div>
        <dt class="font-medium text-gray-500">Primary button URL</dt>
        <dd class="text-gray-900 break-all">{{ $o['primary_button_url'] ?? '—' }}</dd>
    </div>
    <div>
        <dt class="font-medium text-gray-500">Secondary button text</dt>
        <dd class="text-gray-900">{{ $o['secondary_button_text'] ?? '—' }}</dd>
    </div>
    <div class="sm:col-span-2">
        <dt class="font-medium text-gray-500">Secondary button URL</dt>
        <dd class="text-gray-900 break-all">{{ $o['secondary_button_url'] ?? '—' }}</dd>
    </div>
</dl>
@if($videoUrl)
    <div class="mt-4">
        <video controls class="w-full max-w-2xl rounded-lg border border-gray-200">
            <source src="{{ $videoUrl }}">
            Your browser does not support the video tag.
        </video>
    </div>
@endif
