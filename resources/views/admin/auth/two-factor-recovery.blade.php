<x-layouts.auth title="Two-Factor Recovery">
    <div class="flex h-full items-center justify-center px-4 py-12 sm:px-6">
        <section class="mx-auto w-full max-w-xl py-6">
            <div class="rounded-xl border border-zinc-200 text-center dark:border-zinc-800 dark:text-zinc-100">
                <div class="p-5 sm:p-8 md:p-12">
                    <svg
                        xmlns="http://www.w3.org/2000/svg"
                        fill="none"
                        viewBox="0 0 24 24"
                        stroke-width="1.5"
                        stroke="currentColor"
                        class="mb-5 inline-block size-6 opacity-75 text-zinc-600 dark:text-zinc-400"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            d="M16.5 10.5V6.75a4.5 4.5 0 1 0-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 0 0 2.25-2.25v-6.75a2.25 2.25 0 0 0-2.25-2.25H6.75a2.25 2.25 0 0 0-2.25 2.25v6.75a2.25 2.25 0 0 0 2.25 2.25Z"
                        />
                    </svg>
                    <h1 class="mb-2 text-2xl font-bold text-zinc-900 dark:text-white">Recovery Code</h1>
                    <h2 class="mb-8 text-sm text-zinc-600 dark:text-zinc-400">
                        Please confirm access to your account by entering one of your emergency recovery codes.
                    </h2>

                    @if ($errors->any())
                        <div class="mb-6 rounded-md bg-red-50 dark:bg-red-900/20 p-4">
                            <p class="text-sm text-red-800 dark:text-red-200">
                                {{ $errors->first() }}
                            </p>
                        </div>
                    @endif

                    <form
                        action="{{ route('two-factor.login') }}"
                        method="POST"
                        class="space-y-6"
                    >
                        @csrf

                        <div>
                            <label for="recovery_code" class="block text-sm/6 font-medium text-zinc-900 dark:text-zinc-100 mb-2">
                                Recovery Code
                            </label>
                            <input
                                type="text"
                                id="recovery_code"
                                name="recovery_code"
                                required
                                autofocus
                                class="block w-full rounded-lg border border-zinc-200 px-3 py-2 text-sm/6 placeholder-zinc-500 focus:border-[var(--color-accent)] focus:ring-3 focus:ring-[var(--color-accent)]/50 dark:border-zinc-600 dark:bg-transparent dark:placeholder-zinc-400 dark:focus:border-[var(--color-accent)] @error('recovery_code') border-red-600 dark:border-red-500 @enderror"
                                placeholder="Enter recovery code"
                            />
                            @error('recovery_code')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <button
                                type="submit"
                                class="inline-flex min-w-32 items-center justify-center gap-2 rounded-lg border border-[var(--color-accent)] bg-[var(--color-accent)] px-3 py-2 text-sm font-medium leading-5 text-[var(--color-accent-foreground)] hover:opacity-90 focus:outline-hidden focus:ring-2 focus:ring-[var(--color-accent)]/50 active:opacity-80 transition-opacity"
                            >
                                <span>Use Recovery Code</span>
                            </button>
                        </div>
                    </form>
                    <div class="mt-5 text-sm text-zinc-500 dark:text-zinc-400">
                        <a
                            href="{{ route('two-factor.login') }}"
                            class="font-medium text-[var(--color-accent)] underline decoration-[var(--color-accent)]/50 underline-offset-2 hover:opacity-80 dark:text-[var(--color-accent-content)] dark:decoration-[var(--color-accent)]/50 dark:hover:opacity-80 transition-opacity"
                        >
                            Use authentication code instead
                        </a>
                    </div>
                </div>
            </div>
        </section>
    </div>
</x-layouts.auth>
