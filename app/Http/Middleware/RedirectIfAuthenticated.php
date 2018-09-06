<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class RedirectIfAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $guard
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {

        //If request comes from logged in uses, he will
        //be redirected to users's home page.
//        if (Auth::guard('web')->check()) {
//            return redirect('/user_home');
//        }

        //If request comes from logged in admin, he will
        //be redirected to admin's home page.
        if (Auth::guard('web_admin')->check()) {
            return redirect('/admin/home');
        }

        //If request comes from logged in event organizer, he will
        //be redirected to event organizer's home page.
        if (Auth::guard('web_event_organizer')->check()) {
            return redirect('event_organizer/home');
        }

        return $next($request);
    }
}
