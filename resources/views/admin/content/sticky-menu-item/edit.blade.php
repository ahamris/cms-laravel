<x-layouts.admin title="Edit Sticky Menu Item">
    <div class="space-y-6">
        {{-- Page Header --}}
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold tracking-tight text-zinc-900 dark:text-white">Edit {{ $stickyMenuItem->title }}</h1>
                <p class="text-sm text-zinc-500 dark:text-zinc-400">Update the details of this menu item.</p>
            </div>
            
            <x-ui.button variant="secondary" icon="arrow-left" href="{{ route('admin.content.sticky-menu-item.index') }}">
                Cancel & Return
            </x-ui.button>
        </div>

        <form action="{{ route('admin.content.sticky-menu-item.update', $stickyMenuItem) }}" method="POST" class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            @csrf
            @method('PUT')

            {{-- Main Content Column --}}
            <div class="lg:col-span-2 space-y-6">
                
                {{-- General Information Section --}}
                <div class="bg-white dark:bg-zinc-800 rounded-xl border border-zinc-200 dark:border-zinc-700 p-6 space-y-6">
                    <h3 class="text-lg font-semibold text-zinc-900 dark:text-white flex items-center gap-2">
                        <i class="fa-solid fa-pen-to-square text-zinc-400"></i>
                        General Information
                    </h3>
                    
                    {{-- Title Input --}}
                    <x-ui.input 
                        label="Display Title"
                        name="title" 
                        id="title" 
                        :value="old('title', $stickyMenuItem->title)"
                        placeholder="Enter menu item title (e.g. 'Support')" 
                        required
                        hint="This title will be visible to users in the sticky menu."
                    />
                    
                    {{-- Link Input --}}
                    <x-ui.input 
                        label="Destination URL"
                        name="link" 
                        id="link" 
                        :value="old('link', $stickyMenuItem->link)"
                        placeholder="https://example.com/page or /internal-path" 
                        required
                        icon="link"
                    />

                    {{-- Icon Selection --}}
                    <div>
                        <x-icon-picker
                            name="icon"
                            :value="old('icon', $stickyMenuItem->icon)"
                            required
                        />
                        @error('icon')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                        <p class="mt-1 text-xs text-zinc-500">Select an icon that best represents this menu item.</p>
                    </div>
                </div>


            </div>

            {{-- Sidebar Column --}}
            <div class="lg:col-span-1 space-y-6">
                
                {{-- Publish Settings --}}
                <div class="bg-white dark:bg-zinc-800 rounded-xl border border-zinc-200 dark:border-zinc-700 p-6 space-y-6">
                    <h3 class="text-lg font-semibold text-zinc-900 dark:text-white flex items-center gap-2">
                        <i class="fa-solid fa-globe text-zinc-400"></i>
                        Publish Settings
                    </h3>

                    {{-- Active Toggle --}}
                    <div class="flex items-center justify-between">
                        <div class="flex flex-col">
                            <span class="text-sm font-medium text-zinc-900 dark:text-white">Active Status</span>
                            <span class="text-xs text-zinc-500">Enable to show on site</span>
                        </div>
                        <x-ui.toggle 
                            name="is_active" 
                            :checked="old('is_active', $stickyMenuItem->is_active)"
                        />
                    </div>

                    {{-- External Link Toggle --}}
                    <div class="flex items-center justify-between">
                        <div class="flex flex-col">
                            <span class="text-sm font-medium text-zinc-900 dark:text-white">Open in New Tab</span>
                            <span class="text-xs text-zinc-500">External link behavior</span>
                        </div>
                        <x-ui.toggle 
                            name="is_external" 
                            :checked="old('is_external', $stickyMenuItem->is_external)"
                        />
                    </div>

                    <hr class="border-zinc-200 dark:border-zinc-700">

                    {{-- Sort Order --}}
                    <x-ui.input 
                        label="Sort Order"
                        type="number"
                        name="sort_order" 
                        id="sort_order" 
                        :value="old('sort_order', $stickyMenuItem->sort_order)"
                        placeholder="0" 
                        min="0"
                        hint="Higher numbers appear later in the list."
                    />
                    
                    <x-ui.button type="submit" variant="primary" class="w-full justify-center">
                        Save Changes
                    </x-ui.button>
                </div>

                 {{-- Meta Info --}}
                 <div class="rounded-xl border border-zinc-200 bg-zinc-50 p-6 dark:border-zinc-700 dark:bg-zinc-800/50">
                    <h4 class="text-sm font-semibold text-zinc-900 dark:text-white mb-4">Item Metadata</h4>
                    <dl class="space-y-3">
                        <div class="flex justify-between">
                            <dt class="text-sm text-zinc-500">Created At</dt>
                            <dd class="text-sm font-medium text-zinc-700 dark:text-zinc-300">{{ $stickyMenuItem->created_at->format('M d, Y H:i') }}</dd>
                        </div>
                        <div class="flex justify-between">
                            <dt class="text-sm text-zinc-500">Last Updated</dt>
                            <dd class="text-sm font-medium text-zinc-700 dark:text-zinc-300">{{ $stickyMenuItem->updated_at->format('M d, Y H:i') }}</dd>
                        </div>
                        <div class="flex justify-between">
                            <dt class="text-sm text-zinc-500">ID</dt>
                            <dd class="text-sm font-mono font-medium text-zinc-700 dark:text-zinc-300">#{{ $stickyMenuItem->id }}</dd>
                        </div>
                    </dl>
                </div>
            </div>
        </form>
    </div>
</x-layouts.admin>