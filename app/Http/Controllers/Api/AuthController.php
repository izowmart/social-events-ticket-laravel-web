<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserCollection;
use App\Http\Resources\UserResource;
use App\Http\Traits\UniversalMethods;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;


class AuthController extends Controller
{
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
                'username'      =>  'required',
                'first_name'    =>  'required',
                'last_name'     =>  'required',
                'email'         =>  'required|email|unique:users,email',
                'password'      =>  'required|min:8'
            ],
        [
            'username.required'     =>  'Please provide a username',
            'first_name.required'   =>  'Please provide your first name',
            'last_name.required'    =>  'Please provide your last name',
            'email.required'        =>  'Please provide your email',
            'email.email'           =>  'Email address is invalid',
            'email.unique'          =>  'The email address is already in use',
            'password.required'     =>  'Please provide a password',
            'password.min'          =>  'Password must be at least 8 characters',
        ]
            );

        if ($validator->fails()) {

            return response()->json(
                [
                    'success'   => false,
                    'message' => '' . UniversalMethods::getValidationErrorsAsString($validator->errors()->toArray()),
                    'data'      => []
                ],401
            );
        }
        else{

            $user = User::create($request->all());

            if ($user) {

                return response()->json(
                    [
                        'success' => true,
                        'message' => 'User Account Created Successfully. Welcome!',
                        'data' => UserResource::collection($user),
                    ], 200
                );
            }else{
                return response()->json(
                    [
                        'success' => false,
                        'message' => 'User Account Creation Failed!',
                        'data' => [],
                    ], 500
                );
            }
        }
    }

    public function index()
    {
        $users = User::all();

        return response()->json(
            [
                'success' => true,
                'message' => 'Found '.count($users).' users',
                'data' => UserResource::collection($users),
            ], 200
        );

    }


}