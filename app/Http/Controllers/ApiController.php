<?php

namespace App\Http\Controllers;

use PDO;
use JWTAuth;
use App\App;
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
    use StoresSessionTokens;
    use CreatesModelClass;
    use SqlQueries;
    use FilesStore;
    use ValidatesRequests;

    public $app_id;

    private $table;
    private $visibles;
    private $hiddens;
    private $has_many;
    private $filters;
    private $dates;
    private $result;
    private $auth_user;
    private $app_auth_provider;

    public function __construct(Request $request)
    {
        $this->visibles = $request->query("visibles");
        $this->hiddens = $request->query("hiddens")?$request->query("hiddens"):[];
        $this->has_many = $request->query("has_many")?$request->query("has_many"):[];
        $this->filters = $request->query("filters");
        $this->dates = $request->query("dates");
    }

    public function register(Request $request, $app_id, $auth_provider)
    {
        \Log::Info(request()->ip()." end user registered app_id ".$app_id);
        $this->routeChecker($app_id, $auth_provider, $request->secret);
        $this->app_id = $app_id;
        $table = $this->gtc($auth_provider);
        $this->validateGenericInputs($request, $auth_provider, ['id', 'created_at', 'updated_at']);
        $id = $table::create($request->all())->id;
        $table::findOrFail($id)->update(['password' => bcrypt($request->password)]);
        return ['status' => 'success'];
    }

    public function login(Request $request, $app_id, $auth_provider)
    {
        $this->routeChecker($app_id, $auth_provider, $request->secret);
        \Log::Info(request()->ip()." end user logged in app_id ".$app_id);
        $this->app_id = $app_id;
        $table = $this->gtc($auth_provider);
        $record = $table::where(['email' => $request->email])->first();
        if (\Hash::check($request->password, $record->password)){
            $new_token = $this->getToken($app_id, $auth_provider, $record->id);
            return ['status' => 'success', '_token' => $new_token, 'user' => $record];
        }else{
            return ['status' => "failed"];
        }
    }

    public function index(Request $request, $app_id, $auth_provider)
    {
        \Log::Info(request()->ip()." end user requested auth_id in app_id ".$app_id);
        $this->app_id = $app_id;
        return ['auth_id' => $this->getAuthId($app_id, $auth_provider, $request->_token)];
    }

    public function count($app_id, $auth_provider, $table){
        \Log::Info(request()->ip()." end user requested count in app_id ".$app_id);
        $this->app_id = $app_id;
        $this->setTable($table);
        return $this->table::count();
    }

    public function listRecords($app_id, $auth_provider, $table)
    {
        \Log::Info(request()->ip()." end user requested list of records in app_id ".$app_id);
        $this->app_id = $app_id;
        if(!$this->permitChecker($app_id, $auth_provider, $table, 'r')){
            return ['status' => 'un authorized'];
        }
        $this->setTable('app'.$app_id.'_'.$table);
        \Log::Info($this->table::all());
        $this->table = $this->table::all();
        $this->table = $this->table->makeVisible($this->visibles);
        $this->table = $this->table->makeHidden($this->hiddens);
        if(is_array($this->filters)){
            $this->table = $this->table->filter(function($item){
                foreach ($this->filters as $key => $value) {
                    if($item[$key] != $value){
                        return false;
                    }
                }
                return true;
            });
        }
        $this->processTableForHasMany($table);
        return $this->table->toArray();
    }

    public function getRecord($app_id, $auth_provider, $table, $id)
    {
        \Log::Info(request()->ip()." end user requested get record in app_id ".$app_id);
        $this->app_id = $app_id;
        $this->setTable('app'.$app_id.'_'.$table);
        $this->table = $this->table::findOrFail($id);
        // $this->table = $this->table->makeVisible($this->visibles);
        // $this->table = $this->table->makeHidden($this->hiddens);
        // foreach ($this->has_many as $key => $value) {
        //     if( strpos($value, '|') ){
        //         $value = explode('|', $value);
        //         $stable = 'App\\Models\\'.$this->auth_user->id.'\\'.$this->auth_user->active_app_id.ucwords(rtrim($value[0],'s'));
        //         $this->table[$value[1]] = $stable::where('pivot_table', $table)->where('pivot_field', $value[1])->where('pivot_id', $id)->get();
        //     }else{
        //         $stable = 'App\\Models\\'.$this->auth_user->id.'\\'.$this->auth_user->active_app_id.ucwords(rtrim($value,'s'));
        //         $this->table[$value] = $stable::where($table.'_id', $id);
        //     }
        // }
        return $this->table->toArray();
    }

    public function storeRecord($app_id, $auth_provider, $table)
    {
        \Log::Info(request()->ip()." end user requested store record in app_id ".$app_id);
        $this->app_id = $app_id;
        $this->setTable('app'.$app_id.'_'.$table);
        // $this->processRequestForDates();
        $id = $this->table::create($this->request->all())->id;
        return $id;
    }

    public function updateRecord($app_id, $auth_provider, $table, $id)
    {
        \Log::Info(request()->ip()." end user requested updated record in app_id ".$app_id);
        $this->app_id = $app_id;
        $this->setTable('app'.$app_id.'_'.$table);
        $record = $this->table::findOrFail($id)->update($this->request->all());
        return ['status' => 'success'];
    }

    public function deleteRecord($app_id, $auth_provider, $table, $id)
    {
        \Log::Info(request()->ip()." end user requested delete record in app_id ".$app_id);
        $this->app_id = $app_id;
        $this->setTable('app'.$app_id.'_'.$table);
        $record = $this->table::findOrFail($id);
        if($this->table::destroy($id)){
            return ['status' => 'success'];
        }
    }

    public function storeFile($app_id, $auth_provider, $pivot_table, $pivot_field, $pivot_id)
    {
        \Log::Info(request()->ip()." end user requested store file in app_id ".$app_id);
        $this->app_id = $app_id;
        // public_path(); // Path of public/
        // base_path(); // Path of application root
        // storage_path(); // Path of storage/
        // app_path(); // Path of app/

        \Log::Info("storeFile".$pivot_table.$pivot_field.$pivot_id);
        $path = storage_path() .'/app/'. $this->request->file('uploadFile')->store($pivot_table.'/'.$pivot_field.'/'.$pivot_id);

        $this->setTable('attach');
        $id = $this->table::create([
            'pivot_table' => $pivot_table, 
            'pivot_field' => $pivot_field, 
            'pivot_id' => $pivot_id, 
            // 'attach_type_id' => $attach_type_id, 
            // 'attach_name' => $attach_name, 
            'path' => $path
        ])->id;

        return ['id' => $id , 'path'=> $path];
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
            $ar = explode("|", $auth_provider);

            if( in_array( $ar[1], $arr[$ar[0]] ) ){
                return ['status' => 'failed'];
            }
        }else{
            if( $arr[$auth_provider] !== "All Users"){
                return ['status' => 'failed'];
            }
        }
    }

    private function permitChecker($app_id, $auth_provider, $table, $p)
    {
        $app = App::findOrFail($app_id);
        $perm = json_decode($app->permissions, true);
        if(empty($perm[$p][$table.'guest'])){
            if(empty($perm[$p][$table.$auth_provider])){
                \Log::Info(request()->ip()." permission denied for end user in app_id ".$app_id);
                return false;
            }
        }
        \Log::Info(request()->ip()." permission granted for end user in app_id ".$app_id);
        return true;
    }

    public function gtc($table)
    {
        $table_name = ucwords(rtrim('app'.$this->app_id.'_'.$table),'s');
        $myFilePath = app_path() ."/Models/$table_name.php";
        if(!file_exists($myFilePath)){
            $arr = json_decode(App::findOrFail($this->app_id)->auth_providers, true);
            $this->createModelClass($table, in_array($table, $arr));
        }
        return "App\\Models\\".$table_name;
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