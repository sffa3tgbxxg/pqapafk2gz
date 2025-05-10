<?php

namespace App\Http\Middleware;

use App\Exceptions\ServerErrorException;
use App\Models\Invoice;
use App\Models\Role;
use App\Models\Service;
use Closure;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

class SubscriptionOrRoleInServiceMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response) $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->attributes->get("auth_user");
        $services = Service::query()
            ->whereHas('employees', function (Builder $query) use ($user) {
                $query->where('user_id', $user->id);
            })
            ->get()->filter(function (Service $service) {
                return $service->hasSubscribe();
            });

        if (!$user->isAdmin() && !$user->isSubscribe() && $services->isEmpty()) {
            return response()->json(['message' => 'Нет доступа']);
        }

        return $next($request);
    }
}
