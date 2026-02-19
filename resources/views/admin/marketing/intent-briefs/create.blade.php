<x-layouts.admin title="Create Intent Brief">
<div class="space-y-6">
    <div>
        <h1 class="text-2xl font-bold text-gray-900">Create Intent Brief</h1>
        <p class="text-gray-600">Describe your marketing goal and let AI create a content strategy</p>
    </div>

    <form action="{{ route('admin.marketing.intent-briefs.store') }}" method="POST" class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
        @csrf

        <div class="space-y-6">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Business Goal *</label>
                <select name="business_goal" required class="w-full rounded-lg border-gray-300">
                    <option value="">Select a goal...</option>
                    <option value="More leads">More leads</option>
                    <option value="Authority">Build authority</option>
                    <option value="SEO traffic">Increase SEO traffic</option>
                    <option value="Product adoption">Drive product adoption</option>
                </select>
                @error('business_goal')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Target Audience *</label>
                <textarea name="audience" required rows="3" 
                    class="w-full rounded-lg border-gray-300"
                    placeholder="e.g., Decision-makers in government, developers, SMBs, etc."></textarea>
                @error('audience')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Topic / Problem *</label>
                <input type="text" name="topic" required 
                    class="w-full rounded-lg border-gray-300"
                    placeholder="e.g., Woo compliance, AI in government">
                @error('topic')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Tone *</label>
                <select name="tone" required class="w-full rounded-lg border-gray-300">
                    <option value="expert">Expert</option>
                    <option value="neutral">Neutral</option>
                    <option value="persuasive">Persuasive</option>
                </select>
                @error('tone')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Approval Level *</label>
                <select name="approval_level" required class="w-full rounded-lg border-gray-300">
                    <option value="manual">Manual approval (default)</option>
                    <option value="auto_approve">Auto-approve with spot checks</option>
                </select>
                @error('approval_level')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
            </div>

            <div class="flex justify-end space-x-4">
                <a href="{{ route('admin.marketing.intent-briefs.index') }}" 
                   class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50">
                    Cancel
                </a>
                <button type="submit" 
                        class="px-4 py-2 bg-primary text-white rounded-lg hover:bg-primary/80">
                    Create Intent Brief
                </button>
            </div>
        </div>
    </form>
</div>
</x-layouts.admin>

