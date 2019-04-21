<?php

namespace App\Http\Middleware;

use Closure;
use App\Query;
use App\App;
use App\Traits\StoresSessionTokens;
use Illuminate\Support\Facades\Schema;

class Authenticate
{
    use StoresSessionTokens;

    public $con;
    public $app_id;
    public $app;
    public $aid;
    public $fid;
    public $fap;
    public $fname;

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
        if(empty($request->route('query_id'))){
            return $next($request);
        }
        
        $query = Query::find($request->route('query_id'));
        if(empty($query)){
            return response()->json(['message' => 'unknown request'], 404);
        }
        $app = App::findOrFail($query->app_id);

        $authors = explode(', ', $query->auth_providers);
        if($request->author){
            if(!in_array($request->author, $authors)){
                return response()->json(['message' => 'un-authorized'], 401);
            }
            $author = $request->author;
        }else{
            $author = $authors[0];
        }

        if($request->table){
            $tables = explode(', ', $query->tables);
            if(!in_array($request->table, $tables)){
                return response()->json(['message' => 'un-authorized'], 401);
            }
        }

        $commands = explode(', ', $query->commands);
        if($request->command){
            $commands = explode(', ', $query->commands);
            if(!in_array($request->command, $commands)){
                return response()->json(['message' => 'un-authorized'], 401);
            }
            $command = $request->command;
        }else{
            $command = $commands[0];
        }
        
        if(in_array($command, ['new','signup','ve','sevc','login','clogin','files_upload','mail','ps','prc'])){
            if(strtolower($request->method()) != 'post'){
                return response()->json(['message' => 'methodNotAllowed'], 405);
            }
        }elseif($command == 'mod' || $command == 'refresh'){
            if(strtolower($request->method()) != 'put'){
                return response()->json(['message' => 'methodNotAllowed'], 405);
            }
        }elseif($command == 'del'){
            if(strtolower($request->method()) != 'delete'){
                return response()->json(['message' => 'methodNotAllowed'], 405);
            }
        }

        if(in_array($command, ['signup','ve','sevc','login','clogin'])){
            if($app->secret !== $request->secret){
                return response()->json(['message' => 'un-authorized'], 401);
            }
        }elseif($command == 'secret'){
            return response($app->secret);
        }

        if($request->hidden){
            $hiddens = explode(', ', $query->hiddens);
            $arr = explode(',', $request->hidden);
            if(array_intersect($hiddens, $arr) !== $hiddens){
                return response()->json(['message' => 'un-authorized'], 401);
            }
        }

        if($request->special){
            $specials = explode(', ', $query->specials);
            if(!in_array($request->special, $specials)){
                return response()->json(['message' => 'un-authorized'], 401);
            }
        }
    
        $origins = json_decode($app->origins, true)??[];

        if(!in_array($request->header("Origin"), $origins)){
            \Log::Info("Origin:".$request->header("Origin"));
            return response()->json('oops! something is wrong');
        }

        if($author !== 'guest'){
            if($command == 'refresh'){
                return $this->refreshSessionToken($request->_token, $app->token_lifetime);
            }else{
                $app_id = $this->checkSessionToken($request->_token);
                if(!is_numeric($app_id)){ return $app_id; }
            }
        }

        return $next($request);
    }
    
}
