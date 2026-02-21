@if($carouselWidget && ($items->isNotEmpty() || $carouselWidget->show_view_all_button))
<section class="carousel-widget py-12" aria-label="{{ $carouselWidget->title ?: $carouselWidget->name }}">
    <div class="container mx-auto px-4">
        @if($carouselWidget->title || $carouselWidget->description)
            <div class="mb-8">
                @if($carouselWidget->title)
                    <h2 class="text-2xl font-bold text-gray-900 dark:text-white">{{ $carouselWidget->title }}</h2>
                @endif
                @if($carouselWidget->description)
                    <p class="mt-2 text-gray-600 dark:text-gray-400">{{ $carouselWidget->description }}</p>
                @endif
            </div>
        @endif

        <div class="relative">
            <div class="overflow-x-auto scroll-smooth snap-x snap-mandatory flex gap-6 pb-4 -mx-4 px-4" style="scrollbar-width: none; -ms-overflow-style: none;">
                @foreach($items as $article)
                    <a href="{{ url('/artikelen/' . $article->slug) }}"
                       class="flex-shrink-0 w-72 snap-start rounded-xl bg-white dark:bg-zinc-800 border border-gray-200 dark:border-zinc-700 overflow-hidden shadow-sm hover:shadow-md transition-shadow">
                        <x-picture-with-webp
                            :src="$article->image"
                            :alt="$article->title"
                            class="w-full h-40 object-cover"
                            loading="lazy" />
                        <div class="p-4">
                            <time datetime="{{ ($article->published_at ?? $article->created_at)?->format('Y-m-d') }}" class="text-sm text-gray-500 dark:text-gray-400">
                                {{ \Carbon\Carbon::parse($article->published_at ?? $article->created_at)->translatedFormat('j F Y') }}
                            </time>
                            <h3 class="mt-1 font-semibold text-gray-900 dark:text-white line-clamp-2">{{ $article->title }}</h3>
                            @if(($carouselWidget->show_author ?? false) && ($article->author ?? null))
                                <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">{{ $article->author->name ?? '' }}</p>
                            @endif
                        </div>
                    </a>
                @endforeach

                @if($carouselWidget->show_view_all_button && $items->isNotEmpty())
                    <a href="{{ url('/artikelen') }}"
                       class="flex-shrink-0 w-72 snap-start rounded-xl border-2 border-dashed border-gray-300 dark:border-zinc-600 flex flex-col items-center justify-center gap-2 p-6 text-gray-600 dark:text-gray-400 hover:border-purple-500 hover:text-purple-600 transition-colors">
                        <span class="text-4xl"><i class="fa fa-arrow-right"></i></span>
                        <span class="font-semibold">{{ $carouselWidget->view_all_title ?? __('View all') }}</span>
                        @if($carouselWidget->view_all_description)
                            <span class="text-sm text-center">{{ $carouselWidget->view_all_description }}</span>
                        @endif
                    </a>
                @endif
            </div>

            @if($carouselWidget->show_arrows && $items->count() > 1)
                <button type="button"
                        class="carousel-prev absolute left-0 top-1/2 -translate-y-1/2 -translate-x-2 w-10 h-10 rounded-full bg-white dark:bg-zinc-800 border border-gray-200 dark:border-zinc-700 shadow-md flex items-center justify-center text-gray-600 hover:text-purple-600 z-10"
                        aria-label="{{ __('Previous') }}">
                    <i class="fa fa-chevron-left"></i>
                </button>
                <button type="button"
                        class="carousel-next absolute right-0 top-1/2 -translate-y-1/2 translate-x-2 w-10 h-10 rounded-full bg-white dark:bg-zinc-800 border border-gray-200 dark:border-zinc-700 shadow-md flex items-center justify-center text-gray-600 hover:text-purple-600 z-10"
                        aria-label="{{ __('Next') }}">
                    <i class="fa fa-chevron-right"></i>
                </button>
            @endif
        </div>

        @if($carouselWidget->show_dots && $items->count() > 1)
            <div class="carousel-dots flex justify-center gap-2 mt-4" aria-hidden="true">
                @foreach($items as $i => $item)
                    <button type="button" class="w-2 h-2 rounded-full bg-gray-300 dark:bg-zinc-600 carousel-dot" data-index="{{ $i }}" aria-label="{{ __('Go to slide') }} {{ $i + 1 }}"></button>
                @endforeach
            </div>
        @endif
    </div>
</section>
@endif
