<?php

namespace App\Http\Middleware;

use Closure;

class AuthenticateEventOrganizer
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
        //If request does not comes from logged in event_organizer
       //then he shall be redirected to event_organizer Login page
        if (! Auth::guard('web_event_organizer')->check()) {
            return redirect('/event_organizer/login');
        }
        return $next($request);
    }
}
