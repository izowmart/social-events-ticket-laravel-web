<?php

namespace App\Http\Controllers\EventOrganizerPages;

use Illuminate\Http\Request;
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
    public function index(Request $request)
    {
        $scanners = Scanner::select('scanners.id','scanners.first_name','scanners.last_name','scanners.email','events.status as event_status')
                    ->join('event_scanners', 'event_scanners.scanner_id','=','scanners.id')
                    ->join('events', 'events.id','=','event_scanners.event_id')
                    ->where('event_scanners.event_id', '=', $request->id)
                    ->get();
        $data = array(
            'scanners'=>$scanners,
            'event_id'=>$request->id,
            'event_name'=>$request->event_name
        );
        return view('event_organizer.pages.scanners')->with($data);
    }

    /**
     * Show the form for creating a new scanner.
     *
     * @return \Illuminate\Http\Response
     */
    public function showAddForm(Request $request)
    {
        return view('event_organizer.pages.add_scanner')->with('event_id',$request->id);
    }

    /**
     * Show the form for creating a new scanner.
     *
     * @return \Illuminate\Http\Response
     */
    public function showEditForm(Request $request)
    {
        $scanner = Scanner::find($request->id);
        $data = array(
            'scanner'=>$scanner,
            'event_status'=>$request->event_status
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
        //REMEMBER TO OPTIMIZE THIS FUNCTION!!!
        $this->validate($request, [
            'first_name'=>'required',
            'last_name'=>'required',
            'email'=>'required|email|max:255|unique:scanners'
        ]); 

        $event_id = $request->event_id;
        $event_organizer_id = Auth::guard('web_event_organizer')->user()->id;
        $password = substr(md5(microtime()),rand(0,26),8);

        $scanner = new Scanner();
        $scanner->event_organizer_id = $event_organizer_id;
        $scanner->first_name = $request->first_name;
        $scanner->last_name = $request->last_name;
        $scanner->email = $request->email;     
        $scanner->password = bcrypt($password);
        $scanner->save();

        $scanner_details = Scanner::where('email',$request->email)->first();
        $scanner_id = $scanner_details->id;

        $event_scanner = new EventScanner();
        $event_scanner->scanner_id = $scanner_id;
        $event_scanner->event_id = $event_id;
        $event_scanner->save();
                
        $first_name = $request->first_name;
        $email = $request->email;
        $event_name = $request->event_name;

        $data = array('name'=>$request->first_name,'email'=>$request->email,'password'=>$password,'event_name'=>$event_name);
        Mail::send('event_organizer.scanner_mail', $data, function($message) use ($email) {
            $message->to($email)->subject
                ('Added as scanner');
            $message->from('xyz@gmail.com','FIKA');
        });

        //Give message after successfull operation
        $request->session()->flash('status', 'Scanner added successfully');

        //redirect event organizer to approproate place;
        $event_status = $request->status;
        if($request->type==1 && $event_status!=0){
            //if free event and not unverified
            return redirect($this->EventOrganizerVerifiedFreeredirectPath);

        }else if($request->type==2 && $event_status!=0){
            //if paid event and not unverified
            return redirect($this->EventOrganizerVerifiedPaidredirectPath);

        }else{
            //if its unverified
            return redirect($this->EventOrganizerUnverifiedredirectPath);

        }

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

        //redirect event organizer to approproate place;
        $event_status = $request->event_status;
        if($request->type==1 && $event_status!=0){
            //if free event and not unverified
            return redirect($this->EventOrganizerVerifiedFreeredirectPath);

        }else if($request->type==2 && $event_status!=0){
            //if paid event and not unverified
            return redirect($this->EventOrganizerVerifiedPaidredirectPath);

        }else{
            //if its unverified
            return redirect($this->EventOrganizerUnverifiedredirectPath);

        }

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
        if($request->type==1 && $event_status!=0){
            //if free event and not unverified
            $redirect = redirect($this->EventOrganizerVerifiedFreeredirectPath);

        }else if($request->type==2 && $event_status!=0){
            //if paid event and not unverified
            $redirect = redirect($this->EventOrganizerVerifiedPaidredirectPath);

        }else{
            //if its unverified
            $redirect = redirect($this->EventOrganizerUnverifiedredirectPath);

        }
        //Give message to event organizer after successfull operation
        $request->session()->flash('status', 'Scanner deleted successfully');
        return $redirect;
    }
}
