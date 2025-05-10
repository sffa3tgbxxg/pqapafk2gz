<?php

namespace App\Http\Middleware;

use App\Exceptions\ServerErrorException;
use App\Models\Invoice;
use App\Models\Role;
use App\Models\Service;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

class IsAdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response) $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!$request->attributes->get("auth_user")->isAdmin()) {
            return response()->json(['message' => 'Нет доступа']);
        }

        return $next($request);
    }
}
