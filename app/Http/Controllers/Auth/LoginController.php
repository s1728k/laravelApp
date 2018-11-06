<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Traits\AuthenticatesUsers;
use App\Admin;
use App\User;

use Socialite;

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

    protected function redirectTo($rtype = "")
    {
        if ($rtype == "admin"){
            \Log::Info(request()->ip()." was redirected to admin app list page.");
            return '/admin';
        }else if ($rtype == ""){
            \Log::Info(request()->ip()." was redirected to app list page.");
            return '/app/app-list';
        }else{
            \Log::Info(request()->ip()." was redirected to app list page.");
            return "/".$rtype.'/app/app-list';
        }
    }

    protected function emailNotVerified($request, $rtype = "")
    {
        switch ($rtype) {
            case 'admin':
                $check = Admin::where('email', $request->email)->first();
                break;

            default:
                $check = User::where('email', $request->email)->first();
                break;
        }
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

    protected function blockedUser($request, $rtype = "")
    {
        switch ($rtype) {
            case 'admin':
                $check = Admin::where('email', $request->email)->first();
                break;

            default:
                $check = User::where('email', $request->email)->first();
                break;
        }
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


    protected function checkForNoUserEntries()
    {
        $admin = Admin::find(1);
        if(!empty($admin)){
            return false;
        }else{
            \Log::Info(request()->ip()." no admin entries found.");
            return true;
        }
    }

    protected function createFirstAdmin(array $data)
    {
        \Log::Info(request()->ip()." first admin was created.");
        return Admin::create([
            'name' => 'TIO TOUR GUIDES MASTER ADMIN',
            'email' => $data['email'],
            'password' => \Hash::make($data['password']),
            'role' => 'Master',
        ]);
    }

    /**
     * Redirect the user to the GitHub authentication page.
     *
     * @return \Illuminate\Http\Response
     */
    public function redirectToGithub()
    {
        \Log::Info("github entered");
        return Socialite::driver('github')->redirect();
    }

    /**
     * Obtain the user information from GitHub.
     *
     * @return \Illuminate\Http\Response
     */
    public function handleGithubCallback()
    {
        \Log::Info("github redirected");
        $user = Socialite::driver('github')->stateless()->user();
        $this->logUser($user);
        $this->registerUserIfNotExist($user);
        return ["status"=>"success"];
    }

    /**
     * Redirect the user to the GitHub authentication page.
     *
     * @return \Illuminate\Http\Response
     */
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
        \Log::Info("google redirect will start soon");
    }

    /**
     * Obtain the user information from GitHub.
     *
     * @return \Illuminate\Http\Response
     */
    public function handleGoogleCallback()
    {
        \Log::Info("google redirected");
        $user = Socialite::driver('google')->stateless()->user();
        $this->logUser($user);
        $this->registerUserIfNotExist($user);
        return ["status"=>"success"];
    }

    /**
     * Redirect the user to the GitHub authentication page.
     *
     * @return \Illuminate\Http\Response
     */
    public function redirectToLinkedIn()
    {
        \Log::Info("LinkedIn Login");
        return Socialite::driver('linkedin')->redirect();
    }

    /**
     * Obtain the user information from GitHub.
     *
     * @return \Illuminate\Http\Response
     */
    public function handleLinkedInCallback()
    {
        \Log::Info("LinkedIn");
        $user = Socialite::driver('linkedIn')->stateless()->user();
        $this->logUser($user);
        $this->registerUserIfNotExist($user);
        return ["status"=>"success"];
    }

    /**
     * Redirect the user to the GitHub authentication page.
     *
     * @return \Illuminate\Http\Response
     */
    public function redirectToFacebook()
    {
        \Log::Info("Facebook login");
        return Socialite::driver('facebook')->redirect();
    }

    /**
     * Obtain the user information from GitHub.
     *
     * @return \Illuminate\Http\Response
     */
    public function handleFacebookCallback()
    {
        \Log::Info("Facebook");
        $user = Socialite::driver('facebook')->stateless()->user();
        $this->logUser($user);
        $this->registerUserIfNotExist($user);
        return ["status"=>"success"];
    }

    /**
     * Redirect the user to the GitHub authentication page.
     *
     * @return \Illuminate\Http\Response
     */
    public function redirectToTwitter()
    {
        return Socialite::driver('twitter')->redirect();
    }

    /**
     * Obtain the user information from GitHub.
     *
     * @return \Illuminate\Http\Response
     */
    public function handleTwitterCallback()
    {
        \Log::Info("Twitter");
        $user = Socialite::driver('twitter')->user();
        $this->logUser($user);
        // $this->registerUserIfNotExistOAuth1($user);
        return $user;
    }

    public function registerUserIfNotExist($user)
    {
        $email = User::where('email',$user->getEmail())->get();
        \Log::Info(count($email));
        if (count(User::where('email',$user->getEmail())->get() ) === 0 ){
            \Log::Info("ready to create");
            $newUser = [
                'nickname' => $user->getNickname(),
                'name' => $user->getName(),
                'email' => $user->getEmail(),
                'password' => bcrypt("password"),
                'avatar' => $user->getAvatar(),
                'token' => $user->token,
                'remember_token' => $user->refreshToken,
            ];
            \Log::Info($newUser);
            User::create($newUser);
        }else{
            \Log::Info("ready to update");
            $usera = User::where('email', $user->getEmail())
                    ->update([
                        'avatar' => $user->getAvatar(),
                        'token' => $user->token,
                        'remember_token' => $user->refreshToken
                    ]);
        }
    }

    public function logUser($user)
    {
        \Log::Info($user->getNickname());
        \Log::Info($user->getName());
        \Log::Info($user->getEmail());
        \Log::Info($user->getAvatar());
        \Log::Info($user->token);
        \Log::Info($user->refreshToken);
    }

}
