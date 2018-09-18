<?php

namespace App\Http\Controllers\CommonPages;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Event;
use App\PaidEventCategory;
use App\EventSponsorMedia;
use App\EventDate;
use App\EventPrice;
use App\Scanner;
use App\EventScanner;

class EventsController extends Controller
{
    //admin redirect path
    protected $VerifiedPaidredirectPath = 'admin/events/verified/paid';
    protected $VerifiedFreeredirectPath = 'admin/events/verified/free';
    protected $UnverifiedredirectPath = 'admin/events/unverified';

    //event organizer redirect path
    protected $EventOrganizerVerifiedPaidredirectPath = 'event_organizer/events/verified/paid';
    protected $EventOrganizerVerifiedFreeredirectPath = 'event_organizer/events/verified/free';
    protected $EventOrganizerUnverifiedredirectPath = 'event_organizer/events/unverified';

    public function showAddForm(){
        return view('event_organizer.pages.add_event');

    }

    public function showEditForm(Request $request){
        $event = Event::find($request->id);
        return view('event_organizer.pages.edit_event')->with('event',$event);

    }

    public function store(Request $request){
        $this->validate($request, [
            'name'=>'required',
            'description'=>'required',            
            'location'=>'required',
            'type'=>'required',
            'image'=>'image',
            'start_date'=>'required',
            'start_time'=>'required',
            'stop_date'=>'required',
            'stop_time'=>'required'
        ]); 

        //if its paid event, the amount and category is required
        if($request->type==2){
            $this->validate($request, [
            'amount'=>'required',
            'tickets'=>'required',
            'category'=>'required'
        ]); 
        }

        //join date and time
        $join_start = $request->start_date.' '.$request->start_time.'00';
        $join_stop = $request->stop_date.' '.$request->stop_time.'00';

        $event_start = date("Y-m-d H:i:s", strtotime($join_start));
        $event_stop = date("Y-m-d H:i:s", strtotime($join_stop));

        // Handle image upload

        $filenameWithExt = $request->file('image')->getClientOriginalName();
        //get just file name
        $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);
        //get just ext
        $extension = $request->file('image')->getClientOriginalExtension();
        //file name to store
        $fileNameToStore = 'event'.'_'.time().'.'.$extension;
        //upload image
        $path = $request->file('image')->storeAs('public/images/events',$fileNameToStore);

        $event_organizer_id = Auth::guard('web_event_organizer')->user()->id;

        $event = new Event();
        $event->name = $request->name;
        $event->event_organizer_id = $event_organizer_id;
        $event->location = $request->location;
        $event->longitude = $request->longitude;
        $event->latitude = $request->latitude;
        $event->no_of_tickets = $request->tickets;
        $event->description = $request->description;
        $event->type = $request->type;
        $event->save();

        $event_id = $event->id;

        $event_date = new EventDate();
        $event_date->event_id = $event_id;
        $event_date->start_date_time = $event_start;
        $event_date->end_date_time = $event_stop;
        $event_date->save();

        $event_sponsor_media = new EventSponsorMedia();
        $event_sponsor_media->event_id = $event_id;
        $event_sponsor_media->media_url = $fileNameToStore;
        $event_sponsor_media->save();

        $event_price = new EventPrice();
        $event_price->event_id = $event_id;
        if($request->type==2){
            $event_price->price = $request->amount;
        }
        $event_price->save();

        if($request->type==2){
            $paid_event_category = new PaidEventCategory();
            $paid_event_category->event_id = $event_id;
            $paid_event_category->category = $request->category;
            $paid_event_category->save();
            
        }      


        //Give message after successfull operation
        $request->session()->flash('status', 'Event added successfully');
        return redirect($this->EventOrganizerUnverifiedredirectPath);

    }

    public function update(Request $request){
        $this->validate($request, [
            'name'=>'required',
            'description'=>'required',            
            'location'=>'required',
            'type'=>'required'
        ]); 

        $event_organizer_id = Auth::guard('web_event_organizer')->user()->id;

        $event = Event::find($request->id);
        $event->name = $request->name;
        $event->event_organizer_id = $event_organizer_id;
        $event->location = $request->location;
        $event->description = $request->description;
        $event->type = $request->type;

        $event->save();

        //Give message after successfull operation
        $request->session()->flash('status', 'Event updated successfully');

        //redirect event organizer to approproate place
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

    public function Unverifiedindex(){
        $user = $this->CheckUserType();
        if($user=="Admin"){
            $events = Event::select('events.id','events.name','events.description','events.location','events.type','events.status','events.created_at','event_organizers.first_name','event_organizers.last_name')
                    ->join('event_organizers', 'event_organizers.id', '=', 'events.event_organizer_id')
                    ->where('events.status',0)
                    ->get();
        }else{
            //we will search for events that belong to current evenet organizer only
            $event_organizer_id = Auth::guard('web_event_organizer')->user()->id;
            $events = Event::select('events.id','events.name','events.description','events.location','events.type','events.status','events.created_at','event_organizers.first_name','event_organizers.last_name')
                    ->where('events.status',0)
                    ->join('event_organizers', 'event_organizers.id', '=', 'events.event_organizer_id')
                    ->where('events.event_organizer_id',$event_organizer_id)                    
                    ->get();

        }
        $data=array(
           'type'=>'unverified',
           'events'=>$events
        );
        return view('common_pages.events')->with($data);

    }

    public function VerifiedPaidindex(){
        $user = $this->CheckUserType();
        if($user=="Admin"){
            $events = Event::select('events.id','events.name','events.description','events.location','events.type','events.status','events.created_at','event_organizers.first_name','event_organizers.last_name')
                ->where('events.type',2)
                ->join('event_organizers', 'event_organizers.id', '=', 'events.event_organizer_id')
                ->whereIn('events.status',[1,2])
                ->get();
        }else{
            //we will search for events that belong to current evenet organizer only
            $event_organizer_id = Auth::guard('web_event_organizer')->user()->id;
            $events = Event::select('events.id','events.name','events.description','events.location','events.type','events.status','events.created_at','event_organizers.first_name','event_organizers.last_name')
                ->where('events.type',2)
                ->join('event_organizers', 'event_organizers.id', '=', 'events.event_organizer_id')
                ->whereIn('events.status',[1,2])
                ->where('events.event_organizer_id',$event_organizer_id)  
                ->get();

        }
        
        $data=array(
           'type'=>'verified paid',
           'events'=>$events
        );
        return view('common_pages.events')->with($data);        
    }

    public function VerifiedFreeindex(){
        $user = $this->CheckUserType();
        if($user=="Admin"){
            $events = Event::select('events.id','events.name','events.description','events.location','events.type','events.status','events.created_at','event_organizers.first_name','event_organizers.last_name')
                    ->where('events.type',1)
                    ->join('event_organizers', 'event_organizers.id', '=', 'events.event_organizer_id')
                    ->whereIn('events.status',[1,2])
                    ->get();
        }else{
            //we will search for events that belong to current evenet organizer only
            $event_organizer_id = Auth::guard('web_event_organizer')->user()->id;
            $events = Event::select('events.id','events.name','events.description','events.location','events.type','events.status','events.created_at','event_organizers.first_name','event_organizers.last_name')
                    ->where('events.type',1)
                    ->join('event_organizers', 'event_organizers.id', '=', 'events.event_organizer_id')
                    ->whereIn('events.status',[1,2])
                    ->where('events.event_organizer_id',$event_organizer_id) 
                    ->get();

        }
        $data=array(
           'type'=>'verified free',
           'events'=>$events
        );
        return view('common_pages.events')->with($data);  
        
    }

    public function verify(Request $request)
    {
        $id = $request->id;
        $type = $request->type;
        $event = Event::find($id);
        
        $event->status=1;
        $event->save();
        $request->session()->flash('status', 'Verified successfully');
           
        return redirect($this->UnverifiedredirectPath);
        
        
        
    }

    public function activate(Request $request)
    {        
        $id = $request->id;
        $type = $request->type;
        $event = Event::find($id);
        
        $event->status=1;
        $event->save();
        $request->session()->flash('status', 'Activated successfully');

        //check where the request came from and redirect back to that page
        if ($type=="verified free") {            
            return redirect($this->VerifiedFreeredirectPath);
        } else {            
            return redirect($this->VerifiedPaidredirectPath);
        }
        
    }

    public function deactivate(Request $request)
    {        
        $id = $request->id;
        $type = $request->type;
        $event = Event::find($id);
        
        $event->status=2;
        $event->save();
        $request->session()->flash('status', 'Deactivated successfully');

        //check where the request came from and redirect back to that page
        if ($type=="verified free") {            
            return redirect($this->VerifiedFreeredirectPath);
        } else {            
            return redirect($this->VerifiedPaidredirectPath);
        }
        
    }

    public function destroy(Request $request){
        $event = Event::find($request->id);
        $event_status = $event->status;
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

        //first delete scanners if they exist
        if($event->scanners->count()>0){
            $event_scanners = EventScanner::where('event_id',$request->id)->get();
            foreach($event_scanners as $event_scanner){                
                $scanner = Scanner::find($event_scanner->scanner_id);                
                $scanner->delete();
                $event_scanner->delete();
            }

        }

        //delete event_dates table
        $event_dates = EventDate::where('event_id',$request->id);
        $event_dates->delete();

        //delete event_sponsor_media table
        $event_sponsor_media = EventSponsorMedia::where('event_id',$request->id);
        $event_sponsor_media->delete();

        //delete event_sponsor_media table
        $event_sponsor_media = EventSponsorMedia::where('event_id',$request->id);
        $event_sponsor_media->delete();

        //check if its paid event
        if($event->type==2){
            //delete event_prices table
            $event_price = EventPrice::where('event_id',$request->id);
            $event_dates->delete();

            //delete paid_event_categories table
            $paid_event_category = PaidEventCategory::where('event_id',$request->id);
            $paid_event_category->delete();
        }

        //finally delete events table
        $event->delete();
        //Give message to event organizer after successfull operation
        $request->session()->flash('status', 'Event deleted successfully');
        return $redirect;

    } 

    public function CheckUserType(){
        //we check whether the logged in user is admin or event organizer
        if (Auth::guard('web_admin')->check()) {
            $user = "Admin";
        }else{
            $user = "Event Organizer";

        }
        return $user;
    }
}
