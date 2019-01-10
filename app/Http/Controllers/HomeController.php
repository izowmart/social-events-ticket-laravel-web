<?php

namespace App\Http\Controllers;

use App\Event;
use App\PaymentResponse;
use App\TicketCategoryDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Snowfire\Beautymail\Beautymail;

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
        $data = $this->fetch_all_events(false);

        return view('home.index')->with($data);
    }

    /**
     * @param null $all
     *
     * @return array
     */
    public function fetch_all_events($all = false): array
    {
        $events=[];
        $free_events=[];
        $featured_events =[];
        $non_featured_events =[];


        if ($all){
            $events = Event::where('status', 1)
                ->whereHas('event_dates', function ($query) {
                    $query->where('event_dates.start', '>=', now());
                })
                ->paginate(5);
        }else {

            //here we fetch verified (non-)featured (non-)paid events
            $free_events =  Event::where('type', '=', 1)
                ->whereHas('event_dates', function ($query) {
                    $query->where('event_dates.start', '>=', now());
                })
                ->where('status', 1)
                ->take(6)
                ->get();

            $featured_events = Event::where('type', '=', 2)
                ->where('featured_event', '=', 2)
                ->whereHas('event_dates', function ($query) {
                    $query->where('event_dates.start', '>=', now());
                })
                ->where('status', 1)
                ->take(6)
                ->get();

            $non_featured_events = Event::where('type', '=', 2)
                ->where('featured_event', '=', 1)
                ->whereHas('event_dates', function ($query) {
                    $query->where('event_dates.start', '>=', now());
                })
                ->where('status', 1)
                ->take(6)
                ->get();
        }

        $data = [
            'events'            => $events,
            'free_events'         => $free_events,
            'featured_events'     => $featured_events,
            'non_featured_events' => $non_featured_events
        ];

        return $data;
    }

    public function about()
    {
        return view('home.about');
    }

    public function ticket_info()
    {
//        $data = $this->fetch_all_events(true);
        return view('home.ticket_information');
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
        $event = Event::select('events.id', 'events.name', 'events.description', 'events.location', 'events.latitude',
            'events.longitude', 'ticket_category_details.price', 'ticket_category_details.no_of_tickets', 'events.type',
            'events.status', 'events.slug', 'events.created_at', 'event_dates.start', 'event_dates.end',
            'events.media_url', 'events.ticket_sale_end_date')
            ->join('event_dates', 'event_dates.event_id', '=', 'events.id')
            ->leftJoin('ticket_category_details', 'ticket_category_details.event_id', '=', 'events.id')
            ->where('slug', $slug)
            ->orderBy('id', 'desc')
            ->first();
        if ($event == null) {
            return abort(404);
        }
        $ticket_categories = TicketCategoryDetail::select('ticket_category_details.price',
            'ticket_category_details.category_id', 'ticket_category_details.no_of_tickets', 'ticket_categories.name',
            'ticket_categories.slug')
            ->where('ticket_category_details.event_id', $event->id)
            ->join('ticket_categories', 'ticket_categories.id', '=', 'ticket_category_details.category_id')
            ->get();
        $data = [
            'event'             => $event,
            'ticket_categories' => $ticket_categories
        ];

        return view('home.single_event')->with($data);
    }

    public function all_events()
    {
        $data = $this->fetch_all_events(true);

        return view('home.tickets-info')->with($data);
    }

    public function contact_submission(Request $request)
    {
        $this->validate($request,[
            'name_contact' => 'required|string',
            'lastname_contact' => 'required|string',
            'email_contact' => 'required|email',
            'message_contact' => 'required|string',
            'verify_contact'    => 'required|integer|size:4'
        ],[
            'name_contact.required' => 'Enter you First Name',
            'name_contact.string'   => ' Invalid First Name',
            'lastname_contact.required' => 'Enter you Last Name',
            'lastname_contact.string'   => ' Invalid Last Name',
            'email_contact.required' => 'Enter you Email Address',
            'email_contact.email'   => ' Invalid Email',
            'message_contact.required' => 'Enter the message',
            'message_contact.string'   => 'Invalid Message',
            'verify_contact.required' => 'Answer the human verification question.',
            'verify_contact.integer'   => 'Incorrect answer to the human verification question.',
            'verify_contact.size'   => 'Incorrect answer to the human verification question.',
        ]);


        $data = [
            'name' => $request->name_contact." ".$request->lastname_contact,
            'email' => $request->email_contact,
            'the_body' => $request->message_contact
        ];

//        dd($data);

        $beautymail = app()->make(Beautymail::class);
        $beautymail->send('home.emails.contact', $data, function($message) use ($data)
        {
            $message
                ->from($data['email'],$data['name'])
                ->to('info@fikaplaces.com', 'John Smith')
                ->subject('New Customer Enquiry!');
        });

        return redirect()->back()->with('status', "Your Request was submitted successfully");

    }


}
