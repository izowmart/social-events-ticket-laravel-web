<?php

namespace App\Http\Controllers\AdminPages;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Advert;
use App\Admin;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

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
        $adverts = Advert::select('adverts.id','adverts.slug','adverts.title','adverts.description','adverts.image_url','adverts.start_date','adverts.end_date','adverts.status','admins.id as admin_id','admins.first_name','admins.last_name','admins.deleted_at as admin_deleted')
                ->join('admins', 'admins.id', '=', 'adverts.admin_id')
                ->get();
        
        return view('admin.pages.adverts')->with('adverts',$adverts); 
    }

    public function showAddForm()
    {
        
        return view('admin.pages.add_advert'); 
    }

    public function showEditForm($slug)
    {
        $advert = Advert::where('slug',$slug)->first();
        return view('admin.pages.edit_advert')->with('advert',$advert); 
                
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

        $image = $request->file('image');

        $extension = $image->getClientOriginalExtension();
        //file name to store
        $fileNameToStore = 'advert'.'_'.time().'.'.$extension;
        //upload image

        $file_path = "/adverts/";

        $success = Storage::disk('uploads')->put($file_path.$fileNameToStore, File::get($image));

//        $path = $request->file('image')->storeAs('public/images/adverts',$fileNameToStore);

        //get id of curren admin
        $admin_id = Auth::guard('web_admin')->user()->id;

        //generate url for the image
        $image_url = url('/storage/images/adverts/'.$fileNameToStore);

        $advert = new Advert();
        $advert->admin_id = $admin_id;
        $advert->title = $request->title;
        $advert->start_date = $request->start;
        $advert->end_date = $request->stop;
        $advert->image_url = $fileNameToStore;
        $advert->description = $request->description;
        
        $advert->save();

        //Give message to admin after successfull registration
        $request->session()->flash('status', 'Advert added successfully');
        return redirect($this->redirectPath);
    } 

    public function update(Request $request)
    {
        
        $this->validate($request, [
            'title'=>'required',
            'description'=>'required',
            'start'=>'required',
            'stop'=>'required'
        ]); 

        // check if image was updated
        if ($request->hasFile('image')) {
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

            //generate url for the image
            $image_url = url('/storage/images/adverts/'.$fileNameToStore);

            //delete the previous image
            $prevoius_image_path = substr(parse_url($request->previous_image_url, PHP_URL_PATH), 1);
            unlink(public_path($prevoius_image_path));
        }
        
        $advert = Advert::find($request->id);
        $advert->title = $request->title;
        $advert->start_date = $request->start;
        $advert->end_date = $request->stop;
        if ($request->hasFile('image')) {
            $advert->image_url = $image_url;
        }
        $advert->description = $request->description;
        
        $advert->save();

        //Give message to admin after successfull registration
        $request->session()->flash('status', 'Advert updated successfully');
        return redirect($this->redirectPath);
    } 

    public function destroy(Request $request)
    {
        $id = $request->id;
        $advert = Advert::find($id);

        //delete both record and its image
        $prevoius_image_path = substr(parse_url($advert->image_url, PHP_URL_PATH), 1);
        unlink(public_path($prevoius_image_path));
        $advert->delete();
        //Give message to admin after successfull operation
        $request->session()->flash('status', 'Advert deleted successfully');
        return redirect($this->redirectPath);
    }

}
