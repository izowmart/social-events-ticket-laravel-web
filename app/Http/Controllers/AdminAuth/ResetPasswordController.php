<?php

namespace App\Http\Controllers\AdminAuth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use Illuminate\Foundation\Auth\ResetsPasswords;

//Auth Facade
use Illuminate\Support\Facades\Auth;

//Password Broker Facade
use Illuminate\Support\Facades\Password;

class ResetPasswordController extends Controller
{
    //admin redirect path
    protected $redirectTo = '/admin_home';

    //trait for handling reset Password
    use ResetsPasswords;

    //Show form to admin where they can reset password
    public function showResetForm(Request $request, $token = null)
    {
        return view('admin.passwords.reset')->with(
            ['token' => $token, 'email' => $request->email]
        );
    }

    //returns Password broker of admin
    public function broker()
    {
        return Password::broker('admins');
    }

    //returns authentication guard of admin
    protected function guard()
    {
        return Auth::guard('web_admin');
    }
}
