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

class AccessInvoiceUserMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response) $next
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        $invoiceId = $request->route('invoice');
        $invoice = Invoice::query()->findOrFail($invoiceId);
        $employeeRole = Service::query()
            ->where('id', $invoice->service_id)
            ->first()
            ->role($request->attributes->get('auth_user')->id);

        if ($employeeRole == null) {
            throw new ServerErrorException();
        }

        if (!in_array($employeeRole->nameOrig(), [Role::OWNER_SERVICE, Role::ADMIN_SERVICE, Role::OPERATOR_SERVICE])) {
            throw new ServerErrorException();
        }

        return $next($request);
    }
}
