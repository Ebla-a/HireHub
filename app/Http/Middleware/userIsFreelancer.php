<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class userIsFreelancer
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (auth('sanctum')->check() && auth('sanctum')->user()->role === 'freelancer') {
        return $next($request);
    }

    return response()->json([
        'message' => 'this action is for freelancers only'
    ], 403);
    }
}
