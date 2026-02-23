<x-layouts.admin title="Version History - {{ $legal->title }}">
<div class="space-y-6">
    {{-- Header --}}
    <div class="flex items-center justify-between">
        <div class="flex items-center gap-3">
            <div class="w-12 h-12 bg-primary rounded-md flex items-center justify-center">
                <i class="fa-solid fa-clock-rotate-left text-white text-base"></i>
            </div>
            <div class="flex flex-col gap-1">
                <h2>Version History</h2>
                <p>{{ $legal->title }} - {{ $versions->count() }} version(s)</p>
            </div>
        </div>
        <div class="flex items-center space-x-3">
            <a href="{{ route('admin.content.legal.edit', $legal) }}"
               class="px-5 py-2 rounded-md bg-white border border-gray-200 text-gray-700 text-sm hover:bg-gray-50 transition-colors duration-200 flex items-center space-x-2">
                <i class="fa-solid fa-edit"></i>
                <span>Edit Page</span>
            </a>
            <a href="{{ route('admin.content.legal.index') }}"
               class="px-5 py-2 rounded-md bg-white border border-gray-200 text-gray-700 text-sm hover:bg-gray-50 transition-colors duration-200 flex items-center space-x-2">
                <i class="fa-solid fa-arrow-left"></i>
                <span>Back to Legal Pages</span>
            </a>
        </div>
    </div>

    {{-- Success/Error Messages --}}
    @if(session('success'))
        <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-md text-sm">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-md text-sm">
            {{ session('error') }}
        </div>
    @endif

    {{-- Current Version Info --}}
    <div class="bg-blue-50 border border-blue-200 rounded-md p-4">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-blue-900">Current Version: v{{ $legal->current_version }}</p>
                <p class="text-xs text-blue-700 mt-1">Versioning: {{ $legal->versioning_enabled ? 'Enabled' : 'Disabled' }}</p>
            </div>
            <form action="{{ route('admin.content.legal.version.create', $legal) }}" method="POST" class="inline">
                @csrf
                <button type="submit"
                        class="px-4 py-2 rounded-md bg-blue-600 text-white text-sm hover:bg-blue-700 transition-colors duration-200 flex items-center space-x-2">
                    <i class="fa-solid fa-plus"></i>
                    <span>Create New Version</span>
                </button>
            </form>
        </div>
    </div>

    {{-- Versions Table --}}
    <div class="bg-gray-50/50 rounded-md border border-gray-200">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50/80 border-b border-gray-200">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Version</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Created</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Created By</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Notes</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($versions as $version)
                        <tr class="hover:bg-gray-50 {{ $version->version_number == $legal->current_version ? 'bg-blue-50' : '' }}">
                            <td class="px-4 py-3 whitespace-nowrap">
                                <div class="flex items-center space-x-2">
                                    <span class="text-xs font-semibold text-gray-900">{{ $version->version_label }}</span>
                                    @if($version->version_number == $legal->current_version)
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                            Current
                                        </span>
                                    @endif
                                </div>
                            </td>
                            <td class="px-4 py-3 whitespace-nowrap text-xs text-gray-500">
                                {{ $version->created_at->format('M d, Y H:i') }}
                            </td>
                            <td class="px-4 py-3 whitespace-nowrap text-xs text-gray-500">
                                {{ $version->creator ? $version->creator->name : 'System' }}
                            </td>
                            <td class="px-4 py-3 text-xs text-gray-500">
                                <div class="max-w-xs truncate" title="{{ $version->version_notes }}">
                                    {{ $version->version_notes ?: '-' }}
                                </div>
                            </td>
                            <td class="px-4 py-3 whitespace-nowrap">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $version->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                    {{ $version->is_active ? 'Active' : 'Inactive' }}
                                </span>
                            </td>
                            <td class="px-4 py-3 whitespace-nowrap text-sm font-medium">
                                <div class="flex items-center space-x-2">
                                    <a href="{{ route('admin.content.legal.version.show', [$legal, $version->version_number]) }}"
                                       class="text-xs text-gray-600 hover:text-primary transition-colors duration-200"
                                       title="View Version">
                                        <i class="fa-solid fa-eye"></i>
                                    </a>
                                    @if($version->version_number != $legal->current_version)
                                        <form action="{{ route('admin.content.legal.version.restore', [$legal, $version->version_number]) }}"
                                              method="POST" class="inline"
                                              onsubmit="return confirm('Are you sure you want to restore version {{ $version->version_number }}? This will create a new version with the current state before restoring.')">
                                            @csrf
                                            <button type="submit"
                                                    class="text-xs text-gray-600 hover:text-green-600 transition-colors duration-200 focus:outline-none"
                                                    title="Restore Version">
                                                <i class="fa-solid fa-rotate-left"></i>
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center text-gray-500">
                                <div class="flex flex-col items-center">
                                    <i class="fa-solid fa-clock-rotate-left text-3xl text-gray-300 mb-2"></i>
                                    <p class="text-base font-medium mb-1">No versions found</p>
                                    <p class="text-xs text-gray-500">Versions will be created automatically when you update the page.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
</x-layouts.admin>

