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
        return view('admin.pages.add_country'); 
    }

    public function showEditForm($name)
    {
        $country = Country::where('name',$name)->first();

        $data=array(
            'name'=>$name,
            'id'=>$country->id
        );
        return view('admin.pages.edit_country')->with($data); 
        
    }

    public function store(Request $request)
    {
        
        $this->validate($request, [
            'name'=>'required|unique:countries'
        ]); 

        $country = new Country();
        $country->name = $request->name;

        $country->save();

        //Give message to admin after successfull operation
        $request->session()->flash('status', 'Country added successfully');
        return redirect($this->redirectPath);
    } 

    public function update(Request $request)
    {
        
        $this->validate($request, [
            'name'=>'required'
        ]); 

        $country = Country::find($request->id);
        $country->name = $request->name;

        $country->save();
        //Give message to admin after successfull operation
        $request->session()->flash('status', 'Country updated successfully');
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
        $country = Country::find($id);
        //Country::destroy($id);
        $country->delete();
        //Give message to admin after successfull operation
        $request->session()->flash('status', 'Country deleted successfully');
        return response()->json(['success'=>'Deleted successfully']);
    }
}
