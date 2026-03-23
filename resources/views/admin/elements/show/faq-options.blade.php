@php $o = $element->options ?? []; @endphp
<div class="space-y-3 text-sm">
    <div class="flex gap-6 text-gray-700">
        <span><span class="font-medium text-gray-500">Layout:</span> {{ $o['layout'] ?? '—' }}</span>
        <span><span class="font-medium text-gray-500">Columns:</span> {{ $o['columns'] ?? '—' }}</span>
    </div>
    @if(!empty($o['items']) && is_array($o['items']))
        <ul class="space-y-4 list-none pl-0">
            @foreach($o['items'] as $i => $item)
                <li class="border border-gray-200 rounded-lg p-3 bg-gray-50">
                    <p class="font-medium text-gray-900">{{ $item['question'] ?? '—' }}</p>
                    <p class="mt-2 text-gray-700 whitespace-pre-wrap">{{ $item['answer'] ?? '' }}</p>
                </li>
            @endforeach
        </ul>
    @else
        <p class="text-gray-500">No FAQ items.</p>
    @endif
</div>
