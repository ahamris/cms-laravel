<x-layouts.admin title="Footer Links Management">
<x-slot:styles>
<style>
    .sortable-ghost {
        background-color: #c8ebfb;
        opacity: 0.5;
    }
    .sortable-drag {
        opacity: 1 !important;
    }
</style>
</x-slot:styles>

<div class="px-4 py-6">
    @if(session('success'))
    <div class="mb-6 bg-green-50 border border-green-200 rounded-xl p-4">
        <div class="flex items-center">
            <i class="fa-solid fa-check-circle text-green-500 text-lg mr-3"></i>
            <p class="text-sm font-medium text-green-800">{{ session('success') }}</p>
        </div>
    </div>
    @endif

    <div class="max-w-5xl">
        <!-- Footer Links -->
        <div>
            <div class="bg-white shadow rounded-lg">
                <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
                    <h3 class="text-lg font-medium text-gray-900">Footer Links</h3>
                    <a href="{{ route('admin.settings.footer-links.create') }}" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700">
                        <i class="fas fa-plus mr-2"></i> Add New Link
                    </a>
                </div>

                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-6" id="footer-columns-container">
                        @for ($i = 1; $i <= 4; $i++)
                        <div class="bg-gray-50 rounded-lg p-4">
                            <h4 class="font-bold text-gray-700 mb-4">Column {{ $i }}</h4>
                            <div id="column-{{ $i }}" data-column-id="{{ $i }}" class="space-y-3 min-h-[200px] sortable-list">
                                @if(isset($links[$i]))
                                    @foreach($links[$i] as $link)
                                    <div class="bg-white p-3 rounded-md shadow-sm border border-gray-200 flex justify-between items-center cursor-move" data-id="{{ $link->id }}">
                                        <div class="flex items-center">
                                            <i class="fas fa-grip-vertical text-gray-400 mr-3"></i>
                                            <span class="text-sm font-medium text-gray-800">{{ $link->title }}</span>
                                        </div>
                                        <div class="flex items-center space-x-3">
                                            <a href="{{ route('admin.settings.footer-links.edit', $link) }}" class="text-indigo-600 hover:text-indigo-900">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <form action="{{ route('admin.settings.footer-links.destroy', $link) }}" method="POST" onsubmit="return confirm('Are you sure?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-600 hover:text-red-900">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                    @endforeach
                                @endif
                            </div>
                        </div>
                        @endfor
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<x-slot:scripts>
<script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const columns = document.querySelectorAll('.sortable-list');
        columns.forEach(column => {
            new Sortable(column, {
                group: 'footer-links',
                animation: 150,
                ghostClass: 'sortable-ghost',
                dragClass: 'sortable-drag',
                preventOnFilter: false, // Allows clicks on buttons inside sortable items
                onEnd: function (evt) {
                    updateOrder();
                }
            });
        });

        function updateOrder() {
            let orderData = {};
            columns.forEach(column => {
                const columnId = column.dataset.columnId;
                const items = Array.from(column.children).map(item => item.dataset.id);
                orderData[columnId] = items;
            });

            fetch('{{ route("admin.settings.footer-links.order") }}', {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ order: orderData })
            })
            .then(response => response.json())
            .then(data => {
                if (!data.success) {
                    console.error('Failed to save order');
                }
            })
            .catch(error => console.error('Error:', error));
        }
    });
</script>
</x-slot:scripts>
</x-layouts.admin>
