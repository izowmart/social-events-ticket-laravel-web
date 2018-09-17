<?php

namespace App\Http\Controllers\AdminPages;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Venue;
use App\Town;

class VenuesController extends Controller
{
    //redirect path
    protected $redirectPath = 'admin/venues';
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
        $venues = Venue::select('venues.id','venues.name as venue_name','venues.contact_person_name','venues.contact_person_phone','venues.contact_person_email','venues.latitude','venues.longitude','towns.name as town_name')
                ->join('towns', 'towns.id', '=', 'venues.town_id')
                ->get();
        return view('admin.pages.venues')->with('venues',$venues); 
    }

    public function showAddForm()
    {
        $towns = Town::orderBy('name','asc')->get();
        return view('admin.pages.add_venue')->with('towns',$towns); 
    }

    public function showEditForm(Request $request)
    {
        $venue = Venue::select('venues.id as venue_id','venues.name as venue_name','venues.contact_person_name','venues.contact_person_phone','venues.contact_person_email','towns.id as town_id','towns.name as town_name')
                ->join('towns', 'towns.id', '=', 'venues.town_id')
                ->where('venues.id',$request->id)
                ->first();
        $town_name = $venue->town_name;    

        // fetch the towns to be displayed on dropdown 
        $towns = Town::where('name','!=',$town_name)->orderBy('name','asc')->get();

        $data=array(
            'towns'=>$towns,
            'venue'=>$venue
        );
        return view('admin.pages.edit_venue')->with($data); 
        
    }

    public function store(Request $request)
    {
        
        $this->validate($request, [
            'venue_name'=>'required',
            'longitude'=>'required',
            'latitude'=>'required',
            'town_id'=>'required',
            'contact_person_name'=>'required',
            'contact_person_phone'=>'required',
            'contact_person_email'=>'required'
        ]); 

        
        $venue = new Venue();
        $venue->name = $request->venue_name;
        $venue->town_id = $request->town_id;
        $venue->latitude = $request->latitude;
        $venue->longitude = $request->longitude;
        $venue->contact_person_name = $request->contact_person_name;
        $venue->contact_person_phone = $request->contact_person_phone;
        $venue->contact_person_email = $request->contact_person_email;
        
        $venue->save();

        //Give message to admin after successfull registration
        $request->session()->flash('status', 'Venue added successfully');
        return redirect($this->redirectPath);
    } 

    

    public function update(Request $request)
    {
        
        $this->validate($request, [
            'id'=>'required',
            'venue_name'=>'required',
            'longitude'=>'required',
            'latitude'=>'required',
            'town_id'=>'required',
            'contact_person_name'=>'required',
            'contact_person_phone'=>'required',
            'contact_person_email'=>'required'
        ]); 
        
        $venue = Venue::find($request->id);
        $venue->town_id = $request->town_id;
        $venue->name = $request->venue_name;
        $venue->latitude = $request->latitude;
        $venue->longitude = $request->longitude;
        $venue->contact_person_name = $request->contact_person_name;
        $venue->contact_person_phone = $request->contact_person_phone;
        $venue->contact_person_email = $request->contact_person_email;
        
        $venue->save();

        //Give message to admin after successfull registration
        $request->session()->flash('status', 'Venue updated successfully');
        return redirect($this->redirectPath);
    } 

    public function destroy(Request $request)
    {
        $id = $request->id;
        $venue = Venue::find($id);
        $venue->delete();
        //Give message to admin after successfull operation
        $request->session()->flash('status', 'Venue deleted successfully');
        return redirect($this->redirectPath);
    }
}
