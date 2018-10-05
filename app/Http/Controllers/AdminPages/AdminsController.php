<?php

namespace App\Http\Controllers\AdminPages;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Crypt;
use App\Admin;

class AdminsController extends Controller
{
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
        $admins = Admin::orderBy('created_at','asc')->get();
        return view('admin.pages.admins')->with('admins',$admins); 
    }

    public function show($id){
        $admins = Admin::find(Crypt::decrypt($id))->take(1)->get();
        return view('admin.pages.admins')->with('admins',$admins); 

    }
}
