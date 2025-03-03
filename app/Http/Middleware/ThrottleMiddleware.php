<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\RateLimiter;
use App\Helpers\Helper;

class ThrottleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $attemptId = "get:".Auth::user()->id;

        // If you keep requesting the page inside the time,
        // you'll have to wait longer
        RateLimiter::increment($attemptId);

        if (RateLimiter::tooManyAttempts($attemptId, 5)){
            $ttw = RateLimiter::availableIn($attemptId);
            return Helper::errResponse(429,"err-wait",$ttw);
        }

        return $next($request);
    }
}
