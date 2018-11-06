<?php

namespace App\Http\Controllers\EventOrganizerAuth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use Illuminate\Foundation\Auth\ResetsPasswords;

//Auth Facade
use Illuminate\Support\Facades\Auth;

//Password Broker Facade
use Illuminate\Support\Facades\Password;

class ResetPasswordController extends Controller
{
    //event_organizer redirect path
    protected $redirectTo = 'event_organizer/home';

    //trait for handling reset Password
    use ResetsPasswords;

    //Show form to event_organizer where they can reset password
    public function showResetForm(Request $request, $token = null)
    {
        return view('event_organizer.passwords.reset')->with(
            ['token' => $token, 'email' => $request->email]
        );
    }

    //returns Password broker of event_organizer
    public function broker()
    {
        return Password::broker('event_organizers');
    }

    //returns authentication guard of event_organizer
    protected function guard()
    {
        return Auth::guard('web_event_organizer');
    }
}
