<?php

namespace App\Http\Controllers;

use App\PaymentResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
   
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('welcome');
    }

    public function home_user()
    {
        return view('user.home');
    }

    public function home_scanner()
    {
        return view('scanner.home');
    }

}
