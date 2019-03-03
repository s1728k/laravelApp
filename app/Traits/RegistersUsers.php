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
    public function showRegistrationForm()
    {
        \Log::Info(request()->ip()." visited laravel signup page.");
        return view('c.auth.signup');
    }

    /**
     * Handle a registration request for the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function register(Request $request)
    {
        \Log::Info(request()->ip()." attempted to register.");
        // $this->validator($request->all())->validate();
        // $this->guard()->login($user);
        // return $this->registered($request, $user) ?: redirect($this->redirectPath());
        $this->validator($request->all())->validate();
        event(new Registered($user = $this->create($request->all())));
        $this->sendEmailVerificationMail($request, $user);
        return redirect($this->redirectPath());
    }

    public function email_verified(Request $request, $id)
    {
        \Log::Info(request()->ip()." attempted to verify email.");
        return $this->verify_email($request, $id);
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
