<?php

namespace Eyewitness\Eye\Http\Middleware;

use Closure;

class EnabledRoute
{
    /**
     * Handle an incoming request and check if it can access the route.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next, $config)
    {
        if ( ! config('eyewitness.'.$config)) {
            return response()->json(['error' => 'The route is disabled on the server'], 405)
                             ->setCallback($request->input('callback'));
        }

        return $next($request);
    }
}
