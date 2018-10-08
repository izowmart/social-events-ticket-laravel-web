<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Event;
use App\PaidEventCategory;
use App\EventSponsorMedia;
use App\EventDate;
use App\TicketCategoryDetail;
use App\TicketCustomer;
use App\User;

class TicketsController extends Controller
{
    protected $redirectPath = 'tickets/';
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
    
        $ticket_customer = new TicketCustomer;
        $ticket_customer->first_name = $request->first_name;
        $ticket_customer->last_name = $request->last_name;        
        $ticket_customer->email = $request->email;
        $ticket_customer->phone_number = $request->phone;
        if(User::where('email',$request->email)->first()!==null){
            $ticket_customer->user_id = $user->id;
        }
        $ticket_customer->save();
        $request2 = new Request();
        $request2->setMethod('POST');
        $request2->add(['event_id'=>$request->event_id,'customer_id'=>$ticket_customer->id]);
        
        return redirect()->route('encryption_url',['request'=>$request2]);


    }
}
