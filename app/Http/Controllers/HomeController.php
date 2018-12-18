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
        return view('home.index');
    }

    public function about()
    {
        return view('home.about');
    }

    public function tickets()
    {
        return view('home.tickets-info');
    }

    public function blog()
    {
        return view('home.blog');
    }

    public function contact()
    {
        return view('home.contact');
    }

    public function selling()
    {
        return view('home.start_selling');
    }

    public function home_user()
    {
        return view('user.home');
    }

    public function home_scanner()
    {
        return view('scanner.home');
    }

    public function showPrivacyPolicy()
    {
        return view('privacy-policy');
    }

    public function showTermsAndConditions()
    {
        return view('terms-and-conditions');
    }

}
