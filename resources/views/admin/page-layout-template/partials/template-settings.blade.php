@php
    $t = $pageLayoutTemplate ?? null;
    $shell = old('shell_section');
    if (! is_string($shell) || ! in_array($shell, ['none', 'header', 'hero'], true)) {
        if ($t) {
            if ($t->use_header_section && $t->use_hero_section) {
                $shell = 'header';
            } elseif ($t->use_hero_section) {
                $shell = 'hero';
            } elseif ($t->use_header_section) {
                $shell = 'header';
            } else {
                $shell = 'none';
            }
        } else {
            $shell = 'none';
        }
    }
@endphp

<div class="rounded-md border border-zinc-200 bg-zinc-50/50 p-6 dark:border-zinc-700 dark:bg-zinc-900/40">
    <h3 class="mb-4 text-sm font-semibold text-zinc-800 dark:text-zinc-100">{{ __('Template') }}</h3>
    <div class="space-y-4">
        <div>
            <label for="name" class="mb-1.5 block text-xs font-medium text-zinc-600 dark:text-zinc-400">{{ __('Name') }} <span class="text-red-500">*</span></label>
            <input type="text" id="name" name="name" value="{{ old('name', $t?->name) }}" required
                class="w-full rounded-md border border-zinc-200 bg-white px-3 py-2 text-sm dark:border-zinc-600 dark:bg-zinc-900 @error('name') border-red-500 @enderror"
                placeholder="{{ __('e.g. Landing — standard') }}">
            @error('name')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>
        <div>
            <label for="description" class="mb-1.5 block text-xs font-medium text-zinc-600 dark:text-zinc-400">{{ __('Internal note') }}</label>
            <p class="mb-1.5 text-xs text-zinc-500 dark:text-zinc-400">{{ __('For editors only—not the page intro. Use rows below to place the page intro (short body) on the layout.') }}</p>
            <textarea id="description" name="description" rows="3"
                class="w-full rounded-md border border-zinc-200 bg-white px-3 py-2 text-sm dark:border-zinc-600 dark:bg-zinc-900 @error('description') border-red-500 @enderror"
                placeholder="{{ __('When to use this template, client notes, etc.') }}">{{ old('description', $t?->description) }}</textarea>
            @error('description')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <fieldset class="rounded-lg border border-zinc-200 bg-white p-4 dark:border-zinc-600 dark:bg-zinc-950">
            <legend class="px-1 text-xs font-semibold text-zinc-800 dark:text-zinc-100">{{ __('Page header section (header type)') }}</legend>
            <p class="mt-1 text-xs text-zinc-500 dark:text-zinc-400">{{ __('Choose at most one top shell. The frontend uses this to decide between a standard header and a hero-style top section.') }}</p>

            <div class="mt-4 grid grid-cols-1 gap-3 sm:grid-cols-3">
                <label class="flex cursor-pointer flex-col rounded-xl border-2 border-zinc-200 bg-zinc-50/50 p-4 transition has-[:checked]:border-primary has-[:checked]:bg-primary/5 has-[:checked]:ring-2 has-[:checked]:ring-primary/20 dark:border-zinc-600 dark:bg-zinc-900/40 dark:has-[:checked]:bg-primary/10">
                    <input type="radio" name="shell_section" value="header" class="sr-only" @checked($shell === 'header')>
                    <span class="mb-2 flex h-11 w-11 items-center justify-center rounded-lg bg-zinc-200 text-zinc-700 dark:bg-zinc-800 dark:text-zinc-200">
                        <i class="fa-solid fa-bars text-lg" aria-hidden="true"></i>
                    </span>
                    <span class="text-sm font-semibold text-zinc-900 dark:text-zinc-100">{{ __('Header') }}</span>
                    <span class="mt-1 text-xs text-zinc-500 dark:text-zinc-400">{{ __('Navigation / site header shell') }}</span>
                </label>
                <label class="flex cursor-pointer flex-col rounded-xl border-2 border-zinc-200 bg-zinc-50/50 p-4 transition has-[:checked]:border-primary has-[:checked]:bg-primary/5 has-[:checked]:ring-2 has-[:checked]:ring-primary/20 dark:border-zinc-600 dark:bg-zinc-900/40 dark:has-[:checked]:bg-primary/10">
                    <input type="radio" name="shell_section" value="hero" class="sr-only" @checked($shell === 'hero')>
                    <span class="mb-2 flex h-11 w-11 items-center justify-center rounded-lg bg-zinc-200 text-zinc-700 dark:bg-zinc-800 dark:text-zinc-200">
                        <i class="fa-solid fa-image text-lg" aria-hidden="true"></i>
                    </span>
                    <span class="text-sm font-semibold text-zinc-900 dark:text-zinc-100">{{ __('Hero') }}</span>
                    <span class="mt-1 text-xs text-zinc-500 dark:text-zinc-400">{{ __('Large top hero / banner shell') }}</span>
                </label>
                <label class="flex cursor-pointer flex-col rounded-xl border-2 border-zinc-200 bg-zinc-50/50 p-4 transition has-[:checked]:border-primary has-[:checked]:bg-primary/5 has-[:checked]:ring-2 has-[:checked]:ring-primary/20 dark:border-zinc-600 dark:bg-zinc-900/40 dark:has-[:checked]:bg-primary/10">
                    <input type="radio" name="shell_section" value="none" class="sr-only" @checked($shell === 'none')>
                    <span class="mb-2 flex h-11 w-11 items-center justify-center rounded-lg bg-zinc-200 text-zinc-700 dark:bg-zinc-800 dark:text-zinc-200">
                        <i class="fa-solid fa-ban text-lg" aria-hidden="true"></i>
                    </span>
                    <span class="text-sm font-semibold text-zinc-900 dark:text-zinc-100">{{ __('None') }}</span>
                    <span class="mt-1 text-xs text-zinc-500 dark:text-zinc-400">{{ __('No top shell') }}</span>
                </label>
            </div>
        </fieldset>
    </div>
</div>
