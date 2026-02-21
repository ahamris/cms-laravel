@extends('layouts.front')

@section('title', $blog->title . ' | ' . __('ui.open_publications'))

@section('content')
    @if(isset($backgroundImageUrl) && !empty($backgroundImageUrl))
    <section class="relative bg-cover bg-center h-[500px] flex items-center text-white" style="background-image: url('{{ $backgroundImageUrl }}')">
        <div class="absolute inset-0 bg-black/60"></div> {{-- Overlay for readability --}}
        <div class="relative container mx-auto px-6">
            <div class="flex flex-col gap-6 max-w-7xl text-center mx-auto">
                @if($blog->blog_category)
                    <p class="font-semibold">{{ $blog->blog_category->name }}</p>
                @endif
                <h1 class="text-3xl lg:text-4xl font-medium leading-tight">
                    {{ $blog->title }}
                </h1>
            </div>
        </div>
    </section>
    @else
    <section class="relative bg-sky-50 h-[500px] flex items-center">
        <div class="relative container mx-auto px-6">
            <div class="flex flex-col gap-6 max-w-7xl text-center mx-auto">
                @if($blog->blog_category)
                    <p class="text-primary font-semibold">{{ $blog->blog_category->name }}</p>
                @endif
                <h1 class="text-3xl lg:text-4xl font-medium leading-tight">
                    {{ $blog->title }}
                </h1>
            </div>
        </div>
    </section>
    @endif

    <main class="py-12">
        <div class="container mx-auto px-4">
            <!-- Blog Post -->
            <article>
                <!-- Featured Image -->
                <div class="mb-8 rounded-lg overflow-hidden">
                    <img src="{{ $blog->get_image }}"
                         alt="{{ $blog->title }}"
                         class="w-full h-auto max-h-[500px] object-cover">
                </div>

                <!-- Blog Content -->
                <div>
                    <!-- Category and Date -->
                    <div class="flex flex-wrap items-center gap-4 mb-6">
                        @if($blog->blog_category)
                            <span class="inline-block bg-primary-100 text-primary-800 text-sm font-semibold py-1 rounded-full">
                                {{ $blog->blog_category->name }}
                            </span>
                        @endif
                        <span class="text-gray-500 text-sm">
                            {{ $blog->created_at->format('d F Y') }}
                        </span>
                    </div>

                    <!-- Title -->
                    <h1 class="text-3xl md:text-4xl font-bold text-gray-900 mb-6">
                        {{ $blog->title }}
                    </h1>

                    <!-- Author -->
                    <div class="flex items-center gap-4 mb-8">
                        @if($blog->author && method_exists($blog->author, 'get_avatar'))
                            <img src="{{ $blog->author->get_avatar }}"
                                 alt="{{ $blog->author->name ?? __('ui.author_default') }}"
                                 class="w-12 h-12 rounded-full object-cover">
                        @else
                            <img src="{{ asset('frontend/images/team.png') }}"
                                 alt="{{ __('ui.author_default') }}"
                                 class="w-12 h-12 rounded-full object-cover">
                        @endif
                        <div>
                            <p class="font-semibold text-gray-800">
                                {{ $blog->author?->name ?? 'Open Publicaties' }}
                            </p>
                            <p class="text-sm text-gray-500">
                                {{ $blog->created_at->format('d M Y') }}
                            </p>
                        </div>
                    </div>

                    <!-- Blog Content -->
                    <div class="prose max-w-none">
                        {!! $blog->long_body !!}
                    </div>
                </div>
            </article>

            <!-- Comments Section -->
            <section class="mt-16 mx-auto">
                @if($comments->isNotEmpty())
                    <h2 class="text-lg font-semibold text-slate-700 pt-4 mb-4">{{ __('ui.comments_count') }} ({{ $comments->total() }})</h2>
                    <div class="space-y-6">
                        @foreach($comments as $comment)
                            <div class="bg-white rounded-md border border-gray-200 shadow-sm">
                                <div class="flex flex-col sm:flex-row">
                                    <div class="flex-shrink-0 w-full sm:w-48 p-4 border-b sm:border-b-0 sm:border-r border-gray-200">
                                        <div class="flex sm:flex-col items-center sm:items-start gap-4">
                                            <img src="{{ $comment->author->get_avatar }}" alt="{{ $comment->author->short_name ?? 'User' }}" class="w-12 h-12 rounded-full">
                                            <div class="text-left">
                                                <p class="font-semibold text-slate-700">{{ $comment->author->short_name ?? 'User' }}</p>
                                                <p class="text-xs text-gray-500 mt-1">{{ $comment->author->role }}</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="flex-1">
                                        <div class="px-6 py-3 border-b border-gray-200 text-xs text-gray-500 flex justify-between items-center">
                                            <span>{{ __('ui.replied') }}: {{ $comment->created_at->diffForHumans() }}</span>
                                            <div class="flex items-center gap-3">
                                                <a href="#" class="hover:text-primary">{{ __('ui.reply') }}</a>
                                                <a href="#" class="hover:text-primary">{{ __('ui.report') }}</a>
                                            </div>
                                        </div>
                                        <div id="post-content-{{ $comment->id }}" class="p-6 text-gray-800 text-sm">
                                            @if(!empty($comment->parent_id))
                                                <blockquote class="border-l-4 border-gray-300 pl-4 text-gray-600 italic mb-4">
                                                    <div class="font-semibold text-gray-700">
                                                        {{ $comment->parent->author->short_name }} {{ __('ui.wrote') }}:
                                                        {{ $comment->parent->author->short_name }} wrote:
                                                    </div>
                                                    <div class="prose prose-sm max-w-none mt-1">
                                                        {!! $comment->parent->content !!}
                                                    </div>
                                                </blockquote>
                                            @endif
                                            {!! $comment->content !!}
                                        </div>
                                    </div>
                                </div>
                                <div class="px-6 py-2 border-t border-gray-100 bg-gray-50/50">
                                    <div class="flex items-center justify-between text-xs text-gray-500">
                                        <span>Posted {{ $comment->created_at->diffForHumans() }}</span>
                                        <button type="button" class="quote-button font-medium text-slate-600 hover:text-primary transition-colors" data-author-name="{{ $comment->author->short_name ?? 'User' }}" data-post-id="{{ $comment->id }}">
                                            <i class="fa-solid fa-quote-left"></i>
                                            <span>{{ __('ui.quote') }}</span>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    <div class="mt-8">
                        {{ $comments->links() }}
                    </div>
                @else
                    <div class="bg-gray-50 p-6 rounded-lg text-center text-gray-500">
                        Nog geen reacties. Wees de eerste om te reageren!
                    </div>
                @endif

                {{-- Comment Form --}}
                @auth
                    <div id="reply-form" class="mt-12 bg-white rounded-md border border-gray-200 shadow-sm">
                        <div class="p-6">
                            <h3 class="text-lg font-semibold text-slate-700 mb-4">Laat een reactie achter</h3>
                            <div class="flex gap-4">
                                <div class="flex shrink-0 w-20 text-center hidden sm:block">
                                    <img src="{{ auth()->user()->get_avatar }}" alt="Your Avatar" class="size-16 rounded-full mx-auto">
                                    <p class="text-xs font-semibold text-slate-700 mt-2 truncate">{{ auth()->user()->short_name }}</p>
                                </div>
                                <div class="flex-1">
                                    <form action="{{ route('comment.store') }}" method="POST" class="space-y-4">
                                        @csrf
                                        <input type="hidden" name="entity_type" value="{{ get_class($blog) }}">
                                        <input type="hidden" name="entity_id" value="{{ $blog->id }}">
                                        <div class="hidden" aria-hidden="true">
                                            <input type="text" name="hp_phone" tabindex="-1" autocomplete="off">
                                        </div>
                                        <div>
                                            <label for="comment-body" class="block text-sm font-medium text-slate-700 mb-2">Uw reactie</label>
                                            <textarea id="comment-body" name="body" rows="4" required
                                                      class="w-full px-4 py-3 bg-white rounded-md border border-gray-200 focus:border-primary focus:ring-1 focus:ring-primary/10 transition-all outline-none text-sm placeholder:text-gray-400"
                                                      placeholder="Schrijf hier uw bericht...">{{ old('body') }}</textarea>
                                            @error('body')
                                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                            @enderror
                                        </div>
                                        <button type="submit"
                                                class="bg-primary hover:bg-primary/90 text-white px-6 py-2.5 rounded-md text-sm font-semibold transition-colors">
                                            Verstuur reactie
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                @else
                        <div class="bg-white rounded-md border border-gray-100 shadow-sm my-4">
                            <div class="p-6">
                                <p class="text-sm text-gray-500">
                                    {!! __('ui.login_to_reply_message', ['login_url' => route('user.login')]) !!}
                                </p>
                            </div>
                        </div>
                @endauth
            </section>

            <!-- Related Posts -->
            @if($relatedBlogs->isNotEmpty())
                <section class="mt-16">
                    <h2 class="text-2xl font-bold text-gray-900 mb-8">{{ __('ui.related_articles') }}</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                        @foreach($relatedBlogs as $relatedBlog)
                            <article class="bg-white rounded-lg shadow-sm overflow-hidden hover:shadow-md transition-shadow duration-300">
                                <a href="{{ url('/artikelen/' . $relatedBlog->slug) }}" class="block">
                                    <img src="{{ $relatedBlog->get_image }}"
                                         alt="{{ $relatedBlog->title }}"
                                         class="w-full h-48 object-cover">
                                </a>
                                <div class="p-6">
                                    @if($relatedBlog->blog_category)
                                        <span class="inline-block bg-gray-100 text-gray-800 text-xs font-semibold px-2.5 py-0.5 rounded mb-3">
                                            {{ $relatedBlog->blog_category->name }}
                                        </span>
                                    @endif
                                    <h3 class="text-xl font-bold text-gray-900 mb-2">
                                        <a href="{{ url('/artikelen/' . $relatedBlog->slug) }}" class="hover:text-primary-600 transition-colors">
                                            {{ $relatedBlog->title }}
                                        </a>
                                    </h3>
                                    <div class="flex items-center gap-2 text-sm text-gray-500 mb-4">
                                        @if($relatedBlog->author)
                                            <span class="inline-flex items-center">
                                                @if(method_exists($relatedBlog->author, 'get_avatar'))
                                                    <img src="{{ $relatedBlog->author->get_avatar }}"
                                                         alt="{{ $relatedBlog->author->name }}"
                                                         class="w-6 h-6 rounded-full mr-2">
                                                @endif
                                                {{ $relatedBlog->author->name }}
                                            </span>
                                            <span>•</span>
                                        @endif
                                        <span>{{ $relatedBlog->created_at->format('d M Y') }}</span>
                                    </div>
                                    <p class="text-gray-600 line-clamp-2">
                                        {{ Str::limit(strip_tags($relatedBlog->description), 120) }}
                                    </p>
                                    <a href="{{ url('/artikelen/' . $relatedBlog->slug) }}" class="inline-flex items-center text-primary-600 hover:text-primary-800 font-medium mt-4">
                                        {{ __('ui.read_more') }}
                                        <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                        </svg>
                                    </a>
                                </div>
                            </article>
                        @endforeach
                    </div>
                </section>
            @endif
        </div>
    </main>
@endsection

@push('styles')
    <!-- Quill CSS -->
    <link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
    <style>
        .prose {
            color: #374151;
            line-height: 1.75;
        }
        .prose h2 {
            font-size: 1.5rem;
            font-weight: 700;
            margin-top: 2rem;
            margin-bottom: 1rem;
            color: #111827;
        }
        .prose h3 {
            font-size: 1.25rem;
            font-weight: 600;
            margin-top: 1.5rem;
            margin-bottom: 0.75rem;
            color: #111827;
        }
        .prose p {
            margin-bottom: 1.25rem;
        }
        .prose a {
            color: #1d4ed8;
            text-decoration: underline;
        }
        .prose a:hover {
            color: #1e40af;
        }
        .prose ul, .prose ol {
            margin-bottom: 1.25rem;
            padding-left: 1.5rem;
        }
        .prose li {
            margin-bottom: 0.5rem;
        }
        .prose img {
            max-width: 100%;
            height: auto;
            border-radius: 0.5rem;
            margin: 1.5rem 0;
        }
    </style>
@endpush

@push('scripts')
    <!-- Quill JS -->
    <script src="https://cdn.quilljs.com/1.3.6/quill.js"></script>
@endpush
