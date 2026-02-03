<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class EnsureUserHasPermission
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next, string $permission): Response
    {
        // Deny early if the user is not authenticated or lacks the permission
        if (!Auth::check() || !Auth::user()->hasPermission($permission)) {
            return response('Unauthorized action.', 403);
        }

        $response = $next($request);

        // Ensure we always return an instance of Symfony\Component\HttpFoundation\Response
        if ($response instanceof Response) {
            return $response;
        }

        return response($response);
    }
}
