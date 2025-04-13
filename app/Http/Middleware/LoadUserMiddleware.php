<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Symfony\Component\HttpFoundation\Response;

class LoadUserMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response) $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::guard('sanctum')->check()) {
            $userId = Auth::id();
            $user = Cache::remember("user:{$userId}", now()->addMinutes(10), function () use ($userId) {
                return Auth::user();
            });

            $request->attributes->set('auth_user', $user);
        }

        return $next($request);
    }
}
