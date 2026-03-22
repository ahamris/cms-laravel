<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class StandardApiPagination
{
    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);

        if ($response instanceof JsonResponse) {
            $data = $response->getData(true);

            if (isset($data['meta']) && !isset($data['links'])) {
                $meta = $data['meta'];
                $data['links'] = [
                    'first' => $request->fullUrlWithQuery(['page' => 1]),
                    'last'  => $request->fullUrlWithQuery(['page' => $meta['last_page'] ?? 1]),
                    'prev'  => ($meta['current_page'] ?? 1) > 1 ? $request->fullUrlWithQuery(['page' => ($meta['current_page'] ?? 1) - 1]) : null,
                    'next'  => ($meta['current_page'] ?? 1) < ($meta['last_page'] ?? 1) ? $request->fullUrlWithQuery(['page' => ($meta['current_page'] ?? 1) + 1]) : null,
                ];
                $response->setData($data);
            }
        }

        return $response;
    }
}
