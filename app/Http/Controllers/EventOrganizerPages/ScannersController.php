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
    public function index()
    {
        //
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
    public function showEditForm()
    {
        //
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
            'phone'=>'required',    
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
        
        $event = find::Event($request->id);
        
        $first_name = $request->first_name;
        $email = $request->email;
        $event_name = $event->name;

        $data = array('name'=>$request->first_name,'email'=>$request->email,'password'=>$password,'event_name'=>$event_name);
        Mail::send('event_organizer.scanner_mail', $data, function($message) use ($email) {
            $message->to($email, 'Added as scanner')->subject
                ('Added as scanner');
            $message->from('xyz@gmail.com','FIKA');
        });

        //Give message after successfull operation
        $request->session()->flash('status', 'Scanner added successfully');

        //redirect event organizer to approproate place;
        $event_status = $event->status;
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
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified scanner from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
