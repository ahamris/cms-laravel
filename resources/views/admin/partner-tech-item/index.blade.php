<x-layouts.admin title="Partners & Tech Stack">
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <div class="flex items-center gap-3">
            <div class="w-12 h-12 bg-primary rounded-md flex items-center justify-center">
                <i class="fa-solid fa-handshake text-white text-base"></i>
            </div>
            <div class="flex flex-col gap-1">
                <h2>Partners & Tech Stack</h2>
                <p>One record per type. Each record has multipliable link items in <strong>data</strong>.</p>
            </div>
        </div>
        @if($items->count() < 2)
        <a href="{{ route('admin.partner-tech-item.create') }}" class="px-5 py-2 rounded-md bg-primary text-white text-sm hover:bg-primary/80 flex items-center space-x-2">
            <i class="fa fa-plus"></i>
            <span>Add record</span>
        </a>
        @endif
    </div>

    @if(session('success'))
        <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-md text-sm">{{ session('success') }}</div>
    @endif
    @if($errors->has('error'))
        <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-md text-sm">{{ $errors->first('error') }}</div>
    @endif

    <div class="bg-gray-50/50 rounded-md border border-gray-200">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50/80 border-b border-gray-200">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Type</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Title</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Link items</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($items as $item)
                        <tr class="hover:bg-gray-50">
                            <td class="px-4 py-3 whitespace-nowrap">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $item->type === 0 ? 'bg-blue-100 text-blue-800' : 'bg-purple-100 text-purple-800' }}">
                                    {{ $item->type === 0 ? 'Partner' : 'Tech Stack' }}
                                </span>
                            </td>
                            <td class="px-4 py-3 whitespace-nowrap font-medium text-gray-900">{{ $item->name }}</td>
                            <td class="px-4 py-3 whitespace-nowrap text-gray-600">{{ $item->title ?? '—' }}</td>
                            <td class="px-4 py-3 text-gray-500">{{ is_array($item->data) ? count($item->data) : 0 }} items</td>
                            <td class="px-4 py-3 whitespace-nowrap text-sm font-medium">
                                <div class="flex items-center gap-2">
                                    <a href="{{ route('admin.partner-tech-item.show', $item) }}" class="text-gray-600 hover:text-primary" title="View"><i class="fa-solid fa-eye"></i></a>
                                    <a href="{{ route('admin.partner-tech-item.edit', $item) }}" class="text-gray-600 hover:text-blue-600" title="Edit"><i class="fa-solid fa-edit"></i></a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="px-4 py-8 text-center text-gray-500">No records yet. <a href="{{ route('admin.partner-tech-item.create') }}" class="text-primary underline">Add Partners or Tech Stack</a>.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
</x-layouts.admin>
