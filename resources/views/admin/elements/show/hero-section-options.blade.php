@php
    $o = $element->options ?? [];
    $imagePath = $o['image_path'] ?? null;
    $imageUrl = get_image($imagePath);
@endphp
<dl class="grid grid-cols-1 sm:grid-cols-2 gap-3 text-sm">
    <div>
        <dt class="font-medium text-gray-500">Variant</dt>
        <dd class="text-gray-900">{{ $o['variant'] ?? '—' }}</dd>
    </div>
    <div>
        <dt class="font-medium text-gray-500">Layout</dt>
        <dd class="text-gray-900">{{ $o['layout'] ?? '—' }}</dd>
    </div>
    <div>
        <dt class="font-medium text-gray-500">Eyebrow</dt>
        <dd class="text-gray-900">{{ $o['eyebrow'] ?? '—' }}</dd>
    </div>
    <div>
        <dt class="font-medium text-gray-500">Media type</dt>
        <dd class="text-gray-900">{{ $o['media_type'] ?? '—' }}</dd>
    </div>
    <div class="sm:col-span-2">
        <dt class="font-medium text-gray-500">Media URL</dt>
        <dd class="text-gray-900 break-all">{{ $o['media_url'] ?? '—' }}</dd>
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
    <div>
        <dt class="font-medium text-gray-500">Secondary button URL</dt>
        <dd class="text-gray-900 break-all">{{ $o['secondary_button_url'] ?? '—' }}</dd>
    </div>
    <div>
        <dt class="font-medium text-gray-500">Background style</dt>
        <dd class="text-gray-900">{{ $o['background_style'] ?? '—' }}</dd>
    </div>
    <div>
        <dt class="font-medium text-gray-500">Text alignment</dt>
        <dd class="text-gray-900">{{ $o['text_alignment'] ?? '—' }}</dd>
    </div>
    <div class="sm:col-span-2">
        <dt class="font-medium text-gray-500">Image path</dt>
        <dd class="text-gray-900 break-all">{{ $imagePath ?? '—' }}</dd>
    </div>
</dl>
<div class="mt-4">
    <img src="{{ $imageUrl }}" alt="Hero image" class="max-h-72 rounded border border-gray-200">
</div>
