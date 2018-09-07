<?php

namespace App\Http\Controllers\AdminPages;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Country;

class CountriesController extends Controller
{
    //admin redirect path
    protected $redirectPath = 'admin/countries';
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
        $countries = Country::orderBy('created_at','asc')->get();
        return view('admin.pages.countries')->with('countries',$countries); 
    }

    public function showAddForm()
    {
        return view('admin.pages.add_countries'); 
    }

    public function store(Request $request)
    {
        
        $this->validate($request, [
            'name'=>'required|unique:countries'
        ]); 

        $country = new Country();
        $country->name = $request->name;

        $country->save();

        //Give message to admin after successfull registration
        $request->session()->flash('status', 'Country added successfully');
        return redirect($this->redirectPath);
    } 
}
