<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class IsProf
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
        if(!$request->user()->prof) {
            return response([
                'message'=>'unauthorized'
                ]
            );
        }


        return $next($request);
    }
}
