<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Symfony\Component\HttpFoundation\Response;

class ValidateCmsApiKey
{
    /**
     * Require a valid API key for public content when CMS_API_KEY is set.
     * Accepts X-API-Key header or Authorization: Bearer <key>.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $key = Config::get('services.cms_api_key');

        if (empty($key)) {
            return $next($request);
        }

        $provided = $request->header('X-API-Key')
            ?? $request->bearerToken();

        if ($provided !== $key) {
            return response()->json(['message' => 'Invalid or missing API key.'], 401);
        }

        return $next($request);
    }
}
