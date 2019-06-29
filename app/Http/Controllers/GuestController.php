<?php
namespace App\Http\Controllers;

use Carbon\Carbon;
use App\PasswordReset;
use App\Mail\ResetPasswordMail;
use App\Traits\Docs;
use App\Traits\Blog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class GuestController extends CloudController
{
    use Docs;
    use Blog;
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {

    }

    public function homeView()
    {
        \Log::Info($this->fc.'homeView');
        return view($this->theme.'.welcome');
    }

    public function docsView()
    {
        \Log::Info($this->fc.'docsView');
        return view($this->theme.'.docs');
    }

    public function loginView()
    {
        \Log::Info($this->fc.'loginView');
        return view($this->theme.'.auth.login');
    }

    public function signupView()
    {
        \Log::Info($this->fc.'signupView');
        return view($this->theme.'.auth.signup');
    }

    public function passwordResetRequestFormView()
    {
        \Log::Info($this->fc.'passwordResetRequestFormView');
        return view($this->theme.'.auth.passwords.email');
    }

    public function passwordResetFormView(Request $request, $id)
    {
        \Log::Info($this->fc.'passwordResetFormView');
        $table = 'App\\User';
        $record = $table::findOrFail($id);
        $precord = PasswordReset::where("email", $record->email)->first();
        if(!empty($precord)){
            if($request->hash == '"'.$precord->token.'"'){
                \DB::table('password_resets')->where('email', $record->email)->delete();
                return view($this->theme.'.auth.passwords.reset')->with(["id" => $id, "email" => $record->email]);
            }
        }
        return redirect('/login-form');
    }

    public function passwordResetRequest(Request $request)
    {
        \Log::Info($this->fc.'passwordResetRequest');
        $request->validate([
            'email' => 'required|string|max:255|'
        ]);
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

    public function passwordReset(Request $request, $id)
    {
        \Log::Info($this->fc.'passwordReset');
        $request->validate([
            'password' => 'required|string|min:6|confirmed'
        ]);
        $table = 'App\\User';
        $record = $table::findOrFail($id);
        $record->update([
            'password' => bcrypt($request->password),
        ]);
        return redirect('/login-form');
    }

}