<?php

namespace App\Http\Middleware;

use Closure;
use App\App;
use App\Traits\StoresSessionTokens;
use Illuminate\Support\Facades\Schema;

class Authenticate
{
    use StoresSessionTokens;
    
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $guard
     * @return mixed
     */
    public function handle($request, Closure $next)
    {   
        
        if(!Schema::hasTable('app'.$request->route('app_id').'_'.$request->route('auth_provider'))){
            return response()->json(['status' => 'table not found']);
        }
        $app = App::findOrFail($request->route('app_id'));
        if(!in_array($request->route('auth_provider'), json_decode($app->auth_providers,true)??[])){
            return response()->json(['status' => 'auth not found']);
        }
        $res = $this->refreshToken($request->route('app_id'), $request->route('auth_provider'), $request->_token);
        if($res['status'] !== 'success'){
            return response()->json($res);
        }

        \Log::Info("Origin:".$request->header("Origin"));
        // \Log::Info(\Route::getFacadeRoot()->current()->uri());
        
        // if($request->header("Origin") != 'http://localhost:4200'){
        //     return response()->json(['error'=>'unknown origin']);
        // }

        // return $next($request);
        $response = $next($request);
        $data = json_decode($response->Content());
        if(is_array($data)){
            return response()->json(['status' => 'success', 'data' => $data, '_token' => $res['_token']]);
        }else{
            $data->_token=$res['_token'];
            return response()->json($data);
        }

    }
    
}
