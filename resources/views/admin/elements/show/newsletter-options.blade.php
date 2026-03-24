@php $o = $element->options ?? []; @endphp
<dl class="grid grid-cols-1 sm:grid-cols-2 gap-3 text-sm">
    <div>
        <dt class="font-medium text-gray-500">Email placeholder</dt>
        <dd class="text-gray-900">{{ $o['email_placeholder'] ?? '—' }}</dd>
    </div>
    <div>
        <dt class="font-medium text-gray-500">Button text</dt>
        <dd class="text-gray-900">{{ $o['button_text'] ?? '—' }}</dd>
    </div>
    <div>
        <dt class="font-medium text-gray-500">Terms text</dt>
        <dd class="text-gray-900">{{ $o['terms_text'] ?? '—' }}</dd>
    </div>
</dl>
