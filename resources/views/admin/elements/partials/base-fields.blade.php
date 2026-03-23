<div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Title</label>
        <input type="text" name="title" value="{{ old('title', $element->title ?? null) }}"
               class="w-full border border-gray-300 rounded-lg px-3 py-2 @error('title') border-red-500 @enderror">
        @error('title')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
    </div>
    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Sub Title</label>
        <input type="text" name="sub_title" value="{{ old('sub_title', $element->sub_title ?? null) }}"
               class="w-full border border-gray-300 rounded-lg px-3 py-2 @error('sub_title') border-red-500 @enderror">
        @error('sub_title')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
    </div>
</div>

<div>
    <label class="block text-sm font-medium text-gray-700 mb-1">Description</label>
    <textarea name="description" rows="4"
              class="w-full border border-gray-300 rounded-lg px-3 py-2 @error('description') border-red-500 @enderror">{{ old('description', $element->description ?? null) }}</textarea>
    @error('description')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
</div>
