<x-layouts.admin>
    <div class="space-y-6">
        {{-- Header --}}
        <div class="flex justify-between items-center">
            <div class="flex items-center gap-3">
                <div class="w-12 h-12 bg-[var(--color-accent)] dark:bg-[var(--color-accent-content)] rounded-md flex items-center justify-center">
                    <i class="fa-solid fa-info-circle text-white text-base"></i>
                </div>
                <div class="flex flex-col gap-1">
                    <h2 class="text-2xl font-bold text-zinc-900 dark:text-white">{{ $organizationName->display_name }}</h2>
                    <p class="text-sm text-zinc-600 dark:text-zinc-400">Organization details and information</p>
                </div>
            </div>
            <div class="flex items-center gap-2">
                <x-button variant="outline-primary" href="{{ route('admin.content.organization-name.edit', $organizationName) }}" icon="edit">
                    Edit
                </x-button>
                <x-button variant="default" href="{{ route('admin.content.organization-name.index') }}" icon="arrow-left">
                    Back to List
                </x-button>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            {{-- Main Content --}}
            <div class="lg:col-span-2 space-y-6">
                {{-- Basic Information Card --}}
                <x-ui.card variant="filled">
                    <x-slot:header>
                        <h3 class="text-base font-semibold text-zinc-900 dark:text-white flex items-center gap-2">
                            <i class="fas fa-building text-[var(--color-accent)] dark:text-[var(--color-accent-content)]"></i>
                            Basic Information
                        </h3>
                    </x-slot:header>
                    <x-slot:body>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-xs font-medium text-zinc-700 dark:text-zinc-300 mb-1">Organization Name</label>
                                <p class="text-sm font-semibold text-zinc-900 dark:text-white">{{ $organizationName->name }}</p>
                            </div>
                            @if($organizationName->abbreviation)
                            <div>
                                <label class="block text-xs font-medium text-zinc-700 dark:text-zinc-300 mb-1">Abbreviation</label>
                                <x-badge variant="sky" size="sm">{{ $organizationName->abbreviation }}</x-badge>
                            </div>
                            @endif
                        </div>

                        @if($organizationName->address)
                        <div class="mt-6">
                            <label class="block text-xs font-medium text-zinc-700 dark:text-zinc-300 mb-1">Address</label>
                            <div class="bg-white dark:bg-zinc-900 rounded-md p-4 border border-zinc-200 dark:border-zinc-700">
                                <p class="text-sm text-zinc-900 dark:text-white whitespace-pre-line">{{ $organizationName->address }}</p>
                            </div>
                        </div>
                        @endif
                    </x-slot:body>
                </x-ui.card>

                {{-- Contact Information Card --}}
                @if($organizationName->email)
                <x-ui.card variant="filled">
                    <x-slot:header>
                        <h3 class="text-base font-semibold text-zinc-900 dark:text-white flex items-center gap-2">
                            <i class="fas fa-envelope text-[var(--color-accent)] dark:text-[var(--color-accent-content)]"></i>
                            Contact Information
                        </h3>
                    </x-slot:header>
                    <x-slot:body>
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 bg-blue-100 dark:bg-blue-900/30 rounded-full flex items-center justify-center">
                                    <i class="fas fa-envelope text-blue-600 dark:text-blue-400 text-xs"></i>
                                </div>
                                <div>
                                    <p class="text-xs font-medium text-zinc-700 dark:text-zinc-300">Email Address</p>
                                    <a href="mailto:{{ $organizationName->email }}"
                                       class="text-sm text-[var(--color-accent)] dark:text-[var(--color-accent-content)] hover:opacity-80 font-medium transition-colors">
                                        {{ $organizationName->email }}
                                    </a>
                                </div>
                            </div>
                            <button onclick="copyToClipboard('{{ $organizationName->email }}')"
                                    class="p-2 text-sm text-zinc-400 dark:text-zinc-500 hover:text-zinc-600 dark:hover:text-zinc-400 rounded-md hover:bg-zinc-100 dark:hover:bg-zinc-800 transition-colors"
                                    title="Copy email">
                                <i class="fas fa-copy"></i>
                            </button>
                        </div>
                    </x-slot:body>
                </x-ui.card>
                @endif

                {{-- Quick Actions Card --}}
                <x-ui.card variant="filled">
                    <x-slot:header>
                        <h3 class="text-base font-semibold text-zinc-900 dark:text-white flex items-center gap-2">
                            <i class="fas fa-bolt text-[var(--color-accent)] dark:text-[var(--color-accent-content)]"></i>
                            Quick Actions
                        </h3>
                    </x-slot:header>
                    <x-slot:body>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <form action="{{ route('admin.content.organization-name.toggle-active', $organizationName) }}" method="POST" class="inline">
                                @csrf
                                @method('PATCH')
                                <x-button 
                                    type="submit"
                                    :variant="$organizationName->is_active ? 'error' : 'success'"
                                    class="w-full"
                                    :icon="$organizationName->is_active ? 'pause' : 'play'"
                                >
                                    {{ $organizationName->is_active ? 'Deactivate' : 'Activate' }}
                                </x-button>
                            </form>
                            <x-button 
                                variant="outline-primary"
                                onclick="copyToClipboard('{{ $organizationName->display_name }}')"
                                icon="copy"
                                class="w-full"
                            >
                                Copy Name
                            </x-button>
                        </div>
                    </x-slot:body>
                </x-ui.card>
            </div>

            {{-- Sidebar --}}
            <div class="space-y-6">
                {{-- Status Card --}}
                <x-ui.card variant="filled">
                    <x-slot:header>
                        <h3 class="text-base font-semibold text-zinc-900 dark:text-white">Status</h3>
                    </x-slot:header>
                    <x-slot:body>
                        <div class="space-y-4">
                            <div class="flex items-center justify-between">
                                <span class="text-xs font-medium text-zinc-700 dark:text-zinc-300">Active Status</span>
                                <x-badge :variant="$organizationName->is_active ? 'success' : 'error'" size="sm">
                                    <i class="fas {{ $organizationName->is_active ? 'fa-check' : 'fa-times' }} mr-1"></i>
                                    {{ $organizationName->is_active ? 'Active' : 'Inactive' }}
                                </x-badge>
                            </div>
                            <div class="flex items-center justify-between">
                                <span class="text-xs font-medium text-zinc-700 dark:text-zinc-300">Sort Order</span>
                                <span class="text-sm text-zinc-900 dark:text-white font-mono">{{ $organizationName->sort_order }}</span>
                            </div>
                        </div>
                    </x-slot:body>
                </x-ui.card>

                {{-- Metadata Card --}}
                <x-ui.card variant="filled">
                    <x-slot:header>
                        <h3 class="text-base font-semibold text-zinc-900 dark:text-white">Metadata</h3>
                    </x-slot:header>
                    <x-slot:body>
                        <div class="space-y-4">
                            <div>
                                <label class="block text-xs font-medium text-zinc-700 dark:text-zinc-300 mb-1">ID</label>
                                <p class="text-sm text-zinc-900 dark:text-white font-mono">{{ $organizationName->id }}</p>
                            </div>
                            <div>
                                <label class="block text-xs font-medium text-zinc-700 dark:text-zinc-300 mb-1">Created</label>
                                <p class="text-sm text-zinc-900 dark:text-white">{{ $organizationName->created_at->format('M j, Y') }}</p>
                                <p class="text-xs text-zinc-500 dark:text-zinc-400">{{ $organizationName->created_at->format('g:i A') }}</p>
                            </div>
                            <div>
                                <label class="block text-xs font-medium text-zinc-700 dark:text-zinc-300 mb-1">Last Updated</label>
                                <p class="text-sm text-zinc-900 dark:text-white">{{ $organizationName->updated_at->format('M j, Y') }}</p>
                                <p class="text-xs text-zinc-500 dark:text-zinc-400">{{ $organizationName->updated_at->format('g:i A') }}</p>
                            </div>
                        </div>
                    </x-slot:body>
                </x-ui.card>

                {{-- Search Information Card --}}
                <x-ui.card variant="filled">
                    <x-slot:header>
                        <h3 class="text-base font-semibold text-zinc-900 dark:text-white flex items-center gap-2">
                            <i class="fas fa-search text-[var(--color-accent)] dark:text-[var(--color-accent-content)]"></i>
                            Search Information
                        </h3>
                    </x-slot:header>
                    <x-slot:body>
                        <div class="space-y-3">
                            <div class="flex items-center justify-between">
                                <span class="text-xs font-medium text-zinc-700 dark:text-zinc-300">Searchable</span>
                                <x-badge :variant="$organizationName->shouldBeSearchable() ? 'success' : 'secondary'" size="sm">
                                    {{ $organizationName->shouldBeSearchable() ? 'Yes' : 'No' }}
                                </x-badge>
                            </div>
                            <div>
                                <span class="text-xs font-medium text-zinc-700 dark:text-zinc-300 block mb-1">Search Collection</span>
                                <p class="text-xs text-zinc-500 dark:text-zinc-400 font-mono">{{ $organizationName->searchableAs() }}</p>
                            </div>
                        </div>
                    </x-slot:body>
                </x-ui.card>

                {{-- Danger Zone Card --}}
                <x-ui.card variant="filled" class="border-red-200 dark:border-red-900/50">
                    <x-slot:header>
                        <h3 class="text-base font-semibold text-red-900 dark:text-red-400 flex items-center gap-2">
                            <i class="fas fa-exclamation-triangle text-red-600 dark:text-red-400"></i>
                            Danger Zone
                        </h3>
                    </x-slot:header>
                    <x-slot:body>
                        <p class="text-xs text-zinc-600 dark:text-zinc-400 mb-4">
                            Deleting this organization name is permanent and cannot be undone.
                        </p>
                        <form action="{{ route('admin.content.organization-name.destroy', $organizationName) }}"
                              method="POST"
                              onsubmit="return confirm('Are you absolutely sure you want to delete this organization name? This action cannot be undone.');">
                            @csrf
                            @method('DELETE')
                            <x-button 
                                type="submit"
                                variant="danger"
                                icon="trash"
                                class="w-full"
                            >
                                Delete Organization Name
                            </x-button>
                        </form>
                    </x-slot:body>
                </x-ui.card>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        function copyToClipboard(text) {
            navigator.clipboard.writeText(text).then(function() {
                // Show success message
                const toast = document.createElement('div');
                toast.className = 'fixed top-4 right-4 bg-green-500 dark:bg-green-600 text-white px-4 py-2 rounded-lg shadow-lg z-50';
                toast.textContent = 'Copied to clipboard!';
                document.body.appendChild(toast);

                setTimeout(() => {
                    toast.remove();
                }, 2000);
            }).catch(function(err) {
                console.error('Could not copy text: ', err);

                // Fallback for older browsers
                const textArea = document.createElement('textarea');
                textArea.value = text;
                document.body.appendChild(textArea);
                textArea.select();
                document.execCommand('copy');
                document.body.removeChild(textArea);

                const toast = document.createElement('div');
                toast.className = 'fixed top-4 right-4 bg-green-500 dark:bg-green-600 text-white px-4 py-2 rounded-lg shadow-lg z-50';
                toast.textContent = 'Copied to clipboard!';
                document.body.appendChild(toast);

                setTimeout(() => {
                    toast.remove();
                }, 2000);
            });
        }
    </script>
    @endpush
</x-layouts.admin>
