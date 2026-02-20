<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AllowFrontendOrigins
{
    /**
     * Only allow requests whose Origin or Referer is in the allowed list.
     * If the list is empty, all requests are allowed (no restriction).
     */
    public function handle(Request $request, Closure $next): Response
    {
        $allowed = config('frontend-api.allowed_origins', []);

        if ($allowed === [] || in_array('*', $allowed, true)) {
            return $next($request);
        }

        $origin = $request->header('Origin');
        $referer = $request->header('Referer');

        $requestOrigin = $origin;
        if (! $requestOrigin && $referer) {
            $parts = parse_url($referer);
            $scheme = $parts['scheme'] ?? 'https';
            $host = $parts['host'] ?? null;
            $port = $parts['port'] ?? null;
            $requestOrigin = $host ? $scheme.'://'.$host.(isset($port) && (($scheme === 'https' && $port != 443) || ($scheme === 'http' && $port != 80)) ? ':'.$port : '') : null;
        }

        if ($requestOrigin === null) {
            return $next($request);
        }

        $requestHost = parse_url($requestOrigin, \PHP_URL_HOST);
        if ($requestHost === null) {
            $requestHost = $requestOrigin;
        }

        // Always allow same-origin (e.g. Swagger UI on the same backend)
        $appHost = $request->getHost();
        if (strtolower($requestHost) === strtolower($appHost)) {
            return $next($request);
        }

        // Always allow localhost for developers (e.g. Vite dev server at localhost:5173)
        if (strtolower($requestHost) === 'localhost') {
            return $next($request);
        }

        foreach ($allowed as $domain) {
            $domain = trim($domain);
            if ($domain === '') {
                continue;
            }
            if (str_starts_with($domain, '.')) {
                $suffix = substr($domain, 1);
                if (strtolower($requestHost) === strtolower($suffix) || str_ends_with(strtolower($requestHost), strtolower($domain))) {
                    return $next($request);
                }
            } elseif (strtolower($requestHost) === strtolower($domain)) {
                return $next($request);
            }
        }

        return response()->json(['message' => 'Origin not allowed.'], 403);
    }
}
