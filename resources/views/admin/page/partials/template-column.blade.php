@php
    $templates = config('page_templates.templates', []);
    $key = $item->template ?? config('page_templates.default', 'default');
    $label = $templates[$key]['label'] ?? $key;
@endphp
<span class="text-sm text-zinc-700 dark:text-zinc-300">{{ $label }}</span>
