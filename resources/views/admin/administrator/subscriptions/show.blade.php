<x-layouts.admin title="Subscription Details">
    {{-- Page Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-8">
        <div>
            <h1 class="text-3xl font-bold text-zinc-900 dark:text-white mb-2">{{ $subscription->full_name }}</h1>
            <p class="text-zinc-600 dark:text-zinc-400">Subscription #{{ $subscription->id }} - Details and information</p>
        </div>
        <div class="flex flex-wrap items-center gap-3">
            <a href="{{ route('admin.administrator.subscriptions.index') }}" class="inline-flex items-center gap-2 rounded-md bg-white dark:bg-white/10 px-4 py-2 text-sm font-semibold text-gray-900 dark:text-white shadow-xs ring-1 ring-gray-300 ring-inset dark:ring-white/10 hover:bg-gray-50 dark:hover:bg-white/20">
                <i class="fa-solid fa-arrow-left"></i>
                Back to Subscriptions
            </a>
            <a href="{{ route('admin.administrator.subscriptions.edit', $subscription) }}" class="inline-flex items-center gap-2 rounded-md bg-gray-600 px-4 py-2 text-sm font-semibold text-white shadow-xs hover:bg-gray-500">
                <i class="fa-solid fa-edit"></i>
                Edit Subscription
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        {{-- Left Column - 2/3 --}}
        <div class="lg:col-span-2 space-y-8">
            {{-- Contact Information Section --}}
            <div class="rounded-lg border border-gray-200 dark:border-white/10 bg-white dark:bg-white/5 p-6">
                <div class="mb-6">
                    <h2 class="text-base/7 font-semibold text-gray-900 dark:text-white">Contact Information</h2>
                    <p class="mt-1 text-sm/6 text-gray-600 dark:text-gray-400">Personal contact details of the subscriber.</p>
                </div>

                <div class="space-y-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        {{-- Full Name --}}
                        <div>
                            <label class="block text-sm/6 font-medium text-gray-900 dark:text-white">Full Name</label>
                            <div class="mt-2">
                                <p class="text-sm text-gray-700 dark:text-gray-300">{{ $subscription->full_name }}</p>
                            </div>
                        </div>

                        {{-- Email --}}
                        <div>
                            <label class="block text-sm/6 font-medium text-gray-900 dark:text-white">Email</label>
                            <div class="mt-2">
                                <a href="mailto:{{ $subscription->email }}" class="text-sm text-[var(--color-accent)] hover:underline">
                                    {{ $subscription->email }}
                                </a>
                            </div>
                        </div>

                        {{-- Phone --}}
                        @if($subscription->phone)
                        <div>
                            <label class="block text-sm/6 font-medium text-gray-900 dark:text-white">Phone</label>
                            <div class="mt-2">
                                <a href="tel:{{ $subscription->phone }}" class="text-sm text-[var(--color-accent)] hover:underline">
                                    {{ $subscription->phone }}
                                </a>
                            </div>
                        </div>
                        @endif

                        {{-- Job Title --}}
                        @if($subscription->job_title)
                        <div>
                            <label class="block text-sm/6 font-medium text-gray-900 dark:text-white">Job Title</label>
                            <div class="mt-2">
                                <p class="text-sm text-gray-700 dark:text-gray-300">{{ $subscription->job_title }}</p>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Company Information Section --}}
            <div class="rounded-lg border border-gray-200 dark:border-white/10 bg-white dark:bg-white/5 p-6">
                <div class="mb-6">
                    <h2 class="text-base/7 font-semibold text-gray-900 dark:text-white">Company Information</h2>
                    <p class="mt-1 text-sm/6 text-gray-600 dark:text-gray-400">Business details of the subscriber's organization.</p>
                </div>

                <div class="space-y-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        {{-- Company Name --}}
                        <div>
                            <label class="block text-sm/6 font-medium text-gray-900 dark:text-white">Company Name</label>
                            <div class="mt-2">
                                <p class="text-sm text-gray-700 dark:text-gray-300">{{ $subscription->company_name ?? 'Not provided' }}</p>
                            </div>
                        </div>

                        {{-- Company Size --}}
                        @if($subscription->company_size)
                        <div>
                            <label class="block text-sm/6 font-medium text-gray-900 dark:text-white">Company Size</label>
                            <div class="mt-2">
                                <p class="text-sm text-gray-700 dark:text-gray-300">{{ ucfirst($subscription->company_size) }}</p>
                            </div>
                        </div>
                        @endif

                        {{-- Industry --}}
                        @if($subscription->industry)
                        <div>
                            <label class="block text-sm/6 font-medium text-gray-900 dark:text-white">Industry</label>
                            <div class="mt-2">
                                <p class="text-sm text-gray-700 dark:text-gray-300">{{ $subscription->industry }}</p>
                            </div>
                        </div>
                        @endif

                        {{-- Website --}}
                        @if($subscription->website)
                        <div>
                            <label class="block text-sm/6 font-medium text-gray-900 dark:text-white">Website</label>
                            <div class="mt-2">
                                <a href="{{ $subscription->website }}" target="_blank" class="text-sm text-[var(--color-accent)] hover:underline inline-flex items-center gap-1">
                                    {{ $subscription->website }}
                                    <i class="fa-solid fa-external-link-alt text-xs"></i>
                                </a>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Subscription Details Section --}}
            <div class="rounded-lg border border-gray-200 dark:border-white/10 bg-white dark:bg-white/5 p-6">
                <div class="mb-6">
                    <h2 class="text-base/7 font-semibold text-gray-900 dark:text-white">Subscription Details</h2>
                    <p class="mt-1 text-sm/6 text-gray-600 dark:text-gray-400">Information about the subscription request.</p>
                </div>

                <div class="space-y-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        {{-- Product Interest --}}
                        @if($subscription->product_interest ?? null)
                        <div>
                            <label class="block text-sm/6 font-medium text-gray-900 dark:text-white">Product Interest</label>
                            <div class="mt-2">
                                <p class="text-sm text-gray-700 dark:text-gray-300">{{ $subscription->product_interest }}</p>
                            </div>
                        </div>
                        @endif

                        {{-- Preferred Contact Method --}}
                        <div>
                            <label class="block text-sm/6 font-medium text-gray-900 dark:text-white">Preferred Contact Method</label>
                            <div class="mt-2">
                                <p class="text-sm text-gray-700 dark:text-gray-300">{{ ucfirst($subscription->preferred_contact_method ?? 'Email') }}</p>
                            </div>
                        </div>

                        {{-- Preferred Demo Date --}}
                        @if($subscription->preferred_demo_date)
                        <div>
                            <label class="block text-sm/6 font-medium text-gray-900 dark:text-white">Preferred Demo Date</label>
                            <div class="mt-2">
                                <p class="text-sm text-gray-700 dark:text-gray-300">{{ $subscription->preferred_demo_date->format('M d, Y') }}</p>
                            </div>
                        </div>
                        @endif

                        {{-- Preferred Demo Time --}}
                        @if($subscription->preferred_demo_time)
                        <div>
                            <label class="block text-sm/6 font-medium text-gray-900 dark:text-white">Preferred Demo Time</label>
                            <div class="mt-2">
                                <p class="text-sm text-gray-700 dark:text-gray-300">{{ ucfirst($subscription->preferred_demo_time) }}</p>
                            </div>
                        </div>
                        @endif
                    </div>

                    {{-- Message --}}
                    @if($subscription->message)
                    <div>
                        <label class="block text-sm/6 font-medium text-gray-900 dark:text-white">Message</label>
                        <div class="mt-2 rounded-md border border-gray-200 dark:border-white/10 bg-gray-50 dark:bg-white/5 p-4">
                            <p class="text-sm text-gray-700 dark:text-gray-300 whitespace-pre-wrap">{{ $subscription->message }}</p>
                        </div>
                    </div>
                    @endif
                </div>
            </div>

            {{-- Admin Notes Section --}}
            <div class="rounded-lg border border-gray-200 dark:border-white/10 bg-white dark:bg-white/5 p-6">
                <div class="mb-6">
                    <h2 class="text-base/7 font-semibold text-gray-900 dark:text-white">Admin Notes</h2>
                    <p class="mt-1 text-sm/6 text-gray-600 dark:text-gray-400">Internal notes and comments.</p>
                </div>

                <div class="space-y-4">
                    @if($subscription->admin_notes)
                        <div class="rounded-md border border-yellow-200 dark:border-yellow-900/30 bg-yellow-50 dark:bg-yellow-900/10 p-4">
                            <p class="text-sm text-gray-700 dark:text-gray-300 whitespace-pre-wrap">{{ $subscription->admin_notes }}</p>
                        </div>
                    @else
                        <p class="text-sm text-gray-500 dark:text-gray-400 italic">No admin notes yet.</p>
                    @endif

                    {{-- Add Notes Form --}}
                    <form id="notesForm" class="mt-4">
                        @csrf
                        <div class="space-y-4">
                            <div>
                                <label for="notes" class="block text-sm/6 font-medium text-gray-900 dark:text-white">Add Note</label>
                                <div class="mt-2">
                                    <textarea id="notes" name="admin_notes" rows="3"
                                              class="block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline-2 focus:-outline-offset-2 focus:outline-[var(--color-accent)] sm:text-sm/6 dark:bg-white/5 dark:text-white dark:outline-white/10 dark:placeholder:text-gray-500 dark:focus:outline-[var(--color-accent)]"
                                              placeholder="Add your notes here..."></textarea>
                                </div>
                            </div>
                            <button type="submit" class="inline-flex items-center gap-2 rounded-md bg-[var(--color-accent)] px-4 py-2 text-sm font-semibold text-white shadow-xs hover:opacity-90">
                                <i class="fa-solid fa-plus"></i>
                                Add Note
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        {{-- Right Column - 1/3 --}}
        <div class="lg:col-span-1 space-y-8">
            {{-- Status & Type Section --}}
            <div class="rounded-lg border border-gray-200 dark:border-white/10 bg-white dark:bg-white/5 p-6">
                <div class="mb-6">
                    <h2 class="text-base/7 font-semibold text-gray-900 dark:text-white">Status & Type</h2>
                    <p class="mt-1 text-sm/6 text-gray-600 dark:text-gray-400">Current subscription status.</p>
                </div>

                <div class="space-y-6">
                    {{-- Status --}}
                    <div>
                        <label class="block text-sm/6 font-medium text-gray-900 dark:text-white mb-2">Status</label>
                        @php
                            $statusColors = [
                                'new' => 'bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-400',
                                'contacted' => 'bg-cyan-100 text-cyan-800 dark:bg-cyan-900/30 dark:text-cyan-400',
                                'demo_scheduled' => 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-400',
                                'demo_completed' => 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400',
                                'converted' => 'bg-emerald-100 text-emerald-800 dark:bg-emerald-900/30 dark:text-emerald-400',
                                'rejected' => 'bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-400',
                            ];
                            $statusColor = $statusColors[$subscription->status] ?? 'bg-gray-100 text-gray-800 dark:bg-gray-800 dark:text-gray-400';
                        @endphp
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium {{ $statusColor }}">
                            {{ $subscription->formatted_status }}
                        </span>
                    </div>

                    {{-- Active Status --}}
                    <div>
                        <label class="block text-sm/6 font-medium text-gray-900 dark:text-white mb-2">Active Status</label>
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium {{ $subscription->is_active ? 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400' : 'bg-gray-100 text-gray-800 dark:bg-gray-800 dark:text-gray-400' }}">
                            {{ $subscription->is_active ? 'Active' : 'Inactive' }}
                        </span>
                    </div>

                    {{-- Update Status --}}
                    <div>
                        <label for="statusSelect" class="block text-sm/6 font-medium text-gray-900 dark:text-white mb-2">Update Status</label>
                        <div class="mt-2 grid grid-cols-1">
                            <select id="statusSelect" class="col-start-1 row-start-1 w-full appearance-none rounded-md bg-white py-1.5 pr-8 pl-3 text-base text-gray-900 outline-1 -outline-offset-1 outline-gray-300 focus:outline-2 focus:-outline-offset-2 focus:outline-[var(--color-accent)] sm:text-sm/6 dark:bg-white/5 dark:text-white dark:outline-white/10 dark:*:bg-gray-800 dark:focus:outline-[var(--color-accent)]">
                                <option value="new" {{ $subscription->status === 'new' ? 'selected' : '' }}>New Subscription</option>
                                <option value="contacted" {{ $subscription->status === 'contacted' ? 'selected' : '' }}>Contacted</option>
                                <option value="demo_scheduled" {{ $subscription->status === 'demo_scheduled' ? 'selected' : '' }}>Demo Scheduled</option>
                                <option value="demo_completed" {{ $subscription->status === 'demo_completed' ? 'selected' : '' }}>Demo Completed</option>
                                <option value="converted" {{ $subscription->status === 'converted' ? 'selected' : '' }}>Converted to Customer</option>
                                <option value="rejected" {{ $subscription->status === 'rejected' ? 'selected' : '' }}>Rejected</option>
                            </select>
                            <svg viewBox="0 0 16 16" fill="currentColor" class="pointer-events-none col-start-1 row-start-1 mr-2 size-5 self-center justify-self-end text-gray-500 sm:size-4 dark:text-gray-400">
                                <path d="M4.22 6.22a.75.75 0 0 1 1.06 0L8 8.94l2.72-2.72a.75.75 0 1 1 1.06 1.06l-3.25 3.25a.75.75 0 0 1-1.06 0L4.22 7.28a.75.75 0 0 1 0-1.06Z" clip-rule="evenodd" fill-rule="evenodd" />
                            </svg>
                        </div>
                        <button onclick="updateStatus()" class="mt-3 w-full inline-flex items-center justify-center gap-2 rounded-md bg-[var(--color-accent)] px-4 py-2 text-sm font-semibold text-white shadow-xs hover:opacity-90">
                            <i class="fa-solid fa-check"></i>
                            Update Status
                        </button>
                    </div>
                </div>
            </div>

            {{-- Timeline Section --}}
            <div class="rounded-lg border border-gray-200 dark:border-white/10 bg-white dark:bg-white/5 p-6">
                <div class="mb-6">
                    <h2 class="text-base/7 font-semibold text-gray-900 dark:text-white">Timeline</h2>
                    <p class="mt-1 text-sm/6 text-gray-600 dark:text-gray-400">Subscription activity history.</p>
                </div>

                <div class="space-y-4">
                    <div class="flex items-start gap-3">
                        <div class="flex-shrink-0 w-2 h-2 bg-blue-500 rounded-full mt-2"></div>
                        <div>
                            <p class="text-sm font-medium text-gray-900 dark:text-white">Subscription Created</p>
                            <p class="text-xs text-gray-500 dark:text-gray-400">{{ $subscription->created_at->format('M d, Y H:i') }}</p>
                        </div>
                    </div>

                    @if($subscription->contacted_at)
                    <div class="flex items-start gap-3">
                        <div class="flex-shrink-0 w-2 h-2 bg-cyan-500 rounded-full mt-2"></div>
                        <div>
                            <p class="text-sm font-medium text-gray-900 dark:text-white">Contacted</p>
                            <p class="text-xs text-gray-500 dark:text-gray-400">{{ $subscription->contacted_at->format('M d, Y H:i') }}</p>
                        </div>
                    </div>
                    @endif

                    @if($subscription->demo_scheduled_at)
                    <div class="flex items-start gap-3">
                        <div class="flex-shrink-0 w-2 h-2 bg-yellow-500 rounded-full mt-2"></div>
                        <div>
                            <p class="text-sm font-medium text-gray-900 dark:text-white">Demo Scheduled</p>
                            <p class="text-xs text-gray-500 dark:text-gray-400">{{ $subscription->demo_scheduled_at->format('M d, Y H:i') }}</p>
                        </div>
                    </div>
                    @endif

                    @if($subscription->demo_completed_at)
                    <div class="flex items-start gap-3">
                        <div class="flex-shrink-0 w-2 h-2 bg-green-500 rounded-full mt-2"></div>
                        <div>
                            <p class="text-sm font-medium text-gray-900 dark:text-white">Demo Completed</p>
                            <p class="text-xs text-gray-500 dark:text-gray-400">{{ $subscription->demo_completed_at->format('M d, Y H:i') }}</p>
                        </div>
                    </div>
                    @endif
                </div>
            </div>

            {{-- Metadata Section --}}
            <div class="rounded-lg border border-gray-200 dark:border-white/10 bg-white dark:bg-white/5 p-6">
                <div class="mb-6">
                    <h2 class="text-base/7 font-semibold text-gray-900 dark:text-white">Metadata</h2>
                    <p class="mt-1 text-sm/6 text-gray-600 dark:text-gray-400">Additional subscription information.</p>
                </div>

                <div class="space-y-4">
                    <div class="flex justify-between items-center">
                        <span class="text-sm text-gray-600 dark:text-gray-400">Source:</span>
                        <span class="text-sm font-medium text-gray-900 dark:text-white">{{ ucfirst($subscription->source ?? 'Website') }}</span>
                    </div>

                    @if($subscription->utm_source)
                    <div class="flex justify-between items-center">
                        <span class="text-sm text-gray-600 dark:text-gray-400">UTM Source:</span>
                        <span class="text-sm font-medium text-gray-900 dark:text-white">{{ $subscription->utm_source }}</span>
                    </div>
                    @endif

                    @if($subscription->utm_medium)
                    <div class="flex justify-between items-center">
                        <span class="text-sm text-gray-600 dark:text-gray-400">UTM Medium:</span>
                        <span class="text-sm font-medium text-gray-900 dark:text-white">{{ $subscription->utm_medium }}</span>
                    </div>
                    @endif

                    @if($subscription->utm_campaign)
                    <div class="flex justify-between items-center">
                        <span class="text-sm text-gray-600 dark:text-gray-400">UTM Campaign:</span>
                        <span class="text-sm font-medium text-gray-900 dark:text-white">{{ $subscription->utm_campaign }}</span>
                    </div>
                    @endif

                    <div class="flex justify-between items-center">
                        <span class="text-sm text-gray-600 dark:text-gray-400">Newsletter Consent:</span>
                        <span class="text-sm font-medium text-gray-900 dark:text-white">{{ $subscription->newsletter_consent ? 'Yes' : 'No' }}</span>
                    </div>

                    <div class="flex justify-between items-center">
                        <span class="text-sm text-gray-600 dark:text-gray-400">Marketing Consent:</span>
                        <span class="text-sm font-medium text-gray-900 dark:text-white">{{ $subscription->marketing_consent ? 'Yes' : 'No' }}</span>
                    </div>
                </div>
            </div>

            {{-- Timestamps Section --}}
            <div class="rounded-lg border border-gray-200 dark:border-white/10 bg-white dark:bg-white/5 p-6">
                <div class="mb-6">
                    <h2 class="text-base/7 font-semibold text-gray-900 dark:text-white">Timestamps</h2>
                    <p class="mt-1 text-sm/6 text-gray-600 dark:text-gray-400">Record creation and update times.</p>
                </div>

                <div class="space-y-4">
                    <div>
                        <label class="block text-xs font-medium text-gray-600 dark:text-gray-400 mb-1">Created At</label>
                        <p class="text-sm font-medium text-gray-900 dark:text-white">{{ $subscription->created_at->format('M d, Y H:i') }}</p>
                    </div>

                    <div>
                        <label class="block text-xs font-medium text-gray-600 dark:text-gray-400 mb-1">Updated At</label>
                        <p class="text-sm font-medium text-gray-900 dark:text-white">{{ $subscription->updated_at->format('M d, Y H:i') }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
    function updateStatus() {
        const status = document.getElementById('statusSelect').value;

        axios.post('{{ route('admin.administrator.subscriptions.update-status', $subscription) }}', {
            status: status
        })
        .then(response => {
            if (response.data.success) {
                location.reload();
            } else {
                alert('Error updating status');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error updating status');
        });
    }

    document.getElementById('notesForm').addEventListener('submit', function(e) {
        e.preventDefault();

        const notes = document.getElementById('notes').value;

        if (!notes.trim()) {
            alert('Please enter a note');
            return;
        }

        axios.post('{{ route('admin.administrator.subscriptions.add-notes', $subscription) }}', {
            admin_notes: notes
        })
        .then(response => {
            if (response.data.success || response.status === 200) {
                location.reload();
            } else {
                alert('Error adding notes');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error adding notes');
        });
    });
    </script>
</x-layouts.admin>
