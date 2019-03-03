<?php

namespace App\Http\Controllers\Auth;

use Carbon\Carbon;
use App\PasswordReset;
use App\Http\Controllers\Controller;
use App\Traits\SendsPasswordResetEmails;
use Illuminate\Http\Request;
use App\Mail\ResetPasswordMail;
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

    use SendsPasswordResetEmails;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    public function showLinkRequestForm()
    {
        return view('auth.passwords.email');
    }

    public function sendResetLinkEmail(Request $request)
    {
        $table = 'App\\User';
        $record = $table::where('email', $request->email)->first();
        if(!empty($record)){
            $precord = PasswordReset::where("email", $request->email)->first();
            if(!empty($precord)){
                \DB::table('password_resets')->where('email', $request->email)->delete();
            }
            \DB::table('password_resets')->insert([
                'email' => $request->email,
                'token' => str_random(60), //change 60 to any length you want
                'created_at' => Carbon::now(),
            ]);
            $precord = PasswordReset::where("email", $request->email)->first();
            Mail::to($request->email)->send(new ResetPasswordMail($precord));
        }
        return view('auth.passwords.email')->with(['error' => "Email not in our database", 'email' => $request->email]);
    }

    public function password_reset($id, $hash)
    {
        $table = 'App\\User';
        $record = $table::findOrFail($id);
        $precord = PasswordReset::where("email", $record->email)->first();
        if(!empty($precord)){
            if($hash == $precord->token){
                \DB::table('password_resets')->where('email', $record->email)->delete();
                return view('auth.passwords.reset')->with(["id" => $id, "email" => $record->email]);
            }
        }
        return redirect('/login');
    }

    public function reset(Request $request)
    {
        $request->validate([
            'password' => 'required|string|min:6|confirmed'
        ]);
        $table = 'App\\User';
        $record = $table::findOrFail($request->id);
        $record->update([
            'password' => \Hash::make($request->password),
        ]);
        return redirect('/login');
    }

}