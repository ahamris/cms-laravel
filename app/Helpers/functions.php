<?php

use App\Models\Setting;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;

if (! function_exists('api_path')) {
    /**
     * Build API path from config key. Use for seeders, menus, sitemap (headless).
     *
     * @param  string  $key  Key from config('api_paths.endpoints'), e.g. 'home', 'solution', 'module'
     * @param  string|array  ...$segments  Optional segment(s) for template endpoints (e.g. api_path('solution', 'crm') => '/api/solutions/crm')
     */
    function api_path(string $key, ...$segments): string
    {
        $prefix = config('api_paths.prefix', 'api');
        $endpoints = config('api_paths.endpoints', []);
        $path = $endpoints[$key] ?? $key;

        if (! empty($segments)) {
            $path = sprintf($path, ...$segments);
        }

        return '/'.ltrim($prefix.'/'.ltrim($path, '/'), '/');
    }
}

if (! function_exists('get_setting')) {
    function get_setting(string $key, ?string $default = null): mixed
    {
        $settings = Setting::getCached();
        $setting = $settings[$key] ?? null;

        return $setting?->value ?? $default;
    }
}

if (! function_exists('get_environment_badge')) {
    function get_environment_badge(): ?array
    {
        $envBadge = config('app.environment_badge');

        if (! $envBadge || $envBadge === 'production') {
            return null;
        }

        $badges = [
            'ont' => [
                'label' => 'DEV',
                'color' => 'bg-red-500',
                'text_color' => 'text-white',
            ],
            'test' => [
                'label' => 'TEST',
                'color' => 'bg-yellow-500',
                'text_color' => 'text-white',
            ],
            'acc' => [
                'label' => 'STAGING',
                'color' => 'bg-blue-500',
                'text_color' => 'text-white',
            ],
        ];

        return $badges[$envBadge] ?? null;
    }
}

if (! function_exists('get_image')) {
    function get_image(?string $url = null, ?string $default = null): string
    {
        if (empty($url)) {
            return $default ?? asset('front/images/blog.png');
        }
        if (filter_var($url, FILTER_VALIDATE_URL)) {
            return $url;
        }

        $cleanPath = ltrim($url, '/');
        if (str_starts_with($cleanPath, 'storage/')) {
            $cleanPath = substr($cleanPath, 8);
        }
        if (Storage::disk('public')->exists($cleanPath)) {
            return Storage::disk('public')->url($cleanPath);
        }

        $publicPath = public_path($url);
        if (file_exists($publicPath)) {
            return asset($url);
        }

        return $default ?? asset('front/images/blog.png');
    }
}

if (! function_exists('get_image_webp')) {
    /**
     * Return WebP URL for the given image path if a webp/ sibling exists (from image optimizer).
     * Use with <picture><source type="image/webp" srcset="{{ get_image_webp($url) }}">...</picture>.
     *
     * @return string|null WebP URL or null if no WebP version exists
     */
    function get_image_webp(?string $url = null): ?string
    {
        if (empty($url) || filter_var($url, FILTER_VALIDATE_URL)) {
            return null;
        }

        $cleanPath = ltrim($url, '/');
        if (str_starts_with($cleanPath, 'storage/')) {
            $cleanPath = substr($cleanPath, 8);
        }

        $dir = pathinfo($cleanPath, PATHINFO_DIRNAME);
        $base = pathinfo($cleanPath, PATHINFO_FILENAME);
        $webpRelative = ($dir === '.' ? '' : $dir . '/') . 'webp/' . $base . '.webp';

        if (Storage::disk('public')->exists($webpRelative)) {
            return Storage::disk('public')->url($webpRelative);
        }

        $publicWebpPath = public_path(pathinfo($url, PATHINFO_DIRNAME) . '/webp/' . pathinfo($url, PATHINFO_FILENAME) . '.webp');
        if (file_exists($publicWebpPath)) {
            return asset(pathinfo($url, PATHINFO_DIRNAME) . '/webp/' . pathinfo($url, PATHINFO_FILENAME) . '.webp');
        }

        return null;
    }
}

if (! function_exists('localized_route')) {
    function localized_route(string $name, array $parameters = [], ?string $locale = null): string
    {
        $locale = $locale ?? app()->getLocale();
        $defaultLocale = config('app.locale', 'nl');

        if ($locale === $defaultLocale) {
            return route($name, $parameters);
        }
        $parameters['locale'] = $locale;

        return route($name, $parameters);
    }
}

if (! function_exists('current_locale')) {
    function current_locale(): string
    {
        return app()->getLocale();
    }
}

if (! function_exists('available_locales')) {
    function available_locales(): array
    {
        return [
            'nl' => __('Dutch'),
            'en' => __('English'),
        ];
    }
}

if (! function_exists('client_ip')) {
    /**
     * Resolve the client IP, suitable when the app is behind Cloudflare (or other proxies).
     * Prefers CF-Connecting-IP (Cloudflare), then X-Forwarded-For, then X-Real-IP, then REMOTE_ADDR.
     *
     * @return string|null Valid IPv4 or IPv6 address, or null if none could be resolved
     */
    function client_ip(): ?string
    {
        $request = request();
        $candidates = [
            $request->header('CF-Connecting-IP'),   // Cloudflare: original client IP
            $request->header('X-Forwarded-For'),     // Often "client, proxy1, proxy2" — first is client
            $request->header('X-Real-IP'),
            $request->server('REMOTE_ADDR'),
        ];

        foreach ($candidates as $value) {
            if (empty($value)) {
                continue;
            }
            // X-Forwarded-For can be a comma-separated list; first is the client
            $ip = is_string($value) ? trim(explode(',', $value)[0]) : (string) $value;
            if (filter_var($ip, FILTER_VALIDATE_IP)) {
                return $ip;
            }
        }

        return null;
    }
}

if (! function_exists('is_active_route')) {

    function is_active_route(string $routeName): bool
    {
        $currentRoute = request()->route();
        if (! $currentRoute) {
            return false;
        }

        $currentRouteName = $currentRoute->getName();
        if (! $currentRouteName) {
            return false;
        }

        // Use Laravel's built-in route matching with wildcard
        return request()->routeIs($routeName.'*');
    }
}

if (! function_exists('route_index')) {

    function route_index(string $routeName, array $parameters = []): string
    {
        return route($routeName.'.index', $parameters);
    }
}

if (! function_exists('format_date_for_display')) {
    /**
     * Format a date for display in dd/mm/yyyy format
     */
    function format_date_for_display($date): string
    {
        if (! $date) {
            return '';
        }

        try {
            if ($date instanceof Carbon) {
                return $date->format('d/m/Y');
            }

            return Carbon::parse($date)->format('d/m/Y');
        } catch (Exception $e) {
            return '';
        }
    }
}

if (! function_exists('format_date_for_backend')) {
    /**
     * Format a date from dd/mm/yyyy to Y-m-d for backend storage
     */
    function format_date_for_backend($date): ?string
    {
        if (! $date) {
            return null;
        }

        try {
            // If it's already in Y-m-d format
            if (preg_match('/^\d{4}-\d{2}-\d{2}$/', $date)) {
                return $date;
            }

            // If it's in dd/mm/yyyy format
            if (preg_match('/^\d{2}\/\d{2}\/\d{4}$/', $date)) {
                return Carbon::createFromFormat('d/m/Y', $date)->format('Y-m-d');
            }

            // Try to parse other formats
            return Carbon::parse($date)->format('Y-m-d');
        } catch (Exception $e) {
            return null;
        }
    }
}

if (! function_exists('format_localized_date')) {
    /**
     * Format a date in the current application locale
     *
     * @param  mixed  $date  Carbon instance, date string, or null
     * @param  string  $format  Format string (default: 'd M Y')
     * @param  string|null  $locale  Override locale (default: app locale)
     * @return string Formatted date in the specified locale
     */
    function format_localized_date($date, string $format = 'd M Y', ?string $locale = null): string
    {
        if (! $date) {
            return '';
        }

        try {
            $carbon = $date instanceof Carbon ? $date : Carbon::parse($date);

            // Use provided locale or fall back to application locale
            $locale = $locale ?? app()->getLocale();
            $carbon->locale($locale);

            // Use translatedFormat for localized month/day names
            return $carbon->translatedFormat($format);
        } catch (Exception $e) {
            return '';
        }
    }
}

if (! function_exists('format_localized_datetime')) {
    /**
     * Format a datetime in the current application locale
     *
     * @param  mixed  $date  Carbon instance, date string, or null
     * @param  string|null  $locale  Override locale (default: app locale)
     * @return string Formatted datetime in the specified locale
     */
    function format_localized_datetime($date, ?string $locale = null): string
    {
        return format_localized_date($date, 'd F Y H:i', $locale);
    }
}

if (! function_exists('format_localized_date_long')) {
    /**
     * Format a date with full month name in the current application locale
     *
     * @param  mixed  $date  Carbon instance, date string, or null
     * @param  string|null  $locale  Override locale (default: app locale)
     * @return string Formatted date with full month name in the specified locale
     */
    function format_localized_date_long($date, ?string $locale = null): string
    {
        return format_localized_date($date, 'd F Y', $locale);
    }
}


