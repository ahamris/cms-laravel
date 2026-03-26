@props([
    'category' => 'content',
])
@php
    $c = is_string($category) ? $category : 'content';
@endphp
{{-- Mini wireframe previews (decorative; inspired by block-library pickers). --}}
<svg {{ $attributes->merge(['viewBox' => '0 0 120 72', 'xmlns' => 'http://www.w3.org/2000/svg']) }} fill="none" aria-hidden="true" class="h-full w-full">
    @switch($c)
        @case('hero')
            <rect x="8" y="10" width="104" height="52" rx="4" class="stroke-zinc-400 dark:stroke-zinc-500" stroke-width="1.2" fill="rgb(24 24 27 / 0.35)"/>
            <rect x="36" y="22" width="48" height="4" rx="1" class="fill-zinc-300 dark:fill-zinc-500"/>
            <rect x="28" y="30" width="64" height="3" rx="1" class="fill-zinc-300/70 dark:fill-zinc-600"/>
            <rect x="46" y="40" width="28" height="8" rx="2" fill="rgb(59 130 246 / 0.85)"/>
            @break
        @case('features')
            <rect x="8" y="8" width="104" height="56" rx="4" class="stroke-zinc-400 dark:stroke-zinc-500" stroke-width="1.2" fill="rgb(24 24 27 / 0.35)"/>
            @foreach ([16, 30, 44] as $y)
                <rect x="16" y="{{ $y }}" width="10" height="10" rx="2" class="fill-zinc-300 dark:fill-zinc-500"/>
                <rect x="30" y="{{ $y + 1 }}" width="40" height="3" rx="1" class="fill-zinc-300 dark:fill-zinc-500"/>
                <rect x="30" y="{{ $y + 6 }}" width="72" height="2" rx="1" class="fill-zinc-300/60 dark:fill-zinc-600"/>
            @endforeach
            @break
        @case('cta')
            <rect x="8" y="12" width="104" height="48" rx="4" class="stroke-zinc-400 dark:stroke-zinc-500" stroke-width="1.2" fill="rgb(24 24 27 / 0.35)"/>
            <rect x="24" y="22" width="72" height="20" rx="3" class="stroke-zinc-400 dark:stroke-zinc-500" stroke-width="1" fill="rgb(39 39 42 / 0.5)"/>
            <rect x="46" y="30" width="28" height="8" rx="2" fill="rgb(59 130 246 / 0.9)"/>
            @break
        @case('bento')
            <rect x="8" y="8" width="104" height="56" rx="4" class="stroke-zinc-400 dark:stroke-zinc-500" stroke-width="1.2" fill="rgb(24 24 27 / 0.35)"/>
            <rect x="12" y="12" width="44" height="24" rx="2" class="fill-zinc-300/80 dark:fill-zinc-600"/>
            <rect x="60" y="12" width="48" height="11" rx="2" class="fill-zinc-300/60 dark:fill-zinc-600"/>
            <rect x="60" y="27" width="48" height="33" rx="2" class="fill-zinc-300/50 dark:fill-zinc-600"/>
            <rect x="12" y="40" width="44" height="20" rx="2" class="fill-zinc-300/60 dark:fill-zinc-600"/>
            @break
        @case('pricing')
            <rect x="8" y="10" width="104" height="52" rx="4" class="stroke-zinc-400 dark:stroke-zinc-500" stroke-width="1.2" fill="rgb(24 24 27 / 0.35)"/>
            @foreach ([14, 46, 78] as $i => $x)
                <rect x="{{ $x }}" y="18" width="28" height="36" rx="2" class="fill-zinc-300/50 dark:fill-zinc-600" @if($i === 1) stroke="rgb(59 130 246)" stroke-width="1.5" @endif/>
                <rect x="{{ $x + 6 }}" y="26" width="16" height="3" rx="1" class="fill-zinc-200 dark:fill-zinc-500"/>
            @endforeach
            @break
        @case('header')
            <rect x="8" y="10" width="104" height="52" rx="4" class="stroke-zinc-400 dark:stroke-zinc-500" stroke-width="1.2" fill="rgb(24 24 27 / 0.35)"/>
            <rect x="14" y="18" width="18" height="8" rx="2" class="fill-zinc-300 dark:fill-zinc-500"/>
            @foreach ([40, 56, 72, 88] as $x)
                <rect x="{{ $x }}" y="22" width="14" height="2" rx="1" class="fill-zinc-300/70 dark:fill-zinc-600"/>
            @endforeach
            <rect x="98" y="20" width="10" height="6" rx="2" fill="rgb(59 130 246 / 0.8)"/>
            @break
        @case('newsletter')
            <rect x="8" y="12" width="104" height="48" rx="4" class="stroke-zinc-400 dark:stroke-zinc-500" stroke-width="1.2" fill="rgb(24 24 27 / 0.35)"/>
            <rect x="34" y="22" width="52" height="3" rx="1" class="fill-zinc-300 dark:fill-zinc-500"/>
            <rect x="28" y="32" width="48" height="8" rx="2" class="stroke-zinc-400 dark:stroke-zinc-500" stroke-width="1" fill="transparent"/>
            <rect x="80" y="32" width="28" height="8" rx="2" fill="rgb(59 130 246 / 0.85)"/>
            @break
        @case('stats')
            <rect x="8" y="14" width="104" height="44" rx="4" class="stroke-zinc-400 dark:stroke-zinc-500" stroke-width="1.2" fill="rgb(24 24 27 / 0.35)"/>
            @foreach ([18, 46, 74] as $x)
                <rect x="{{ $x }}" y="24" width="22" height="8" rx="1" class="fill-zinc-200 dark:fill-zinc-400"/>
                <rect x="{{ $x }}" y="36" width="22" height="2" rx="1" class="fill-zinc-300/60 dark:fill-zinc-600"/>
            @endforeach
            @break
        @case('testimonials')
            <rect x="8" y="10" width="104" height="52" rx="4" class="stroke-zinc-400 dark:stroke-zinc-500" stroke-width="1.2" fill="rgb(24 24 27 / 0.35)"/>
            <rect x="22" y="20" width="76" height="4" rx="1" class="fill-zinc-300 dark:fill-zinc-500"/>
            <rect x="18" y="28" width="84" height="2" rx="1" class="fill-zinc-300/60 dark:fill-zinc-600"/>
            <rect x="18" y="33" width="72" height="2" rx="1" class="fill-zinc-300/60 dark:fill-zinc-600"/>
            @foreach ([44, 52, 60, 68, 76] as $x)
                <circle cx="{{ $x }}" cy="46" r="2" class="fill-amber-400"/>
            @endforeach
            @foreach ([52, 60, 68] as $cx)
                <circle cx="{{ $cx }}" cy="58" r="1.5" class="fill-zinc-400 dark:fill-zinc-500"/>
            @endforeach
            @break
        @case('blog')
            <rect x="8" y="10" width="104" height="52" rx="4" class="stroke-zinc-400 dark:stroke-zinc-500" stroke-width="1.2" fill="rgb(24 24 27 / 0.35)"/>
            @foreach ([14, 46, 78] as $x)
                <rect x="{{ $x }}" y="16" width="28" height="18" rx="2" class="fill-zinc-300/70 dark:fill-zinc-600"/>
                <rect x="{{ $x }}" y="38" width="24" height="3" rx="1" class="fill-zinc-300 dark:fill-zinc-500"/>
                <rect x="{{ $x }}" y="44" width="20" height="2" rx="1" class="fill-zinc-300/50 dark:fill-zinc-600"/>
            @endforeach
            @break
        @case('contact')
            <rect x="8" y="10" width="104" height="52" rx="4" class="stroke-zinc-400 dark:stroke-zinc-500" stroke-width="1.2" fill="rgb(24 24 27 / 0.35)"/>
            @foreach ([18, 28, 38] as $y)
                <rect x="20" y="{{ $y }}" width="80" height="6" rx="1" class="stroke-zinc-400 dark:stroke-zinc-500" stroke-width="0.8" fill="transparent"/>
            @endforeach
            <rect x="20" y="48" width="32" height="8" rx="2" fill="rgb(59 130 246 / 0.85)"/>
            @break
        @case('team')
            <rect x="8" y="10" width="104" height="52" rx="4" class="stroke-zinc-400 dark:stroke-zinc-500" stroke-width="1.2" fill="rgb(24 24 27 / 0.35)"/>
            @foreach ([18, 46, 74] as $x)
                <circle cx="{{ $x + 10 }}" cy="26" r="8" class="fill-zinc-300 dark:fill-zinc-500"/>
                <rect x="{{ $x }}" y="38" width="20" height="2" rx="1" class="fill-zinc-300/70 dark:fill-zinc-600"/>
            @endforeach
            @break
        @case('content')
            <rect x="8" y="10" width="104" height="52" rx="4" class="stroke-zinc-400 dark:stroke-zinc-500" stroke-width="1.2" fill="rgb(24 24 27 / 0.35)"/>
            <rect x="16" y="18" width="48" height="4" rx="1" class="fill-zinc-300 dark:fill-zinc-500"/>
            @foreach ([26, 32, 38, 44, 50] as $y)
                <rect x="16" y="{{ $y }}" width="88" height="2" rx="1" class="fill-zinc-300/50 dark:fill-zinc-600"/>
            @endforeach
            @break
        @case('logo_cloud')
            <rect x="8" y="16" width="104" height="40" rx="4" class="stroke-zinc-400 dark:stroke-zinc-500" stroke-width="1.2" fill="rgb(24 24 27 / 0.35)"/>
            @foreach ([14, 34, 54, 74, 94] as $x)
                <rect x="{{ $x }}" y="28" width="16" height="8" rx="1" class="fill-zinc-300/70 dark:fill-zinc-600"/>
            @endforeach
            @foreach ([24, 44, 64, 84] as $x)
                <rect x="{{ $x }}" y="40" width="12" height="6" rx="1" class="fill-zinc-300/50 dark:fill-zinc-600"/>
            @endforeach
            @break
        @case('faqs')
            <rect x="8" y="10" width="104" height="52" rx="4" class="stroke-zinc-400 dark:stroke-zinc-500" stroke-width="1.2" fill="rgb(24 24 27 / 0.35)"/>
            @foreach ([18, 30, 42, 54] as $y)
                <rect x="14" y="{{ $y }}" width="92" height="8" rx="2" class="fill-zinc-300/40 dark:fill-zinc-600"/>
                <polyline points="99,{{ $y + 3 }} 102.5,{{ $y + 6 }} 99,{{ $y + 9 }}" class="stroke-zinc-400 dark:stroke-zinc-500" fill="none" stroke-width="1.2" stroke-linecap="round" stroke-linejoin="round"/>
            @endforeach
            @break
        @case('footer')
            <rect x="8" y="12" width="104" height="48" rx="4" class="stroke-zinc-400 dark:stroke-zinc-500" stroke-width="1.2" fill="rgb(24 24 27 / 0.35)"/>
            @foreach ([14, 42, 70] as $x)
                <rect x="{{ $x }}" y="20" width="20" height="2" rx="1" class="fill-zinc-300 dark:fill-zinc-500"/>
                @foreach ([26, 30, 34] as $dy)
                    <rect x="{{ $x }}" y="{{ $dy }}" width="16" height="1.5" rx="0.5" class="fill-zinc-300/50 dark:fill-zinc-600"/>
                @endforeach
            @endforeach
            <rect x="14" y="48" width="16" height="6" rx="1" class="fill-zinc-300 dark:fill-zinc-500"/>
            @break
        @default
            <rect x="8" y="10" width="104" height="52" rx="4" class="stroke-zinc-400 dark:stroke-zinc-500" stroke-width="1.2" fill="rgb(24 24 27 / 0.35)"/>
            <rect x="16" y="22" width="88" height="3" rx="1" class="fill-zinc-300 dark:fill-zinc-500"/>
            <rect x="16" y="30" width="72" height="2" rx="1" class="fill-zinc-300/60 dark:fill-zinc-600"/>
    @endswitch
</svg>
