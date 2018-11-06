<?php
namespace App\Http\Controllers;

use Carbon\Carbon;
use App\PasswordReset;
use App\Mail\ResetPasswordMail;
use App\Traits\Docs;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class GuestController extends CloudController
{
    use Docs;
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $table = 'App\\Visitor';
        $record = $table::where('ip_address', request()->ip())->first();
        $this->theme = $record->theme??'cb';
    }

    public function setTheme(Request $request)
    {
        $arr = ['Bootstrap Theme' => 'cb', 'Materialize Theme' => 'cm'];
        $table = 'App\\Visitor';
        $record = $table::where('ip_address', request()->ip())->first();
        if(!empty($record)){
            $record->update([
                'ip_address' => request()->ip(),
                'uuid' => $request->uuid,
                'theme' => $arr[$request->theme]
            ]);
        }else{
            $table::create([
                'ip_address' => request()->ip(),
                'uuid' => $request->uuid,
                'theme' => $arr[$request->theme]
            ]);
        }
        return redirect()->route('c.welcome');
    }

    public function homeView()
    {
        \Log::Info(request()->ip()." visited welcome page.");
        return view($this->theme.'.welcome')->with(['admin' => false]);
    }

    public function docsView()
    {
        \Log::Info(request()->ip()." visited docs page.");
        return view($this->theme.'.docs')->with(['admin' => false]);
    }

    public function loginView()
    {
        \Log::Info(request()->ip()." visited login page.");
        return view($this->theme.'.auth.login')->with(['admin' => false]);
    }

    public function signupView()
    {
        \Log::Info(request()->ip()." visited signup page.");
        return view($this->theme.'.auth.signup')->with(['admin' => false]);
    }

    public function passwordResetRequestFormView()
    {
        \Log::Info(request()->ip()." visited password reset request page.");
        return view($this->theme.'.auth.passwords.email')->with(['admin' => false]);
    }

    public function passwordResetFormView(Request $request, $rtype, $id)
    {
        \Log::Info(request()->ip()." visited password reset page.");
        $table = 'App\\'.ucwords(rtrim($rtype,'s'));
        $record = $table::findOrFail($id);
        $precord = PasswordReset::where("email", $record->email)->first();
        if(!empty($precord)){
            if($request->hash == '"'.$precord->token.'"'){
                \DB::table('password_resets')->where('email', $record->email)->delete();
                return view($this->theme.'.auth.passwords.reset')->with(['admin' => false, "id" => $id, "rtype" => $rtype, "email" => $record->email]);
            }
        }
        return redirect('/login-form');
    }

    public function passwordResetRequest(Request $request)
    {
        \Log::Info(request()->ip()." requested password reset.");
        $request->validate([
            'email' => 'required|string|max:255|'
        ]);
        $table = 'App\\'.ucwords(rtrim('users','s'));
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
            Mail::to($request->email)->send(new ResetPasswordMail('user', $precord));
            $request->validate([
                'email' => [function($attribute, $value, $fail){
                    $fail("Reset link has been successfully sent to your email!");
                }],
            ]);
        }
        $request->validate([
            'email' => [function($attribute, $value, $fail){
                $fail("Email is not in our records!");
            }],
        ]);
    }

    public function passwordReset(Request $request, $rtype, $id)
    {
        \Log::Info(request()->ip()." changed password.");
        $request->validate([
            'password' => 'required|string|min:6|confirmed'
        ]);
        $table = 'App\\'.ucwords(rtrim($rtype,'s'));
        $record = $table::findOrFail($id);
        $record->update([
            'password' => bcrypt($request->password),
        ]);
        return redirect('/login-form');
    }

    public function adminLoginRedirect()
    {
        return redirect()->route('c.auth.admin.login');
    }

    public function adminLoginView()
    {
        \Log::Info(request()->ip()." visited admin login page.");
        return view($this->theme.'.auth.admin_login')->with(['admin' => true]);
    }

    public function adminSignupView()
    {
        \Log::Info(request()->ip()." visited admin signup page.");
        return view($this->theme.'.auth.admin_signup')->with(['admin' => true]);
    }

}