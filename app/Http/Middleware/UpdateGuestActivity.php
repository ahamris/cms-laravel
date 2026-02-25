<?php

namespace App\Http\Middleware;

use App\Models\Guest;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class UpdateGuestActivity
{
    /**
     * Handle an incoming request.
     * Records guest activity for web (GET pages) and for all API requests (GET/POST/etc.)
     * so that any frontend call to the API updates stats without requiring a separate ping.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if ($this->shouldTrack($request)) {
            Guest::updateOrCreate(
                ['ip_address' => $request->getClientIp()],
                ['ip_address' => $request->getClientIp(), 'last_activity' => now()]
            );
        }

        return $next($request);
    }

    /**
     * Whether to record this request as guest activity.
     * Web: GET only, skip AJAX (JS tracker handles that). API: any method (every endpoint counts).
     */
    private function shouldTrack(Request $request): bool
    {
        $path = $request->getPathInfo();
        $userAgent = $request->userAgent();

        if (! $userAgent) {
            return false;
        }

        if (str_starts_with($path, '/admin')) {
            return false;
        }

        $isApi = str_starts_with($path, '/api/');

        // Web: skip AJAX (avoid double-count with JS); API: allow all (XHR/fetch is normal)
        if (! $isApi && $request->ajax()) {
            return false;
        }

        foreach (['bot', 'crawler', 'spider', 'scraper', 'facebook', 'twitter', 'google', 'bing', 'yahoo', 'baidu', 'duckduck', 'yandex'] as $pattern) {
            if (str_contains(strtolower($userAgent), $pattern)) {
                return false;
            }
        }

        if ($isApi) {
            return true; // any method
        }

        return $request->isMethod('GET');
    }

}
