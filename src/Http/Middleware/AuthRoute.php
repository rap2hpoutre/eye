<?php

namespace Eyewitness\Eye\Http\Middleware;

use Illuminate\Support\Facades\Cache;
use Closure;

class AuthRoute
{
    /**
     * Authenticate an incoming request for the Eyewitness routes.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (($request->app_token !== config('eyewitness.app_token')) || ($request->secret_key !== config('eyewitness.secret_key'))) {
            return response()->json(['error' => 'Unauthorized'], 401)
                             ->setCallback($request->input('callback'));
        }

        return $next($request);
    }
}
