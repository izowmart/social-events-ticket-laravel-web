<?php

namespace App\Http\Controllers\AdminPages;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Town;
use App\Country;
use DB;

class TownsController extends Controller
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
        // $countries = DB::select('SELECT towns.name, towns.created_at, countries.name
        // FROM ((towns INNER JOIN countries ON countries.id = towns.country_id)');
        $towns = Town
            ::join('countries', 'towns.country_id', '=', 'countries.id')
            ->select('towns.name', 'towns.created_at', 'countries.name')
            ->getQuery() // Optional: downgrade to non-eloquent builder so we don't build invalid User objects.
            ->get();
        return view('admin.pages.towns')->with('towns',$towns); 
    }
}
