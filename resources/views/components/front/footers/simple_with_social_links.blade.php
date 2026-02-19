@php
    $logoUrl = get_image(get_setting('site_logo'), asset('front/images/logo.svg'));
    $siteName = get_setting('site_name', 'Your Company');
    $copyright = get_setting('copyright_footer');
@endphp
<footer class="bg-white dark:bg-gray-900">
  <div class="mx-auto max-w-7xl px-6 py-12 md:flex md:items-center md:justify-between lg:px-8">
    <div class="flex justify-center gap-x-6 md:order-2">
        <x-front.social-links />
    </div>
    <p class="mt-8 text-center text-sm/6 text-gray-600 md:order-1 md:mt-0 dark:text-gray-400">
      @if($copyright)
        &copy; {{ date('Y') }} {{ $copyright }}
      @else
        &copy; {{ date('Y') }} {{ $siteName }}, Inc. All rights reserved.
      @endif
    </p>
  </div>
</footer>
