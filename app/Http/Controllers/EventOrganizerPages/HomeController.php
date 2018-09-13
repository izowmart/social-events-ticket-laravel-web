<?php

namespace App\Http\Controllers\EventOrganizerPages;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class HomeController extends Controller
{
    public function index(){
        return view('event_organizer.pages.home');
    }
}
