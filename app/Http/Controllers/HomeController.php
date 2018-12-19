<?php

namespace App\Http\Controllers;

use App\Event;
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
        //here we fetch verified (non-)featured (non-)paid events
        $free_events = Event::where('type', '=', 1)->get();

        $featured_events = Event::where('type', '=', 2)
            ->where('featured_event', '=', 2)
            ->get();

        $non_featured_events = Event::where('type', '=', 2)
            ->where('featured_event', '=', 1)
            ->get();

        return view('home.index')->with(['free_events'=> $free_events,'featured_events'=>$featured_events,
                                          'non_featured_events' => $non_featured_events,
        ]);
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
