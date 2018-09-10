<?php

namespace App\Http\Controllers\AdminPages;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\EventOrganizer;

class EventOrganizersController extends Controller
{
    //redirect path
    protected $VerifiedredirectPath = 'admin/event_organizers/verified';
    protected $UnverifiedredirectPath = 'admin/event_organizers/unverified';
    
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
    public function Verifiedindex()
    {
        $event_organizers = EventOrganizer::where('status',1)->orWhere('status',2)->get();
        $data=array(
           'type'=>'verified',
           'event_organizers'=>$event_organizers
        );
        return view('admin.pages.event_organizers')->with($data);
        
    }

    public function Unverifiedindex()
    {
        $event_organizers = EventOrganizer::where('status',0)->get();
        $data=array(
           'type'=>'unverified',
           'event_organizers'=>$event_organizers
        );
        return view('admin.pages.event_organizers')->with($data);
        
    }

    public function verify(Request $request)
    {
        $id = $request->id;
        $type = $request->type;
        $event_organizer = EventOrganizer::find($id);
        
        $event_organizer->status=1;
        $event_organizer->save();
        $request->session()->flash('status', 'Verified successfully');

        //check where the request came from and redirect back to that page
        if ($type=="verified") {            
            return redirect($this->VerifiedredirectPath);
        } else {            
            return redirect($this->UnverifiedredirectPath);
        }
        
        
    }

    public function activate(Request $request)
    {        
        $id = $request->id;
        $type = $request->type;
        $event_organizer = EventOrganizer::find($id);
        
        $event_organizer->status=1;
        $event_organizer->save();
        $request->session()->flash('status', 'Activated successfully');

        //check where the request came from and redirect back to that page
        if ($type=="verified") {            
            return redirect($this->VerifiedredirectPath);
        } else {            
            return redirect($this->UnverifiedredirectPath);
        }
        
    }

    public function deactivate(Request $request)
    {        
        $id = $request->id;
        $type = $request->type;
        $event_organizer = EventOrganizer::find($id);
        
        $event_organizer->status=2;
        $event_organizer->save();
        $request->session()->flash('status', 'Deactivated successfully');

        //check where the request came from and redirect back to that page
        if ($type=="verified") {            
            return redirect($this->VerifiedredirectPath);
        } else {            
            return redirect($this->UnverifiedredirectPath);
        }
        
    }
}
