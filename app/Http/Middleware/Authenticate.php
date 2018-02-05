<?php

namespace Eyewitness\Eye\Http\Middleware;

use Closure;

class Authenticate
{
    /**
     * Authenticate a user for Eyewitness.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (! session('eyewitness:auth')) {
            return redirect()->route('eyewitness.login')->withWarning('Sorry - you must login to Eyewitness first.');
        }

        return $next($request);
    }
}
