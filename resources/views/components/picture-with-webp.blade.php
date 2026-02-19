@props([
    'src' => null,
    'alt' => '',
    'class' => '',
    'loading' => 'lazy',
])
@php
    $defaultImg = asset('front/images/blog.png');
    $mainUrl = get_image($src, $defaultImg);
    $webpUrl = $src ? get_image_webp($src) : null;
@endphp
@if($webpUrl)
    <picture>
        <source type="image/webp" srcset="{{ $webpUrl }}">
        <img src="{{ $mainUrl }}" alt="{{ $alt }}" loading="{{ $loading }}" {{ $attributes->merge(['class' => $class]) }}>
    </picture>
@else
    <img src="{{ $mainUrl }}" alt="{{ $alt }}" loading="{{ $loading }}" {{ $attributes->merge(['class' => $class]) }}>
@endif
