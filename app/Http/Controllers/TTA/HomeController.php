<?php

namespace App\Http\Controllers\TTA;

use App\Models\Price;
use Illuminate\Http\Request;
use App\Events\MessageReceived;

use PDO;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Config\Repository\Config;

use JWTAuth;

class HomeController extends Controller
{
    private $request;
    private $table;
    private $visibles;
    private $hiddens;
    private $has_many;
    private $filters;
    private $dates;
    private $result;
    private $auth_user;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(Request $request)
    {
        $this->middleware('jst.auth');
        $this->request = $request;
        $this->visibles = $request->query("visibles");
        $this->hiddens = $request->query("hiddens")?$request->query("hiddens"):[];
        $this->has_many = $request->query("has_many")?$request->query("has_many"):[];
        $this->filters = $request->query("filters");
        $this->dates = $request->query("dates");
        // Auth::guard('api')->user();
        JWTAuth::setToken($request->input('token'));
        $this->auth_user = JWTAuth::toUser();
    }

    private function setTable($table)
    {
        // $this->table = 'App\\Models\\'.$table;
        $this->table = 'App\\Models\\'.$this->auth_user->id.'\\'.$this->auth_user->active_app_id.ucwords(rtrim($table,'s'));
    }

    private function setTableSelf($table)
    {
        // $this->table = 'App\\Models\\'.$table;
        $this->table = 'App\\Models\\'.ucwords(rtrim($table,'s'));
    }

    private function addSessionToken($response)
    {
        try{
            $token = JWTAuth::refresh($this->request->token);
        }catch(TokenInvalidException $e){
            throw new AccessDeniedHttpException('The token is invalid');
        }
        return ['status' => true, 'session_token' => $token, 'data' => $response ];
    }

    private function updateDotEnv($key, $newValue, $delim='')
    {
        $path = base_path('.env');
        $oldValue = env($key);
        if ($oldValue === $newValue) {
            return;
        }
        if (file_exists($path)) {
            file_put_contents(
                $path, str_replace(
                    $key.'='.$delim.$oldValue.$delim, 
                    $key.'='.$delim.$newValue.$delim, 
                    file_get_contents($path)
                )
            );
        }
    }

    private function getDotEnv($key)
    {
        $path = base_path('.env');
        return env($key);
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

    public function register(Request $request){
        $user = $this->user->create([
          'nickname' => $request->get('nickname'),
          'name' => $request->get('name'),
          'email' => $request->get('email'),
          'password' => bcrypt($request->get('password'))
        ]);
        return response()->json(['status'=>true,'message'=>'User created successfully','data'=>$user]);
    }

    public function login(Request $request){
        $credentials = $request->only('email', 'password');
        $token = null;
        try {
           if (!$token = JWTAuth::attempt($credentials)) {
            return response()->json(['invalid_email_or_password'], 422);
           }
        } catch (JWTAuthException $e) {
            return response()->json(['failed_to_create_token'], 500);
        }
        $user = JWTAuth::toUser($request->token);
        return $this->addSessionToken($user);
    }

    public function getAuthUser(Request $request){
        \Log::Info("getAuthUser");
        $user = JWTAuth::toUser($request->token);
        return $this->addSessionToken($user);
    }

    public function count($dbname, $table){
        \Log::Info("count".$table);
        $this->updateDotEnv('DB_DATABASE', $dbname);
        $this->setTable($table);
        return $this->table::count();
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($dbname, $table)
    {
        \Log::Info("index".$table.$dbname);
        $this->updateDotEnv('DB_DATABASE', $dbname);
        $this->setTable($table);
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
        // \Log::Info($this->table);
        return $this->table->toArray();
    }

    public function getRecord($dbname, $table, $id)
    {
        \Log::Info("getRecord".$table.$id);
        $this->updateDotEnv('DB_DATABASE', $dbname);
        $this->setTable($table);
        $this->table = $this->table::findOrFail($id);
        $this->table = $this->table->makeVisible($this->visibles);
        $this->table = $this->table->makeHidden($this->hiddens);
        foreach ($this->has_many as $key => $value) {
            if( strpos($value, '|') ){
                $value = explode('|', $value);
                $stable = 'App\\Models\\'.$this->auth_user->id.'\\'.$this->auth_user->active_app_id.ucwords(rtrim($value[0],'s'));
                $this->table[$value[1]] = $stable::where('pivot_table', $table)->where('pivot_field', $value[1])->where('pivot_id', $id)->get();
            }else{
                $stable = 'App\\Models\\'.$this->auth_user->id.'\\'.$this->auth_user->active_app_id.ucwords(rtrim($value,'s'));
                $this->table[$value] = $stable::where($table.'_id', $id);
            }
        }
        return $this->table->toArray();
    }

    public function storeRecord($dbname, $table)
    {
        \Log::Info("storeRecord".$table);
        // \Log::Info($this->request->all());
        $this->updateDotEnv('DB_DATABASE', $dbname);
        $this->setTable($table);
        // $this->processRequestForDates();
        $id = $this->table::create($this->request->all())->id;
        return $id;
    }

    public function updateRecord($dbname, $table, $id)
    {
        \Log::Info("updateRecord".$table.$id);
        \Log::Info($this->request->all());
        $this->updateDotEnv('DB_DATABASE', $dbname);
        $this->setTable($table);
        $record = $this->table::findOrFail($id)->update($this->request->all());
        return ['status' => 'success'];
    }

    public function deleteRecord($dbname, $table, $id)
    {
        \Log::Info("deleteRecord".$table.$id);
        $this->updateDotEnv('DB_DATABASE', $dbname);
        $this->setTable($table);
        $record = $this->table::findOrFail($id);
        if($this->table::destroy($id)){
            return ['status' => 'success'];
        }
    }

    public function getMyApps()
    {
        \Log::Info("getMyApps");
        $this->hiddens = array('created_by', 'created_at', 'updated_at');
        $this->setTableSelf("myapps");
        $this->table = $this->table::where('created_by', $this->auth_user->id)->get();
        $this->table = $this->table->makeHidden($this->hiddens);
        return $this->table->toArray();
    }

    public function createNewApp()
    {
        \Log::Info("createNewApp");
        $this->setTableSelf("myapps");
        $id = $this->table::create([
            "name" => $this->request->name,
            "privacy" => $this->request->privacy,
            "created_by" => $this->auth_user->id
        ])->id;
        $this->setTableSelf("users");
        $record = $this->table::findOrFail($this->auth_user->id)->update(["active_app_id" => $id]);
        return ['status' => 'success', "active_app_id" => $id];
    }

    public function updateMyApp($id)
    {
        $this->setTableSelf("myapps");
        $record = $this->table::findOrFail($id)->update($this->request->all());
        $this->setTableSelf("users");
        $record = $this->table::findOrFail($this->auth_user->id)->update(["active_app_id" => $id]);
        return ['status' => 'success', "active_app_id" => $id];
    }

    public function deleteMyApp($id)
    {
        \Log::Info("deleteMyApp".$id);
        $this->setTableSelf("myapps");
        $record = $this->table::findOrFail($id);
        if($this->table::destroy($id)){
            $app_record = $this->table::where("created_by" , $this->auth_user->id)->first();
            \Log::Info($app_record);
            $this->setTableSelf("users");
            $record = $this->table::findOrFail($app_record->created_by)->update(["active_app_id" => $app_record->id]);
            return ['status' => 'success', "active_app_id" => $app_record->id];
        }
    }

    public function activeMyApp($id)
    {
        \Log::Info("activeMyApp".  $this->request->active_app_id);
        $this->setTableSelf("users");
        $record = $this->table::findOrFail($this->auth_user->id)->update(["active_app_id" => $id]);
        return ['status' => 'success', "active_app_id" => $id];
    }

    public function getDatabases()
    {
        \Log::Info("getDatabases");
        $this->hiddens = array('private', 'table_size', 'created_by', 'created_at', 'updated_at', 'id');
        return $this->index($this->getDotEnv('DB_DATABASE'), 'databases');
    }

    public function getMyTables(){
        \Log::Info("getMyTables");
        // $this->filters = array("D" => "");
        return $this->index($this->getDotEnv('DB_DATABASE'), 'mastertablelist');
    }

    public function getTableGroups($db_name)
    {
        \Log::Info("getTableGroups");
        $this->hiddens = array('private', 'table_size', 'created_by', 'created_at', 'updated_at', 'id');
        $this->filters = array('database_name' => $db_name);
        return $this->index($this->getDotEnv('DB_DATABASE'), 'tablegroup');
    }

    public function getTablesInActiveApp()
    {
        \Log::Info("getTablesInActiveApp");
        $this->hiddens = array('table_description', 'keywords', 'field_indexes', 
            'fillable', 'hidden', 'created_by', 'created_at', 'updated_at', 'id');
        $this->setTableSelf("mastertablelist");
        $this->table = $this->table::where('app_id' , $this->auth_user->active_app_id)->get();
        $this->table = $this->table->makeHidden($this->hiddens);
        return $this->table->toArray();
    }

    public function getTableFields($table)
    {
        \Log::Info("getTableFields");
        $this->setTableSelf("mastertablelist");
        $record = $this->table::where('table_name', $table)->first();
        $field_indexes = unserialize( base64_decode( $record->field_indexes ) );
        $this->setTableSelf("masterfieldlist");
        $this->table = $this->table::all();
        $result = array();
        foreach ($field_indexes as $key => $value) {
            array_push($result, $this->table->where('sr', $value)->first()
                ->makeHidden(array("id", "sr", "data_type", "created_at", "updated_at")));
        }
        return $result;
    }

    public function getTableDetails($table)
    {
        \Log::Info("getTableDetails");
        $this->setTableSelf("mastertablelist");
        $record = $this->table::where('table_name', $table)->first();
        \Log::Info($record);
        $field_indexes = unserialize( base64_decode( $record->field_indexes ) );
        \Log::Info($field_indexes);
        $table_description = $record->table_description;
        $keywords = $record->keywords;
        $this->setTableSelf("masterfieldlist");
        $this->table = $this->table::all();
        $result = array();
        foreach ($field_indexes as $key => $value) {
            array_push($result, $this->table->where('sr', $value)->first()
                ->makeHidden(array("id", "sr", "data_type", "created_at", "updated_at")));
        }
        return ['table_description' => $table_description, 'keywords' => $keywords, 'fields' => $result];
    }

    public function getTableFieldDataTypes($table)
    {
        \Log::Info("getTableDetails");
        $this->setTableSelf("mastertablelist");
        $record = $this->table::where('table_name', $table)->first();
        $field_indexes = unserialize( base64_decode( $record->field_indexes ) );
        $this->setTableSelf("masterfieldlist");
        $this->table = $this->table::all();
        $result = array();
        foreach ($field_indexes as $key => $value) {
            array_push($result, $this->table->where('sr', $value)->first()
                ->makeHidden(array("id", "sr", "created_at", "updated_at")));
        }
        return $result;
    }

    public function storeFile($pivot_table, $pivot_field, $pivot_id)
    {
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

    public function createTable()
    {
        \Log::Info("createTable");
        // \Log::Info($this->request);
        // \Log::Info(strlen($this->request));
        // $dbname = $this->request->database_name;
        $newtable = $this->request->table_name;
        // $group_name = $this->request->group_name;
        $table_description = $this->request->table_description;
        $keywords = $this->request->keywords;
        $authenticatable = $this->request->authenticatable;
        $notifiable = $this->request->notifiable;
        $fields = $this->request->fields;
        $composite_indexes = $this->request->composite_indexes;

        // $this->updateDotEnv('DB_DATABASE', $dbname);

        if(Schema::hasTable($newtable)){
            return ['table exists delete it before creating.'];
        }

        $fillable = array();
        $hidden = array();
        foreach ($fields as $key => $value) {
            switch ($value['elequent_array']) {
                case 'fillable':
                    array_push($fillable, $value['field_name']);
                    break;
                
                case 'hidden':
                    array_push($hidden, $value['field_name']);
                    break;

                case 'both':
                    array_push($fillable, $value['field_name']);
                    array_push($hidden, $value['field_name']);
                    break;
                
                default:
                    // 
                    break;
            }
        }

        $field_indexes = array();
        $this->setTableSelf("masterfieldlist");
        foreach ($fields as $key => $value) {
            $existing_field = $this->table::where("field_name", $value['field_name'])->where("data_type", $value['data_type'])->first();
            if(empty($existing_field)){
                $lastSr = $this->table::count() + 1;
                $this->table::create([
                    "sr" => $lastSr,
                    "field_name" => $value['field_name'],
                    "data_type" => $value['data_type']
                ]);
                array_push($field_indexes, $lastSr);
            }else{
                array_push($field_indexes, $existing_field->sr);
            }
        }

        // $this->setTableSelf("tablegroup");
        // // $private = $this->table::where('name', $group_name)->first()->private;
        // $record = $this->table::findOrFail($id)->update([
        //     "auth_table_name" => $this->auth_user->active_app_id.$newtable
        // ]);

        $this->setTableSelf("mastertablelist");
        $this->table::create([
            "table_name" => $this->auth_user->active_app_id.$newtable,
            "app_id" => $this->auth_user->active_app_id,
            "table_description" => $table_description,
            "keywords" => $keywords,
            "field_indexes" => base64_encode( serialize( $field_indexes ) ),
            "fillable" => base64_encode( serialize( $fillable ) ),
            "hidden" => base64_encode( serialize( $hidden ) ),
        ]);

        Schema::create($this->auth_user->active_app_id.$newtable, function (Blueprint $table) use ($fields, $composite_indexes) {
            $table->increments('id');
                foreach ($fields as $key => $value) {
                    switch ($value['data_type']) {
                        case 'bigIncrements':
                            if(!empty($value['data_type_modifier'])){
                                foreach ($value['data_type_modifier'] as $key1 => $value1) {
                                    switch ($value1['name']) {
                                        case 'after':
                                            $table->bigIncrements($value['field_name'])->after($value1['value']);
                                            break;

                                        case 'comment':
                                            $table->bigIncrements($value['field_name'])->comment($value1['value']);
                                            break;

                                        case 'default':
                                            $table->bigIncrements($value['field_name'])->default($value1['value']);
                                            break;

                                        case 'first':
                                            $table->bigIncrements($value['field_name'])->first();
                                            break;

                                        case 'storedAs':
                                            $table->bigIncrements($value['field_name'])->storedAs($value1['value']);
                                            break;

                                        case 'unsigned':
                                            $table->bigIncrements($value['field_name'])->unsigned();
                                            break;
                                        
                                        case 'virtualAs':
                                            $table->bigIncrements($value['field_name'])->virtualAs($value1['value']);
                                            break;

                                        default:
                                            # code...
                                            break;
                                    }
                                }
                            }
                            $table->bigIncrements($value['field_name'])->nullable();
                            break;

                        case 'bigInteger':
                            if(!empty($value['data_type_modifier'])){
                                foreach ($value['data_type_modifier'] as $key1 => $value1) {
                                    switch ($value1['name']) {
                                        case 'after':
                                            $table->bigInteger($value['field_name'])->after($value1['value']);
                                            break;

                                        case 'comment':
                                            $table->bigInteger($value['field_name'])->comment($value1['value']);
                                            break;

                                        case 'default':
                                            $table->bigInteger($value['field_name'])->default($value1['value']);
                                            break;

                                        case 'first':
                                            $table->bigInteger($value['field_name'])->first();
                                            break;

                                        case 'storedAs':
                                            $table->bigInteger($value['field_name'])->storedAs($value1['value']);
                                            break;

                                        case 'unsigned':
                                            $table->bigInteger($value['field_name'])->unsigned();
                                            break;
                                        
                                        case 'virtualAs':
                                            $table->bigInteger($value['field_name'])->virtualAs($value1['value']);
                                            break;

                                        default:
                                            # code...
                                            break;
                                    }
                                }
                            }
                            $table->bigInteger($value['field_name'])->nullable();
                            break;

                        case 'binary':
                            if(!empty($value['data_type_modifier'])){
                                foreach ($value['data_type_modifier'] as $key1 => $value1) {
                                    switch ($value1['name']) {
                                        case 'after':
                                            $table->binary($value['field_name'])->after($value1['value']);
                                            break;

                                        case 'comment':
                                            $table->binary($value['field_name'])->comment($value1['value']);
                                            break;

                                        case 'default':
                                            $table->binary($value['field_name'])->default($value1['value']);
                                            break;

                                        case 'first':
                                            $table->binary($value['field_name'])->first();
                                            break;

                                        case 'storedAs':
                                            $table->binary($value['field_name'])->storedAs($value1['value']);
                                            break;

                                        case 'unsigned':
                                            $table->binary($value['field_name'])->unsigned();
                                            break;
                                        
                                        case 'virtualAs':
                                            $table->binary($value['field_name'])->virtualAs($value1['value']);
                                            break;

                                        default:
                                            # code...
                                            break;
                                    }
                                }
                            }
                            $table->binary($value['field_name'])->nullable();
                            break;

                        case 'boolean':
                            if(!empty($value['data_type_modifier'])){
                                foreach ($value['data_type_modifier'] as $key1 => $value1) {
                                    switch ($value1['name']) {
                                        case 'after':
                                            $table->boolean($value['field_name'])->after($value1['value']);
                                            break;

                                        case 'comment':
                                            $table->boolean($value['field_name'])->comment($value1['value']);
                                            break;

                                        case 'default':
                                            $table->boolean($value['field_name'])->default($value1['value']);
                                            break;

                                        case 'first':
                                            $table->boolean($value['field_name'])->first();
                                            break;

                                        case 'storedAs':
                                            $table->boolean($value['field_name'])->storedAs($value1['value']);
                                            break;

                                        case 'unsigned':
                                            $table->boolean($value['field_name'])->unsigned();
                                            break;
                                        
                                        case 'virtualAs':
                                            $table->boolean($value['field_name'])->virtualAs($value1['value']);
                                            break;

                                        default:
                                            # code...
                                            break;
                                    }
                                }
                            }
                            $table->boolean($value['field_name'])->nullable();
                            break;

                        case 'char':
                            if(!empty($value['data_type_modifier'])){
                                foreach ($value['data_type_modifier'] as $key1 => $value1) {
                                    switch ($value1['name']) {
                                        case 'after':
                                            $table->char($value['field_name'])->after($value1['value']);
                                            break;

                                        case 'comment':
                                            $table->char($value['field_name'])->comment($value1['value']);
                                            break;

                                        case 'default':
                                            $table->char($value['field_name'])->default($value1['value']);
                                            break;

                                        case 'first':
                                            $table->char($value['field_name'])->first();
                                            break;

                                        case 'storedAs':
                                            $table->char($value['field_name'])->storedAs($value1['value']);
                                            break;

                                        case 'unsigned':
                                            $table->char($value['field_name'])->unsigned();
                                            break;
                                        
                                        case 'virtualAs':
                                            $table->char($value['field_name'])->virtualAs($value1['value']);
                                            break;

                                        default:
                                            # code...
                                            break;
                                    }
                                }
                            }
                            $table->char($value['field_name'])->nullable();
                            break;

                        case 'date':
                            if(!empty($value['data_type_modifier'])){
                                foreach ($value['data_type_modifier'] as $key1 => $value1) {
                                    switch ($value1['name']) {
                                        case 'after':
                                            $table->date($value['field_name'])->after($value1['value']);
                                            break;

                                        case 'comment':
                                            $table->date($value['field_name'])->comment($value1['value']);
                                            break;

                                        case 'default':
                                            $table->date($value['field_name'])->default($value1['value']);
                                            break;

                                        case 'first':
                                            $table->date($value['field_name'])->first();
                                            break;

                                        case 'storedAs':
                                            $table->date($value['field_name'])->storedAs($value1['value']);
                                            break;

                                        case 'unsigned':
                                            $table->date($value['field_name'])->unsigned();
                                            break;
                                        
                                        case 'virtualAs':
                                            $table->date($value['field_name'])->virtualAs($value1['value']);
                                            break;

                                        default:
                                            # code...
                                            break;
                                    }
                                }
                            }
                            $table->date($value['field_name'])->nullable();
                            break;

                        case 'dateTime':
                            if(!empty($value['data_type_modifier'])){
                                foreach ($value['data_type_modifier'] as $key1 => $value1) {
                                    switch ($value1['name']) {
                                        case 'after':
                                            $table->dateTime($value['field_name'])->after($value1['value']);
                                            break;

                                        case 'comment':
                                            $table->dateTime($value['field_name'])->comment($value1['value']);
                                            break;

                                        case 'default':
                                            $table->dateTime($value['field_name'])->default($value1['value']);
                                            break;

                                        case 'first':
                                            $table->dateTime($value['field_name'])->first();
                                            break;

                                        case 'storedAs':
                                            $table->dateTime($value['field_name'])->storedAs($value1['value']);
                                            break;

                                        case 'unsigned':
                                            $table->dateTime($value['field_name'])->unsigned();
                                            break;
                                        
                                        case 'virtualAs':
                                            $table->dateTime($value['field_name'])->virtualAs($value1['value']);
                                            break;

                                        default:
                                            # code...
                                            break;
                                    }
                                }
                            }
                            $table->dateTime($value['field_name'])->nullable();
                            break;

                        case 'dateTimeTz':
                            if(!empty($value['data_type_modifier'])){
                                foreach ($value['data_type_modifier'] as $key1 => $value1) {
                                    switch ($value1['name']) {
                                        case 'after':
                                            $table->dateTimeTz($value['field_name'])->after($value1['value']);
                                            break;

                                        case 'comment':
                                            $table->dateTimeTz($value['field_name'])->comment($value1['value']);
                                            break;

                                        case 'default':
                                            $table->dateTimeTz($value['field_name'])->default($value1['value']);
                                            break;

                                        case 'first':
                                            $table->dateTimeTz($value['field_name'])->first();
                                            break;

                                        case 'storedAs':
                                            $table->dateTimeTz($value['field_name'])->storedAs($value1['value']);
                                            break;

                                        case 'unsigned':
                                            $table->dateTimeTz($value['field_name'])->unsigned();
                                            break;
                                        
                                        case 'virtualAs':
                                            $table->dateTimeTz($value['field_name'])->virtualAs($value1['value']);
                                            break;

                                        default:
                                            # code...
                                            break;
                                    }
                                }
                            }
                            $table->dateTimeTz($value['field_name'])->nullable();
                            break;

                        case 'decimal':
                            if(!empty($value['data_type_modifier'])){
                                foreach ($value['data_type_modifier'] as $key1 => $value1) {
                                    switch ($value1['name']) {
                                        case 'after':
                                            $table->decimal($value['field_name'])->after($value1['value']);
                                            break;

                                        case 'comment':
                                            $table->decimal($value['field_name'])->comment($value1['value']);
                                            break;

                                        case 'default':
                                            $table->decimal($value['field_name'])->default($value1['value']);
                                            break;

                                        case 'first':
                                            $table->decimal($value['field_name'])->first();
                                            break;

                                        case 'storedAs':
                                            $table->decimal($value['field_name'])->storedAs($value1['value']);
                                            break;

                                        case 'unsigned':
                                            $table->decimal($value['field_name'])->unsigned();
                                            break;
                                        
                                        case 'virtualAs':
                                            $table->decimal($value['field_name'])->virtualAs($value1['value']);
                                            break;

                                        default:
                                            # code...
                                            break;
                                    }
                                }
                            }
                            $table->decimal($value['field_name'])->nullable();
                            break;

                        case 'double':
                            if(!empty($value['data_type_modifier'])){
                                foreach ($value['data_type_modifier'] as $key1 => $value1) {
                                    switch ($value1['name']) {
                                        case 'after':
                                            $table->double($value['field_name'])->after($value1['value']);
                                            break;

                                        case 'comment':
                                            $table->double($value['field_name'])->comment($value1['value']);
                                            break;

                                        case 'default':
                                            $table->double($value['field_name'])->default($value1['value']);
                                            break;

                                        case 'first':
                                            $table->double($value['field_name'])->first();
                                            break;

                                        case 'storedAs':
                                            $table->double($value['field_name'])->storedAs($value1['value']);
                                            break;

                                        case 'unsigned':
                                            $table->double($value['field_name'])->unsigned();
                                            break;
                                        
                                        case 'virtualAs':
                                            $table->double($value['field_name'])->virtualAs($value1['value']);
                                            break;

                                        default:
                                            # code...
                                            break;
                                    }
                                }
                            }
                            $table->double($value['field_name'])->nullable();
                            break;

                        case 'enum':
                            if(!empty($value['data_type_modifier'])){
                                foreach ($value['data_type_modifier'] as $key1 => $value1) {
                                    switch ($value1['name']) {
                                        case 'after':
                                            $table->enum($value['field_name'])->after($value1['value']);
                                            break;

                                        case 'comment':
                                            $table->enum($value['field_name'])->comment($value1['value']);
                                            break;

                                        case 'default':
                                            $table->enum($value['field_name'])->default($value1['value']);
                                            break;

                                        case 'first':
                                            $table->enum($value['field_name'])->first();
                                            break;

                                        case 'storedAs':
                                            $table->enum($value['field_name'])->storedAs($value1['value']);
                                            break;

                                        case 'unsigned':
                                            $table->enum($value['field_name'])->unsigned();
                                            break;
                                        
                                        case 'virtualAs':
                                            $table->enum($value['field_name'])->virtualAs($value1['value']);
                                            break;

                                        default:
                                            # code...
                                            break;
                                    }
                                }
                            }
                            $table->enum($value['field_name'])->nullable();
                            break;

                        case 'float':
                            if(!empty($value['data_type_modifier'])){
                                foreach ($value['data_type_modifier'] as $key1 => $value1) {
                                    switch ($value1['name']) {
                                        case 'after':
                                            $table->float($value['field_name'])->after($value1['value']);
                                            break;

                                        case 'comment':
                                            $table->float($value['field_name'])->comment($value1['value']);
                                            break;

                                        case 'default':
                                            $table->float($value['field_name'])->default($value1['value']);
                                            break;

                                        case 'first':
                                            $table->float($value['field_name'])->first();
                                            break;

                                        case 'storedAs':
                                            $table->float($value['field_name'])->storedAs($value1['value']);
                                            break;

                                        case 'unsigned':
                                            $table->float($value['field_name'])->unsigned();
                                            break;
                                        
                                        case 'virtualAs':
                                            $table->float($value['field_name'])->virtualAs($value1['value']);
                                            break;

                                        default:
                                            # code...
                                            break;
                                    }
                                }
                            }
                            $table->float($value['field_name'])->nullable();
                            break;

                        case 'increments':
                            if(!empty($value['data_type_modifier'])){
                                foreach ($value['data_type_modifier'] as $key1 => $value1) {
                                    switch ($value1['name']) {
                                        case 'after':
                                            $table->increments($value['field_name'])->after($value1['value']);
                                            break;

                                        case 'comment':
                                            $table->increments($value['field_name'])->comment($value1['value']);
                                            break;

                                        case 'default':
                                            $table->increments($value['field_name'])->default($value1['value']);
                                            break;

                                        case 'first':
                                            $table->increments($value['field_name'])->first();
                                            break;

                                        case 'storedAs':
                                            $table->increments($value['field_name'])->storedAs($value1['value']);
                                            break;

                                        case 'unsigned':
                                            $table->increments($value['field_name'])->unsigned();
                                            break;
                                        
                                        case 'virtualAs':
                                            $table->increments($value['field_name'])->virtualAs($value1['value']);
                                            break;

                                        default:
                                            # code...
                                            break;
                                    }
                                }
                            }
                            $table->increments($value['field_name']);
                            break;

                        case 'integer':
                            if(!empty($value['data_type_modifier'])){
                                foreach ($value['data_type_modifier'] as $key1 => $value1) {
                                    switch ($value1['name']) {
                                        case 'after':
                                            $table->integer($value['field_name'])->after($value1['value']);
                                            break;

                                        case 'comment':
                                            $table->integer($value['field_name'])->comment($value1['value']);
                                            break;

                                        case 'default':
                                            $table->integer($value['field_name'])->default($value1['value']);
                                            break;

                                        case 'first':
                                            $table->integer($value['field_name'])->first();
                                            break;

                                        case 'storedAs':
                                            $table->integer($value['field_name'])->storedAs($value1['value']);
                                            break;

                                        case 'unsigned':
                                            $table->integer($value['field_name'])->unsigned();
                                            break;
                                        
                                        case 'virtualAs':
                                            $table->integer($value['field_name'])->virtualAs($value1['value']);
                                            break;

                                        default:
                                            # code...
                                            break;
                                    }
                                }
                            }
                            $table->integer($value['field_name'])->nullable();
                            break;

                        case 'ipAddress':
                            if(!empty($value['data_type_modifier'])){
                                foreach ($value['data_type_modifier'] as $key1 => $value1) {
                                    switch ($value1['name']) {
                                        case 'after':
                                            $table->ipAddress($value['field_name'])->after($value1['value']);
                                            break;

                                        case 'comment':
                                            $table->ipAddress($value['field_name'])->comment($value1['value']);
                                            break;

                                        case 'default':
                                            $table->ipAddress($value['field_name'])->default($value1['value']);
                                            break;

                                        case 'first':
                                            $table->ipAddress($value['field_name'])->first();
                                            break;

                                        case 'storedAs':
                                            $table->ipAddress($value['field_name'])->storedAs($value1['value']);
                                            break;

                                        case 'unsigned':
                                            $table->ipAddress($value['field_name'])->unsigned();
                                            break;
                                        
                                        case 'virtualAs':
                                            $table->ipAddress($value['field_name'])->virtualAs($value1['value']);
                                            break;

                                        default:
                                            # code...
                                            break;
                                    }
                                }
                            }
                            $table->ipAddress($value['field_name'])->nullable();
                            break;

                        case 'json':
                            if(!empty($value['data_type_modifier'])){
                                foreach ($value['data_type_modifier'] as $key1 => $value1) {
                                    switch ($value1['name']) {
                                        case 'after':
                                            $table->json($value['field_name'])->after($value1['value']);
                                            break;

                                        case 'comment':
                                            $table->json($value['field_name'])->comment($value1['value']);
                                            break;

                                        case 'default':
                                            $table->json($value['field_name'])->default($value1['value']);
                                            break;

                                        case 'first':
                                            $table->json($value['field_name'])->first();
                                            break;

                                        case 'storedAs':
                                            $table->json($value['field_name'])->storedAs($value1['value']);
                                            break;

                                        case 'unsigned':
                                            $table->json($value['field_name'])->unsigned();
                                            break;
                                        
                                        case 'virtualAs':
                                            $table->json($value['field_name'])->virtualAs($value1['value']);
                                            break;

                                        default:
                                            # code...
                                            break;
                                    }
                                }
                            }
                            $table->json($value['field_name'])->nullable();
                            break;

                        case 'jsonb':
                            if(!empty($value['data_type_modifier'])){
                                foreach ($value['data_type_modifier'] as $key1 => $value1) {
                                    switch ($value1['name']) {
                                        case 'after':
                                            $table->jsonb($value['field_name'])->after($value1['value']);
                                            break;

                                        case 'comment':
                                            $table->jsonb($value['field_name'])->comment($value1['value']);
                                            break;

                                        case 'default':
                                            $table->jsonb($value['field_name'])->default($value1['value']);
                                            break;

                                        case 'first':
                                            $table->jsonb($value['field_name'])->first();
                                            break;

                                        case 'storedAs':
                                            $table->jsonb($value['field_name'])->storedAs($value1['value']);
                                            break;

                                        case 'unsigned':
                                            $table->jsonb($value['field_name'])->unsigned();
                                            break;
                                        
                                        case 'virtualAs':
                                            $table->jsonb($value['field_name'])->virtualAs($value1['value']);
                                            break;

                                        default:
                                            # code...
                                            break;
                                    }
                                }
                            }
                            $table->jsonb($value['field_name'])->nullable();
                            break;

                        case 'longText':
                            if(!empty($value['data_type_modifier'])){
                                foreach ($value['data_type_modifier'] as $key1 => $value1) {
                                    switch ($value1['name']) {
                                        case 'after':
                                            $table->longText($value['field_name'])->after($value1['value']);
                                            break;

                                        case 'comment':
                                            $table->longText($value['field_name'])->comment($value1['value']);
                                            break;

                                        case 'default':
                                            $table->longText($value['field_name'])->default($value1['value']);
                                            break;

                                        case 'first':
                                            $table->longText($value['field_name'])->first();
                                            break;

                                        case 'storedAs':
                                            $table->longText($value['field_name'])->storedAs($value1['value']);
                                            break;

                                        case 'unsigned':
                                            $table->longText($value['field_name'])->unsigned();
                                            break;
                                        
                                        case 'virtualAs':
                                            $table->longText($value['field_name'])->virtualAs($value1['value']);
                                            break;

                                        default:
                                            # code...
                                            break;
                                    }
                                }
                            }
                            $table->longText($value['field_name'])->nullable();
                            break;

                        case 'macAddress':
                            if(!empty($value['data_type_modifier'])){
                                foreach ($value['data_type_modifier'] as $key1 => $value1) {
                                    switch ($value1['name']) {
                                        case 'after':
                                            $table->macAddress($value['field_name'])->after($value1['value']);
                                            break;

                                        case 'comment':
                                            $table->macAddress($value['field_name'])->comment($value1['value']);
                                            break;

                                        case 'default':
                                            $table->macAddress($value['field_name'])->default($value1['value']);
                                            break;

                                        case 'first':
                                            $table->macAddress($value['field_name'])->first();
                                            break;

                                        case 'storedAs':
                                            $table->macAddress($value['field_name'])->storedAs($value1['value']);
                                            break;

                                        case 'unsigned':
                                            $table->macAddress($value['field_name'])->unsigned();
                                            break;
                                        
                                        case 'virtualAs':
                                            $table->macAddress($value['field_name'])->virtualAs($value1['value']);
                                            break;

                                        default:
                                            # code...
                                            break;
                                    }
                                }
                            }
                            $table->macAddress($value['field_name'])->nullable();
                            break;

                        case 'mediumIncrements':
                            if(!empty($value['data_type_modifier'])){
                                foreach ($value['data_type_modifier'] as $key1 => $value1) {
                                    switch ($value1['name']) {
                                        case 'after':
                                            $table->mediumIncrements($value['field_name'])->after($value1['value']);
                                            break;

                                        case 'comment':
                                            $table->mediumIncrements($value['field_name'])->comment($value1['value']);
                                            break;

                                        case 'default':
                                            $table->mediumIncrements($value['field_name'])->default($value1['value']);
                                            break;

                                        case 'first':
                                            $table->mediumIncrements($value['field_name'])->first();
                                            break;

                                        case 'storedAs':
                                            $table->mediumIncrements($value['field_name'])->storedAs($value1['value']);
                                            break;

                                        case 'unsigned':
                                            $table->mediumIncrements($value['field_name'])->unsigned();
                                            break;
                                        
                                        case 'virtualAs':
                                            $table->mediumIncrements($value['field_name'])->virtualAs($value1['value']);
                                            break;

                                        default:
                                            # code...
                                            break;
                                    }
                                }
                            }
                            $table->mediumIncrements($value['field_name'])->nullable();
                            break;

                        case 'mediumInteger':
                            if(!empty($value['data_type_modifier'])){
                                foreach ($value['data_type_modifier'] as $key1 => $value1) {
                                    switch ($value1['name']) {
                                        case 'after':
                                            $table->mediumInteger($value['field_name'])->after($value1['value']);
                                            break;

                                        case 'comment':
                                            $table->mediumInteger($value['field_name'])->comment($value1['value']);
                                            break;

                                        case 'default':
                                            $table->mediumInteger($value['field_name'])->default($value1['value']);
                                            break;

                                        case 'first':
                                            $table->mediumInteger($value['field_name'])->first();
                                            break;

                                        case 'storedAs':
                                            $table->mediumInteger($value['field_name'])->storedAs($value1['value']);
                                            break;

                                        case 'unsigned':
                                            $table->mediumInteger($value['field_name'])->unsigned();
                                            break;
                                        
                                        case 'virtualAs':
                                            $table->mediumInteger($value['field_name'])->virtualAs($value1['value']);
                                            break;

                                        default:
                                            # code...
                                            break;
                                    }
                                }
                            }
                            $table->mediumInteger($value['field_name'])->nullable();
                            break;

                        case 'mediumText':
                            if(!empty($value['data_type_modifier'])){
                                foreach ($value['data_type_modifier'] as $key1 => $value1) {
                                    switch ($value1['name']) {
                                        case 'after':
                                            $table->mediumText($value['field_name'])->after($value1['value']);
                                            break;

                                        case 'comment':
                                            $table->mediumText($value['field_name'])->comment($value1['value']);
                                            break;

                                        case 'default':
                                            $table->mediumText($value['field_name'])->default($value1['value']);
                                            break;

                                        case 'first':
                                            $table->mediumText($value['field_name'])->first();
                                            break;

                                        case 'storedAs':
                                            $table->mediumText($value['field_name'])->storedAs($value1['value']);
                                            break;

                                        case 'unsigned':
                                            $table->mediumText($value['field_name'])->unsigned();
                                            break;
                                        
                                        case 'virtualAs':
                                            $table->mediumText($value['field_name'])->virtualAs($value1['value']);
                                            break;

                                        default:
                                            # code...
                                            break;
                                    }
                                }
                            }
                            $table->mediumText($value['field_name'])->nullable();
                            break;

                        case 'morphs':
                            if(!empty($value['data_type_modifier'])){
                                foreach ($value['data_type_modifier'] as $key1 => $value1) {
                                    switch ($value1['name']) {
                                        case 'after':
                                            $table->morphs($value['field_name'])->after($value1['value']);
                                            break;

                                        case 'comment':
                                            $table->morphs($value['field_name'])->comment($value1['value']);
                                            break;

                                        case 'default':
                                            $table->morphs($value['field_name'])->default($value1['value']);
                                            break;

                                        case 'first':
                                            $table->morphs($value['field_name'])->first();
                                            break;

                                        case 'storedAs':
                                            $table->morphs($value['field_name'])->storedAs($value1['value']);
                                            break;

                                        case 'unsigned':
                                            $table->morphs($value['field_name'])->unsigned();
                                            break;
                                        
                                        case 'virtualAs':
                                            $table->morphs($value['field_name'])->virtualAs($value1['value']);
                                            break;

                                        default:
                                            # code...
                                            break;
                                    }
                                }
                            }
                            $table->morphs($value['field_name'])->nullable();
                            break;

                        case 'nullableMorphs':
                            if(!empty($value['data_type_modifier'])){
                                foreach ($value['data_type_modifier'] as $key1 => $value1) {
                                    switch ($value1['name']) {
                                        case 'after':
                                            $table->nullableMorphs($value['field_name'])->after($value1['value']);
                                            break;

                                        case 'comment':
                                            $table->nullableMorphs($value['field_name'])->comment($value1['value']);
                                            break;

                                        case 'default':
                                            $table->nullableMorphs($value['field_name'])->default($value1['value']);
                                            break;

                                        case 'first':
                                            $table->nullableMorphs($value['field_name'])->first();
                                            break;

                                        case 'storedAs':
                                            $table->nullableMorphs($value['field_name'])->storedAs($value1['value']);
                                            break;

                                        case 'unsigned':
                                            $table->nullableMorphs($value['field_name'])->unsigned();
                                            break;
                                        
                                        case 'virtualAs':
                                            $table->nullableMorphs($value['field_name'])->virtualAs($value1['value']);
                                            break;

                                        default:
                                            # code...
                                            break;
                                    }
                                }
                            }
                            $table->nullableMorphs($value['field_name'])->nullable();
                            break;

                        case 'nullableTimestamps':
                            if(!empty($value['data_type_modifier'])){
                                foreach ($value['data_type_modifier'] as $key1 => $value1) {
                                    switch ($value1['name']) {
                                        case 'after':
                                            $table->nullableTimestamps($value['field_name'])->after($value1['value']);
                                            break;

                                        case 'comment':
                                            $table->nullableTimestamps($value['field_name'])->comment($value1['value']);
                                            break;

                                        case 'default':
                                            $table->nullableTimestamps($value['field_name'])->default($value1['value']);
                                            break;

                                        case 'first':
                                            $table->nullableTimestamps($value['field_name'])->first();
                                            break;

                                        case 'storedAs':
                                            $table->nullableTimestamps($value['field_name'])->storedAs($value1['value']);
                                            break;

                                        case 'unsigned':
                                            $table->nullableTimestamps($value['field_name'])->unsigned();
                                            break;
                                        
                                        case 'virtualAs':
                                            $table->nullableTimestamps($value['field_name'])->virtualAs($value1['value']);
                                            break;

                                        default:
                                            # code...
                                            break;
                                    }
                                }
                            }
                            $table->nullableTimestamps($value['field_name'])->nullable();
                            break;

                        case 'rememberToken':
                            if(!empty($value['data_type_modifier'])){
                                foreach ($value['data_type_modifier'] as $key1 => $value1) {
                                    switch ($value1['name']) {
                                        case 'after':
                                            $table->rememberToken($value['field_name'])->after($value1['value']);
                                            break;

                                        case 'comment':
                                            $table->rememberToken($value['field_name'])->comment($value1['value']);
                                            break;

                                        case 'default':
                                            $table->rememberToken($value['field_name'])->default($value1['value']);
                                            break;

                                        case 'first':
                                            $table->rememberToken($value['field_name'])->first();
                                            break;

                                        case 'storedAs':
                                            $table->rememberToken($value['field_name'])->storedAs($value1['value']);
                                            break;

                                        case 'unsigned':
                                            $table->rememberToken($value['field_name'])->unsigned();
                                            break;
                                        
                                        case 'virtualAs':
                                            $table->rememberToken($value['field_name'])->virtualAs($value1['value']);
                                            break;

                                        default:
                                            # code...
                                            break;
                                    }
                                }
                            }
                            $table->rememberToken($value['field_name'])->nullable();
                            break;

                        case 'smallIncrements':
                            if(!empty($value['data_type_modifier'])){
                                foreach ($value['data_type_modifier'] as $key1 => $value1) {
                                    switch ($value1['name']) {
                                        case 'after':
                                            $table->smallIncrements($value['field_name'])->after($value1['value']);
                                            break;

                                        case 'comment':
                                            $table->smallIncrements($value['field_name'])->comment($value1['value']);
                                            break;

                                        case 'default':
                                            $table->smallIncrements($value['field_name'])->default($value1['value']);
                                            break;

                                        case 'first':
                                            $table->smallIncrements($value['field_name'])->first();
                                            break;

                                        case 'storedAs':
                                            $table->smallIncrements($value['field_name'])->storedAs($value1['value']);
                                            break;

                                        case 'unsigned':
                                            $table->smallIncrements($value['field_name'])->unsigned();
                                            break;
                                        
                                        case 'virtualAs':
                                            $table->smallIncrements($value['field_name'])->virtualAs($value1['value']);
                                            break;

                                        default:
                                            # code...
                                            break;
                                    }
                                }
                            }
                            $table->smallIncrements($value['field_name'])->nullable();
                            break;

                        case 'smallInteger':
                            if(!empty($value['data_type_modifier'])){
                                foreach ($value['data_type_modifier'] as $key1 => $value1) {
                                    switch ($value1['name']) {
                                        case 'after':
                                            $table->smallInteger($value['field_name'])->after($value1['value']);
                                            break;

                                        case 'comment':
                                            $table->smallInteger($value['field_name'])->comment($value1['value']);
                                            break;

                                        case 'default':
                                            $table->smallInteger($value['field_name'])->default($value1['value']);
                                            break;

                                        case 'first':
                                            $table->smallInteger($value['field_name'])->first();
                                            break;

                                        case 'storedAs':
                                            $table->smallInteger($value['field_name'])->storedAs($value1['value']);
                                            break;

                                        case 'unsigned':
                                            $table->smallInteger($value['field_name'])->unsigned();
                                            break;
                                        
                                        case 'virtualAs':
                                            $table->smallInteger($value['field_name'])->virtualAs($value1['value']);
                                            break;

                                        default:
                                            # code...
                                            break;
                                    }
                                }
                            }
                            $table->smallInteger($value['field_name'])->nullable();
                            break;

                        case 'softDeletes':
                            if(!empty($value['data_type_modifier'])){
                                foreach ($value['data_type_modifier'] as $key1 => $value1) {
                                    switch ($value1['name']) {
                                        case 'after':
                                            $table->softDeletes($value['field_name'])->after($value1['value']);
                                            break;

                                        case 'comment':
                                            $table->softDeletes($value['field_name'])->comment($value1['value']);
                                            break;

                                        case 'default':
                                            $table->softDeletes($value['field_name'])->default($value1['value']);
                                            break;

                                        case 'first':
                                            $table->softDeletes($value['field_name'])->first();
                                            break;

                                        case 'storedAs':
                                            $table->softDeletes($value['field_name'])->storedAs($value1['value']);
                                            break;

                                        case 'unsigned':
                                            $table->softDeletes($value['field_name'])->unsigned();
                                            break;
                                        
                                        case 'virtualAs':
                                            $table->softDeletes($value['field_name'])->virtualAs($value1['value']);
                                            break;

                                        default:
                                            # code...
                                            break;
                                    }
                                }
                            }
                            $table->softDeletes($value['field_name'])->nullable();
                            break;

                        case 'string':
                            if(!empty($value['data_type_modifier'])){
                                foreach ($value['data_type_modifier'] as $key1 => $value1) {
                                    switch ($value1['name']) {
                                        case 'after':
                                            $table->string($value['field_name'])->after($value1['value']);
                                            break;

                                        case 'comment':
                                            $table->string($value['field_name'])->comment($value1['value']);
                                            break;

                                        case 'default':
                                            $table->string($value['field_name'])->default($value1['value']);
                                            break;

                                        case 'first':
                                            $table->string($value['field_name'])->first();
                                            break;

                                        case 'storedAs':
                                            $table->string($value['field_name'])->storedAs($value1['value']);
                                            break;

                                        case 'unsigned':
                                            $table->string($value['field_name'])->unsigned();
                                            break;
                                        
                                        case 'virtualAs':
                                            $table->string($value['field_name'])->virtualAs($value1['value']);
                                            break;

                                        default:
                                            # code...
                                            break;
                                    }
                                }
                            }
                            $table->string($value['field_name'])->nullable();
                            break;

                        case 'string':
                            if(!empty($value['data_type_modifier'])){
                                foreach ($value['data_type_modifier'] as $key1 => $value1) {
                                    switch ($value1['name']) {
                                        case 'after':
                                            $table->string($value['field_name'])->after($value1['value']);
                                            break;

                                        case 'comment':
                                            $table->string($value['field_name'])->comment($value1['value']);
                                            break;

                                        case 'default':
                                            $table->string($value['field_name'])->default($value1['value']);
                                            break;

                                        case 'first':
                                            $table->string($value['field_name'])->first();
                                            break;

                                        case 'storedAs':
                                            $table->string($value['field_name'])->storedAs($value1['value']);
                                            break;

                                        case 'unsigned':
                                            $table->string($value['field_name'])->unsigned();
                                            break;
                                        
                                        case 'virtualAs':
                                            $table->string($value['field_name'])->virtualAs($value1['value']);
                                            break;

                                        default:
                                            # code...
                                            break;
                                    }
                                }
                            }
                            $table->string($value['field_name'])->nullable();
                            break;

                        case 'text':
                            if(!empty($value['data_type_modifier'])){
                                foreach ($value['data_type_modifier'] as $key1 => $value1) {
                                    switch ($value1['name']) {
                                        case 'after':
                                            $table->text($value['field_name'])->after($value1['value']);
                                            break;

                                        case 'comment':
                                            $table->text($value['field_name'])->comment($value1['value']);
                                            break;

                                        case 'default':
                                            $table->text($value['field_name'])->default($value1['value']);
                                            break;

                                        case 'first':
                                            $table->text($value['field_name'])->first();
                                            break;

                                        case 'storedAs':
                                            $table->text($value['field_name'])->storedAs($value1['value']);
                                            break;

                                        case 'unsigned':
                                            $table->text($value['field_name'])->unsigned();
                                            break;
                                        
                                        case 'virtualAs':
                                            $table->text($value['field_name'])->virtualAs($value1['value']);
                                            break;

                                        default:
                                            # code...
                                            break;
                                    }
                                }
                            }
                            $table->text($value['field_name'])->nullable();
                            break;

                        case 'time':
                            if(!empty($value['data_type_modifier'])){
                                foreach ($value['data_type_modifier'] as $key1 => $value1) {
                                    switch ($value1['name']) {
                                        case 'after':
                                            $table->time($value['field_name'])->after($value1['value']);
                                            break;

                                        case 'comment':
                                            $table->time($value['field_name'])->comment($value1['value']);
                                            break;

                                        case 'default':
                                            $table->time($value['field_name'])->default($value1['value']);
                                            break;

                                        case 'first':
                                            $table->time($value['field_name'])->first();
                                            break;

                                        case 'storedAs':
                                            $table->time($value['field_name'])->storedAs($value1['value']);
                                            break;

                                        case 'unsigned':
                                            $table->time($value['field_name'])->unsigned();
                                            break;
                                        
                                        case 'virtualAs':
                                            $table->time($value['field_name'])->virtualAs($value1['value']);
                                            break;

                                        default:
                                            # code...
                                            break;
                                    }
                                }
                            }
                            $table->time($value['field_name'])->nullable();
                            break;

                        case 'timeTz':
                            if(!empty($value['data_type_modifier'])){
                                foreach ($value['data_type_modifier'] as $key1 => $value1) {
                                    switch ($value1['name']) {
                                        case 'after':
                                            $table->timeTz($value['field_name'])->after($value1['value']);
                                            break;

                                        case 'comment':
                                            $table->timeTz($value['field_name'])->comment($value1['value']);
                                            break;

                                        case 'default':
                                            $table->timeTz($value['field_name'])->default($value1['value']);
                                            break;

                                        case 'first':
                                            $table->timeTz($value['field_name'])->first();
                                            break;

                                        case 'storedAs':
                                            $table->timeTz($value['field_name'])->storedAs($value1['value']);
                                            break;

                                        case 'unsigned':
                                            $table->timeTz($value['field_name'])->unsigned();
                                            break;
                                        
                                        case 'virtualAs':
                                            $table->timeTz($value['field_name'])->virtualAs($value1['value']);
                                            break;

                                        default:
                                            # code...
                                            break;
                                    }
                                }
                            }
                            $table->timeTz($value['field_name'])->nullable();
                            break;

                        case 'tinyInteger':
                            if(!empty($value['data_type_modifier'])){
                                foreach ($value['data_type_modifier'] as $key1 => $value1) {
                                    switch ($value1['name']) {
                                        case 'after':
                                            $table->tinyInteger($value['field_name'])->after($value1['value']);
                                            break;

                                        case 'comment':
                                            $table->tinyInteger($value['field_name'])->comment($value1['value']);
                                            break;

                                        case 'default':
                                            $table->tinyInteger($value['field_name'])->default($value1['value']);
                                            break;

                                        case 'first':
                                            $table->tinyInteger($value['field_name'])->first();
                                            break;

                                        case 'storedAs':
                                            $table->tinyInteger($value['field_name'])->storedAs($value1['value']);
                                            break;

                                        case 'unsigned':
                                            $table->tinyInteger($value['field_name'])->unsigned();
                                            break;
                                        
                                        case 'virtualAs':
                                            $table->tinyInteger($value['field_name'])->virtualAs($value1['value']);
                                            break;

                                        default:
                                            # code...
                                            break;
                                    }
                                }
                            }
                            $table->tinyInteger($value['field_name'])->nullable();
                            break;

                        case 'timestamp':
                            if(!empty($value['data_type_modifier'])){
                                foreach ($value['data_type_modifier'] as $key1 => $value1) {
                                    switch ($value1['name']) {
                                        case 'after':
                                            $table->timestamp($value['field_name'])->after($value1['value']);
                                            break;

                                        case 'comment':
                                            $table->timestamp($value['field_name'])->comment($value1['value']);
                                            break;

                                        case 'default':
                                            $table->timestamp($value['field_name'])->default($value1['value']);
                                            break;

                                        case 'first':
                                            $table->timestamp($value['field_name'])->first();
                                            break;

                                        case 'storedAs':
                                            $table->timestamp($value['field_name'])->storedAs($value1['value']);
                                            break;

                                        case 'unsigned':
                                            $table->timestamp($value['field_name'])->unsigned();
                                            break;
                                        
                                        case 'virtualAs':
                                            $table->timestamp($value['field_name'])->virtualAs($value1['value']);
                                            break;

                                        default:
                                            # code...
                                            break;
                                    }
                                }
                            }
                            $table->timestamp($value['field_name'])->nullable();
                            break;

                        case 'timestampTz':
                            if(!empty($value['data_type_modifier'])){
                                foreach ($value['data_type_modifier'] as $key1 => $value1) {
                                    switch ($value1['name']) {
                                        case 'after':
                                            $table->timestampTz($value['field_name'])->after($value1['value']);
                                            break;

                                        case 'comment':
                                            $table->timestampTz($value['field_name'])->comment($value1['value']);
                                            break;

                                        case 'default':
                                            $table->timestampTz($value['field_name'])->default($value1['value']);
                                            break;

                                        case 'first':
                                            $table->timestampTz($value['field_name'])->first();
                                            break;

                                        case 'storedAs':
                                            $table->timestampTz($value['field_name'])->storedAs($value1['value']);
                                            break;

                                        case 'unsigned':
                                            $table->timestampTz($value['field_name'])->unsigned();
                                            break;
                                        
                                        case 'virtualAs':
                                            $table->timestampTz($value['field_name'])->virtualAs($value1['value']);
                                            break;

                                        default:
                                            # code...
                                            break;
                                    }
                                }
                            }
                            $table->timestampTz($value['field_name'])->nullable();
                            break;

                        case 'timestamps':
                            if(!empty($value['data_type_modifier'])){
                                foreach ($value['data_type_modifier'] as $key1 => $value1) {
                                    switch ($value1['name']) {
                                        case 'after':
                                            $table->timestamps($value['field_name'])->after($value1['value']);
                                            break;

                                        case 'comment':
                                            $table->timestamps($value['field_name'])->comment($value1['value']);
                                            break;

                                        case 'default':
                                            $table->timestamps($value['field_name'])->default($value1['value']);
                                            break;

                                        case 'first':
                                            $table->timestamps($value['field_name'])->first();
                                            break;

                                        case 'storedAs':
                                            $table->timestamps($value['field_name'])->storedAs($value1['value']);
                                            break;

                                        case 'unsigned':
                                            $table->timestamps($value['field_name'])->unsigned();
                                            break;
                                        
                                        case 'virtualAs':
                                            $table->timestamps($value['field_name'])->virtualAs($value1['value']);
                                            break;

                                        default:
                                            # code...
                                            break;
                                    }
                                }
                            }
                            $table->timestamps();
                            break;

                        case 'timestampsTz':
                            if(!empty($value['data_type_modifier'])){
                                foreach ($value['data_type_modifier'] as $key1 => $value1) {
                                    switch ($value1['name']) {
                                        case 'after':
                                            $table->timestampsTz($value['field_name'])->after($value1['value']);
                                            break;

                                        case 'comment':
                                            $table->timestampsTz($value['field_name'])->comment($value1['value']);
                                            break;

                                        case 'default':
                                            $table->timestampsTz($value['field_name'])->default($value1['value']);
                                            break;

                                        case 'first':
                                            $table->timestampsTz($value['field_name'])->first();
                                            break;

                                        case 'storedAs':
                                            $table->timestampsTz($value['field_name'])->storedAs($value1['value']);
                                            break;

                                        case 'unsigned':
                                            $table->timestampsTz($value['field_name'])->unsigned();
                                            break;
                                        
                                        case 'virtualAs':
                                            $table->timestampsTz($value['field_name'])->virtualAs($value1['value']);
                                            break;

                                        default:
                                            # code...
                                            break;
                                    }
                                }
                            }
                            $table->timestampsTz($value['field_name'])->nullable();
                            break;

                        case 'unsignedBigInteger':
                            if(!empty($value['data_type_modifier'])){
                                foreach ($value['data_type_modifier'] as $key1 => $value1) {
                                    switch ($value1['name']) {
                                        case 'after':
                                            $table->unsignedBigInteger($value['field_name'])->after($value1['value']);
                                            break;

                                        case 'comment':
                                            $table->unsignedBigInteger($value['field_name'])->comment($value1['value']);
                                            break;

                                        case 'default':
                                            $table->unsignedBigInteger($value['field_name'])->default($value1['value']);
                                            break;

                                        case 'first':
                                            $table->unsignedBigInteger($value['field_name'])->first();
                                            break;

                                        case 'storedAs':
                                            $table->unsignedBigInteger($value['field_name'])->storedAs($value1['value']);
                                            break;

                                        case 'unsigned':
                                            $table->unsignedBigInteger($value['field_name'])->unsigned();
                                            break;
                                        
                                        case 'virtualAs':
                                            $table->unsignedBigInteger($value['field_name'])->virtualAs($value1['value']);
                                            break;

                                        default:
                                            # code...
                                            break;
                                    }
                                }
                            }
                            $table->unsignedBigInteger($value['field_name'])->nullable();
                            break;

                        case 'unsignedInteger':
                            if(!empty($value['data_type_modifier'])){
                                foreach ($value['data_type_modifier'] as $key1 => $value1) {
                                    switch ($value1['name']) {
                                        case 'after':
                                            $table->unsignedInteger($value['field_name'])->after($value1['value']);
                                            break;

                                        case 'comment':
                                            $table->unsignedInteger($value['field_name'])->comment($value1['value']);
                                            break;

                                        case 'default':
                                            $table->unsignedInteger($value['field_name'])->default($value1['value']);
                                            break;

                                        case 'first':
                                            $table->unsignedInteger($value['field_name'])->first();
                                            break;

                                        case 'storedAs':
                                            $table->unsignedInteger($value['field_name'])->storedAs($value1['value']);
                                            break;

                                        case 'unsigned':
                                            $table->unsignedInteger($value['field_name'])->unsigned();
                                            break;
                                        
                                        case 'virtualAs':
                                            $table->unsignedInteger($value['field_name'])->virtualAs($value1['value']);
                                            break;

                                        default:
                                            # code...
                                            break;
                                    }
                                }
                            }
                            $table->unsignedInteger($value['field_name'])->nullable();
                            break;

                        case 'unsignedMediumInteger':
                            if(!empty($value['data_type_modifier'])){
                                foreach ($value['data_type_modifier'] as $key1 => $value1) {
                                    switch ($value1['name']) {
                                        case 'after':
                                            $table->unsignedMediumInteger($value['field_name'])->after($value1['value']);
                                            break;

                                        case 'comment':
                                            $table->unsignedMediumInteger($value['field_name'])->comment($value1['value']);
                                            break;

                                        case 'default':
                                            $table->unsignedMediumInteger($value['field_name'])->default($value1['value']);
                                            break;

                                        case 'first':
                                            $table->unsignedMediumInteger($value['field_name'])->first();
                                            break;

                                        case 'storedAs':
                                            $table->unsignedMediumInteger($value['field_name'])->storedAs($value1['value']);
                                            break;

                                        case 'unsigned':
                                            $table->unsignedMediumInteger($value['field_name'])->unsigned();
                                            break;
                                        
                                        case 'virtualAs':
                                            $table->unsignedMediumInteger($value['field_name'])->virtualAs($value1['value']);
                                            break;

                                        default:
                                            # code...
                                            break;
                                    }
                                }
                            }
                            $table->unsignedMediumInteger($value['field_name'])->nullable();
                            break;

                        case 'unsignedSmallInteger':
                            if(!empty($value['data_type_modifier'])){
                                foreach ($value['data_type_modifier'] as $key1 => $value1) {
                                    switch ($value1['name']) {
                                        case 'after':
                                            $table->unsignedSmallInteger($value['field_name'])->after($value1['value']);
                                            break;

                                        case 'comment':
                                            $table->unsignedSmallInteger($value['field_name'])->comment($value1['value']);
                                            break;

                                        case 'default':
                                            $table->unsignedSmallInteger($value['field_name'])->default($value1['value']);
                                            break;

                                        case 'first':
                                            $table->unsignedSmallInteger($value['field_name'])->first();
                                            break;

                                        case 'storedAs':
                                            $table->unsignedSmallInteger($value['field_name'])->storedAs($value1['value']);
                                            break;

                                        case 'unsigned':
                                            $table->unsignedSmallInteger($value['field_name'])->unsigned();
                                            break;
                                        
                                        case 'virtualAs':
                                            $table->unsignedSmallInteger($value['field_name'])->virtualAs($value1['value']);
                                            break;

                                        default:
                                            # code...
                                            break;
                                    }
                                }
                            }
                            $table->unsignedSmallInteger($value['field_name'])->nullable();
                            break;

                        case 'unsignedTinyInteger':
                            if(!empty($value['data_type_modifier'])){
                                foreach ($value['data_type_modifier'] as $key1 => $value1) {
                                    switch ($value1['name']) {
                                        case 'after':
                                            $table->unsignedTinyInteger($value['field_name'])->after($value1['value']);
                                            break;

                                        case 'comment':
                                            $table->unsignedTinyInteger($value['field_name'])->comment($value1['value']);
                                            break;

                                        case 'default':
                                            $table->unsignedTinyInteger($value['field_name'])->default($value1['value']);
                                            break;

                                        case 'first':
                                            $table->unsignedTinyInteger($value['field_name'])->first();
                                            break;

                                        case 'storedAs':
                                            $table->unsignedTinyInteger($value['field_name'])->storedAs($value1['value']);
                                            break;

                                        case 'unsigned':
                                            $table->unsignedTinyInteger($value['field_name'])->unsigned();
                                            break;
                                        
                                        case 'virtualAs':
                                            $table->unsignedTinyInteger($value['field_name'])->virtualAs($value1['value']);
                                            break;

                                        default:
                                            # code...
                                            break;
                                    }
                                }
                            }
                            $table->unsignedTinyInteger($value['field_name'])->nullable();
                            break;

                        case 'uuid':
                            if(!empty($value['data_type_modifier'])){
                                foreach ($value['data_type_modifier'] as $key1 => $value1) {
                                    switch ($value1['name']) {
                                        case 'after':
                                            $table->uuid($value['field_name'])->after($value1['value']);
                                            break;

                                        case 'comment':
                                            $table->uuid($value['field_name'])->comment($value1['value']);
                                            break;

                                        case 'default':
                                            $table->uuid($value['field_name'])->default($value1['value']);
                                            break;

                                        case 'first':
                                            $table->uuid($value['field_name'])->first();
                                            break;

                                        case 'storedAs':
                                            $table->uuid($value['field_name'])->storedAs($value1['value']);
                                            break;

                                        case 'unsigned':
                                            $table->uuid($value['field_name'])->unsigned();
                                            break;
                                        
                                        case 'virtualAs':
                                            $table->uuid($value['field_name'])->virtualAs($value1['value']);
                                            break;

                                        default:
                                            # code...
                                            break;
                                    }
                                }
                            }
                            $table->uuid($value['field_name'])->nullable();
                            break;

                        default:
                            # code...
                            break;
                    }

                    if(!empty($value->my_sql_index)){
                        foreach ($value->my_sql_index as $key1 => $value1) {
                            switch ($value1['name']) {
                                case 'primary':
                                    $table->primary($value1['value']);
                                    break;

                                case 'unique':
                                    $table->unique($value1['value']);
                                    break;

                                case 'index':
                                    $table->index($value1['value']);
                                    break;

                                default:
                                    # code...
                                    break;
                            }
                        }
                    }
                }

                if(!empty($composite_indexes)){
                    foreach ($composite_indexes as $key => $value) {
                        switch ($value->name) {
                            case 'primary':
                                $table->primary($value['value']);
                                break;

                            case 'unique':
                                $table->unique($value['value']);
                                break;

                            default:
                                # code...
                                break;
                        }
                    }
                }
                $table->timestamps();
        }); 
        if (!file_exists(app_path() ."/Models/".$this->auth_user->id)) {
            mkdir(app_path() ."/Models/".$this->auth_user->id, 0777, true);
        }
        $myfile = fopen(app_path() ."/Models/".$this->auth_user->id.'/'.$this->auth_user->active_app_id. ucwords(rtrim($newtable,'s')) .".php", "w");
        $line1 = "<?php\n";
        $line2 = "namespace App\Models\\".$this->auth_user->id . ";\n";

        if($authenticatable && $notifiable){
            $line3a = "use Laravel\Passport\HasApiTokens;\n";
            $line3b = "use Illuminate\Foundation\Auth\User as Authenticatable;\n";
            $line3c = "use Tymon\JWTAuth\Contracts\JWTSubject\n";
            $line3d = "use Illuminate\Notifications\Notifiable;\n";
            $line3 = $line3a . $line3b . $line3c . $line3d ;
            $line4 = "class ".$newtable." extends Authenticatable implements JWTSubject\n";
            $line6 = "use HasApiTokens, Notifiable;\n" . "public $" ."table = '" .$newtable ."';\n";
            $line10a = "public function getJWTIdentifier(){return $this->getKey();}\n";
            $line10b = "public function getJWTCustomClaims(){return [];}\n";
            $line10 = $line10a . $line10b;
        }elseif(!$authenticatable && $notifiable){
            $line3b = "use Illuminate\Database\Eloquent\Model;\n";
            $line3d = "use Illuminate\Notifications\Notifiable;\n";
            $line3 =  $line3b . $line3d ;
            $line4 = "class ".$newtable." extends Model\n";
            $line6 = "use HasApiTokens, Notifiable;\n" . "public $" ."table = '" .$newtable ."';\n";
            $line10a = "public function getJWTIdentifier(){return $this->getKey();}\n";
            $line10b = "public function getJWTCustomClaims(){return [];}\n";
            $line10 = $line10a . $line10b;
        }elseif($authenticatable && !$notifiable){
            $line3a = "use Laravel\Passport\HasApiTokens;\n";
            $line3b = "use Illuminate\Foundation\Auth\User as Authenticatable;\n";
            $line3c = "use Tymon\JWTAuth\Contracts\JWTSubject\n";
            $line3 = $line3a . $line3b . $line3c ;
            $line4 = "class ".$newtable." extends Authenticatable implements JWTSubject\n";
            $line6 = "use HasApiTokens, Notifiable;\n" . "public $" ."table = '" .$newtable ."';\n";
            $line10a = "public function getJWTIdentifier(){return $this->getKey();}\n";
            $line10b = "public function getJWTCustomClaims(){return [];}\n";
            $line10 = $line10a . $line10b;
        }else{
            $line3 = "use Illuminate\Database\Eloquent\Model;\n";
            $line4 = "class ".$newtable." extends Model\n";
            $line6 = "public $" ."table = '" .$newtable ."';\n";
            $line10 = "";
        }
        
        $line5 = "{\n";
        
        $line7 = "protected $" ."fillable = [\n'";
        $line8 = implode("', '", $fillable) ."'];\n";
        $line9 = "protected $" ."hidden = [\n'". implode("', '", $hidden) ."'];\n";

        $line11 = "}\n";

        $txt = $line1.$line2.$line3.$line4.$line5.$line6.$line7.$line8.$line9.$line10.$line11;

        fwrite($myfile, $txt);
        fclose($myfile);
        return ['table created successfully'];
    }

    public function updateTable()
    {
        \Log::Info("updateTable");
        // \Log::Info($this->request);
        // \Log::Info(strlen($this->request));
        $dbname = $this->request->database_name;
        $newtable = $this->request->table_name;
        $group_name = $this->request->group_name;
        $table_description = $this->request->table_description;
        $keywords = $this->request->keywords;
        $authenticatable = $this->request->authenticatable;
        $notifiable = $this->request->notifiable;
        $delete_fields = explode( str_replace( " ", "", $this->request->delete_fields) , "," );
        $fields = $this->request->fields;
        $composite_indexes = $this->request->composite_indexes;

        $this->updateDotEnv('DB_DATABASE', $dbname);

        if(!Schema::hasTable($newtable)){
            return ['table doesnot exists create it before updating.'];
        }

        $this->setTableSelf("mastertablelist");
        // gzcompress( $string )
        $fillable = unserialize( base64_decode( $this->table::where('table_name', $newtable)->first()->fillable ) );
        $hidden = unserialize( base64_decode( $this->table::where('table_name', $newtable)->first()->hidden ) );
        $field_indexes = unserialize( base64_decode( $this->table::where('table_name', $newtable)->first()->field_indexes ) );

        $this->setTableSelf("masterfieldlist");
        $deletefield_indexes = array();
        foreach ($delete_fields as $key => $value) {
            foreach ($this->table::where("field_name", $value)->get() as $key1 => $value1) {
                array_push($deletefield_indexes, $value1->sr);
            }
        }

        $field_indexes = array_diff($field_indexes, $deletefield_indexes);
        $fillable = array_diff($fillable, $delete_fields);
        $hidden = array_diff($hidden, $delete_fields);

        foreach ($fields as $key => $value) {
            $existing_field = $this->table::where("field_name", $value['field_name'])->where("data_type", $value['data_type'])->first();
            if(empty($existing_field)){
                $lastSr = $this->table::count() + 1;
                $this->table::create([
                    "sr" => $lastSr,
                    "field_name" => $value['field_name'],
                    "data_type" => $value['data_type']
                ]);
                array_push($field_indexes, $lastSr);
            }else{
                array_push($field_indexes, $existing_field->sr);
            }

            switch ($value['elequent_array']) {
                case 'fillable':
                    array_push($fillable, $value['field_name']);
                    break;
                
                case 'hidden':
                    array_push($hidden, $value['field_name']);
                    break;

                case 'both':
                    array_push($fillable, $value['field_name']);
                    array_push($hidden, $value['field_name']);
                    break;
                
                default:
                    //
                    break;
            }
        }

        $this->setTableSelf("mastertablelist");
        $record = $this->table::where('table_name', $this->auth_user->active_app_id.$newtable)->update([
            "table_description" => $table_description,
            "keywords" => $keywords,
            "field_indexes" => base64_encode( serialize( $field_indexes ) ),
            "fillable" => base64_encode( serialize( $fillable ) ),
            "hidden" => base64_encode( serialize( $hidden ) ),
        ]);

        Schema::table($this->auth_user->active_app_id.$newtable, function (Blueprint $table) use ($fields, $composite_indexes, $delete_fields) {
            $table->dropColumn($delete_fields);
                foreach ($fields as $key => $value) {
                    switch ($value['data_type']) {
                        case 'bigIncrements':
                            if(!empty($value['data_type_modifier'])){
                                foreach ($value['data_type_modifier'] as $key1 => $value1) {
                                    switch ($value1['name']) {
                                        case 'after':
                                            $table->bigIncrements($value['field_name'])->after($value1['value']);
                                            break;

                                        case 'comment':
                                            $table->bigIncrements($value['field_name'])->comment($value1['value']);
                                            break;

                                        case 'default':
                                            $table->bigIncrements($value['field_name'])->default($value1['value']);
                                            break;

                                        case 'first':
                                            $table->bigIncrements($value['field_name'])->first();
                                            break;

                                        case 'storedAs':
                                            $table->bigIncrements($value['field_name'])->storedAs($value1['value']);
                                            break;

                                        case 'unsigned':
                                            $table->bigIncrements($value['field_name'])->unsigned();
                                            break;
                                        
                                        case 'virtualAs':
                                            $table->bigIncrements($value['field_name'])->virtualAs($value1['value']);
                                            break;

                                        default:
                                            # code...
                                            break;
                                    }
                                }
                            }
                            $table->bigIncrements($value['field_name'])->nullable();
                            break;

                        case 'bigInteger':
                            if(!empty($value['data_type_modifier'])){
                                foreach ($value['data_type_modifier'] as $key1 => $value1) {
                                    switch ($value1['name']) {
                                        case 'after':
                                            $table->bigInteger($value['field_name'])->after($value1['value']);
                                            break;

                                        case 'comment':
                                            $table->bigInteger($value['field_name'])->comment($value1['value']);
                                            break;

                                        case 'default':
                                            $table->bigInteger($value['field_name'])->default($value1['value']);
                                            break;

                                        case 'first':
                                            $table->bigInteger($value['field_name'])->first();
                                            break;

                                        case 'storedAs':
                                            $table->bigInteger($value['field_name'])->storedAs($value1['value']);
                                            break;

                                        case 'unsigned':
                                            $table->bigInteger($value['field_name'])->unsigned();
                                            break;
                                        
                                        case 'virtualAs':
                                            $table->bigInteger($value['field_name'])->virtualAs($value1['value']);
                                            break;

                                        default:
                                            # code...
                                            break;
                                    }
                                }
                            }
                            $table->bigInteger($value['field_name'])->nullable();
                            break;

                        case 'binary':
                            if(!empty($value['data_type_modifier'])){
                                foreach ($value['data_type_modifier'] as $key1 => $value1) {
                                    switch ($value1['name']) {
                                        case 'after':
                                            $table->binary($value['field_name'])->after($value1['value']);
                                            break;

                                        case 'comment':
                                            $table->binary($value['field_name'])->comment($value1['value']);
                                            break;

                                        case 'default':
                                            $table->binary($value['field_name'])->default($value1['value']);
                                            break;

                                        case 'first':
                                            $table->binary($value['field_name'])->first();
                                            break;

                                        case 'storedAs':
                                            $table->binary($value['field_name'])->storedAs($value1['value']);
                                            break;

                                        case 'unsigned':
                                            $table->binary($value['field_name'])->unsigned();
                                            break;
                                        
                                        case 'virtualAs':
                                            $table->binary($value['field_name'])->virtualAs($value1['value']);
                                            break;

                                        default:
                                            # code...
                                            break;
                                    }
                                }
                            }
                            $table->binary($value['field_name'])->nullable();
                            break;

                        case 'boolean':
                            if(!empty($value['data_type_modifier'])){
                                foreach ($value['data_type_modifier'] as $key1 => $value1) {
                                    switch ($value1['name']) {
                                        case 'after':
                                            $table->boolean($value['field_name'])->after($value1['value']);
                                            break;

                                        case 'comment':
                                            $table->boolean($value['field_name'])->comment($value1['value']);
                                            break;

                                        case 'default':
                                            $table->boolean($value['field_name'])->default($value1['value']);
                                            break;

                                        case 'first':
                                            $table->boolean($value['field_name'])->first();
                                            break;

                                        case 'storedAs':
                                            $table->boolean($value['field_name'])->storedAs($value1['value']);
                                            break;

                                        case 'unsigned':
                                            $table->boolean($value['field_name'])->unsigned();
                                            break;
                                        
                                        case 'virtualAs':
                                            $table->boolean($value['field_name'])->virtualAs($value1['value']);
                                            break;

                                        default:
                                            # code...
                                            break;
                                    }
                                }
                            }
                            $table->boolean($value['field_name'])->nullable();
                            break;

                        case 'char':
                            if(!empty($value['data_type_modifier'])){
                                foreach ($value['data_type_modifier'] as $key1 => $value1) {
                                    switch ($value1['name']) {
                                        case 'after':
                                            $table->char($value['field_name'])->after($value1['value']);
                                            break;

                                        case 'comment':
                                            $table->char($value['field_name'])->comment($value1['value']);
                                            break;

                                        case 'default':
                                            $table->char($value['field_name'])->default($value1['value']);
                                            break;

                                        case 'first':
                                            $table->char($value['field_name'])->first();
                                            break;

                                        case 'storedAs':
                                            $table->char($value['field_name'])->storedAs($value1['value']);
                                            break;

                                        case 'unsigned':
                                            $table->char($value['field_name'])->unsigned();
                                            break;
                                        
                                        case 'virtualAs':
                                            $table->char($value['field_name'])->virtualAs($value1['value']);
                                            break;

                                        default:
                                            # code...
                                            break;
                                    }
                                }
                            }
                            $table->char($value['field_name'])->nullable();
                            break;

                        case 'date':
                            if(!empty($value['data_type_modifier'])){
                                foreach ($value['data_type_modifier'] as $key1 => $value1) {
                                    switch ($value1['name']) {
                                        case 'after':
                                            $table->date($value['field_name'])->after($value1['value']);
                                            break;

                                        case 'comment':
                                            $table->date($value['field_name'])->comment($value1['value']);
                                            break;

                                        case 'default':
                                            $table->date($value['field_name'])->default($value1['value']);
                                            break;

                                        case 'first':
                                            $table->date($value['field_name'])->first();
                                            break;

                                        case 'storedAs':
                                            $table->date($value['field_name'])->storedAs($value1['value']);
                                            break;

                                        case 'unsigned':
                                            $table->date($value['field_name'])->unsigned();
                                            break;
                                        
                                        case 'virtualAs':
                                            $table->date($value['field_name'])->virtualAs($value1['value']);
                                            break;

                                        default:
                                            # code...
                                            break;
                                    }
                                }
                            }
                            $table->date($value['field_name'])->nullable();
                            break;

                        case 'dateTime':
                            if(!empty($value['data_type_modifier'])){
                                foreach ($value['data_type_modifier'] as $key1 => $value1) {
                                    switch ($value1['name']) {
                                        case 'after':
                                            $table->dateTime($value['field_name'])->after($value1['value']);
                                            break;

                                        case 'comment':
                                            $table->dateTime($value['field_name'])->comment($value1['value']);
                                            break;

                                        case 'default':
                                            $table->dateTime($value['field_name'])->default($value1['value']);
                                            break;

                                        case 'first':
                                            $table->dateTime($value['field_name'])->first();
                                            break;

                                        case 'storedAs':
                                            $table->dateTime($value['field_name'])->storedAs($value1['value']);
                                            break;

                                        case 'unsigned':
                                            $table->dateTime($value['field_name'])->unsigned();
                                            break;
                                        
                                        case 'virtualAs':
                                            $table->dateTime($value['field_name'])->virtualAs($value1['value']);
                                            break;

                                        default:
                                            # code...
                                            break;
                                    }
                                }
                            }
                            $table->dateTime($value['field_name'])->nullable();
                            break;

                        case 'dateTimeTz':
                            if(!empty($value['data_type_modifier'])){
                                foreach ($value['data_type_modifier'] as $key1 => $value1) {
                                    switch ($value1['name']) {
                                        case 'after':
                                            $table->dateTimeTz($value['field_name'])->after($value1['value']);
                                            break;

                                        case 'comment':
                                            $table->dateTimeTz($value['field_name'])->comment($value1['value']);
                                            break;

                                        case 'default':
                                            $table->dateTimeTz($value['field_name'])->default($value1['value']);
                                            break;

                                        case 'first':
                                            $table->dateTimeTz($value['field_name'])->first();
                                            break;

                                        case 'storedAs':
                                            $table->dateTimeTz($value['field_name'])->storedAs($value1['value']);
                                            break;

                                        case 'unsigned':
                                            $table->dateTimeTz($value['field_name'])->unsigned();
                                            break;
                                        
                                        case 'virtualAs':
                                            $table->dateTimeTz($value['field_name'])->virtualAs($value1['value']);
                                            break;

                                        default:
                                            # code...
                                            break;
                                    }
                                }
                            }
                            $table->dateTimeTz($value['field_name'])->nullable();
                            break;

                        case 'decimal':
                            if(!empty($value['data_type_modifier'])){
                                foreach ($value['data_type_modifier'] as $key1 => $value1) {
                                    switch ($value1['name']) {
                                        case 'after':
                                            $table->decimal($value['field_name'])->after($value1['value']);
                                            break;

                                        case 'comment':
                                            $table->decimal($value['field_name'])->comment($value1['value']);
                                            break;

                                        case 'default':
                                            $table->decimal($value['field_name'])->default($value1['value']);
                                            break;

                                        case 'first':
                                            $table->decimal($value['field_name'])->first();
                                            break;

                                        case 'storedAs':
                                            $table->decimal($value['field_name'])->storedAs($value1['value']);
                                            break;

                                        case 'unsigned':
                                            $table->decimal($value['field_name'])->unsigned();
                                            break;
                                        
                                        case 'virtualAs':
                                            $table->decimal($value['field_name'])->virtualAs($value1['value']);
                                            break;

                                        default:
                                            # code...
                                            break;
                                    }
                                }
                            }
                            $table->decimal($value['field_name'])->nullable();
                            break;

                        case 'double':
                            if(!empty($value['data_type_modifier'])){
                                foreach ($value['data_type_modifier'] as $key1 => $value1) {
                                    switch ($value1['name']) {
                                        case 'after':
                                            $table->double($value['field_name'])->after($value1['value']);
                                            break;

                                        case 'comment':
                                            $table->double($value['field_name'])->comment($value1['value']);
                                            break;

                                        case 'default':
                                            $table->double($value['field_name'])->default($value1['value']);
                                            break;

                                        case 'first':
                                            $table->double($value['field_name'])->first();
                                            break;

                                        case 'storedAs':
                                            $table->double($value['field_name'])->storedAs($value1['value']);
                                            break;

                                        case 'unsigned':
                                            $table->double($value['field_name'])->unsigned();
                                            break;
                                        
                                        case 'virtualAs':
                                            $table->double($value['field_name'])->virtualAs($value1['value']);
                                            break;

                                        default:
                                            # code...
                                            break;
                                    }
                                }
                            }
                            $table->double($value['field_name'])->nullable();
                            break;

                        case 'enum':
                            if(!empty($value['data_type_modifier'])){
                                foreach ($value['data_type_modifier'] as $key1 => $value1) {
                                    switch ($value1['name']) {
                                        case 'after':
                                            $table->enum($value['field_name'])->after($value1['value']);
                                            break;

                                        case 'comment':
                                            $table->enum($value['field_name'])->comment($value1['value']);
                                            break;

                                        case 'default':
                                            $table->enum($value['field_name'])->default($value1['value']);
                                            break;

                                        case 'first':
                                            $table->enum($value['field_name'])->first();
                                            break;

                                        case 'storedAs':
                                            $table->enum($value['field_name'])->storedAs($value1['value']);
                                            break;

                                        case 'unsigned':
                                            $table->enum($value['field_name'])->unsigned();
                                            break;
                                        
                                        case 'virtualAs':
                                            $table->enum($value['field_name'])->virtualAs($value1['value']);
                                            break;

                                        default:
                                            # code...
                                            break;
                                    }
                                }
                            }
                            $table->enum($value['field_name'])->nullable();
                            break;

                        case 'float':
                            if(!empty($value['data_type_modifier'])){
                                foreach ($value['data_type_modifier'] as $key1 => $value1) {
                                    switch ($value1['name']) {
                                        case 'after':
                                            $table->float($value['field_name'])->after($value1['value']);
                                            break;

                                        case 'comment':
                                            $table->float($value['field_name'])->comment($value1['value']);
                                            break;

                                        case 'default':
                                            $table->float($value['field_name'])->default($value1['value']);
                                            break;

                                        case 'first':
                                            $table->float($value['field_name'])->first();
                                            break;

                                        case 'storedAs':
                                            $table->float($value['field_name'])->storedAs($value1['value']);
                                            break;

                                        case 'unsigned':
                                            $table->float($value['field_name'])->unsigned();
                                            break;
                                        
                                        case 'virtualAs':
                                            $table->float($value['field_name'])->virtualAs($value1['value']);
                                            break;

                                        default:
                                            # code...
                                            break;
                                    }
                                }
                            }
                            $table->float($value['field_name'])->nullable();
                            break;

                        case 'increments':
                            if(!empty($value['data_type_modifier'])){
                                foreach ($value['data_type_modifier'] as $key1 => $value1) {
                                    switch ($value1['name']) {
                                        case 'after':
                                            $table->increments($value['field_name'])->after($value1['value']);
                                            break;

                                        case 'comment':
                                            $table->increments($value['field_name'])->comment($value1['value']);
                                            break;

                                        case 'default':
                                            $table->increments($value['field_name'])->default($value1['value']);
                                            break;

                                        case 'first':
                                            $table->increments($value['field_name'])->first();
                                            break;

                                        case 'storedAs':
                                            $table->increments($value['field_name'])->storedAs($value1['value']);
                                            break;

                                        case 'unsigned':
                                            $table->increments($value['field_name'])->unsigned();
                                            break;
                                        
                                        case 'virtualAs':
                                            $table->increments($value['field_name'])->virtualAs($value1['value']);
                                            break;

                                        default:
                                            # code...
                                            break;
                                    }
                                }
                            }
                            $table->increments($value['field_name'])->nullable();
                            break;

                        case 'integer':
                            if(!empty($value['data_type_modifier'])){
                                foreach ($value['data_type_modifier'] as $key1 => $value1) {
                                    switch ($value1['name']) {
                                        case 'after':
                                            $table->integer($value['field_name'])->after($value1['value']);
                                            break;

                                        case 'comment':
                                            $table->integer($value['field_name'])->comment($value1['value']);
                                            break;

                                        case 'default':
                                            $table->integer($value['field_name'])->default($value1['value']);
                                            break;

                                        case 'first':
                                            $table->integer($value['field_name'])->first();
                                            break;

                                        case 'storedAs':
                                            $table->integer($value['field_name'])->storedAs($value1['value']);
                                            break;

                                        case 'unsigned':
                                            $table->integer($value['field_name'])->unsigned();
                                            break;
                                        
                                        case 'virtualAs':
                                            $table->integer($value['field_name'])->virtualAs($value1['value']);
                                            break;

                                        default:
                                            # code...
                                            break;
                                    }
                                }
                            }
                            $table->integer($value['field_name'])->nullable();
                            break;

                        case 'ipAddress':
                            if(!empty($value['data_type_modifier'])){
                                foreach ($value['data_type_modifier'] as $key1 => $value1) {
                                    switch ($value1['name']) {
                                        case 'after':
                                            $table->ipAddress($value['field_name'])->after($value1['value']);
                                            break;

                                        case 'comment':
                                            $table->ipAddress($value['field_name'])->comment($value1['value']);
                                            break;

                                        case 'default':
                                            $table->ipAddress($value['field_name'])->default($value1['value']);
                                            break;

                                        case 'first':
                                            $table->ipAddress($value['field_name'])->first();
                                            break;

                                        case 'storedAs':
                                            $table->ipAddress($value['field_name'])->storedAs($value1['value']);
                                            break;

                                        case 'unsigned':
                                            $table->ipAddress($value['field_name'])->unsigned();
                                            break;
                                        
                                        case 'virtualAs':
                                            $table->ipAddress($value['field_name'])->virtualAs($value1['value']);
                                            break;

                                        default:
                                            # code...
                                            break;
                                    }
                                }
                            }
                            $table->ipAddress($value['field_name'])->nullable();
                            break;

                        case 'json':
                            if(!empty($value['data_type_modifier'])){
                                foreach ($value['data_type_modifier'] as $key1 => $value1) {
                                    switch ($value1['name']) {
                                        case 'after':
                                            $table->json($value['field_name'])->after($value1['value']);
                                            break;

                                        case 'comment':
                                            $table->json($value['field_name'])->comment($value1['value']);
                                            break;

                                        case 'default':
                                            $table->json($value['field_name'])->default($value1['value']);
                                            break;

                                        case 'first':
                                            $table->json($value['field_name'])->first();
                                            break;

                                        case 'storedAs':
                                            $table->json($value['field_name'])->storedAs($value1['value']);
                                            break;

                                        case 'unsigned':
                                            $table->json($value['field_name'])->unsigned();
                                            break;
                                        
                                        case 'virtualAs':
                                            $table->json($value['field_name'])->virtualAs($value1['value']);
                                            break;

                                        default:
                                            # code...
                                            break;
                                    }
                                }
                            }
                            $table->json($value['field_name'])->nullable();
                            break;

                        case 'jsonb':
                            if(!empty($value['data_type_modifier'])){
                                foreach ($value['data_type_modifier'] as $key1 => $value1) {
                                    switch ($value1['name']) {
                                        case 'after':
                                            $table->jsonb($value['field_name'])->after($value1['value']);
                                            break;

                                        case 'comment':
                                            $table->jsonb($value['field_name'])->comment($value1['value']);
                                            break;

                                        case 'default':
                                            $table->jsonb($value['field_name'])->default($value1['value']);
                                            break;

                                        case 'first':
                                            $table->jsonb($value['field_name'])->first();
                                            break;

                                        case 'storedAs':
                                            $table->jsonb($value['field_name'])->storedAs($value1['value']);
                                            break;

                                        case 'unsigned':
                                            $table->jsonb($value['field_name'])->unsigned();
                                            break;
                                        
                                        case 'virtualAs':
                                            $table->jsonb($value['field_name'])->virtualAs($value1['value']);
                                            break;

                                        default:
                                            # code...
                                            break;
                                    }
                                }
                            }
                            $table->jsonb($value['field_name'])->nullable();
                            break;

                        case 'longText':
                            if(!empty($value['data_type_modifier'])){
                                foreach ($value['data_type_modifier'] as $key1 => $value1) {
                                    switch ($value1['name']) {
                                        case 'after':
                                            $table->longText($value['field_name'])->after($value1['value']);
                                            break;

                                        case 'comment':
                                            $table->longText($value['field_name'])->comment($value1['value']);
                                            break;

                                        case 'default':
                                            $table->longText($value['field_name'])->default($value1['value']);
                                            break;

                                        case 'first':
                                            $table->longText($value['field_name'])->first();
                                            break;

                                        case 'storedAs':
                                            $table->longText($value['field_name'])->storedAs($value1['value']);
                                            break;

                                        case 'unsigned':
                                            $table->longText($value['field_name'])->unsigned();
                                            break;
                                        
                                        case 'virtualAs':
                                            $table->longText($value['field_name'])->virtualAs($value1['value']);
                                            break;

                                        default:
                                            # code...
                                            break;
                                    }
                                }
                            }
                            $table->longText($value['field_name'])->nullable();
                            break;

                        case 'macAddress':
                            if(!empty($value['data_type_modifier'])){
                                foreach ($value['data_type_modifier'] as $key1 => $value1) {
                                    switch ($value1['name']) {
                                        case 'after':
                                            $table->macAddress($value['field_name'])->after($value1['value']);
                                            break;

                                        case 'comment':
                                            $table->macAddress($value['field_name'])->comment($value1['value']);
                                            break;

                                        case 'default':
                                            $table->macAddress($value['field_name'])->default($value1['value']);
                                            break;

                                        case 'first':
                                            $table->macAddress($value['field_name'])->first();
                                            break;

                                        case 'storedAs':
                                            $table->macAddress($value['field_name'])->storedAs($value1['value']);
                                            break;

                                        case 'unsigned':
                                            $table->macAddress($value['field_name'])->unsigned();
                                            break;
                                        
                                        case 'virtualAs':
                                            $table->macAddress($value['field_name'])->virtualAs($value1['value']);
                                            break;

                                        default:
                                            # code...
                                            break;
                                    }
                                }
                            }
                            $table->macAddress($value['field_name'])->nullable();
                            break;

                        case 'mediumIncrements':
                            if(!empty($value['data_type_modifier'])){
                                foreach ($value['data_type_modifier'] as $key1 => $value1) {
                                    switch ($value1['name']) {
                                        case 'after':
                                            $table->mediumIncrements($value['field_name'])->after($value1['value']);
                                            break;

                                        case 'comment':
                                            $table->mediumIncrements($value['field_name'])->comment($value1['value']);
                                            break;

                                        case 'default':
                                            $table->mediumIncrements($value['field_name'])->default($value1['value']);
                                            break;

                                        case 'first':
                                            $table->mediumIncrements($value['field_name'])->first();
                                            break;

                                        case 'storedAs':
                                            $table->mediumIncrements($value['field_name'])->storedAs($value1['value']);
                                            break;

                                        case 'unsigned':
                                            $table->mediumIncrements($value['field_name'])->unsigned();
                                            break;
                                        
                                        case 'virtualAs':
                                            $table->mediumIncrements($value['field_name'])->virtualAs($value1['value']);
                                            break;

                                        default:
                                            # code...
                                            break;
                                    }
                                }
                            }
                            $table->mediumIncrements($value['field_name'])->nullable();
                            break;

                        case 'mediumInteger':
                            if(!empty($value['data_type_modifier'])){
                                foreach ($value['data_type_modifier'] as $key1 => $value1) {
                                    switch ($value1['name']) {
                                        case 'after':
                                            $table->mediumInteger($value['field_name'])->after($value1['value']);
                                            break;

                                        case 'comment':
                                            $table->mediumInteger($value['field_name'])->comment($value1['value']);
                                            break;

                                        case 'default':
                                            $table->mediumInteger($value['field_name'])->default($value1['value']);
                                            break;

                                        case 'first':
                                            $table->mediumInteger($value['field_name'])->first();
                                            break;

                                        case 'storedAs':
                                            $table->mediumInteger($value['field_name'])->storedAs($value1['value']);
                                            break;

                                        case 'unsigned':
                                            $table->mediumInteger($value['field_name'])->unsigned();
                                            break;
                                        
                                        case 'virtualAs':
                                            $table->mediumInteger($value['field_name'])->virtualAs($value1['value']);
                                            break;

                                        default:
                                            # code...
                                            break;
                                    }
                                }
                            }
                            $table->mediumInteger($value['field_name'])->nullable();
                            break;

                        case 'mediumText':
                            if(!empty($value['data_type_modifier'])){
                                foreach ($value['data_type_modifier'] as $key1 => $value1) {
                                    switch ($value1['name']) {
                                        case 'after':
                                            $table->mediumText($value['field_name'])->after($value1['value']);
                                            break;

                                        case 'comment':
                                            $table->mediumText($value['field_name'])->comment($value1['value']);
                                            break;

                                        case 'default':
                                            $table->mediumText($value['field_name'])->default($value1['value']);
                                            break;

                                        case 'first':
                                            $table->mediumText($value['field_name'])->first();
                                            break;

                                        case 'storedAs':
                                            $table->mediumText($value['field_name'])->storedAs($value1['value']);
                                            break;

                                        case 'unsigned':
                                            $table->mediumText($value['field_name'])->unsigned();
                                            break;
                                        
                                        case 'virtualAs':
                                            $table->mediumText($value['field_name'])->virtualAs($value1['value']);
                                            break;

                                        default:
                                            # code...
                                            break;
                                    }
                                }
                            }
                            $table->mediumText($value['field_name'])->nullable();
                            break;

                        case 'morphs':
                            if(!empty($value['data_type_modifier'])){
                                foreach ($value['data_type_modifier'] as $key1 => $value1) {
                                    switch ($value1['name']) {
                                        case 'after':
                                            $table->morphs($value['field_name'])->after($value1['value']);
                                            break;

                                        case 'comment':
                                            $table->morphs($value['field_name'])->comment($value1['value']);
                                            break;

                                        case 'default':
                                            $table->morphs($value['field_name'])->default($value1['value']);
                                            break;

                                        case 'first':
                                            $table->morphs($value['field_name'])->first();
                                            break;

                                        case 'storedAs':
                                            $table->morphs($value['field_name'])->storedAs($value1['value']);
                                            break;

                                        case 'unsigned':
                                            $table->morphs($value['field_name'])->unsigned();
                                            break;
                                        
                                        case 'virtualAs':
                                            $table->morphs($value['field_name'])->virtualAs($value1['value']);
                                            break;

                                        default:
                                            # code...
                                            break;
                                    }
                                }
                            }
                            $table->morphs($value['field_name'])->nullable();
                            break;

                        case 'nullableMorphs':
                            if(!empty($value['data_type_modifier'])){
                                foreach ($value['data_type_modifier'] as $key1 => $value1) {
                                    switch ($value1['name']) {
                                        case 'after':
                                            $table->nullableMorphs($value['field_name'])->after($value1['value']);
                                            break;

                                        case 'comment':
                                            $table->nullableMorphs($value['field_name'])->comment($value1['value']);
                                            break;

                                        case 'default':
                                            $table->nullableMorphs($value['field_name'])->default($value1['value']);
                                            break;

                                        case 'first':
                                            $table->nullableMorphs($value['field_name'])->first();
                                            break;

                                        case 'storedAs':
                                            $table->nullableMorphs($value['field_name'])->storedAs($value1['value']);
                                            break;

                                        case 'unsigned':
                                            $table->nullableMorphs($value['field_name'])->unsigned();
                                            break;
                                        
                                        case 'virtualAs':
                                            $table->nullableMorphs($value['field_name'])->virtualAs($value1['value']);
                                            break;

                                        default:
                                            # code...
                                            break;
                                    }
                                }
                            }
                            $table->nullableMorphs($value['field_name'])->nullable();
                            break;

                        case 'nullableTimestamps':
                            if(!empty($value['data_type_modifier'])){
                                foreach ($value['data_type_modifier'] as $key1 => $value1) {
                                    switch ($value1['name']) {
                                        case 'after':
                                            $table->nullableTimestamps($value['field_name'])->after($value1['value']);
                                            break;

                                        case 'comment':
                                            $table->nullableTimestamps($value['field_name'])->comment($value1['value']);
                                            break;

                                        case 'default':
                                            $table->nullableTimestamps($value['field_name'])->default($value1['value']);
                                            break;

                                        case 'first':
                                            $table->nullableTimestamps($value['field_name'])->first();
                                            break;

                                        case 'storedAs':
                                            $table->nullableTimestamps($value['field_name'])->storedAs($value1['value']);
                                            break;

                                        case 'unsigned':
                                            $table->nullableTimestamps($value['field_name'])->unsigned();
                                            break;
                                        
                                        case 'virtualAs':
                                            $table->nullableTimestamps($value['field_name'])->virtualAs($value1['value']);
                                            break;

                                        default:
                                            # code...
                                            break;
                                    }
                                }
                            }
                            $table->nullableTimestamps($value['field_name'])->nullable();
                            break;

                        case 'rememberToken':
                            if(!empty($value['data_type_modifier'])){
                                foreach ($value['data_type_modifier'] as $key1 => $value1) {
                                    switch ($value1['name']) {
                                        case 'after':
                                            $table->rememberToken($value['field_name'])->after($value1['value']);
                                            break;

                                        case 'comment':
                                            $table->rememberToken($value['field_name'])->comment($value1['value']);
                                            break;

                                        case 'default':
                                            $table->rememberToken($value['field_name'])->default($value1['value']);
                                            break;

                                        case 'first':
                                            $table->rememberToken($value['field_name'])->first();
                                            break;

                                        case 'storedAs':
                                            $table->rememberToken($value['field_name'])->storedAs($value1['value']);
                                            break;

                                        case 'unsigned':
                                            $table->rememberToken($value['field_name'])->unsigned();
                                            break;
                                        
                                        case 'virtualAs':
                                            $table->rememberToken($value['field_name'])->virtualAs($value1['value']);
                                            break;

                                        default:
                                            # code...
                                            break;
                                    }
                                }
                            }
                            $table->rememberToken($value['field_name'])->nullable();
                            break;

                        case 'smallIncrements':
                            if(!empty($value['data_type_modifier'])){
                                foreach ($value['data_type_modifier'] as $key1 => $value1) {
                                    switch ($value1['name']) {
                                        case 'after':
                                            $table->smallIncrements($value['field_name'])->after($value1['value']);
                                            break;

                                        case 'comment':
                                            $table->smallIncrements($value['field_name'])->comment($value1['value']);
                                            break;

                                        case 'default':
                                            $table->smallIncrements($value['field_name'])->default($value1['value']);
                                            break;

                                        case 'first':
                                            $table->smallIncrements($value['field_name'])->first();
                                            break;

                                        case 'storedAs':
                                            $table->smallIncrements($value['field_name'])->storedAs($value1['value']);
                                            break;

                                        case 'unsigned':
                                            $table->smallIncrements($value['field_name'])->unsigned();
                                            break;
                                        
                                        case 'virtualAs':
                                            $table->smallIncrements($value['field_name'])->virtualAs($value1['value']);
                                            break;

                                        default:
                                            # code...
                                            break;
                                    }
                                }
                            }
                            $table->smallIncrements($value['field_name'])->nullable();
                            break;

                        case 'smallInteger':
                            if(!empty($value['data_type_modifier'])){
                                foreach ($value['data_type_modifier'] as $key1 => $value1) {
                                    switch ($value1['name']) {
                                        case 'after':
                                            $table->smallInteger($value['field_name'])->after($value1['value']);
                                            break;

                                        case 'comment':
                                            $table->smallInteger($value['field_name'])->comment($value1['value']);
                                            break;

                                        case 'default':
                                            $table->smallInteger($value['field_name'])->default($value1['value']);
                                            break;

                                        case 'first':
                                            $table->smallInteger($value['field_name'])->first();
                                            break;

                                        case 'storedAs':
                                            $table->smallInteger($value['field_name'])->storedAs($value1['value']);
                                            break;

                                        case 'unsigned':
                                            $table->smallInteger($value['field_name'])->unsigned();
                                            break;
                                        
                                        case 'virtualAs':
                                            $table->smallInteger($value['field_name'])->virtualAs($value1['value']);
                                            break;

                                        default:
                                            # code...
                                            break;
                                    }
                                }
                            }
                            $table->smallInteger($value['field_name'])->nullable();
                            break;

                        case 'softDeletes':
                            if(!empty($value['data_type_modifier'])){
                                foreach ($value['data_type_modifier'] as $key1 => $value1) {
                                    switch ($value1['name']) {
                                        case 'after':
                                            $table->softDeletes($value['field_name'])->after($value1['value']);
                                            break;

                                        case 'comment':
                                            $table->softDeletes($value['field_name'])->comment($value1['value']);
                                            break;

                                        case 'default':
                                            $table->softDeletes($value['field_name'])->default($value1['value']);
                                            break;

                                        case 'first':
                                            $table->softDeletes($value['field_name'])->first();
                                            break;

                                        case 'storedAs':
                                            $table->softDeletes($value['field_name'])->storedAs($value1['value']);
                                            break;

                                        case 'unsigned':
                                            $table->softDeletes($value['field_name'])->unsigned();
                                            break;
                                        
                                        case 'virtualAs':
                                            $table->softDeletes($value['field_name'])->virtualAs($value1['value']);
                                            break;

                                        default:
                                            # code...
                                            break;
                                    }
                                }
                            }
                            $table->softDeletes($value['field_name'])->nullable();
                            break;

                        case 'string':
                            if(!empty($value['data_type_modifier'])){
                                foreach ($value['data_type_modifier'] as $key1 => $value1) {
                                    switch ($value1['name']) {
                                        case 'after':
                                            $table->string($value['field_name'])->after($value1['value']);
                                            break;

                                        case 'comment':
                                            $table->string($value['field_name'])->comment($value1['value']);
                                            break;

                                        case 'default':
                                            $table->string($value['field_name'])->default($value1['value']);
                                            break;

                                        case 'first':
                                            $table->string($value['field_name'])->first();
                                            break;

                                        case 'storedAs':
                                            $table->string($value['field_name'])->storedAs($value1['value']);
                                            break;

                                        case 'unsigned':
                                            $table->string($value['field_name'])->unsigned();
                                            break;
                                        
                                        case 'virtualAs':
                                            $table->string($value['field_name'])->virtualAs($value1['value']);
                                            break;

                                        default:
                                            # code...
                                            break;
                                    }
                                }
                            }
                            $table->string($value['field_name'])->nullable();
                            break;

                        case 'string':
                            if(!empty($value['data_type_modifier'])){
                                foreach ($value['data_type_modifier'] as $key1 => $value1) {
                                    switch ($value1['name']) {
                                        case 'after':
                                            $table->string($value['field_name'])->after($value1['value']);
                                            break;

                                        case 'comment':
                                            $table->string($value['field_name'])->comment($value1['value']);
                                            break;

                                        case 'default':
                                            $table->string($value['field_name'])->default($value1['value']);
                                            break;

                                        case 'first':
                                            $table->string($value['field_name'])->first();
                                            break;

                                        case 'storedAs':
                                            $table->string($value['field_name'])->storedAs($value1['value']);
                                            break;

                                        case 'unsigned':
                                            $table->string($value['field_name'])->unsigned();
                                            break;
                                        
                                        case 'virtualAs':
                                            $table->string($value['field_name'])->virtualAs($value1['value']);
                                            break;

                                        default:
                                            # code...
                                            break;
                                    }
                                }
                            }
                            $table->string($value['field_name'])->nullable();
                            break;

                        case 'text':
                            if(!empty($value['data_type_modifier'])){
                                foreach ($value['data_type_modifier'] as $key1 => $value1) {
                                    switch ($value1['name']) {
                                        case 'after':
                                            $table->text($value['field_name'])->after($value1['value']);
                                            break;

                                        case 'comment':
                                            $table->text($value['field_name'])->comment($value1['value']);
                                            break;

                                        case 'default':
                                            $table->text($value['field_name'])->default($value1['value']);
                                            break;

                                        case 'first':
                                            $table->text($value['field_name'])->first();
                                            break;

                                        case 'storedAs':
                                            $table->text($value['field_name'])->storedAs($value1['value']);
                                            break;

                                        case 'unsigned':
                                            $table->text($value['field_name'])->unsigned();
                                            break;
                                        
                                        case 'virtualAs':
                                            $table->text($value['field_name'])->virtualAs($value1['value']);
                                            break;

                                        default:
                                            # code...
                                            break;
                                    }
                                }
                            }
                            $table->text($value['field_name'])->nullable();
                            break;

                        case 'time':
                            if(!empty($value['data_type_modifier'])){
                                foreach ($value['data_type_modifier'] as $key1 => $value1) {
                                    switch ($value1['name']) {
                                        case 'after':
                                            $table->time($value['field_name'])->after($value1['value']);
                                            break;

                                        case 'comment':
                                            $table->time($value['field_name'])->comment($value1['value']);
                                            break;

                                        case 'default':
                                            $table->time($value['field_name'])->default($value1['value']);
                                            break;

                                        case 'first':
                                            $table->time($value['field_name'])->first();
                                            break;

                                        case 'storedAs':
                                            $table->time($value['field_name'])->storedAs($value1['value']);
                                            break;

                                        case 'unsigned':
                                            $table->time($value['field_name'])->unsigned();
                                            break;
                                        
                                        case 'virtualAs':
                                            $table->time($value['field_name'])->virtualAs($value1['value']);
                                            break;

                                        default:
                                            # code...
                                            break;
                                    }
                                }
                            }
                            $table->time($value['field_name'])->nullable();
                            break;

                        case 'timeTz':
                            if(!empty($value['data_type_modifier'])){
                                foreach ($value['data_type_modifier'] as $key1 => $value1) {
                                    switch ($value1['name']) {
                                        case 'after':
                                            $table->timeTz($value['field_name'])->after($value1['value']);
                                            break;

                                        case 'comment':
                                            $table->timeTz($value['field_name'])->comment($value1['value']);
                                            break;

                                        case 'default':
                                            $table->timeTz($value['field_name'])->default($value1['value']);
                                            break;

                                        case 'first':
                                            $table->timeTz($value['field_name'])->first();
                                            break;

                                        case 'storedAs':
                                            $table->timeTz($value['field_name'])->storedAs($value1['value']);
                                            break;

                                        case 'unsigned':
                                            $table->timeTz($value['field_name'])->unsigned();
                                            break;
                                        
                                        case 'virtualAs':
                                            $table->timeTz($value['field_name'])->virtualAs($value1['value']);
                                            break;

                                        default:
                                            # code...
                                            break;
                                    }
                                }
                            }
                            $table->timeTz($value['field_name'])->nullable();
                            break;

                        case 'tinyInteger':
                            if(!empty($value['data_type_modifier'])){
                                foreach ($value['data_type_modifier'] as $key1 => $value1) {
                                    switch ($value1['name']) {
                                        case 'after':
                                            $table->tinyInteger($value['field_name'])->after($value1['value']);
                                            break;

                                        case 'comment':
                                            $table->tinyInteger($value['field_name'])->comment($value1['value']);
                                            break;

                                        case 'default':
                                            $table->tinyInteger($value['field_name'])->default($value1['value']);
                                            break;

                                        case 'first':
                                            $table->tinyInteger($value['field_name'])->first();
                                            break;

                                        case 'storedAs':
                                            $table->tinyInteger($value['field_name'])->storedAs($value1['value']);
                                            break;

                                        case 'unsigned':
                                            $table->tinyInteger($value['field_name'])->unsigned();
                                            break;
                                        
                                        case 'virtualAs':
                                            $table->tinyInteger($value['field_name'])->virtualAs($value1['value']);
                                            break;

                                        default:
                                            # code...
                                            break;
                                    }
                                }
                            }
                            $table->tinyInteger($value['field_name'])->nullable();
                            break;

                        case 'timestamp':
                            if(!empty($value['data_type_modifier'])){
                                foreach ($value['data_type_modifier'] as $key1 => $value1) {
                                    switch ($value1['name']) {
                                        case 'after':
                                            $table->timestamp($value['field_name'])->after($value1['value']);
                                            break;

                                        case 'comment':
                                            $table->timestamp($value['field_name'])->comment($value1['value']);
                                            break;

                                        case 'default':
                                            $table->timestamp($value['field_name'])->default($value1['value']);
                                            break;

                                        case 'first':
                                            $table->timestamp($value['field_name'])->first();
                                            break;

                                        case 'storedAs':
                                            $table->timestamp($value['field_name'])->storedAs($value1['value']);
                                            break;

                                        case 'unsigned':
                                            $table->timestamp($value['field_name'])->unsigned();
                                            break;
                                        
                                        case 'virtualAs':
                                            $table->timestamp($value['field_name'])->virtualAs($value1['value']);
                                            break;

                                        default:
                                            # code...
                                            break;
                                    }
                                }
                            }
                            $table->timestamp($value['field_name'])->nullable();
                            break;

                        case 'timestampTz':
                            if(!empty($value['data_type_modifier'])){
                                foreach ($value['data_type_modifier'] as $key1 => $value1) {
                                    switch ($value1['name']) {
                                        case 'after':
                                            $table->timestampTz($value['field_name'])->after($value1['value']);
                                            break;

                                        case 'comment':
                                            $table->timestampTz($value['field_name'])->comment($value1['value']);
                                            break;

                                        case 'default':
                                            $table->timestampTz($value['field_name'])->default($value1['value']);
                                            break;

                                        case 'first':
                                            $table->timestampTz($value['field_name'])->first();
                                            break;

                                        case 'storedAs':
                                            $table->timestampTz($value['field_name'])->storedAs($value1['value']);
                                            break;

                                        case 'unsigned':
                                            $table->timestampTz($value['field_name'])->unsigned();
                                            break;
                                        
                                        case 'virtualAs':
                                            $table->timestampTz($value['field_name'])->virtualAs($value1['value']);
                                            break;

                                        default:
                                            # code...
                                            break;
                                    }
                                }
                            }
                            $table->timestampTz($value['field_name'])->nullable();
                            break;

                        case 'timestamps':
                            if(!empty($value['data_type_modifier'])){
                                foreach ($value['data_type_modifier'] as $key1 => $value1) {
                                    switch ($value1['name']) {
                                        case 'after':
                                            $table->timestamps($value['field_name'])->after($value1['value']);
                                            break;

                                        case 'comment':
                                            $table->timestamps($value['field_name'])->comment($value1['value']);
                                            break;

                                        case 'default':
                                            $table->timestamps($value['field_name'])->default($value1['value']);
                                            break;

                                        case 'first':
                                            $table->timestamps($value['field_name'])->first();
                                            break;

                                        case 'storedAs':
                                            $table->timestamps($value['field_name'])->storedAs($value1['value']);
                                            break;

                                        case 'unsigned':
                                            $table->timestamps($value['field_name'])->unsigned();
                                            break;
                                        
                                        case 'virtualAs':
                                            $table->timestamps($value['field_name'])->virtualAs($value1['value']);
                                            break;

                                        default:
                                            # code...
                                            break;
                                    }
                                }
                            }
                            $table->timestamps($value['field_name'])->nullable();
                            break;

                        case 'timestampsTz':
                            if(!empty($value['data_type_modifier'])){
                                foreach ($value['data_type_modifier'] as $key1 => $value1) {
                                    switch ($value1['name']) {
                                        case 'after':
                                            $table->timestampsTz($value['field_name'])->after($value1['value']);
                                            break;

                                        case 'comment':
                                            $table->timestampsTz($value['field_name'])->comment($value1['value']);
                                            break;

                                        case 'default':
                                            $table->timestampsTz($value['field_name'])->default($value1['value']);
                                            break;

                                        case 'first':
                                            $table->timestampsTz($value['field_name'])->first();
                                            break;

                                        case 'storedAs':
                                            $table->timestampsTz($value['field_name'])->storedAs($value1['value']);
                                            break;

                                        case 'unsigned':
                                            $table->timestampsTz($value['field_name'])->unsigned();
                                            break;
                                        
                                        case 'virtualAs':
                                            $table->timestampsTz($value['field_name'])->virtualAs($value1['value']);
                                            break;

                                        default:
                                            # code...
                                            break;
                                    }
                                }
                            }
                            $table->timestampsTz($value['field_name'])->nullable();
                            break;

                        case 'unsignedBigInteger':
                            if(!empty($value['data_type_modifier'])){
                                foreach ($value['data_type_modifier'] as $key1 => $value1) {
                                    switch ($value1['name']) {
                                        case 'after':
                                            $table->unsignedBigInteger($value['field_name'])->after($value1['value']);
                                            break;

                                        case 'comment':
                                            $table->unsignedBigInteger($value['field_name'])->comment($value1['value']);
                                            break;

                                        case 'default':
                                            $table->unsignedBigInteger($value['field_name'])->default($value1['value']);
                                            break;

                                        case 'first':
                                            $table->unsignedBigInteger($value['field_name'])->first();
                                            break;

                                        case 'storedAs':
                                            $table->unsignedBigInteger($value['field_name'])->storedAs($value1['value']);
                                            break;

                                        case 'unsigned':
                                            $table->unsignedBigInteger($value['field_name'])->unsigned();
                                            break;
                                        
                                        case 'virtualAs':
                                            $table->unsignedBigInteger($value['field_name'])->virtualAs($value1['value']);
                                            break;

                                        default:
                                            # code...
                                            break;
                                    }
                                }
                            }
                            $table->unsignedBigInteger($value['field_name'])->nullable();
                            break;

                        case 'unsignedInteger':
                            if(!empty($value['data_type_modifier'])){
                                foreach ($value['data_type_modifier'] as $key1 => $value1) {
                                    switch ($value1['name']) {
                                        case 'after':
                                            $table->unsignedInteger($value['field_name'])->after($value1['value']);
                                            break;

                                        case 'comment':
                                            $table->unsignedInteger($value['field_name'])->comment($value1['value']);
                                            break;

                                        case 'default':
                                            $table->unsignedInteger($value['field_name'])->default($value1['value']);
                                            break;

                                        case 'first':
                                            $table->unsignedInteger($value['field_name'])->first();
                                            break;

                                        case 'storedAs':
                                            $table->unsignedInteger($value['field_name'])->storedAs($value1['value']);
                                            break;

                                        case 'unsigned':
                                            $table->unsignedInteger($value['field_name'])->unsigned();
                                            break;
                                        
                                        case 'virtualAs':
                                            $table->unsignedInteger($value['field_name'])->virtualAs($value1['value']);
                                            break;

                                        default:
                                            # code...
                                            break;
                                    }
                                }
                            }
                            $table->unsignedInteger($value['field_name'])->nullable();
                            break;

                        case 'unsignedMediumInteger':
                            if(!empty($value['data_type_modifier'])){
                                foreach ($value['data_type_modifier'] as $key1 => $value1) {
                                    switch ($value1['name']) {
                                        case 'after':
                                            $table->unsignedMediumInteger($value['field_name'])->after($value1['value']);
                                            break;

                                        case 'comment':
                                            $table->unsignedMediumInteger($value['field_name'])->comment($value1['value']);
                                            break;

                                        case 'default':
                                            $table->unsignedMediumInteger($value['field_name'])->default($value1['value']);
                                            break;

                                        case 'first':
                                            $table->unsignedMediumInteger($value['field_name'])->first();
                                            break;

                                        case 'storedAs':
                                            $table->unsignedMediumInteger($value['field_name'])->storedAs($value1['value']);
                                            break;

                                        case 'unsigned':
                                            $table->unsignedMediumInteger($value['field_name'])->unsigned();
                                            break;
                                        
                                        case 'virtualAs':
                                            $table->unsignedMediumInteger($value['field_name'])->virtualAs($value1['value']);
                                            break;

                                        default:
                                            # code...
                                            break;
                                    }
                                }
                            }
                            $table->unsignedMediumInteger($value['field_name'])->nullable();
                            break;

                        case 'unsignedSmallInteger':
                            if(!empty($value['data_type_modifier'])){
                                foreach ($value['data_type_modifier'] as $key1 => $value1) {
                                    switch ($value1['name']) {
                                        case 'after':
                                            $table->unsignedSmallInteger($value['field_name'])->after($value1['value']);
                                            break;

                                        case 'comment':
                                            $table->unsignedSmallInteger($value['field_name'])->comment($value1['value']);
                                            break;

                                        case 'default':
                                            $table->unsignedSmallInteger($value['field_name'])->default($value1['value']);
                                            break;

                                        case 'first':
                                            $table->unsignedSmallInteger($value['field_name'])->first();
                                            break;

                                        case 'storedAs':
                                            $table->unsignedSmallInteger($value['field_name'])->storedAs($value1['value']);
                                            break;

                                        case 'unsigned':
                                            $table->unsignedSmallInteger($value['field_name'])->unsigned();
                                            break;
                                        
                                        case 'virtualAs':
                                            $table->unsignedSmallInteger($value['field_name'])->virtualAs($value1['value']);
                                            break;

                                        default:
                                            # code...
                                            break;
                                    }
                                }
                            }
                            $table->unsignedSmallInteger($value['field_name'])->nullable();
                            break;

                        case 'unsignedTinyInteger':
                            if(!empty($value['data_type_modifier'])){
                                foreach ($value['data_type_modifier'] as $key1 => $value1) {
                                    switch ($value1['name']) {
                                        case 'after':
                                            $table->unsignedTinyInteger($value['field_name'])->after($value1['value']);
                                            break;

                                        case 'comment':
                                            $table->unsignedTinyInteger($value['field_name'])->comment($value1['value']);
                                            break;

                                        case 'default':
                                            $table->unsignedTinyInteger($value['field_name'])->default($value1['value']);
                                            break;

                                        case 'first':
                                            $table->unsignedTinyInteger($value['field_name'])->first();
                                            break;

                                        case 'storedAs':
                                            $table->unsignedTinyInteger($value['field_name'])->storedAs($value1['value']);
                                            break;

                                        case 'unsigned':
                                            $table->unsignedTinyInteger($value['field_name'])->unsigned();
                                            break;
                                        
                                        case 'virtualAs':
                                            $table->unsignedTinyInteger($value['field_name'])->virtualAs($value1['value']);
                                            break;

                                        default:
                                            # code...
                                            break;
                                    }
                                }
                            }
                            $table->unsignedTinyInteger($value['field_name'])->nullable();
                            break;

                        case 'uuid':
                            if(!empty($value['data_type_modifier'])){
                                foreach ($value['data_type_modifier'] as $key1 => $value1) {
                                    switch ($value1['name']) {
                                        case 'after':
                                            $table->uuid($value['field_name'])->after($value1['value']);
                                            break;

                                        case 'comment':
                                            $table->uuid($value['field_name'])->comment($value1['value']);
                                            break;

                                        case 'default':
                                            $table->uuid($value['field_name'])->default($value1['value']);
                                            break;

                                        case 'first':
                                            $table->uuid($value['field_name'])->first();
                                            break;

                                        case 'storedAs':
                                            $table->uuid($value['field_name'])->storedAs($value1['value']);
                                            break;

                                        case 'unsigned':
                                            $table->uuid($value['field_name'])->unsigned();
                                            break;
                                        
                                        case 'virtualAs':
                                            $table->uuid($value['field_name'])->virtualAs($value1['value']);
                                            break;

                                        default:
                                            # code...
                                            break;
                                    }
                                }
                            }
                            $table->uuid($value['field_name'])->nullable();
                            break;

                        default:
                            # code...
                            break;
                    }

                    if(!empty($value->my_sql_index)){
                        foreach ($value->my_sql_index as $key1 => $value1) {
                            switch ($value1['name']) {
                                case 'primary':
                                    $table->primary($value1['value']);
                                    break;

                                case 'unique':
                                    $table->unique($value1['value']);
                                    break;

                                case 'index':
                                    $table->index($value1['value']);
                                    break;

                                default:
                                    # code...
                                    break;
                            }
                        }
                    }
                }
                
                if(!empty($composite_indexes)){
                    foreach ($composite_indexes as $key => $value) {
                        switch ($value->name) {
                            case 'primary':
                                $table->primary($value['value']);
                                break;

                            case 'unique':
                                $table->unique($value['value']);
                                break;

                            default:
                                # code...
                                break;
                        }
                    }
                }
        });

        $myfile = fopen(app_path() ."/Models/".$this->auth_user->id.'/'.$this->auth_user->active_app_id. ucwords(rtrim($newtable,'s')) .".php", "w");
        $line1 = "<?php\n";
        $line2 = "namespace App\Models\\".$this->auth_user->id.";\n";
        if($authenticatable && $notifiable){
            $line3a = "use Laravel\Passport\HasApiTokens;\n";
            $line3b = "use Illuminate\Foundation\Auth\User as Authenticatable;\n";
            $line3c = "use Tymon\JWTAuth\Contracts\JWTSubject\n";
            $line3d = "use Illuminate\Notifications\Notifiable;\n";
            $line3 = $line3a . $line3b . $line3c . $line3d ;
            $line4 = "class ".$newtable." extends Authenticatable implements JWTSubject\n";
            $line6 = "use HasApiTokens, Notifiable;\n" . "public $" ."table = '" .$newtable ."';\n";
            $line10a = "public function getJWTIdentifier(){return $this->getKey();}\n";
            $line10b = "public function getJWTCustomClaims(){return [];}\n";
            $line10 = $line10a . $line10b;
        }elseif(!$authenticatable && $notifiable){
            $line3b = "use Illuminate\Database\Eloquent\Model;\n";
            $line3d = "use Illuminate\Notifications\Notifiable;\n";
            $line3 =  $line3b . $line3d ;
            $line4 = "class ".$newtable." extends Model\n";
            $line6 = "use HasApiTokens, Notifiable;\n" . "public $" ."table = '" .$newtable ."';\n";
            $line10a = "public function getJWTIdentifier(){return $this->getKey();}\n";
            $line10b = "public function getJWTCustomClaims(){return [];}\n";
            $line10 = $line10a . $line10b;
        }elseif($authenticatable && !$notifiable){
            $line3a = "use Laravel\Passport\HasApiTokens;\n";
            $line3b = "use Illuminate\Foundation\Auth\User as Authenticatable;\n";
            $line3c = "use Tymon\JWTAuth\Contracts\JWTSubject\n";
            $line3 = $line3a . $line3b . $line3c ;
            $line4 = "class ".$newtable." extends Authenticatable implements JWTSubject\n";
            $line6 = "use HasApiTokens, Notifiable;\n" . "public $" ."table = '" .$newtable ."';\n";
            $line10a = "public function getJWTIdentifier(){return $this->getKey();}\n";
            $line10b = "public function getJWTCustomClaims(){return [];}\n";
            $line10 = $line10a . $line10b;
        }else{
            $line3 = "use Illuminate\Database\Eloquent\Model;\n";
            $line4 = "class ".$newtable." extends Model\n";
            $line6 = "public $" ."table = '" .$newtable ."';\n";
            $line10 = "";
        }
        
        $line5 = "{\n";
        
        $line7 = "protected $" ."fillable = [\n'";
        $line8 = implode("', '", $fillable) ."'];\n";
        $line9 = "protected $" ."hidden = [\n'". implode("', '", $hidden) ."'];\n";

        $line11 = "}\n";

        $txt = $line1.$line2.$line3.$line4.$line5.$line6.$line7.$line8.$line9.$line10.$line11;


        fwrite($myfile, $txt);
        fclose($myfile);
        return ['table updated successfully'];
    }

    public function deleteTable($table)
    {
        \Log::Info("deleteTable");
        $this->setTableSelf("mastertablelist");
        $record = $this->table::where('table_name', $table)->first();
        if($this->table::destroy($record->id)){
            Schema::dropIfExists($table);
            // return ['success'];
            return $this->getMyTables();
        }
    }

}
