<?php

namespace App\Http\Controllers\CommonPages;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Event;
use App\EventSponsorMedia;
use App\EventDate;
use App\EventPrice;
use App\Scanner;
use App\EventScanner;
use App\TicketCategory;
use App\TicketCategoryDetail;

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
        $ticket_categories = TicketCategory::all();
        return view('event_organizer.pages.add_event')->with('ticket_categories',$ticket_categories);

    }

    public function showEditForm($slug){
        $ticket_categories = TicketCategory::all();
        
        $event = Event::select('events.id','events.name','events.slug','events.description','events.location','events.latitude','events.longitude','events.type','events.slug','event_dates.start','event_dates.end','event_sponsor_media.media_url')
                    ->join('event_dates', 'event_dates.event_id', '=', 'events.id')
                    ->join('event_sponsor_media', 'event_sponsor_media.event_id', '=', 'events.id')
                    ->where('events.slug',$slug)
                    ->orderBy('id','desc')
                    ->first();
        $ticket_category_details = TicketCategoryDetail::select('ticket_category_details.price','ticket_category_details.no_of_tickets','ticket_category_details.ticket_sale_end_date','ticket_category_details.category_id','ticket_categories.slug','ticket_categories.name')
                                    ->join('ticket_categories', 'ticket_categories.id', '=', 'ticket_category_details.category_id')
                                    ->where('event_id', $event->id)
                                    ->get();

        $data = array(
            'event'=>$event,
            'ticket_categories'=>$ticket_categories,
            'ticket_category_details'=>$ticket_category_details
        );   

        return view('event_organizer.pages.edit_event')->with($data);

    }

    public function store(Request $request){
                        
        $this->validate($request, [
            'name'=>'required',
            'description'=>'required',            
            'location'=>'required',
            'type'=>'required',
            'image'=>'image',
        ]); 

        //check if its paid event and validate required fields
        // if($request->type==2){
        //     //get selected categories
        //     $ticket_category = TicketCategory::find($single_category);
        //     $ticket_slug = $ticket_category->slug;
        // }

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
        $event->description = $request->description;
        $event->type = $request->type;
        $event->save();

        $event_id = $event->id;

        foreach ($request->dates as $date) {
            //echo 'start: '.$date['start']. 'stop: '.$date['stop'].'<br>';
            $event_date = new EventDate();
            $event_date->event_id = $event_id;
            $event_date->start = date('Y-m-d H:i:s',strtotime($date['start']));
            $event_date->end = date('Y-m-d H:i:s',strtotime($date['stop']));
            $event_date->save();
        }

        $event_sponsor_media = new EventSponsorMedia();
        $event_sponsor_media->event_id = $event_id;
        $event_sponsor_media->media_url = $fileNameToStore;
        $event_sponsor_media->save();

        //insert price and category if it's a paid event
        if($request->type==2){

            foreach($request->category as $single_category){
                //get the slug of category from db
                $ticket_category = TicketCategory::find($single_category);
                $ticket_slug = $ticket_category->slug;
                //creat names for inputs
                $amount = $ticket_slug.'_amount';
                $tickets = $ticket_slug.'_tickets';
                $ticket_sale_end_date = $ticket_slug.'_ticket_sale_end_date';

                $ticket_category_details = new TicketCategoryDetail;
                $ticket_category_details->event_id = $event_id;
                $ticket_category_details->category_id = $ticket_category->id;
                $ticket_category_details->price = $request->$amount;
                $ticket_category_details->no_of_tickets = $request->$tickets;
                $ticket_category_details->ticket_sale_end_date = date('Y-m-d H:i:s',strtotime($request->$ticket_sale_end_date));
                $ticket_category_details->save();
                
            }
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
            'type'=>'required',
            'start'=>'required',
            'stop'=>'required'
        ]); 

        //check if its paid event and validate required fields
        // if($request->type==2){
        //     //get selected categories
        //     $ticket_category = TicketCategory::find($single_category);
        //     $ticket_slug = $ticket_category->slug;
        // }

        // check if image was updated
        if ($request->hasFile('image')) {
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

            //delete the previous image
            unlink(public_path('storage/images/events/'.$request->previous_image_url));
        }

        $event_organizer_id = Auth::guard('web_event_organizer')->user()->id;

        $event = Event::find($request->id);

        //get previous event type before updating event
        $prev_event_type = $event->type;

        $event->name = $request->name;
        $event->event_organizer_id = $event_organizer_id;
        $event->location = $request->location;
        $event->longitude = $request->longitude;
        $event->latitude = $request->latitude;
        $event->description = $request->description;
        $event->type = $request->type;

        $event->save();

        $event_id = $event->id;

        $event_date = EventDate::where('event_id',$event_id)->first();
        $event_date->start = date('Y-m-d H:i:s',strtotime($request->start));
        $event_date->end = date('Y-m-d H:i:s',strtotime($request->stop));
        $event_date->save();

        if ($request->hasFile('image')) {
            $event_sponsor_media = EventSponsorMedia::where('event_id',$event_id)->first();
            $event_sponsor_media->media_url = $fileNameToStore;
            $event_sponsor_media->save();
        }

        //check if it was a paid event and changed to free. If so we will delete its record from ticket_category_details table
        if($request->type==1 && $prev_event_type==2){
            $ticket_category_details = TicketCategoryDetail::where('event_id',$request->id);
            $ticket_category_details->delete();
            
        }

        //update price and category if it's a paid event
        if($request->type==2){
            //delete all its records from ticket_category_details table and insert the new ones
            $ticket_category_details = TicketCategoryDetail::where('event_id',$request->id);
            $ticket_category_details->delete();
            
            foreach($request->category as $single_category){
                //get the slug of category from db
                $ticket_category = TicketCategory::find($single_category);
                $ticket_slug = $ticket_category->slug;
                //creat names for inputs
                $amount = $ticket_slug.'_amount';
                $tickets = $ticket_slug.'_tickets';
                $ticket_sale_end_date = $ticket_slug.'_ticket_sale_end_date';

                $ticket_category_details = new TicketCategoryDetail;
                $ticket_category_details->event_id = $event_id;
                $ticket_category_details->category_id = $ticket_category->id;
                $ticket_category_details->price = $request->$amount;
                $ticket_category_details->no_of_tickets = $request->$tickets;
                $ticket_category_details->ticket_sale_end_date = date('Y-m-d H:i:s',strtotime($request->$ticket_sale_end_date));
                $ticket_category_details->save();
                
            }           
        }

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
            //we will search for events that belong to current event organizer only
            $event_organizer_id = Auth::guard('web_event_organizer')->user()->id;
            $events = Event::select('events.id','events.name','events.slug','events.description','events.location','events.type','events.status','events.created_at','event_organizers.first_name','event_organizers.last_name')
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
            $events = Event::select('events.id','events.name','events.slug','events.description','events.location','events.type','events.status','events.created_at','event_organizers.first_name','event_organizers.last_name')
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
            $events = Event::select('events.id','events.name','events.slug','events.description','events.location','events.type','events.status','events.created_at','event_organizers.first_name','event_organizers.last_name')
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

        //check if its paid event
        if($event->type==2){
            //delete ticket_category_details table
            $ticket_category_details = TicketCategoryDetail::where('event_id',$request->id);
            $ticket_category_details->delete();
        }

        //delete event_sponsor_media table
        $event_sponsor_media = EventSponsorMedia::where('event_id',$request->id);
        $event_sponsor_media->delete();

        //delete event_dates table
        $event_dates = EventDate::where('event_id',$request->id);
        $event_dates->delete();

        //delete image
        Storage::delete('images/events/'.$event->image_url);

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
