<?php

namespace App\Http\Controllers\Contact;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Notification;
use App\Notifications\ContactNotification;

class ContactController extends Controller
{

    public function store(Request $request)
    {
        try {
            Notification::route('mail', 'haute.signatures@gmail.com')
            ->notify(new ContactNotification($request));
            return back()->with("success", "Thank for your message.Your request will be looked into and a member of our team will be in touch.");
        } catch (\Throwable $th) {
            return back()->with("success", "We could not send your mail at the moment");
        }
       
    }
   
}
