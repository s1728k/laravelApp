<?php

namespace App\Http\Controllers;

use App\App;
use App\Query;
use App\Log;
use App\Mail\CommonMail;
use App\Traits\ScrapesWeb;
use App\Traits\StoresSessionTokens;
use App\Traits\CreatesModelClass;
use App\Traits\SqlQueries;
use App\Traits\FilesStore;
use App\Traits\ValidatesRequests;
use App\Traits\UtilityFunctions;
use App\Traits\SendsChatMessages;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class ApiController extends Controller
{
    use ScrapesWeb;
    use StoresSessionTokens;
    use CreatesModelClass;
    use SqlQueries;
    use FilesStore;
    use ValidatesRequests;
    use UtilityFunctions;
    use SendsChatMessages;

    public $con;
    public $app_id;
    public $app;
    public $aid;
    public $fid;
    public $fap;
    public $fname;
    public $can_chat_with;
    public $chat_admins;
    public $chat_friends;
    public $fc;

    public function __construct(Request $request)
    {
        $this->fc = "ApiController::";
        \Log::Info($this->fc.'__construct');
        if(empty($request->route('query_id'))){
            if($request->_token){
                $this->getAuth($request->_token);
            }else{
                $this->aid = $request->app_id;
                $this->fap = $request->fap;
                $this->fname = $request->fname??'Guest';
            }
        }else{
            $this->getAuth($request->_token);
        }
    }

    public function junction(Request $request, $query_id, $id = null)
    {
        \Log::Info($this->fc.'junction');
        $query = Query::findOrFail($query_id);
        $this->app_id = $query->app_id;
        $this->con = App::findOrFail($this->app_id)->db_connection;

        if(!$request->author){
            $authors = explode(', ', $query->auth_providers);
            $author = $authors[0];
        }else{
            $author = $request->author;
        }

        if(!$request->table){
            $tables = explode(', ', $query->tables);
            $table = $tables[0];
        }else{
            $table = $request->table;
        }
        \Log::Info($table);

        if(!$request->command){
            $commands = explode(', ', $query->commands);
            $command = $commands[0];
        }else{
            $command = $request->command;
        }

        if(!$request->fillable){
            $fillables = explode(', ', $query->fillables);
        }else{
            $fillables = explode(',', $request->fillable);
        }

        if(!$request->hidden){
            $hiddens = explode(', ', $query->hiddens);
        }else{
            $hiddens = explode(',', $request->hidden);
        }

        if(!$request->mandatory){
            $mandatory = explode(', ', $query->mandatory);
        }else{
            $mandatory = explode(',', $request->mandatory);
        }

        if(!$request->join){
            $joins = explode('|', $query->joins);
        }else{
            $joins = explode('|', $request->join);
        }

        if(!$request->filter){
            $filters = $query->filters?explode('|', $query->filters):[];
        }else{
            $filters = explode('|', $request->filter);
        }

        if(!$request->special){
            $specials = explode(', ', $query->specials);
        }

        Log::create([
            'aid'=>$this->app_id, 
            'fid'=>$this->fid, 
            'fap'=>$this->fap, 
            'qid'=>$query_id, 
            'query_nick_name' => $query->name,
            'auth_provider'=>$author, 
            'table_name'=>$table, 
            'command'=>$command, 
            'ip'=>request()->ip(),
        ]);

        if($command == 'signup'){
            array_push($fillables, 'password');
            return $this->signup($request, $table, $fillables, $hiddens);
        }elseif($command == 'login'){
            array_push($fillables, 'password');
            return $this->login($request, $table, $fillables, $hiddens);
        }elseif($command == 'clogin'){
            array_push($fillables, 'password');
            return $this->clogin($request, $table, $fillables, $hiddens);
        }elseif($command == 'files_upload'){
            return $this->uploadFiles($request);
        }elseif($command == 'email'){
            return $this->sendMail($request);
        }elseif($command == 'ps'){
            return $this->savePushSubscription($request);
        }

        $table_class = $this->gtc($table, $fillables, $hiddens);

        if($command == 'all'){
            return $this->index($request, $table_class, $joins, $filters, $special);
        }elseif($command == 'new'){
            return $this->storeRecord($request, $table_class, $table, $mandatory);
        }elseif($command == 'get'){
            return $this->getRecord($table_class, $id, $joins, $filters);
        }elseif($command == 'mod'){
            return $this->updateRecord($request, $table_class, $table, $id, $mandatory);
        }elseif($command == 'del'){
            return $this->deleteRecord($table_class, $id);
        }elseif($command == 'sevc'){
            return $this->sendEmailVerificationCode($request, $table_class);
        }elseif($command == 've'){
            return $this->emailVerify($request, $table_class);
        }
    }

    public function index($request, $table_class, $joins = [], $filters = [], $special = [])
    {
        \Log::Info(request()->ip()." end user requested list of records in app_id ".$this->app_id);
        $query = $table_class::where('id','<>',0);
        $query = $this->whereFilters($query, $filters);

        if( !empty($request->_pluck) && in_array('_pluck', $special) ){
            $res = $query->pluck($request->_pluck);
        }elseif( !empty($request->_count) && in_array('_count', $special) ){
            $res = $query->pluck($request->_count);
        }elseif( !empty($request->_max) && in_array('_max', $special) ){
            $res = $query->pluck($request->_max);
        }elseif( !empty($request->_min) && in_array('_min', $special) ){
            $res = $query->pluck($request->_min);
        }elseif( !empty($request->_avg) && in_array('_avg', $special) ){
            $res = $query->pluck($request->_avg);
        }elseif( !empty($request->_sum) && in_array('_sum', $special) ){
            $res = $query->pluck($request->_sum);
        }else{
            $res = $query->get();
        }
        $this->remModelClass($table_class);
        return $res;
    }

    public function storeRecord($request, $table_class, $table, $mandatory = [])
    {
        \Log::Info(request()->ip()." end user requested store record in app_id ".$this->app_id);
        $this->validateGenericInputs($request, $table, ['id', 'created_at', 'updated_at'], $mandatory);
        $res = $table_class::create($request->all());
        $this->remModelClass($table_class);
        return $res;
    }

    public function getRecord($table_class, $id, $joins = [], $filters = [])
    {
        \Log::Info(request()->ip()." end user requested get record in app_id ".$this->app_id);
        $query = $table_class::where('id',$id);
        $query = $this->whereFilters($query, $filters);
        $this->remModelClass($table_class);
        $res = $query->first();
        return $res??['error'=>'un-authorizeds'];
    }

    public function updateRecord($request, $table_class, $table, $id, $mandatory = [])
    {
        \Log::Info(request()->ip()." end user requested updated record in app_id ".$this->app_id);
        $this->validateGenericInputs($request, $table, ['id', 'created_at', 'updated_at'], $mandatory);
        $table_class::findOrFail($id)->update($request->all());
        $this->remModelClass($table_class);
        return ['message' => "record updated!"];
    }

    public function deleteRecord($table_class, $id)
    {
        \Log::Info(request()->ip()." end user requested delete record in app_id ".$this->app_id);
        $record = $table_class::findOrFail($id);
        if($table_class::destroy($id)){
            $this->remModelClass($table_class);
            return ['id' => $id];
        }
    }

    public function signup($request, $author, $fillables, $hiddens)
    {
        \Log::Info(request()->ip()." end user registered app_id ".$this->app_id);
        $this->validateGenericInputs($request, $author, ['id', 'created_at', 'updated_at'],[],true);
        $table_class = $this->gtc($author, $fillables, $hiddens);
        $record = $table_class::create($request->all());
        $record->update(['password' => bcrypt($request->password)]);
        $this->remModelClass($table_class);
        return ['status' => 'success', 'user' => $record];
    }

    public function sendEmailVerificationCode($request, $table_class)
    {
        \Log::Info(request()->ip()." end user registered app_id ".$this->app_id);
        $record = $table_class::find($request->id);
        if(empty($record)){
            return ["message" => "record does not exists"];
        }
        $code = mt_rand(100000, 999999);
        $record->update(['email_verification' => $code]);
        $record->save();
        if(!empty($record->email)){
            Mail::to($record->email)->send(new CommonMail([
                "Verification Code" => $code,
            ]));
        }
        return ['message' => 'email verification code sent successfull'];
    }

    public function emailVerify($request, $table_class)
    {
        $record = $table_class::find($request->id);
        if(empty($record)){
            return ["message" => "record does not exists"];
        }
        if($record->email_verification == $request->code){
            $record->update(['email_verification' => 'done']);
            $record->save();
            return ["message" => "email verification successfull"];
        }else{
            return ["message" => "email verification failed"];
        }
    }

    public function login($request, $author, $fillables, $hiddens)
    {
        \Log::Info(request()->ip()." end user logged in app_id ".$this->app_id);
        $this->validateGenericInputs($request, $author, ['id', 'created_at', 'updated_at']);
        $table_class = $this->gtc($author, $fillables, $hiddens);
        $record = $table_class::where(['email' => $request->email])->first();
        if(!empty($record)){
            if (\Hash::check($request->password, $record->password)){
                $new_token = $this->createSessionToken($request, $this->app_id, $author, $record->id, $record->name);
                $this->remModelClass($table_class);
                return ['status' => "success", '_token' => $new_token, 'user' => $record];
            }else{
                $this->remModelClass($table_class);
                return ['message' => "incorrect password"];
            }
        }
        return ['message' => "email address does not exists"];
    }

    public function clogin($request, $author, $fillables, $hiddens)
    {
        \Log::Info(request()->ip()." end user logged in app_id ".$this->app_id);
        $this->validateGenericInputs($request, $author, ['id', 'created_at', 'updated_at']);
        $table_class = $this->gtc($author, $fillables, $hiddens);
        $record = $table_class::where(['email' => $request->email])->first();
        if(!empty($record)){
            if($record->email_verification == 'done'){
                if (\Hash::check($request->password, $record->password)){
                    $new_token = $this->createSessionToken($request, $this->app_id, $author, $record->id, $record->name);
                    $this->remModelClass($table_class);
                    return ['status' => "success", '_token' => $new_token, 'user' => $record];
                }else{
                    $this->remModelClass($table_class);
                    return ['message' => "incorrect password"];
                }
            }
            $this->remModelClass($table_class);
            return ['message' => "email address not verified"];
        }
        $this->remModelClass($table_class);
        return ['message' => "email address does not exists"];
    }

    public function uploadFiles($request)
    {
        \Log::Info(request()->ip()." uploaded files for app id ".$this->app_id);
        $files = $request->file('files');
        $res = [];
        if($request->hasFile('files'))
        {
            $table = 'App\\File';
            foreach ($files as $key => $file) {
                $path = $file->store('public');
                $res[] = $table::create([
                    'app_id' => $this->app_id,
                    'name' => $file->getClientOriginalName(),
                    'mime' => $file->getMimeType(),
                    'size' => $file->getSize(),
                    'path' => env('APP_URL').str_replace('public','/public/storage',$path),
                ]);
            }
        }
        return $res;
    }

    public function sendMail($request)
    {
        Mail::to($request->to)->send(new CommonMail($request->message));
        return ['message' => 'mail successfully sent'];
    }

    public function savePushSubscription($request)
    {
        $table = "App\\PushSubscription";
        $exists = $table::where('subscription', json_encode($request->subscription))->first();
        if(!$exists){
            $table::create(['app_id' => $this->app_id, 'subscription' => json_encode($request->subscription)]);
            return ['message' => 'successfully saved'];
        }else{
            return ['message' => 'already saved'];
        }
    }

}