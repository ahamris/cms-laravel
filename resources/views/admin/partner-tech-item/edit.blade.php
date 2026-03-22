<x-layouts.admin title="Edit Partner / Tech Stack">
    <div class="flex items-center justify-between mb-4">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 bg-primary rounded-md flex items-center justify-center">
                <i class="fa-solid fa-edit text-white text-sm"></i>
            </div>
            <div>
                <h2 class="text-lg font-semibold text-gray-900">Edit record</h2>
                <p class="text-sm text-gray-500">{{ $partnerTechItem->name }} · {{ $partnerTechItem->type === 0 ? 'Partner' : 'Tech Stack' }}</p>
            </div>
        </div>
        <a href="{{ route('admin.partner-tech-item.index') }}" class="px-4 py-2 rounded-md bg-white border border-gray-200 text-gray-700 text-sm hover:bg-gray-50 flex items-center gap-2">
            <i class="fa fa-arrow-left"></i> Back
        </a>
    </div>

    <form action="{{ route('admin.partner-tech-item.update', $partnerTechItem) }}" method="POST" enctype="multipart/form-data" id="partner-tech-form">
        @csrf
        @method('PUT')
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            {{-- Main content (left) --}}
            <div class="lg:col-span-2 space-y-4">
                <div class="bg-white rounded-lg border border-gray-200 p-5">
                    <h3 class="text-sm font-semibold text-gray-900 mb-4">Details</h3>
                    <div class="space-y-4">
                        <div>
                            <label for="name" class="block text-xs font-medium text-gray-700 mb-1">Name <span class="text-red-500">*</span></label>
                            <input type="text" id="name" name="name" value="{{ old('name', $partnerTechItem->name) }}" required class="w-full px-3 py-2 text-sm border border-gray-200 rounded-md focus:outline-none focus:ring-1 focus:ring-primary @error('name') border-red-500 @enderror">
                            @error('name')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-700 mb-1">Type</label>
                            <input type="text" value="{{ $partnerTechItem->type === 0 ? 'Partner' : 'Tech Stack' }}" disabled class="w-full px-3 py-2 text-sm bg-gray-50 border border-gray-200 rounded-md text-gray-600">
                            <input type="hidden" name="type" value="{{ $partnerTechItem->type }}">
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-700 mb-1">Banner</label>
                            <x-ui.image-upload
                                id="banner"
                                name="banner"
                                label=""
                                :required="false"
                                help-text="Optional. Max 8MB."
                                :max-size="8192"
                                size="small"
                                :current-image="$partnerTechItem->banner ? get_image($partnerTechItem->banner) : null"
                                current-image-alt="Banner"
                            />
                            @error('banner')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label for="title" class="block text-xs font-medium text-gray-700 mb-1">Title</label>
                            <input type="text" id="title" name="title" value="{{ old('title', $partnerTechItem->title) }}" class="w-full px-3 py-2 text-sm border border-gray-200 rounded-md focus:outline-none">
                            @error('title')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label for="description" class="block text-xs font-medium text-gray-700 mb-1">Description</label>
                            <textarea id="description" name="description" rows="2" class="w-full px-3 py-2 text-sm border border-gray-200 rounded-md focus:outline-none resize-none">{{ old('description', $partnerTechItem->description) }}</textarea>
                            @error('description')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                        </div>
                    </div>
                </div>
            </div>

            {{-- Right sidebar: Link items (multipliable) --}}
            <div class="lg:col-span-1">
                <div class="bg-white rounded-lg border border-gray-200 p-4 sticky top-4">
                    <div class="flex items-center justify-between mb-3">
                        <h3 class="text-sm font-semibold text-gray-900">Link items</h3>
                        <button type="button" id="add-data-row" class="px-2 py-1 text-xs font-medium rounded bg-primary text-white hover:opacity-90">+ Add</button>
                    </div>
                    <script type="application/json" id="static-pages-json">@json($staticPages ?? [])</script>
                    <div id="data-items-container" class="space-y-3 max-h-[calc(100vh-12rem)] overflow-y-auto pr-1">
                        @php
                            $dataItems = old('data', $partnerTechItem->data ?? []);
                            if (!is_array($dataItems)) $dataItems = [];
                            $staticPages = $staticPages ?? collect();
                        @endphp
                        @forelse($dataItems as $i => $row)
                            @php
                                $lt = $row['link_type'] ?? 'external';
                                $linkVal = $row['link'] ?? '';
                                $rowImage = $row['image'] ?? null;
                            @endphp
                            <div class="data-row border border-gray-100 rounded-lg p-3 bg-gray-50/50 space-y-2">
                                <div class="flex items-center justify-between gap-1">
                                    <select name="data[{{ $i }}][link_type]" class="data-link-type flex-1 min-w-0 px-2 py-1 text-xs border border-gray-200 rounded">
                                        <option value="external" {{ $lt == 'external' ? 'selected' : '' }}>URL</option>
                                        <option value="static" {{ $lt == 'static' ? 'selected' : '' }}>Static</option>
                                    </select>
                                    <input type="hidden" name="data[{{ $i }}][link]" class="data-link-hidden" value="{{ $linkVal }}">
                                    <div class="data-link-external flex-1 min-w-0" style="{{ $lt == 'static' ? 'display:none' : '' }}">
                                        <input type="text" class="data-link-input w-full px-2 py-1 text-xs border border-gray-200 rounded" placeholder="https://..." value="{{ $lt == 'external' ? $linkVal : '' }}">
                                    </div>
                                    <div class="data-link-static flex-1 min-w-0" style="{{ $lt == 'external' ? 'display:none' : '' }}">
                                        <select class="data-link-select w-full px-2 py-1 text-xs border border-gray-200 rounded">
                                            <option value="">Page…</option>
                                            @foreach($staticPages as $sp)
                                                <option value="{{ $sp->slug }}" {{ $linkVal === $sp->slug ? 'selected' : '' }}>{{ Str::limit($sp->title, 20) }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="flex items-center gap-1 shrink-0">
                                        <input type="number" name="data[{{ $i }}][sort_order]" value="{{ $row['sort_order'] ?? $i }}" min="0" class="w-10 px-1 py-1 text-xs border border-gray-200 rounded" title="Order">
                                        <button type="button" class="data-row-remove p-1 text-gray-400 hover:text-red-600 rounded" title="Remove"><i class="fa-solid fa-times text-xs"></i></button>
                                    </div>
                                </div>
                                <div class="link-item-image">
                                    <x-ui.image-upload
                                        id="data_{{ $i }}_image"
                                        name="data[{{ $i }}][image]"
                                        label=""
                                        :required="false"
                                        help-text=""
                                        :max-size="20480"
                                        size="small"
                                        :current-image="$rowImage ? get_image($rowImage) : null"
                                        :current-image-alt="'Item ' . ($i + 1) . ' image'"
                                    />
                                </div>
                            </div>
                        @empty
                            <div class="data-row border border-gray-100 rounded-lg p-3 bg-gray-50/50 space-y-2">
                                <div class="flex items-center gap-1 flex-wrap">
                                    <select name="data[0][link_type]" class="data-link-type w-16 px-2 py-1 text-xs border border-gray-200 rounded">
                                        <option value="external">URL</option>
                                        <option value="static">Static</option>
                                    </select>
                                    <input type="hidden" name="data[0][link]" class="data-link-hidden" value="">
                                    <div class="data-link-external flex-1 min-w-[100px]">
                                        <input type="text" class="data-link-input w-full px-2 py-1 text-xs border border-gray-200 rounded" placeholder="https://...">
                                    </div>
                                    <div class="data-link-static flex-1 min-w-[100px]" style="display:none">
                                        <select class="data-link-select w-full px-2 py-1 text-xs border border-gray-200 rounded">
                                            <option value="">Page…</option>
                                            @foreach($staticPages as $sp)
                                                <option value="{{ $sp->slug }}">{{ Str::limit($sp->title, 20) }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <input type="number" name="data[0][sort_order]" value="0" min="0" class="w-10 px-1 py-1 text-xs border border-gray-200 rounded">
                                    <button type="button" class="data-row-remove p-1 text-gray-400 hover:text-red-600 rounded"><i class="fa-solid fa-times text-xs"></i></button>
                                </div>
                                <div class="link-item-image">
                                    <x-ui.image-upload
                                        id="data_0_image"
                                        name="data[0][image]"
                                        label=""
                                        :required="false"
                                        help-text=""
                                        :max-size="20480"
                                        size="small"
                                        :current-image="null"
                                        current-image-alt="Image"
                                    />
                                </div>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>

        <div class="mt-6 flex justify-end gap-3">
            <a href="{{ route('admin.partner-tech-item.index') }}" class="px-4 py-2 text-sm text-gray-700 bg-white border border-gray-200 rounded-md hover:bg-gray-50">Cancel</a>
            <button type="submit" class="px-4 py-2 text-sm text-white bg-primary rounded-md hover:bg-primary/80">Update</button>
        </div>
    </form>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        var container = document.getElementById('data-items-container');
        var addBtn = document.getElementById('add-data-row');
        var staticPagesJson = document.getElementById('static-pages-json');
        var staticPages = staticPagesJson ? JSON.parse(staticPagesJson.textContent || '[]') : [];
        var nextIndex = container.querySelectorAll('.data-row').length;

        function syncLinkValue(row) {
            var typeSelect = row.querySelector('.data-link-type');
            var hidden = row.querySelector('.data-link-hidden');
            var input = row.querySelector('.data-link-input');
            var select = row.querySelector('.data-link-select');
            if (!hidden) return;
            var val = typeSelect && typeSelect.value === 'static' && select ? select.value : (input ? input.value : '');
            hidden.value = val || '';
        }

        function bindLinkTypeToggle(row) {
            var typeSelect = row.querySelector('.data-link-type');
            var externalDiv = row.querySelector('.data-link-external');
            var staticDiv = row.querySelector('.data-link-static');
            var input = row.querySelector('.data-link-input');
            var select = row.querySelector('.data-link-select');
            if (!typeSelect) return;
            function toggle() {
                var isStatic = typeSelect.value === 'static';
                if (externalDiv) externalDiv.style.display = isStatic ? 'none' : '';
                if (staticDiv) staticDiv.style.display = isStatic ? '' : 'none';
                syncLinkValue(row);
            }
            typeSelect.addEventListener('change', toggle);
            if (input) input.addEventListener('input', function() { syncLinkValue(row); });
            if (select) select.addEventListener('change', function() { syncLinkValue(row); });
        }

        container.querySelectorAll('.data-row').forEach(function(row) { bindLinkTypeToggle(row); });

        addBtn.addEventListener('click', function() {
            var optionsHtml = staticPages.map(function(sp) {
                return '<option value="' + (sp.slug || '') + '">' + (sp.title || sp.slug).substring(0, 20) + '</option>';
            }).join('');
            var row = document.createElement('div');
            row.className = 'data-row border border-gray-100 rounded-lg p-3 bg-gray-50/50 space-y-2';
            row.innerHTML =
                '<div class="flex items-center gap-1 flex-wrap">' +
                '<select name="data[' + nextIndex + '][link_type]" class="data-link-type w-16 px-2 py-1 text-xs border border-gray-200 rounded"><option value="external">URL</option><option value="static">Static</option></select>' +
                '<input type="hidden" name="data[' + nextIndex + '][link]" class="data-link-hidden" value="">' +
                '<div class="data-link-external flex-1 min-w-[100px]"><input type="text" class="data-link-input w-full px-2 py-1 text-xs border border-gray-200 rounded" placeholder="https://..."></div>' +
                '<div class="data-link-static flex-1 min-w-[100px]" style="display:none"><select class="data-link-select w-full px-2 py-1 text-xs border border-gray-200 rounded"><option value="">Page…</option>' + optionsHtml + '</select></div>' +
                '<input type="number" name="data[' + nextIndex + '][sort_order]" value="' + nextIndex + '" min="0" class="w-10 px-1 py-1 text-xs border border-gray-200 rounded">' +
                '<button type="button" class="data-row-remove p-1 text-gray-400 hover:text-red-600 rounded"><i class="fa-solid fa-times text-xs"></i></button>' +
                '</div>' +
                '<div class="link-item-image"><label class="block text-xs text-gray-500 mb-1">Image</label><input type="file" name="data[' + nextIndex + '][image]" accept="image/*" class="block w-full text-xs text-gray-500 file:mr-2 file:py-1 file:px-2 file:rounded file:border-0 file:text-xs file:bg-primary file:text-white file:cursor-pointer hover:file:opacity-90"></div>';
            container.appendChild(row);
            bindLinkTypeToggle(row);
            nextIndex++;
            bindRemove();
        });

        function reindexRows() {
            container.querySelectorAll('.data-row').forEach(function(r, i) {
                r.querySelectorAll('[name^="data["]').forEach(function(inp) {
                    inp.name = inp.name.replace(/^data\[\d+\]/, 'data[' + i + ']');
                });
                var orderInp = r.querySelector('[name$="[sort_order]"]');
                if (orderInp && orderInp.value === '') orderInp.value = i;
            });
            nextIndex = container.querySelectorAll('.data-row').length;
        }

        function bindRemove() {
            container.querySelectorAll('.data-row-remove').forEach(function(btn) {
                btn.onclick = function() {
                    var row = btn.closest('.data-row');
                    if (container.querySelectorAll('.data-row').length > 1) row.remove();
                    reindexRows();
                };
            });
        }
        bindRemove();
    });
    </script>
</x-layouts.admin>
