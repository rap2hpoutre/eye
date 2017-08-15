<?php

namespace Eyewitness\Eye\App\Http\Middleware;

use Closure;

class EnabledLogRoute
{
    /**
     * Handle an incoming request and check if it can access the route.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (! config('eyewitness.routes_log')) {
            return response()->json(['error' => 'The log route is disabled on the server'], 405)
                             ->setCallback($request->input('callback'));
        }

        return $next($request);
    }
}
