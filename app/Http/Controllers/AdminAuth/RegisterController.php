<?php

namespace App\Http\Controllers\AdminAuth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

//Validator facade used in validator method
use Illuminate\Support\Facades\Validator;

//admin Model
use App\Admin;

//Auth Facade used in guard
use Auth;

class RegisterController extends Controller
{
    protected $redirectPath = 'admin_register';
    
    //shows registration form to admin
    public function showRegistrationForm()
    {
        return view('admin.auth.register');
    }

  //Handles registration request for admin
    public function register(Request $request)
    {

       //Validates data
        $this->validator($request->all())->validate();

       //Create admin
        $admin = $this->create($request->all());

        //Give message to admin after successfull registration
        $request->session()->flash('status', 'Admin registered successfully');
        return redirect($this->redirectPath);
    }

    //Validates user's Input
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'first_name' => 'required|max:255',
            'last_name' => 'required|max:255',
            'email' => 'required|email|max:255|unique:admins',
            'password' => 'required|min:6|confirmed',
        ]);
    }

    //Create a new admin instance after a validation.
    protected function create(array $data)
    {
        return Admin::create([
            'first_name' => $data['first_name'],
            'last_name' => $data['last_name'],
            'email' => $data['email'],
            'password' => bcrypt($data['password']),
        ]);
    }

    //Get the guard to authenticate admin
   protected function guard()
   {
       return Auth::guard('web_admin');
   }
}
