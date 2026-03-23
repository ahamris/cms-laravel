@php $o = $element->options ?? []; @endphp
<div class="space-y-4 text-sm">
    <div>
        <p class="text-xs font-medium text-gray-500">Label</p>
        <p class="text-gray-900">{{ $o['label'] ?? '—' }}</p>
    </div>
    <div>
        <p class="text-xs font-medium text-gray-500">Titel</p>
        <p class="text-gray-900 font-medium">{{ $o['title'] ?? '—' }}</p>
    </div>
    <div>
        <p class="text-xs font-medium text-gray-500">Beschrijving</p>
        <p class="text-gray-700 whitespace-pre-wrap">{{ $o['description'] ?? '—' }}</p>
    </div>
    @if(!empty($o['cards']) && is_array($o['cards']))
        <ul class="space-y-4 list-none pl-0 mt-4">
            @foreach($o['cards'] as $i => $card)
                <li class="border border-gray-200 rounded-lg p-3 bg-gray-50">
                    <p class="text-xs text-gray-500">Kaart {{ $i + 1 }}</p>
                    <p class="font-medium text-gray-900">{{ $card['label'] ?? '' }}</p>
                    <p class="font-semibold text-gray-800 mt-1">{{ $card['title'] ?? '' }}</p>
                    <p class="text-gray-700 mt-2 whitespace-pre-wrap">{{ $card['description'] ?? '' }}</p>
                    <p class="mt-2 text-sm">
                        <span class="text-gray-500">Knop:</span>
                        <span class="text-primary">{{ $card['button_text'] ?? '—' }}</span>
                        <span class="text-gray-500"> → </span>
                        <span class="break-all">{{ $card['button_link'] ?? '—' }}</span>
                    </p>
                </li>
            @endforeach
        </ul>
    @else
        <p class="text-gray-500">Geen kaarten.</p>
    @endif
</div>
