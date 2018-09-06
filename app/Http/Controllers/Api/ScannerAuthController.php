<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\ScannerResource;
use App\Http\Traits\UniversalMethods;
use App\Scanner;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Validator;

class ScannerAuthController extends Controller
{
    use SendsPasswordResetEmails, AuthenticatesUsers;


    public function index()
    {
        $scanners = Scanner::all();

        return response()->json([
            'success'=>true,
            'message'      => 'Found '.count($scanners),
            'data' => ScannerResource::make($scanners),
        ]);
    }


    public function login(Request $request)
    {
        $validator = Validator::make($request->all(),
            [
                'email'    => 'required|email|exists:scanners,email',
                'password' => 'required|min:8'
            ],
            [
                'email.required'    => 'Please provide an email address',
                'email.email'       => 'Email address is invalid',
                'email.exists'      => 'You do not have an account. Kindly sign up!',
                'password.required' => 'Please provide a password',
                'password.min'      => 'Password must be at least 8 characters',
            ]
        );

        if ($validator->fails()) {
            return response()->json(
                [
                    'success' => false,
                    'message' => '' . UniversalMethods::getValidationErrorsAsString($validator->errors()->toArray()),
                    'data'    => []
                ], 200
            );
        } else {
            //attempt to authenticate user
            if ($this->guard()->attempt($request->only(['email', 'password']))) {

                return response()->json(
                    [
                        'success' => true,
                        'message' => 'Scanner Successfully Logged In. Welcome!',
                        'data'    => ScannerResource::make($this->guard()->user()),
                    ], 200
                );
            } else {
                return response()->json(
                    [
                        'success' => true,
                        'message' => 'Email or Password is Incorrect!',
                        'data'    => [],
                    ], 200
                );
            }
        }
    }

    public function reset_password(Request $request)
    {
        $validator = Validator::make($request->all(),
            [
                'email' => 'required|email|exists:scanners,email'
            ],
            [
                'email.required' => 'Please provide an email address',
                'email.email'    => 'Email address is invalid',
                'email.exists'   => 'You do not have an account. Kindly sign up!',
            ]
        );

        if ($validator->fails()) {
            return response()->json(
                [
                    'success' => false,
                    'message' => '' . UniversalMethods::getValidationErrorsAsString($validator->errors()->toArray()),
                    'data'    => []
                ], 200
            );
        } else {

            //send an email
            $response = $this->sendResetLinkEmail($request);

            return $response;
        }

    }

    /**
     * Send a reset link to the given user.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\JsonResponse
     */
    public function sendResetLinkEmail(Request $request)
    {
        $this->validateEmail($request);

        // We will send the password reset link to this user. Once we have attempted
        // to send the link, we will examine the response then see the message we
        // need to show to the user. Finally, we'll send out a proper response.
        $response = $this->broker()->sendResetLink(
            $request->only('email')
        );
        if ($response == Password::RESET_LINK_SENT){
            return response()->json(
                [
                    'success' => true,
                    'message' => 'Password Reset Link Email sent successfully. Kindly check your inbox!',
                    'data'    => []
                ], 200
            );
        }else{
            return response()->json(
                [
                    'success' => true,
                    'message' => 'Sending the Reset Link Email Failed!',
                    'data'    => []
                ], 200
            );

        }

    }


    //Custom guard for admin
    protected function guard()
    {
        return Auth::guard('scanner');
    }

    //Password Broker for admin Model
    public function broker()
    {
        return Password::broker('scanners');
    }
}
