@php
    $o = $element->options ?? [];
    $imagePath = $o['image_path'] ?? null;
    $imageUrl = get_image($imagePath);
@endphp
<dl class="grid grid-cols-1 sm:grid-cols-2 gap-3 text-sm">
    <div>
        <dt class="font-medium text-gray-500">Section label</dt>
        <dd class="text-gray-900">{{ $o['section_label'] ?? '—' }}</dd>
    </div>
    <div>
        <dt class="font-medium text-gray-500">Primary button text</dt>
        <dd class="text-gray-900">{{ $o['primary_button_text'] ?? '—' }}</dd>
    </div>
    <div>
        <dt class="font-medium text-gray-500">Primary button URL</dt>
        <dd class="text-gray-900 break-all">{{ $o['primary_button_url'] ?? '—' }}</dd>
    </div>
    <div class="sm:col-span-2">
        <dt class="font-medium text-gray-500">Image path</dt>
        <dd class="text-gray-900 break-all">{{ $imagePath ?? '—' }}</dd>
    </div>
</dl>
<div class="mt-4">
    <img src="{{ $imageUrl }}" alt="Feature image"
         class="max-h-72 rounded border border-gray-200">
</div>
