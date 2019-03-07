<?php

namespace App\Http\Middleware;

use Closure;
use App\Query;
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
        $query = Query::find($request->route('query_id'));
        if(empty($query)){
            return response()->json(['error' => 'unknown request']);
        }
        $app_id = $query->app_id;

        $authors = explode(', ', $query->auth_providers);
        if($request->author){
            if(!in_array($request->author, $authors)){
                return response()->json(['error' => 'un-authorized']);
            }
            $author = $request->author;
        }else{
            $author = $authors[0];
        }

        if($request->table){
            $tables = explode(', ', $query->tables);
            if(!in_array($request->table, $tables)){
                return response()->json(['error' => 'un-authorized']);
            }
        }

        $commands = explode(', ', $query->commands);
        if($request->command){
            $commands = explode(', ', $query->commands);
            if(!in_array($request->command, $commands)){
                return response()->json(['error' => 'un-authorized']);
            }
            $command = $request->command;
        }else{
            $command = $commands[0];
        }
        
        if(in_array($command, ['new','signup','login','files_upload','ps','prc'])){
            if(strtolower($request->method()) != 'post'){
                return response()->json(['error' => 'methodNotAllowed']);
            }
        }elseif($command == 'mod'){
            if(strtolower($request->method()) != 'put'){
                return response()->json(['error' => 'methodNotAllowed']);
            }
        }elseif($command == 'del'){
            if(strtolower($request->method()) != 'delete'){
                return response()->json(['error' => 'methodNotAllowed']);
            }
        }

        if($command=='signup' || $command == 'login'){
            $app = ('App\\App')::findOrFail($query->app_id);
            if($app->secret !== $request->secret){
                return ['error' => 'un-authorized'];
            }
        }

        if($request->hidden){
            $hiddens = explode(', ', $query->hiddens);
            $arr = explode(',', $request->hidden);
            if(array_intersect($hiddens, $arr) !== $hiddens){
                return response()->json(['error' => 'un-authorized']);
            }
        }

        if($request->special){
            $specials = explode(', ', $query->specials);
            if(!in_array($request->special, $specials)){
                return response()->json(['error' => 'un-authorized']);
            }
        }

        if($author !== 'guest'){
            $res = $this->refreshToken($app_id, $author, $request->_token);
            if($res['status'] !== 'success'){
                return response()->json($res);
            }
        }else{
            $res = ['_token' => ''];
        }
        
        \Log::Info("Origin:".$request->header("Origin"));
        
        // if($request->header("Origin") != 'http://localhost:4200'){
        //     return response()->json(['error'=>'unknown origin']);
        // }

        // return $next($request);
        $response = $next($request);
        $data = json_decode($response->Content());
        if($command == 'login'){
            return response()->json($data);
        }elseif($command == 'get' || $command == 'all'){
            if(!empty($data->error)){
                return response()->json(['status' => 'error', 'error' => $data->error, '_token' => $res['_token']]);
            }
        }
        return response()->json(['status' => 'success', 'data' => $data, '_token' => $res['_token']]);
    }
    
}
