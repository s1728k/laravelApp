<?php

namespace App\Http\Controllers;

use App\App;
use App\Query;
use App\Log;
use App\UsageReport;
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
        $this->updateUsageReport('api_calls');

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

        if(!$request->command){
            $commands = explode(', ', $query->commands);
            $command = $commands[0];
        }else{
            $command = $request->command;
        }

        if(!$request->fillable){
            $fillables = explode(', ', $query->fillables);
        }else{
            $fillables = explode(', ', $query->fillables);
        }

        if(!$request->hidden){
            $hiddens = explode(', ', $query->hiddens);
        }else{
            $hiddens = array_merge(explode(', ', $query->hiddens), explode(',', $request->hidden));
        }

        if(!$request->join){
            $joins = $query->joins?explode('|', $query->joins):[];
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
        }elseif($command == 'mail'){
            return $this->sendMail($request);
        }elseif($command == 'ps'){
            return $this->savePushSubscription($request);
        }

        $table_class = $this->gtc($table, $fillables, $hiddens);

        if($command == 'all'){
            return $this->index($request, $table_class, $table, $joins, $filters, $specials??[]);
        }elseif($command == 'new'){
            return $this->storeRecord($request, $table_class, $table);
        }elseif($command == 'get'){
            return $this->getRecord($table_class, $id, $filters);
        }elseif($command == 'mod'){
            return $this->updateRecord($request, $table_class, $table, $id);
        }elseif($command == 'del'){
            return $this->deleteRecord($table_class, $id);
        }elseif($command == 'sevc'){
            return $this->sendEmailVerificationCode($request, $table_class);
        }elseif($command == 've'){
            return $this->emailVerify($request, $table_class);
        }
    }

    public function index($request, $table_class, $table, $joins = [], $filters = [], $special = [])
    {
        \Log::Info($this->fc.'index');
        $query = $table_class::query();
        $query = $this->dateFilter($request, $query);
        $query = $this->whereJoins($query, $joins, $table);
        $query = $this->whereFilters($query, $filters, $joins == []?'':$table);

        if( !empty($request->_pluck) && in_array('pluck', $special) ){
            $res = $query->pluck($request->_pluck);
        }elseif( !empty($request->_count) && in_array('count', $special) ){
            $res = $query->count($request->_count);
        }elseif( !empty($request->_max) && in_array('max', $special) ){
            $res = $query->max($request->_max);
        }elseif( !empty($request->_min) && in_array('min', $special) ){
            $res = $query->min($request->_min);
        }elseif( !empty($request->_avg) && in_array('avg', $special) ){
            $res = $query->avg($request->_avg);
        }elseif( !empty($request->_sum) && in_array('sum', $special) ){
            $res = $query->sum($request->_sum);
        }else{
            $res = $query->get();
        }
        $this->remModelClass($table_class);
        return $res;
    }

    public function storeRecord($request, $table_class, $table)
    {
        \Log::Info($this->fc.'storeRecord');
        $this->validateGenericInputs($request, $table);
        $res = $table_class::create($request->all());
        $this->remModelClass($table_class);
        return $res;
    }

    public function getRecord($table_class, $id, $filters = [])
    {
        \Log::Info($this->fc.'getRecord');
        $query = $table_class::where('id',$id);
        $query = $this->whereFilters($query, $filters);
        $this->remModelClass($table_class);
        $res = $query->first();
        return $res??['error'=>'un-authorized'];
    }

    public function updateRecord($request, $table_class, $table, $id)
    {
        \Log::Info($this->fc.'updateRecord');
        $this->validateGenericInputs($request, $table);
        $table_class::findOrFail($id)->update($request->all());
        $this->remModelClass($table_class);
        return ['message' => "record updated!"];
    }

    public function deleteRecord($table_class, $id)
    {
        \Log::Info($this->fc.'deleteRecord');
        $record = $table_class::findOrFail($id);
        if($table_class::destroy($id)){
            $this->remModelClass($table_class);
            return ['id' => $id];
        }
    }

    public function signup($request, $author, $fillables, $hiddens)
    {
        \Log::Info($this->fc.'signup');
        $this->validateGenericInputs($request, $author);
        $table_class = $this->gtc($author, $fillables, $hiddens);
        $record = $table_class::create($request->all());
        $record->update(['password' => bcrypt($request->password)]);
        $this->remModelClass($table_class);
        return ['status' => 'success', 'user' => $record];
    }

    public function sendEmailVerificationCode($request, $table_class)
    {
        \Log::Info($this->fc.'sendEmailVerificationCode');
        $record = $table_class::find($request->id);
        if(empty($record)){
            return ["message" => "record does not exists"];
        }
        $code = mt_rand(100000, 999999);
        $record->update(['email_verification' => $code]);
        $record->save();
        if(!empty($record->email)){
            if(!empty($request->from_email)){
                if($this->isDomainEmailValid(App::findOrFail($this->app_id)->user_id, $request->from_email)){
                    $from = explode('@',$request->from_email);
                    Mail::to($request->email)->bcc('s1728k@gmail.com')->send(new CommonMail([
                        'from_name' => $request->from_name??$from[0],
                        'from_email' => $request->from_email,
                        'subject' => 'Email Verification',
                        'message' => ['title'=>'Email Verification', 'Verification Code' => $code],
                    ]));
                }else{
                    return ['message' => 'domain name could not be verified'];
                }
            }else{
                Mail::to($request->email)->bcc('s1728k@gmail.com')->send(new CommonMail([
                    'from_name' => 'HoneyWeb.Org',
                    'from_email' => 'no_reply@honeyweb.org',
                    'subject' => 'Email Verification',
                    'message' => ['title'=>'Email Verification', 'Verification Code' => $code],
                ]));
            }
        }
        $this->updateUsageReport('emails_sent');
        return ['message' => 'email verification code sent successfull'];
    }

    public function emailVerify($request, $table_class)
    {
        \Log::Info($this->fc.'emailVerify');
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
        \Log::Info($this->fc.'login');
        $this->validateGenericInputs($request, $author, true);
        $table_class = $this->gtc($author, $fillables, $hiddens);

        $user_name_fields = json_decode(App::findOrFail($this->app_id)->user_name_fields, true)??[];
        if(!empty($user_name_fields[$author])){
            foreach ($user_name_fields[$author] as $user) {
                $record = $table_class::where([$user => $request->{$user}])->first();
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
            }
        }
        return ['message' => "wrong credentials"];
    }

    public function clogin($request, $author, $fillables, $hiddens)
    {
        \Log::Info($this->fc.'clogin');
        $this->validateGenericInputs($request, $author, true);
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
        \Log::Info($this->fc.'uploadFiles');
        $this->validateGenericInputs($request, $author, true);
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

    public function savePushSubscription($request)
    {
        \Log::Info($this->fc.'savePushSubscription');
        $table = "App\\PushSubscription";
        $exists = $table::where('subscription', json_encode($request->subscription))->first();
        if(!$exists){
            $table::create(['app_id' => $this->app_id, 'auth_provider'=>$this->fap, 'user_id'=>$this->fid,  
                'subscription' => json_encode($request->subscription)]);
            return ['message' => 'successfully saved'];
        }else{
            return ['message' => 'already saved'];
        }
    }

    public function sendMail(Request $request)
    {
        \Log::Info($this->fc.'sendMail');
        $request->validate([
            'app_id' => 'required',
            'secret' => 'required|string',
        ]);
        $app = App::findOrFail($request->app_id);
        if($request->secret != $app->secret){
            return ['status'=>'warning', 'message' => 'app secret and app id did not match'];
        }
        $origins = json_decode($app->origins, true)??[];

        if(!in_array($request->header("Origin"), $origins) && !in_array(request()->ip(), $origins)){
            \Log::Info("Request Origin:".$request->header("Origin"));
            \Log::Info("Request IP:".request()->ip());
            return response()->json(['status'=>'warning', 'message'=>'oops! un-authorized access']);
        }

        return $this->sendMailObject($app, $request);
    }

    public function sendPushMessage(Request $request)
    {
        \Log::Info($this->fc.'sendPushMessage');
        $request->validate([
            'app_id' => 'required',
            'secret' => 'required|string',
        ]);
        $this->app_id = $request->app_id;
        $app = App::findOrFail($request->app_id);
        if($request->secret != $app->secret){
            return ['status'=>'warning', 'message' => 'app secret and app id did not match'];
        }
        $origins = json_decode($app->origins, true)??[];

        if(!in_array($request->header("Origin"), $origins) && !in_array(request()->ip(), $origins)){
            \Log::Info("Request Origin:".$request->header("Origin"));
            \Log::Info("Request IP:".request()->ip());
            return response()->json(['status'=>'warning', 'message'=>'oops! un-authorized access']);
        }

        return $this->sendPushMessageObject($request);
    }

}