<x-layouts.admin :title="'Create ' . $heading">
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Create {{ $heading }}</h1>
            <p class="text-gray-600">A new item will always be saved as type: <code>{{ $type }}</code>.</p>
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
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Title</label>
                    <input type="text" name="title" value="{{ old('title') }}"
                           class="w-full border border-gray-300 rounded-lg px-3 py-2 @error('title') border-red-500 @enderror">
                    @error('title')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Sub Title</label>
                    <input type="text" name="sub_title" value="{{ old('sub_title') }}"
                           class="w-full border border-gray-300 rounded-lg px-3 py-2 @error('sub_title') border-red-500 @enderror">
                    @error('sub_title')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                <textarea name="description" rows="4"
                          class="w-full border border-gray-300 rounded-lg px-3 py-2 @error('description') border-red-500 @enderror">{{ old('description') }}</textarea>
                @error('description')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Options (JSON)</label>
                <textarea name="options" rows="12"
                          class="w-full font-mono text-sm border border-gray-300 rounded-lg px-3 py-2 @error('options') border-red-500 @enderror">{{ old('options', '{}') }}</textarea>
                @error('options')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
            </div>

        </div>

        <button type="submit"
                class="bg-primary text-white px-4 py-2 rounded-lg hover:bg-primary/80 transition-colors duration-200">
            <i class="fa-solid fa-save mr-2"></i>Create Item
        </button>
    </form>
</div>
</x-layouts.admin>
