<x-layouts.admin title="Hero / Header section backgrounds">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-8">
        <div>
            <h1 class="text-3xl font-bold text-zinc-900 dark:text-white mb-2">Hero section backgrounds</h1>
            <p class="text-zinc-600 dark:text-zinc-400">Set background images for the hero/header section on contact, blog, solutions, modules, docs, academy and demo request (list/static pages only). Leave empty to keep the current default (colour or gradient).</p>
        </div>
    </div>

    @if (session('status') === 'hero-settings-updated')
        <div class="mb-6 rounded-lg bg-green-50 dark:bg-green-500/10 border border-green-200 dark:border-green-500/20 p-4">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <i class="fa-solid fa-check-circle text-green-500 text-lg"></i>
                </div>
                <div class="ml-3">
                    <p class="text-sm font-medium text-green-800 dark:text-green-300">Hero background settings saved.</p>
                </div>
            </div>
        </div>
    @endif

    <form method="POST" action="{{ route('admin.settings.hero-backgrounds.update') }}" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="rounded-lg border border-gray-200 dark:border-white/10 bg-white dark:bg-white/5 p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                @foreach([
                    'hero_background_contact' => ['Contact', '/api/contact'],
                    'hero_background_blog' => ['Blog', '/api/blog'],
                    'hero_background_solutions_index' => ['Solutions', '/api/solutions'],
                    'hero_background_modules_index' => ['Modules', '/api/modules'],
                    'hero_background_docs' => ['Docs', '/api/docs'],
                    'hero_background_academy' => ['Academy', '/api/course'],
                    'hero_background_trial' => ['Demo request', '/api/proefversie'],
                ] as $key => $label)
                    <div>
                        <x-image-upload
                            id="{{ $key }}"
                            name="{{ $key }}"
                            label="{{ $label[0] }}"
                            :current-image="\App\Models\Setting::hasFile($key) ? get_image(get_setting($key)) : null"
                            help-text="{{ $label[1] }} — JPEG, PNG, WebP max 20MB"
                            :max-size="20480"
                            current-image-alt="Hero background"
                        />
                    </div>
                @endforeach
            </div>
        </div>

        <div class="mt-8 flex justify-end">
            <button type="submit" class="inline-flex items-center gap-2 rounded-md bg-[var(--color-accent)] px-4 py-2 text-sm font-semibold text-white shadow-xs hover:opacity-90 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-[var(--color-accent)]">
                <i class="fa-solid fa-save"></i>
                Save hero backgrounds
            </button>
        </div>
    </form>
</x-layouts.admin>
