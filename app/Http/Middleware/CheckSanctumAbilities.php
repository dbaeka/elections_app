<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckSanctumAbilities
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next, ...$abilities)
    {
        foreach ($abilities as $ability) {
            if ($request->user()->tokenCan($ability)) {
                return $next($request);
            }
        }
        abort(400, "Access Denied");
        return $next($request);
    }
}
