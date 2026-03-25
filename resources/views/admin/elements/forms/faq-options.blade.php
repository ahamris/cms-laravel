@php
    $o = old('options', $element?->options ?? []);
    $items = $o['items'] ?? [];
    if (! is_array($items) || count($items) === 0) {
        $items = [['question' => '', 'answer' => '']];
    }
@endphp
<div class="border-t border-gray-200 pt-4 mt-4 space-y-4">
    <h3 class="text-lg font-semibold text-gray-900">FAQ block</h3>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Layout</label>
            <select name="options[layout]" class="w-full border border-gray-300 rounded-lg px-3 py-2 @error('options.layout') border-red-500 @enderror">
                @foreach(['accordion' => 'Accordion', 'tabs' => 'Tabs'] as $val => $label)
                    <option value="{{ $val }}" @selected(($o['layout'] ?? 'accordion') === $val)>{{ $label }}</option>
                @endforeach
            </select>
            @error('options.layout')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Columns</label>
            <select name="options[columns]" class="w-full border border-gray-300 rounded-lg px-3 py-2 @error('options.columns') border-red-500 @enderror">
                @foreach([1 => '1', 2 => '2'] as $val => $label)
                    <option value="{{ $val }}" @selected((int) ($o['columns'] ?? 1) === $val)>{{ $label }}</option>
                @endforeach
            </select>
            @error('options.columns')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
        </div>
    </div>

    <div>
        <div class="flex items-center justify-between mb-2">
            <span class="text-sm font-medium text-gray-700">Questions &amp; answers</span>
            <x-button type="button" id="faq-add-row" variant="sky" size="sm">+ Add row</x-button>
        </div>
        <div id="faq-items" class="space-y-4">
            @foreach($items as $idx => $row)
                <div class="faq-item rounded-lg border border-gray-200 p-4 space-y-3 bg-gray-50" data-index="{{ $idx }}">
                    <div class="flex justify-between items-start gap-2">
                        <span class="text-xs font-medium text-gray-500">Item {{ $idx + 1 }}</span>
                        <x-button type="button" variant="error" size="sm" class="faq-remove-row">
                            Remove
                        </x-button>
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-600 mb-1">Question</label>
                        <input type="text" name="options[items][{{ $idx }}][question]" value="{{ $row['question'] ?? '' }}"
                               class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm">
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-600 mb-1">Answer</label>
                        <textarea name="options[items][{{ $idx }}][answer]" rows="3"
                                  class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm">{{ $row['answer'] ?? '' }}</textarea>
                    </div>
                </div>
            @endforeach
        </div>
        @error('options.items')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
    </div>
</div>

<template id="faq-item-template">
    <div class="faq-item rounded-lg border border-gray-200 p-4 space-y-3 bg-gray-50" data-index="__INDEX__">
        <div class="flex justify-between items-start gap-2">
            <span class="text-xs font-medium text-gray-500">New item</span>
            <x-button type="button" variant="error" size="sm" class="faq-remove-row">
                Remove
            </x-button>
        </div>
        <div>
            <label class="block text-xs font-medium text-gray-600 mb-1">Question</label>
            <input type="text" name="options[items][__INDEX__][question]" value=""
                   class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm">
        </div>
        <div>
            <label class="block text-xs font-medium text-gray-600 mb-1">Answer</label>
            <textarea name="options[items][__INDEX__][answer]" rows="3"
                      class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm"></textarea>
        </div>
    </div>
</template>

<script>
(function () {
    const container = document.getElementById('faq-items');
    const addBtn = document.getElementById('faq-add-row');
    const tpl = document.getElementById('faq-item-template');
    if (!container || !addBtn || !tpl) return;

    function nextIndex() {
        const items = container.querySelectorAll('.faq-item');
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
        root.querySelectorAll('.faq-remove-row').forEach(function (btn) {
            btn.addEventListener('click', function () {
                const items = container.querySelectorAll('.faq-item');
                if (items.length <= 1) return;
                root.remove();
            });
        });
    }

    container.querySelectorAll('.faq-item').forEach(bindRemove);
})();
</script>
