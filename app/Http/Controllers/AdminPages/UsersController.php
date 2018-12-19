<?php

namespace App\Http\Controllers\AdminPages;

use Illuminate\Support\Facades\Crypt;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Country;
use App\User;

class UsersController extends Controller
{
    //redirect path
    protected $redirectPath = 'admin/users';
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
        $users = user::select('users.first_name','users.last_name','users.username','users.phone_number','users.status','users.gender','users.email','users.year_of_birth','users.app_version_code','countries.name as country_name')
                ->leftJoin('countries', 'countries.id', '=', 'users.country_id') //show all users even those who have not indicated from which country they come from
                ->get();
        return view('admin.pages.users')->with('users',$users); 
    }

    public function show($id)
    {
        $users = user::where('users.id',Crypt::decrypt($id))
                ->select('users.first_name','users.last_name','users.username','users.phone_number','users.status','users.gender','users.email','users.year_of_birth','users.app_version_code','countries.name')
                ->leftJoin('countries', 'countries.id', '=', 'users.country_id') //show all users even those who have not indicated from which country they come from
                ->get();
        return view('admin.pages.users')->with('users',$users); 
    }

    public function showAddForm()
    {
        
        return view('admin.pages.add_venue'); 
    }
}
