<?php

namespace App\Http\Controllers\AdminPages;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

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
        // $adverts = Advert::select('adverts.title','adverts.description','adverts.image_url','adverts.start_date','adverts.end_date','adverts.status','admins.first_name','admins.last_name')
        //         ->join('admins', 'admins.id', '=', 'adverts.admin_id')
        //         ->get();
        // return view('admin.pages.adverts')->with('adverts',$adverts); 
        return view('admin.pages.venues'); 
    }

    public function showAddForm()
    {
        
        return view('admin.pages.add_venue'); 
    }

    public function store(Request $request)
    {
        
        // $this->validate($request, [
        //     'title'=>'required',
        //     'description'=>'required',
        //     'image'=>'image',
        //     'start'=>'required',
        //     'stop'=>'required'
        // ]); 

        
        // $advert = new Advert();
        // $advert->admin_id = $admin_id;
        // $advert->title = $request->title;
        // $advert->start_date = $request->start;
        // $advert->end_date = $request->stop;
        // $advert->image_url = $image_url;
        // $advert->description = $request->description;
        
        // $advert->save();

        // //Give message to admin after successfull registration
        // $request->session()->flash('status', 'Advert added successfully');
        // return redirect($this->redirectPath);
    } 
}
