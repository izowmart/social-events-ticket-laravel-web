<?php

namespace App\Http\Controllers\AdminPages;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Town;
use App\Country;
use DB;

class TownsController extends Controller
{
    //redirect path
    protected $redirectPath = 'admin/towns';
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
        $towns = Town::select('countries.name as country_name','towns.name as town_name','towns.created_at')
                ->join('countries', 'countries.id', '=', 'towns.country_id')
                ->get();
                // dd($towns);
        // $towns = Town
        //     ::join('countries', 'towns.country_id', '=', 'countries.id')
        //     ->select('towns.name', 'towns.created_at', 'countries.name')
        //     ->getQuery() // Optional: downgrade to non-eloquent builder so we don't build invalid User objects.
        //     ->get();
        //echo $towns;
       return view('admin.pages.towns')->with('towns',$towns); 
    }

    public function showAddForm()
    {
        $countries = Country::orderBy('name','asc')->get();
        return view('admin.pages.add_town')->with('countries',$countries); 
    }

    public function store(Request $request)
    {
        
        $this->validate($request, [
            'name'=>'required|unique:towns',
            'country_id'=>'required'
        ]); 

        $town = new Town();
        $town->country_id = $request->country_id;
        $town->name = $request->name;

        $town->save();

        //Give message to admin after successfull registration
        $request->session()->flash('status', 'Town added successfully');
        return redirect($this->redirectPath);
    } 
}
