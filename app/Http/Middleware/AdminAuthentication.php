<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;

class AdminAuthentication {

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null) {
        // dd(Auth::guard('customer')->user());
        if (Auth::guard('customer')->check() && (Auth::guard('customer')->user()->type == 'super_admin' || Auth::guard('customer')->user()->type == 'admin')) {
            return $next($request);
        }
        return Redirect::route('adminLogin')->with('error_message', 'Please login first!');
    }

}
