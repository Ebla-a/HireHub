<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;
use App\Models\ApiLog;
class PerformanceMonitor
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        //Enable Query Monitoring
        DB::enableQueryLog();
        //Recording the order start time
        $request->attributes->set('start_time', microtime(true));
        return $next($request);
    }

    public function terminate($request, $response)
    {
       $startTime = $request->attributes->get('start_time');
        $endTime = microtime(true);
        $duration = $endTime - $startTime;

    
        $queries = DB::getQueryLog();
        $queryCount = count($queries);

        
        ApiLog::create([
            'method' => $request->method(),
            'url' => $request->fullUrl(),
            'duration' => round($duration, 4),
            'query_count' => $queryCount,
            'ip' => $request->ip(),
        ]);
    }
}
