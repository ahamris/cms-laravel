<?php

namespace App\Http\Middleware;

use App\Helpers\Variable;
use Closure;
use Illuminate\Http\JsonResponse;

class CheckIfAdmin
{
    private function checkIfUserIsAdmin($user)
    {
        return $user->hasRole(Variable::ROLE_ADMIN);
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
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (auth()->guest()) {
            return $this->respondToUnauthorizedRequest($request);
        }

        if (! $this->checkIfUserIsAdmin(auth()->user())) {
            // If the user is not an admin, redirect to the customer dashboard
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'You do not have permission to access this page.',
                    'redirect' => url('/'), // Redirect to homepage for JSON requests
                ], 403);
            }

            // Redirect non-admins to the homepage to prevent a redirect loop
            return redirect('/')
                ->with('error', 'You do not have permission to access this page.');
        }

        return $next($request);
    }
}
