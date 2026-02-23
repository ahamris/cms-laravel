<x-layouts.admin title="Create Registration">
    {{-- Page Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-8">
        <div>
            <h1 class="text-3xl font-bold text-zinc-900 dark:text-white mb-2">Create New Registration</h1>
            <p class="text-zinc-600 dark:text-zinc-400">Register a participant for a live session</p>
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ route('admin.session-registration.index') }}"
                class="inline-flex items-center gap-2 rounded-md bg-white dark:bg-white/10 px-4 py-2 text-sm font-semibold text-gray-900 dark:text-white shadow-xs ring-1 ring-gray-300 ring-inset dark:ring-white/10 hover:bg-gray-50 dark:hover:bg-white/20">
                <i class="fa-solid fa-arrow-left"></i>
                Back to Registrations
            </a>
        </div>
    </div>

    <form action="{{ route('admin.session-registration.store') }}" method="POST">
        @csrf

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            {{-- Left Column - 2/3 --}}
            <div class="lg:col-span-2 space-y-8">
                {{-- Session Selection --}}
                <div
                    class="rounded-md border border-zinc-200 dark:border-white/10 bg-white dark:bg-white/5 p-6 shadow-sm">
                    <div class="mb-6">
                        <h2 class="text-lg font-bold text-gray-900 dark:text-white">Session Details</h2>
                        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">Select the live session for this
                            registration.</p>
                    </div>

                    @php
                        $sessionOptions = ['' => 'Select a session'] + $liveSessions->mapWithKeys(fn($s) => [$s->id => $s->title . ' - ' . $s->formatted_date])->all();
                    @endphp
                    <x-ui.select name="live_session_id" id="live_session_id" label="Live Session"
                        :options="$sessionOptions" :value="(string) old('live_session_id', $selectedSessionId ?? '')"
                        required :error="$errors->has('live_session_id')"
                        :errorMessage="$errors->first('live_session_id')" />
                </div>

                {{-- Participant Information --}}
                <div
                    class="rounded-md border border-zinc-200 dark:border-white/10 bg-white dark:bg-white/5 p-6 shadow-sm">
                    <div class="mb-6">
                        <h2 class="text-lg font-bold text-gray-900 dark:text-white">Participant Information</h2>
                        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">Personal details of the participant.
                        </p>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        <x-ui.input name="name" id="name" label="Full Name" :value="old('name')"
                            placeholder="Enter participant's full name" required :error="$errors->has('name')"
                            :errorMessage="$errors->first('name')" />
                        <x-ui.input name="email" id="email" label="Email Address" type="email" :value="old('email')"
                            placeholder="participant@example.com" required :error="$errors->has('email')"
                            :errorMessage="$errors->first('email')" />
                    </div>
                    <x-ui.input name="organization" id="organization" label="Organization" :value="old('organization')"
                        placeholder="Company or organization name" required :error="$errors->has('organization')"
                        :errorMessage="$errors->first('organization')" />
                </div>

                {{-- Notes --}}
                <div
                    class="rounded-md border border-zinc-200 dark:border-white/10 bg-white dark:bg-white/5 p-6 shadow-sm">
                    <div class="mb-6">
                        <h2 class="text-lg font-bold text-gray-900 dark:text-white">Additional Notes</h2>
                        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">Internal notes about this registration.
                        </p>
                    </div>

                    <x-ui.textarea name="notes" id="notes" label="Notes" :value="old('notes')"
                        placeholder="Any additional notes about this registration..." :rows="4"
                        hint="Maximum 2000 characters" :error="$errors->has('notes')"
                        :errorMessage="$errors->first('notes')" />
                </div>
            </div>

            {{-- Right Column - 1/3 --}}
            <div class="lg:col-span-1 space-y-8">
                {{-- Publish Action --}}
                <div
                    class="rounded-md border border-zinc-200 dark:border-white/10 bg-white dark:bg-white/5 p-6 shadow-sm">
                    <h2 class="text-lg font-bold text-gray-900 dark:text-white mb-4">Actions</h2>
                    <div class="space-y-4">
                        <x-button variant="primary" type="submit" class="w-full justify-center" icon="save"
                            icon-position="left">Create Registration</x-button>
                        <a href="{{ route('admin.session-registration.index') }}" class="block">
                            <x-button variant="secondary" type="button" class="w-full justify-center">Cancel</x-button>
                        </a>
                    </div>
                </div>

                {{-- Status & Settings --}}
                <div
                    class="rounded-md border border-zinc-200 dark:border-white/10 bg-white dark:bg-white/5 p-6 shadow-sm">
                    <h2 class="text-lg font-bold text-gray-900 dark:text-white mb-4">Status & Settings</h2>
                    <div class="space-y-4">
                        <x-ui.select name="status" id="status" label="Registration Status" :options="['registered' => 'Registered', 'attended' => 'Attended', 'no_show' => 'No Show', 'cancelled' => 'Cancelled']"
                            :value="old('status', 'registered')" required :error="$errors->has('status')"
                            :errorMessage="$errors->first('status')" />

                        <div class="pt-2">
                            <input type="hidden" name="marketing_consent" value="0">
                            <x-ui.checkbox name="marketing_consent" id="marketing_consent" value="1"
                                label="Marketing Consent" :checked="(bool) old('marketing_consent')" />
                            <p class="text-xs text-zinc-500 dark:text-zinc-400 mt-1.5 ml-7">Participant agrees to
                                receive marketing communications</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</x-layouts.admin>