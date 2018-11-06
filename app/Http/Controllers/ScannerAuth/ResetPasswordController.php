<?php

namespace App\Http\Controllers\ScannerAuth;

use App\Helpers\ValidUserScannerPassword;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use Illuminate\Foundation\Auth\ResetsPasswords;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;


class ResetPasswordController extends Controller
{
    //trait for handling reset Password
    use ResetsPasswords;

    //user redirect path
    protected $redirectTo = 'scanner/home';



    //Show form to user where they can reset password
    public function showResetForm(Request $request, $token = null)
    {
        return view('scanner.passwords.reset')->with(
            ['token' => $token, 'email' => $request->email]
        );
    }

    /**
     * Get the password reset validation rules.
     *
     * @return array
     */
    protected function rules()
    {
        return [
            'token' => 'required',
            'email' => 'required|email',
            'password' => ['required', new ValidUserScannerPassword()],
        ];
    }


    protected function guard()
    {
        return Auth::guard('scanner');
    }

    public function broker()
    {
        return Password::broker('scanners');
    }

}
