<?php

namespace App\Http\Controllers\AdminAuth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

//Validator facade used in validator method
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Mail;
use App\Http\Requests;
//admin Model
use App\Admin;

//Auth Facade used in guard
use Auth;

class RegisterController extends Controller
{
    protected $redirectPath = 'admin/admins';
    
    //shows registration form to admin
    public function showRegistrationForm()
    {
        return view('admin.pages.add_admin');
    }
    
    //Create a new admin instance after a validation.
    public function register(Request $request)
    {
        $this->validate($request, [
            'first_name' => 'required|max:255',
            'last_name' => 'required|max:255',
            'email' => 'required|email|max:255|unique:admins',
        ]);

        $password = substr(md5(microtime()),rand(0,26),8);
        Admin::create([
            'first_name' => $request['first_name'],
            'last_name' => $request['last_name'],
            'email' => $request['email'],
            'password' => bcrypt($password),
        ]);
        $admin_first_name = $request['first_name'];
        $admin_email = $request['email'];
        $admin_password = $password;

        $data = array('name'=>$admin_first_name,'email'=>$admin_email,'password'=>$admin_password);
        $beautymail = app()->make(\Snowfire\Beautymail\Beautymail::class);
        $beautymail->send('admin.registration_mail', $data, function($message) use ($admin_email, $admin_first_name)
        {
            $message
                ->from('noreply@fikaplaces.com','Fika Places')
                ->to($admin_email, $admin_first_name)
                ->subject('Registered as admin');
        });

        //Give message to admin after successfull registration
        $request->session()->flash('status', 'login credential has been sent to registered admin email');
        return redirect($this->redirectPath);
        
    }

   

    //Get the guard to authenticate admin
   protected function guard()
   {
       return Auth::guard('web_admin');
   }
}
