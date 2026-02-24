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
    /**
     * Return a full (absolute) URL for an image path. Always returns a full URL, never a relative path.
     */
    function get_image(?string $url = null, ?string $default = null): string
    {
        $result = null;

        if (empty($url)) {
            $result = $default ?? asset('front/images/blog.png');
        } elseif (filter_var($url, FILTER_VALIDATE_URL)) {
            $result = $url;
        } else {
            $cleanPath = ltrim($url, '/');
            if (str_starts_with($cleanPath, 'storage/')) {
                $cleanPath = substr($cleanPath, 8);
            }
            if (Storage::disk('public')->exists($cleanPath)) {
                $result = Storage::disk('public')->url($cleanPath);
            } elseif (file_exists(public_path($url))) {
                $result = asset($url);
            } else {
                $result = $default ?? asset('front/images/blog.png');
            }
        }

        // Ensure full URL (no relative path)
        if (is_string($result) && $result !== '' && ! str_starts_with($result, 'http://') && ! str_starts_with($result, 'https://')) {
            $result = asset(ltrim($result, '/'));
        }

        return $result;
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

if (! function_exists('url_to_path')) {
    /**
     * Convert a full URL to path-only (no scheme/host). Leaves relative paths unchanged.
     *
     * @param  string  $url  Full URL (e.g. https://example.com/foo?bar=1) or path (e.g. /foo)
     * @return string Path with leading slash, e.g. /foo?bar=1, or original string if not a full URL
     */
    function url_to_path(string $url): string
    {
        $url = trim($url);
        if ($url === '') {
            return $url;
        }

        $parsed = parse_url($url);
        if ($parsed === false || ! isset($parsed['host'])) {
            return $url;
        }

        $path = $parsed['path'] ?? '/';
        if ($path === '') {
            $path = '/';
        }
        if (! str_starts_with($path, '/')) {
            $path = '/'.$path;
        }
        if (isset($parsed['query']) && $parsed['query'] !== '') {
            $path .= '?'.$parsed['query'];
        }
        if (isset($parsed['fragment']) && $parsed['fragment'] !== '') {
            $path .= '#'.$parsed['fragment'];
        }

        return $path;
    }
}

if (! function_exists('resource_urls_to_paths')) {
    /**
     * Recursively convert full URLs in a resource response to path-only (no domain).
     * External URLs and image URLs are left unchanged (full URL) so the frontend can use them directly.
     * Only recurses into arrays; objects (e.g. Resource collections) are left unchanged.
     *
     * @param  mixed  $data  Array, object, or string (e.g. JsonResource::toArray() or response array)
     * @return mixed Same structure with same-origin non-image URL strings replaced by path-only; external and image URLs unchanged
     */
    function resource_urls_to_paths(mixed $data): mixed
    {
        if (is_array($data)) {
            return array_map('resource_urls_to_paths', $data);
        }

        if (is_object($data)) {
            return $data;
        }

        if (is_string($data) && filter_var($data, FILTER_VALIDATE_URL)) {
            if (resource_url_should_keep_full($data)) {
                return $data;
            }

            return url_to_path($data);
        }

        return $data;
    }
}

if (! function_exists('resource_url_should_keep_full')) {
    /**
     * Whether the given URL should be left as full URL (not stripped to path) in API responses.
     * True for external URLs (different host, e.g. ui-avatars.com) and for same-origin image URLs.
     */
    function resource_url_should_keep_full(string $url): bool
    {
        $host = parse_url($url, PHP_URL_HOST);
        if ($host === null || $host === '') {
            return false;
        }

        $appUrl = config('app.url', '');
        $appHost = $appUrl !== '' ? parse_url($appUrl, PHP_URL_HOST) : null;
        if ($appHost !== null && strtolower($host) !== strtolower($appHost)) {
            return true;
        }

        return resource_url_is_image($url);
    }
}

if (! function_exists('resolve_menu_template')) {
    /**
     * Resolve frontend template name from a menu link URL and optional page slug.
     * Used when building menu API responses so the React frontend knows which template to render.
     *
     * @param  string  $url  Resolved link URL or path (e.g. /api/contact, /api/blog, /api/blog/my-post)
     * @param  string|null  $pageSlug  Page slug when link is a page reference (for api/pages/* or page_id)
     * @return string Template name (e.g. contact, blog-list, blog-detail, page)
     */
    function resolve_menu_template(string $url, ?string $pageSlug = null): string
    {
        $path = trim($url);
        if ($path === '') {
            return config('menu_templates.default', 'page');
        }

        // Normalize to path only (no scheme, host, query)
        $parsed = parse_url($path);
        if ($parsed !== false && (str_contains($path, '://') || isset($parsed['query']))) {
            $path = $parsed['path'] ?? '/';
        }
        $path = trim($path, '/');
        if ($path === '') {
            return config('menu_templates.default', 'page');
        }

        $exact = config('menu_templates.exact', []);
        if (isset($exact[$path])) {
            return $exact[$path];
        }

        $prefixes = config('menu_templates.prefix', []);
        foreach ($prefixes as $prefix => $template) {
            if (str_starts_with($path, $prefix)) {
                if ($template === null) {
                    // api/pages/ → resolve by page slug (from argument or extracted from path)
                    $slug = $pageSlug;
                    if (($slug === null || $slug === '') && strlen($path) > strlen($prefix)) {
                        $slug = trim(substr($path, strlen($prefix)), '/');
                        $slug = explode('/', $slug)[0] ?? '';
                    }
                    $pageSlugs = config('menu_templates.page_slugs', []);
                    if ($slug !== null && $slug !== '' && isset($pageSlugs[$slug])) {
                        return $pageSlugs[$slug];
                    }
                    return config('menu_templates.default', 'page');
                }
                return $template;
            }
        }

        // If we have a page slug but path didn't match (e.g. custom url stored), still try page_slugs
        if ($pageSlug !== null && $pageSlug !== '') {
            $pageSlugs = config('menu_templates.page_slugs', []);
            if (isset($pageSlugs[$pageSlug])) {
                return $pageSlugs[$pageSlug];
            }
        }

        return config('menu_templates.default', 'page');
    }
}

if (! function_exists('resource_url_is_image')) {
    /**
     * Whether the given URL is considered an image URL (path has image extension or /storage/ or /images/).
     */
    function resource_url_is_image(string $url): bool
    {
        $path = parse_url($url, PHP_URL_PATH);
        if ($path === null || $path === '') {
            return false;
        }
        $pathLower = strtolower($path);
        $imageExtensions = ['.jpg', '.jpeg', '.png', '.gif', '.webp', '.svg', '.avif', '.ico'];
        foreach ($imageExtensions as $ext) {
            if (str_ends_with($pathLower, $ext)) {
                return true;
            }
        }

        return str_contains($pathLower, '/storage/') || str_contains($pathLower, '/images/');
    }
}


