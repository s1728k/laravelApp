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
        $query = Query::findOrFail($request->route('query_id'));
        $app_id = $query->app_id;

        $authors = explode(', ', $query->auth_providers);
        if($request->author){
            if(!in_array($request->author, $authors)){
                return response()->json(['status' => 'un-authorized']);
            }
            $author = $request->author;
        }else{
            $author = $authors[0];
        }

        if($request->table){
            $tables = explode(', ', $query->tables);
            if(!in_array($request->table, $tables)){
                return response()->json(['status' => 'un-authorized']);
            }
        }

        $commands = explode(', ', $query->commands);
        if($request->command){
            $commands = explode(', ', $query->commands);
            if(!in_array($request->command, $commands)){
                return response()->json(['status' => 'un-authorized']);
            }
            $command = $request->command;
        }else{
            $command = $commands[0];
        }
        
        if($command=='new' || $command=='signup' || $command=='login' || $command=='files_upload' ){
            if(strtolower($request->method()) != 'post'){
                return response()->json(['status' => 'methodNotAllowed']);
            }
        }elseif($command == 'mod'){
            if(strtolower($request->method()) != 'put'){
                return response()->json(['status' => 'methodNotAllowed']);
            }
        }elseif($command == 'del'){
            if(strtolower($request->method()) != 'delete'){
                return response()->json(['status' => 'methodNotAllowed']);
            }
        }

        if($request->visible){
            $visibles = explode(', ', $query->visibles);
            $arr = explode(',', $request->visible);
            if(array_intersect($arr, $visibles) !== $arr){
                return response()->json(['status' => 'un-authorized']);
            }
        }

        if($request->hidden){
            $hiddens = explode(', ', $query->hiddens);
            $arr = explode(',', $request->hidden);
            if(array_intersect($hiddens, $arr) !== $hiddens){
                return response()->json(['status' => 'un-authorized']);
            }
        }

        if($request->special){
            $specials = explode(', ', $query->specials);
            if(!in_array($request->special, $specials)){
                return response()->json(['status' => 'un-authorized']);
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
        if(is_array($data)){
            return response(['status' => 'success', 'data' => $data, '_token' => $res['_token']])->withHeaders([
                'Content-Type' => 'application/x-www-form-urlencoded',
                'X-Header-One' => 'Header Value',
                'X-Header-Two' => 'Header Value',
            ]);
        }else{
            $data->_token=$data->_token??$res['_token'];
            return response($data)->withHeaders([
                'Content-Type' => 'application/x-www-form-urlencoded',
                'X-Header-One' => 'Header Value',
                'X-Header-Two' => 'Header Value',
            ]);;
        }

    }
    
}
