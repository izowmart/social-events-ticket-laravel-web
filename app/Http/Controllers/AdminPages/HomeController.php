<?php

namespace App\Http\Controllers\AdminPages;

use App\Http\Controllers\Controller;
use App\User;
use App\EventOrganizer;
use App\Event;
use App\Abuse;
use App\Post;
use App\Country;
use Carbon\Carbon;
use DB;

class HomeController extends Controller
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
        //we seleect users registered between the last 1 week, then all users
        $new_users = User::where('created_at','>',Carbon::now()->subDays(7)->toDateTimeString())->get();
        $app_users = User::all();

        //select unverified event organizers, then all event organizers
        $pending_event_organizers = EventOrganizer::where('status',0);
        $all_event_organizers = EventOrganizer::all();

        //select unverified event organizers, then all events
        $pending_events = Event::where('status',0);
        $all_events = Event::all();

        //select all abuses from posts, select all posts that has abuse, then all posts
        $abuses = Abuse::all();
        $posts_with_abuse = Abuse::distinct()->select('posts.id')
                                    ->leftJoin('posts','posts.id','=','abuses.post_id')
                                    ->get();
        $posts = Post::all();
        $data = array(
            'app_users'=>$app_users,
            'new_users'=>$new_users,
            'pending_event_organizers'=>$pending_event_organizers,
            'all_event_organizers'=>$all_event_organizers,
            'pending_events'=>$pending_events,
            'all_events'=>$all_events,
            'abuses'=>$abuses,
            'posts'=>$posts,
            'posts_with_abuse'=>$posts_with_abuse
        );

        return view('admin.pages.home')->with($data); 
    }

    public function new_users_chart(){
        $users = User::select('created_at', DB::raw('count(*) as total'))
                        ->where('created_at', '>=', Carbon::now()->subDays(7)->toDateTimeString())
                        ->groupBy(DB::raw('DATE_FORMAT(created_at, "%Y-%M-%D")'))
                        ->orderBy('created_at', 'ASC')->get();
        
        $users_array = array();        
        foreach($users as $user){
            $users_array[] = $user;
        }
        return response()->json($users_array);
        
    }

    public function active_users_chart(){
        $users = Post::select('users.first_name', DB::raw('count(posts.user_id) as total'))
                        ->orderBy('total', 'DESC')
                        ->join('users','users.id','=','posts.user_id')
                        ->groupBy(DB::raw('posts.user_id'))
                        ->take(5)
                        ->get();
        
        $users_array = array();        
        foreach($users as $user){
            $users_array[] = $user;
        }
        return response()->json($users_array);
        
    }

    public function active_venues_chart(){
        $venues = Post::select('venues.name', DB::raw('count(posts.venue_id) as total'))
                        ->orderBy('total', 'DESC')
                        ->join('venues','venues.id','=','posts.venue_id')
                        ->groupBy(DB::raw('posts.venue_id'))
                        ->take(5)
                        ->get();
        
        $venues_array = array();        
        foreach($venues as $user){
            $venues_array[] = $user;
        }
        return response()->json($venues_array);
        
    }

    public function most_users_chart(){
        $venues = Country::select('countries.name', DB::raw('count(users.country_id) as total'))
                        ->orderBy('total', 'DESC')
                        ->join('users','users.country_id','=','countries.id')
                        ->groupBy(DB::raw('countries.name'))
                        ->take(5)
                        ->get();
        
        $venues_array = array();        
        foreach($venues as $user){
            $venues_array[] = $user;
        }
        return response()->json($venues_array);
        
    }
}
