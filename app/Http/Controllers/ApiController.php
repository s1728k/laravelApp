<?php

namespace App\Http\Controllers;

use PDO;
use JWTAuth;
use App\App;
use App\Query;
use App\Traits\ScrapesWeb;
use App\Traits\StoresSessionTokens;
use App\Traits\CreatesModelClass;
use App\Traits\SqlQueries;
use App\Traits\FilesStore;
use App\Traits\ValidatesRequests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Config\Repository\Config;

class ApiController extends Controller
{
    use ScrapesWeb;
    use StoresSessionTokens;
    use CreatesModelClass;
    use SqlQueries;
    use FilesStore;
    use ValidatesRequests;

    public $app_id;
    public $con;

    private $table;
    private $fillables;
    private $hiddens;
    private $has_many;
    private $filters;
    private $dates;
    private $result;
    private $auth_user;
    private $app_auth_provider;

    public function __construct(Request $request)
    {
        $this->fillables = $request->query("fillables");
        $this->hiddens = $request->query("hiddens")?$request->query("hiddens"):[];
        $this->has_many = $request->query("has_many")?$request->query("has_many"):[];
        $this->filters = $request->query("filters");
        $this->dates = $request->query("dates");
    }

    public function junction(Request $request, $query_id, $id = null)
    {
        \Log::Info('junction');
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
            $filters = explode('|', $query->filters);
        }else{
            $filters = explode('|', $request->filter);
        }

        if(!$request->special){
            // $specials = explode(', ', $query->specials);
            // $special = $specials[0];
            $special = "";
        }else{
            $special = $request->special;
        }

        if($command == 'signup'){
            array_push($fillables, 'password');
            return $this->signup($request, $table, $fillables, $hiddens);
        }elseif($command == 'login'){
            array_push($fillables, 'password');
            return $this->login($request, $table, $fillables, $hiddens);
        }elseif($command == 'files_upload'){
            return $this->uploadFiles($request);
        }

        $table_class = $this->gtc($table, $fillables, $hiddens);

        if($command == 'all'){
            return $this->index($table, $fillables, $hiddens, $joins, $filters, $special);
        }elseif($command == 'new'){
            return $this->storeRecord($request, $table_class, $table, $mandatory);
        }elseif($command == 'get'){
            return $this->getRecord($table_class, $id, $joins);
        }elseif($command == 'mod'){
            return $this->updateRecord($request, $table_class, $table, $id, $mandatory);
        }elseif($command == 'del'){
            return $this->deleteRecord($table_class, $id);
        }
    }

    public function index($table, $fillables = [], $hiddens = [], $joins = [], $filters = [], $special = null)
    {
        \Log::Info(request()->ip()." end user requested list of records in app_id ".$this->app_id);
        $table_class = $this->gtc($table, $fillables, $hiddens);
        $query = $table_class::where('id','<>',0);
        foreach ($filters as $filter) {
            $f = explode(", ", $filter);
            if($f[0] == 'where'){
                $query = $query->where($f[1],$f[2],$f[3]);
            }elseif($f[0] == 'orWhere'){
                $query = $query->orWhere($f[1],$f[2],$f[3]);
            }
        }
        // $arr = $this->getFields($table, ['password', 'remember_token'], $this->app_id);
        // $query->select(array_intersect($arr,$fillables));
        if($special == 'pluck'){
            $res = $query->pluck($column);
        }elseif($special == 'count'){
            $res = ['count' => $query->count()];
        }elseif($special == 'max'){
            $res = ['max' => $query->max($column)];
        }elseif($special == 'min'){
            $res = ['min' => $query->min($column)];
        }elseif($special == 'avg'){
            $res = ['avg' => $query->avg($column)];
        }elseif($special == 'sum'){
            $res = ['sum' => $query->sum($column)];
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
        $res = $table_class::create($request->all())->id;
        $this->remModelClass($table_class);
        return ['id' => $res];
    }

    public function getRecord($table_class, $id, $joins = [])
    {
        \Log::Info(request()->ip()." end user requested get record in app_id ".$this->app_id);
        $res = $table_class::findOrFail($id);
        $this->remModelClass($table_class);
        return $res;
    }

    public function updateRecord($request, $table_class, $table, $id, $mandatory = [])
    {
        \Log::Info(request()->ip()." end user requested updated record in app_id ".$this->app_id);
        $this->validateGenericInputs($request, $table, ['id', 'created_at', 'updated_at'], $mandatory);
        $record = $table_class::findOrFail($id)->update($request->all());
        $this->remModelClass($table_class);
        return ['status' => 'success'];
    }

    public function deleteRecord($table_class, $id)
    {
        \Log::Info(request()->ip()." end user requested delete record in app_id ".$this->app_id);
        $record = $table_class::findOrFail($id);
        if($table_class::destroy($id)){
            $this->remModelClass($table_class);
            return ['status' => 'success'];
        }
    }

    public function signup($request, $author, $fillables, $hiddens)
    {
        \Log::Info(request()->ip()." end user registered app_id ".$this->app_id);
        $app = App::findOrFail($this->app_id);
        if($app->secret !== $request->secret){
            return ['status' => 'failed'];
        }
        $this->validateGenericInputs($request, $author, ['id', 'created_at', 'updated_at']);
        $table_class = $this->gtc($author, $fillables, $hiddens);
        $record = $table_class::create($request->all());
        $record->update(['password' => bcrypt($request->password)]);
        $this->remModelClass($table_class);
        return ['status' => 'success'];
    }

    public function login($request, $author, $fillables, $hiddens)
    {
        \Log::Info(request()->ip()." end user logged in app_id ".$this->app_id);
        $app = App::findOrFail($this->app_id);
        if($app->secret !== $request->secret){
            return ['status' => 'failed'];
        }
        $table_class = $this->gtc($author, $fillables, $hiddens);
        $record = $table_class::where(['email' => $request->email])->first();
        if (\Hash::check($request->password, $record->password)){
            $new_token = $this->getToken($this->app_id, $author, $record->id);
            $this->remModelClass($table_class);
            return ['status' => 'success', '_token' => $new_token, 'user' => $record];
        }else{
            $this->remModelClass($table_class);
            return ['status' => "failed"];
        }
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

    private function setTable($table)
    {
        $this->table = 'App\\Models\\'.ucwords(rtrim($table,'s'));
    }

    private function processTableForHasMany($table)
    {
        $this->table = $this->table->map(function ($itable) use ($table) {
            // $itable['price'] = Price::find($itable->price_id);
            foreach ($this->has_many as $key => $value) {
                if( strpos($value, '|') ){
                    $value = explode('|', $value);
                    $stable = 'App\\Models\\'.$this->auth_user->id.'\\'.$this->auth_user->active_app_id.ucwords(rtrim($value[0],'s'));
                    $itable[$value[1]] = $stable::where('pivot_table', $table)
                                                ->where('pivot_field', $value[1])
                                                ->where('pivot_id', $itable->id)->get();
                }else{
                    $stable = 'App\\Models\\'.$this->auth_user->id.'\\'.$this->auth_user->active_app_id.ucwords(rtrim($value,'s'));
                    $itable[$value] = $stable::where($table.'_id', $itable->id);
                }
            }
            return $itable;
        });
    }

    private function processRequestForDates()
    {
        $this->request = $this->request->map(function ($itable) {
            foreach ($this->dates as $key => $value) {
                $irequest[$key] = new \DateTime($irequest[$value]);
            }
            return $irequest;
        });
    }

    private function sp($auth_provider, $index)
    {
        $arr = explode('|', $auth_provider.'|');
        return $arr[$index];
    }

    private function routeChecker($app_id, $auth_provider, $secret)
    {
        if(!Schema::hasTable('app'.$app_id.'_'. $this->sp($auth_provider,0) )){
            return ['status' => 'failed'];
        }

        $app = App::findOrFail($app_id);

        if($app->secret !== $secret){
            return ['status' => 'failed'];
        }

        $arr = json_decode($app->auth_providers,true)??[];

        if(strpos($auth_provider, '|')){
            $ap = explode("|", $auth_provider);

            if( !in_array( $ap[1], $arr[$ap[0]] ) ){
                return ['status' => 'failed'];
            }
        }else{
            if( $arr[$auth_provider] !== "All Users"){
                return ['status' => 'failed'];
            }
        }

        $p = json_decode($app->permissions,true)??[];

        if(! $p['guest']['All Users'][$ap[0]]['p']){
            return ['status' => 'failed'];
        }
    }

    public function gtc($table, $fillables = null, $hiddens = null)
    {
        $table_name = $this->tClass($table);
        // $myFilePath = app_path() ."/Models/$table_name.php";
        // if(!file_exists($myFilePath)){
            $arr = json_decode(App::findOrFail($this->app_id)->auth_providers, true);
            $this->createModelClass($table, in_array($table, $arr), $fillables, $hiddens);
        // }
        return "App\\Models\\".$table_name;
    }

    private function remModelClass($table_class)
    {
        $myFilePath = base_path() .'/'.$table_class.'.php';
        if(is_writable($myFilePath)){
            unlink($myFilePath);
        }
    }

    public function tClass($table)
    {
        return ucwords(rtrim('app'.$this->app_id.'_'.$table,'s'));
    }

    private function table($table)
    {
        return 'app'.$this->app_id.'_'.$table;
    }

}