<x-layouts.admin title="Add Social Media Platform">
    <div class="space-y-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-zinc-900 dark:text-white mb-2">Add Social Media Platform</h1>
                <p class="text-zinc-600 dark:text-zinc-400">Create a new social media platform for sharing and widgets</p>
            </div>
            <a href="{{ route('admin.settings.social-media-platforms.index') }}" class="inline-flex items-center gap-2 rounded-md bg-white dark:bg-white/10 px-4 py-2 text-sm font-semibold text-zinc-900 dark:text-white shadow-xs ring-1 ring-zinc-300 dark:ring-white/10 hover:bg-zinc-50 dark:hover:bg-white/20">
                <i class="fa-solid fa-arrow-left"></i> Back
            </a>
        </div>

        <form action="{{ route('admin.settings.social-media-platforms.store') }}" method="POST" class="max-w-2xl">
            @csrf

            @if($errors->any())
                <div class="mb-6 rounded-lg bg-red-50 dark:bg-red-500/10 border border-red-200 dark:border-red-500/20 p-4">
                    <ul class="list-disc list-inside text-sm text-red-800 dark:text-red-300">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="rounded-lg border border-gray-200 dark:border-white/10 bg-white dark:bg-white/5 p-6 space-y-6">
                <x-ui.input id="name" name="name" :value="old('name')" label="Name" placeholder="e.g. LinkedIn" required />
                <x-ui.input id="slug" name="slug" :value="old('slug')" label="Slug" placeholder="e.g. linkedin" required />
                <x-ui.icon-picker id="icon" name="icon" :value="old('icon')" label="Icon (FontAwesome)" help-text="Select a FontAwesome icon for the platform" :required="false" />
                <div>
                    <label for="color" class="block text-sm font-medium text-zinc-900 dark:text-white">Color (hex)</label>
                    <div class="mt-2 flex items-center gap-2">
                        <input type="color" id="color-picker" value="{{ old('color', '#0077b5') }}" class="h-10 w-14 rounded border border-gray-300 dark:border-white/20 cursor-pointer">
                        <x-ui.input id="color" name="color" type="text" :value="old('color', '#0077b5')" placeholder="#0077b5" maxlength="7" class="flex-1" />
                    </div>
                    @error('color')<p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>@enderror
                </div>
                <x-ui.input id="sort_order" name="sort_order" type="number" :value="old('sort_order', 0)" label="Sort order" min="0" />
                <div>
                    <x-ui.toggle id="is_active" name="is_active" :checked="old('is_active', true)" label="Active" />
                </div>
            </div>

            <div class="rounded-lg border border-amber-200 dark:border-amber-500/30 bg-amber-50/50 dark:bg-amber-500/5 p-6 space-y-4 mt-6">
                <h2 class="text-lg font-semibold text-zinc-900 dark:text-white">API credentials (for auto-posting)</h2>
                <p class="text-sm text-zinc-600 dark:text-zinc-400">Use one of these slugs to enable credential fields: <code class="text-xs bg-zinc-200 dark:bg-zinc-700 px-1 rounded">{{ implode(', ', $supportedSlugs) }}</code></p>
                @foreach ($credentialFieldsBySlug as $platformSlug => $fields)
                    @if (count($fields) > 0)
                        <div data-credential-block="{{ $platformSlug }}" class="credential-block hidden space-y-4">
                            @foreach ($fields as $field)
                                <div>
                                    <label for="api_credentials_{{ $platformSlug }}_{{ $field['key'] }}" class="block text-sm font-medium text-zinc-700 dark:text-zinc-300">{{ $field['label'] }}</label>
                                    <input type="{{ $field['type'] ?? 'text' }}" id="api_credentials_{{ $platformSlug }}_{{ $field['key'] }}" name="api_credentials[{{ $field['key'] }}]" value="{{ old('api_credentials.'.$field['key']) }}" autocomplete="off" class="mt-1 block w-full rounded-md border border-gray-300 dark:border-white/20 bg-white dark:bg-zinc-800 px-3 py-2 text-sm">
                                </div>
                            @endforeach
                        </div>
                    @endif
                @endforeach
            </div>

            <div class="mt-6 flex gap-3">
                <x-button type="submit" variant="primary">Create Platform</x-button>
                <a href="{{ route('admin.settings.social-media-platforms.index') }}"><x-button type="button" variant="secondary">Cancel</x-button></a>
            </div>
        </form>
    </div>
    <script>
        document.getElementById('color-picker')?.addEventListener('input', function() {
            document.getElementById('color').value = this.value;
        });
        document.getElementById('color')?.addEventListener('input', function() {
            if (/^#[0-9A-Fa-f]{6}$/.test(this.value)) {
                document.getElementById('color-picker').value = this.value;
            }
        });
        function toggleCredentialBlocks() {
            var slug = (document.getElementById('slug')?.value || '').toLowerCase().trim();
            document.querySelectorAll('.credential-block').forEach(function(el) {
                var blockSlug = el.getAttribute('data-credential-block');
                el.classList.toggle('hidden', blockSlug !== slug);
            });
        }
        document.getElementById('slug')?.addEventListener('input', toggleCredentialBlocks);
        document.getElementById('slug')?.addEventListener('change', toggleCredentialBlocks);
        toggleCredentialBlocks();
    </script>
</x-layouts.admin>
