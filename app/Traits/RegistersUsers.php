<?php

namespace App\Traits;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Auth\Events\Registered;

trait RegistersUsers
{
    use RedirectsUsers;

    /**
     * Show the application registration form.
     *
     * @return \Illuminate\Http\Response
     */
    public function showRegistrationForm($rtype = "")
    {
        switch ($rtype) {
            case 'admin':
                \Log::Info(request()->ip()." visited admin signup page.");
                return view('c.admin_signup');
                break;
            default:
                \Log::Info(request()->ip()." visited laravel signup page.");
                return view('c.auth.signup');
                break;
        }
    }

    public function showAdminRegistrationForm($rtype = "")
    {
        \Log::Info(request()->ip()." visited admin signup page.");
        return view('c.admin_signup');
    }

    /**
     * Handle a registration request for the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function register(Request $request, $rtype = "")
    {
        \Log::Info(request()->ip()." attempted to register.");
        // $this->validator($request->all())->validate();
        // $this->guard()->login($user);
        // return $this->registered($request, $user) ?: redirect($this->redirectPath());
        switch ($rtype) {
            case 'admin':
                $this->adminvalidator($request->all())->validate();
                event(new Registered($user = $this->admincreate($request->all())));
                $this->sendEmailVerificationMail($request, $rtype, $user);
                return redirect($this->redirectPath($rtype));
                break;
            default:
                $this->validator($request->all())->validate();
                event(new Registered($user = $this->create($request->all())));
                $this->sendEmailVerificationMail($request, $rtype, $user);
                return redirect($this->redirectPath());
                break;
        }
    }

    public function adminRegister(Request $request)
    {
        \Log::Info(request()->ip()." attempted to admin register.");
        $this->register($request, 'admin');
    }

    public function email_verified(Request $request, $rtype, $id)
    {
        \Log::Info(request()->ip()." attempted to verify email.");
        return $this->verify_email($request, $rtype, $id);
    }

    /**
     * Get the guard to be used during registration.
     *
     * @return \Illuminate\Contracts\Auth\StatefulGuard
     */
    protected function guard()
    {
        return Auth::guard();
    }

    /**
     * The user has been registered.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  mixed  $user
     * @return mixed
     */
    protected function registered(Request $request, $user)
    {
        //
    }
}
