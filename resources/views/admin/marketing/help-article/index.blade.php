<x-layouts.admin title="Help Articles">
<div class="space-y-6">
    {{-- Header --}}
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Help Articles</h1>
            <p class="text-gray-600">Manage knowledge base articles and documentation</p>
        </div>
        <a href="{{ route('admin.marketing.help-article.create') }}"
           class="bg-primary text-white px-4 py-2 rounded-lg hover:bg-primary/80 transition-colors duration-200">
            <i class="fa-solid fa-plus mr-2"></i>
            Create New Article
        </a>
    </div>

    {{-- Help Articles Table --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        @if($helpArticles->count() > 0)
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50 border-b border-gray-200">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Article</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Difficulty</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Features</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Read Time</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($helpArticles as $article)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4">
                                    <div class="flex items-start">
                                        <div class="h-10 w-10 rounded-lg bg-blue-50 flex items-center justify-center mr-3 flex-shrink-0">
                                            <i class="fa-solid fa-question-circle text-blue-600"></i>
                                        </div>
                                        <div class="min-w-0 flex-1">
                                            <div class="text-sm font-medium text-gray-900">{{ $article->title }}</div>
                                            @if($article->excerpt)
                                                <div class="text-sm text-gray-500 mt-1">{{ Str::limit($article->excerpt, 80) }}</div>
                                            @endif
                                            <div class="text-xs text-gray-400 mt-1">{{ $article->slug }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                        @switch($article->difficulty_level)
                                            @case('beginner') bg-green-100 text-green-800 @break
                                            @case('intermediate') bg-yellow-100 text-yellow-800 @break
                                            @case('advanced') bg-red-100 text-red-800 @break
                                        @endswitch">
                                        @switch($article->difficulty_level)
                                            @case('beginner') <i class="fa-solid fa-seedling mr-1"></i>Beginner @break
                                            @case('intermediate') <i class="fa-solid fa-graduation-cap mr-1"></i>Intermediate @break
                                            @case('advanced') <i class="fa-solid fa-rocket mr-1"></i>Advanced @break
                                        @endswitch
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    @if($article->productFeatures && $article->productFeatures->count() > 0)
                                        <div class="flex flex-wrap gap-1">
                                            @foreach($article->productFeatures->take(2) as $feature)
                                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                                    @if($feature->icon)
                                                        <i class="fa-solid {{ $feature->icon }} mr-1"></i>
                                                    @endif
                                                    {{ $feature->name }}
                                                </span>
                                            @endforeach
                                            @if($article->productFeatures->count() > 2)
                                                <span class="text-xs text-gray-500">+{{ $article->productFeatures->count() - 2 }} more</span>
                                            @endif
                                        </div>
                                    @else
                                        <span class="text-gray-400 text-sm">No features</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4">
                                    @if($article->estimated_read_time)
                                        <div class="flex items-center text-sm text-gray-600">
                                            <i class="fa-solid fa-clock mr-1"></i>
                                            {{ $article->estimated_read_time }} min
                                        </div>
                                    @else
                                        <span class="text-gray-400 text-sm">Not set</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex flex-col space-y-1">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $article->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                            {{ $article->is_active ? 'Active' : 'Inactive' }}
                                        </span>
                                        @if($article->is_featured)
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                                <i class="fa-solid fa-star mr-1"></i>Featured
                                            </span>
                                        @endif
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <div class="flex items-center justify-end space-x-2">
                                        <a href="{{ route('admin.marketing.help-article.show', $article) }}"
                                           class="text-blue-600 hover:text-blue-900"
                                           title="View">
                                            <i class="fa-solid fa-eye"></i>
                                        </a>
                                        <a href="{{ route('admin.marketing.help-article.edit', $article) }}"
                                           class="text-blue-600 hover:text-blue-900"
                                           title="Edit">
                                            <i class="fa-solid fa-edit"></i>
                                        </a>
                                        <button onclick="deleteArticle({{ $article->id }})"
                                                class="text-red-600 hover:text-red-900"
                                                title="Delete">
                                            <i class="fa-solid fa-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="text-center py-12">
                <i class="fa-solid fa-question-circle text-4xl text-gray-400 mb-4"></i>
                <p class="text-gray-600">No help articles found. Create your first help article!</p>
            </div>
        @endif
    </div>

    {{-- Statistics --}}
    @if($helpArticles->count() > 0)
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <div class="flex items-center">
                    <div class="h-12 w-12 rounded-lg bg-blue-50 flex items-center justify-center">
                        <i class="fa-solid fa-file-alt text-blue-600 text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Total Articles</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $helpArticles->count() }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <div class="flex items-center">
                    <div class="h-12 w-12 rounded-lg bg-green-50 flex items-center justify-center">
                        <i class="fa-solid fa-check-circle text-green-600 text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Active Articles</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $helpArticles->where('is_active', true)->count() }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <div class="flex items-center">
                    <div class="h-12 w-12 rounded-lg bg-yellow-50 flex items-center justify-center">
                        <i class="fa-solid fa-star text-yellow-600 text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Featured</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $helpArticles->where('is_featured', true)->count() }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <div class="flex items-center">
                    <div class="h-12 w-12 rounded-lg bg-purple-50 flex items-center justify-center">
                        <i class="fa-solid fa-clock text-purple-600 text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Avg. Read Time</p>
                        <p class="text-2xl font-bold text-gray-900">
                            {{ $helpArticles->where('estimated_read_time', '>', 0)->avg('estimated_read_time') ? round($helpArticles->where('estimated_read_time', '>', 0)->avg('estimated_read_time')) : 0 }} min
                        </p>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>

{{-- Delete Modal --}}
<div id="deleteModal" class="fixed inset-0 bg-gray-600/50 overflow-y-auto h-full w-full hidden z-50">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <div class="flex items-center mb-4">
                <i class="fa-solid fa-exclamation-triangle text-red-500 text-2xl mr-3"></i>
                <h3 class="text-lg font-semibold text-gray-900">Delete Help Article</h3>
            </div>
            <p class="text-gray-600 mb-6">
                Are you sure you want to delete this help article? This action cannot be undone.
            </p>
            <div class="flex items-center justify-end space-x-4">
                <button type="button"
                        onclick="document.getElementById('deleteModal').classList.add('hidden')"
                        class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400 transition-colors duration-200">
                    Cancel
                </button>
                <form id="deleteForm" method="POST" class="inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit"
                            class="px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700 transition-colors duration-200">
                        Delete
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
function deleteArticle(articleId) {
    document.getElementById('deleteForm').action = `/admin/marketing/help-article/${articleId}`;
    document.getElementById('deleteModal').classList.remove('hidden');
}
</script>
</x-layouts.admin>
