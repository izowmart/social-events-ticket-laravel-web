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

    public function showAddTicketTemplate($slug){
        $event = Event::where('slug',$slug)->first();
        //make sure the event is not yet verified by admin or is not a free event
        if($event->status==1 || $event->type==1){
            return redirect($this->EventOrganizerVerifiedPaidredirectPath);            
        }
        $event_dates = EventDate::select('id','start','end')->where('event_id',$event->id)->get();
        $ticket_category_details = TicketCategoryDetail::select('ticket_category_details.price','ticket_category_details.no_of_tickets','ticket_category_details.category_id','ticket_categories.slug','ticket_categories.name')
                                    ->join('ticket_categories', 'ticket_categories.id', '=', 'ticket_category_details.category_id')
                                    ->where('event_id', $event->id)
                                    ->get();
        $data = array(
            'event'=>$event,
            'event_dates'=>$event_dates,
            'ticket_category_details'=>$ticket_category_details
        );   
        return view('event_organizer.pages.select_ticket_template')->with($data);

    }

    public function saveTicketTemplate(Request $request){
        $this->validate($request, [
            'ticket_template'=>'required|numeric',
            'event_id'=>'required|numeric'
        ]); 
        $event = Event::find($request->event_id);
        $event->ticket_template = $request->ticket_template;
        //we update the staus to unverified onl if it was draft
        if($event->status==3){
            $event->status = 0;
        }
        $event->save();

        //Give message after successfull operation
        $request->session()->flash('status', 'Ticket template updated successfully');

        return redirect($this->EventOrganizerUnverifiedredirectPath);
        

    }

    public function showEditForm($slug){
        $ticket_categories = TicketCategory::all();
        
        $event = Event::select('events.id','events.name','events.slug','events.description','events.status','events.location','events.latitude','events.longitude','events.type','events.slug','events.media_url','events.ticket_sale_end_date')
                    ->where('events.slug',$slug)
                    ->orderBy('id','desc')
                    ->first();
        //allow edit for free events and paid events that are not verified only
        if($event->status==1 && $event->type!=1){
            return redirect($this->EventOrganizerVerifiedPaidredirectPath);            
        }
        $event_dates = EventDate::select('id','start','end')->where('event_id',$event->id)->get();
        $ticket_category_details = TicketCategoryDetail::select('ticket_category_details.price','ticket_category_details.no_of_tickets','ticket_category_details.category_id','ticket_categories.slug','ticket_categories.name')
                                    ->join('ticket_categories', 'ticket_categories.id', '=', 'ticket_category_details.category_id')
                                    ->where('event_id', $event->id)
                                    ->get();

        $data = array(
            'event'=>$event,
            'event_dates'=>$event_dates,
            'ticket_categories'=>$ticket_categories,
            'ticket_category_details'=>$ticket_category_details
        );   

        return view('event_organizer.pages.edit_event')->with($data);

    }

    public function store(Request $request){
                        
        try{
        $this->validate($request, [
            'name'=>'required',
            'description'=>'required',            
            'location'=>'required',
            'type'=>'required',
            'ticket_sale_end_date'=>'nullable|date',
            'event_image'=>'required|array',
            'event_sponsor_image'=>'nullable|array'
        ]); 

        
        $event_organizer_id = Auth::guard('web_event_organizer')->user()->id;

        // dd(Auth::guard('web_event_organizer')->user()->id,$event_organizer_id);

        $event = new Event();
        $event->name = $request->name;
        $event->event_organizer_id = $event_organizer_id;
        $event->location = $request->location;
        $event->longitude = $request->longitude;
        $event->latitude = $request->latitude;
        $event->description = $request->description;
        //if it is paid event we insert ticket_sale_end_date and set status to draft
        if($request->type==2 && $request->has('ticket_sale_end_date')){
            $event->ticket_sale_end_date = date('Y-m-d H:i:s',strtotime($request->ticket_sale_end_date));
            $event->status = 3;
        }
        $event->media_url = $this->uploadImage('event_image','/public/storage/images/events',0);
        $event->type = $request->type;
        //if its a free event we set to verified
        if($request->type==1){
            $event->status = 1;
        }
        $event->save();
        $this->insertEventDates($request->dates,$event->id);

        if($request->has('sponsor_images_checkbox')) {
            if($request->event_sponsor_image['0']!=null){
                //insert the event sponsor media images
                $this->insertEventSponsorImages($request->event_sponsor_image,$event->id);
    
            }
        }               

        //insert price and category if it's a paid event
        if($request->type==2){
            $this->insertTicketCategoryDetails($request,$event->id);
        }

        if($request->type==1){
            //if free event
            $request->session()->flash('status', 'Event added successfully');
            return redirect($this->EventOrganizerFreeredirectPath);
        }else{
            //for paid event redirect to choose ticket template            
            $request->session()->flash('status', 'Event added successfully. Choose the ticket template');
            return redirect('event_organizer/events/add/ticket-template/'.$event->slug);
        }
    }catch(Exception $exception){
        logger("event creation failed: "+$exception->getMessage());
    }

    }

    public function update(Request $request){
        $this->validate($request, [
            'name'=>'required',
            'description'=>'required',            
            'location'=>'required',
            'type'=>'required',
            'ticket_sale_end_date'=>'nullable|date',
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
        //if its a free event we set to verified
        if($request->type==1){
            $event->status = 1;
        }
        //if it is paid event we insert ticket_sale_end_date
        if($request->type==2&&$request->has('ticket_sale_end_date')){
            $event->ticket_sale_end_date = date('Y-m-d H:i:s',strtotime($request->ticket_sale_end_date));
        }
        // check if image was updated
        if($request->event_image['0']!=null){
            $event->media_url = $this->uploadImage('event_image','/public/storage/images/events',0);
            //delete the previous image
            unlink(public_path('storage/images/events/'.$event->previous_image_url));
            // unlink(public_path('storage/images/events/'.$request->previous_image_url));
        }
        $event->type = $request->type;
        $event->save();

        $event_id = $event->id;

        if($request->has('sponsor_images_checkbox')) {
            if($request->event_sponsor_image['0']!=null){
                //we delete all previous images if they exist
                $this->deleteSponsorImages($event->id);
                //insert the new event sponsor media images
                $this->insertEventSponsorImages($request->event_sponsor_image,$event->id);    
            }
        } else{
            //if its unchecked delete if its present on db
            $this->deleteSponsorImages($event->id);
        }

        //delete previous dates and insert new ones
        $event_date = EventDate::where('event_id',$event_id);
        $event_date->delete();
        $this->insertEventDates($request->dates,$event_id);

        //check if it was a paid event and changed to free. If so we will delete its record from ticket_category_details table
        if($request->type==1 && $prev_event_type==2){
            $ticket_category_details = TicketCategoryDetail::where('event_id',$request->id);
            $ticket_category_details->delete();
            
        }else{
            //else if it was a free and change to paid, we set event status to unverified
            $event->status = 0;
            $event->save();
        }

        //update price and category if it's a paid event
        if($request->type==2){
            //delete all its records from ticket_category_details table and insert the new ones
            $ticket_category_details = TicketCategoryDetail::where('event_id',$request->id);
            $ticket_category_details->delete();            
            $this->insertTicketCategoryDetails($request,$event_id);
        }

        //Give message after successfull operation
        $request->session()->flash('status', 'Event updated successfully');

        //if the user checked that he needs to update ticket template. But first we ensure the sponsor images are there
        if($request->has('sponsor_images_checkbox')) {
            if($request->has('update_ticket_template_checkbox')) {
                return redirect('event_organizer/events/add/ticket-template/'.$event->slug);
            }
            
        }

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

    //Expects the image name, the storage location of the image and the image array index
    public function uploadImage($image_name,$storage_location,$index){

        // Pass Slim's getImages the name of your file input
        $images = Slim::getImages($image_name);

        $image = $images[$index];
        // Grab the ouput data (data modified after Slim has done its thing)
        if ( isset($image['output']['data']) )
        {
            // Original file name
            $name = $image['output']['name'];

            // Base64 of the image
            $data = $image['output']['data'];

            // Server path
            $path = base_path() . $storage_location;

            // Save the file to the server
            $file = Slim::saveFile($data, $name, $path);

            return $file['name'];

        } 
        
    }

    public function insertEventSponsorImages($images,$event_id){
        for ($i=0; $i < count($images); $i++) { 
            if($images[$i]!=null){                
                $event_sponsor_media = new EventSponsorMedia();
                $event_sponsor_media->event_id = $event_id;
                $event_sponsor_media->media_url = $this->uploadImage('event_sponsor_image','/public/storage/images/event_sponsors',$i);
                $event_sponsor_media->save();
            }
        }
        return;
    }

    public function insertEventDates($dates,$event_id){
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

    public function insertTicketCategoryDetails($request,$event_id){
        foreach($request->category as $single_category){
            //get the slug of category from db
            $ticket_category = TicketCategory::find($single_category);
            $ticket_slug = $ticket_category->slug;
            //creat names for inputs
            $amount = $ticket_slug.'_amount';
            $tickets = $ticket_slug.'_tickets';

            $ticket_category_details = new TicketCategoryDetail;
            $ticket_category_details->event_id = $event_id;
            $ticket_category_details->category_id = $ticket_category->id;
            $ticket_category_details->price = $request->$amount;
            $ticket_category_details->no_of_tickets = $request->$tickets;
            $ticket_category_details->save();
            
        }
        return;

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
        $id = Crypt::decrypt($request->id);

        $event = Event::find($id);        
        $event->status=1;
        $event->save();

        $request->session()->flash('status', 'Verified successfully');           
        return redirect($this->UnverifiedredirectPath);
        
        
        
    }

    public function activate(Request $request)
    {        
        $id = Crypt::decrypt($request->id);
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
        $id = Crypt::decrypt($request->id);
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

        $event = Event::find(Crypt::decrypt($request->id));
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
        $event_id = Crypt::decrypt($request->id);
        $event = Event::find($event_id);
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
            $event_scanners = EventScanner::where('event_id',$event_id)->get();
            foreach($event_scanners as $event_scanner){                
                $scanner = Scanner::find($event_scanner->scanner_id);                
                $scanner->delete();
                $event_scanner->delete();
            }

        }

        //check if its paid event
        if($event->type==2){
            //delete ticket_category_details table
            $ticket_category_details = TicketCategoryDetail::where('event_id',$event_id);
            $ticket_category_details->delete();
        }

        //delete event_sponsor_media 
        $this->deleteSponsorImages($event_id);

        //delete event_dates table
        $event_dates = EventDate::where('event_id',$event_id);
        $event_dates->delete();

        //delete image
        unlink(public_path('storage/images/events/'.$event->media_url));

        //finally delete events table
        $event->delete();
        //Give message to event organizer after successfull operation
        $request->session()->flash('status', 'Event deleted successfully');
        return $redirect;

    } 

    public function deleteSponsorImages($event_id){
        //check if record exist first
        if(EventSponsorMedia::where('event_id',$event_id)->count()>0){
            $sponsor_images = EventSponsorMedia::where('event_id',$event_id)->get();
            foreach($sponsor_images as $single_sponser_image){
                //delete image
                unlink(public_path('storage/images/event_sponsors/'.$single_sponser_image->media_url));
                $single_sponser_image->delete();                
            }

        }
        return;
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