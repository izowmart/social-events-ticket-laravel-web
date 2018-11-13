<?php

namespace App\Http\Controllers\Api;

use App\EventScanner;
use App\Helpers\ValidUserScannerPassword;
use App\Http\Resources\ScannerResource;
use App\Http\Traits\UniversalMethods;
use App\Scanner;
use App\Transformers\ScannerTransformer;
use Carbon\Carbon;
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
            'data' => fractal($scanners,ScannerTransformer::class),
        ]);
    }


    public function login(Request $request)
    {
        $validator = Validator::make($request->all(),
            [
                'email'    => 'required|email|exists:scanners,email',
                'password' => ['required', new ValidUserScannerPassword()],
            ],
            [
                'email.required'    => 'Please provide an email address',
                'email.email'       => 'Email address is invalid',
                'email.exists'      => 'You do not have an account. Kindly sign up!',
                'password.required' => 'Please provide a password',
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

                $scanner = $this->guard()->user();

                //generate token for the scanner
                //create token for the user
                $tokenResult = $scanner->createToken('Personal Access Token');
                $token = $tokenResult->token;
                $token->expires_at = Carbon::now()->addWeeks(1);
                $token->save();

                return response()->json(
                    [
                        'success' => true,
                        'message' => 'Scanner Successfully Logged In. Welcome!',
                        'data'    => fractal($scanner,ScannerTransformer::class),
                        'access_token' => $tokenResult->accessToken,
                        'expires_at'   => Carbon::parse(
                            $tokenResult->token->expires_at
                        )->toDateTimeString()
                    ], 201
                );
            } else {
                return response()->json(
                    [
                        'success' => true,
                        'message' => 'Email or Password is Incorrect!',
                        'data'    => [],
                    ], 401
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
    //custom function to check ticket validity when scanning
    public function events_tickets(Request $request){
        try{
            if($event = EventScanner::where('event_id',$request->event_id)){
                if ($event->token == $request->token){
                    if($event->status == false){
                        $event = new EventScanner();
                        $event->status=true;
                        $event->update();
                        return response()->json([
                            'success' => true,
                            'message' => "Ticket is valid",
                            'data' => "$event"
                        ],200);
                    }else{
                        return response()->json([
                            'success' =>false,
                            'message'=> "Ticket has already been scanned",
                            'data' => []
                        ],401
                        );
                    }
                }else{
                    return response()->json([
                        'success' =>false,
                        'message'=> "Invalid token",
                        'data' => []
                    ],401
                    );
                }
            }else{
                return response()->json([
                    'success' =>false,
                    'message'=> "Event doesn't exist",
                    'data' => []
                ],401
                );
            }

        }catch(\Exception $exception){
            return $exception->getMessage();
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
