<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Event;
use App\PaidEventCategory;
use App\EventSponsorMedia;
use App\EventDate;
use App\TicketCategoryDetail;

class TicketsController extends Controller
{
    public function index(){
        $events = Event::select('events.id','events.name','events.description','events.location','events.latitude','events.longitude','ticket_category_details.price','ticket_category_details.no_of_tickets','ticket_category_details.ticket_sale_end_date','events.type','events.slug','event_dates.start','event_dates.end','events.media_url')
                    ->join('event_dates', 'event_dates.event_id', '=', 'events.id')
                    ->leftJoin('ticket_category_details', 'ticket_category_details.event_id', '=', 'events.id')
                    ->where('events.status',1)
                    ->orderBy('id','desc')
                    ->groupBy('events.slug')
                    ->get();

        return view('tickets.home')->with('events',$events);
    }

    public function show($slug){
        $event = Event::select('events.id','events.name','events.description','events.location','events.latitude','events.longitude','ticket_category_details.price','ticket_category_details.no_of_tickets','ticket_category_details.ticket_sale_end_date','events.type','events.status','events.slug','events.created_at','event_dates.start','event_dates.end','events.media_url')
                    ->join('event_dates', 'event_dates.event_id', '=', 'events.id')
                    ->leftJoin('ticket_category_details', 'ticket_category_details.event_id', '=', 'events.id')
                    ->where('slug',$slug)
                    ->orderBy('id','desc')
                    ->first();
        $ticket_categories = TicketCategoryDetail::select('ticket_category_details.price','ticket_category_details.no_of_tickets','ticket_category_details.ticket_sale_end_date','ticket_categories.name','ticket_categories.slug')
                                ->where('ticket_category_details.event_id',$event->id)
                                ->join('ticket_categories', 'ticket_categories.id', '=', 'ticket_category_details.category_id')
                                ->get();
        $data = array(
            'event'=>$event,
            'ticket_categories'=>$ticket_categories
        );
        return view('tickets.ticket_details')->with($data);        

    }

    public function save(Request $request){

    }
}
