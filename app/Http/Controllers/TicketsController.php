<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Event;
use App\PaidEventCategory;
use App\EventSponsorMedia;
use App\EventDate;
use App\EventPrice;

class TicketsController extends Controller
{
    public function index(){
        $events = Event::select('events.id','events.name','events.description','events.location','events.latitude','events.longitude','events.no_of_tickets','events.type','events.status','events.slug','events.created_at','event_dates.start_date','event_dates.end_date','event_dates.start_time','event_dates.end_time','event_sponsor_media.media_url','paid_event_categories.category','event_prices.price')
                    ->join('event_dates', 'event_dates.event_id', '=', 'events.id')
                    ->join('event_sponsor_media', 'event_sponsor_media.event_id', '=', 'events.id')
                    ->leftJoin('event_prices', 'event_prices.event_id', '=', 'events.id')
                    ->leftJoin('paid_event_categories', 'paid_event_categories.event_id', '=', 'events.id')
                    ->where('events.status',1)
                    ->orderBy('id','desc')
                    ->get();

        return view('tickets.home')->with('events',$events);
    }

    public function show($slug){
        $event = Event::select('events.id','events.name','events.description','events.location','events.latitude','events.longitude','events.no_of_tickets','events.type','events.status','events.slug','events.created_at','event_dates.start_date','event_dates.end_date','event_dates.start_time','event_dates.end_time','event_sponsor_media.media_url','paid_event_categories.category','event_prices.price')
                    ->join('event_dates', 'event_dates.event_id', '=', 'events.id')
                    ->join('event_sponsor_media', 'event_sponsor_media.event_id', '=', 'events.id')
                    ->leftJoin('event_prices', 'event_prices.event_id', '=', 'events.id')
                    ->leftJoin('paid_event_categories', 'paid_event_categories.event_id', '=', 'events.id')
                    ->where('slug',$slug)
                    ->orderBy('id','desc')
                    ->first();
        return view('tickets.ticket_details')->with('event',$event);        

    }
}
