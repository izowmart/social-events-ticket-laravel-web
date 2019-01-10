<?php

namespace App\Http\Controllers;

use App\Event;
use App\PaymentResponse;
use App\TicketCategoryDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
        $data = $this->fetch_all_events();

        return view('home.index')->with($data);
    }

    public function about()
    {
        return view('home.about');
    }

    public function tickets()
    {
        return view('home.tickets-info');
    }

    public function blog()
    {
        return view('home.blog');
    }

    public function contact()
    {
        return view('home.contact');
    }

    public function selling()
    {
        return view('home.start_selling');
    }

    public function home_user()
    {
        return view('user.home');
    }

    public function home_scanner()
    {
        return view('scanner.home');
    }

    public function showPrivacyPolicy()
    {
        return view('privacy-policy');
    }

    public function showTermsAndConditions()
    {
        return view('terms-and-conditions');
    }

    public function single_event($slug)
    {
        //we fetch the particular event
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
        return view('home.single_event')->with($data);
    }

    public function all_events()
    {
        $data = $this->fetch_all_events();

        return view('home.all_events')->with($data);
    }

    /**
     * @return array
     */
    public function fetch_all_events(): array
    {
//here we fetch verified (non-)featured (non-)paid events
        $free_events = Event::where('type', '=', 1)->get();

        $featured_events = Event::where('type', '=', 2)
            ->where('featured_event', '=', 2)
            ->get();

        $non_featured_events = Event::where('type', '=', 2)
            ->where('featured_event', '=', 1)
            ->get();

        $data = [
            'free_events'         => $free_events,
            'featured_events'     => $featured_events,
            'non_featured_events' => $non_featured_events
        ];

        return $data;
    }


}
