<x-layouts.admin title="Comment Details">
    {{-- Page Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-8">
        <div>
            <h1 class="text-3xl font-bold text-zinc-900 dark:text-white mb-2">Comment Details</h1>
            <p class="text-zinc-600 dark:text-zinc-400">Comment #{{ $comment->id }} -
                {{ $comment->user->name ?? $comment->guest_name ?? 'Anonymous' }}
            </p>
        </div>
        <div class="flex flex-wrap items-center gap-3">
            @if($comment->is_approved)
                <span
                    class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400 ring-1 ring-green-600/20">
                    <i class="fa-solid fa-check-circle mr-1.5"></i> Approved
                </span>
            @else
                <span
                    class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-400 ring-1 ring-yellow-600/20">
                    <i class="fa-solid fa-clock mr-1.5"></i> Pending Approval
                </span>
            @endif

            <form action="{{ route('admin.comment.toggle-approve', $comment) }}" method="POST" class="inline">
                @csrf
                <button type="submit"
                    class="inline-flex items-center gap-2 rounded-md {{ $comment->is_approved ? 'bg-zinc-100 text-zinc-700 hover:bg-zinc-200' : 'bg-primary text-white hover:bg-primary/90 shadow-sm' }} px-4 py-2 text-sm font-semibold transition-all">
                    <i class="fa-solid {{ $comment->is_approved ? 'fa-xmark' : 'fa-check' }}"></i>
                    {{ $comment->is_approved ? 'Unapprove' : 'Approve Comment' }}
                </button>
            </form>

            <a href="{{ route('admin.comment.index') }}"
                class="inline-flex items-center gap-2 rounded-md bg-white dark:bg-white/10 px-4 py-2 text-sm font-semibold text-gray-900 dark:text-white shadow-xs ring-1 ring-gray-300 ring-inset dark:ring-white/10 hover:bg-gray-50 dark:hover:bg-white/20">
                <i class="fa-solid fa-arrow-left"></i>
                Back to Comments
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        {{-- Left Column - 2/3 --}}
        <div class="lg:col-span-2 space-y-8">
            {{-- Author Information Section --}}
            <div class="rounded-lg border border-gray-200 dark:border-white/10 bg-white dark:bg-white/5 p-6">
                <div class="mb-6">
                    <h2 class="text-base/7 font-semibold text-gray-900 dark:text-white">Author Information</h2>
                    <p class="mt-1 text-sm/6 text-gray-600 dark:text-gray-400">Details about the comment author.</p>
                </div>

                <div class="space-y-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        {{-- Name --}}
                        <div>
                            <label class="block text-sm/6 font-medium text-gray-900 dark:text-white">Name</label>
                            <div class="mt-2">
                                <p class="text-sm text-gray-700 dark:text-gray-300">
                                    {{ $comment->user->name ?? $comment->guest_name ?? 'Anonymous' }}
                                    @if(!$comment->user_id) <span
                                        class="ml-1 text-[10px] bg-zinc-100 px-1.5 py-0.5 rounded uppercase font-bold text-zinc-500">Guest</span>
                                    @endif
                                </p>
                            </div>
                        </div>

                        {{-- Email --}}
                        <div>
                            <label class="block text-sm/6 font-medium text-gray-900 dark:text-white">Email</label>
                            <div class="mt-2">
                                @php $email = $comment->user->email ?? $comment->guest_email; @endphp
                                @if($email)
                                    <a href="mailto:{{ $email }}"
                                        class="text-sm text-[var(--color-accent)] hover:underline">
                                        {{ $email }}
                                    </a>
                                @else
                                    <p class="text-sm text-gray-500 dark:text-gray-400">N/A</p>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Comment Content Section --}}
            <div class="rounded-lg border border-gray-200 dark:border-white/10 bg-white dark:bg-white/5 p-6">
                <div class="mb-6">
                    <h2 class="text-base/7 font-semibold text-gray-900 dark:text-white">Comment Content</h2>
                    <p class="mt-1 text-sm/6 text-gray-600 dark:text-gray-400">The full comment text.</p>
                </div>

                <div class="rounded-md border border-gray-200 dark:border-white/10 bg-gray-50 dark:bg-white/5 p-4">
                    <p class="text-sm text-gray-700 dark:text-gray-300 whitespace-pre-wrap">{{ $comment->body }}</p>
                </div>
            </div>

            {{-- Entity Information Section --}}
            <div class="rounded-lg border border-gray-200 dark:border-white/10 bg-white dark:bg-white/5 p-6">
                <div class="mb-6">
                    <h2 class="text-base/7 font-semibold text-gray-900 dark:text-white">In Response To</h2>
                    <p class="mt-1 text-sm/6 text-gray-600 dark:text-gray-400">The content this comment is associated
                        with.</p>
                </div>

                <div>
                    @if($comment->entity)
                        @php
                            $type = strtolower(class_basename($comment->entity_type));
                            $routeMap = ['blog' => 'blog'];
                            $baseRoute = $routeMap[$type] ?? $type;
                        @endphp
                        <a href="{{ route('admin.' . $baseRoute . '.show', $comment->entity) }}"
                            class="inline-flex items-center gap-2 text-sm text-[var(--color-accent)] hover:underline">
                            <i class="fa-solid fa-link"></i>
                            {{ $comment->entity->title ?? $comment->entity->name ?? 'View ' . class_basename($comment->entity_type) }}
                        </a>
                    @else
                        <p class="text-sm text-gray-500 dark:text-gray-400 italic">This comment is not associated with any
                            entity.</p>
                    @endif
                </div>
            </div>
        </div>

        {{-- Right Column - 1/3 --}}
        <div class="lg:col-span-1 space-y-8">
            {{-- Timestamps Section --}}
            <div class="rounded-lg border border-gray-200 dark:border-white/10 bg-white dark:bg-white/5 p-6">
                <div class="mb-6">
                    <h2 class="text-base/7 font-semibold text-gray-900 dark:text-white">Timestamps</h2>
                    <p class="mt-1 text-sm/6 text-gray-600 dark:text-gray-400">Record creation and update times.</p>
                </div>

                <div class="space-y-4">
                    <div>
                        <label class="block text-xs font-medium text-gray-600 dark:text-gray-400 mb-1">Submitted
                            On</label>
                        <p class="text-sm font-medium text-gray-900 dark:text-white">
                            {{ $comment->created_at->format('M d, Y H:i') }}
                        </p>
                    </div>

                    <div>
                        <label class="block text-xs font-medium text-gray-600 dark:text-gray-400 mb-1">Last
                            Updated</label>
                        <p class="text-sm font-medium text-gray-900 dark:text-white">
                            {{ $comment->updated_at->format('M d, Y H:i') }}
                        </p>
                    </div>
                </div>
            </div>

            {{-- Metadata Section --}}
            <div class="rounded-lg border border-gray-200 dark:border-white/10 bg-white dark:bg-white/5 p-6">
                <div class="mb-6">
                    <h2 class="text-base/7 font-semibold text-gray-900 dark:text-white">Metadata</h2>
                    <p class="mt-1 text-sm/6 text-gray-600 dark:text-gray-400">Additional comment information.</p>
                </div>

                <div class="space-y-4">
                    <div class="flex justify-between items-center">
                        <span class="text-sm text-gray-600 dark:text-gray-400">Comment ID:</span>
                        <span class="text-sm font-medium text-gray-900 dark:text-white">#{{ $comment->id }}</span>
                    </div>

                    @if($comment->entity_type)
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-gray-600 dark:text-gray-400">Entity Type:</span>
                            <span
                                class="text-sm font-medium text-gray-900 dark:text-white">{{ ucfirst(str_replace('_', ' ', $comment->entity_type)) }}</span>
                        </div>
                    @endif

                    @if($comment->entity_id)
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-gray-600 dark:text-gray-400">Entity ID:</span>
                            <span
                                class="text-sm font-medium text-gray-900 dark:text-white">#{{ $comment->entity_id }}</span>
                        </div>
                    @endif

                    @if($comment->user_id)
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-gray-600 dark:text-gray-400">User ID:</span>
                            <span class="text-sm font-medium text-gray-900 dark:text-white">#{{ $comment->user_id }}</span>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-layouts.admin>