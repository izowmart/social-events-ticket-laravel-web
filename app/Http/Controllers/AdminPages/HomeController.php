<?php

namespace App\Http\Controllers\AdminPages;

use App\Http\Controllers\Controller;
use App\User;
use Carbon\Carbon;

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
        //we seleect users registered between the last 1 week
        $new_users = User::where('created_at','>',Carbon::now()->subDays(7)->toDateTimeString())->get();

        $app_users = User::all();

        $data = array(
            'app_users'=>$app_users,
            'new_users'=>$new_users
        );

        return view('admin.pages.home')->with($data); 
    }
}
