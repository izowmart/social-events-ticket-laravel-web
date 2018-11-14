<?php

namespace App\Http\Controllers\AdminPages;

use Illuminate\Support\Facades\Crypt;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
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
        $venues = Venue::select('venues.id','venues.slug','venues.name as venue_name','venues.contact_person_name','venues.contact_person_phone','venues.contact_person_email','venues.latitude','venues.longitude','venues.venue_image','venues.featured_status','towns.id as town_id','towns.name as town_name')
                ->join('towns', 'towns.id', '=', 'venues.town_id')
                ->get();
        return view('admin.pages.venues')->with('venues',$venues); 
    }

    public function show($id)
    {
        $venues = Venue::where('venues.id',Crypt::decrypt($id))
                ->select('venues.id','venues.slug','venues.name as venue_name','venues.contact_person_name','venues.contact_person_phone','venues.contact_person_email','venues.latitude','venues.longitude','towns.id as town_id','towns.name as town_name')
                ->join('towns', 'towns.id', '=', 'venues.town_id')
                ->get();
        return view('admin.pages.venues')->with('venues',$venues); 
    }

    public function showAddForm()
    {
        $towns = Town::orderBy('name','asc')->get();
        return view('admin.pages.add_venue')->with('towns',$towns); 
    } 

    public function showEditForm($slug)
    {
        $venue = Venue::select('venues.id as venue_id','venues.slug','venues.name as venue_name','venues.contact_person_name','venues.contact_person_phone','venues.contact_person_email','towns.id as town_id','towns.name as town_name')
                ->join('towns', 'towns.id', '=', 'venues.town_id')
                ->where('venues.slug',$slug)
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
                'contact_person_email'=>'required',
            ]); 
        $featured_status = $request->featured;
        $venue = new Venue();
        $venue->name = $request->venue_name;
        $venue->town_id = $request->town_id;
        $venue->latitude = $request->latitude;
        $venue->longitude = $request->longitude;
        $venue->contact_person_name = $request->contact_person_name;
        $venue->contact_person_phone = $request->contact_person_phone;
        $venue->contact_person_email = $request->contact_person_email;
        if(empty($featured_status)) {
            $featured_status = 0;
        }
        $venue->featured_status = $featured_status;
        $venue->featured_description = $request->featured_description;
        if ($request->hasFile('venue_image')) {
            $this->validate($request,[
                'venue_image' => 'image: jpg,png,jpeg',
            ]);
            $name = $request->file('venue_image')->getClientOriginalName();
            $filename = time().'_'. $name;
            $request->file('venue_image')->move('venue_images', $filename);
            $venue->venue_image = $filename;

        }
        else
        {
            $filename = 'pin.jpeg';
            $venue->venue_image = $filename;
        }
        
        $venue->save();

        //Give message to admin after successful registration
        $request->session()->flash('status', 'Venue added successfully');
        return redirect($this->redirectPath);
    } 

    

    public function update(Request $request)
    {
        
        $this->validate($request, [
            'venue_name'=>'required',
            'longitude'=>'required',
            'latitude'=>'required',
            'town_id'=>'required',
            'contact_person_name'=>'required',
            'contact_person_phone'=>'required',
            'contact_person_email'=>'required',
        ]); 



        $venue = Venue::find($request->id);
        $venue->town_id = $request->town_id;
        $venue->name = $request->venue_name;
        $venue->latitude = $request->latitude;
        $venue->longitude = $request->longitude;
        $venue->contact_person_name = $request->contact_person_name;
        $venue->contact_person_phone = $request->contact_person_phone;
        $venue->contact_person_email = $request->contact_person_email;
        // $venue->venue_image = $filename;

        if ($request->hasFile('venue_image')) {
            $this->validate($request,[
                'venue_image' => 'image: jpg,png,jpeg',
            ]);
            $name = $request->file('venue_image')->getClientOriginalName();
            $filename = time().'_'. $name;
            $request->file('venue_image')->move('venue_images', $filename);
            $venue->venue_image = $filename;

        }
        
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

    public function featureVenue(Request $request)
    {
        $venue = Venue::find($request->id);
        $venue->featured_status = !$venue->featured_status;
        $venue->featured_description = $request->featured_description;
        $venue->save();
        return redirect($this->redirectPath);
    }

    public function unfeatureVenue($slug)
    {
        $venue = Venue::where('slug', '=', $slug)->first();
        $venue->featured_status = !$venue->featured_status;
        $venue->save();
        return redirect($this->redirectPath);
    }
}
