<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;


class userIsClient
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (auth('sanctum')->check() && auth('sanctum')->user()->role === 'client') {
        return $next($request);
    }

    return response()->json([
        'message' => 'this action is for clients only'
    ], 403);
    }
}
