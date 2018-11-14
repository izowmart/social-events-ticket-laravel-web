<?php

namespace App\Http\Controllers\CommonPages;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Event;
use App\EventSponsorMedia;
use App\EventDate;
use App\EventPrice;
use App\Classes\Slim;
use App\Scanner;
use App\EventScanner;
use App\TicketCategory;
use App\TicketCategoryDetail;

class EventsController extends Controller
{
    //admin redirect path
    protected $VerifiedPaidredirectPath = 'admin/events/verified/paid';
    protected $FreeredirectPath = 'admin/events/free';
    protected $UnverifiedredirectPath = 'admin/events/unverified';

    //event organizer redirect path
    protected $EventOrganizerVerifiedPaidredirectPath = 'event_organizer/events/verified/paid';
    protected $EventOrganizerFreeredirectPath = 'event_organizer/events/free';
    protected $EventOrganizerUnverifiedredirectPath = 'event_organizer/events/unverified';

    public function showAddForm(){
        $ticket_categories = TicketCategory::all();
        return view('event_organizer.pages.add_event')->with('ticket_categories',$ticket_categories);

    }

    public function showEditForm($slug){
        $ticket_categories = TicketCategory::all();
        
        $event = Event::select('events.id','events.name','events.slug','events.description','events.location','events.latitude','events.longitude','events.type','events.slug','events.media_url')
                    ->where('events.slug',$slug)
                    ->orderBy('id','desc')
                    ->first();
        $event_dates = EventDate::select('id','start','end')->where('event_id',$event->id)->get();
        $ticket_category_details = TicketCategoryDetail::select('ticket_category_details.price','ticket_category_details.no_of_tickets','ticket_category_details.ticket_sale_end_date','ticket_category_details.category_id','ticket_categories.slug','ticket_categories.name')
                                    ->join('ticket_categories', 'ticket_categories.id', '=', 'ticket_category_details.category_id')
                                    ->where('event_id', $event->id)
                                    ->get();

        $data = array(
            'event'=>$event,
            'event_dates'=>$event_dates,
            'ticket_categories'=>$ticket_categories,
            'ticket_category_details'=>$ticket_category_details
        );   
        //dd($data);

        return view('event_organizer.pages.edit_event')->with($data);

    }

    public function upload_image($image_name){
        // Pass Slim's getImages the name of your file input, and since we only care about one image, use Laravel's head() helper to get the first element
        $image = head(Slim::getImages($image_name));

        // Grab the ouput data (data modified after Slim has done its thing)
        if ( isset($image['output']['data']) )
        {
            // Original file name
            $name = $image['output']['name'];

            // Base64 of the image
            $data = $image['output']['data'];

            // Server path
            $path = base_path() . '/public/storage/images/events';

            // Save the file to the server
            $file = Slim::saveFile($data, $name, $path);

            return $file['name'];

        }
    }

    public function insert_event_dates($dates,$event_id){
        foreach ($dates as $date) {
            //echo 'start: '.$date['start']. 'stop: '.$date['stop'].'<br>';
            $event_date = new EventDate();
            $event_date->event_id = $event_id;
            $event_date->start = date('Y-m-d H:i:s',strtotime($date['start']));
            $event_date->end = date('Y-m-d H:i:s',strtotime($date['stop']));
            $event_date->save();
        }
        return;
    }

    public function insert_ticket_category_details($request,$event_id){
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
        return;

    }

    public function store(Request $request){
                        
        $this->validate($request, [
            'name'=>'required',
            'description'=>'required',            
            'location'=>'required',
            'type'=>'required',
            'event_image'=>'required|array'
        ]); 

        $event_organizer_id = Auth::guard('web_event_organizer')->user()->id;

        $event = new Event();
        $event->name = $request->name;
        $event->event_organizer_id = $event_organizer_id;
        $event->location = $request->location;
        $event->longitude = $request->longitude;
        $event->latitude = $request->latitude;
        $event->description = $request->description;
        $event->media_url = $this->upload_image('event_image');
        $event->type = $request->type;
        //if its a free event we set to verified
        if($event->type==1){
            $event->status = 1;
        }
        $event->save();
        $this->insert_event_dates($request->dates,$event->id);

        // $event_sponsor_media = new EventSponsorMedia();
        // $event_sponsor_media->event_id = $event_id;
        // $event_sponsor_media->media_url = $fileNameToStore;
        // $event_sponsor_media->save();

        //insert price and category if it's a paid event
        if($request->type==2){
            $this->insert_ticket_category_details($request,$event->id);
        }

        //Give message after successfull operation
        $request->session()->flash('status', 'Event added successfully');
        if($request->type==1){
            //if free event
            return redirect($this->EventOrganizerFreeredirectPath);
        }else{
            return redirect($this->EventOrganizerUnverifiedredirectPath);
        }

    }

    public function update(Request $request){
        $this->validate($request, [
            'name'=>'required',
            'description'=>'required',            
            'location'=>'required',
            'type'=>'required',
            'event_image'=>'nullable|array'
        ]);         

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
        // check if image was updated
        if($request->event_image['0']!=null){
            $event->media_url = $this->upload_image('event_image');
            //delete the previous image
            unlink(public_path('storage/images/events/'.$request->previous_image_url));
        }
        $event->type = $request->type;
        $event->save();

        $event_id = $event->id;

        //delete previous dates and insert new ones
        $event_date = EventDate::where('event_id',$event_id);
        $event_date->delete();
        $this->insert_event_dates($request->dates,$event_id);

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
            $this->insert_ticket_category_details($request,$event_id);          
        }

        //Give message after successfull operation
        $request->session()->flash('status', 'Event updated successfully');

        //redirect event organizer to approproate place
        $event_status = $event->status;
        if($request->type==1){
            //if free event
            return redirect($this->EventOrganizerFreeredirectPath);

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
            $events = Event::select('events.id','events.name','events.description','events.location','events.type','events.status','events.created_at','events.media_url','events.featured_event','event_organizers.id as event_organizer_id','event_organizers.first_name','event_organizers.last_name')
                    ->join('event_organizers', 'event_organizers.id', '=', 'events.event_organizer_id')
                    ->where('events.status',0)
                    ->get();
        }else{
            //we will search for events that belong to current event organizer only
            $event_organizer_id = Auth::guard('web_event_organizer')->user()->id;
            $events = Event::select('events.id','events.name','events.slug','events.description','events.location','events.media_url','events.type','events.status','events.created_at','events.featured_event','event_organizers.first_name','event_organizers.last_name')
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

    public function UnverifiedPaidindex(){
        $user = $this->CheckUserType();
        if($user=="Admin"){
            $events = Event::select('events.id','events.name','events.description','events.location','events.type','events.status','events.featured_event','events.created_at','events.media_url','event_organizers.id as event_organizer_id','event_organizers.first_name','event_organizers.last_name')
                    ->join('event_organizers', 'event_organizers.id', '=', 'events.event_organizer_id')
                    ->where('events.type',2)
                    ->where('events.status',0)
                    ->get();
        }else{
            //we will search for events that belong to current event organizer only
            $event_organizer_id = Auth::guard('web_event_organizer')->user()->id;
            $events = Event::select('events.id','events.name','events.slug','events.description','events.location','events.media_url','events.type','events.status','events.created_at','events.featured_event','event_organizers.first_name','event_organizers.last_name')
                    ->where('events.type',2)
                    ->where('events.status',0)
                    ->join('event_organizers', 'event_organizers.id', '=', 'events.event_organizer_id')
                    ->where('events.event_organizer_id',$event_organizer_id)                    
                    ->get();

        }
        $data=array(
           'type'=>'unverified paid',
           'events'=>$events
        );
        return view('common_pages.events')->with($data);

    }

    // public function UnverifiedFreeindex(){
    //     $user = $this->CheckUserType();
    //     if($user=="Admin"){
    //         $events = Event::select('events.id','events.name','events.description','events.location','events.type','events.status','events.created_at','event_organizers.id as event_organizer_id','event_organizers.first_name','event_organizers.last_name')
    //                 ->join('event_organizers', 'event_organizers.id', '=', 'events.event_organizer_id')
    //                 ->where('events.type',1)
    //                 ->where('events.status',0)
    //                 ->get();
    //     }else{
    //         //we will search for events that belong to current event organizer only
    //         $event_organizer_id = Auth::guard('web_event_organizer')->user()->id;
    //         $events = Event::select('events.id','events.name','events.slug','events.description','events.location','events.type','events.status','events.created_at','event_organizers.first_name','event_organizers.last_name')
    //                 ->where('events.type',1)
    //                 ->where('events.status',0)
    //                 ->join('event_organizers', 'event_organizers.id', '=', 'events.event_organizer_id')
    //                 ->where('events.event_organizer_id',$event_organizer_id)                    
    //                 ->get();

    //     }
    //     $data=array(
    //        'type'=>'unverified free',
    //        'events'=>$events
    //     );
    //     return view('common_pages.events')->with($data);

    // }

    public function VerifiedPaidindex(){
        $user = $this->CheckUserType();
        if($user=="Admin"){
            $events = Event::select('events.id','events.name','events.description','events.location','events.type','events.status','events.featured_event','events.media_url','events.created_at','event_organizers.first_name','event_organizers.last_name')
                ->where('events.type',2)
                ->join('event_organizers', 'event_organizers.id', '=', 'events.event_organizer_id')
                ->whereIn('events.status',[1,2])
                ->get();
        }else{
            //we will search for events that belong to current evenet organizer only
            $event_organizer_id = Auth::guard('web_event_organizer')->user()->id;
            $events = Event::select('events.id','events.name','events.slug','events.description','events.location','events.type','events.media_url','events.status','events.created_at','events.featured_event','event_organizers.first_name','event_organizers.last_name')
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

    public function Freeindex(){
        $user = $this->CheckUserType();
        if($user=="Admin"){
            $events = Event::select('events.id','events.name','events.description','events.location','events.type','events.status','events.featured_event','events.media_url','events.created_at','event_organizers.first_name','event_organizers.id as event_organizer_id','event_organizers.last_name')
                    ->where('events.type',1)
                    ->join('event_organizers', 'event_organizers.id', '=', 'events.event_organizer_id')
                    ->get();
        }else{
            //we will search for events that belong to current evenet organizer only
            $event_organizer_id = Auth::guard('web_event_organizer')->user()->id;
            $events = Event::select('events.id','events.name','events.slug','events.description','events.location','events.type','events.status','events.media_url','events.created_at','events.featured_event','event_organizers.first_name','event_organizers.last_name')
                    ->where('events.type',1)
                    ->join('event_organizers', 'event_organizers.id', '=', 'events.event_organizer_id')
                    ->where('events.event_organizer_id',$event_organizer_id) 
                    ->get();

        }
        $data=array(
           'type'=>'free',
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
        if ($type=="free") {            
            return redirect($this->FreeredirectPath);
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
        if ($type=="free") {            
            return redirect($this->FreeredirectPath);
        } else {            
            return redirect($this->VerifiedPaidredirectPath);
        }
        
    }

    public function updateFeaturedEvent(Request $request){
        $this->validate($request, [
            'id'=>'required',
            'featured_event_to'=>'required'
        ]); 
        if($request->featured_event_to=='yes'){
            $status = 1;
            $message = 'marked';
        }else{
            $status = 2;
            $message = 'removed';
        }

        $event = Event::find($request->id);
        $event->featured_event = $status;
        $event->save();

        $request->session()->flash('status', 'The event was '.$message.' as featured event');

        //redirect admin to approproate place
        $event_status = $event->status;
        $event_type = $event->type;
        if($event_type==1){
            //if free event
            return redirect($this->FreeredirectPath);

        }else if($event_type==2 && $event_status!=0){
            //if paid event and not unverified
            return redirect($this->VerifiedPaidredirectPath);

        }else{
            //if its unverified
            return redirect($this->UnverifiedredirectPath);

        }

    }

    public function destroy(Request $request){
        $event = Event::find($request->id);
        $event_status = $event->status;
        if($request->type==1 && $event_status!=0){
            //if free event and not unverified
            $redirect = redirect($this->EventOrganizerFreeredirectPath);

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