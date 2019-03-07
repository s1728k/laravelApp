<?php

namespace App\Http\Controllers\Auth;

use App\User;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

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


    protected $redirectTo = '/app/app-list';


    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    protected function redirectTo()
    {
        \Log::Info(request()->ip()." was redirected to app list page.");
        return $this->redirectTo;
    }

    protected function showLoginForm()
    {
        return view('cb.auth.login');
    }

    protected function login(Request $request)
    {
        $this->validateLogin($request);

        if ($this->hasTooManyLoginAttempts($request)) {
            $this->fireLockoutEvent($request);

            return $this->sendLockoutResponse($request);
        }

        //----------------------------------------
        if ($this->emailNotVerified($request)){
            return view ('cb.user_interaction')->with(['msg' => 'login_redirect']);
        }
        $user = $this->blockedUser($request);
        if($user){
            return view ('cb.user_interaction')->with(['msg' => 'login_redirect', 'user' => $user]);
        }
        //----------------------------------------

        if ($this->attemptLogin($request)) {
            \Log::Info("attempt to login success");
            \Log::Info($this->sendLoginResponse($request));
            return $this->sendLoginResponse($request);
        }

        $this->incrementLoginAttempts($request);

        return $this->sendFailedLoginResponse($request);
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
        return $check;
    }

}
