<x-layouts.admin title="Content Plans">
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Content Plans</h1>
            <p class="text-gray-600">Manage your automated content marketing plans</p>
        </div>
        <a href="{{ route('admin.marketing.dashboard') }}"
           class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50">
            Dashboard
        </a>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        @if($contentPlans->count() > 0)
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50 border-b border-gray-200">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Intent Brief</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Period</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Autopilot Mode</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($contentPlans as $plan)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4">
                                    <div class="text-sm font-medium text-gray-900">
                                        {{ $plan->intentBrief->business_goal ?? 'N/A' }}
                                    </div>
                                    <div class="text-sm text-gray-500">
                                        {{ Str::limit($plan->intentBrief->topic ?? '', 40) }}
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-600">
                                    {{ $plan->start_date->format('M d') }} - {{ $plan->end_date->format('M d, Y') }}
                                </td>
                                <td class="px-6 py-4">
                                    <span class="px-2 py-1 text-xs rounded-full
                                        {{ $plan->status === 'active' ? 'bg-green-100 text-green-800' : '' }}
                                        {{ $plan->status === 'pending_approval' ? 'bg-yellow-100 text-yellow-800' : '' }}
                                        {{ $plan->status === 'approved' ? 'bg-blue-100 text-blue-800' : 'bg-gray-100 text-gray-800' }}">
                                        {{ ucfirst(str_replace('_', ' ', $plan->status)) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-600 capitalize">
                                    {{ str_replace('_', ' ', $plan->autopilot_mode) }}
                                </td>
                                <td class="px-6 py-4 text-right text-sm font-medium">
                                    <a href="{{ route('admin.marketing.content-plans.show', $plan) }}" 
                                       class="text-primary hover:text-primary/80">View</a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="px-6 py-4 border-t border-gray-200">
                {{ $contentPlans->links() }}
            </div>
        @else
            <div class="p-12 text-center">
                <p class="text-gray-500">No content plans yet. Create an intent brief to generate one.</p>
            </div>
        @endif
    </div>
</div>
</x-layouts.admin>

