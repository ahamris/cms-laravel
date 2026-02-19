<?php

namespace App\Http\Middleware;

use App\Helpers\Variable;
use Closure;
use Illuminate\Http\JsonResponse;

class CheckIfUser
{

    private function checkIfUserIsActive($user)
    {
        return ($user->hasRole(Variable::ROLE_USER));
    }

    /**
     * Answer to unauthorized access request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    private function respondToUnauthorizedRequest($request)
    {
        if ($request->ajax() || $request->wantsJson()) {
            return response('Not authorized', JsonResponse::HTTP_UNAUTHORIZED);
        }

        return redirect()->guest(route('admin.login'));
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (auth()->guest()) {
            return $this->respondToUnauthorizedRequest($request);
        }

        if (! $this->checkIfUserIsActive(auth()->user())) {
            // If the user is not an admin, redirect to the customer dashboard
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'You do not have permission to access this page.',
                    'redirect' => route('user.index')
                ], 403);
            }

            return redirect()->route('user.index')
                ->with('error', 'You do not have permission to access this page.');
        }

        return $next($request);
    }
}
