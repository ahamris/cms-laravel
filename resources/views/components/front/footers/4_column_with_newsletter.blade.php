@php
    $logoUrl = get_image(get_setting('site_logo'), asset('front/images/logo.svg'));
    $siteName = get_setting('site_name', 'Your Company');
    $copyright = get_setting('copyright_footer');
@endphp
<footer class="bg-white dark:bg-gray-900">
  <div class="mx-auto max-w-7xl px-6 pt-20 pb-8 sm:pt-24 lg:px-8 lg:pt-32">
    <div class="xl:grid xl:grid-cols-3 xl:gap-8">
      <div class="grid grid-cols-2 gap-8 xl:col-span-2">
        <div class="md:grid md:grid-cols-2 md:gap-8">
          @if(isset($footerLinks[1]) && $footerLinks[1]->count() > 0)
            <div>
              <h3 class="text-sm/6 font-semibold text-gray-900 dark:text-white">Solutions</h3>
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
              <h3 class="text-sm/6 font-semibold text-gray-900 dark:text-white">Support</h3>
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
              <h3 class="text-sm/6 font-semibold text-gray-900 dark:text-white">Company</h3>
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
              <h3 class="text-sm/6 font-semibold text-gray-900 dark:text-white">Legal</h3>
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
      @php
          // TODO: Newsletter subscription functionality needs to be implemented
          // This form should connect to a newsletter subscription system
      @endphp
      <div class="mt-10 xl:mt-0">
        <h3 class="text-sm/6 font-semibold text-gray-900 dark:text-white">Subscribe to our newsletter</h3>
        <p class="mt-2 text-sm/6 text-gray-600 dark:text-gray-400">The latest news, articles, and resources, sent to your inbox weekly.</p>
        <form class="mt-6 sm:flex sm:max-w-md">
          <label for="email-address" class="sr-only">Email address</label>
          <input id="email-address" type="email" name="email-address" required placeholder="Enter your email" autocomplete="email" class="w-full min-w-0 rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-500 focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 sm:w-64 sm:text-sm/6 xl:w-full dark:bg-white/5 dark:text-white dark:outline-gray-700 dark:focus:outline-indigo-500" />
          <div class="mt-4 sm:mt-0 sm:ml-4 sm:shrink-0">
            <button type="submit" class="flex w-full items-center justify-center rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-xs hover:bg-indigo-500 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600 dark:bg-indigo-500 dark:hover:bg-indigo-400 dark:focus-visible:outline-indigo-500">Subscribe</button>
          </div>
        </form>
      </div>
    </div>
    <div class="mt-16 border-t border-gray-900/10 pt-8 sm:mt-20 md:flex md:items-center md:justify-between lg:mt-24 dark:border-white/10">
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
