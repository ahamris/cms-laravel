<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Symfony\Component\HttpFoundation\Response;

class RestrictToAllowedOrigins
{
    /**
     * Block requests whose Origin or Referer is not the React SPA URL(s).
     * Uses the same allowed origins as CORS. If Origin/Referer is missing, allow (server-side, Swagger, etc.).
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $allowed = Config::get('cors.allowed_origins', ['*']);

        if (in_array('*', $allowed, true)) {
            return $next($request);
        }

        $origin = $request->header('Origin') ?? $this->originFromReferer($request->header('Referer'));

        if ($origin === null) {
            return $next($request);
        }

        $origin = rtrim($origin, '/');
        $allowed = array_map(fn (string $o) => rtrim($o, '/'), $allowed);

        if (! in_array($origin, $allowed, true)) {
            return response()->json(['message' => 'Request origin not allowed.'], 403);
        }

        return $next($request);
    }

    private function originFromReferer(?string $referer): ?string
    {
        if ($referer === null || $referer === '') {
            return null;
        }

        $parsed = parse_url($referer);
        if (! isset($parsed['scheme'], $parsed['host'])) {
            return null;
        }

        $port = isset($parsed['port']) && ! in_array($parsed['port'], [80, 443], true)
            ? ':'.$parsed['port']
            : '';

        return $parsed['scheme'].'://'.$parsed['host'].$port;
    }
}
