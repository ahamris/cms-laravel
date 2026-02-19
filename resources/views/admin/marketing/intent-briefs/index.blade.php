<x-layouts.admin title="Intent Briefs">
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Intent Briefs</h1>
            <p class="text-gray-600">Create content marketing strategies from business goals</p>
        </div>
        <a href="{{ route('admin.marketing.intent-briefs.create') }}"
           class="bg-primary text-white px-4 py-2 rounded-lg hover:bg-primary/80 transition-colors">
            <i class="fa-solid fa-plus mr-2"></i>
            New Intent Brief
        </a>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        @if($intentBriefs->count() > 0)
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50 border-b border-gray-200">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Business Goal</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Audience</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Topic</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($intentBriefs as $brief)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 text-sm text-gray-900">{{ $brief->business_goal }}</td>
                                <td class="px-6 py-4 text-sm text-gray-600">{{ Str::limit($brief->audience, 50) }}</td>
                                <td class="px-6 py-4 text-sm text-gray-600">{{ Str::limit($brief->topic, 50) }}</td>
                                <td class="px-6 py-4">
                                    <span class="px-2 py-1 text-xs rounded-full
                                        {{ $brief->status === 'completed' ? 'bg-green-100 text-green-800' : '' }}
                                        {{ $brief->status === 'processing' ? 'bg-blue-100 text-blue-800' : '' }}
                                        {{ $brief->status === 'failed' ? 'bg-red-100 text-red-800' : 'bg-gray-100 text-gray-800' }}">
                                        {{ ucfirst($brief->status) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-right text-sm font-medium">
                                    <a href="{{ route('admin.marketing.intent-briefs.show', $brief) }}" 
                                       class="text-primary hover:text-primary/80">View</a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="px-6 py-4 border-t border-gray-200">
                {{ $intentBriefs->links() }}
            </div>
        @else
            <div class="p-12 text-center">
                <p class="text-gray-500">No intent briefs yet. Create your first one to get started.</p>
            </div>
        @endif
    </div>
</div>
</x-layouts.admin>

