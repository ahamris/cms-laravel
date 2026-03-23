<x-layouts.admin :title="$heading . ' Detail'">
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">{{ $heading }} Detail</h1>
            <p class="text-gray-600">Record #{{ $element->id }}</p>
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ route($routeBase . '.edit', $element->id) }}"
               class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition-colors duration-200">
                Edit
            </a>
            <a href="{{ route($routeBase . '.index') }}"
               class="bg-gray-600 text-white px-4 py-2 rounded-lg hover:bg-gray-700 transition-colors duration-200">
                Back
            </a>
        </div>
    </div>

    @if($typeHelp)
        <div class="rounded-lg border border-blue-200 bg-blue-50 px-4 py-3 text-sm text-blue-900">
            {{ $typeHelp }}
        </div>
    @endif

    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 space-y-4">
        <div><span class="font-semibold">Type:</span> <code>{{ $element->type->value }}</code></div>
        <div><span class="font-semibold">Title:</span> {{ $element->title ?: '-' }}</div>
        <div><span class="font-semibold">Sub title:</span> {{ $element->sub_title ?: '-' }}</div>
        <div>
            <span class="font-semibold">Description:</span>
            <p class="mt-2 text-gray-700 whitespace-pre-wrap">{{ $element->description ?: '-' }}</p>
        </div>
        <div>
            <span class="font-semibold block mb-2">Options</span>
            @include($showOptionsView, ['element' => $element])
        </div>
    </div>
</div>
</x-layouts.admin>
