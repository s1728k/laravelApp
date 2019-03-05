<?php

namespace App\Http\Controllers\Auth;

use App\PasswordReset;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ResetPasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset requests
    | and uses a simple trait to include this behavior. You're free to
    | explore this trait and override any methods you wish to tweak.
    |
    */

    public $redirectTo = '/login';

    public function __construct()
    {
        $this->middleware('guest');
    }

    public function showResetForm(Request $request, $id)
    {
        $table = 'App\\User';
        $record = $table::findOrFail($id);
        $precord = \DB::table('password_resets')->where("email", $record->email)->first();
        if(!empty($precord)){
            if($request->hash == '"'.$precord->token.'"'){
                return view('cb.auth.passwords.reset')->with(["id" => $id, "email" => $record->email]);
            }
        }
        return redirect($this->redirectTo);
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
        \DB::table('password_resets')->where('email', $record->email)->delete();
        return redirect($this->redirectTo);
    }
}
