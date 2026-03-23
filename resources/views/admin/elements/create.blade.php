<x-layouts.admin :title="'Create ' . $heading">
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Create {{ $heading }}</h1>
            <p class="text-gray-600">A new item will always be saved as type: <code>{{ $type->value }}</code>.</p>
        </div>
        <a href="{{ route($routeBase . '.index') }}"
           class="bg-gray-600 text-white px-4 py-2 rounded-lg hover:bg-gray-700 transition-colors duration-200">
            <i class="fa-solid fa-arrow-left mr-2"></i>Back to List
        </a>
    </div>

    @if($typeHelp)
        <div class="rounded-lg border border-blue-200 bg-blue-50 px-4 py-3 text-sm text-blue-900">
            {{ $typeHelp }}
        </div>
    @endif

    <form action="{{ route($routeBase . '.store') }}" method="POST" class="space-y-6">
        @csrf
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 space-y-4">
            @include('admin.elements.partials.base-fields', ['element' => $element])
            @include($optionsFormView, ['element' => $element])
        </div>

        <button type="submit"
                class="bg-primary text-white px-4 py-2 rounded-lg hover:bg-primary/80 transition-colors duration-200">
            <i class="fa-solid fa-save mr-2"></i>Create Item
        </button>
    </form>
</div>
</x-layouts.admin>
