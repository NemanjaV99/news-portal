<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckAjaxRequest
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if (! $request->ajax()) {

            return response('', 401);
        }

        return $next($request);
    }
}
