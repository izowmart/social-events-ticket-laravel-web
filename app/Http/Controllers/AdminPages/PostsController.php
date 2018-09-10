<?php

namespace App\Http\Controllers\AdminPages;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Post;
use App\Abuse;
use DB;

class PostsController extends Controller
{
    //redirect path
    protected $redirectPath = 'admin/posts';
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
        $posts = Post::select('posts.media_url','posts.comment','posts.id','posts.status','venues.name as venue_name','users.first_name','users.last_name')
                ->join('venues', 'venues.id', '=', 'posts.venue_id')
                ->join('users', 'users.id', '=', 'posts.user_id')
                ->get();
            
         return view('admin.pages.posts')->with('posts',$posts); 
        
    }

    public function block(Request $request)
    {
        $id = $request->id;
        $post = Post::find($id);
        
        $post->status=2;
        $post->save();
        $request->session()->flash('status', 'Blocked successfully');
        return redirect($this->redirectPath);

        
    }
}
