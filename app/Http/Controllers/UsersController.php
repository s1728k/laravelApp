<?php

namespace App\Http\Controllers;

use JWTAuth;
use JWTAuthException;

use App\Models\User;
use App\Models\Admin;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class UsersController extends Controller
{
    private $table;
    private $user;
    private $admin;
    private $auth_user;
    
    public function __construct(User $user, Admin $admin){
        $this->user = $user;
        $this->admin = $admin;
    }
    
    private function setTable($table)
    {
        // $this->table = 'App\\Models\\'.$table;
        $this->table = 'App\\Models\\'.$this->auth_user->id.'\\'.$this->auth_user->active_app_id.ucwords(rtrim($table,'s'));
    }
    
    public function client_customer_register($dbname, $table)
    {
        \Log::Info("client_customer_register".$table);
        // \Log::Info($this->request->all());
        $this->setTable($table);
        $user = $this->table::create([
          'name' => $request->get('name'),
          'email' => $request->get('email'),
          'password' => bcrypt($request->get('password'))
        ]);
        return response()->json(['status'=>true,'message'=>'User created successfully','data'=>$user]);
    }
    
    public function client_customer_login(Request $request){
        \Log::Info("login");
        $credentials = $request->only('app_id', 'email', 'password');
        $token = null;
        try {
           if (!$token = JWTAuth::attempt($credentials)) {
            return response()->json(['invalid_email_or_password'], 422);
           }
        } catch (JWTAuthException $e) {
            return response()->json(['failed_to_create_token'], 500);
        }
        return ['status' => true, 'session_token' => $token ];
    }
    
    public function client_register(Request $request){
        $user = $this->user->create([
          'name' => $request->get('name'),
          'email' => $request->get('email'),
          'password' => bcrypt($request->get('password'))
        ]);
        // $myfile = fopen(app_path() ."/Models/".$this->auth_user->id."/1.php", "w");
        // fwrite($myfile, "");
        // fclose($myfile);
        return response()->json(['status'=>true,'message'=>'User created successfully','data'=>$user]);
    }
    
    public function client_login(Request $request){
        \Log::Info("login");
        $credentials = $request->only('email', 'password');
        $token = null;
        try {
           if (!$token = JWTAuth::attempt($credentials)) {
            return response()->json(['invalid_email_or_password'], 422);
           }
        } catch (JWTAuthException $e) {
            return response()->json(['failed_to_create_token'], 500);
        }
        JWTAuth::setToken($token);
        return ['status' => true, 'session_token' => $token, 'user' => JWTAuth::toUser() ];
    }
    
    public function admin_register(Request $request){
        $admin = $this->admin->create([
          'name' => $request->get('name'),
          'email' => $request->get('email'),
          'password' => bcrypt($request->get('password'))
        ]);
        return response()->json(['status'=>true,'message'=>'User created successfully','data'=>$admin]);
    }
    
    public function admin_login(Request $request){
        \Log::Info("login");
        $credentials = $request->only('email', 'password');
        $token = null;
        try {
           if (!$token = JWTAuth::attempt($credentials)) {
            return response()->json(['invalid_email_or_password'], 422);
           }
        } catch (JWTAuthException $e) {
            return response()->json(['failed_to_create_token'], 500);
        }
        return ['status' => true, 'session_token' => $token ];
    }
    
}
