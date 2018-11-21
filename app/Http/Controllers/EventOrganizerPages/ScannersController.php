<?php

namespace App\Http\Controllers\EventOrganizerPages;

use App\Http\Traits\UniversalMethods;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Scanner;
use App\EventScanner;
use App\Event;

class ScannersController extends Controller
{
    //event organizer redirect path
    protected $EventOrganizerVerifiedPaidredirectPath = 'event_organizer/events/verified/paid';
    protected $EventOrganizerVerifiedFreeredirectPath = 'event_organizer/events/verified/free';
    protected $EventOrganizerUnverifiedredirectPath = 'event_organizer/events/unverified';

    /**
     * Display a listing of the scanner.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($event_slug)
    {
        $event = Event::select('id','name','slug')->where('slug',$event_slug)->first();
        $scanners = Scanner::select('scanners.id','scanners.first_name','scanners.last_name','scanners.email','events.status as event_status')
                    ->join('event_scanners', 'event_scanners.scanner_id','=','scanners.id')
                    ->join('events', 'events.id','=','event_scanners.event_id')
                    ->where('event_scanners.event_id', '=', $event->id)
                    ->get();
        $data = array(
            'scanners'=>$scanners,
            'event'=>$event,
        );
        return view('event_organizer.pages.scanners')->with($data);
    }

    /**
     * Show the form for creating a new scanner.
     *
     * @return \Illuminate\Http\Response
     */
    public function showAddForm($event_slug)
    {
        $event = Event::select('id','slug','name')->where('slug',$event_slug)->first();
        return view('event_organizer.pages.add_scanner')->with('event',$event);
    }

    /**
     * Show the form for creating a new scanner.
     *
     * @return \Illuminate\Http\Response
     */
    public function showEditForm($event_slug,$scanner_id)
    {
        $scanner = Scanner::find(Crypt::decrypt($scanner_id));
        $data = array(
            'scanner'=>$scanner,
            'event_slug'=>$event_slug
        );
        return view('event_organizer.pages.edit_scanner')->with($data);
    }

    /**
     * Store a newly created scanner in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'first_name'=>'required',
            'last_name'=>'required',
            'email'=>'required|email|max:255|unique:scanners'
        ]); 

        $event_id = Crypt::decrypt($request->event_id);
        $event_organizer_id = Auth::guard('web_event_organizer')->user()->id;
        $password = UniversalMethods::passwordGenerator();

        $scanner = new Scanner();
        $scanner->event_organizer_id = $event_organizer_id;
        $scanner->first_name = $request->first_name;
        $scanner->last_name = $request->last_name;
        $scanner->email = $request->email;     
        $scanner->password = bcrypt($password);
        $scanner->save();

        $event_scanner = new EventScanner();
        $event_scanner->scanner_id = $scanner->id;
        $event_scanner->event_id = $event_id;
        $event_scanner->save();
                
        $first_name = $request->first_name;
        $email = $request->email;
        $event_name = $request->event_name;

        $data = array('name'=>$request->first_name,'email'=>$request->email,'password'=>$password,'event_name'=>$event_name);
        $beautymail = app()->make(\Snowfire\Beautymail\Beautymail::class);
        $beautymail->send('event_organizer.scanner_mail', $data, function($message) use ($email,$first_name)
        {
            $message
                ->from('noreply@fikaplaces.com','Fika Places')
                ->to($email, $first_name)
                ->subject('Registered as scanner');
        });

        //Give message after successfull operation
        $request->session()->flash('status', 'Scanner added successfully');
        
        return redirect('event_organizer/events/'.$request->event_slug.'/scanners');


    }


    /**
     * Update the specified scanner in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $this->validate($request, [
            'first_name'=>'required',
            'last_name'=>'required',
            'email'=>'required|email|max:255'
        ]); 

        $scanner = Scanner::find($request->scanner_id);
        $scanner->first_name = $request->first_name;
        $scanner->last_name = $request->last_name;
        $scanner->email = $request->email;  
        $scanner->save();

        //Give message after successfull operation
        $request->session()->flash('status', 'Scanner updated successfully');

        return redirect('event_organizer/events/'.$request->event_slug.'/scanners');

    }

    /**
     * Remove the specified scanner from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        $scanner = Scanner::find($request->id);
        $event_scanner = EventScanner::where('scanner_id','=',$request->id);
        $event_scanner->delete();
        $scanner->delete();
        $event_status = $request->status;
        
        //Give message to event organizer after successfull operation
        $request->session()->flash('status', 'Scanner deleted successfully');
        return redirect('event_organizer/events/'.$request->event_slug.'/scanners');
    }
}
