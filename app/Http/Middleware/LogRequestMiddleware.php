<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class LogRequestMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next)
    {
        $logData = [
            'ip' => $request->ip(),
            'method' => $request->method(),
            'url' => $request->fullUrl(),
            'header' => $request->headers->all(),
            'body' => $request->all(),
            'timestamp' => now()->toDateTimeString(),
        ];
        $logJson = json_encode($logData, JSON_PRETTY_PRINT);
        Log::info('Incoming Request', ['log' => $logJson]);

        return $next($request);
    }
}
