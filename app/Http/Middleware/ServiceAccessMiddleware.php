<?php

namespace App\Http\Middleware;

use App\Models\Service;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

class ServiceAccessMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response) $next
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        $serviceId = $request->route('service') ?? $request->integer('service_id');

        if (!($serviceId instanceof Service) && !($request->attributes->get('service') instanceof Service)) {
            $service = Service::query()->where('id', $serviceId)->firstOrFail();
        }

        $employee = $service->employee($request->attributes->get('auth_user')?->id);
        if (!in_array($employee->role->nameOrig(), $roles)) {
            Log::error(json_encode($employee));
            throw new AccessDeniedHttpException("Нет доступа");
        }

        return $next($request);
    }
}
