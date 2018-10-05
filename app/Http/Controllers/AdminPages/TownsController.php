<?php

namespace App\Http\Controllers\AdminPages;

use Illuminate\Support\Facades\Crypt;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Country;
use App\Town;
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
        $towns = Town::select('countries.name as country_name','towns.name as town_name','towns.created_at','towns.id')
                ->join('countries', 'countries.id', '=', 'towns.country_id')
                ->get();
       return view('admin.pages.towns')->with('towns',$towns); 
    }

    public function show($id)
    {
        $towns = Town::find(Crypt::decrypt($id))
                ->select('countries.name as country_name','towns.name as town_name','towns.created_at','towns.id')
                ->join('countries', 'countries.id', '=', 'towns.country_id')
                ->take(1)
                ->get();
       return view('admin.pages.towns')->with('towns',$towns); 
    }

    public function showAddForm()
    {
        $countries = Country::orderBy('name','asc')->get();
        return view('admin.pages.add_town')->with('countries',$countries); 
    }

    public function showEditForm($country,$town)
    {
        // fetch the countries to be displayed on dropdown 
        $countries = Country::where('name','!=',$country)->orderBy('name','asc')->get();

        //fetch the specific town info to obtain id
        $db_town = Town::where('name',$town)->first();
        $data=array(
            'town'=>$town,
            'town_id'=>$db_town->id,
            'country'=>$country,
            'country_id'=>$db_town->country_id,
            'countries'=>$countries
        );
        return view('admin.pages.edit_town')->with($data); 
        
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

    public function update(Request $request)
    {
        
        $this->validate($request, [
            'country_id'=>'required',
            'town_id'=>'required',
            'name'=>'required'
        ]); 

        $town = Town::find($request->town_id);
        
        $town->name = $request->name;
        $town->country_id = $request->country_id;

        $town->save();
        //Give message to admin after successfull operation
        $request->session()->flash('status', 'Town updated successfully');
        return redirect($this->redirectPath);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        $id = $request->id;
        $town = Town::find($id);
        //Country::destroy($id);
        $town->delete();
        //Give message to admin after successfull operation
        $request->session()->flash('status', 'Town deleted successfully');
        return redirect($this->redirectPath);
    }
}
