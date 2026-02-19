<x-layouts.admin title="Intent Brief Details">
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Intent Brief</h1>
            <p class="text-gray-600">View and manage your content strategy</p>
        </div>
        <div class="flex space-x-3">
            <a href="{{ route('admin.marketing.intent-briefs.edit', $intentBrief) }}" 
               class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50">
                Edit
            </a>
            <a href="{{ route('admin.marketing.intent-briefs.index') }}" 
               class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50">
                Back
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2 space-y-6">
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h2 class="text-lg font-semibold mb-4">Brief Details</h2>
                <dl class="space-y-4">
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Business Goal</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $intentBrief->business_goal }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Audience</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $intentBrief->audience }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Topic</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $intentBrief->topic }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Tone</dt>
                        <dd class="mt-1 text-sm text-gray-900 capitalize">{{ $intentBrief->tone }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Status</dt>
                        <dd class="mt-1">
                            <span class="px-2 py-1 text-xs rounded-full
                                {{ $intentBrief->status === 'completed' ? 'bg-green-100 text-green-800' : '' }}
                                {{ $intentBrief->status === 'processing' ? 'bg-blue-100 text-blue-800' : '' }}
                                {{ $intentBrief->status === 'failed' ? 'bg-red-100 text-red-800' : 'bg-gray-100 text-gray-800' }}">
                                {{ ucfirst($intentBrief->status) }}
                            </span>
                        </dd>
                    </div>
                </dl>
            </div>

            @if($intentBrief->contentPlan)
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h2 class="text-lg font-semibold">Content Plan</h2>
                        <a href="{{ route('admin.marketing.content-plans.show', $intentBrief->contentPlan) }}" 
                           class="text-primary hover:text-primary/80">
                            View Plan →
                        </a>
                    </div>
                    <p class="text-sm text-gray-600">
                        Status: <span class="font-medium">{{ ucfirst($intentBrief->contentPlan->status) }}</span><br>
                        Autopilot Mode: <span class="font-medium capitalize">{{ str_replace('_', ' ', $intentBrief->contentPlan->autopilot_mode) }}</span>
                    </p>
                </div>
            @else
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <p class="text-gray-600 mb-4">No content plan generated yet.</p>
                    @if($intentBrief->status === 'processing')
                        <p class="text-blue-600">Content plan is being generated...</p>
                    @else
                        <button onclick="generatePlan({{ $intentBrief->id }})" 
                                class="px-4 py-2 bg-primary text-white rounded-lg hover:bg-primary/80">
                            Generate Content Plan
                        </button>
                    @endif
                </div>
            @endif
        </div>
    </div>
</div>

<script>
function generatePlan(id) {
    fetch(`/admin/marketing/intent-briefs/${id}/generate-plan`, {
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
</script>
</x-layouts.admin>

