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

class HomeController1 extends Controller
{
    private $request;
    private $table;
    private $visibles;
    private $hiddens;
    private $has_many;
    private $filters;
    private $dates;
    private $result;

    private function setTable($table)
    {
        $this->table = 'App\\Models\\'.$table;
        // $this->table = 'App\\Models\\'.ucwords(rtrim($table,'s'));
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

    private function processTableForHasMany($table)
    {
        $this->table = $this->table->map(function ($itable) use ($table) {
            // $itable['price'] = Price::find($itable->price_id);
            foreach ($this->has_many as $key => $value) {
                if( strpos($value, '|') ){
                    $value = explode('|', $value);
                    $stable = 'App\\Models\\'.ucwords(rtrim($value[0],'s'));
                    $itable[$value[1]] = $stable::where('pivot_table', $table)
                                                ->where('pivot_field', $value[1])
                                                ->where('pivot_id', $itable->id)->get();
                }else{
                    $stable = 'App\\Models\\'.ucwords(rtrim($value,'s'));
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

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(Request $request)
    {
        // $this->middleware('auth');
        $this->request = $request;
        $this->visibles = $request->query("visibles");
        $this->hiddens = $request->query("hiddens")?$request->query("hiddens"):[];
        $this->has_many = $request->query("has_many")?$request->query("has_many"):[];
        $this->filters = $request->query("filters");
        $this->dates = $request->query("dates");
        // Auth::guard('api')->user();
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($dbname, $table)
    {
        \Log::Info("index".$table);
        $this->updateDotEnv('DB_DATABASE', $dbname);
        $this->setTable($table);
        $this->table = $this->table::all();
        $this->table = $this->table->makeVisible($this->visibles);
        $this->table = $this->table->makeHidden($this->hiddens);
        $this->table = $this->table->filter($this->filters);
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
                $stable = 'App\\Models\\'.ucwords(rtrim($value[0],'s'));
                $this->table[$value[1]] = $stable::where('pivot_table', $table)->where('pivot_field', $value[1])->where('pivot_id', $id)->get();
            }else{
                $stable = 'App\\Models\\'.ucwords(rtrim($value,'s'));
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
        foreach ($this->request->all() as $key => $value) {
            $jres = json_decode($key, true);
        }
        // $this->processRequestForDates();
        $id = $this->table::create($jres)->id;
        return $id;
    }

    public function updateOrDeleteRecord($dbname, $table, $id)
    {
        \Log::Info("updateOrDeleteRecord".$dbname.$table.$id);
        $this->updateDotEnv('DB_DATABASE', $dbname);
        $this->setTable($table);
        if(!empty($this->request->all())){
            foreach ($this->request->all() as $key => $value) {
                $jres = json_decode($key, true);
            }
            $record = $this->table::findOrFail($id)->update($jres);
            return ['status' => 'put success'];
        }else{
            $record = $this->table::findOrFail($id);
            if($this->table::destroy($id)){
                return ['status' => 'del success'];
            }
        }
    }

    public function updateRecord($dbname, $table, $id)
    {
        \Log::Info("updateRecord".$table.$id.$this->request->all());
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
        \Log::Info($this->request);
        \Log::Info(strlen($this->request));
        $dbname = $this->request->database_name;
        $newtable = $this->request->table_name;
        $group_name = $this->request->group_name;
        $table_description = $this->request->table_description;
        $keywords = $this->request->keywords;
        $fields = $this->request->fields;
        $composite_index = $this->request->composite_index;

        $this->updateDotEnv('DB_DATABASE', $dbname);
        
        Schema::create($newtable, function (Blueprint $table) use ($fields, $composite_index) {
            $table->increments('id');
                foreach ($fields as $key => $value) {
                    switch ($value->data_type) {
                        case 'bigIncrements':
                            if(is_array($value->data_type_modifier)){
                                foreach ($value->data_type_modifier as $key1 => $value1) {
                                    switch ($value1->name) {
                                        case 'after':
                                            $table->bigIncrements($value->field_name)->after($value1->value);
                                            break;

                                        case 'comment':
                                            $table->bigIncrements($value->field_name)->comment($value1->value);
                                            break;

                                        case 'default':
                                            $table->bigIncrements($value->field_name)->default($value1->value);
                                            break;

                                        case 'first':
                                            $table->bigIncrements($value->field_name)->first();
                                            break;

                                        case 'storedAs':
                                            $table->bigIncrements($value->field_name)->storedAs($value1->value);
                                            break;

                                        case 'unsigned':
                                            $table->bigIncrements($value->field_name)->unsigned();
                                            break;
                                        
                                        case 'virtualAs':
                                            $table->bigIncrements($value->field_name)->virtualAs($value1->value);
                                            break;

                                        default:
                                            # code...
                                            break;
                                    }
                                }
                            }
                            $table->bigIncrements($value->field_name)->nullable();
                            break;

                        case 'bigInteger':
                            if(is_array($value->data_type_modifier)){
                                foreach ($value->data_type_modifier as $key1 => $value1) {
                                    switch ($value1->name) {
                                        case 'after':
                                            $table->bigInteger($value->field_name)->after($value1->value);
                                            break;

                                        case 'comment':
                                            $table->bigInteger($value->field_name)->comment($value1->value);
                                            break;

                                        case 'default':
                                            $table->bigInteger($value->field_name)->default($value1->value);
                                            break;

                                        case 'first':
                                            $table->bigInteger($value->field_name)->first();
                                            break;

                                        case 'storedAs':
                                            $table->bigInteger($value->field_name)->storedAs($value1->value);
                                            break;

                                        case 'unsigned':
                                            $table->bigInteger($value->field_name)->unsigned();
                                            break;
                                        
                                        case 'virtualAs':
                                            $table->bigInteger($value->field_name)->virtualAs($value1->value);
                                            break;

                                        default:
                                            # code...
                                            break;
                                    }
                                }
                            }
                            $table->bigInteger($value->field_name)->nullable();
                            break;

                        case 'binary':
                            if(is_array($value->data_type_modifier)){
                                foreach ($value->data_type_modifier as $key1 => $value1) {
                                    switch ($value1->name) {
                                        case 'after':
                                            $table->binary($value->field_name)->after($value1->value);
                                            break;

                                        case 'comment':
                                            $table->binary($value->field_name)->comment($value1->value);
                                            break;

                                        case 'default':
                                            $table->binary($value->field_name)->default($value1->value);
                                            break;

                                        case 'first':
                                            $table->binary($value->field_name)->first();
                                            break;

                                        case 'storedAs':
                                            $table->binary($value->field_name)->storedAs($value1->value);
                                            break;

                                        case 'unsigned':
                                            $table->binary($value->field_name)->unsigned();
                                            break;
                                        
                                        case 'virtualAs':
                                            $table->binary($value->field_name)->virtualAs($value1->value);
                                            break;

                                        default:
                                            # code...
                                            break;
                                    }
                                }
                            }
                            $table->binary($value->field_name)->nullable();
                            break;

                        case 'boolean':
                            if(is_array($value->data_type_modifier)){
                                foreach ($value->data_type_modifier as $key1 => $value1) {
                                    switch ($value1->name) {
                                        case 'after':
                                            $table->boolean($value->field_name)->after($value1->value);
                                            break;

                                        case 'comment':
                                            $table->boolean($value->field_name)->comment($value1->value);
                                            break;

                                        case 'default':
                                            $table->boolean($value->field_name)->default($value1->value);
                                            break;

                                        case 'first':
                                            $table->boolean($value->field_name)->first();
                                            break;

                                        case 'storedAs':
                                            $table->boolean($value->field_name)->storedAs($value1->value);
                                            break;

                                        case 'unsigned':
                                            $table->boolean($value->field_name)->unsigned();
                                            break;
                                        
                                        case 'virtualAs':
                                            $table->boolean($value->field_name)->virtualAs($value1->value);
                                            break;

                                        default:
                                            # code...
                                            break;
                                    }
                                }
                            }
                            $table->boolean($value->field_name)->nullable();
                            break;

                        case 'char':
                            if(is_array($value->data_type_modifier)){
                                foreach ($value->data_type_modifier as $key1 => $value1) {
                                    switch ($value1->name) {
                                        case 'after':
                                            $table->char($value->field_name)->after($value1->value);
                                            break;

                                        case 'comment':
                                            $table->char($value->field_name)->comment($value1->value);
                                            break;

                                        case 'default':
                                            $table->char($value->field_name)->default($value1->value);
                                            break;

                                        case 'first':
                                            $table->char($value->field_name)->first();
                                            break;

                                        case 'storedAs':
                                            $table->char($value->field_name)->storedAs($value1->value);
                                            break;

                                        case 'unsigned':
                                            $table->char($value->field_name)->unsigned();
                                            break;
                                        
                                        case 'virtualAs':
                                            $table->char($value->field_name)->virtualAs($value1->value);
                                            break;

                                        default:
                                            # code...
                                            break;
                                    }
                                }
                            }
                            $table->char($value->field_name)->nullable();
                            break;

                        case 'date':
                            if(is_array($value->data_type_modifier)){
                                foreach ($value->data_type_modifier as $key1 => $value1) {
                                    switch ($value1->name) {
                                        case 'after':
                                            $table->date($value->field_name)->after($value1->value);
                                            break;

                                        case 'comment':
                                            $table->date($value->field_name)->comment($value1->value);
                                            break;

                                        case 'default':
                                            $table->date($value->field_name)->default($value1->value);
                                            break;

                                        case 'first':
                                            $table->date($value->field_name)->first();
                                            break;

                                        case 'storedAs':
                                            $table->date($value->field_name)->storedAs($value1->value);
                                            break;

                                        case 'unsigned':
                                            $table->date($value->field_name)->unsigned();
                                            break;
                                        
                                        case 'virtualAs':
                                            $table->date($value->field_name)->virtualAs($value1->value);
                                            break;

                                        default:
                                            # code...
                                            break;
                                    }
                                }
                            }
                            $table->date($value->field_name)->nullable();
                            break;

                        case 'dateTime':
                            if(is_array($value->data_type_modifier)){
                                foreach ($value->data_type_modifier as $key1 => $value1) {
                                    switch ($value1->name) {
                                        case 'after':
                                            $table->dateTime($value->field_name)->after($value1->value);
                                            break;

                                        case 'comment':
                                            $table->dateTime($value->field_name)->comment($value1->value);
                                            break;

                                        case 'default':
                                            $table->dateTime($value->field_name)->default($value1->value);
                                            break;

                                        case 'first':
                                            $table->dateTime($value->field_name)->first();
                                            break;

                                        case 'storedAs':
                                            $table->dateTime($value->field_name)->storedAs($value1->value);
                                            break;

                                        case 'unsigned':
                                            $table->dateTime($value->field_name)->unsigned();
                                            break;
                                        
                                        case 'virtualAs':
                                            $table->dateTime($value->field_name)->virtualAs($value1->value);
                                            break;

                                        default:
                                            # code...
                                            break;
                                    }
                                }
                            }
                            $table->dateTime($value->field_name)->nullable();
                            break;

                        case 'dateTimeTz':
                            if(is_array($value->data_type_modifier)){
                                foreach ($value->data_type_modifier as $key1 => $value1) {
                                    switch ($value1->name) {
                                        case 'after':
                                            $table->dateTimeTz($value->field_name)->after($value1->value);
                                            break;

                                        case 'comment':
                                            $table->dateTimeTz($value->field_name)->comment($value1->value);
                                            break;

                                        case 'default':
                                            $table->dateTimeTz($value->field_name)->default($value1->value);
                                            break;

                                        case 'first':
                                            $table->dateTimeTz($value->field_name)->first();
                                            break;

                                        case 'storedAs':
                                            $table->dateTimeTz($value->field_name)->storedAs($value1->value);
                                            break;

                                        case 'unsigned':
                                            $table->dateTimeTz($value->field_name)->unsigned();
                                            break;
                                        
                                        case 'virtualAs':
                                            $table->dateTimeTz($value->field_name)->virtualAs($value1->value);
                                            break;

                                        default:
                                            # code...
                                            break;
                                    }
                                }
                            }
                            $table->dateTimeTz($value->field_name)->nullable();
                            break;

                        case 'decimal':
                            if(is_array($value->data_type_modifier)){
                                foreach ($value->data_type_modifier as $key1 => $value1) {
                                    switch ($value1->name) {
                                        case 'after':
                                            $table->decimal($value->field_name)->after($value1->value);
                                            break;

                                        case 'comment':
                                            $table->decimal($value->field_name)->comment($value1->value);
                                            break;

                                        case 'default':
                                            $table->decimal($value->field_name)->default($value1->value);
                                            break;

                                        case 'first':
                                            $table->decimal($value->field_name)->first();
                                            break;

                                        case 'storedAs':
                                            $table->decimal($value->field_name)->storedAs($value1->value);
                                            break;

                                        case 'unsigned':
                                            $table->decimal($value->field_name)->unsigned();
                                            break;
                                        
                                        case 'virtualAs':
                                            $table->decimal($value->field_name)->virtualAs($value1->value);
                                            break;

                                        default:
                                            # code...
                                            break;
                                    }
                                }
                            }
                            $table->decimal($value->field_name)->nullable();
                            break;

                        case 'double':
                            if(is_array($value->data_type_modifier)){
                                foreach ($value->data_type_modifier as $key1 => $value1) {
                                    switch ($value1->name) {
                                        case 'after':
                                            $table->double($value->field_name)->after($value1->value);
                                            break;

                                        case 'comment':
                                            $table->double($value->field_name)->comment($value1->value);
                                            break;

                                        case 'default':
                                            $table->double($value->field_name)->default($value1->value);
                                            break;

                                        case 'first':
                                            $table->double($value->field_name)->first();
                                            break;

                                        case 'storedAs':
                                            $table->double($value->field_name)->storedAs($value1->value);
                                            break;

                                        case 'unsigned':
                                            $table->double($value->field_name)->unsigned();
                                            break;
                                        
                                        case 'virtualAs':
                                            $table->double($value->field_name)->virtualAs($value1->value);
                                            break;

                                        default:
                                            # code...
                                            break;
                                    }
                                }
                            }
                            $table->double($value->field_name)->nullable();
                            break;

                        case 'enum':
                            if(is_array($value->data_type_modifier)){
                                foreach ($value->data_type_modifier as $key1 => $value1) {
                                    switch ($value1->name) {
                                        case 'after':
                                            $table->enum($value->field_name)->after($value1->value);
                                            break;

                                        case 'comment':
                                            $table->enum($value->field_name)->comment($value1->value);
                                            break;

                                        case 'default':
                                            $table->enum($value->field_name)->default($value1->value);
                                            break;

                                        case 'first':
                                            $table->enum($value->field_name)->first();
                                            break;

                                        case 'storedAs':
                                            $table->enum($value->field_name)->storedAs($value1->value);
                                            break;

                                        case 'unsigned':
                                            $table->enum($value->field_name)->unsigned();
                                            break;
                                        
                                        case 'virtualAs':
                                            $table->enum($value->field_name)->virtualAs($value1->value);
                                            break;

                                        default:
                                            # code...
                                            break;
                                    }
                                }
                            }
                            $table->enum($value->field_name)->nullable();
                            break;

                        case 'float':
                            if(is_array($value->data_type_modifier)){
                                foreach ($value->data_type_modifier as $key1 => $value1) {
                                    switch ($value1->name) {
                                        case 'after':
                                            $table->float($value->field_name)->after($value1->value);
                                            break;

                                        case 'comment':
                                            $table->float($value->field_name)->comment($value1->value);
                                            break;

                                        case 'default':
                                            $table->float($value->field_name)->default($value1->value);
                                            break;

                                        case 'first':
                                            $table->float($value->field_name)->first();
                                            break;

                                        case 'storedAs':
                                            $table->float($value->field_name)->storedAs($value1->value);
                                            break;

                                        case 'unsigned':
                                            $table->float($value->field_name)->unsigned();
                                            break;
                                        
                                        case 'virtualAs':
                                            $table->float($value->field_name)->virtualAs($value1->value);
                                            break;

                                        default:
                                            # code...
                                            break;
                                    }
                                }
                            }
                            $table->float($value->field_name)->nullable();
                            break;

                        case 'increments':
                            if(is_array($value->data_type_modifier)){
                                foreach ($value->data_type_modifier as $key1 => $value1) {
                                    switch ($value1->name) {
                                        case 'after':
                                            $table->increments($value->field_name)->after($value1->value);
                                            break;

                                        case 'comment':
                                            $table->increments($value->field_name)->comment($value1->value);
                                            break;

                                        case 'default':
                                            $table->increments($value->field_name)->default($value1->value);
                                            break;

                                        case 'first':
                                            $table->increments($value->field_name)->first();
                                            break;

                                        case 'storedAs':
                                            $table->increments($value->field_name)->storedAs($value1->value);
                                            break;

                                        case 'unsigned':
                                            $table->increments($value->field_name)->unsigned();
                                            break;
                                        
                                        case 'virtualAs':
                                            $table->increments($value->field_name)->virtualAs($value1->value);
                                            break;

                                        default:
                                            # code...
                                            break;
                                    }
                                }
                            }
                            $table->increments($value->field_name)->nullable();
                            break;

                        case 'integer':
                            if(is_array($value->data_type_modifier)){
                                foreach ($value->data_type_modifier as $key1 => $value1) {
                                    switch ($value1->name) {
                                        case 'after':
                                            $table->integer($value->field_name)->after($value1->value);
                                            break;

                                        case 'comment':
                                            $table->integer($value->field_name)->comment($value1->value);
                                            break;

                                        case 'default':
                                            $table->integer($value->field_name)->default($value1->value);
                                            break;

                                        case 'first':
                                            $table->integer($value->field_name)->first();
                                            break;

                                        case 'storedAs':
                                            $table->integer($value->field_name)->storedAs($value1->value);
                                            break;

                                        case 'unsigned':
                                            $table->integer($value->field_name)->unsigned();
                                            break;
                                        
                                        case 'virtualAs':
                                            $table->integer($value->field_name)->virtualAs($value1->value);
                                            break;

                                        default:
                                            # code...
                                            break;
                                    }
                                }
                            }
                            $table->integer($value->field_name)->nullable();
                            break;

                        case 'ipAddress':
                            if(is_array($value->data_type_modifier)){
                                foreach ($value->data_type_modifier as $key1 => $value1) {
                                    switch ($value1->name) {
                                        case 'after':
                                            $table->ipAddress($value->field_name)->after($value1->value);
                                            break;

                                        case 'comment':
                                            $table->ipAddress($value->field_name)->comment($value1->value);
                                            break;

                                        case 'default':
                                            $table->ipAddress($value->field_name)->default($value1->value);
                                            break;

                                        case 'first':
                                            $table->ipAddress($value->field_name)->first();
                                            break;

                                        case 'storedAs':
                                            $table->ipAddress($value->field_name)->storedAs($value1->value);
                                            break;

                                        case 'unsigned':
                                            $table->ipAddress($value->field_name)->unsigned();
                                            break;
                                        
                                        case 'virtualAs':
                                            $table->ipAddress($value->field_name)->virtualAs($value1->value);
                                            break;

                                        default:
                                            # code...
                                            break;
                                    }
                                }
                            }
                            $table->ipAddress($value->field_name)->nullable();
                            break;

                        case 'json':
                            if(is_array($value->data_type_modifier)){
                                foreach ($value->data_type_modifier as $key1 => $value1) {
                                    switch ($value1->name) {
                                        case 'after':
                                            $table->json($value->field_name)->after($value1->value);
                                            break;

                                        case 'comment':
                                            $table->json($value->field_name)->comment($value1->value);
                                            break;

                                        case 'default':
                                            $table->json($value->field_name)->default($value1->value);
                                            break;

                                        case 'first':
                                            $table->json($value->field_name)->first();
                                            break;

                                        case 'storedAs':
                                            $table->json($value->field_name)->storedAs($value1->value);
                                            break;

                                        case 'unsigned':
                                            $table->json($value->field_name)->unsigned();
                                            break;
                                        
                                        case 'virtualAs':
                                            $table->json($value->field_name)->virtualAs($value1->value);
                                            break;

                                        default:
                                            # code...
                                            break;
                                    }
                                }
                            }
                            $table->json($value->field_name)->nullable();
                            break;

                        case 'jsonb':
                            if(is_array($value->data_type_modifier)){
                                foreach ($value->data_type_modifier as $key1 => $value1) {
                                    switch ($value1->name) {
                                        case 'after':
                                            $table->jsonb($value->field_name)->after($value1->value);
                                            break;

                                        case 'comment':
                                            $table->jsonb($value->field_name)->comment($value1->value);
                                            break;

                                        case 'default':
                                            $table->jsonb($value->field_name)->default($value1->value);
                                            break;

                                        case 'first':
                                            $table->jsonb($value->field_name)->first();
                                            break;

                                        case 'storedAs':
                                            $table->jsonb($value->field_name)->storedAs($value1->value);
                                            break;

                                        case 'unsigned':
                                            $table->jsonb($value->field_name)->unsigned();
                                            break;
                                        
                                        case 'virtualAs':
                                            $table->jsonb($value->field_name)->virtualAs($value1->value);
                                            break;

                                        default:
                                            # code...
                                            break;
                                    }
                                }
                            }
                            $table->jsonb($value->field_name)->nullable();
                            break;

                        case 'longText':
                            if(is_array($value->data_type_modifier)){
                                foreach ($value->data_type_modifier as $key1 => $value1) {
                                    switch ($value1->name) {
                                        case 'after':
                                            $table->longText($value->field_name)->after($value1->value);
                                            break;

                                        case 'comment':
                                            $table->longText($value->field_name)->comment($value1->value);
                                            break;

                                        case 'default':
                                            $table->longText($value->field_name)->default($value1->value);
                                            break;

                                        case 'first':
                                            $table->longText($value->field_name)->first();
                                            break;

                                        case 'storedAs':
                                            $table->longText($value->field_name)->storedAs($value1->value);
                                            break;

                                        case 'unsigned':
                                            $table->longText($value->field_name)->unsigned();
                                            break;
                                        
                                        case 'virtualAs':
                                            $table->longText($value->field_name)->virtualAs($value1->value);
                                            break;

                                        default:
                                            # code...
                                            break;
                                    }
                                }
                            }
                            $table->longText($value->field_name)->nullable();
                            break;

                        case 'macAddress':
                            if(is_array($value->data_type_modifier)){
                                foreach ($value->data_type_modifier as $key1 => $value1) {
                                    switch ($value1->name) {
                                        case 'after':
                                            $table->macAddress($value->field_name)->after($value1->value);
                                            break;

                                        case 'comment':
                                            $table->macAddress($value->field_name)->comment($value1->value);
                                            break;

                                        case 'default':
                                            $table->macAddress($value->field_name)->default($value1->value);
                                            break;

                                        case 'first':
                                            $table->macAddress($value->field_name)->first();
                                            break;

                                        case 'storedAs':
                                            $table->macAddress($value->field_name)->storedAs($value1->value);
                                            break;

                                        case 'unsigned':
                                            $table->macAddress($value->field_name)->unsigned();
                                            break;
                                        
                                        case 'virtualAs':
                                            $table->macAddress($value->field_name)->virtualAs($value1->value);
                                            break;

                                        default:
                                            # code...
                                            break;
                                    }
                                }
                            }
                            $table->macAddress($value->field_name)->nullable();
                            break;

                        case 'mediumIncrements':
                            if(is_array($value->data_type_modifier)){
                                foreach ($value->data_type_modifier as $key1 => $value1) {
                                    switch ($value1->name) {
                                        case 'after':
                                            $table->mediumIncrements($value->field_name)->after($value1->value);
                                            break;

                                        case 'comment':
                                            $table->mediumIncrements($value->field_name)->comment($value1->value);
                                            break;

                                        case 'default':
                                            $table->mediumIncrements($value->field_name)->default($value1->value);
                                            break;

                                        case 'first':
                                            $table->mediumIncrements($value->field_name)->first();
                                            break;

                                        case 'storedAs':
                                            $table->mediumIncrements($value->field_name)->storedAs($value1->value);
                                            break;

                                        case 'unsigned':
                                            $table->mediumIncrements($value->field_name)->unsigned();
                                            break;
                                        
                                        case 'virtualAs':
                                            $table->mediumIncrements($value->field_name)->virtualAs($value1->value);
                                            break;

                                        default:
                                            # code...
                                            break;
                                    }
                                }
                            }
                            $table->mediumIncrements($value->field_name)->nullable();
                            break;

                        case 'mediumInteger':
                            if(is_array($value->data_type_modifier)){
                                foreach ($value->data_type_modifier as $key1 => $value1) {
                                    switch ($value1->name) {
                                        case 'after':
                                            $table->mediumInteger($value->field_name)->after($value1->value);
                                            break;

                                        case 'comment':
                                            $table->mediumInteger($value->field_name)->comment($value1->value);
                                            break;

                                        case 'default':
                                            $table->mediumInteger($value->field_name)->default($value1->value);
                                            break;

                                        case 'first':
                                            $table->mediumInteger($value->field_name)->first();
                                            break;

                                        case 'storedAs':
                                            $table->mediumInteger($value->field_name)->storedAs($value1->value);
                                            break;

                                        case 'unsigned':
                                            $table->mediumInteger($value->field_name)->unsigned();
                                            break;
                                        
                                        case 'virtualAs':
                                            $table->mediumInteger($value->field_name)->virtualAs($value1->value);
                                            break;

                                        default:
                                            # code...
                                            break;
                                    }
                                }
                            }
                            $table->mediumInteger($value->field_name)->nullable();
                            break;

                        case 'mediumText':
                            if(is_array($value->data_type_modifier)){
                                foreach ($value->data_type_modifier as $key1 => $value1) {
                                    switch ($value1->name) {
                                        case 'after':
                                            $table->mediumText($value->field_name)->after($value1->value);
                                            break;

                                        case 'comment':
                                            $table->mediumText($value->field_name)->comment($value1->value);
                                            break;

                                        case 'default':
                                            $table->mediumText($value->field_name)->default($value1->value);
                                            break;

                                        case 'first':
                                            $table->mediumText($value->field_name)->first();
                                            break;

                                        case 'storedAs':
                                            $table->mediumText($value->field_name)->storedAs($value1->value);
                                            break;

                                        case 'unsigned':
                                            $table->mediumText($value->field_name)->unsigned();
                                            break;
                                        
                                        case 'virtualAs':
                                            $table->mediumText($value->field_name)->virtualAs($value1->value);
                                            break;

                                        default:
                                            # code...
                                            break;
                                    }
                                }
                            }
                            $table->mediumText($value->field_name)->nullable();
                            break;

                        case 'morphs':
                            if(is_array($value->data_type_modifier)){
                                foreach ($value->data_type_modifier as $key1 => $value1) {
                                    switch ($value1->name) {
                                        case 'after':
                                            $table->morphs($value->field_name)->after($value1->value);
                                            break;

                                        case 'comment':
                                            $table->morphs($value->field_name)->comment($value1->value);
                                            break;

                                        case 'default':
                                            $table->morphs($value->field_name)->default($value1->value);
                                            break;

                                        case 'first':
                                            $table->morphs($value->field_name)->first();
                                            break;

                                        case 'storedAs':
                                            $table->morphs($value->field_name)->storedAs($value1->value);
                                            break;

                                        case 'unsigned':
                                            $table->morphs($value->field_name)->unsigned();
                                            break;
                                        
                                        case 'virtualAs':
                                            $table->morphs($value->field_name)->virtualAs($value1->value);
                                            break;

                                        default:
                                            # code...
                                            break;
                                    }
                                }
                            }
                            $table->morphs($value->field_name)->nullable();
                            break;

                        case 'nullableMorphs':
                            if(is_array($value->data_type_modifier)){
                                foreach ($value->data_type_modifier as $key1 => $value1) {
                                    switch ($value1->name) {
                                        case 'after':
                                            $table->nullableMorphs($value->field_name)->after($value1->value);
                                            break;

                                        case 'comment':
                                            $table->nullableMorphs($value->field_name)->comment($value1->value);
                                            break;

                                        case 'default':
                                            $table->nullableMorphs($value->field_name)->default($value1->value);
                                            break;

                                        case 'first':
                                            $table->nullableMorphs($value->field_name)->first();
                                            break;

                                        case 'storedAs':
                                            $table->nullableMorphs($value->field_name)->storedAs($value1->value);
                                            break;

                                        case 'unsigned':
                                            $table->nullableMorphs($value->field_name)->unsigned();
                                            break;
                                        
                                        case 'virtualAs':
                                            $table->nullableMorphs($value->field_name)->virtualAs($value1->value);
                                            break;

                                        default:
                                            # code...
                                            break;
                                    }
                                }
                            }
                            $table->nullableMorphs($value->field_name)->nullable();
                            break;

                        case 'nullableTimestamps':
                            if(is_array($value->data_type_modifier)){
                                foreach ($value->data_type_modifier as $key1 => $value1) {
                                    switch ($value1->name) {
                                        case 'after':
                                            $table->nullableTimestamps($value->field_name)->after($value1->value);
                                            break;

                                        case 'comment':
                                            $table->nullableTimestamps($value->field_name)->comment($value1->value);
                                            break;

                                        case 'default':
                                            $table->nullableTimestamps($value->field_name)->default($value1->value);
                                            break;

                                        case 'first':
                                            $table->nullableTimestamps($value->field_name)->first();
                                            break;

                                        case 'storedAs':
                                            $table->nullableTimestamps($value->field_name)->storedAs($value1->value);
                                            break;

                                        case 'unsigned':
                                            $table->nullableTimestamps($value->field_name)->unsigned();
                                            break;
                                        
                                        case 'virtualAs':
                                            $table->nullableTimestamps($value->field_name)->virtualAs($value1->value);
                                            break;

                                        default:
                                            # code...
                                            break;
                                    }
                                }
                            }
                            $table->nullableTimestamps($value->field_name)->nullable();
                            break;

                        case 'rememberToken':
                            if(is_array($value->data_type_modifier)){
                                foreach ($value->data_type_modifier as $key1 => $value1) {
                                    switch ($value1->name) {
                                        case 'after':
                                            $table->rememberToken($value->field_name)->after($value1->value);
                                            break;

                                        case 'comment':
                                            $table->rememberToken($value->field_name)->comment($value1->value);
                                            break;

                                        case 'default':
                                            $table->rememberToken($value->field_name)->default($value1->value);
                                            break;

                                        case 'first':
                                            $table->rememberToken($value->field_name)->first();
                                            break;

                                        case 'storedAs':
                                            $table->rememberToken($value->field_name)->storedAs($value1->value);
                                            break;

                                        case 'unsigned':
                                            $table->rememberToken($value->field_name)->unsigned();
                                            break;
                                        
                                        case 'virtualAs':
                                            $table->rememberToken($value->field_name)->virtualAs($value1->value);
                                            break;

                                        default:
                                            # code...
                                            break;
                                    }
                                }
                            }
                            $table->rememberToken($value->field_name)->nullable();
                            break;

                        case 'smallIncrements':
                            if(is_array($value->data_type_modifier)){
                                foreach ($value->data_type_modifier as $key1 => $value1) {
                                    switch ($value1->name) {
                                        case 'after':
                                            $table->smallIncrements($value->field_name)->after($value1->value);
                                            break;

                                        case 'comment':
                                            $table->smallIncrements($value->field_name)->comment($value1->value);
                                            break;

                                        case 'default':
                                            $table->smallIncrements($value->field_name)->default($value1->value);
                                            break;

                                        case 'first':
                                            $table->smallIncrements($value->field_name)->first();
                                            break;

                                        case 'storedAs':
                                            $table->smallIncrements($value->field_name)->storedAs($value1->value);
                                            break;

                                        case 'unsigned':
                                            $table->smallIncrements($value->field_name)->unsigned();
                                            break;
                                        
                                        case 'virtualAs':
                                            $table->smallIncrements($value->field_name)->virtualAs($value1->value);
                                            break;

                                        default:
                                            # code...
                                            break;
                                    }
                                }
                            }
                            $table->smallIncrements($value->field_name)->nullable();
                            break;

                        case 'smallInteger':
                            if(is_array($value->data_type_modifier)){
                                foreach ($value->data_type_modifier as $key1 => $value1) {
                                    switch ($value1->name) {
                                        case 'after':
                                            $table->smallInteger($value->field_name)->after($value1->value);
                                            break;

                                        case 'comment':
                                            $table->smallInteger($value->field_name)->comment($value1->value);
                                            break;

                                        case 'default':
                                            $table->smallInteger($value->field_name)->default($value1->value);
                                            break;

                                        case 'first':
                                            $table->smallInteger($value->field_name)->first();
                                            break;

                                        case 'storedAs':
                                            $table->smallInteger($value->field_name)->storedAs($value1->value);
                                            break;

                                        case 'unsigned':
                                            $table->smallInteger($value->field_name)->unsigned();
                                            break;
                                        
                                        case 'virtualAs':
                                            $table->smallInteger($value->field_name)->virtualAs($value1->value);
                                            break;

                                        default:
                                            # code...
                                            break;
                                    }
                                }
                            }
                            $table->smallInteger($value->field_name)->nullable();
                            break;

                        case 'softDeletes':
                            if(is_array($value->data_type_modifier)){
                                foreach ($value->data_type_modifier as $key1 => $value1) {
                                    switch ($value1->name) {
                                        case 'after':
                                            $table->softDeletes($value->field_name)->after($value1->value);
                                            break;

                                        case 'comment':
                                            $table->softDeletes($value->field_name)->comment($value1->value);
                                            break;

                                        case 'default':
                                            $table->softDeletes($value->field_name)->default($value1->value);
                                            break;

                                        case 'first':
                                            $table->softDeletes($value->field_name)->first();
                                            break;

                                        case 'storedAs':
                                            $table->softDeletes($value->field_name)->storedAs($value1->value);
                                            break;

                                        case 'unsigned':
                                            $table->softDeletes($value->field_name)->unsigned();
                                            break;
                                        
                                        case 'virtualAs':
                                            $table->softDeletes($value->field_name)->virtualAs($value1->value);
                                            break;

                                        default:
                                            # code...
                                            break;
                                    }
                                }
                            }
                            $table->softDeletes($value->field_name)->nullable();
                            break;

                        case 'string':
                            if(is_array($value->data_type_modifier)){
                                foreach ($value->data_type_modifier as $key1 => $value1) {
                                    switch ($value1->name) {
                                        case 'after':
                                            $table->string($value->field_name)->after($value1->value);
                                            break;

                                        case 'comment':
                                            $table->string($value->field_name)->comment($value1->value);
                                            break;

                                        case 'default':
                                            $table->string($value->field_name)->default($value1->value);
                                            break;

                                        case 'first':
                                            $table->string($value->field_name)->first();
                                            break;

                                        case 'storedAs':
                                            $table->string($value->field_name)->storedAs($value1->value);
                                            break;

                                        case 'unsigned':
                                            $table->string($value->field_name)->unsigned();
                                            break;
                                        
                                        case 'virtualAs':
                                            $table->string($value->field_name)->virtualAs($value1->value);
                                            break;

                                        default:
                                            # code...
                                            break;
                                    }
                                }
                            }
                            $table->string($value->field_name)->nullable();
                            break;

                        case 'string':
                            if(is_array($value->data_type_modifier)){
                                foreach ($value->data_type_modifier as $key1 => $value1) {
                                    switch ($value1->name) {
                                        case 'after':
                                            $table->string($value->field_name)->after($value1->value);
                                            break;

                                        case 'comment':
                                            $table->string($value->field_name)->comment($value1->value);
                                            break;

                                        case 'default':
                                            $table->string($value->field_name)->default($value1->value);
                                            break;

                                        case 'first':
                                            $table->string($value->field_name)->first();
                                            break;

                                        case 'storedAs':
                                            $table->string($value->field_name)->storedAs($value1->value);
                                            break;

                                        case 'unsigned':
                                            $table->string($value->field_name)->unsigned();
                                            break;
                                        
                                        case 'virtualAs':
                                            $table->string($value->field_name)->virtualAs($value1->value);
                                            break;

                                        default:
                                            # code...
                                            break;
                                    }
                                }
                            }
                            $table->string($value->field_name)->nullable();
                            break;

                        case 'text':
                            if(is_array($value->data_type_modifier)){
                                foreach ($value->data_type_modifier as $key1 => $value1) {
                                    switch ($value1->name) {
                                        case 'after':
                                            $table->text($value->field_name)->after($value1->value);
                                            break;

                                        case 'comment':
                                            $table->text($value->field_name)->comment($value1->value);
                                            break;

                                        case 'default':
                                            $table->text($value->field_name)->default($value1->value);
                                            break;

                                        case 'first':
                                            $table->text($value->field_name)->first();
                                            break;

                                        case 'storedAs':
                                            $table->text($value->field_name)->storedAs($value1->value);
                                            break;

                                        case 'unsigned':
                                            $table->text($value->field_name)->unsigned();
                                            break;
                                        
                                        case 'virtualAs':
                                            $table->text($value->field_name)->virtualAs($value1->value);
                                            break;

                                        default:
                                            # code...
                                            break;
                                    }
                                }
                            }
                            $table->text($value->field_name)->nullable();
                            break;

                        case 'time':
                            if(is_array($value->data_type_modifier)){
                                foreach ($value->data_type_modifier as $key1 => $value1) {
                                    switch ($value1->name) {
                                        case 'after':
                                            $table->time($value->field_name)->after($value1->value);
                                            break;

                                        case 'comment':
                                            $table->time($value->field_name)->comment($value1->value);
                                            break;

                                        case 'default':
                                            $table->time($value->field_name)->default($value1->value);
                                            break;

                                        case 'first':
                                            $table->time($value->field_name)->first();
                                            break;

                                        case 'storedAs':
                                            $table->time($value->field_name)->storedAs($value1->value);
                                            break;

                                        case 'unsigned':
                                            $table->time($value->field_name)->unsigned();
                                            break;
                                        
                                        case 'virtualAs':
                                            $table->time($value->field_name)->virtualAs($value1->value);
                                            break;

                                        default:
                                            # code...
                                            break;
                                    }
                                }
                            }
                            $table->time($value->field_name)->nullable();
                            break;

                        case 'timeTz':
                            if(is_array($value->data_type_modifier)){
                                foreach ($value->data_type_modifier as $key1 => $value1) {
                                    switch ($value1->name) {
                                        case 'after':
                                            $table->timeTz($value->field_name)->after($value1->value);
                                            break;

                                        case 'comment':
                                            $table->timeTz($value->field_name)->comment($value1->value);
                                            break;

                                        case 'default':
                                            $table->timeTz($value->field_name)->default($value1->value);
                                            break;

                                        case 'first':
                                            $table->timeTz($value->field_name)->first();
                                            break;

                                        case 'storedAs':
                                            $table->timeTz($value->field_name)->storedAs($value1->value);
                                            break;

                                        case 'unsigned':
                                            $table->timeTz($value->field_name)->unsigned();
                                            break;
                                        
                                        case 'virtualAs':
                                            $table->timeTz($value->field_name)->virtualAs($value1->value);
                                            break;

                                        default:
                                            # code...
                                            break;
                                    }
                                }
                            }
                            $table->timeTz($value->field_name)->nullable();
                            break;

                        case 'tinyInteger':
                            if(is_array($value->data_type_modifier)){
                                foreach ($value->data_type_modifier as $key1 => $value1) {
                                    switch ($value1->name) {
                                        case 'after':
                                            $table->tinyInteger($value->field_name)->after($value1->value);
                                            break;

                                        case 'comment':
                                            $table->tinyInteger($value->field_name)->comment($value1->value);
                                            break;

                                        case 'default':
                                            $table->tinyInteger($value->field_name)->default($value1->value);
                                            break;

                                        case 'first':
                                            $table->tinyInteger($value->field_name)->first();
                                            break;

                                        case 'storedAs':
                                            $table->tinyInteger($value->field_name)->storedAs($value1->value);
                                            break;

                                        case 'unsigned':
                                            $table->tinyInteger($value->field_name)->unsigned();
                                            break;
                                        
                                        case 'virtualAs':
                                            $table->tinyInteger($value->field_name)->virtualAs($value1->value);
                                            break;

                                        default:
                                            # code...
                                            break;
                                    }
                                }
                            }
                            $table->tinyInteger($value->field_name)->nullable();
                            break;

                        case 'timestamp':
                            if(is_array($value->data_type_modifier)){
                                foreach ($value->data_type_modifier as $key1 => $value1) {
                                    switch ($value1->name) {
                                        case 'after':
                                            $table->timestamp($value->field_name)->after($value1->value);
                                            break;

                                        case 'comment':
                                            $table->timestamp($value->field_name)->comment($value1->value);
                                            break;

                                        case 'default':
                                            $table->timestamp($value->field_name)->default($value1->value);
                                            break;

                                        case 'first':
                                            $table->timestamp($value->field_name)->first();
                                            break;

                                        case 'storedAs':
                                            $table->timestamp($value->field_name)->storedAs($value1->value);
                                            break;

                                        case 'unsigned':
                                            $table->timestamp($value->field_name)->unsigned();
                                            break;
                                        
                                        case 'virtualAs':
                                            $table->timestamp($value->field_name)->virtualAs($value1->value);
                                            break;

                                        default:
                                            # code...
                                            break;
                                    }
                                }
                            }
                            $table->timestamp($value->field_name)->nullable();
                            break;

                        case 'timestampTz':
                            if(is_array($value->data_type_modifier)){
                                foreach ($value->data_type_modifier as $key1 => $value1) {
                                    switch ($value1->name) {
                                        case 'after':
                                            $table->timestampTz($value->field_name)->after($value1->value);
                                            break;

                                        case 'comment':
                                            $table->timestampTz($value->field_name)->comment($value1->value);
                                            break;

                                        case 'default':
                                            $table->timestampTz($value->field_name)->default($value1->value);
                                            break;

                                        case 'first':
                                            $table->timestampTz($value->field_name)->first();
                                            break;

                                        case 'storedAs':
                                            $table->timestampTz($value->field_name)->storedAs($value1->value);
                                            break;

                                        case 'unsigned':
                                            $table->timestampTz($value->field_name)->unsigned();
                                            break;
                                        
                                        case 'virtualAs':
                                            $table->timestampTz($value->field_name)->virtualAs($value1->value);
                                            break;

                                        default:
                                            # code...
                                            break;
                                    }
                                }
                            }
                            $table->timestampTz($value->field_name)->nullable();
                            break;

                        case 'timestamps':
                            if(is_array($value->data_type_modifier)){
                                foreach ($value->data_type_modifier as $key1 => $value1) {
                                    switch ($value1->name) {
                                        case 'after':
                                            $table->timestamps($value->field_name)->after($value1->value);
                                            break;

                                        case 'comment':
                                            $table->timestamps($value->field_name)->comment($value1->value);
                                            break;

                                        case 'default':
                                            $table->timestamps($value->field_name)->default($value1->value);
                                            break;

                                        case 'first':
                                            $table->timestamps($value->field_name)->first();
                                            break;

                                        case 'storedAs':
                                            $table->timestamps($value->field_name)->storedAs($value1->value);
                                            break;

                                        case 'unsigned':
                                            $table->timestamps($value->field_name)->unsigned();
                                            break;
                                        
                                        case 'virtualAs':
                                            $table->timestamps($value->field_name)->virtualAs($value1->value);
                                            break;

                                        default:
                                            # code...
                                            break;
                                    }
                                }
                            }
                            $table->timestamps($value->field_name)->nullable();
                            break;

                        case 'timestampsTz':
                            if(is_array($value->data_type_modifier)){
                                foreach ($value->data_type_modifier as $key1 => $value1) {
                                    switch ($value1->name) {
                                        case 'after':
                                            $table->timestampsTz($value->field_name)->after($value1->value);
                                            break;

                                        case 'comment':
                                            $table->timestampsTz($value->field_name)->comment($value1->value);
                                            break;

                                        case 'default':
                                            $table->timestampsTz($value->field_name)->default($value1->value);
                                            break;

                                        case 'first':
                                            $table->timestampsTz($value->field_name)->first();
                                            break;

                                        case 'storedAs':
                                            $table->timestampsTz($value->field_name)->storedAs($value1->value);
                                            break;

                                        case 'unsigned':
                                            $table->timestampsTz($value->field_name)->unsigned();
                                            break;
                                        
                                        case 'virtualAs':
                                            $table->timestampsTz($value->field_name)->virtualAs($value1->value);
                                            break;

                                        default:
                                            # code...
                                            break;
                                    }
                                }
                            }
                            $table->timestampsTz($value->field_name)->nullable();
                            break;

                        case 'unsignedBigInteger':
                            if(is_array($value->data_type_modifier)){
                                foreach ($value->data_type_modifier as $key1 => $value1) {
                                    switch ($value1->name) {
                                        case 'after':
                                            $table->unsignedBigInteger($value->field_name)->after($value1->value);
                                            break;

                                        case 'comment':
                                            $table->unsignedBigInteger($value->field_name)->comment($value1->value);
                                            break;

                                        case 'default':
                                            $table->unsignedBigInteger($value->field_name)->default($value1->value);
                                            break;

                                        case 'first':
                                            $table->unsignedBigInteger($value->field_name)->first();
                                            break;

                                        case 'storedAs':
                                            $table->unsignedBigInteger($value->field_name)->storedAs($value1->value);
                                            break;

                                        case 'unsigned':
                                            $table->unsignedBigInteger($value->field_name)->unsigned();
                                            break;
                                        
                                        case 'virtualAs':
                                            $table->unsignedBigInteger($value->field_name)->virtualAs($value1->value);
                                            break;

                                        default:
                                            # code...
                                            break;
                                    }
                                }
                            }
                            $table->unsignedBigInteger($value->field_name)->nullable();
                            break;

                        case 'unsignedInteger':
                            if(is_array($value->data_type_modifier)){
                                foreach ($value->data_type_modifier as $key1 => $value1) {
                                    switch ($value1->name) {
                                        case 'after':
                                            $table->unsignedInteger($value->field_name)->after($value1->value);
                                            break;

                                        case 'comment':
                                            $table->unsignedInteger($value->field_name)->comment($value1->value);
                                            break;

                                        case 'default':
                                            $table->unsignedInteger($value->field_name)->default($value1->value);
                                            break;

                                        case 'first':
                                            $table->unsignedInteger($value->field_name)->first();
                                            break;

                                        case 'storedAs':
                                            $table->unsignedInteger($value->field_name)->storedAs($value1->value);
                                            break;

                                        case 'unsigned':
                                            $table->unsignedInteger($value->field_name)->unsigned();
                                            break;
                                        
                                        case 'virtualAs':
                                            $table->unsignedInteger($value->field_name)->virtualAs($value1->value);
                                            break;

                                        default:
                                            # code...
                                            break;
                                    }
                                }
                            }
                            $table->unsignedInteger($value->field_name)->nullable();
                            break;

                        case 'unsignedMediumInteger':
                            if(is_array($value->data_type_modifier)){
                                foreach ($value->data_type_modifier as $key1 => $value1) {
                                    switch ($value1->name) {
                                        case 'after':
                                            $table->unsignedMediumInteger($value->field_name)->after($value1->value);
                                            break;

                                        case 'comment':
                                            $table->unsignedMediumInteger($value->field_name)->comment($value1->value);
                                            break;

                                        case 'default':
                                            $table->unsignedMediumInteger($value->field_name)->default($value1->value);
                                            break;

                                        case 'first':
                                            $table->unsignedMediumInteger($value->field_name)->first();
                                            break;

                                        case 'storedAs':
                                            $table->unsignedMediumInteger($value->field_name)->storedAs($value1->value);
                                            break;

                                        case 'unsigned':
                                            $table->unsignedMediumInteger($value->field_name)->unsigned();
                                            break;
                                        
                                        case 'virtualAs':
                                            $table->unsignedMediumInteger($value->field_name)->virtualAs($value1->value);
                                            break;

                                        default:
                                            # code...
                                            break;
                                    }
                                }
                            }
                            $table->unsignedMediumInteger($value->field_name)->nullable();
                            break;

                        case 'unsignedSmallInteger':
                            if(is_array($value->data_type_modifier)){
                                foreach ($value->data_type_modifier as $key1 => $value1) {
                                    switch ($value1->name) {
                                        case 'after':
                                            $table->unsignedSmallInteger($value->field_name)->after($value1->value);
                                            break;

                                        case 'comment':
                                            $table->unsignedSmallInteger($value->field_name)->comment($value1->value);
                                            break;

                                        case 'default':
                                            $table->unsignedSmallInteger($value->field_name)->default($value1->value);
                                            break;

                                        case 'first':
                                            $table->unsignedSmallInteger($value->field_name)->first();
                                            break;

                                        case 'storedAs':
                                            $table->unsignedSmallInteger($value->field_name)->storedAs($value1->value);
                                            break;

                                        case 'unsigned':
                                            $table->unsignedSmallInteger($value->field_name)->unsigned();
                                            break;
                                        
                                        case 'virtualAs':
                                            $table->unsignedSmallInteger($value->field_name)->virtualAs($value1->value);
                                            break;

                                        default:
                                            # code...
                                            break;
                                    }
                                }
                            }
                            $table->unsignedSmallInteger($value->field_name)->nullable();
                            break;

                        case 'unsignedTinyInteger':
                            if(is_array($value->data_type_modifier)){
                                foreach ($value->data_type_modifier as $key1 => $value1) {
                                    switch ($value1->name) {
                                        case 'after':
                                            $table->unsignedTinyInteger($value->field_name)->after($value1->value);
                                            break;

                                        case 'comment':
                                            $table->unsignedTinyInteger($value->field_name)->comment($value1->value);
                                            break;

                                        case 'default':
                                            $table->unsignedTinyInteger($value->field_name)->default($value1->value);
                                            break;

                                        case 'first':
                                            $table->unsignedTinyInteger($value->field_name)->first();
                                            break;

                                        case 'storedAs':
                                            $table->unsignedTinyInteger($value->field_name)->storedAs($value1->value);
                                            break;

                                        case 'unsigned':
                                            $table->unsignedTinyInteger($value->field_name)->unsigned();
                                            break;
                                        
                                        case 'virtualAs':
                                            $table->unsignedTinyInteger($value->field_name)->virtualAs($value1->value);
                                            break;

                                        default:
                                            # code...
                                            break;
                                    }
                                }
                            }
                            $table->unsignedTinyInteger($value->field_name)->nullable();
                            break;

                        case 'uuid':
                            if(is_array($value->data_type_modifier)){
                                foreach ($value->data_type_modifier as $key1 => $value1) {
                                    switch ($value1->name) {
                                        case 'after':
                                            $table->uuid($value->field_name)->after($value1->value);
                                            break;

                                        case 'comment':
                                            $table->uuid($value->field_name)->comment($value1->value);
                                            break;

                                        case 'default':
                                            $table->uuid($value->field_name)->default($value1->value);
                                            break;

                                        case 'first':
                                            $table->uuid($value->field_name)->first();
                                            break;

                                        case 'storedAs':
                                            $table->uuid($value->field_name)->storedAs($value1->value);
                                            break;

                                        case 'unsigned':
                                            $table->uuid($value->field_name)->unsigned();
                                            break;
                                        
                                        case 'virtualAs':
                                            $table->uuid($value->field_name)->virtualAs($value1->value);
                                            break;

                                        default:
                                            # code...
                                            break;
                                    }
                                }
                            }
                            $table->uuid($value->field_name)->nullable();
                            break;

                        default:
                            # code...
                            break;
                    }
                }
                if(is_array($value->my_sql_index)){
                    foreach ($value->my_sql_index as $key1 => $value1) {
                        switch ($value1->name) {
                            case 'primary':
                                $table->primary($value->field_name);
                                break;

                            case 'unique':
                                $table->unique($value->field_name);
                                break;

                            case 'index':
                                $table->index($value->field_name);
                                break;

                            default:
                                # code...
                                break;
                        }
                    }
                }
                if(is_array($composite_index)){
                    foreach ($composite_index as $key => $value) {
                        switch ($value->name) {
                            case 'primary':
                                $table->primary($value->value);
                                break;

                            case 'unique':
                                $table->unique($value->value);
                                break;

                            default:
                                # code...
                                break;
                        }
                    }
                }
            $table->timestamps();
        });

        $fillable = "";
        $hidden = "";
        foreach ($fields as $key => $value) {
            switch ($value->elequent_array) {
                case 'visibles':
                    $fillable = $fillable.$value->field_name.", ";
                    break;
                
                case 'hidden':
                    $hidden = $hidden.$value->field_name.", ";
                    break;
                
                default:
                    $fillable = $fillable.$value->field_name.", ";
                    $hidden = $hidden.$value->field_name.", ";
                    break;
            }
        }
        $myfile = fopen(app_path() ."/Models/" .$newtable.".php", "w");
        $line1 = "<?php\n";
        $line2 = "namespace App\Models;\n";
        $line3 = "use Illuminate\Database\Eloquent\Model;\n";
        $line4 = "class ".$newtable." extends Model\n";
        $line5 = "{\n";
        $line6 = "public $" ."table = '" .$newtable ."';\n";
        $line7 = "protected $" ."fillable = [\n";
        $line8 = $fillable."];\n";
        $line9 = "protected $" ."hidden = [\n".$hidden."];\n";
        $line10 = "}\n";

        $txt = $line1.$line2.$line3.$line4.$line5.$line6.$line7.$line8.$line9.$line10;

        fwrite($myfile, $txt);
        fclose($myfile);
        return DB::table($newtable)->get();
    }

    public function deleteTable($table)
    {
        \Log::Info("deleteTable");
        Schema::dropIfExists($table);
        return ['success'];
    }
    
}
