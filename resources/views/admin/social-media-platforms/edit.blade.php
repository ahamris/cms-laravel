<x-layouts.admin title="Edit Social Media Platform">
    <div class="space-y-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-zinc-900 dark:text-white mb-2">Edit {{ $socialMediaPlatform->name }}</h1>
                <p class="text-zinc-600 dark:text-zinc-400">Update platform settings and credentials</p>
            </div>
            <a href="{{ route('admin.settings.social-media-platforms.index') }}" class="inline-flex items-center gap-2 rounded-md bg-white dark:bg-white/10 px-4 py-2 text-sm font-semibold text-zinc-900 dark:text-white shadow-xs ring-1 ring-zinc-300 dark:ring-white/10 hover:bg-zinc-50 dark:hover:bg-white/20">
                <i class="fa-solid fa-arrow-left"></i> Back
            </a>
        </div>

        @if (session('success'))
            <div class="rounded-lg bg-green-50 dark:bg-green-500/10 border border-green-200 dark:border-green-500/20 p-4">
                <p class="text-sm font-medium text-green-800 dark:text-green-300">{{ session('success') }}</p>
            </div>
        @endif

        <form action="{{ route('admin.settings.social-media-platforms.update', $socialMediaPlatform) }}" method="POST">
            @csrf
            @method('PUT')

            @if($errors->any())
                <div class="mb-6 rounded-lg bg-red-50 dark:bg-red-500/10 border border-red-200 dark:border-red-500/20 p-4">
                    <ul class="list-disc list-inside text-sm text-red-800 dark:text-red-300">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                {{-- Left: Platform settings --}}
                <div class="rounded-xl border border-zinc-200 dark:border-white/10 bg-white dark:bg-white/5 p-6 space-y-6 shadow-sm">
                    <div class="flex items-center gap-2 border-b border-zinc-200 dark:border-white/10 pb-3">
                        <i class="fa-solid fa-sliders text-zinc-500 dark:text-zinc-400"></i>
                        <h2 class="text-lg font-semibold text-zinc-900 dark:text-white">Platform settings</h2>
                    </div>
                    <x-ui.input id="name" name="name" :value="old('name', $socialMediaPlatform->name)" label="Name" placeholder="e.g. LinkedIn" required />
                    <x-ui.input id="slug" name="slug" :value="old('slug', $socialMediaPlatform->slug)" label="Slug" placeholder="e.g. linkedin" required />
                    <x-ui.icon-picker id="icon" name="icon" :value="old('icon', $socialMediaPlatform->icon)" label="Icon (FontAwesome)" help-text="Select a FontAwesome icon for the platform" :required="false" />
                    <div>
                        <label for="color" class="block text-sm font-medium text-zinc-900 dark:text-white">Color (hex)</label>
                        <div class="mt-2 flex items-center gap-2">
                            <input type="color" id="color-picker" value="{{ old('color', $socialMediaPlatform->color) }}" class="h-10 w-14 rounded border border-gray-300 dark:border-white/20 cursor-pointer">
                            <x-ui.input id="color" name="color" type="text" :value="old('color', $socialMediaPlatform->color)" placeholder="#0077b5" maxlength="7" class="flex-1" />
                        </div>
                        @error('color')<p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>@enderror
                    </div>
                    <x-ui.input id="sort_order" name="sort_order" type="number" :value="old('sort_order', $socialMediaPlatform->sort_order)" label="Sort order" min="0" />
                    <div>
                        <x-ui.toggle id="is_active" name="is_active" :checked="old('is_active', $socialMediaPlatform->is_active)" label="Active" />
                    </div>
                </div>

                {{-- Right: API credentials --}}
                <div class="rounded-xl border border-amber-200/60 dark:border-amber-500/20 bg-amber-50/30 dark:bg-amber-500/5 p-6 space-y-4 shadow-sm">
                    <div class="flex items-center gap-2 border-b border-amber-200/60 dark:border-amber-500/20 pb-3">
                        <i class="fa-solid fa-key text-amber-600 dark:text-amber-400"></i>
                        <h2 class="text-lg font-semibold text-zinc-900 dark:text-white">API credentials</h2>
                    </div>
                    @if (count($credentialFields) > 0)
                        <p class="text-sm text-zinc-600 dark:text-zinc-400">Leave password fields blank to keep existing values.</p>
                        @php $creds = $socialMediaPlatform->api_credentials ?? []; @endphp
                        @foreach ($credentialFields as $field)
                            @php
                                $val = old('api_credentials.'.$field['key'], $creds[$field['key']] ?? '');
                                $isPassword = ($field['type'] ?? 'text') === 'password';
                                $displayVal = $isPassword && $val ? '' : $val;
                            @endphp
                            <div>
                                <label for="api_credentials_{{ $field['key'] }}" class="block text-sm font-medium text-zinc-700 dark:text-zinc-300">{{ $field['label'] }}</label>
                                <input type="{{ $field['type'] ?? 'text' }}" id="api_credentials_{{ $field['key'] }}" name="api_credentials[{{ $field['key'] }}]" value="{{ $displayVal }}" placeholder="{{ $isPassword && !empty($creds[$field['key']]) ? '••••••••' : '' }}" autocomplete="off" class="mt-1 block w-full rounded-md border border-gray-300 dark:border-white/20 bg-white dark:bg-zinc-800 px-3 py-2 text-sm focus:ring-2 focus:ring-amber-500/30 focus:border-amber-500">
                            </div>
                        @endforeach
                    @else
                        <p class="text-sm text-zinc-600 dark:text-zinc-400">For auto-posting, set slug to one of: <code class="text-xs bg-zinc-200 dark:bg-zinc-700 px-1.5 py-0.5 rounded">{{ implode(', ', $supportedSlugs) }}</code> to see credential fields.</p>
                        <p class="text-sm text-zinc-500 dark:text-zinc-400 italic">No credential fields for the current slug.</p>
                    @endif
                </div>
            </div>

            <div class="mt-6 flex gap-3">
                <x-button type="submit" variant="primary">Update Platform</x-button>
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
    </script>
</x-layouts.admin>
