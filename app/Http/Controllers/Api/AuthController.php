<?php

namespace App\Http\Controllers\Api;

use App\Follower;
use App\Helpers\ValidUserScannerPassword;
use App\Http\Controllers\Controller;
use App\Http\Traits\SendFCMNotification;
use App\Http\Traits\UniversalMethods;
use App\Notification;
use App\Transformers\UserTransformer;
use App\User;
use Carbon\Carbon;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Validator;
use Intervention\Image\Facades\Image;


class AuthController extends Controller
{
    //Sends Password Reset emails
    use SendsPasswordResetEmails;

    CONST APPROVED_FOLLOW_REQUEST = 1;
    CONST PENDING_FOLLOW_REQUEST = 2;
    CONST REJECTED_FOLLOW_REQUEST = 3;


    /**
     * Register an app user
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function register_user(Request $request)
    {
        $validator = Validator::make($request->all(),
            [
                'username'   => 'required|alpha_dash',
                'first_name' => 'required|string',
                'last_name'  => 'required|string',
                'email'      => 'bail|required|email|unique:users,email',
                'password'   => ['required', new ValidUserScannerPassword()],
            ],
            [
                'username.required'   => 'Please provide a username',
                'first_name.required' => 'Please provide your first name',
                'last_name.required'  => 'Please provide your last name',
                'email.required'      => 'Please provide your email',
                'email.email'         => 'Email address is invalid',
                'email.unique'        => 'The email address is already in use',
                'password.required'   => 'Please provide a password',
                //                'password.regex'      => 'Password must be at least 6 characters with lowercase and uppercase letters and a number',
            ]
        );

        if ($validator->fails()) {

            return response()->json(
                [
                    'success' => false,
                    'message' => '' . UniversalMethods::getValidationErrorsAsString($validator->errors()->toArray()),
                    'data'    => null
                ], 200
            );
        } else {
            $password = bcrypt($request->password);
            $data = $request->except('password');

            $data['password'] = $password;

            $user = User::create($data);

            if ($user) {

                //create token for the user
                $tokenResult = $user->createToken('Personal Access Token');
                $token = $tokenResult->token;
                $token->expires_at = Carbon::now()->addWeeks(1);
                $token->save();

                $userTransformer = new UserTransformer();
                $userTransformer->setUserId($user->id);

                return response()->json(
                    [
                        'success'      => true,
                        'message'      => 'User Account Created Successfully. Welcome!',
                        'data'         => fractal($user, $userTransformer),
                        'access_token' => $tokenResult->accessToken,
                        'expires_at'   => Carbon::parse(
                            $tokenResult->token->expires_at
                        )->toDateTimeString()
                    ], 201
                );
            } else {
                return response()->json(
                    [
                        'success' => false,
                        'message' => 'User Account Creation Failed!',
                        'data'    => null,
                    ], 500
                );
            }
        }
    }


    public function login_user(Request $request)
    {
        $validator = Validator::make($request->all(),
            [
                'email'    => 'bail|required|email|exists:users,email',
                'password' => ['required', new ValidUserScannerPassword()],
            ],
            [
                'email.required'    => 'Please provide an email address',
                'email.email'       => 'Email address is invalid',
                'email.exists'      => 'You do not have an account. Kindly sign up!',
                'password.required' => 'Please provide a password',
                'password.regex'    => 'Password must be at least 6 characters with lowercase and uppercase letters and a number',
            ]
        );

        if ($validator->fails()) {
            return response()->json(
                [
                    'success' => false,
                    'message' => '' . UniversalMethods::getValidationErrorsAsString($validator->errors()->toArray()),
                    'data'    => null
                ], 200
            );
        } else {
            //attempt to authenticate user
            if (Auth::attempt($request->only(['email', 'password']))) {

                $user = Auth::user();
                //create token for the user
                $tokenResult = $user->createToken('Personal Access Token');
                $token = $tokenResult->token;
                $token->expires_at = Carbon::now()->addWeeks(1);
                $token->save();

                $userTransformer = new UserTransformer();
                $userTransformer->setUserId($user->id);

                return response()->json(
                    [
                        'success'      => true,
                        'message'      => 'User Successfully Logged In. Welcome!',
                        'data'         => fractal($user, $userTransformer),
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
                        'data'    => null,
                    ], 401
                );
            }
        }
    }

    public function facebook_login(Request $request)
    {
        $validator = Validator::make($request->all(),
            [
                'email'       => 'nullable|email',
                'first_name'  => 'required|string',
                'last_name'   => 'required|string',
                'facebook_id' => 'required|string',
                'profile_url' => 'nullable|string',
            ],
            [
                'email.required' => 'Please provide an email address',
                'email.email'    => 'Email address is invalid',
            ]
        );

        if ($validator->fails()) {
            return response()->json(
                [
                    'success' => false,
                    'message' => '' . UniversalMethods::getValidationErrorsAsString($validator->errors()->toArray()),
                    'data'    => null
                ], 200
            );
        } else {
            $first_name = $request->first_name;
            $last_name = $request->last_name;
            $profile_url = $request->has('profile_url') ? $request->profile_url : null;
            $facebook_id = $request->has('facebook_id') ? $request->facebook_id : null;
            $email = $request->has('email') ? $request->email : $facebook_id . "@fikaplaces.com";

            $user = User::where('email', $email)
                ->orwhere('facebook_id', $facebook_id)
                ->first();

            if (!empty($user)) {
                /* a user already exists,
                 * we'll return the user instance from the server
                 *  and not update it since the user may want to
                 * maintain different details with FB on FIKA
                 */

            } else {
                /*
                 * if  no user exists, create one
                 */
                DB::beginTransaction();
                try {

                    $user = $user = User::create([
                        'first_name'  => $first_name,
                        'last_name'   => $last_name,
                        'email'       => $email,
                        'facebook_id' => $facebook_id,
                        'username'   => $first_name . "_" . str_random("4") . random_int(1111, 9999),
                        'password'    => bcrypt($email)
                    ]);

                    //if has profile pic from FB use it...
                    if ($profile_url != null) {
                        $file_name = $user->id . "_" . uniqid().".png";
                        $file_path = public_path("uploads/users/");

                        if (!file_exists($file_path)) {
                            mkdir($file_path, 0755, true);
                        }

                        Image::make($profile_url)
                            ->save($file_path.$file_name);

                        $user->profile_url = $file_name;
                        if ($user->save()) {
                            DB::commit();
                        }else{
                            DB::rollBack();
                            return response()->json([
                                'success' => false,
                                'message' => "User account could not be created at the moment. Try again!---- ",
                            ]);
                        }
                    }

                } catch ( \Exception $e ) {
                    DB::rollBack();
                    return response()->json([
                        'success' => false,
                        'message' => "User account could not be created at the moment. Try again!: ".$e->getMessage(),
                    ]);
                }
            }
            //generate user access token for them...

            //create token for the user
            $tokenResult = $user->createToken('Personal Access Token');
            $token = $tokenResult->token;
            $token->expires_at = Carbon::now()->addWeeks(1);
            $token->save();

            $userTransformer = new UserTransformer();
            $userTransformer->setUserId($user->id);

            return response()->json(
                [
                    'success'      => true,
                    'message'      => 'User Successfully Logged In. Welcome!',
                    'data'         => fractal($user, $userTransformer),
                    'access_token' => $tokenResult->accessToken,
                    'expires_at'   => Carbon::parse(
                        $tokenResult->token->expires_at
                    )->toDateTimeString()
                ], 201
            );
        }


    }

    public function reset_password_user(Request $request)
    {
        $validator = Validator::make($request->all(),
            [
                'email' => 'required|email|exists:users,email'
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
                    'data'    => null
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
     * @param  \Illuminate\Http\Request $request
     *
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
        if ($response == Password::RESET_LINK_SENT) {
            return response()->json(
                [
                    'success' => true,
                    'message' => 'Password Reset Link Email sent successfully. Kindly check your inbox!',
                    'data'    => null
                ], 200
            );
        } else {
            return response()->json(
                [
                    'success' => true,
                    'message' => 'Sending the Reset Link Email Failed!',
                    'data'    => null
                ], 200
            );

        }

    }

    public function user_relations($user_id)
    {
        try {
            $data = $this->fetch_relations($user_id);

            return response()->json(
                [
                    'success' => true,
                    'message' => 'Successfully fetched your people!',
                    'data'    => [$data],
                ]
            );
        } catch ( \Exception $exception ) {
            return response()->json(
                [
                    'success' => false,
                    'message' => 'Something went wrong, sorry: ' . $exception->getMessage(),
                    'data'    => [],
                ]
            );
        }
    }

    /**
     * @param $user_id
     *
     * @return array
     */
    public function fetch_relations($user_id): array
    {
        $user = User::findOrFail($user_id);
        $data = [];
        if ($user) {
            $userTransformer = new UserTransformer();
            $userTransformer->setUserId($user_id);

            $data =
                [
                    'followers' => fractal($user->followers, $userTransformer),
                    'following' => fractal($user->following, $userTransformer)
                ];
        }

        return $data;
    }

    /**
     * @param \Illuminate\Http\Request $request
     *
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function follow(Request $request)
    {
        try {
            $validator = Validator::make($request->all(),
                [
                    'follower_id'             => 'required|exists:users,id',
                    'followed_id'             => 'required|exists:users,id|different:follower_id',
                    'follow_request_response' => 'sometimes'
                ],
                [
                    'follower_id.required'  => 'Kindly sign up',
                    'follower_id.exists'    => 'Kindly sign up!',
                    'followed_id:required'  => 'Kindly sign up!',
                    'followed_id:exists'    => 'Kindly sign up!',
                    'followed_id.different' => 'You cannot follow yourself!'
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
            }

            $follower_id = $request->follower_id;
            $followed_id = $request->followed_id;
            $follow_request_response = (bool)$request->follow_request_response;

//            dd($follow_request_response);

            $follow_record = Follower::where('follower_id', $follower_id)
                ->where('followed_id', $followed_id)
                ->first();

            $follower = User::find($follower_id);
            $followed = User::find($followed_id);

            //relationship doesn't exist
            if ($follow_record == null) {

                //check whether the user allows to be followed automatically
                //otherwise, send a follow request
                if ($followed->auto_follow_status) {

                    //raise a follow record with pending status
                    $follower->follows($followed_id);

                    //raise a follow notification for the followed user
                    Notification::create(
                        [
                            'initializer_id' => $follower_id,
                            'recipient_id'   => $followed_id,
                            'type'           => 3,
                            'model_id'       => null,
                            'seen'           => false
                        ]
                    );

                } else {
                    //raise a follow record with pending status
                    $follower->follows($followed_id, AuthController::PENDING_FOLLOW_REQUEST);

                    //raise follow_request notification for the followed user
                    Notification::create(
                        [
                            'initializer_id' => $follower_id,
                            'recipient_id'   => $followed_id,
                            'type'           => 4,
                            'model_id'       => null,
                            'seen'           => false
                        ]
                    );

                    //TODO:: also send them an FCM notification

                }
            } //relationship exists
            else {
                if ($follow_record->status == 1) {
                    $follower->unfollows($followed_id);
                } elseif ($follow_record->status == 2) {
                    if ($follow_request_response == true) {
                        $follower->approve_following($followed_id);

                        //raise a follow notification for the followed user
                        Notification::where('initializer_id', $follower_id)
                            ->where('recipient_id', $followed_id)
                            ->where('type', 4)
                            ->update(
                                [
                                    'type'     => 3,
                                    'model_id' => null,
                                    'seen'     => false
                                ]
                            );

                    } else {
                        $follower->unfollows($followed_id);
                    }
                }
            }

            $data = $this->fetch_relations($follower_id);

            return response()->json(
                [
                    'success' => true,
                    'message' => 'Successfully fetched your people!',
                    'data'    => $data,
                ]
            );
        } catch ( \Exception $exception ) {
            return response()->json(
                [
                    'success' => false,
                    'message' => 'Something went wrong, sorry: \n' . $exception->getMessage() . ' ------ ' . $exception->getTraceAsString(),
                    'data'    => [],
                ]
            );
        }
    }

    public function follow_request(Request $request)
    {
        $validator = Validator::make($request->all(),
            [
                'follower_id' => 'required|exists:users,id',
                'followed_id' => 'required|exists:users,id|different:follower_id',
                'status'
            ],
            [
                'follower_id.required'  => 'Kindly sign up',
                'follower_id.exists'    => 'Kindly sign up!',
                'followed_id:required'  => 'Kindly sign up!',
                'followed_id:exists'    => 'Kindly sign up!',
                'followed_id.different' => 'You cannot follow yourself!'
            ]
        );

    }


    public function index()
    {
        $users = User::all();

        return response()->json(
            [
                'success' => true,
                'message' => 'Found ' . count($users) . ' users',
                'data'    => fractal($users, UserTransformer::class),
            ], 200
        );

    }

    public function update_auto_follow_status(Request $request)
    {
        try {
            $validator = Validator::make($request->all(),
                [
                    'user_id' => 'required|integer|exists:users,id',
                    'status'  => 'required'
                ],
                [
                    'user_id.required' => 'Kindly Login1',
                    'user_id.integer'  => 'Kindly Login2',
                    'user_id.exists'   => 'Kindly Sign Up3',
                    'status.required'  => 'Kindly Login4',
                    'status.boolean'   => 'Kindly Login5',
                ]);

            if ($validator->fails()) {
                return response()->json(
                    [
                        'success' => false,
                        'message' => '' . UniversalMethods::getValidationErrorsAsString($validator->errors()->toArray()),
                        'data'    => []
                    ], 500
                );
            }
            $user_id = $request->user_id;
            $status = (bool)$request->status;

            $result = User::where('id', $user_id)->update([
                'auto_follow_status' => $status
            ]);

            if ($result) {
                return response()->json([
                    'success' => true,
                    'message' => 'status changed',

                ], 200);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'status change failed',
                ], 500);
            }
        } catch ( \Exception $exception ) {
            return response()->json([
                'success' => false,
                'message' => 'status change failed: ' . $exception->getMessage(),
            ], 500);
        }
    }

    public function update_app_version_code_and_fcm_token(Request $request)
    {
        try {
            $regex = "/^(?:(\d+)\.)?(?:(\d+)\.)?(\d+)$/m";
            $validator = Validator::make($request->all(),
                [
                    'user_id'          => 'required|integer',
                    'app_version_code' => ['nullable', 'regex:' . $regex],
                    'fcm_token'         =>'nullable|string',
                ]);

            if ($validator->fails()) {
                return response()->json(
                    [
                        'success' => false,
                        'message' => '' . UniversalMethods::getValidationErrorsAsString($validator->errors()->toArray()),
                        'data'    => null
                    ], 500
                );
            }

            $user = User::where('id', $request->user_id)->first();

            if ($user != null) {
                if ($request->has('app_version_code') && $request->app_version_code != null) {
                    $user->app_version_code = $request->app_version_code;
                }
                if ($request->has('fcm_token') && $request->fcm_token != null){
                    $user->fcm_token = $request->fcm_token;
                }

                $user->first_time_login = false;
                $user->save();

                $userTransformer = new UserTransformer();
                $userTransformer->setUserId($user->id);

                return response()->json(
                    [
                        'success' => true,
                        'message' => 'Updated successfully',
                        'data'   => fractal($user, $userTransformer)
                    ], 200
                );
            } else {
                return response()->json(
                    [
                        'success' => false,
                        'message' => 'Kindly sign Up',
                    ], 500
                );
            }

        } catch ( \Exception $exception ) {
            return response()->json(
                [
                    'success' => false,
                    'message' => 'error: '.$exception->getMessage(),
                ], 500
            );
        }
    }

    public function testNotification()
    {
        $data=['message'=> 'This is a test notification'];
        $fcm_token = User::find(25)->fcm_token;

        $result = SendFCMNotification::sendNotification([$fcm_token], $data);

        return response()->json([
            'result' => $result
        ]);
    }
}