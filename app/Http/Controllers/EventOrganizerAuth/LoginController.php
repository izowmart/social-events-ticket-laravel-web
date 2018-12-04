<?php

namespace App\Http\Controllers\EventOrganizerAuth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

//Class needed for login and Logout logic
use Illuminate\Foundation\Auth\AuthenticatesUsers;

//Auth facade
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    //Where to redirect event_organizer after login.
    protected $redirectTo = 'event_organizer/home';

    //Trait
    use AuthenticatesUsers;    

    //Custom guard for event_organizer
    protected function guard()
    {
      return Auth::guard('web_event_organizer');
    }

    //Shows event_organizer login form
   public function showLoginForm()
   {
       return view('event_organizer.auth.login');
   }

   /**
     * Get the needed authorization credentials from the request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    protected function credentials(Request $request)
    {
        $credentials = $request->only($this->username(), 'password');
        $credentials['status'] = 1;
        return $credentials;
    }

    /**
     * Get the failed login response instance.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    protected function sendFailedLoginResponse(Request $request)
    {
        $errors = [$this->username() => trans('auth.failed')];
        // Load user from database
        $user = \App\EventOrganizer::where($this->username(), $request->{$this->username()})->first();
        // Check if user was successfully loaded, that the password matches
        // and active is not 1. If so, override the default error message.
        if ($user && \Hash::check($request->password, $user->password) && $user->status == 0) {
            $errors = [$this->username() => 'Your account is not activated.'];
        }else if($user && \Hash::check($request->password, $user->password) && $user->status == 2){
            $errors = [$this->username() => 'Your account is deactivated.'];
        }
        if ($request->expectsJson()) {
            return response()->json($errors, 422);
        }
        return redirect()->back()
            ->withInput($request->only($this->username(), 'remember'))
            ->withErrors($errors);
    }
}
