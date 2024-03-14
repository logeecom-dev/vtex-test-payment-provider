<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class VerifyVtexRequest
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string ...$guards): Response
    {
        if (!$request->header('X-Vtex-Api-Is-Testsuite')
            && ($request->header('X-Vtex-Api-Appkey') !== config('services.vtex.app_key')
                || $request->header('X-Vtex-Api-Apptoken') !== config('services.vtex.app_token')
            )) {
            return \response('Unauthorized!', 401);
        }

        return $next($request);
    }
}
