<?php

namespace App\Http\Middleware;

use Closure;

//Auth Facade
use Auth;

class AuthenticateAdmin
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
        //If request does not comes from logged in admin
       //then he shall be redirected to admin Login page
    //    if (! Auth::guard('web_admin')->check()) {
    //        return redirect('/admin_login');
    //    }

       return $next($request);
    }
}
