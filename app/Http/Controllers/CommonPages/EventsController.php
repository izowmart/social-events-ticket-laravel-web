<?php

namespace App\Http\Controllers\CommonPages;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Event;

class EventsController extends Controller
{
    //admin redirect path
    protected $VerifiedPaidredirectPath = 'admin/events/verified/paid';
    protected $VerifiedFreeredirectPath = 'admin/events/verified/free';
    protected $UnverifiedredirectPath = 'admin/events/unverified';

    //event organizer redirect path
    protected $EventOrganizerUnverifiedredirectPath = 'event_organizer/events/unverified';

    public function showAddForm(){
        return view('event_organizer.pages.add_event');

    }

    public function store(Request $request){
        $this->validate($request, [
            'name'=>'required',
            'description'=>'required',            
            'location'=>'required',
            'type'=>'required'
        ]); 

        $event_organizer_id = Auth::guard('web_event_organizer')->user()->id;

        $event = new Event();
        $event->name = $request->name;
        $event->event_organizer_id = $event_organizer_id;
        $event->location = $request->location;
        $event->description = $request->description;
        $event->type = $request->type;

        $event->save();

        //Give message after successfull operation
        $request->session()->flash('status', 'Event added successfully');
        return redirect($this->EventOrganizerUnverifiedredirectPath);

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

    function CheckUserType(){
        //we check whether the logged in user is admin or event organizer
        if (Auth::guard('web_admin')->check()) {
            $user = "Admin";
        }else{
            $user = "Event Organizer";

        }
        return $user;
    }
}
