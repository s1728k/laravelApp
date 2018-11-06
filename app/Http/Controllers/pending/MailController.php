<?php

namespace App\Http\Controllers;

use App\Models\Contact;
use App\Mail\GeneralAcknowledgement;
use App\Mail\ContactMessage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class MailController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {;
        // $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function SendContactAcknowledgement(Request $request)
    {
        \Log::Info("fdsfsd");
        Mail::to($request->input("email"))
                ->cc("s1728k@gmail.com")
                ->send(new GeneralAcknowledgement());
        return 11;
    }

    public function ContactMessage(Request $request)
    {
        \Log::Info("fdsfsd");
        Contact::Create($request);
        Mail::to($request->input("email"))
                ->cc("s1728k@gmail.com")
                ->send(new ContactMessage());
        return ['status':'success'];
    }
}
