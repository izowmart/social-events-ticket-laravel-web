<?php

namespace App\Http\Controllers\AdminPages;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Advert;
use Illuminate\Support\Facades\Auth;

class AdvertsController extends Controller
{
    //redirect path
    protected $redirectPath = 'admin/adverts';
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
        $adverts = Advert::select('adverts.title','adverts.description','adverts.image_url','adverts.start_date','adverts.end_date','adverts.status','admins.first_name','admins.last_name')
                ->join('admins', 'admins.id', '=', 'adverts.admin_id')
                ->get();
        return view('admin.pages.adverts')->with('adverts',$adverts); 
    }

    public function showAddForm()
    {
        
        return view('admin.pages.add_advert'); 
    }

    public function store(Request $request)
    {
        
        $this->validate($request, [
            'title'=>'required',
            'description'=>'required',
            'image'=>'image',
            'start'=>'required',
            'stop'=>'required'
        ]); 

        // Handle image upload

        $filenameWithExt = $request->file('image')->getClientOriginalName();
        //get just file name
        $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);
        //get just ext
        $extension = $request->file('image')->getClientOriginalExtension();
        //file name to store
        $fileNameToStore = 'advert'.'_'.time().'.'.$extension;
        //upload image
        $path = $request->file('image')->storeAs('public/images/adverts',$fileNameToStore);

        //get id of curren admin
        $admin_id = Auth::guard('web_admin')->user()->id;

        //generate url for the image
        $image_url = url('/storage/images/adverts/'.$fileNameToStore);

        $advert = new Advert();
        $advert->admin_id = $admin_id;
        $advert->title = $request->title;
        $advert->start_date = $request->start;
        $advert->end_date = $request->stop;
        $advert->image_url = $image_url;
        $advert->description = $request->description;
        
        $advert->save();

        //Give message to admin after successfull registration
        $request->session()->flash('status', 'Advert added successfully');
        return redirect($this->redirectPath);
    } 
}
