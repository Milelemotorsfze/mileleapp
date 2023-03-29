<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class CheckStatus
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    // public function handle(Request $request, Closure $next): Response
    // {
    //     return $next($request);
    // }
    public function handle($request, Closure $next)
    {
        $response = $next($request);
        //If the status is inactive and data is deleted redirect to login 
        if(Auth::check() && Auth::user()->status != 'active'){
            Auth::logout();
            return redirect('/login')->with('erro_login', 'Your error text');
            // return redirect()->route('logout');
        }
        return $response;
    }
}
