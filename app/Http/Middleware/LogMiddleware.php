<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class LogMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        Log::info('Request url' . $request->fullUrl());
        return $next($request);
    }
}
