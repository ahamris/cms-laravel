<x-layouts.admin title="Permissions">
    <div class="space-y-6">
        {{-- Header --}}
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900 dark:text-zinc-100">Permissions</h1>
                <p class="text-gray-600 dark:text-zinc-400">Manage system permissions</p>
            </div>
        </div>

        @if (session('success'))
            <div class="bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 text-green-700 dark:text-green-300 px-4 py-3 rounded-lg">
                <i class="fa-solid fa-check-circle mr-2"></i>
                {{ session('success') }}
            </div>
        @endif

        @if (session('error'))
            <div class="bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 text-red-700 dark:text-red-300 px-4 py-3 rounded-lg">
                <i class="fa-solid fa-exclamation-circle mr-2"></i>
                {{ session('error') }}
            </div>
        @endif

        {{-- Live search: filters from 2nd character --}}
        <div class="bg-white dark:bg-zinc-800 rounded-xl shadow-sm border border-gray-200 dark:border-zinc-700 p-4">
            <div class="relative flex-1 min-w-[200px] max-w-sm">
                <i class="fa-solid fa-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 dark:text-zinc-500"></i>
                <input type="text"
                       id="permissionSearch"
                       value="{{ old('search', $search ?? '') }}"
                       placeholder="Type to filter (min 2 letters)..."
                       autocomplete="off"
                       class="pl-10 pr-4 py-2 border border-gray-300 dark:border-zinc-600 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary text-sm w-full bg-white dark:bg-zinc-800 text-gray-900 dark:text-zinc-100">
            </div>
        </div>

        {{-- Checklist-style grid --}}
        <div class="bg-white dark:bg-zinc-800 rounded-xl shadow-sm border border-gray-200 dark:border-zinc-700 p-4">
            <div class="flex items-center justify-between mb-3">
                <span id="permissionCount" class="text-sm text-gray-600 dark:text-zinc-400">{{ $permissions->count() }} permission{{ $permissions->count() === 1 ? '' : 's' }}</span>
            </div>

            @if ($permissions->isEmpty())
                <div class="py-12 text-center">
                    <i class="fa-solid fa-shield-alt text-4xl text-gray-300 dark:text-zinc-600 mb-4 block"></i>
                    <h3 class="text-lg font-medium text-gray-900 dark:text-zinc-100 mb-2">No permissions found</h3>
                    <p class="text-gray-600 dark:text-zinc-400">No permissions in the system.</p>
                </div>
            @else
                <div id="permissionNoResults" class="py-12 text-center hidden">
                    <i class="fa-solid fa-search text-4xl text-gray-300 dark:text-zinc-600 mb-4 block"></i>
                    <p class="text-gray-600 dark:text-zinc-400">No permissions match your filter.</p>
                </div>
                <ul id="permissionList" class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 xl:grid-cols-6 gap-2 list-none p-0 m-0">
                    @foreach ($permissions as $permission)
                        <li class="permission-item" data-permission="{{ strtolower($permission->name) }}">
                            <span class="inline-flex items-center w-full px-3 py-1.5 text-sm rounded-md bg-gray-100 dark:bg-zinc-700 text-gray-800 dark:text-zinc-200 border border-gray-200 dark:border-zinc-600">
                                <i class="fa-solid fa-key text-gray-400 dark:text-zinc-500 mr-2 text-xs"></i>
                                <span class="truncate" title="{{ $permission->name }}">{{ $permission->name }}</span>
                            </span>
                        </li>
                    @endforeach
                </ul>
            @endif
        </div>
    </div>

    <script>
        (function() {
            var input = document.getElementById('permissionSearch');
            var list = document.getElementById('permissionList');
            var countEl = document.getElementById('permissionCount');
            var noResults = document.getElementById('permissionNoResults');

            if (!input || !list) return;

            var items = list.querySelectorAll('.permission-item');
            var total = items.length;

            function updateFilter() {
                var q = (input.value || '').trim().toLowerCase();
                var showAll = q.length < 2;
                var visible = 0;

                items.forEach(function(li) {
                    var name = (li.getAttribute('data-permission') || '').toLowerCase();
                    var match = showAll || name.indexOf(q) !== -1;
                    li.style.display = match ? '' : 'none';
                    if (match) visible++;
                });

                if (countEl) {
                    countEl.textContent = visible + ' permission' + (visible === 1 ? '' : 's');
                    if (!showAll && total > 0) countEl.textContent += ' of ' + total;
                }
                if (noResults) {
                    noResults.classList.toggle('hidden', visible > 0);
                }
            }

            input.addEventListener('input', updateFilter);
            input.addEventListener('keyup', updateFilter);
            document.addEventListener('DOMContentLoaded', function() {
                if ((input.value || '').trim().length >= 2) updateFilter();
            });
        })();
    </script>
</x-layouts.admin>
