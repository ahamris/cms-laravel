<x-layouts.admin title="Marketing Dashboard">
<div class="space-y-6">
    <div>
        <h1 class="text-2xl font-bold text-gray-900">Marketing Dashboard</h1>
        <p class="text-gray-600">Overview of your content marketing performance</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600">Active Plans</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $activePlans }}</p>
                </div>
                <div class="p-3 bg-blue-100 rounded-lg">
                    <i class="fa-solid fa-calendar text-blue-600"></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600">Published Blogs (30d)</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $publishedBlogs }}</p>
                </div>
                <div class="p-3 bg-green-100 rounded-lg">
                    <i class="fa-solid fa-blog text-green-600"></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600">Total Impressions (30d)</p>
                    <p class="text-2xl font-bold text-gray-900">{{ number_format($totalImpressions) }}</p>
                </div>
                <div class="p-3 bg-purple-100 rounded-lg">
                    <i class="fa-solid fa-chart-line text-purple-600"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <h2 class="text-lg font-semibold mb-4">Next Publications</h2>
            @if($nextPublications->count() > 0)
                <div class="space-y-3">
                    @foreach($nextPublications->take(5) as $publication)
                        <div class="flex justify-between items-center p-3 bg-gray-50 rounded-lg">
                            <div>
                                <p class="text-sm font-medium">{{ $publication->content_data['title'] ?? 'Content Item' }}</p>
                                <p class="text-xs text-gray-600">{{ $publication->scheduled_at->format('M d, Y H:i') }}</p>
                            </div>
                            <span class="px-2 py-1 text-xs rounded-full bg-blue-100 text-blue-800">Scheduled</span>
                        </div>
                    @endforeach
                </div>
            @else
                <p class="text-gray-500 text-sm">No upcoming publications</p>
            @endif
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <h2 class="text-lg font-semibold mb-4">Awaiting Approval</h2>
            @if($awaitingApproval->count() > 0)
                <div class="space-y-3">
                    @foreach($awaitingApproval as $plan)
                        <div class="flex justify-between items-center p-3 bg-yellow-50 rounded-lg">
                            <div>
                                <p class="text-sm font-medium">{{ $plan->intentBrief->business_goal ?? 'Content Plan' }}</p>
                                <p class="text-xs text-gray-600">Created by {{ $plan->intentBrief->user->name ?? 'Unknown' }}</p>
                            </div>
                            <a href="{{ route('admin.marketing.content-plans.show', $plan) }}" 
                               class="text-primary hover:text-primary/80 text-sm">Review →</a>
                        </div>
                    @endforeach
                </div>
            @else
                <p class="text-gray-500 text-sm">No plans awaiting approval</p>
            @endif
        </div>
    </div>

    @if(count($risks) > 0 || count($suggestions) > 0)
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <h2 class="text-lg font-semibold mb-4">Risks & Suggestions</h2>
            <div class="space-y-3">
                @foreach($risks as $risk)
                    <div class="flex items-start p-3 bg-red-50 border border-red-200 rounded-lg">
                        <i class="fa-solid fa-exclamation-triangle text-red-600 mt-1 mr-3"></i>
                        <div class="flex-1">
                            <p class="text-sm font-medium text-red-900">{{ $risk['message'] }}</p>
                            <a href="{{ $risk['action'] }}" class="text-sm text-red-700 hover:underline">View →</a>
                        </div>
                    </div>
                @endforeach

                @foreach($suggestions as $suggestion)
                    <div class="flex items-start p-3 bg-blue-50 border border-blue-200 rounded-lg">
                        <i class="fa-solid fa-lightbulb text-blue-600 mt-1 mr-3"></i>
                        <div class="flex-1">
                            <p class="text-sm font-medium text-blue-900">{{ $suggestion['message'] }}</p>
                            <a href="{{ $suggestion['action'] }}" class="text-sm text-blue-700 hover:underline">View →</a>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif
</div>
</x-layouts.admin>

