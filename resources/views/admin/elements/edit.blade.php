<x-layouts.admin :title="'Edit ' . $heading">
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Edit {{ $heading }}</h1>
            <p class="text-gray-600">This item is locked to type: <code>{{ is_object($type) ? $type->value : $type }}</code>.</p>
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

    <form action="{{ route($routeBase . '.update', $element->id) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
        @csrf
        @method('PUT')
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 space-y-4">
            @include('admin.elements.partials.base-fields', ['element' => $element])
            @include($optionsFormView, ['element' => $element])
        </div>

        <button type="submit"
                class="bg-primary text-white px-4 py-2 rounded-lg hover:bg-primary/80 transition-colors duration-200">
            <i class="fa-solid fa-save mr-2"></i>Update Item
        </button>
    </form>
</div>
</x-layouts.admin>
