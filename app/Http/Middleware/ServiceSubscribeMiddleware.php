<?php

namespace App\Http\Middleware;

use App\Exceptions\ErrorException;
use App\Models\Service;
use HttpException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Symfony\Component\HttpFoundation\Response;
use Closure;

class ServiceSubscribeMiddleware
{
    /**\
     * @param Request $request
     * @param Closure $next
     * @return Response
     * @throws HttpException
     */
    public function handle(Request $request, Closure $next): Response
    {
        $apiKey = $request->header('Authorization');

        $service = Cache::remember("service-api-key:{$apiKey}", 60 * 60, function () use ($apiKey) {
            return Service::query()->where('api_key', $apiKey)->first();
        });

        if ($service == null) {
            return response()->json(['message' => 'Api Key is invalid'], Response::HTTP_UNAUTHORIZED);
        }

        if (!$service->active) {
            return response()->json(['message' => 'Service is disabled'], Response::HTTP_CONFLICT);
        }

        if (!$service->hasSubscribe()) {
            return response()->json(['message' => 'Subscribe is expiry'], Response::HTTP_UNAUTHORIZED);
        }

        $request->attributes->set('service', $service);

        return $next($request);
    }
}
