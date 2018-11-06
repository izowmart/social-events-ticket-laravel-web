<?php

namespace App\Http\Controllers\AdminPages;

use Illuminate\Support\Facades\Crypt;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Abuse;

class AbusesController extends Controller
{
    //redirect path
    protected $redirectPath = 'admin/abuses';
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
    public function index($id)
    {
        //$abuse = Abuse::where('post_id',$request->id)->get();
        $abuses = Abuse::select('abuses.type','abuses.created_at','abuses.id','users.first_name','users.last_name','users.email','posts.status')
                ->join('users', 'users.id', '=', 'abuses.user_id')
                ->join('posts', 'posts.id', '=', 'abuses.post_id')
                ->where('abuses.post_id',Crypt::decrypt($id))
                ->get();
        return view('admin.pages.abuses')->with('abuses',$abuses);
        
    }
}
