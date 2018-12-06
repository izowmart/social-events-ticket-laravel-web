<?php

namespace App\Http\Controllers\EventOrganizerAuth;

use App\Mail\NewEventOrganizerAccount;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

//Validator facade used in validator method
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
//event_organizer Model
use App\EventOrganizer;
use Auth;

class RegisterController extends Controller
{    
    //we will redirect to the same page
    protected $redirectPath = 'event_organizer/register';

    //shows registration form to Event Organizer
    public function showRegistrationForm()
    {
        return view('event_organizer.auth.register');
    }

  //Handles registration request for event_organizer
    public function register(Request $request)
    {

       //Validates data
        $this->validator($request->all())->validate();

       //Create event_organizer
        $event_organizer = $this->create($request->all());

        $data=[
            'name' => $event_organizer->name
        ];

        //send them a welcome email
        Mail::to($event_organizer)->queue(new NewEventOrganizerAccount($data));

        //Give message to event_organizer after successfull registration
        $request->session()->flash('status', 'Regestered successfully, You can proceed to login.');
        return redirect($this->redirectPath);
    }

    //Validates user's Input
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'first_name' => 'required|max:255',
            'last_name' => 'required|max:255',
            'email' => 'required|email|max:255|unique:event_organizers',
            'password' => 'required|min:6|confirmed',
        ]);
    }

    //Create a new event_organizer instance after a validation.
    protected function create(array $data)
    {
        return EventOrganizer::create([
            'first_name' => $data['first_name'],
            'last_name' => $data['last_name'],
            'email' => $data['email'],
            'password' => bcrypt($data['password']),
        ]);
    }

    //Get the guard to authenticate event_organizer
   protected function guard()
   {
       return Auth::guard('web_event_organizer');
   }

   
   
}
