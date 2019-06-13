<?php

namespace App\Http\Controllers\Auth;

use App\User;
use App\Mail\CommonMail;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Auth\Events\Registered;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    protected $redirectTo = '/login';

    public function __construct()
    {
        $this->middleware('guest');
    }

    protected function redirectTo()
    {
        \Log::Info(request()->ip()." redirected to email verification send page.");
        return $this->redirectTo;
    }

    public function showRegistrationForm()
    {
        \Log::Info(request()->ip()." visited laravel signup page.");
        return view('cb.auth.signup');
    }

    public function register(Request $request)
    {
        \Log::Info(request()->ip()." attempted to register.");
        $this->validator($request->all())->validate();
        event(new Registered($user = $this->create($request->all())));

        $urlpath = url('email_verified').'/'.$user->id.'?hash="'.$user->email_varification.'"';
        try{
            Mail::to($request->email)->bcc('s1728k@gmail.com')->send(new CommonMail([
                'from_name' => 'HoneyWeb.Org',
                'from_email' => 'no_reply@honeyweb.org',
                'subject' => 'Email Verification',
                'message' => ['title'=>'Email Verification Link', 'verification link' => $urlpath],
            ]));
        }catch(Exception $e){
            $request->validate(['email' => [function($attribute, $value, $fail){
                $fail('Mail sending failed. retry!');
            }]]);
        }
        return view ('cb.user_interaction')->with(['msg' => 'signup']);
    }

    public function email_verified(Request $request, $id)
    {
        \Log::Info(request()->ip()." attempted to verify email.");
        if('"'.User::findOrFail($id)->email_varification.'"' == $request->query('hash')){
            User::findOrFail($id)->update(['email_varification' => 'done']);
            return view ('cb.user_interaction')->with(['msg' => 'signup_complete']);
        };
        return view ('cb.user_interaction')->with(['msg' => 'invalid_link']);
    }

    public function resend_verification_mail(Request $request)
    {
        \Log::Info(request()->ip()." resend_verification_mail");
        $request->validate(['email' => 'required|email|exists:users']);
        $user = ('Auth\\User')::where('email', $request->email)->first();
        $urlpath = url('email_verified').'/'.$user->id.'?hash="'.$user->email_varification.'"';
        try{
            Mail::to($request->email)->bcc('s1728k@gmail.com')->send(new CommonMail([
                'from_name' => 'HoneyWeb.Org',
                'from_email' => 'no_reply@honeyweb.org',
                'subject' => 'Email Verification',
                'message' => ['title'=>'Email Verification Link', 'verification link' => $urlpath],
            ]));
        }catch(Exception $e){
            $request->validate(['email' => [function($attribute, $value, $fail){
                $fail('Mail sending failed. retry!');
            }]]);
        }
        return view ('cb.user_interaction')->with(['msg' => 'signup']);
    }

    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6|confirmed',
        ]);
    }

    protected function create(array $data)
    {
        return User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => bcrypt($data['password']),
            'email_varification' => bcrypt($data['email']),
        ]);
    }

    protected function guard()
    {
        return Auth::guard();
    }

    protected function registered(Request $request, $user)
    {
        
    }
}
