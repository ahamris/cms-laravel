@php
    $o = old('options', $element?->options ?? []);
    $cards = $o['cards'] ?? [];
    if (! is_array($cards) || count($cards) === 0) {
        $cards = [['label' => '', 'title' => '', 'description' => '', 'button_text' => '', 'button_link' => '']];
    }
@endphp
<div class="border-t border-gray-200 pt-4 mt-4 space-y-4">
    <h3 class="text-lg font-semibold text-gray-900">Sectie</h3>

    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Label</label>
        <input type="text" name="options[label]" value="{{ $o['label'] ?? '' }}"
               class="w-full border border-gray-300 rounded-lg px-3 py-2 @error('options.label') border-red-500 @enderror"
               placeholder="Bijv. VOOR BELEIDSMAKERS">
        @error('options.label')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
    </div>
    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Titel</label>
        <input type="text" name="options[title]" value="{{ $o['title'] ?? '' }}"
               class="w-full border border-gray-300 rounded-lg px-3 py-2 @error('options.title') border-red-500 @enderror">
        @error('options.title')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
    </div>
    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Beschrijving</label>
        <textarea name="options[description]" rows="4"
                  class="w-full border border-gray-300 rounded-lg px-3 py-2 @error('options.description') border-red-500 @enderror">{{ $o['description'] ?? '' }}</textarea>
        @error('options.description')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
    </div>

    <div>
        <div class="flex items-center justify-between mb-2">
            <span class="text-sm font-medium text-gray-700">Kaarten</span>
            <button type="button" id="cg-add-row" class="text-sm text-primary hover:underline">+ Kaart toevoegen</button>
        </div>
        <div id="cg-items" class="space-y-4">
            @foreach($cards as $idx => $row)
                <div class="cg-item rounded-lg border border-gray-200 p-4 space-y-3 bg-gray-50" data-index="{{ $idx }}">
                    <div class="flex justify-between items-start gap-2">
                        <span class="text-xs font-medium text-gray-500">Kaart {{ $idx + 1 }}</span>
                        <button type="button" class="cg-remove-row text-xs text-red-600 hover:underline">Verwijderen</button>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                        <div>
                            <label class="block text-xs font-medium text-gray-600 mb-1">Label</label>
                            <input type="text" name="options[cards][{{ $idx }}][label]" value="{{ $row['label'] ?? '' }}"
                                   class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm">
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-600 mb-1">Titel</label>
                            <input type="text" name="options[cards][{{ $idx }}][title]" value="{{ $row['title'] ?? '' }}"
                                   class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm">
                        </div>
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-600 mb-1">Beschrijving</label>
                        <textarea name="options[cards][{{ $idx }}][description]" rows="3"
                                  class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm">{{ $row['description'] ?? '' }}</textarea>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                        <div>
                            <label class="block text-xs font-medium text-gray-600 mb-1">Knoptekst</label>
                            <input type="text" name="options[cards][{{ $idx }}][button_text]" value="{{ $row['button_text'] ?? '' }}"
                                   class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm">
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-600 mb-1">Knoplink</label>
                            <input type="text" name="options[cards][{{ $idx }}][button_link]" value="{{ $row['button_link'] ?? '' }}"
                                   placeholder="#"
                                   class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm">
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
        @error('options.cards')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
    </div>
</div>

<template id="cg-item-template">
    <div class="cg-item rounded-lg border border-gray-200 p-4 space-y-3 bg-gray-50" data-index="__INDEX__">
        <div class="flex justify-between items-start gap-2">
            <span class="text-xs font-medium text-gray-500">Nieuwe kaart</span>
            <button type="button" class="cg-remove-row text-xs text-red-600 hover:underline">Verwijderen</button>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
            <div>
                <label class="block text-xs font-medium text-gray-600 mb-1">Label</label>
                <input type="text" name="options[cards][__INDEX__][label]" value=""
                       class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm">
            </div>
            <div>
                <label class="block text-xs font-medium text-gray-600 mb-1">Titel</label>
                <input type="text" name="options[cards][__INDEX__][title]" value=""
                       class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm">
            </div>
        </div>
        <div>
            <label class="block text-xs font-medium text-gray-600 mb-1">Beschrijving</label>
            <textarea name="options[cards][__INDEX__][description]" rows="3"
                      class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm"></textarea>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
            <div>
                <label class="block text-xs font-medium text-gray-600 mb-1">Knoptekst</label>
                <input type="text" name="options[cards][__INDEX__][button_text]" value=""
                       class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm">
            </div>
            <div>
                <label class="block text-xs font-medium text-gray-600 mb-1">Knoplink</label>
                <input type="text" name="options[cards][__INDEX__][button_link]" value=""
                       class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm">
            </div>
        </div>
    </div>
</template>

<script>
(function () {
    const container = document.getElementById('cg-items');
    const addBtn = document.getElementById('cg-add-row');
    const tpl = document.getElementById('cg-item-template');
    if (!container || !addBtn || !tpl) return;

    function nextIndex() {
        const items = container.querySelectorAll('.cg-item');
        let max = -1;
        items.forEach(function (el) {
            const i = parseInt(el.dataset.index, 10);
            if (!isNaN(i) && i > max) max = i;
        });
        return max + 1;
    }

    addBtn.addEventListener('click', function () {
        const idx = nextIndex();
        const html = tpl.innerHTML.replace(/__INDEX__/g, idx);
        const wrap = document.createElement('div');
        wrap.innerHTML = html.trim();
        const node = wrap.firstElementChild;
        container.appendChild(node);
        bindRemove(node);
    });

    function bindRemove(root) {
        root.querySelectorAll('.cg-remove-row').forEach(function (btn) {
            btn.addEventListener('click', function () {
                const items = container.querySelectorAll('.cg-item');
                if (items.length <= 1) return;
                root.remove();
            });
        });
    }

    container.querySelectorAll('.cg-item').forEach(bindRemove);
})();
</script>
