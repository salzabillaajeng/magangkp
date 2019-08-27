<?php

namespace App\Http\Middleware;
Use App\User;
use Auth;

use Closure;

class UserLevelMiddleware
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
       // $user = Auth::user();

        if($request->user()->level != 0 )
        {
           abort(403);
        }

        return $next($request);
    }
}
