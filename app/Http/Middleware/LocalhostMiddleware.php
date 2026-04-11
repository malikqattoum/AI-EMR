<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class LocalhostMiddleware
{
    /**
     * Handle an incoming request.
     *
     * Only allow requests from localhost (127.0.0.1, ::1, or localhost).
     * This is used for debug routes that should never be accessible in production.
     *
     * Uses Laravel's built-in IP resolution which correctly handles trusted proxy
     * configuration. Does NOT trust X-Forwarded-For or similar headers for access
     * control decisions, as those can be spoofed.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next)
    {
        $ip = $request->ip();
        $allowedIps = ['127.0.0.1', '::1', 'localhost'];

        if (!in_array($ip, $allowedIps)) {
            abort(403, 'This route is only accessible from localhost.');
        }

        return $next($request);
    }
}
