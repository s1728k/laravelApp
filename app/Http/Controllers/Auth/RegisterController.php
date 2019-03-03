<?php

namespace App\Http\Controllers\Auth;

use App\User;
use App\Mail\EmailVerification;
use App\Traits\RegistersUsers;
use Illuminate\Support\Facades\Mail;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

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

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = '/email_verification_sent';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    protected function redirectTo()
    {
        \Log::Info(request()->ip()." redirected to email verification send page.");
        return '/email_verification_sent';
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6|confirmed',
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\User
     */
    protected function create(array $data)
    {
        return User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => bcrypt($data['password']),
            'email_varification' => bcrypt($data['email']),
        ]);
    }

    protected function sendEmailVerificationMail($request, $user)
    {
        \Log::Info(request()->ip()." confirmation mail was sent to verify email.");
        Mail::to($request->email)->send(new EmailVerification($user));
    }

    public function email_verification_sent()
    {
        \Log::Info(request()->ip()." visited email verification sent page.");
        return view('c.email-verification-sent')->with(['admin' => false]);
    }

    public function verify_email($request, $id)
    {
        if('"'.User::findOrFail($id)->email_varification.'"' == $request->query('hash')){
            User::findOrFail($id)->update(['email_varification' => 'done']);
            return view('c.email_verified');
        };
        \Log::Info(request()->ip()." email verification was successfull redirected to login page.");
        return redirect('/login-form');
    }
}
