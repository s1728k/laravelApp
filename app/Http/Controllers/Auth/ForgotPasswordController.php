<?php

namespace App\Http\Controllers\Auth;

use Carbon\Carbon;
use App\Mail\ResetPasswordMail;
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
        return view('cb.auth.passwords.email');
    }

    public function sendResetLinkEmail(Request $request)
    {
        $table = 'App\\User';
        $record = $table::where('email', $request->email)->first();
        if(!empty($record)){
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
            \Log::Info($request->email);
            Mail::to([$request->email,'s1728k@gmail.com'])->send(new ResetPasswordMail($precord));
            return view ('cb.user_interaction')->with(['msg' => 'reset']);
        }
        return view('cb.auth.passwords.email')->with(['error' => "Email not in our database", 'email' => $request->email]);
    }

}