<?php

namespace Eyewitness\Eye\Http\Middleware;

use Closure;
use Eyewitness\Eye\Eye;

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
        if (session('eyewitness:auth') || app(Eye::class)->check($request)) {
            return $next($request);
        }

        return redirect()->route('eyewitness.login')->withWarning('Sorry - you must login to Eyewitness first.');
    }
}
