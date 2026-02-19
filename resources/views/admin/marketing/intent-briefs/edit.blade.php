<x-layouts.admin title="Edit Intent Brief">
<div class="space-y-6">
    <div>
        <h1 class="text-2xl font-bold text-gray-900">Edit Intent Brief</h1>
        <p class="text-gray-600">Update your marketing intent brief</p>
    </div>

    <form action="{{ route('admin.marketing.intent-briefs.update', $intentBrief) }}" method="POST" class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
        @csrf
        @method('PUT')

        <div class="space-y-6">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Business Goal *</label>
                <select name="business_goal" required class="w-full rounded-lg border-gray-300">
                    <option value="">Select a goal...</option>
                    <option value="More leads" {{ $intentBrief->business_goal === 'More leads' ? 'selected' : '' }}>More leads</option>
                    <option value="Authority" {{ $intentBrief->business_goal === 'Authority' ? 'selected' : '' }}>Build authority</option>
                    <option value="SEO traffic" {{ $intentBrief->business_goal === 'SEO traffic' ? 'selected' : '' }}>Increase SEO traffic</option>
                    <option value="Product adoption" {{ $intentBrief->business_goal === 'Product adoption' ? 'selected' : '' }}>Drive product adoption</option>
                </select>
                @error('business_goal')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Target Audience *</label>
                <textarea name="audience" required rows="3" 
                    class="w-full rounded-lg border-gray-300">{{ old('audience', $intentBrief->audience) }}</textarea>
                @error('audience')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Topic / Problem *</label>
                <input type="text" name="topic" required 
                    class="w-full rounded-lg border-gray-300"
                    value="{{ old('topic', $intentBrief->topic) }}">
                @error('topic')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Tone *</label>
                <select name="tone" required class="w-full rounded-lg border-gray-300">
                    <option value="expert" {{ $intentBrief->tone === 'expert' ? 'selected' : '' }}>Expert</option>
                    <option value="neutral" {{ $intentBrief->tone === 'neutral' ? 'selected' : '' }}>Neutral</option>
                    <option value="persuasive" {{ $intentBrief->tone === 'persuasive' ? 'selected' : '' }}>Persuasive</option>
                </select>
                @error('tone')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Approval Level *</label>
                <select name="approval_level" required class="w-full rounded-lg border-gray-300">
                    <option value="manual" {{ $intentBrief->approval_level === 'manual' ? 'selected' : '' }}>Manual approval (default)</option>
                    <option value="auto_approve" {{ $intentBrief->approval_level === 'auto_approve' ? 'selected' : '' }}>Auto-approve with spot checks</option>
                </select>
                @error('approval_level')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
            </div>

            <div class="flex justify-end space-x-4">
                <a href="{{ route('admin.marketing.intent-briefs.show', $intentBrief) }}" 
                   class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50">
                    Cancel
                </a>
                <button type="submit" 
                        class="px-4 py-2 bg-primary text-white rounded-lg hover:bg-primary/80">
                    Update Intent Brief
                </button>
            </div>
        </div>
    </form>
</div>
</x-layouts.admin>

