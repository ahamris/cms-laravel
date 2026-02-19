@php
    $logoUrl = get_image(get_setting('site_logo'), asset('front/images/logo.svg'));
    $siteName = get_setting('site_name', 'Your Company');
    $copyright = get_setting('copyright_footer');
@endphp
<footer class="bg-white dark:bg-gray-900">
  <div class="mx-auto max-w-7xl px-6 py-16 sm:py-24 lg:px-8 lg:py-32">
    @php
        $ctaTitle = get_setting('footer_cta_title', 'Get started');
        $ctaSubtitle = get_setting('footer_cta_subtitle', 'Boost your productivity. Start using our app today.');
        $ctaDescription = get_setting('footer_cta_description', 'Incididunt sint fugiat pariatur cupidatat consectetur sit cillum anim id veniam aliqua proident excepteur commodo do ea.');
        $ctaButtonText = get_setting('footer_cta_button_text', 'Get started');
        $ctaButtonUrl = get_setting('footer_cta_button_url', '#');
    @endphp
    <div class="mx-auto max-w-2xl text-center">
      <hgroup>
        <h2 class="text-base/7 font-semibold text-indigo-600 dark:text-indigo-400">{{ $ctaTitle }}</h2>
        <p class="mt-2 text-4xl font-semibold tracking-tight text-balance text-gray-900 sm:text-5xl dark:text-white">{{ $ctaSubtitle }}</p>
      </hgroup>
      <p class="mx-auto mt-6 max-w-xl text-lg/8 text-pretty text-gray-600 dark:text-gray-400">{{ $ctaDescription }}</p>
      <div class="mt-8 flex justify-center">
        <a href="{{ $ctaButtonUrl }}" class="rounded-md bg-indigo-600 px-3.5 py-2.5 text-sm font-semibold text-white shadow-xs hover:bg-indigo-500 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600 dark:bg-indigo-500 dark:hover:bg-indigo-400 dark:focus-visible:outline-indigo-500">{{ $ctaButtonText }}</a>
      </div>
    </div>
    <div class="mt-24 border-t border-gray-900/10 pt-12 xl:grid xl:grid-cols-3 xl:gap-8 dark:border-white/10">
      <img src="{{ $logoUrl }}" alt="{{ $siteName }}" class="footer-logo max-w-full w-auto h-auto max-h-12 object-contain dark:hidden" />
      <img src="{{ $logoUrl }}" alt="{{ $siteName }}" class="footer-logo max-w-full w-auto h-auto max-h-12 object-contain not-dark:hidden" />
      <div class="mt-16 grid grid-cols-2 gap-8 xl:col-span-2 xl:mt-0">
        <div class="md:grid md:grid-cols-2 md:gap-8">
          @if(isset($footerLinks[1]) && $footerLinks[1]->count() > 0)
            <div>
              <h3 class="text-sm/6 font-semibold text-gray-950 dark:text-white">Solutions</h3>
              <ul role="list" class="mt-6 space-y-4">
                @foreach($footerLinks[1] as $link)
                  <li>
                    <a href="{{ $link->url }}" 
                       {{ !empty($link->open_in_new_tab) ? 'target="_blank" rel="noopener noreferrer"' : '' }}
                       class="text-sm/6 text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-white">
                      {{ $link->title }}
                    </a>
                  </li>
                @endforeach
              </ul>
            </div>
          @endif
          @if(isset($footerLinks[2]) && $footerLinks[2]->count() > 0)
            <div class="mt-10 md:mt-0">
              <h3 class="text-sm/6 font-semibold text-gray-950 dark:text-white">Support</h3>
              <ul role="list" class="mt-6 space-y-4">
                @foreach($footerLinks[2] as $link)
                  <li>
                    <a href="{{ $link->url }}" 
                       {{ !empty($link->open_in_new_tab) ? 'target="_blank" rel="noopener noreferrer"' : '' }}
                       class="text-sm/6 text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-white">
                      {{ $link->title }}
                    </a>
                  </li>
                @endforeach
              </ul>
            </div>
          @endif
        </div>
        <div class="md:grid md:grid-cols-2 md:gap-8">
          @if(isset($footerLinks[3]) && $footerLinks[3]->count() > 0)
            <div>
              <h3 class="text-sm/6 font-semibold text-gray-950 dark:text-white">Company</h3>
              <ul role="list" class="mt-6 space-y-4">
                @foreach($footerLinks[3] as $link)
                  <li>
                    <a href="{{ $link->url }}" 
                       {{ !empty($link->open_in_new_tab) ? 'target="_blank" rel="noopener noreferrer"' : '' }}
                       class="text-sm/6 text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-white">
                      {{ $link->title }}
                    </a>
                  </li>
                @endforeach
              </ul>
            </div>
          @endif
          @if(isset($footerLinks[4]) && $footerLinks[4]->count() > 0)
            <div class="mt-10 md:mt-0">
              <h3 class="text-sm/6 font-semibold text-gray-950 dark:text-white">Legal</h3>
              <ul role="list" class="mt-6 space-y-4">
                @foreach($footerLinks[4] as $link)
                  <li>
                    <a href="{{ $link->url }}" 
                       {{ !empty($link->open_in_new_tab) ? 'target="_blank" rel="noopener noreferrer"' : '' }}
                       class="text-sm/6 text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-white">
                      {{ $link->title }}
                    </a>
                  </li>
                @endforeach
              </ul>
            </div>
          @endif
        </div>
      </div>
    </div>
    <div class="mt-12 border-t border-gray-900/10 pt-8 md:flex md:items-center md:justify-between dark:border-white/10">
      <div class="flex gap-x-6 md:order-2">
        <x-front.social-links />
      </div>
      <p class="mt-8 text-sm/6 text-gray-600 md:order-1 md:mt-0 dark:text-gray-400">
        @if($copyright)
          &copy; {{ date('Y') }} {{ $copyright }}
        @else
          &copy; {{ date('Y') }} {{ $siteName }}, Inc. All rights reserved.
        @endif
      </p>
    </div>
  </div>
</footer>
