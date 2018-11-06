<?php

namespace App\Traits;

trait StoresSessionTokens
{

    public function getToken($app_id, $auth_provider, $id)
    {
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
        $tokens = $this->getFileContents($app_id, $auth_provider);

        if(!empty($tokens[$token.'_time'])){
            $time = $tokens[$token.'_time'];
            $curtime = time();
            if(($curtime-$time) > 1800) {     //1800 seconds
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

    public function getAuthId($app_id, $auth_provider, $token)
    {
        $tokens = $this->getFileContents($app_id, $auth_provider);
        return $tokens[$token];
    }

    private function getFileContents($app_id, $auth_provider)
    {
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
        $myfilepath = storage_path()."/honeyweb/".ucwords(rtrim('app'.$app_id.'_'.$auth_provider,'s')) .".php";
        $myfile = fopen($myfilepath, "w");
        fwrite($myfile, json_encode($tokens));
        fclose($myfile);
    }
}
