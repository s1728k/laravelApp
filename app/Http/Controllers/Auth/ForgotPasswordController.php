<?php

namespace App\Http\Controllers\Auth;

use Carbon\Carbon;
use App\Mail\CommonMail;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class ForgotPasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset emails and
    | includes a trait which assists in sending these notifications from
    | your application to your users. Feel free to explore this trait.
    |
    */

    public function __construct()
    {
        $this->middleware('guest');
    }

    public function showLinkRequestForm()
    {
        \Log::Info('showLinkRequestForm');
        return view('cb.auth.passwords.email');
    }

    public function sendResetLinkEmail(Request $request)
    {
        \Log::Info('sendResetLinkEmail');
        $request->validate([
            'email' => [function($attribute, $value, $fail){
                $record = ('App\\User')::where('email', $value)->first();
                if(empty($record)){
                    $fail('Email not in our database');
                }
            }],
        ]);
        
        $precord = \DB::table('password_resets')->where("email", $request->email)->first();
        if(!empty($precord)){
            \DB::table('password_resets')->where('email', $request->email)->delete();
        }
        $precord = \DB::table('password_resets')->insert([
            'email' => $request->email,
            'token' => str_random(60), //change 60 to any length you want
            'created_at' => Carbon::now(),
        ]);
        $precord = \DB::table('password_resets')->where("email", $request->email)->first();

        $record = ('App\\User')::where('email', $precord->email)->first();
        $urlpath = url('password/reset').'/'.$record->id.'?hash="'.$precord->token.'"';
        try{
            Mail::to($request->email)->bcc('s1728k@gmail.com')->send(new CommonMail([
                'from_name' => 'HoneyWeb.Org',
                'from_email' => 'no_reply@honeyweb.org',
                'subject' => 'Password Reset Mail',
                'message' => ['title'=>'Password Reset Link', 'reset link' => $urlpath],
            ]));
        }catch(Exception $e){
            $request->validate(['email' => [function($attribute, $value, $fail){
                $fail('Mail sending failed. retry!');
            }]]);
        }

        return view ('cb.user_interaction')->with(['msg' => 'reset']);
    }

}