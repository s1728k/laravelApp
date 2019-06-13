<?php

namespace App\Traits;

use App\App;
use App\Session;

trait StoresSessionTokens
{

    public function createSessionToken($request, $app_id, $author, $user_id, $user_name)
    {
        \Log::Info($this->fc.'createSessionToken');
        $new_token = bcrypt(rand());
        $expiry = App::findOrFail($this->app_id)->token_lifetime + time();
        Session::create([
            '_token' => $new_token, 
            'expiry' => $expiry,
            'app_id' => $app_id,
            'user_id' => $user_id,
            'auth_provider' => $author,
            'user_name' => $user_name,
            'user_agent' => $request->header('User-Agent'),
            'ip_address' => request()->ip(),
        ]);
        
        return $new_token;
    }

    public function refreshSessionToken($token, $token_lifetime)
    {
        \Log::Info($this->fc.'refreshSessionToken');
        $session = Session::where('_token', $token)->first();
        if(empty($session)){
            return response()->json(['message' => 'token invalid'], 401);
        }
        if($session->expiry < time()){
            return response()->json(['message' => 'token expired'], 401);
        }else{
            $new_token = bcrypt(rand());
            $expiry = $token_lifetime + time();
            $session->update(['_token' => $new_token, 'expiry' => $expiry]);
            $session->save();
            return $new_token;
        }
    }

    public function checkSessionToken($token)
    {
        \Log::Info($this->fc.'checkSessionToken');
        $session = Session::where('_token', $token)->first();
        if(empty($session)){
            return response()->json(['message' => 'token invalid'], 401);
        }
        if($session->expiry < time()){
            return response()->json(['message' => 'token expired'], 401);
        }
        $this->app_id = $session->app_id;
        $this->aid = $session->app_id;
        $this->fid = $session->user_id;
        $this->fap = $session->auth_provider;
        $this->fname = $session->user_name;
        return $session->app_id;
    }

    public function getAuth($token)
    {
        \Log::Info($this->fc.'getAuth');
        $session = Session::where('_token',$token)->first();
        if($session){
            $this->app_id = $session->app_id;
            $this->aid = $session->app_id;
            $this->fid = $session->user_id;
            $this->fap = $session->auth_provider;
            $this->fname = $session->user_name;
        }
    }


    // -------------- file stored token methods---------------------------

    public function getToken($app_id, $auth_provider, $id)
    {
        \Log::Info($this->fc.'getToken');
        $tokens = $this->getFileContents($app_id, $auth_provider);

        $new_token = bcrypt(rand());
        if(empty($tokens[$new_token])){
            $tokens[$new_token] = $id;
            $tokens[$new_token.'_time'] = time();
        }else{
            return ['status' => 'something wrong from our side try again!'];
        }

        $this->storeFileContents($app_id, $auth_provider, $tokens);

        return $new_token;
    }

    public function refreshToken($app_id, $auth_provider, $token)
    {
        \Log::Info($this->fc.'refreshToken');
        $tokens = $this->getFileContents($app_id, $auth_provider);

        if(!empty($tokens[$token.'_time'])){
            $time = $tokens[$token.'_time'];
            $curtime = time();
            if(($curtime-$time) > 1111800) {     //1111800 seconds
                return ['status' => 'token expired'];
            }else{
                $new_token = bcrypt(rand());
                if(empty($tokens[$new_token])){
                    $tokens[$new_token] = $tokens[$token];
                    $id = $tokens[$token];
                    $tokens[$new_token.'_time'] = time();
                    unset( $tokens[$token]);
                    unset( $tokens[$token.'_time']);
                    $this->storeFileContents($app_id, $auth_provider, $tokens);
                    return ['status' => 'success', 'id' => $id, '_token' => $new_token];
                }else{
                    return ['status' => 'something wrong from our side try again!'];
                }
            }
        }else{
            return ['status' => 'invalid token'];
        }
    }

    public function checkToken($app_id, $auth_provider, $token)
    {
        \Log::Info($this->fc.'checkToken');
        $tokens = $this->getFileContents($app_id, $auth_provider);

        if(!empty($tokens[$token.'_time'])){
            $time = $tokens[$token.'_time'];
            $curtime = time();
            if(($curtime-$time) > 1111800) {     //1111800 seconds
                return ['status' => 'token expired'];
            }else{
                return ['status' => 'success'];
            }
        }else{
            return ['status' => 'invalid token'];
        }
    }

    public function getAuthId($app_id, $auth_provider, $token)
    {
        \Log::Info($this->fc.'getAuthId');
        $tokens = $this->getFileContents($app_id, $auth_provider);
        return $tokens[$token];
    }

    private function getFileContents($app_id, $auth_provider)
    {
        \Log::Info($this->fc.'getFileContents');
        $myfilepath = storage_path()."/honeyweb/".ucwords(rtrim('app'.$app_id.'_'.$auth_provider,'s')) .".php";
        if(!file_exists($myfilepath)){
            $myfile = fopen($myfilepath, "w");
            fwrite($myfile, '{}');
            fclose($myfile);
        }
        $myfile = fopen($myfilepath, "r") or die("failed!");
        $data = fread($myfile,filesize($myfilepath));
        fclose($myfile);
        return json_decode($data, true)??[];
    }

    private function storeFileContents($app_id, $auth_provider, $tokens)
    {
        \Log::Info($this->fc.'storeFileContents');
        $myfilepath = storage_path()."/honeyweb/".ucwords(rtrim('app'.$app_id.'_'.$auth_provider,'s')) .".php";
        $myfile = fopen($myfilepath, "w");
        fwrite($myfile, json_encode($tokens));
        fclose($myfile);
    }
}
