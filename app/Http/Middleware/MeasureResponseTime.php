<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class MeasureResponseTime
{
    public function handle(Request $request, Closure $next)
    {
        $start = microtime(true);
        
        $response = $next($request);
        
        $duration = (microtime(true) - $start) * 1000; // Convert to milliseconds
        $response->headers->set('X-Response-Time', number_format($duration, 2));
        
        return $response;
    }
} 