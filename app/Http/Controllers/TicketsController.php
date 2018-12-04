<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Event;
use App\PaidEventCategory;
use App\EventSponsorMedia;
use App\Http\Traits\UniversalMethods;
use App\EventDate;
use App\TicketCategoryDetail;
use App\TicketCustomer;
use App\User;

class TicketsController extends Controller
{
    protected $redirectPath = 'tickets/';
    public function index(){
        $today = Carbon::now()->toDateTimeString();
        $events = Event::select('events.id','events.name','events.description','events.location','events.latitude','events.longitude','ticket_category_details.price','ticket_category_details.no_of_tickets','events.type','events.slug','event_dates.start','event_dates.end','events.media_url','events.ticket_sale_end_date')
                    ->join('event_dates', 'event_dates.event_id', '=', 'events.id')
                    ->leftJoin('ticket_category_details', 'ticket_category_details.event_id', '=', 'events.id')
                    ->where('events.status',1)
            ->whereDate('event_dates.end','>=',$today) //NB:: only show current or upcoming events
                    ->orderBy('id','desc')
                    ->groupBy('events.slug')
                    ->get();

        return view('tickets.home')->with('events',$events);
    }

    public function show($slug){
        $event = Event::select('events.id','events.name','events.description','events.location','events.latitude','events.longitude','ticket_category_details.price','ticket_category_details.no_of_tickets','events.type','events.status','events.slug','events.created_at','event_dates.start','event_dates.end','events.media_url','events.ticket_sale_end_date')
                    ->join('event_dates', 'event_dates.event_id', '=', 'events.id')
                    ->leftJoin('ticket_category_details', 'ticket_category_details.event_id', '=', 'events.id')
                    ->where('slug',$slug)
                    ->orderBy('id','desc')
                    ->first();
        if($event==null){
            return abort(404);
        }
        $ticket_categories = TicketCategoryDetail::select('ticket_category_details.price','ticket_category_details.category_id','ticket_category_details.no_of_tickets','ticket_categories.name','ticket_categories.slug')
                                ->where('ticket_category_details.event_id',$event->id)
                                ->join('ticket_categories', 'ticket_categories.id', '=', 'ticket_category_details.category_id')
                                ->get();                   
        $data = array(
            'event'=>$event,
            'ticket_categories'=>$ticket_categories
        );
        return view('tickets.ticket_details')->with($data);        

    }

    public function displayTickets()
    {
        return view('tickets.display-tickets');
    }
}
