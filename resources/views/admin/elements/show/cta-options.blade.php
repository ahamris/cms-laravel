@php $o = $element->options ?? []; @endphp
<dl class="grid grid-cols-1 sm:grid-cols-2 gap-3 text-sm">
    <div><dt class="font-medium text-gray-500">Button text</dt><dd class="text-gray-900">{{ $o['button_text'] ?? '—' }}</dd></div>
    <div><dt class="font-medium text-gray-500">Button URL</dt><dd class="text-gray-900 break-all">{{ $o['button_url'] ?? '—' }}</dd></div>
    <div><dt class="font-medium text-gray-500">Button style</dt><dd class="text-gray-900">{{ $o['button_style'] ?? '—' }}</dd></div>
    <div><dt class="font-medium text-gray-500">Background</dt><dd class="text-gray-900">{{ $o['background'] ?? '—' }}</dd></div>
    <div><dt class="font-medium text-gray-500">Alignment</dt><dd class="text-gray-900">{{ $o['alignment'] ?? '—' }}</dd></div>
</dl>
