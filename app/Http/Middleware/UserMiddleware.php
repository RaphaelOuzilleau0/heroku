<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class UserMiddleware
{
    const ADMIN_ID = 1;

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if (!$request->user()->hasRole(self::ADMIN_ID)) {
            return \response(\json_encode(["message"=>"Admin Access Only"]), 403)->header('Content-Type', 'application/json');
        }
        
        return $next($request);
    }
}