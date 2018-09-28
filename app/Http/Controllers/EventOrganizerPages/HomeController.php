<?php

namespace App\Http\Controllers\EventOrganizerPages;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use App\Event;

class HomeController extends Controller
{
    public function index(){        
        $event_organizer_id = Auth::guard('web_event_organizer')->user()->id;
        //select all events 
        $all_events = Event::select('id')->where('event_organizer_id',$event_organizer_id);
        //select unverified events that belong to current event organizer
        $unverified_events = Event::select('id')->where('event_organizer_id',$event_organizer_id)->where('status',0);

        $data = array(
            'all_events'=>$all_events,
            'unverified_events'=>$unverified_events
        );

        return view('event_organizer.pages.home');
    }
}
