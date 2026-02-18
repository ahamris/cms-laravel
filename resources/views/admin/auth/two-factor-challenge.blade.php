<x-layouts.auth title="Two-Factor Authentication">
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
                    <h1 class="mb-2 text-2xl font-bold text-zinc-900 dark:text-white">Two-Factor Authentication</h1>
                    <h2 class="mb-8 text-sm text-zinc-600 dark:text-zinc-400">
                        Please confirm your account by entering the verification code from your authenticator app.
                    </h2>

                    @if ($errors->any())
                        <div class="mb-6 rounded-md bg-red-50 dark:bg-red-900/20 p-4">
                            <p class="text-sm text-red-800 dark:text-red-200">
                                {{ $errors->first() }}
                            </p>
                        </div>
                    @endif

                    <form
                        x-data="{
                            loading: false,
                            error: null,
                            isNumber(value) {
                                return /^[0-9]$/.test(value);
                            },
                            clearInputs() {
                                $refs.num1.value = '';
                                $refs.num2.value = '';
                                $refs.num3.value = '';
                                $refs.num4.value = '';
                                $refs.num5.value = '';
                                $refs.num6.value = '';
                                $refs.codeInput.value = '';
                                $refs.num1.focus();
                            },
                            async handleSubmit() {
                                const code = this.getCode();
                                if (code.length !== 6) {
                                    this.error = 'Please enter a 6-digit code';
                                    return false;
                                }

                                this.loading = true;
                                this.error = null;

                                // Set the code value in the hidden input
                                $refs.codeInput.value = code;

                                try {
                                    const formData = new FormData($refs.twoFactorForm);

                                    const response = await window.axios.post('{{ route('two-factor.login') }}', formData);

                                    // Success - redirect will be handled by Fortify
                                    // If we get here, it means the request was successful
                                    // Fortify will redirect, but if it's a JSON response, handle it
                                    if (response.data?.redirect) {
                                        window.location.href = response.data.redirect;
                                    } else {
                                        // Default redirect
                                        window.location.href = '/admin';
                                    }
                                } catch (err) {
                                    this.loading = false;
                                    this.clearInputs();
                                    
                                    if (err.response) {
                                        // Check if it's a validation error
                                        const errors = err.response.data?.errors;
                                        if (errors?.code) {
                                            this.error = Array.isArray(errors.code) ? errors.code[0] : errors.code;
                                        } else if (err.response.data?.message) {
                                            this.error = err.response.data.message;
                                        } else if (err.response.status === 422) {
                                            this.error = 'The provided two-factor authentication code was invalid.';
                                        } else {
                                            this.error = 'An error occurred. Please try again.';
                                        }
                                    } else {
                                        this.error = 'Network error. Please check your connection and try again.';
                                    }
                                }
                                
                                return false; // Prevent default form submission
                            },
                            handlePaste(e) {
                                e.preventDefault();
                                let num = e.clipboardData.getData('text/plain').trim().replace(/\D/g, '');
                                
                                if (num.length >= 6) {
                                    $refs.num1.value = num.charAt(0);
                                    $refs.num2.value = num.charAt(1);
                                    $refs.num3.value = num.charAt(2);
                                    $refs.num4.value = num.charAt(3);
                                    $refs.num5.value = num.charAt(4);
                                    $refs.num6.value = num.charAt(5);
                                    $refs.num6.focus();
                                    
                                    // Auto submit after paste
                                    setTimeout(() => {
                                        this.handleSubmit();
                                    }, 100);
                                }
                            },
                            getCode() {
                                const code = ($refs.num1.value || '') + 
                                            ($refs.num2.value || '') + 
                                            ($refs.num3.value || '') + 
                                            ($refs.num4.value || '') + 
                                            ($refs.num5.value || '') + 
                                            ($refs.num6.value || '');
                                return code.replace(/\D/g, '');
                            }
                        }"
                        x-ref="twoFactorForm"
                        action="{{ route('two-factor.login') }}"
                        method="POST"
                        class="space-y-6"
                        @submit.prevent="handleSubmit()"
                    >
                        @csrf

                        <div x-show="error" x-cloak class="mb-6 rounded-md bg-red-50 dark:bg-red-900/20 p-4">
                            <p class="text-sm text-red-800 dark:text-red-200" x-text="error"></p>
                        </div>

                        <input type="hidden" name="code" x-ref="codeInput" value="" />

                        <div class="inline-flex items-center gap-1.5">
                            <input
                                x-ref="num1"
                                x-on:input="() => { isNumber($refs.num1.value) ? $refs.num2.focus() : $refs.num1.value = '' }"
                                x-on:paste="handlePaste"
                                type="text"
                                id="num1"
                                name="num1"
                                maxlength="1"
                                autofocus
                                inputmode="numeric"
                                pattern="[0-9]"
                                autocomplete="off"
                                class="block w-10 h-12 rounded-lg bg-zinc-100 text-center text-lg font-semibold text-zinc-900 focus:outline-none focus:bg-zinc-200 dark:bg-zinc-800 dark:text-white dark:focus:bg-zinc-700 transition-colors"
                            />
                            <input
                                x-ref="num2"
                                x-on:input="() => { isNumber($refs.num2.value) ? $refs.num3.focus() : $refs.num2.value = '' }"
                                x-on:keydown.backspace="() => { $refs.num2.value === '' ? $refs.num1.focus() : null }"
                                x-on:paste="handlePaste"
                                type="text"
                                id="num2"
                                name="num2"
                                maxlength="1"
                                inputmode="numeric"
                                pattern="[0-9]"
                                autocomplete="off"
                                class="block w-10 h-12 rounded-lg bg-zinc-100 text-center text-lg font-semibold text-zinc-900 focus:outline-none focus:bg-zinc-200 dark:bg-zinc-800 dark:text-white dark:focus:bg-zinc-700 transition-colors"
                            />
                            <input
                                x-ref="num3"
                                x-on:input="() => { isNumber($refs.num3.value) ? $refs.num4.focus() : $refs.num3.value = '' }"
                                x-on:keydown.backspace="() => { $refs.num3.value === '' ? $refs.num2.focus() : null }"
                                x-on:paste="handlePaste"
                                type="text"
                                id="num3"
                                name="num3"
                                maxlength="1"
                                inputmode="numeric"
                                pattern="[0-9]"
                                autocomplete="off"
                                class="block w-10 h-12 rounded-lg bg-zinc-100 text-center text-lg font-semibold text-zinc-900 focus:outline-none focus:bg-zinc-200 dark:bg-zinc-800 dark:text-white dark:focus:bg-zinc-700 transition-colors"
                            />
                            <span class="text-sm text-zinc-400 dark:text-zinc-600">-</span>
                            <input
                                x-ref="num4"
                                x-on:input="() => { isNumber($refs.num4.value) ? $refs.num5.focus() : $refs.num4.value = '' }"
                                x-on:keydown.backspace="() => { $refs.num4.value === '' ? $refs.num3.focus() : null }"
                                x-on:paste="handlePaste"
                                type="text"
                                id="num4"
                                name="num4"
                                maxlength="1"
                                inputmode="numeric"
                                pattern="[0-9]"
                                autocomplete="off"
                                class="block w-10 h-12 rounded-lg bg-zinc-100 text-center text-lg font-semibold text-zinc-900 focus:outline-none focus:bg-zinc-200 dark:bg-zinc-800 dark:text-white dark:focus:bg-zinc-700 transition-colors"
                            />
                            <input
                                x-ref="num5"
                                x-on:input="() => { isNumber($refs.num5.value) ? $refs.num6.focus() : $refs.num5.value = '' }"
                                x-on:keydown.backspace="() => { $refs.num5.value === '' ? $refs.num4.focus() : null }"
                                x-on:paste="handlePaste"
                                type="text"
                                id="num5"
                                name="num5"
                                maxlength="1"
                                inputmode="numeric"
                                pattern="[0-9]"
                                autocomplete="off"
                                class="block w-10 h-12 rounded-lg bg-zinc-100 text-center text-lg font-semibold text-zinc-900 focus:outline-none focus:bg-zinc-200 dark:bg-zinc-800 dark:text-white dark:focus:bg-zinc-700 transition-colors"
                            />
                            <input
                                x-ref="num6"
                                x-on:input="() => { 
                                    if (isNumber($refs.num6.value)) {
                                        // Auto submit when 6th digit is entered
                                        setTimeout(() => {
                                            handleSubmit();
                                        }, 100);
                                    } else {
                                        $refs.num6.value = '';
                                    }
                                }"
                                x-on:keydown.backspace="() => { $refs.num6.value === '' ? $refs.num5.focus() : null }"
                                x-on:paste="handlePaste"
                                type="text"
                                id="num6"
                                name="num6"
                                maxlength="1"
                                inputmode="numeric"
                                pattern="[0-9]"
                                autocomplete="off"
                                class="block w-10 h-12 rounded-lg bg-zinc-100 text-center text-lg font-semibold text-zinc-900 focus:outline-none focus:bg-zinc-200 dark:bg-zinc-800 dark:text-white dark:focus:bg-zinc-700 transition-colors"
                            />
                        </div>
                        <div>
                            <button
                                x-ref="twoFactorButton"
                                type="submit"
                                :disabled="loading"
                                class="inline-flex min-w-32 items-center justify-center gap-2 rounded-lg border border-[var(--color-accent)] bg-[var(--color-accent)] px-3 py-2 text-sm font-medium leading-5 text-[var(--color-accent-foreground)] hover:opacity-90 focus:outline-hidden focus:ring-2 focus:ring-[var(--color-accent)]/50 active:opacity-80 transition-opacity disabled:opacity-50 disabled:cursor-not-allowed"
                            >
                                <span x-show="!loading">Verify code</span>
                                <span x-show="loading" class="flex items-center gap-2">
                                    <svg class="animate-spin h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                    </svg>
                                    Verifying...
                                </span>
                            </button>
                        </div>
                    </form>
                    <div class="mt-5 text-sm text-zinc-500 dark:text-zinc-400">
                        <a
                            href="{{ route('two-factor.recovery') }}"
                            class="font-medium text-[var(--color-accent)] underline decoration-[var(--color-accent)]/50 underline-offset-2 hover:opacity-80 dark:text-[var(--color-accent-content)] dark:decoration-[var(--color-accent)]/50 dark:hover:opacity-80 transition-opacity"
                        >
                            Use a recovery code
                        </a>
                    </div>
                </div>
            </div>
        </section>
    </div>
</x-layouts.auth>
