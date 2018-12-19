<?php

namespace App\Http\Controllers\AdminPages;

use Illuminate\Support\Facades\Crypt;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Abuse;
use App\Post;
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
        $posts = Post::select('posts.media_url','posts.comment','posts.id','posts.status','venues.id as venue_id','venues.name as venue_name','users.id as user_id','users.first_name','users.last_name','posts.media_type')
                ->join('venues', 'venues.id', '=', 'posts.venue_id')
                ->join('users', 'users.id', '=', 'posts.user_id')
            ->leftJoin('shares','shares.original_post_id','=','posts.id') //only fetch non shared posts
            ->whereNull('shares.original_post_id')
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
