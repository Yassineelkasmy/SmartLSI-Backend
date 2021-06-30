<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class IsEtu
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
        if(!$request->user()->etu) {
            return response([
                'message'=>'unauthorized'
                ]
            );
        }


        return $next($request);
    }
}
