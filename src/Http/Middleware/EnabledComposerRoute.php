<?php

namespace Eyewitness\Eye\Http\Middleware;

use Closure;

class EnabledComposerRoute
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
        if ( ! config('eyewitness.monitor_composer_lock')) {
            return response()->json(['error' => 'The composer route is disabled on the server'], 405)
                             ->setCallback($request->input('callback'));
        }

        return $next($request);
    }
}
