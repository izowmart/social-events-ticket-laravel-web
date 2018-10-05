<?php

namespace App\Http\Controllers\AdminPages;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Auth;
use App\Admin;

class AdminsController extends Controller
{
    //admin redirect path
    protected $redirectPath = 'admin/admins';
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
        //get id of curren admin
        $admin_id = Auth::guard('web_admin')->user()->id;

        $admins = Admin::where('id','!=',$admin_id)->orderBy('created_at','asc')->get();
        return view('admin.pages.admins')->with('admins',$admins); 
    }

    public function show($id){
        $admins = Admin::find(Crypt::decrypt($id))->take(1)->get();
        return view('admin.pages.admins')->with('admins',$admins); 

    }

    public function destroy(Request $request){
        $admin = Admin::find($request->id);
        $admin->delete();

        //Give message to admin after successfull operation
        $request->session()->flash('status', 'Admin deleted successfully');
        return redirect($this->redirectPath);

    }
}
