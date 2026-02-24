<x-layouts.admin title="Contact Form Details">
    <div class="space-y-6">
        {{-- Page Header --}}
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-8">
            <div>
                <h1 class="text-3xl font-bold text-zinc-900 dark:text-white mb-2">Contact Form #{{ $contactForm->id }}
                </h1>
                <p class="text-zinc-600 dark:text-zinc-400">Submitted by {{ $contactForm->full_name }} on
                    {{ $contactForm->created_at->format('M d, Y') }}
                </p>
            </div>
            <div class="flex items-center gap-3">
                <a href="{{ route('admin.administrator.contact-forms.index') }}"
                    class="inline-flex items-center gap-2 rounded-md bg-white dark:bg-white/10 px-4 py-2 text-sm font-semibold text-gray-900 dark:text-white ring-1 ring-gray-300 ring-inset dark:ring-white/10 hover:bg-gray-50 dark:hover:bg-white/20">
                    <i class="fa-solid fa-arrow-left"></i>
                    Back to Contact Forms
                </a>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            {{-- Left Column: Submission Details & Conversation --}}
            <div class="lg:col-span-2 space-y-6">

                {{-- Submission Information --}}
                <div class="bg-white dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700 rounded-md p-6">
                    <div class="flex items-center gap-2 border-b border-zinc-100 dark:border-zinc-700 pb-4 mb-6">
                        <i class="fa-solid fa-id-card text-zinc-400"></i>
                        <h2 class="text-sm font-semibold text-zinc-900 dark:text-white uppercase tracking-wider">
                            Submission
                            Details</h2>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-6">
                        <div>
                            <label class="block text-sm font-semibold text-zinc-500 mb-1">Full Name</label>
                            <p class="text-sm font-medium text-zinc-900 dark:text-white">{{ $contactForm->full_name }}
                            </p>
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-zinc-500 mb-1">Email Address</label>
                            <a href="mailto:{{ $contactForm->email }}"
                                class="text-sm font-medium text-blue-600 dark:text-blue-400 hover:underline">{{ $contactForm->email }}</a>
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-zinc-500 mb-1">Phone Number</label>
                            <p class="text-sm font-medium text-zinc-900 dark:text-white">
                                {{ $contactForm->country_code }}{{ $contactForm->phone }}
                            </p>
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-zinc-500 mb-1">Company</label>
                            <p class="text-sm font-medium text-zinc-900 dark:text-white">
                                {{ $contactForm->company_name ?: '-' }}
                            </p>
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-zinc-500 mb-1">Reason for Contact</label>
                            <p class="text-sm font-medium text-zinc-900 dark:text-white">
                                {{ ucwords($contactForm->reden) }}
                            </p>
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-zinc-500 mb-1">Contact Preference</label>
                            <p class="text-sm font-medium text-zinc-900 dark:text-white uppercase">
                                {{ $contactForm->contact_preference }}
                            </p>
                        </div>
                    </div>
                </div>

                {{-- Unified Conversation Thread --}}
                <div
                    class="bg-white dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700 rounded-md overflow-hidden">
                    <div
                        class="px-6 py-4 bg-zinc-50 dark:bg-zinc-900/40 border-b border-zinc-200 dark:border-zinc-700 flex items-center justify-between">
                        <div class="flex items-center gap-2">
                            <i class="fa-solid fa-comments text-zinc-400"></i>
                            <h2 class="text-sm font-semibold text-zinc-900 dark:text-white uppercase tracking-wider">
                                Conversation</h2>
                        </div>
                    </div>

                    <div class="p-6 space-y-6">
                        @forelse($contactForm->messages as $message)
                                            <div
                                                class="flex flex-col {{ $message->direction === 'outbound' ? 'items-end' : 'items-start' }}">
                                                {{-- Meta --}}
                                                <div
                                                    class="flex items-center gap-2 mb-1.5 px-0.5 {{ $message->direction === 'outbound' ? 'flex-row-reverse' : '' }}">
                                                    <span class="text-sm font-semibold text-zinc-900 dark:text-white">
                                                        {{ $message->direction === 'outbound' ? ($message->user->name ?? 'Admin') : $contactForm->full_name }}
                                                    </span>
                                                    <span
                                                        class="text-xs text-zinc-400">{{ $message->created_at->format('M d, H:i') }}</span>
                                                </div>

                                                {{-- Bubble --}}
                                                <div
                                                    class="max-w-[90%] px-4 py-3 rounded-md text-sm leading-relaxed border shadow-none {{ $message->direction === 'outbound'
                            ? 'bg-blue-600 border-blue-600 text-white'
                            : 'bg-zinc-50 dark:bg-zinc-900 border-zinc-200 dark:border-zinc-700 text-zinc-800 dark:text-zinc-200' }}">
                                                    {!! nl2br(e($message->message)) !!}

                                                    @if($message->status === 'failed')
                                                        <div
                                                            class="mt-2 text-xs font-semibold text-red-200 flex items-center gap-1.5 uppercase tracking-tight">
                                                            <i class="fa-solid fa-circle-exclamation"></i>
                                                            Delivery Failed
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>
                        @empty
                            <div class="text-center py-6">
                                <p class="text-sm text-zinc-500 italic">No message history found.</p>
                            </div>
                        @endforelse
                    </div>

                    {{-- Reply Form --}}
                    @if(!$contactForm->outboundMessages->count() > 0)
                        <div class="p-6 bg-zinc-50/50 dark:bg-zinc-900/30 border-t border-zinc-200 dark:border-zinc-700">
                            <form action="{{ route('admin.administrator.contact-forms.reply', $contactForm) }}"
                                method="POST" class="space-y-4">
                                @csrf
                                <input type="hidden" name="subject"
                                    value="Re: Contact Form Submission - {{ $contactForm->full_name }}">

                                <div class="flex items-center gap-1.5 mb-2">
                                    <i class="fa-solid fa-reply text-blue-500 text-xs"></i>
                                    <span class="text-xs font-semibold uppercase tracking-wider text-zinc-500">Send
                                        Response</span>
                                </div>

                                <x-ui.textarea name="message"
                                    placeholder="Type your reply to {{ $contactForm->first_name }} here..." rows="5"
                                    required
                                    class="!bg-white dark:!bg-zinc-800 border-zinc-200 dark:border-zinc-700 shadow-none !text-sm" />

                                <div class="flex items-center justify-between">
                                    <p class="text-xs text-zinc-500 italic">
                                        * A single reply will be sent via email.
                                    </p>
                                    <x-ui.button type="submit" variant="primary">Send Reply</x-ui.button>
                                </div>
                            </form>
                        </div>
                    @else
                        <div
                            class="p-4 bg-zinc-50 dark:bg-zinc-900/40 border-t border-zinc-200 dark:border-zinc-700 flex items-center justify-center gap-2">
                            <i class="fa-solid fa-lock text-zinc-400 text-xs"></i>
                            <span class="text-xs font-semibold text-zinc-500 uppercase tracking-widest">Conversation
                                Finalized</span>
                        </div>
                    @endif
                </div>
            </div>

            {{-- Right Column: Management --}}
            <div class="space-y-6">

                {{-- Status & Notes --}}
                <div class="bg-white dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700 rounded-md p-6">
                    <h3
                        class="text-xs font-semibold text-zinc-500 uppercase tracking-widest mb-6 border-b border-zinc-100 dark:border-zinc-700 pb-2">
                        Management</h3>

                    <form action="{{ route('admin.administrator.contact-forms.update', $contactForm) }}" method="POST"
                        class="space-y-6">
                        @csrf
                        @method('PUT')

                        <x-ui.select label="Status" name="status">
                            <option value="new" {{ $contactForm->status === 'new' ? 'selected' : '' }}>Nieuw</option>
                            <option value="contacted" {{ $contactForm->status === 'contacted' ? 'selected' : '' }}>
                                Gecontacteerd</option>
                            <option value="resolved" {{ $contactForm->status === 'resolved' ? 'selected' : '' }}>Opgelost
                            </option>
                            <option value="closed" {{ $contactForm->status === 'closed' ? 'selected' : '' }}>Gesloten
                            </option>
                        </x-ui.select>

                        <x-ui.textarea label="Admin Notes" name="admin_notes" rows="4"
                            :value="$contactForm->admin_notes" placeholder="Internal notes..."
                            class="!text-sm"></x-ui.textarea>

                        <x-ui.button type="submit" variant="primary" class="w-full justify-center">Update
                            Records</x-ui.button>
                    </form>
                </div>

                {{-- Extras --}}
                @if(count($contactForm->attachment_list) > 0 || $contactForm->avg_optin)
                    <div class="bg-white dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700 rounded-md p-6">
                        <h3
                            class="text-xs font-semibold text-zinc-500 uppercase tracking-widest mb-6 border-b border-zinc-100 dark:border-zinc-700 pb-2">
                            Information</h3>

                        <div class="space-y-6">
                            @if(count($contactForm->attachment_list) > 0)
                                <div>
                                    <label class="block text-sm font-semibold text-zinc-500 mb-2">Attachments</label>
                                    <div class="space-y-2">
                                        @foreach($contactForm->attachment_list as $attachment)
                                            <a href="{{ asset('storage/' . ($attachment['path'] ?? '')) }}" download
                                                class="flex items-center gap-2 p-3 rounded-md bg-zinc-50 dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-700 hover:border-blue-400 transition-all group">
                                                <i class="fa-solid fa-paperclip text-zinc-400 group-hover:text-blue-500"></i>
                                                <span
                                                    class="text-sm font-medium text-zinc-700 dark:text-zinc-200 truncate">{{ $attachment['name'] ?? basename($attachment['path'] ?? '') }}</span>
                                            </a>
                                        @endforeach
                                    </div>
                                </div>
                            @endif

                            <div>
                                <label class="block text-sm font-semibold text-zinc-500 mb-1">GDPR Status</label>
                                <div class="flex items-center gap-2">
                                    <span
                                        class="size-2 rounded-full {{ $contactForm->avg_optin ? 'bg-green-500' : 'bg-red-500' }}"></span>
                                    <span
                                        class="text-sm font-medium text-zinc-700 dark:text-zinc-300">{{ $contactForm->avg_optin ? 'Accepted' : 'Declined' }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif

            </div>
        </div>
    </div>
</x-layouts.admin>