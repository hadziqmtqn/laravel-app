<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Log;

class LogRouteApiMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);

        $log = [
            'URI' => $request->getUri(),
            'METHOD' => $request->getMethod(),
            'REQUEST_BODY' => $request->all(),
            'RESPONSE' => $response->getContent()
        ];

        try {
            $res = $response->getOriginalContent();

            if ($res['error'] != null) {
                $errorLog = Log::channel('error');
                $errorLog->error(json_encode($log));
            }
        } catch (\Throwable $th) {
            //throw $th;
        }

        Log::info(json_encode($log));

        return $response;
    }
}
