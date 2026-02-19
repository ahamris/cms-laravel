<x-layouts.admin title="Content Plan Details">
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Content Plan</h1>
            <p class="text-gray-600">30-day content marketing strategy</p>
        </div>
        <div class="flex space-x-3">
            @if($contentPlan->status === 'pending_approval')
                <button onclick="approvePlan({{ $contentPlan->id }})" 
                        class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700">
                    Approve Plan
                </button>
            @endif
            <a href="{{ route('admin.marketing.content-plans.index') }}" 
               class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50">
                Back
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-4 gap-6">
        <div class="lg:col-span-3 space-y-6">
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h2 class="text-lg font-semibold mb-4">Plan Overview</h2>
                <dl class="grid grid-cols-2 gap-4">
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Status</dt>
                        <dd class="mt-1">
                            <span class="px-2 py-1 text-xs rounded-full
                                {{ $contentPlan->status === 'active' ? 'bg-green-100 text-green-800' : '' }}
                                {{ $contentPlan->status === 'pending_approval' ? 'bg-yellow-100 text-yellow-800' : 'bg-gray-100 text-gray-800' }}">
                                {{ ucfirst(str_replace('_', ' ', $contentPlan->status)) }}
                            </span>
                        </dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Autopilot Mode</dt>
                        <dd class="mt-1">
                            <select id="autopilot_mode" 
                                    onchange="updateAutopilotMode({{ $contentPlan->id }}, this.value)"
                                    class="text-sm rounded-md border-gray-300 focus:border-primary focus:ring-primary">
                                <option value="assisted" {{ $contentPlan->autopilot_mode === 'assisted' ? 'selected' : '' }}>Assisted</option>
                                <option value="guided" {{ $contentPlan->autopilot_mode === 'guided' ? 'selected' : '' }}>Guided</option>
                                <option value="full_autopilot" {{ $contentPlan->autopilot_mode === 'full_autopilot' ? 'selected' : '' }}>Full Autopilot</option>
                            </select>
                            <p class="text-xs text-gray-500 mt-1">
                                @if($contentPlan->autopilot_mode === 'assisted')
                                    AI proposes → you approve
                                @elseif($contentPlan->autopilot_mode === 'guided')
                                    AI publishes unless blocked
                                @else
                                    AI plans, writes, schedules, publishes
                                @endif
                            </p>
                        </dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Start Date</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $contentPlan->start_date->format('M d, Y') }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">End Date</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $contentPlan->end_date->format('M d, Y') }}</dd>
                    </div>
                </dl>
            </div>

            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h2 class="text-lg font-semibold mb-4">Content Timeline (30 Days)</h2>
                
                {{-- Visual Timeline --}}
                <div class="mb-6 relative">
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-xs text-gray-500">{{ $contentPlan->start_date->format('M d') }}</span>
                        <span class="text-xs text-gray-500">{{ $contentPlan->end_date->format('M d') }}</span>
                    </div>
                    <div class="h-2 bg-gray-200 rounded-full relative">
                        @php
                            $allItems = $contentPlan->items()->whereNotNull('scheduled_at')->orderBy('scheduled_at')->get();
                            $totalDays = $contentPlan->start_date->diffInDays($contentPlan->end_date);
                        @endphp
                        @foreach($allItems->take(10) as $item)
                            @php
                                $daysFromStart = $contentPlan->start_date->diffInDays($item->scheduled_at);
                                $position = ($daysFromStart / max($totalDays, 1)) * 100;
                                $color = match($item->item_type) {
                                    'pillar' => 'bg-blue-500',
                                    'supporting' => 'bg-green-500',
                                    'social' => 'bg-purple-500',
                                    'evergreen' => 'bg-orange-500',
                                    default => 'bg-gray-500',
                                };
                            @endphp
                            <div class="absolute h-2 w-2 rounded-full {{ $color }}" 
                                 style="left: {{ min($position, 98) }}%; top: 0;"
                                 title="{{ $item->content_data['title'] ?? $item->item_type }} - {{ $item->scheduled_at->format('M d') }}">
                            </div>
                        @endforeach
                    </div>
                    <div class="flex items-center justify-between mt-2 text-xs text-gray-500">
                        <span>Start</span>
                        <span>End</span>
                    </div>
                </div>

                @if($itemsByType['pillar']->count() > 0)
                    <div class="mb-6">
                        <h3 class="font-medium text-gray-900 mb-3">Pillar Articles ({{ $itemsByType['pillar']->count() }})</h3>
                        <div class="space-y-2">
                            @foreach($itemsByType['pillar'] as $item)
                                <div class="p-3 bg-gray-50 rounded-lg">
                                    <div class="flex justify-between items-start">
                                        <div class="flex-1">
                                            <p class="font-medium">{{ $item->content_data['title'] ?? 'Pillar Article' }}</p>
                                            <p class="text-sm text-gray-600">{{ $item->scheduled_at ? $item->scheduled_at->format('M d, Y H:i') : 'Not scheduled' }}</p>
                                            @if($item->relatedContent)
                                                <a href="{{ route('admin.content.blog.edit', $item->relatedContent) }}" 
                                                   class="text-xs text-primary hover:underline mt-1 inline-block">
                                                    <i class="fa-solid fa-edit mr-1"></i>Edit Blog
                                                </a>
                                            @endif
                                        </div>
                                        <span class="px-2 py-1 text-xs rounded-full bg-gray-200">{{ ucfirst($item->status) }}</span>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                @if($itemsByType['supporting']->count() > 0)
                    <div class="mb-6">
                        <h3 class="font-medium text-gray-900 mb-3">Supporting Articles ({{ $itemsByType['supporting']->count() }})</h3>
                        <div class="space-y-2">
                            @foreach($itemsByType['supporting'] as $item)
                                <div class="p-3 bg-gray-50 rounded-lg">
                                    <div class="flex justify-between items-start">
                                        <div class="flex-1">
                                            <p class="font-medium">{{ $item->content_data['title'] ?? 'Supporting Article' }}</p>
                                            <p class="text-sm text-gray-600">{{ $item->scheduled_at ? $item->scheduled_at->format('M d, Y H:i') : 'Not scheduled' }}</p>
                                            @if($item->relatedContent)
                                                <a href="{{ route('admin.content.blog.edit', $item->relatedContent) }}" 
                                                   class="text-xs text-primary hover:underline mt-1 inline-block">
                                                    <i class="fa-solid fa-edit mr-1"></i>Edit Blog
                                                </a>
                                            @endif
                                        </div>
                                        <span class="px-2 py-1 text-xs rounded-full bg-gray-200">{{ ucfirst($item->status) }}</span>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                @if($itemsByType['social']->count() > 0)
                    <div>
                        <h3 class="font-medium text-gray-900 mb-3">Social Media Posts ({{ $itemsByType['social']->count() }})</h3>
                        <p class="text-sm text-gray-600">Social posts will be generated from blog content.</p>
                    </div>
                @endif
            </div>
        </div>

        <div class="space-y-6">
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h3 class="font-semibold mb-4">Quick Actions</h3>
                <div class="space-y-2">
                    <button onclick="generateContent({{ $contentPlan->id }})" 
                            class="w-full px-4 py-2 bg-primary text-white rounded-lg hover:bg-primary/80 text-sm">
                        Generate Content
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function approvePlan(id) {
    fetch(`/admin/marketing/content-plans/${id}/approve`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Content-Type': 'application/json',
        },
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            location.reload();
        }
    });
}

function generateContent(id) {
    fetch(`/admin/marketing/content-plans/${id}/generate`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Content-Type': 'application/json',
        },
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('Content generation started!');
            location.reload();
        }
    });
}

function updateAutopilotMode(planId, mode) {
    fetch(`/admin/marketing/content-plans/${planId}/autopilot-mode`, {
        method: 'PUT',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({ autopilot_mode: mode })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            location.reload();
        } else {
            alert('Failed to update autopilot mode');
        }
    });
}
</script>
</x-layouts.admin>

