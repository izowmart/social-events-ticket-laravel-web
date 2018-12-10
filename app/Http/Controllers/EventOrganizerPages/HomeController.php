<?php

namespace App\Http\Controllers\EventOrganizerPages;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use App\Event;
use Carbon\Carbon;

class HomeController extends Controller
{
    public function index()
    {
        $event_organizer = Auth::guard('web_event_organizer')->user();

        $scanners = $event_organizer->scanners;

        $event_organizer_id = $event_organizer->id;
        //select all events 
        $all_events = Event::select('id')->where('event_organizer_id', $event_organizer_id)->get();
        //select unverified events that belong to current event organizer
        $unverified_events = Event::select('id')->where([
            ['event_organizer_id', $event_organizer_id],
            ['status', 0]
        ])->get();
        //select upcoming events
        $upcoming_events = Event::select('events.id', 'event_dates.start')->where('event_organizer_id',
            $event_organizer_id)->where('status', 1)
            ->join('event_dates', 'event_dates.event_id', '=', 'events.id')
            ->where('event_dates.start', '>', Carbon::now()->toDateTimeString())
            ->groupBy('events.id')
            ->get();


        $data = [
            'all_events' => $all_events,
            'unverified_events' => $unverified_events,
            'upcoming_events' => $upcoming_events,
            'scanners' => $scanners
        ];

        return view('event_organizer.pages.home')->with($data);
    }
}
