<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class SetLocaleMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Skip locale detection for language switching endpoint
        if ($request->is('language/switch')) {
            return $next($request);
        }
        
        $locale = $this->determineLocale($request);
        
        // Set the application locale
        App::setLocale($locale);
        
        // Store locale in session for consistency (only if not already set or different)
        if (!session('locale') || session('locale') !== $locale) {
            session(['locale' => $locale]);
        }
        
        return $next($request);
    }

    /**
     * Determine the appropriate locale for the user
     */
    private function determineLocale(Request $request): string
    {
        $supportedLocales = config('app.locales', ['nl', 'en']);
        $defaultLocale = config('app.locale', 'nl');

        // 1. Check session for previously set locale
        if (session('locale') && in_array(session('locale'), $supportedLocales)) {
            return session('locale');
        }

        // 2. Check user's IP-based location
        $ipBasedLocale = $this->getLocaleFromIp(client_ip());
        if ($ipBasedLocale && in_array($ipBasedLocale, $supportedLocales)) {
            return $ipBasedLocale;
        }

        // 3. Check browser's Accept-Language header
        $browserLocale = $this->getLocaleFromBrowser($request);
        if ($browserLocale && in_array($browserLocale, $supportedLocales)) {
            return $browserLocale;
        }

        // 4. Fall back to default locale
        return $defaultLocale;
    }

    /**
     * Get locale based on user's IP address
     */
    private function getLocaleFromIp(string $ip): ?string
    {
        // Skip for local/private IPs
        if ($this->isLocalIp($ip)) {
            return null;
        }

        // Cache the result for 24 hours to avoid excessive API calls
        $cacheKey = "ip_locale_{$ip}";
        
        return Cache::remember($cacheKey, 60 * 60 * 24, function () use ($ip) {
            try {
                // Using ipapi.co service (free tier: 1000 requests/day)
                $response = Http::timeout(3)->get("https://ipapi.co/{$ip}/country_code/");
                
                if ($response->successful()) {
                    $countryCode = strtolower(trim($response->body()));
                    return $this->mapCountryToLocale($countryCode);
                }
            } catch (\Exception $e) {

            }

            // Fallback: try ip-api.com (free, no key required)
            try {
                $response = Http::timeout(3)->get("http://ip-api.com/json/{$ip}?fields=countryCode");
                
                if ($response->successful()) {
                    $data = $response->json();
                    if (isset($data['countryCode'])) {
                        $countryCode = strtolower($data['countryCode']);
                        return $this->mapCountryToLocale($countryCode);
                    }
                }
            } catch (\Exception $e) {

            }

            return null;
        });
    }

    /**
     * Map country code to supported locale
     */
    private function mapCountryToLocale(string $countryCode): string
    {
        // Dutch-speaking regions
        $dutchCountries = ['nl', 'be', 'sr']; // Netherlands, Belgium, Suriname
        
        // English-speaking regions (and international fallback)
        $englishCountries = [
            'us', 'gb', 'ca', 'au', 'nz', 'ie', 'za', 'sg', 'my', 'ph', 'in',
            'de', 'fr', 'es', 'it', 'pt', 'se', 'no', 'dk', 'fi', 'ch', 'at'
        ];

        if (in_array($countryCode, $dutchCountries)) {
            return 'nl';
        }

        if (in_array($countryCode, $englishCountries)) {
            return 'en';
        }

        // For other countries, default to Dutch (since this appears to be a Dutch business)
        return 'nl';
    }

    /**
     * Get locale from browser's Accept-Language header
     */
    private function getLocaleFromBrowser(Request $request): ?string
    {
        $acceptLanguage = $request->header('Accept-Language');
        
        if (!$acceptLanguage) {
            return null;
        }

        // Parse Accept-Language header
        $languages = [];
        foreach (explode(',', $acceptLanguage) as $lang) {
            $parts = explode(';', trim($lang));
            $code = trim($parts[0]);
            $quality = 1.0;
            
            if (isset($parts[1]) && strpos($parts[1], 'q=') === 0) {
                $quality = (float) substr($parts[1], 2);
            }
            
            $languages[$code] = $quality;
        }

        // Sort by quality (preference)
        arsort($languages);

        // Check for exact matches first
        foreach ($languages as $lang => $quality) {
            $locale = strtolower(substr($lang, 0, 2));
            
            // Check for Dutch
            if (in_array($locale, ['nl']) || strpos($lang, 'nl') === 0) {
                return 'nl';
            }
            
            // Check for English
            if (in_array($locale, ['en']) || strpos($lang, 'en') === 0) {
                return 'en';
            }
        }

        return null;
    }

    /**
     * Check if IP is local/private
     */
    private function isLocalIp(string $ip): bool
    {
        return in_array($ip, ['127.0.0.1', '::1']) || 
               !filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE);
    }
}
