<?php

namespace App\Http\Controllers\Auth;

use App\User;
use App\Traits\AuthenticatesUsers;
use App\Http\Controllers\Controller;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/app/app-list';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    protected function redirectTo()
    {
        \Log::Info(request()->ip()." was redirected to app list page.");
        return '/app/app-list';
    }

    protected function emailNotVerified($request)
    {
        $check = User::where('email', $request->email)->first();

        if(!empty($check)){
            if ($check->email_varification == "done"){
                return false;
            }
        }else{
            return false;
        }
        \Log::Info(request()->ip()." email had not verified and retured back.");
        return true;
    }

    protected function blockedUser($request)
    {
        $check = User::where('email', $request->email)->first();

        if(!empty($check)){
            if (!$check->blocked){
                return false;
            }
        }else{
            return false;
        }
        \Log::Info(request()->ip()." user blocked by admin.");
        return true;
    }

}
