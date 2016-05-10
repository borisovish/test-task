<?php

namespace App\Http\Middleware;

use Closure;
use Gate;
use Illuminate\Support\Facades\Redirect;

class SuperAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if(Gate::denies('super')) return redirect('/');
        return $next($request);
    }
}
