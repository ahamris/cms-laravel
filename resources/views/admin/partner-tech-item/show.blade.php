<x-layouts.admin title="{{ $partnerTechItem->name }}">
    <div class="flex items-center justify-between mb-6">
        <div class="flex items-center gap-3">
            <div class="w-12 h-12 bg-primary rounded-md flex items-center justify-center">
                <i class="fa-solid fa-handshake text-white text-base"></i>
            </div>
            <div class="flex flex-col gap-1">
                <h2>{{ $partnerTechItem->name }}</h2>
                <p>
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $partnerTechItem->type === 0 ? 'bg-blue-100 text-blue-800' : 'bg-purple-100 text-purple-800' }}">
                        {{ $partnerTechItem->type === 0 ? 'Partner' : 'Tech Stack' }}
                    </span>
                    — {{ count($partnerTechItem->data ?? []) }} link item(s)
                </p>
            </div>
        </div>
        <div class="flex items-center gap-2">
            <a href="{{ route('admin.partner-tech-item.edit', $partnerTechItem) }}" class="px-5 py-2 rounded-md bg-primary text-white text-sm hover:bg-primary/80 flex items-center gap-2"><i class="fa fa-edit"></i> Edit</a>
            <a href="{{ route('admin.partner-tech-item.index') }}" class="px-5 py-2 rounded-md bg-white border border-gray-200 text-gray-700 text-sm hover:bg-gray-50 flex items-center gap-2"><i class="fa fa-arrow-left"></i> Back</a>
        </div>
    </div>

    <div class="space-y-4">
        @if($partnerTechItem->title)
        <div class="bg-gray-50/50 rounded-md border border-gray-200 p-4">
            <span class="text-gray-700">Title: {{ $partnerTechItem->title }}</span>
        </div>
        @endif
        @if($partnerTechItem->description)
        <div class="bg-gray-50/50 rounded-md border border-gray-200 p-6">
            <h3 class="font-semibold text-gray-900 mb-2">Description</h3>
            <p class="text-gray-700">{{ $partnerTechItem->description }}</p>
        </div>
        @endif
        <div class="bg-gray-50/50 rounded-md border border-gray-200 p-6">
            <h3 class="font-semibold text-gray-900 mb-3">Link items (data)</h3>
            @php $resolved = $partnerTechItem->data_items_resolved; @endphp
            @if(count($resolved))
                <ul class="space-y-4">
                    @foreach($resolved as $idx => $item)
                        <li class="flex flex-wrap gap-4 items-start p-4 bg-white border border-gray-200 rounded-lg">
                            @if(!empty($item['image_url']))
                                <div class="flex-shrink-0 w-20 h-20 rounded-md overflow-hidden border border-gray-200 bg-gray-50">
                                    <img src="{{ $item['image_url'] }}" alt="" class="w-full h-full object-contain" loading="lazy">
                                </div>
                            @endif
                            <div class="flex-1 min-w-0 space-y-1">
                                <div class="flex flex-wrap items-center gap-2">
                                    <span class="font-medium text-gray-500">#{{ $item['sort_order'] }}</span>
                                    <span class="px-1.5 py-0.5 rounded text-xs {{ ($item['link_type'] ?? 'external') == 'static' ? 'bg-amber-100 text-amber-800' : 'bg-gray-100 text-gray-700' }}">{{ $item['link_type'] ?? 'external' }}</span>
                                </div>
                                <p class="text-sm text-gray-600 break-all"><span class="font-medium text-gray-500">Link:</span> {{ $item['link'] ?? '—' }}</p>
                                @if(!empty($item['image']))
                                    <p class="text-xs text-gray-500 break-all"><span class="font-medium text-gray-500">Image path:</span> {{ $item['image'] }}</p>
                                @endif
                                @if($item['url'] ?? null)
                                    <a href="{{ $item['url'] }}" target="_blank" rel="noopener" class="inline-flex items-center gap-1 text-sm text-primary hover:underline"><i class="fa-solid fa-external-link-alt text-xs"></i> Open URL</a>
                                @endif
                            </div>
                        </li>
                    @endforeach
                </ul>
            @else
                <p class="text-gray-500">No link items.</p>
            @endif
        </div>
        @if($partnerTechItem->banner)
        <div class="bg-gray-50/50 rounded-md border border-gray-200 p-6">
            <h3 class="font-semibold text-gray-900 mb-3">Banner</h3>
            <div class="flex flex-wrap gap-4 items-start">
                <div class="rounded-lg overflow-hidden border border-gray-200 bg-white max-w-xs">
                    <img src="{{ $partnerTechItem->banner_url }}" alt="Banner" class="max-h-48 w-auto object-contain" loading="lazy">
                </div>
                <p class="text-sm text-gray-500 break-all self-center">Path: {{ $partnerTechItem->banner }}</p>
            </div>
        </div>
        @endif
    </div>
</x-layouts.admin>
