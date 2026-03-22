<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;

class RateLimitHeaders
{
    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);

        $key = 'api:' . ($request->user()?->id ?? $request->ip());
        $maxAttempts = 60;
        $remaining = RateLimiter::remaining($key, $maxAttempts);

        $response->headers->set('X-RateLimit-Limit', $maxAttempts);
        $response->headers->set('X-RateLimit-Remaining', max(0, $remaining));

        return $response;
    }
}
